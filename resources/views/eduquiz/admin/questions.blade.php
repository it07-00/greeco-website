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
                <div class="actions">
                    <form method="post" action="{{ route('eduquiz.staff.questions.copy', $question) }}">@csrf<button class="button quiet">Nhân bản</button></form>
                    <form method="post" action="{{ route('eduquiz.staff.questions.delete', $question) }}"
                        data-confirm="Xóa câu hỏi này khỏi ngân hàng và các bộ đề? Các phiên thi đã tạo vẫn giữ nguyên.">
                        @csrf
                        @method('DELETE')
                        <button class="button danger">Xóa câu hỏi</button>
                    </form>
                </div>
            </li>
        @empty
            <li>Chưa có câu hỏi phù hợp.</li>
        @endforelse
    </ul>
    @include('eduquiz.admin.pagination', ['page' => $questions])
</section>
@if ($questions->total())
    <details class="card">
        <summary>Xóa tất cả câu hỏi</summary>
        <p>Xóa mọi câu hỏi bạn có quyền quản lý, kể cả ngoài kết quả tìm kiếm. Các bộ đề sẽ mất những câu đã xóa; các phiên thi đã tạo và kết quả vẫn được giữ.</p>
        <form method="post" action="{{ route('eduquiz.staff.questions.delete-all') }}" data-confirm="Bạn có chắc muốn xóa tất cả câu hỏi trong phạm vi quản lý, kể cả ngoài kết quả tìm kiếm?">
            @csrf
            @method('DELETE')
            <button class="button danger">Xác nhận xóa tất cả câu hỏi</button>
        </form>
    </details>
@endif
@endsection

