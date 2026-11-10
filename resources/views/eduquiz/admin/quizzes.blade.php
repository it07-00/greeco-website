@extends('eduquiz.layout', ['title' => 'Bộ đề thi', 'staff' => true])

@section('content')
<header class="section-head">
    <div><p class="eyebrow">NỘI DUNG ĐÀO TẠO</p><h1>Bộ đề thi</h1></div>
    <div class="actions">
        <a class="button quiet" href="{{ route('eduquiz.staff.guide') }}">Quy trình</a>
        <a class="button primary" href="{{ route('eduquiz.staff.quizzes.edit', ['course' => request('course')]) }}">Tạo bộ đề</a>
    </div>
</header>
<section class="card">
    @if (request('course'))<a href="{{ route('eduquiz.staff.quizzes') }}">Hiện tất cả bộ đề</a>@endif
    <ul class="record-list">
        @forelse ($quizzes as $quiz)
            <li>
                <div><a href="{{ route('eduquiz.staff.quizzes.edit', $quiz) }}">{{ $quiz->title }}</a><br><span class="muted small">{{ $quiz->questions_count }} câu · {{ $quiz->course?->title ?? 'Chưa gán khóa' }}</span></div>
                <form method="post" action="{{ route('eduquiz.staff.quizzes.delete', $quiz) }}"
                    data-confirm="Xóa bộ đề này? Ngân hàng câu hỏi, các phiên thi đã tạo và kết quả vẫn được giữ.">
                    @csrf
                    @method('DELETE')
                    <button class="button danger">Xóa bộ đề</button>
                </form>
            </li>
        @empty
            <li>Chưa có bộ đề.</li>
        @endforelse
    </ul>
    @include('eduquiz.admin.pagination', ['page' => $quizzes])
</section>
@if ($quizzes->total())
    <details class="card">
        <summary>Xóa tất cả bộ đề</summary>
        <p>Xóa mọi bộ đề bạn có quyền quản lý, kể cả ngoài khóa đang lọc. Ngân hàng câu hỏi, các phiên thi đã tạo và kết quả vẫn được giữ.</p>
        <form method="post" action="{{ route('eduquiz.staff.quizzes.delete-all') }}" data-confirm="Bạn có chắc muốn xóa tất cả bộ đề trong phạm vi quản lý, kể cả ngoài khóa đang lọc?">
            @csrf
            @method('DELETE')
            <button class="button danger">Xác nhận xóa tất cả bộ đề</button>
        </form>
    </details>
@endif
@endsection

