@extends('eduquiz.layout', ['title' => 'Ngân hàng câu hỏi', 'staff' => true])

@section('content')
<header class="section-head">
    <div><p class="eyebrow">NỘI DUNG ĐÀO TẠO</p><h1>Ngân hàng câu hỏi</h1></div>
    <a class="button primary" href="{{ route('eduquiz.staff.questions.edit') }}">Thêm câu hỏi</a>
</header>
<details class="card">
    <summary>Nhập câu hỏi từ Excel hoặc CSV</summary>
    <p class="muted">Dùng đúng hàng tiêu đề trong tệp mẫu. Cột <code>correct</code> nhận A–F, ví dụ <code>A,C</code>. Tối đa 500 dòng mỗi lần nhập.</p>
    <form class="actions" method="post" enctype="multipart/form-data" action="{{ route('eduquiz.staff.questions.import') }}">
        @csrf
        <label>Tệp câu hỏi<input type="file" name="file" accept=".xlsx,.csv" required></label>
        <button class="button primary">Nhập tệp</button>
        <a href="{{ route('eduquiz.staff.questions.template') }}">Tải tệp mẫu</a>
    </form>
</details>
<section class="card">
    <form class="actions" method="get">
        <label>Tìm câu hỏi<input type="search" name="q" value="{{ request('q') }}" maxlength="150"></label>
        <button class="button quiet">Tìm kiếm</button>
    </form>
    <ul class="record-list">
        @forelse ($questions as $question)
            <li>
                <div><a href="{{ route('eduquiz.staff.questions.edit', $question) }}">{{ Str::limit($question->prompt, 160) }}</a><br><span class="muted small">{{ $question->options_count }} đáp án · {{ $question->seconds }} giây</span></div>
                <form method="post" action="{{ route('eduquiz.staff.questions.copy', $question) }}">@csrf<button class="button quiet">Nhân bản</button></form>
            </li>
        @empty
            <li>Chưa có câu hỏi phù hợp.</li>
        @endforelse
    </ul>
    @include('eduquiz.admin.pagination', ['page' => $questions])
</section>
@endsection

