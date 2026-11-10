<?php

namespace App\Http\Controllers\EduQuiz;

use App\Models\EduQuiz\Answer;
use App\Models\EduQuiz\Quiz;
use App\Models\EduQuiz\Room;
use App\Services\CourseRegistrationQrCode;
use App\Services\EduQuiz\Access;
use App\Services\EduQuiz\Contact;
use App\Services\EduQuiz\Play;
use App\Services\EduQuiz\Privacy;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RoomController
{
    public function __construct(private Play $play) {}

    public function index(Request $request)
    {
        return view('eduquiz.admin.rooms', [
            'quizzes' => Access::scope(Quiz::query(), $request->user())->whereHas('questions')->orderBy('title')->get(['id', 'title']),
            'rooms' => Access::scope(Room::query(), $request->user())->latest('id')->paginate(20),
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'quiz_id' => ['required', 'integer'],
            'mode' => ['required', 'in:live,self'],
            'duration_minutes' => ['required_if:mode,self', 'nullable', 'integer', 'between:1,240'],
            'attempt_limit' => ['required_if:mode,self', 'nullable', 'integer', 'between:1,10'],
            'requires_invite' => ['sometimes', 'accepted'],
        ]);

        $room = DB::transaction(function () use ($request, $data) {
            $quiz = Quiz::lockForUpdate()->findOrFail($data['quiz_id']);
            Access::authorize($quiz, $request->user());
            $quiz->load(['course', 'questions.options']);
            if ($quiz->questions->isEmpty()) {
                throw ValidationException::withMessages(['quiz_id' => 'Bộ đề chưa có câu hỏi.']);
            }

            $attributes = [
                'owner_id' => $request->user()->id,
                'quiz_id' => $quiz->id,
                'course_id' => $quiz->course_id,
                'title' => $quiz->title,
                'course_title' => $quiz->course?->title,
                'mode' => $data['mode'],
                'questions' => $quiz->questions->map->snapshot()->all(),
                'duration_seconds' => $data['mode'] === 'self' ? $data['duration_minutes'] * 60 : 0,
                'attempt_limit' => $data['mode'] === 'self' ? $data['attempt_limit'] : 1,
                'requires_invite' => isset($data['requires_invite']),
            ];

            return retry(5, fn () => Room::create([
                ...$attributes,
                'pin' => (string) random_int(10000000, 99999999),
            ]), 0, fn (\Throwable $error) => $error instanceof UniqueConstraintViolationException);
        }, 3);

        return redirect()->route('eduquiz.staff.rooms.show', $room);
    }

    public function show(Request $request, Room $room)
    {
        Access::authorize($room, $request->user());

        return view('eduquiz.admin.room', [
            'room' => $room,
            'joinUrl' => route('eduquiz.room', $room->pin),
        ]);
    }

    public function delete(Request $request, Room $room)
    {
        Access::authorize($room, $request->user());
        $this->play->deleteRoom($room);

        return redirect()->route('eduquiz.staff.rooms')->with('notice', 'Đã xóa phòng thi.');
    }

    public function deleteAll(Request $request)
    {
        $count = 0;
        Access::scope(Room::query(), $request->user())->select('id')->eachById(function (Room $room) use (&$count) {
            if ($this->play->deleteRoom($room)) {
                $count++;
            }
        });

        return redirect()->route('eduquiz.staff.rooms')->with('notice', "Đã xóa {$count} phòng thi.");
    }

    public function qr(Request $request, Room $room, CourseRegistrationQrCode $qr)
    {
        Access::authorize($room, $request->user());
        $result = $qr->renderUrl(route('eduquiz.room', $room->pin), 'svg');

        return response($result->getString())->header('Content-Type', $result->getMimeType());
    }

    public function control(Request $request, Room $room)
    {
        Access::authorize($room, $request->user());
        $data = $request->validate([
            'action' => ['required', 'in:start,pause,resume,close,reveal,next,finish'],
            'version' => ['required', 'integer', 'min:0'],
        ]);
        $this->play->control($room, $data['action'], (int) $data['version']);

        return response()->noContent();
    }

    public function state(Request $request, Room $room)
    {
        Access::authorize($room, $request->user());
        $phase = $room->phase();
        $question = $room->mode === 'live' && ! in_array($phase, ['lobby', 'finished'], true)
            ? $room->questions[$room->current]
            : null;
        $attemptIds = $this->play->attempts($room)->select('id');
        $members = $room->participants()->count();
        $counts = [];
        $answered = 0;
        if ($question) {
            $counts = array_fill(0, count($question['options']), 0);
            $answers = Answer::whereIn('attempt_id', $attemptIds)->where('position', $room->current);
            if (in_array($phase, ['closed', 'revealed'], true)) {
                foreach ($answers->select('id', 'choices')->lazyById() as $answer) {
                    $answered++;
                    foreach ($answer->choices as $choice) {
                        $counts[$choice]++;
                    }
                }
            } else {
                $answered = $answers->count();
            }
        }

        $leaders = $room->mode === 'live' && in_array($phase, ['closed', 'revealed', 'finished'], true)
            ? DB::table('eduquiz_session_participants as a')
                ->whereIn('a.id', $attemptIds)
                ->leftJoin('eduquiz_answers as answers', 'answers.attempt_id', '=', 'a.id')
                ->groupBy('a.participant_id', 'a.id')
                ->selectRaw('a.participant_id, a.id as attempt_id, coalesce(sum(answers.correct), 0) as score,
                    count(answers.id) as answered, sum(answers.elapsed_seconds) as seconds')
                ->orderByDesc('score')->orderByDesc('answered')->orderBy('seconds')->orderBy('attempt_id')->limit(10)->get()
                ->map(fn ($row) => ['label' => 'Học viên #'.$row->participant_id, 'score' => (int) $row->score])
            : [];

        return response()->json([
            'phase' => $phase,
            'mode' => $room->mode,
            'members' => $members,
            'answered' => $answered,
            'total' => count($room->questions),
            'version' => $room->version,
            'now' => now()->toIso8601String(),
            'deadline' => $room->deadline?->toIso8601String(),
            'pausedSeconds' => $phase === 'paused' ? $room->paused_seconds : null,
            'questions' => $question ? [[
                'position' => $room->current,
                'prompt' => $question['prompt'],
                'options' => array_column($question['options'], 'text'),
                'image' => $question['image'] ? route('eduquiz.staff.rooms.image', [$room, $room->current]) : null,
                'correct' => $phase === 'revealed' ? array_keys(array_filter(array_column($question['options'], 'correct'))) : null,
                'explanation' => $phase === 'revealed' ? $question['explanation'] : null,
            ]] : [],
            'counts' => $counts,
            'leaders' => $leaders,
            'completed' => $phase === 'finished' || $room->mode === 'self'
                ? $this->play->attempts($room)->whereNotNull('finished_at')->count()
                : null,
        ]);
    }

    public function image(Request $request, Room $room, int $position)
    {
        Access::authorize($room, $request->user());
        $path = $room->questions[$position]['image'] ?? null;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path);
    }

    public function invite(Request $request, Room $room)
    {
        Access::authorize($room, $request->user());
        $data = $request->validate(['contact' => ['required', 'string', 'max:254']]);
        $contact = filter_var($data['contact'], FILTER_VALIDATE_EMAIL)
            ? Contact::email($data['contact'])
            : Contact::phone($data['contact']);
        $token = Str::random(32);

        DB::transaction(function () use ($room, $contact, $token) {
            $room = Room::lockForUpdate()->findOrFail($room->id);
            abort_unless($room->requires_invite && ! $room->finished_at, 409);
            $exists = DB::table('eduquiz_invitations')->where('session_id', $room->id)->where('contact', $contact)->whereNotNull('used_at')->exists();
            if ($exists) {
                throw ValidationException::withMessages(['contact' => 'Mã mời của liên hệ này đã được sử dụng.']);
            }
            DB::table('eduquiz_invitations')->updateOrInsert(['session_id' => $room->id, 'contact' => $contact], [
                'token_hash' => hash('sha256', $token), 'created_at' => now(), 'updated_at' => now(),
            ]);
        });

        return back()->with('invite_code', $token);
    }

    public function anonymize(Request $request, Room $room, Privacy $privacy)
    {
        abort_if(Access::instructor($request->user()), 403);
        $privacy->anonymize($room);

        return back()->with('notice', 'Đã xóa thông tin định danh và thu hồi quyền truy cập của học viên.');
    }
}
