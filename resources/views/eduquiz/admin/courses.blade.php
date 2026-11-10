@extends('eduquiz.layout', ['title' => 'Khóa đào tạo', 'staff' => true])

@section('content')
<header class="section-head"><div><p class="eyebrow">NỘI DUNG ĐÀO TẠO</p><h1>Khóa đào tạo</h1><p class="muted">Các khóa hiện có trên website GREECO.</p></div></header>
<section class="card">
    <ul class="record-list">
        @forelse ($courses as $course)
            <li><strong>{{ $course->title }}</strong><a href="{{ route('eduquiz.staff.quizzes', ['course' => $course->id]) }}">{{ $course->quiz_count }} bộ đề</a></li>
        @empty
            <li>Chưa có khóa đào tạo.</li>
        @endforelse
    </ul>
    @include('eduquiz.admin.pagination', ['page' => $courses])
</section>
@endsection

