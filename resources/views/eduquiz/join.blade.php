@extends('eduquiz.layout', ['title' => 'Tham gia trắc nghiệm'])

@section('content')
<section class="card join-card">
    <div class="join-hero">
        <p class="eyebrow">{{ $room?->mode === 'self' ? 'BÀI KIỂM TRA TỰ LÀM' : 'GREECO EDUQUIZ' }}</p>
        <h1>Tham gia trắc nghiệm</h1>
        @if ($room)
            <p class="muted">{{ $room->title }} @if($room->course_title) · {{ $room->course_title }} @endif</p>
        @else
            <p class="muted">Nhập mã phòng 8 chữ số từ giảng viên để vào bài kiểm tra.</p>
        @endif
    </div>

    @if ($room?->finished_at)
        <p class="notice">Phiên thi đã kết thúc.</p>
        <div class="actions">
            <a class="button quiet block" href="{{ route('eduquiz.join') }}">Nhập mã phòng khác</a>
        </div>
    @else
        <form method="post" action="{{ $room ? route('eduquiz.room.join', $room->pin) : route('eduquiz.join.store') }}" class="stack">
            @csrf
            <label>Mã phòng (PIN 8 số)
                <input class="pin-input" name="pin" value="{{ old('pin', $room?->pin) }}" placeholder="00000000" inputmode="numeric" pattern="[0-9]{8}" maxlength="8" autocomplete="off" required @readonly($room)>
            </label>
            <label>Họ và tên học viên
                <input name="name" value="{{ old('name') }}" placeholder="Nguyễn Văn A" autocomplete="name" maxlength="150" required>
            </label>
            <fieldset class="stack">
                <legend>Thông tin liên hệ</legend>
                <p class="muted small">Nhập ít nhất một trong hai mục sau để hệ thống ghi nhận kết quả.</p>
                <div class="field-grid">
                    <label>Email<input type="email" name="email" value="{{ old('email') }}" placeholder="hocvien@example.com" autocomplete="email" maxlength="254"></label>
                    <label>Số điện thoại<input type="tel" name="phone" value="{{ old('phone') }}" placeholder="09xx xxx xxx" autocomplete="tel" maxlength="40"></label>
                </div>
            </fieldset>
            @if ($room?->requires_invite)
                <label>Mã mời cá nhân
                    <input name="invite" placeholder="Nhập mã mời từ giảng viên" autocomplete="off" maxlength="64" required aria-describedby="invite-hint">
                </label>
                <p id="invite-hint" class="muted small">Dùng đúng email hoặc số điện thoại mà giảng viên đã mời.</p>
            @endif
            @if ($room?->mode === 'self')
                <p class="notice">Thời gian {{ $room->duration_seconds / 60 }} phút bắt đầu tính khi bạn tham gia. Tối đa {{ $room->attempt_limit }} lượt.</p>
            @endif
            <label class="checkbox privacy">
                <input type="checkbox" name="consent" value="1" required @checked(old('consent'))>
                <span>Tôi đã đọc thông báo. GREECO sử dụng họ tên và thông tin liên hệ để ghi nhận lượt làm bài và báo cáo đào tạo. Thông tin định danh được ẩn danh sau {{ $retentionDays }} ngày kể từ khi phiên kết thúc.</span>
            </label>
            <button class="button primary block" type="submit">Tham gia phòng thi</button>
        </form>
    @endif
</section>
@endsection
