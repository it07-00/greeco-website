@extends('eduquiz.layout', ['title' => $room->title, 'staff' => true])

@section('content')
<section class="card arena-shell" data-quiz="host" data-state="{{ route('eduquiz.staff.rooms.state', $room) }}"
    data-control="{{ route('eduquiz.staff.rooms.control', $room) }}">
    <header class="section-head">
        <div>
            <p class="eyebrow">{{ $room->mode === 'live' ? 'EDUQUIZ LIVE' : 'BÀI KIỂM TRA TỰ LÀM' }} · PIN {{ $room->pin }}</p>
            <h1>{{ $room->title }}</h1>
            <p class="muted">{{ $room->course_title ?? 'Màn hình điều khiển của giảng viên' }}</p>
        </div>
        <p class="status-badge" data-phase>Đang kết nối</p>
    </header>
    <nav class="view-switch" aria-label="Màn hình phòng thi">
        <span aria-current="page">Màn hình giảng viên</span>
        <a href="{{ $joinUrl }}" target="_blank" rel="noopener">Điện thoại học viên ↗</a>
    </nav>
    <div class="arena-meta" data-meta hidden>
        <span data-count></span>
        <span data-timer></span>
    </div>
    <p class="quiz-feedback" data-feedback role="status"></p>
    <div data-board><div class="arena placeholder"><h2>Đang kết nối</h2><p>Đang tải nội dung phòng thi…</p></div></div>
    <div class="actions control-bar" data-actions></div>
    <section class="chart-panel" data-chart hidden aria-label="Tỷ lệ lựa chọn đáp án"></section>
    <section class="chart-panel" data-leaders hidden aria-label="Bảng xếp hạng"></section>
    <noscript><p class="notice error">Bật JavaScript để điều khiển phòng thi.</p></noscript>
</section>

<div class="field-grid room-details">
    <section class="card">
        <div class="card-header">
            <h2>Mời học viên tham gia</h2>
            <span class="status-badge">{{ $room->requires_invite ? 'Yêu cầu mã mời' : 'Mở tự do theo PIN' }}</span>
        </div>
        <div class="join-info">
            <img class="qr" src="{{ route('eduquiz.staff.rooms.qr', $room) }}" width="148" height="148" alt="Mã QR tham gia phòng {{ $room->pin }}">
            <div class="stack" style="gap: 8px;">
                <p class="pin">{{ $room->pin }}</p>
                <p class="muted small">Quét mã QR hoặc nhập PIN tại trang EduQuiz.</p>
                <a href="{{ $joinUrl }}" class="break-link small">{{ $joinUrl }}</a>
                <div class="actions">
                    <button type="button" class="button quiet" data-copy="{{ $joinUrl }}">Sao chép liên kết</button>
                </div>
            </div>
        </div>
        @if ($room->requires_invite && !$room->finished_at)
            <form class="stack invite" method="post" action="{{ route('eduquiz.staff.rooms.invite', $room) }}">
                @csrf
                <label>Email hoặc số điện thoại được mời
                    <input name="contact" placeholder="hocvien@example.com hoặc 09xx..." maxlength="254" value="{{ old('contact') }}" required>
                </label>
                <div class="actions">
                    <button class="button primary">Tạo mã mời cá nhân</button>
                </div>
            </form>
            @if ($code = session('invite_code'))
                <p class="notice break-link" style="margin-top: 12px; margin-bottom: 0;">Gửi riêng cho học viên mã <strong>{{ $code }}</strong>. Mã hiển thị một lần.</p>
            @endif
        @endif
    </section>
    <section class="card stack">
        <div class="card-header" style="margin-bottom: 0;">
            <h2>Báo cáo phiên thi</h2>
        </div>
        <p class="muted">Xem chi tiết lượt tham gia, đáp án từng câu và điểm số đã ghi nhận của học viên.</p>
        <div class="actions">
            <a class="button quiet" href="{{ route('eduquiz.staff.results', ['session' => $room->id]) }}">Xem kết quả & thống kê</a>
        </div>
        @if (!\App\Services\EduQuiz\Access::instructor(auth()->user()) && $room->finished_at && !$room->anonymized_at)
            <form method="post" class="invite" action="{{ route('eduquiz.staff.rooms.anonymize', $room) }}"
                data-confirm="Xóa họ tên, thông tin liên hệ, mã mời và quyền truy cập của học viên trong phiên này? Điểm và đáp án sẽ được giữ ở dạng ẩn danh.">
                @csrf
                <button class="button danger">Ẩn danh học viên</button>
            </form>
        @elseif ($room->anonymized_at)
            <p class="muted small">Thông tin định danh học viên đã được ẩn danh.</p>
        @endif
    </section>
</div>
@endsection
