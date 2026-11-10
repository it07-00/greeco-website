@extends('eduquiz.layout', ['title' => $room->title])

@section('content')
<section class="card arena-shell" data-quiz="learner" data-state="{{ route('eduquiz.state', $room->pin) }}"
    data-answer="{{ route('eduquiz.answer', $room->pin) }}" data-submit="{{ route('eduquiz.submit', $room->pin) }}"
    data-retry="{{ route('eduquiz.retry', $room->pin) }}">
    <header class="section-head">
        <div>
            <p class="eyebrow">{{ $room->mode === 'live' ? 'EDUQUIZ LIVE' : 'BÀI KIỂM TRA TỰ LÀM' }} · {{ $room->pin }}</p>
            <h1>{{ $room->title }}</h1>
            @if ($room->course_title)<p class="muted">{{ $room->course_title }}</p>@endif
        </div>
        <p class="status-badge" data-phase>Đang kết nối</p>
    </header>
    <div class="arena-meta" data-meta hidden>
        <span data-count></span><span data-timer></span>
    </div>
    <p class="quiz-feedback" data-feedback role="status"></p>
    <div data-board><p class="arena placeholder">Đang tải nội dung phòng thi…</p></div>
    <div class="actions" data-actions></div>
    <noscript><p class="notice error">Bật JavaScript để tham gia trắc nghiệm.</p></noscript>
</section>
@endsection

