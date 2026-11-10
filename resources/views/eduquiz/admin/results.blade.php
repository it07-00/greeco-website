@extends('eduquiz.layout', ['title' => $section === 'participants' ? 'Danh sách học viên' : 'Kết quả và thống kê', 'staff' => true])

@section('content')
<header class="section-head">
    <div><p class="eyebrow">BÁO CÁO ĐÀO TẠO</p><h1>{{ $section === 'participants' ? 'Danh sách học viên' : 'Kết quả và thống kê' }}</h1></div>
    @if ($section === 'results')
        <div class="actions">
            <a class="button quiet" href="{{ route('eduquiz.staff.results.export', ['format' => 'xlsx'] + request()->only('q', 'session', 'course', 'from', 'to')) }}">Xuất Excel</a>
            <a class="button primary" href="{{ route('eduquiz.staff.results.export', ['format' => 'pdf'] + request()->only('q', 'session', 'course', 'from', 'to')) }}">Xuất PDF</a>
        </div>
    @endif
</header>
<section class="card">
    <form class="stack" method="get">
        <div class="field-grid three">
            <label>Tên, email hoặc điện thoại<input type="search" name="q" value="{{ request('q') }}" maxlength="150"></label>
            <label>Khóa đào tạo<select name="course"><option value="">Tất cả</option>
                @foreach ($courses as $course)<option value="{{ $course->id }}" @selected(request('course') == $course->id)>{{ $course->title }}</option>@endforeach
            </select></label>
            <label>Phiên thi<select name="session"><option value="">Tất cả</option>
                @foreach ($rooms as $room)<option value="{{ $room->id }}" @selected(request('session') == $room->id)>{{ $room->title }} · {{ $room->pin }}</option>@endforeach
            </select></label>
            <label>Ngày tạo phiên từ<input type="date" name="from" value="{{ request('from') }}"></label>
            <label>Đến ngày<input type="date" name="to" value="{{ request('to') }}"></label>
        </div>
        <div class="actions"><button class="button primary">Áp dụng bộ lọc</button><span class="muted">{{ $rows->total() }} {{ $section === 'participants' ? 'học viên theo phiên' : 'lượt làm bài' }}</span></div>
    </form>
</section>
@if ($stats)
    <div class="metrics">
        <div class="card metric"><span class="muted">Học viên theo phiên</span><strong>{{ $stats->participants }}</strong></div>
        <div class="card metric"><span class="muted">Lượt hoàn thành</span><strong>{{ $stats->completed }}</strong></div>
        <div class="card metric"><span class="muted">Điểm trung bình</span><strong>{{ $stats->average === null ? '—' : number_format($stats->average, 1, ',', '.').'%' }}</strong></div>
    </div>
@endif
<section class="card table-scroll" tabindex="0" aria-label="Danh sách lượt làm bài">
    <table>
        <thead><tr><th>Học viên</th><th>Liên hệ</th><th>Bộ đề / Khóa đào tạo</th><th>PIN</th><th>Lượt</th><th>Điểm</th><th>Trạng thái</th></tr></thead>
        <tbody>
        @forelse ($rows as $row)
            <tr>
                <td><a href="{{ route('eduquiz.staff.results.detail', $row->id) }}">{{ $row->name }}</a></td>
                <td>{{ $row->email ?? '' }} @if($row->email && $row->phone)<br>@endif {{ $row->phone ?? '' }}</td>
                <td>{{ $row->title }} @if($row->course_title)<br><span class="muted small">{{ $row->course_title }}</span>@endif</td>
                <td>{{ $row->pin }}</td><td>{{ $row->attempt }}</td><td>{{ $row->finished_at ? $row->score.'/'.$row->total : '—' }}</td>
                <td>{{ $row->finished_at ? 'Hoàn thành' : 'Đang làm' }}</td>
            </tr>
        @empty
            <tr><td colspan="7">Chưa có lượt làm bài phù hợp.</td></tr>
        @endforelse
        </tbody>
    </table>
    @include('eduquiz.admin.pagination', ['page' => $rows])
</section>
@endsection

