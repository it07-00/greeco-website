@extends('eduquiz.layout', ['title' => $question ? 'Sửa câu hỏi' : 'Tạo câu hỏi', 'staff' => true])

@section('content')
<header class="section-head">
    <div><a class="back" href="{{ route('eduquiz.staff.questions') }}">← Ngân hàng câu hỏi</a><h1>{{ $question ? 'Sửa câu hỏi' : 'Tạo câu hỏi' }}</h1></div>
</header>
<form class="card stack" method="post" enctype="multipart/form-data" action="{{ route('eduquiz.staff.questions.save', $question) }}">
    @csrf
    <label>Nội dung câu hỏi<textarea name="prompt" rows="3" maxlength="2500" required>{{ old('prompt', $question?->prompt) }}</textarea></label>
    <fieldset class="stack">
        <legend>Phương án trả lời</legend>
        <p class="muted small">Nhập 2–6 phương án. Đánh dấu một hoặc nhiều đáp án đúng.</p>
        @php($options = old('options', $question?->options->toArray() ?? []))
        @foreach (str_split('ABCDEF') as $index => $letter)
            <div class="option-field">
                <label>Phương án {{ $letter }}<input name="options[{{ $index }}][text]" value="{{ $options[$index]['text'] ?? '' }}" maxlength="500"></label>
                <label class="checkbox"><input type="checkbox" name="options[{{ $index }}][correct]" value="1" @checked($options[$index]['correct'] ?? false)> <span>{{ $letter }} đúng</span></label>
            </div>
        @endforeach
    </fieldset>
    <div class="field-grid">
        <label>Thời gian trả lời (giây)<input type="number" name="seconds" min="5" max="600" value="{{ old('seconds', $question?->seconds ?? 30) }}" required></label>
        <label>Ảnh minh họa (tối đa 4 MB)<input type="file" name="image" accept="image/png,image/jpeg,image/webp"></label>
    </div>
    @if ($question?->image)
        <img class="question-image" src="{{ route('eduquiz.staff.questions.image', $question) }}" alt="Ảnh minh họa hiện tại">
        <label class="checkbox"><input type="checkbox" name="remove_image" value="1"> Xóa ảnh khỏi phiên bản hiện tại</label>
    @endif
    <label>Giải thích đáp án<textarea name="explanation" rows="3" maxlength="3500">{{ old('explanation', $question?->explanation) }}</textarea></label>
    <div class="actions"><button class="button primary">Lưu câu hỏi</button></div>
</form>
@if ($question)
    <section class="card">
        <h2>Lịch sử chỉnh sửa</h2>
        @foreach ($revisions as $revision)
            @php($content = json_decode($revision->content, true))
            <details class="revision">
                <summary>{{ \Carbon\Carbon::parse($revision->created_at)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') }} · {{ $revision->name ?? 'Tài khoản đã xóa' }}</summary>
                <p>{{ $content['prompt'] }} · {{ $content['seconds'] }} giây</p>
                <ul>@foreach ($content['options'] as $option)<li>{{ $option['text'] }} @if($option['correct']) (đúng) @endif</li>@endforeach</ul>
                @if ($content['explanation'])<p>{{ $content['explanation'] }}</p>@endif
            </details>
        @endforeach
        @include('eduquiz.admin.pagination', ['page' => $revisions])
    </section>
@endif
@endsection

