@extends('eduquiz.layout', ['title' => 'Quản lý trắc nghiệm', 'staff' => true])

@section('content')
<header class="section-head">
    <div>
        <p class="eyebrow">KHU VỰC GIẢNG VIÊN</p>
        <h1>Quản lý trắc nghiệm</h1>
        <p class="muted">Soạn câu hỏi, tổ chức phòng thi và theo dõi kết quả đào tạo.</p>
    </div>
    <div class="actions">
        <a class="button quiet" href="{{ route('eduquiz.staff.questions.edit') }}">Thêm câu hỏi</a>
        <a class="button primary" href="{{ route('eduquiz.staff.rooms') }}">Tạo phiên thi</a>
    </div>
</header>
<div class="metrics">
    @foreach ($counts as $label => $number)
        <div class="card metric">
            <span class="muted">{{ $label }}</span>
            <strong>{{ $number }}</strong>
        </div>
    @endforeach
</div>
<section class="card">
    <div class="card-header">
        <h2>Phiên thi gần đây</h2>
        <a class="small" href="{{ route('eduquiz.staff.rooms') }}">Xem tất cả →</a>
    </div>
    <ul class="record-list">
        @forelse ($rooms as $room)
            <li>
                <div>
                    <a href="{{ route('eduquiz.staff.rooms.show', $room) }}">{{ $room->title }}</a>
                    <br><span class="muted small">{{ $room->mode === 'live' ? 'Trực tiếp' : 'Tự làm' }} · {{ $room->finished_at ? 'Đã kết thúc' : 'Đang mở' }}</span>
                </div>
                <span class="status-badge">PIN {{ $room->pin }}</span>
            </li>
        @empty
            <li>Chưa có phiên thi. Hãy bắt đầu từ <a href="{{ route('eduquiz.staff.questions') }}">ngân hàng câu hỏi</a>.</li>
        @endforelse
    </ul>
</section>
@endsection
