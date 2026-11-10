@extends('eduquiz.layout', ['title' => 'Bộ đề thi', 'staff' => true])

@section('content')
<header class="section-head">
    <div><p class="eyebrow">NỘI DUNG ĐÀO TẠO</p><h1>Bộ đề thi</h1></div>
    <a class="button primary" href="{{ route('eduquiz.staff.quizzes.edit', ['course' => request('course')]) }}">Tạo bộ đề</a>
</header>
<section class="card">
    @if (request('course'))<a href="{{ route('eduquiz.staff.quizzes') }}">Hiện tất cả bộ đề</a>@endif
    <ul class="record-list">
        @forelse ($quizzes as $quiz)
            <li><a href="{{ route('eduquiz.staff.quizzes.edit', $quiz) }}">{{ $quiz->title }}</a><span class="muted">{{ $quiz->questions_count }} câu · {{ $quiz->course?->title ?? 'Chưa gán khóa' }}</span></li>
        @empty
            <li>Chưa có bộ đề.</li>
        @endforelse
    </ul>
    @include('eduquiz.admin.pagination', ['page' => $quizzes])
</section>
@endsection

