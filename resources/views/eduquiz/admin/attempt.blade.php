@extends('eduquiz.layout', ['title' => 'Chi tiết lượt làm bài', 'staff' => true])

@section('content')
<header class="section-head">
    <div><a class="back" href="{{ route('eduquiz.staff.results', ['session' => $room->id]) }}">← Kết quả phiên thi</a><h1>{{ $attempt->participant->name }}</h1>
        <p class="muted">{{ $room->title }} · PIN {{ $room->pin }} · Lượt {{ $attempt->attempt }}</p></div>
    <span class="status-badge">{{ $attempt->result ? $attempt->result->score.'/'.$attempt->result->total.' điểm' : 'Đang làm' }}</span>
</header>
<section class="card">
    <p>{{ $attempt->participant->email }} {{ $attempt->participant->phone }}</p>
    @if ($attempt->result)<p class="muted">Thời gian hoàn thành {{ $attempt->result->elapsed_seconds }} giây</p>@endif
    <ol class="answer-review">
        @php($answers = $attempt->answers->keyBy('position'))
        @foreach ($room->questions as $position => $question)
            @php($answer = $answers->get($position))
            <li><strong>{{ $question['prompt'] }}</strong>
                @if ($answer && $answer->choices)
                    <p>{{ implode(' · ', array_map(fn ($choice) => $question['options'][$choice]['text'], $answer->choices)) }}</p>
                    <p class="muted">{{ $answer->correct ? 'Đúng' : 'Sai' }} · {{ $answer->elapsed_seconds }} giây</p>
                @else
                    <p class="muted">Chưa trả lời</p>
                @endif
            </li>
        @endforeach
    </ol>
</section>
@endsection

