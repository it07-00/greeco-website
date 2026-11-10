@extends('eduquiz.layout', ['title' => 'Phòng thi', 'staff' => true])

@section('content')
<header class="section-head"><div><p class="eyebrow">TỔ CHỨC ĐÀO TẠO</p><h1>Phòng thi</h1><p class="muted">Tạo phiên trực tiếp hoặc cấp liên kết cho học viên tự làm.</p></div></header>
<form class="card stack" method="post" action="{{ route('eduquiz.staff.rooms.create') }}" data-room-form>
    @csrf
    <h2>Tạo phiên thi</h2>
    <div class="field-grid">
        <label>Bộ đề
            <select name="quiz_id" required>
                <option value="">Chọn bộ đề</option>
                @foreach ($quizzes as $quiz)
                    <option value="{{ $quiz->id }}" @selected(old('quiz_id') == $quiz->id)>{{ $quiz->title }}</option>
                @endforeach
            </select>
        </label>
        <label>Hình thức
            <select name="mode">
                <option value="live" @selected(old('mode') === 'live')>Trực tiếp</option>
                <option value="self" @selected(old('mode') === 'self')>Tự làm bài</option>
            </select>
        </label>
    </div>
    <div class="field-grid" data-self-settings>
        <label>Thời lượng tự làm (phút)<input type="number" min="1" max="240" name="duration_minutes" value="{{ old('duration_minutes', 15) }}"></label>
        <label>Giới hạn số lượt tự làm<input type="number" min="1" max="10" name="attempt_limit" value="{{ old('attempt_limit', 1) }}"></label>
    </div>
    <label class="checkbox"><input type="checkbox" name="requires_invite" value="1" @checked(old('requires_invite'))> Yêu cầu mã mời cá nhân cho phiên này</label>
    <div class="actions">
        @if ($quizzes->isNotEmpty())
            <button class="button primary">Tạo phiên thi</button>
        @else
            <a class="button primary" href="{{ route('eduquiz.staff.quizzes') }}">Chuẩn bị bộ đề có câu hỏi</a>
        @endif
    </div>
</form>
<section class="card">
    <h2>Các phiên đã tạo</h2>
    <ul class="record-list">
        @forelse ($rooms as $room)
            <li><div><a href="{{ route('eduquiz.staff.rooms.show', $room) }}">{{ $room->title }}</a><br><span class="muted small">PIN {{ $room->pin }} · {{ $room->mode === 'live' ? 'Trực tiếp' : 'Tự làm' }}</span></div>
                <span class="status-badge">{{ $room->finished_at ? 'Đã kết thúc' : 'Đang mở' }}</span></li>
        @empty
            <li>Chưa có phiên thi.</li>
        @endforelse
    </ul>
    @include('eduquiz.admin.pagination', ['page' => $rooms])
</section>
@endsection

