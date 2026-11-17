<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Document;
use App\Models\Post;
use App\Models\Project;
use App\Models\RegistrationAttendee;
use App\Models\Service;
use App\Services\EduQuiz\Access;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityStatsExcelExport
{
    public function __construct(public ActivityStatisticsService $statistics) {}

    public function download(): StreamedResponse
    {
        $this->authorizeAdmin();

        $filename = 'thong-ke-hoat-dong-greeco-'.now('Asia/Ho_Chi_Minh')->format('Ymd-His').'.xlsx';

        return $this->streamWorkbook($filename, function (Spreadsheet $workbook): void {
            $this->writeSummarySheet($workbook);
            $this->writeCourseRegistrationsSheet($workbook->createSheet());
            $this->writeAttendeesSheet($workbook->createSheet());
            $this->writeEduQuizSheet($workbook->createSheet());
            $this->writeContentSheet($workbook->createSheet());
            $this->writeContactsSheet($workbook->createSheet());
        });
    }

    /** @param iterable<CourseRegistration> $registrations */
    public function downloadCourseRegistrations(iterable $registrations): StreamedResponse
    {
        $this->authorizeAdmin();

        $filename = 'dang-ky-khoa-hoc-greeco-'.now('Asia/Ho_Chi_Minh')->format('Ymd-His').'.xlsx';

        return $this->streamWorkbook($filename, function (Spreadsheet $workbook) use ($registrations): void {
            $sheet = $workbook->getActiveSheet();
            $this->populateCourseRegistrationsSheet($sheet, $registrations);

            $attendeeSheet = $workbook->createSheet();
            $attendeeSheet->setTitle('Danh sách học viên');
            $headers = ['Mã đăng ký', 'Khóa học', 'Doanh nghiệp', 'Họ tên học viên', 'Chức danh', 'Email', 'Số điện thoại', 'Ngày đăng ký'];
            $rowIndex = $this->writeHeaderRow($attendeeSheet, $headers);

            foreach ($registrations as $registration) {
                $registration->loadMissing(['course:id,title', 'attendees']);
                foreach ($registration->attendees as $attendee) {
                    $this->writeDataRow($attendeeSheet, $rowIndex++, [
                        $registration->code,
                        $registration->course?->title ?? '',
                        $registration->company_name,
                        $attendee->full_name,
                        $attendee->job_title,
                        $attendee->email,
                        $attendee->phone,
                        $this->formatDateTime($registration->created_at),
                    ], [0, 6]);
                }
            }

            $this->finalizeSheet($attendeeSheet, count($headers), $rowIndex - 1, [16, 32, 30, 26, 22, 28, 18, 18]);
        });
    }

    /** @param iterable<Contact> $contacts */
    public function downloadContacts(iterable $contacts): StreamedResponse
    {
        $this->authorizeAdmin();

        $filename = 'lien-he-greeco-'.now('Asia/Ho_Chi_Minh')->format('Ymd-His').'.xlsx';

        return $this->streamWorkbook($filename, function (Spreadsheet $workbook) use ($contacts): void {
            $this->populateContactsSheet($workbook->getActiveSheet(), $contacts);
        });
    }

    private function authorizeAdmin(): void
    {
        $user = auth()->user();
        abort_unless($user && ! Access::instructor($user), 403);
    }

    private function streamWorkbook(string $filename, callable $builder): StreamedResponse
    {
        return response()->streamDownload(function () use ($builder): void {
            $workbook = new Spreadsheet;
            $builder($workbook);
            $workbook->setActiveSheetIndex(0);
            (new Xlsx($workbook))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function writeSummarySheet(Spreadsheet $workbook): void
    {
        $sheet = $workbook->getActiveSheet();
        $sheet->setTitle('Tổng hợp hoạt động');

        $this->writeDataRow($sheet, 1, [
            'BÁO CÁO THỐNG KÊ CHI TIẾT HOẠT ĐỘNG GREECO',
            'Xuất lúc: '.now('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s'),
        ]);
        $sheet->getStyle('A1:B1')->getFont()->setBold(true)->setSize(12);

        $headers = ['Nhóm', 'Hạng mục', 'Hôm nay', 'Tuần này', 'Tháng này', 'Năm nay', 'Tổng cộng', 'Trạng thái / Chi tiết'];
        $rowIndex = $this->writeHeaderRow($sheet, $headers, 3);

        foreach ($this->statistics->getSummaryRows() as $row) {
            $this->writeDataRow($sheet, $rowIndex++, [
                $row['group'],
                $row['label'],
                $row['today'],
                $row['week'],
                $row['month'],
                $row['year'],
                $row['total'],
                $row['highlight_label'],
            ]);
        }

        $this->finalizeSheet($sheet, count($headers), $rowIndex - 1, [24, 34, 14, 14, 14, 14, 14, 32], 3);
    }

    private function writeCourseRegistrationsSheet(Worksheet $sheet): void
    {
        $registrations = CourseRegistration::query()
            ->with('course:id,title')
            ->latest('created_at')
            ->limit(1000)
            ->get();

        $this->populateCourseRegistrationsSheet($sheet, $registrations);
    }

    /** @param iterable<CourseRegistration> $registrations */
    private function populateCourseRegistrationsSheet(Worksheet $sheet, iterable $registrations): void
    {
        $sheet->setTitle('Đăng ký khóa học');
        $headers = [
            'Mã đăng ký',
            'Khóa học',
            'Doanh nghiệp',
            'Mã số thuế',
            'Địa chỉ doanh nghiệp',
            'Người liên hệ',
            'Email liên hệ',
            'Số điện thoại',
            'Số học viên',
            'Email nhận hóa đơn',
            'Câu hỏi tư vấn',
            'Nguồn',
            'Trạng thái',
            'Đã xem',
            'Ghi chú quản trị',
            'Ngày đăng ký',
        ];
        $rowIndex = $this->writeHeaderRow($sheet, $headers);

        foreach ($registrations as $registration) {
            $registration->loadMissing('course:id,title');
            $this->writeDataRow($sheet, $rowIndex++, [
                $registration->code,
                $registration->course?->title ?? '',
                $registration->company_name,
                $registration->tax_code,
                $registration->company_address,
                $registration->contact_name,
                $registration->contact_email,
                $registration->contact_phone,
                (int) $registration->attendee_count,
                $registration->invoice_email,
                $registration->consultation_question,
                $registration->source === 'qr' ? 'QR' : 'Website',
                CourseRegistration::STATUSES[$registration->status] ?? (string) $registration->status,
                $registration->is_read ? 'Đã xem' : 'Chưa xem',
                $registration->admin_notes,
                $this->formatDateTime($registration->created_at),
            ], [0, 3, 7]);
        }

        $this->finalizeSheet($sheet, count($headers), $rowIndex - 1, [16, 32, 30, 16, 32, 24, 26, 16, 13, 26, 34, 12, 16, 12, 30, 18]);
    }

    private function writeAttendeesSheet(Worksheet $sheet): void
    {
        $sheet->setTitle('Học viên khóa học');
        $headers = ['Mã đăng ký', 'Khóa học', 'Doanh nghiệp', 'Họ tên học viên', 'Chức danh', 'Email', 'Số điện thoại', 'Ngày đăng ký'];
        $rowIndex = $this->writeHeaderRow($sheet, $headers);

        $attendees = RegistrationAttendee::query()
            ->with(['registration:id,course_id,code,company_name,created_at', 'registration.course:id,title'])
            ->latest('id')
            ->limit(2000)
            ->get();

        foreach ($attendees as $attendee) {
            $this->writeDataRow($sheet, $rowIndex++, [
                $attendee->registration?->code ?? '',
                $attendee->registration?->course?->title ?? '',
                $attendee->registration?->company_name ?? '',
                $attendee->full_name,
                $attendee->job_title,
                $attendee->email,
                $attendee->phone,
                $this->formatDateTime($attendee->registration?->created_at ?? $attendee->created_at),
            ], [0, 6]);
        }

        $this->finalizeSheet($sheet, count($headers), $rowIndex - 1, [16, 32, 30, 26, 22, 28, 18, 18]);
    }

    private function writeEduQuizSheet(Worksheet $sheet): void
    {
        $sheet->setTitle('EduQuiz - Kết quả thi');
        $headers = [
            'Họ tên',
            'Email',
            'Điện thoại',
            'Khóa đào tạo',
            'Bộ đề',
            'PIN',
            'Hình thức',
            'Lượt',
            'Điểm',
            'Tổng câu',
            'Tỷ lệ (%)',
            'Thời gian (giây)',
            'Trạng thái',
            'Ngày tạo phiên',
        ];
        $rowIndex = $this->writeHeaderRow($sheet, $headers);

        $rows = DB::table('eduquiz_session_participants as a')
            ->join('eduquiz_participants as p', 'p.id', '=', 'a.participant_id')
            ->join('eduquiz_sessions as s', 's.id', '=', 'p.session_id')
            ->leftJoin('eduquiz_results as r', 'r.attempt_id', '=', 'a.id')
            ->select([
                'p.name',
                'p.email',
                'p.phone',
                's.course_title',
                's.title',
                's.pin',
                's.mode',
                'a.attempt',
                'a.finished_at',
                'r.score',
                'r.total',
                'r.elapsed_seconds',
                's.created_at as session_date',
            ])
            ->orderByDesc('a.id')
            ->limit(2000)
            ->get();

        foreach ($rows as $row) {
            $total = (int) ($row->total ?? 0);
            $score = $row->finished_at !== null ? (int) ($row->score ?? 0) : null;
            $percent = ($score !== null && $total > 0) ? round(($score / $total) * 100, 1) : null;

            $this->writeDataRow($sheet, $rowIndex++, [
                $row->name,
                $row->email,
                $row->phone,
                $row->course_title,
                $row->title,
                $row->pin,
                $row->mode === 'live' ? 'Trực tiếp' : 'Tự làm',
                (int) $row->attempt,
                $score,
                $row->finished_at !== null ? $total : null,
                $percent,
                $row->elapsed_seconds !== null ? (int) $row->elapsed_seconds : null,
                $row->finished_at ? 'Hoàn thành' : 'Đang làm',
                $this->formatDateTime($row->session_date),
            ], [2, 5]);
        }

        $this->finalizeSheet($sheet, count($headers), $rowIndex - 1, [26, 28, 16, 28, 30, 14, 14, 10, 10, 12, 12, 16, 15, 18]);
    }

    private function writeContentSheet(Worksheet $sheet): void
    {
        $sheet->setTitle('Nội dung & Đào tạo');
        $headers = ['Loại', 'Tiêu đề / Tên', 'Trạng thái', 'Lượt xem', 'Ngày tạo'];
        $rowIndex = $this->writeHeaderRow($sheet, $headers);

        foreach (Post::query()->latest('created_at')->limit(500)->get(['title', 'is_published', 'views_count', 'created_at']) as $post) {
            $this->writeDataRow($sheet, $rowIndex++, [
                'Bài viết',
                $post->title,
                $post->is_published ? 'Đã xuất bản' : 'Bản nháp',
                (int) $post->views_count,
                $this->formatDateTime($post->created_at),
            ]);
        }

        foreach (Course::query()->latest('created_at')->limit(500)->get(['title', 'is_active', 'created_at']) as $course) {
            $this->writeDataRow($sheet, $rowIndex++, [
                'Khóa học',
                $course->title,
                $course->is_active ? 'Đang hiển thị' : 'Đang ẩn',
                null,
                $this->formatDateTime($course->created_at),
            ]);
        }

        foreach (Service::query()->latest('created_at')->limit(500)->get(['name', 'is_active', 'created_at']) as $service) {
            $this->writeDataRow($sheet, $rowIndex++, [
                'Dịch vụ',
                $service->name,
                $service->is_active ? 'Đang hiển thị' : 'Đang ẩn',
                null,
                $this->formatDateTime($service->created_at),
            ]);
        }

        foreach (Project::query()->latest('created_at')->limit(500)->get(['title', 'is_active', 'created_at']) as $project) {
            $this->writeDataRow($sheet, $rowIndex++, [
                'Dự án',
                $project->title,
                $project->is_active ? 'Đang hiển thị' : 'Đang ẩn',
                null,
                $this->formatDateTime($project->created_at),
            ]);
        }

        foreach (Document::query()->latest('created_at')->limit(500)->get(['title', 'is_active', 'created_at']) as $document) {
            $this->writeDataRow($sheet, $rowIndex++, [
                'Tài liệu',
                $document->title,
                $document->is_active ? 'Đang hiển thị' : 'Đang ẩn',
                null,
                $this->formatDateTime($document->created_at),
            ]);
        }

        $this->finalizeSheet($sheet, count($headers), $rowIndex - 1, [16, 48, 18, 14, 18]);
    }

    private function writeContactsSheet(Worksheet $sheet): void
    {
        $contacts = Contact::query()->latest('created_at')->limit(1000)->get();
        $this->populateContactsSheet($sheet, $contacts);
    }

    /** @param iterable<Contact> $contacts */
    private function populateContactsSheet(Worksheet $sheet, iterable $contacts): void
    {
        $sheet->setTitle('Liên hệ khách hàng');
        $headers = ['Họ tên', 'Email', 'Số điện thoại', 'Tiêu đề', 'Nội dung', 'Trạng thái', 'Ngày gửi'];
        $rowIndex = $this->writeHeaderRow($sheet, $headers);

        foreach ($contacts as $contact) {
            $this->writeDataRow($sheet, $rowIndex++, [
                $contact->name,
                $contact->email,
                $contact->phone,
                $contact->subject,
                $contact->message,
                $contact->is_read ? 'Đã đọc' : 'Chưa đọc',
                $this->formatDateTime($contact->created_at),
            ], [2]);
        }

        $this->finalizeSheet($sheet, count($headers), $rowIndex - 1, [26, 28, 16, 30, 44, 14, 18]);
    }

    /** @param list<string> $headers */
    private function writeHeaderRow(Worksheet $sheet, array $headers, int $row = 1): int
    {
        foreach ($headers as $column => $label) {
            $sheet->setCellValueExplicit([$column + 1, $row], $label, DataType::TYPE_STRING);
        }

        $lastColumn = Coordinate::stringFromColumnIndex(count($headers));
        $range = 'A'.$row.':'.$lastColumn.$row;

        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '065F46'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ECFDF5'],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);
        $sheet->getRowDimension($row)->setRowHeight(24);

        return $row + 1;
    }

    /**
     * @param  list<string|int|float|null>  $values
     * @param  list<int>  $forceStringColumns
     */
    private function writeDataRow(Worksheet $sheet, int $row, array $values, array $forceStringColumns = []): void
    {
        foreach ($values as $column => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            if (in_array($column, $forceStringColumns, true) || is_string($value)) {
                $sheet->setCellValueExplicit([$column + 1, $row], (string) $value, DataType::TYPE_STRING);
            } else {
                $sheet->setCellValue([$column + 1, $row], $value);
            }
        }
    }

    /** @param list<int> $widths */
    private function finalizeSheet(Worksheet $sheet, int $columnCount, int $lastRow, array $widths, int $headerRow = 1): void
    {
        foreach ($widths as $index => $width) {
            $columnLetter = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->getColumnDimension($columnLetter)->setWidth($width);
        }

        $lastColumn = Coordinate::stringFromColumnIndex($columnCount);
        if ($lastRow >= $headerRow) {
            $sheet->getStyle('A'.($headerRow + 1).':'.$lastColumn.$lastRow)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
            $sheet->setAutoFilter('A'.$headerRow.':'.$lastColumn.$lastRow);
        }

        $sheet->freezePane('A'.($headerRow + 1));
    }

    private function formatDateTime(mixed $value): string
    {
        if (! $value) {
            return '';
        }

        return CarbonImmutable::parse($value)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i');
    }
}
