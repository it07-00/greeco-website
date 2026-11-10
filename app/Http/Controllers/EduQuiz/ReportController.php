<?php

namespace App\Http\Controllers\EduQuiz;

use App\Models\Course;
use App\Models\EduQuiz\Attempt;
use App\Models\EduQuiz\Room;
use App\Services\EduQuiz\Access;
use Carbon\CarbonImmutable;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController
{
    private const HEADERS = ['Họ tên', 'Email', 'Điện thoại', 'Khóa đào tạo', 'Bộ đề', 'PIN', 'Ngày tạo phiên', 'Lượt', 'Điểm', 'Thời gian (giây)', 'Trạng thái'];

    public function index(Request $request, string $section = 'results')
    {
        $rooms = Access::scope(Room::query(), $request->user());
        $courses = Course::whereIn('id', (clone $rooms)->select('course_id'))->orderBy('title')->get(['id', 'title']);
        $query = $this->query($request);
        $stats = null;
        if ($section === 'participants') {
            $query->where('a.id', function (Builder $latest) {
                $latest->from('eduquiz_session_participants as recent')->selectRaw('max(recent.id)')
                    ->whereColumn('recent.participant_id', 'a.participant_id');
            });
        } else {
            $stats = (clone $query)->reorder()->select([])
                ->selectRaw('count(distinct p.id) as participants, count(r.attempt_id) as completed, avg(100.0 * r.score / nullif(r.total, 0)) as average')
                ->first();
        }

        return view('eduquiz.admin.results', [
            'section' => $section,
            'rows' => $query->paginate(25)->withQueryString(),
            'rooms' => $rooms->latest('id')->get(['id', 'title', 'pin']),
            'courses' => $courses,
            'stats' => $stats,
        ]);
    }

    public function participants(Request $request)
    {
        return $this->index($request, 'participants');
    }

    public function detail(Request $request, Attempt $attempt)
    {
        $attempt->load(['participant', 'result', 'answers']);
        $room = Room::findOrFail($attempt->participant->session_id);
        Access::authorize($room, $request->user());

        return view('eduquiz.admin.attempt', compact('attempt', 'room'));
    }

    public function export(Request $request, string $format)
    {
        $query = $this->query($request);
        if ($format === 'xlsx') {
            return response()->streamDownload(function () use ($query) {
                $workbook = new Spreadsheet;
                $sheet = $workbook->getActiveSheet();
                $sheet->setTitle('Kết quả');
                $sheet->fromArray(self::HEADERS);
                $sheet->getStyle('A1:K1')->getFont()->setBold(true);
                $sheet->freezePane('A2');
                $index = 2;
                foreach ($query->cursor() as $row) {
                    foreach ($this->cells($row) as $column => $value) {
                        $sheet->setCellValueExplicit([$column + 1, $index], (string) $value, DataType::TYPE_STRING);
                    }
                    $index++;
                }
                foreach (range('A', 'K') as $column) {
                    $sheet->getColumnDimension($column)->setWidth(in_array($column, ['A', 'B', 'D', 'E'], true) ? 32 : 18);
                }
                $sheet->setAutoFilter('A1:K'.($index - 1));
                (new Xlsx($workbook))->save('php://output');
            }, 'eduquiz-ket-qua.xlsx', ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
        }

        $rows = $query->limit(501)->get();
        if ($rows->count() > 500) {
            return back()->withErrors(['export' => 'PDF hỗ trợ tối đa 500 dòng. Hãy thu hẹp bộ lọc hoặc xuất Excel.']);
        }

        $pdf = new Dompdf(new Options([
            'defaultFont' => 'DejaVu Sans',
            'isRemoteEnabled' => false,
        ]));
        $pdf->loadHtml(view('eduquiz.admin.pdf', [
            'headers' => self::HEADERS,
            'rows' => $rows->map(fn ($row) => $this->cells($row)),
        ])->render(), 'UTF-8');
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="eduquiz-ket-qua.pdf"',
        ]);
    }

    private function query(Request $request): Builder
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'session' => ['nullable', 'integer'],
            'course' => ['nullable', 'integer'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);
        $query = DB::table('eduquiz_session_participants as a')
            ->join('eduquiz_participants as p', 'p.id', '=', 'a.participant_id')
            ->join('eduquiz_sessions as s', 's.id', '=', 'p.session_id')
            ->leftJoin('eduquiz_results as r', 'r.attempt_id', '=', 'a.id')
            ->select([
                'a.id', 'a.attempt', 'a.finished_at', 'a.started_at', 'p.name', 'p.email', 'p.phone',
                's.course_title', 's.title', 's.pin', 's.created_at as session_date',
                'r.score', 'r.total', 'r.elapsed_seconds',
            ])->orderByDesc('a.id');
        if (Access::instructor($request->user())) {
            $query->where('s.owner_id', $request->user()->id);
        }

        if (! empty($data['q'])) {
            $pattern = '%'.$data['q'].'%';
            $phonePattern = '%'.preg_replace('/[\s().-]+/u', '', $data['q']).'%';
            $query->where(fn (Builder $query) => $query->whereLike('p.name', $pattern)
                ->orWhereLike('p.email', $pattern)
                ->orWhereLike('p.phone', $pattern)
                ->orWhereLike('p.phone', str_starts_with($phonePattern, '%0') ? '%+84'.substr($phonePattern, 2) : $phonePattern));
        }
        if (! empty($data['session'])) {
            $query->where('s.id', $data['session']);
        }
        if (! empty($data['course'])) {
            $query->where('s.course_id', $data['course']);
        }
        if (! empty($data['from'])) {
            $query->where('s.created_at', '>=', CarbonImmutable::parse($data['from'], 'Asia/Ho_Chi_Minh')->setTimezone(config('app.timezone')));
        }
        if (! empty($data['to'])) {
            $query->where('s.created_at', '<', CarbonImmutable::parse($data['to'], 'Asia/Ho_Chi_Minh')->addDay()->setTimezone(config('app.timezone')));
        }

        return $query;
    }

    private function cells(object $row): array
    {
        return [
            $row->name, $row->email, $row->phone, $row->course_title, $row->title, $row->pin,
            CarbonImmutable::parse($row->session_date)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i'),
            $row->attempt, $row->finished_at ? $row->score.'/'.$row->total : '',
            $row->elapsed_seconds, $row->finished_at ? 'Hoàn thành' : 'Đang làm',
        ];
    }
}
