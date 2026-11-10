<?php

namespace App\Http\Controllers\EduQuiz;

use App\Models\Course;
use App\Models\EduQuiz\Question;
use App\Models\EduQuiz\Quiz;
use App\Models\User;
use App\Services\EduQuiz\Access;
use App\Services\EduQuiz\QuestionInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LibraryController
{
    public function __construct(private QuestionInput $input) {}

    public function questions(Request $request)
    {
        $query = Access::scope(Question::query(), $request->user())->withCount('options')->latest('id');
        if ($search = $request->string('q')->trim()->limit(150)->value()) {
            $query->whereLike('prompt', '%'.$search.'%');
        }

        return view('eduquiz.admin.questions', ['questions' => $query->paginate(20)->withQueryString()]);
    }

    public function editQuestion(Request $request, ?Question $question = null)
    {
        if ($question) {
            Access::authorize($question, $request->user());
        }

        return view('eduquiz.admin.question', [
            'question' => $question?->load('options'),
            'revisions' => $question
                ? DB::table('eduquiz_question_revisions')->where('question_id', $question->id)
                    ->leftJoin('users', 'users.id', '=', 'eduquiz_question_revisions.editor_id')
                    ->latest('eduquiz_question_revisions.id')
                    ->select('content', 'eduquiz_question_revisions.created_at', 'users.name')->paginate(10)
                : collect(),
        ]);
    }

    public function saveQuestion(Request $request, ?Question $question = null)
    {
        if ($question) {
            Access::authorize($question, $request->user());
        }
        $request->validate(['image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'], 'options' => ['required', 'array', 'max:6']]);
        [$attributes, $options] = $this->input->validate($request->all());
        if ($image = $request->file('image')) {
            $attributes['image'] = $image->store('eduquiz/questions', 'local');
            abort_unless($attributes['image'], 500, 'Không lưu được ảnh.');
        } elseif ($request->boolean('remove_image')) {
            $attributes['image'] = null;
        }

        $question ??= new Question(['owner_id' => $request->user()->id]);
        try {
            $question->revise($attributes, $options, $request->user()->id);
        } catch (\Throwable $exception) {
            if ($image) {
                Storage::disk('local')->delete($attributes['image']);
            }
            throw $exception;
        }

        return redirect()->route('eduquiz.staff.questions.edit', $question)->with('notice', 'Đã lưu câu hỏi và lịch sử chỉnh sửa.');
    }

    public function duplicate(Request $request, Question $question)
    {
        Access::authorize($question, $request->user());
        $snapshot = $question->snapshot();
        $options = $snapshot['options'];
        unset($snapshot['options']);
        $snapshot['prompt'] = mb_substr('Bản sao · '.$snapshot['prompt'], 0, 2500);
        $copy = new Question(['owner_id' => $request->user()->id]);
        $copy->revise($snapshot, $options, $request->user()->id);

        return redirect()->route('eduquiz.staff.questions.edit', $copy)->with('notice', 'Đã nhân bản câu hỏi.');
    }

    public function image(Request $request, Question $question)
    {
        Access::authorize($question, $request->user());
        abort_unless($question->image && Storage::disk('local')->exists($question->image), 404);

        return Storage::disk('local')->response($question->image);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => ['required', 'file', 'mimes:xlsx,csv,txt', 'extensions:xlsx,csv', 'max:3072']]);
        $file = $request->file('file');
        $rows = $this->input->read($file->getRealPath(), strtolower($file->getClientOriginalExtension()));
        $editor = $request->user()->id;
        DB::transaction(function () use ($rows, $editor) {
            foreach ($rows as [$attributes, $options]) {
                (new Question(['owner_id' => $editor]))->revise($attributes, $options, $editor);
            }
        });

        return back()->with('notice', 'Đã nhập '.count($rows).' câu hỏi.');
    }

    public function template()
    {
        return response()->streamDownload(fn () => $this->input->template()->save('php://output'), 'eduquiz-cau-hoi-mau.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function quizzes(Request $request)
    {
        $quizzes = Access::scope(Quiz::query(), $request->user())->with('course')->withCount('questions')->latest('id');
        if ($request->integer('course')) {
            $quizzes->where('course_id', $request->integer('course'));
        }

        return view('eduquiz.admin.quizzes', ['quizzes' => $quizzes->paginate(20)->withQueryString()]);
    }

    public function courses(Request $request)
    {
        $quizzes = Access::scope(Quiz::query(), $request->user())->selectRaw('count(*)')->whereColumn('course_id', 'courses.id');

        return view('eduquiz.admin.courses', [
            'courses' => Course::select('id', 'title')->addSelect(['quiz_count' => $quizzes])->orderBy('title')->paginate(30),
        ]);
    }

    public function editQuiz(Request $request, ?Quiz $quiz = null)
    {
        if ($quiz) {
            Access::authorize($quiz, $request->user());
        }

        return view('eduquiz.admin.quiz', [
            'quiz' => $quiz?->load('questions:id,prompt'),
            'questions' => Access::scope(Question::query(), $this->quizOwner($request, $quiz))->latest('id')->get(['id', 'prompt']),
            'courses' => Course::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function saveQuiz(Request $request, ?Quiz $quiz = null)
    {
        if ($quiz) {
            Access::authorize($quiz, $request->user());
        }
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'questions' => ['required', 'array', 'min:1', 'max:100'],
            'questions.*' => ['required', 'integer', 'distinct'],
        ]);
        $ids = array_values($data['questions']);
        if (Access::scope(Question::query(), $this->quizOwner($request, $quiz))->whereKey($ids)->count() !== count($ids)) {
            throw ValidationException::withMessages(['questions' => 'Có câu hỏi không còn khả dụng.']);
        }

        $quiz ??= new Quiz(['owner_id' => $request->user()->id]);
        DB::transaction(function () use ($data, $ids, $quiz) {
            if ($quiz->exists) {
                Quiz::lockForUpdate()->findOrFail($quiz->id);
            }
            $quiz->fill(['title' => $data['title'], 'course_id' => $data['course_id'] ?? null])->save();
            $positions = [];
            foreach ($ids as $position => $id) {
                $positions[$id] = ['position' => $position];
            }

            // Rebuild positions together to support swaps under the unique index.
            $quiz->questions()->detach();
            $quiz->questions()->attach($positions);
        }, 3);

        return redirect()->route('eduquiz.staff.quizzes.edit', $quiz)->with('notice', 'Đã lưu bộ đề.');
    }

    private function quizOwner(Request $request, ?Quiz $quiz): User
    {
        return $quiz && $quiz->owner_id !== $request->user()->id
            ? User::findOrFail($quiz->owner_id)
            : $request->user();
    }
}
