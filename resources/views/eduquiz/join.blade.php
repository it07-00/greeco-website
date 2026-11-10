@extends('eduquiz.layout', ['title' => 'Tham gia trắc nghiệm'])

@section('content')
<section class="card join-card">
    <p class="eyebrow">{{ $room?->mode === 'self' ? 'BÀI KIỂM TRA TỰ LÀM' : 'GREECO EDUQUIZ' }}</p>
    <h1>Tham gia trắc nghiệm</h1>
    @if ($room)
        <p class="muted">{{ $room->title }} @if($room->course_title) · {{ $room->course_title }} @endif</p>
    @else
        <p class="muted">Nhập mã phòng từ giảng viên để vào bài kiểm tra.</p>
    @endif

    @if ($room?->finished_at)
        <p class="notice">Phiên thi đã kết thúc.</p>
        <a href="{{ route('eduquiz.join') }}">Nhập mã phòng khác</a>
    @else
        <form method="post" action="{{ $room ? route('eduquiz.room.join', $room->pin) : route('eduquiz.join.store') }}" class="stack">
            @csrf
            <label>Mã phòng
                <input name="pin" value="{{ old('pin', $room?->pin) }}" inputmode="numeric" pattern="[0-9]{8}" maxlength="8" autocomplete="off" required @readonly($room)>
            </label>
            <label>Họ và tên
                <input name="name" value="{{ old('name') }}" autocomplete="name" maxlength="150" required>
            </label>
            <fieldset class="stack">
                <legend>Thông tin liên hệ</legend>
                <p class="muted small">Nhập ít nhất một trong hai mục sau.</p>
                <label>Email<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="254"></label>
                <label>Số điện thoại<input type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" maxlength="40"></label>
            </fieldset>
            @if ($room?->requires_invite)
                <label>Mã mời cá nhân
                    <input name="invite" autocomplete="off" maxlength="64" required aria-describedby="invite-hint">
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
            <button class="button primary" type="submit">Tham gia</button>
        </form>
    @endif
</section>
@endsection

