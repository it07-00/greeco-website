@extends('eduquiz.layout', ['title' => $quiz ? 'Sửa bộ đề' : 'Tạo bộ đề', 'staff' => true])

@section('content')
<header class="section-head">
    <div><a class="back" href="{{ route('eduquiz.staff.quizzes') }}">← Bộ đề thi</a><h1>{{ $quiz ? 'Sửa bộ đề' : 'Tạo bộ đề' }}</h1></div>
</header>
<form class="card stack" method="post" action="{{ route('eduquiz.staff.quizzes.save', $quiz) }}">
    @csrf
    <div class="field-grid">
        <label>Tên bộ đề<input name="title" value="{{ old('title', $quiz?->title) }}" maxlength="255" required></label>
        <label>Khóa đào tạo
            <select name="course_id">
                <option value="">Chưa gán khóa</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $quiz?->course_id ?? request('course')) == $course->id)>{{ $course->title }}</option>
                @endforeach
            </select>
        </label>
    </div>
    <section data-quiz-builder class="stack">
        <h2>Thứ tự câu hỏi</h2>
        <p class="muted">Chọn câu từ ngân hàng rồi dùng nút Lên hoặc Xuống để sắp xếp.</p>
        <div class="actions">
            <label>Chọn câu hỏi
                <select data-question-picker>
                    <option value="">Chọn một câu</option>
                    @foreach ($questions as $question)
                        <option value="{{ $question->id }}">{{ Str::limit($question->prompt, 160) }}</option>
                    @endforeach
                </select>
            </label>
            <button type="button" class="button quiet" data-add-question>Thêm vào đề</button>
        </div>
        @if ($questions->isEmpty())
            <p>Chưa có câu hỏi. <a href="{{ route('eduquiz.staff.questions.edit') }}">Tạo câu hỏi trước</a>.</p>
        @endif
        <p class="small" data-builder-feedback role="status"></p>
        @php($questionList = $questions->keyBy('id'))
        <ol class="builder-list" data-question-list>
            @foreach (old('questions', $quiz?->questions->modelKeys() ?? []) as $id)
                @if ($item = $questionList->get($id))
                    <li><input type="hidden" name="questions[]" value="{{ $item->id }}"><span>{{ Str::limit($item->prompt, 160) }}</span>
                        <div class="actions">
                            <button class="button quiet" type="button" data-order="up">Lên</button>
                            <button class="button quiet" type="button" data-order="down">Xuống</button>
                            <button class="button quiet" type="button" data-order="remove">Bỏ</button>
                        </div>
                    </li>
                @endif
            @endforeach
        </ol>
    </section>
    <div class="actions"><button class="button primary">Lưu bộ đề</button></div>
</form>
@endsection

