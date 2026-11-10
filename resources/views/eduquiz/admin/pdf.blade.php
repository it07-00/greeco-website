<!doctype html>
<html lang="vi">
<head><meta charset="utf-8"><title>Kết quả GREECO EduQuiz</title>
<style>
    body { font-family: "DejaVu Sans", sans-serif; font-size: 9px; color: #162d50; }
    h1 { font-size: 20px; }
    table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    thead { display: table-header-group; }
    th, td { text-align: left; padding: 5px; border: 1px solid #cbd5e1; overflow-wrap: break-word; }
    th { background: #eaf1fb; }
</style></head>
<body>
<h1>Kết quả GREECO EduQuiz</h1>
<table>
    <thead><tr>@foreach ($headers as $header)<th>{{ $header }}</th>@endforeach</tr></thead>
    <tbody>
    @forelse ($rows as $row)<tr>@foreach ($row as $cell)<td>{{ $cell }}</td>@endforeach</tr>
    @empty<tr><td colspan="{{ count($headers) }}">Chưa có dữ liệu phù hợp.</td></tr>@endforelse
    </tbody>
</table>
</body>
</html>

