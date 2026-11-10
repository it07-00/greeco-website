@extends('eduquiz.layout', ['title' => $room->title, 'staff' => true])

@section('content')
<section class="card arena-shell" data-quiz="host" data-state="{{ route('eduquiz.staff.rooms.state', $room) }}"
    data-control="{{ route('eduquiz.staff.rooms.control', $room) }}">
    <header class="section-head">
        <div>
            <p class="eyebrow">{{ $room->mode === 'live' ? 'EDUQUIZ LIVE' : 'BÀI KIỂM TRA TỰ LÀM' }}</p>
            <h1>{{ $room->title }}</h1>
            <p class="muted">{{ $room->course_title ?? 'Màn hình giảng viên' }}</p>
        </div>
        <p class="status-badge" data-phase>Đang kết nối</p>
    </header>
    <nav class="view-switch" aria-label="Màn hình phòng thi">
        <span aria-current="page">Màn hình giảng viên</span>
        <a href="{{ $joinUrl }}" target="_blank" rel="noopener">Điện thoại học viên ↗</a>
    </nav>
    <div class="arena-meta" data-meta hidden>
        <span data-count></span><span data-timer></span>
    </div>
    <p class="quiz-feedback" data-feedback role="status"></p>
    <div data-board><p class="arena placeholder">Đang tải nội dung phòng thi…</p></div>
    <div class="actions control-bar" data-actions></div>
    <section class="chart-panel" data-chart hidden aria-label="Tỷ lệ lựa chọn đáp án"></section>
    <section class="chart-panel" data-leaders hidden aria-label="Bảng xếp hạng"></section>
    <noscript><p class="notice error">Bật JavaScript để điều khiển phòng thi.</p></noscript>
</section>

<div class="field-grid room-details">
    <section class="card">
        <h2>Mời học viên</h2>
        <div class="join-info">
            <img class="qr" src="{{ route('eduquiz.staff.rooms.qr', $room) }}" width="144" height="144" alt="Mã QR tham gia phòng {{ $room->pin }}">
            <div>
                <p class="pin">{{ $room->pin }}</p>
                <p class="muted small">Quét QR hoặc nhập mã tại trang EduQuiz.</p>
                <a href="{{ $joinUrl }}" class="break-link">{{ $joinUrl }}</a>
                <div class="actions"><button type="button" class="button quiet" data-copy="{{ $joinUrl }}">Sao chép liên kết</button></div>
            </div>
        </div>
        @if ($room->requires_invite && !$room->finished_at)
            <form class="stack invite" method="post" action="{{ route('eduquiz.staff.rooms.invite', $room) }}">
                @csrf
                <label>Email hoặc số điện thoại được mời<input name="contact" maxlength="254" value="{{ old('contact') }}" required></label>
                <button class="button primary">Tạo mã mời cá nhân</button>
            </form>
            @if ($code = session('invite_code'))
                <p class="notice break-link">Gửi riêng cho học viên mã <strong>{{ $code }}</strong>. Mã hiển thị một lần.</p>
            @endif
        @endif
    </section>
    <section class="card">
        <h2>Báo cáo phiên thi</h2>
        <p class="muted">Xem lượt tham gia, đáp án và điểm đã ghi nhận.</p>
        <a class="button quiet" href="{{ route('eduquiz.staff.results', ['session' => $room->id]) }}">Xem kết quả</a>
        @if (!\App\Services\EduQuiz\Access::instructor(auth()->user()) && $room->finished_at && !$room->anonymized_at)
            <form method="post" class="invite" action="{{ route('eduquiz.staff.rooms.anonymize', $room) }}"
                data-confirm="Xóa họ tên, thông tin liên hệ, mã mời và quyền truy cập của học viên trong phiên này? Điểm và đáp án sẽ được giữ ở dạng ẩn danh.">
                @csrf
                <button class="button danger">Ẩn danh học viên</button>
            </form>
        @elseif ($room->anonymized_at)
            <p class="muted">Thông tin học viên đã được ẩn danh.</p>
        @endif
    </section>
</div>
@endsection

