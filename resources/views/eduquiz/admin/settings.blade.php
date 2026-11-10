@extends('eduquiz.layout', ['title' => 'Cài đặt', 'staff' => true])

@section('content')
<header class="section-head"><div><p class="eyebrow">QUẢN TRỊ EDUQUIZ</p><h1>Cài đặt</h1></div></header>
<form class="card stack" method="post" action="{{ route('eduquiz.staff.settings.save') }}">
    @csrf
    <h2>Lưu giữ dữ liệu học viên</h2>
    <label>Số ngày kể từ khi phiên kết thúc<input name="retention_days" type="number" min="1" max="3650" value="{{ old('retention_days', $retentionDays) }}" required></label>
    <p class="muted">Khi hết hạn, tác vụ định kỳ xóa tên, email, số điện thoại và mã mời. Điểm và đáp án được giữ ở dạng ẩn danh. Quản trị viên có thể ẩn danh sớm tại trang phiên đã kết thúc.</p>
    <div class="actions"><button class="button primary">Lưu cài đặt</button></div>
</form>
<section class="card">
    <h2>Tài khoản giảng viên</h2>
    <p class="muted">Giảng viên đăng nhập qua <a href="{{ route('filament.instructor.auth.login') }}">cổng giảng viên</a> và quản lý dữ liệu do mình tạo.</p>
    <ul class="record-list">
        @forelse ($instructors as $user)<li><strong>{{ $user->name }}</strong><span>{{ $user->email }}</span></li>
        @empty<li>Chưa có tài khoản giảng viên.</li>@endforelse
    </ul>
</section>
@endsection

