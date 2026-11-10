@extends('eduquiz.layout', ['title' => 'Quản lý trắc nghiệm', 'staff' => true])

@section('content')
<header class="section-head">
    <div><p class="eyebrow">KHU VỰC GIẢNG VIÊN</p><h1>Quản lý trắc nghiệm</h1><p class="muted">Soạn câu hỏi, tổ chức phòng thi và theo dõi kết quả đào tạo.</p></div>
    <a class="button primary" href="{{ route('eduquiz.staff.rooms') }}">Tạo phiên thi</a>
</header>
<div class="metrics">
    @foreach ($counts as $label => $number)
        <div class="card metric"><span class="muted">{{ $label }}</span><strong>{{ $number }}</strong></div>
    @endforeach
</div>
<section class="card">
    <h2>Phiên thi gần đây</h2>
    <ul class="record-list">
        @forelse ($rooms as $room)
            <li><a href="{{ route('eduquiz.staff.rooms.show', $room) }}">{{ $room->title }}</a><span>PIN {{ $room->pin }}</span></li>
        @empty
            <li>Chưa có phiên thi. Hãy bắt đầu từ <a href="{{ route('eduquiz.staff.questions') }}">ngân hàng câu hỏi</a>.</li>
        @endforelse
    </ul>
</section>
@endsection

