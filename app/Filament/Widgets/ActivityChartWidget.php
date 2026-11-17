<?php

namespace App\Filament\Widgets;

use App\Models\Contact;
use App\Models\CourseRegistration;
use App\Models\EduQuiz\Result;
use Carbon\CarbonImmutable;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

class ActivityChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Biểu đồ Hoạt động Đào tạo, Trắc nghiệm & Liên hệ';

    protected ?string $description = 'Theo dõi xu hướng đăng ký khóa học, lượt nộp bài EduQuiz và liên hệ khách hàng theo Tuần / Tháng / Năm';

    protected ?string $maxHeight = '320px';

    public ?string $filter = 'week';

    /**
     * @return array<string, string>
     */
    protected function getFilters(): ?array
    {
        return [
            'week' => '7 ngày gần nhất',
            'month' => '30 ngày gần nhất',
            'year' => '12 tháng năm nay',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        return match ($this->filter ?: 'week') {
            'month' => $this->getDailyData(30),
            'year' => $this->getMonthlyDataForYear(),
            default => $this->getDailyData(7),
        };
    }

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * @return array<string, mixed>
     */
    private function getDailyData(int $days): array
    {
        $end = now()->endOfDay();
        $start = now()->subDays($days - 1)->startOfDay();

        $registrations = CourseRegistration::query()->whereBetween('created_at', [$start, $end])->pluck('created_at');
        $results = Result::query()->whereBetween('created_at', [$start, $end])->pluck('created_at');
        $contacts = Contact::query()->whereBetween('created_at', [$start, $end])->pluck('created_at');

        $labels = [];
        $regCounts = [];
        $quizCounts = [];
        $contactCounts = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $key = $day->format('Y-m-d');
            $labels[] = $day->format('d/m');
            $regCounts[$key] = 0;
            $quizCounts[$key] = 0;
            $contactCounts[$key] = 0;
        }

        $this->tallyByFormat($registrations, 'Y-m-d', $regCounts);
        $this->tallyByFormat($results, 'Y-m-d', $quizCounts);
        $this->tallyByFormat($contacts, 'Y-m-d', $contactCounts);

        return $this->formatDatasets(
            $labels,
            array_values($regCounts),
            array_values($quizCounts),
            array_values($contactCounts),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function getMonthlyDataForYear(): array
    {
        $start = now()->startOfYear();
        $end = now()->endOfYear();

        $registrations = CourseRegistration::query()->whereBetween('created_at', [$start, $end])->pluck('created_at');
        $results = Result::query()->whereBetween('created_at', [$start, $end])->pluck('created_at');
        $contacts = Contact::query()->whereBetween('created_at', [$start, $end])->pluck('created_at');

        $labels = [];
        $regCounts = [];
        $quizCounts = [];
        $contactCounts = [];

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = 'Tháng '.$month;
            $regCounts[$month] = 0;
            $quizCounts[$month] = 0;
            $contactCounts[$month] = 0;
        }

        $this->tallyByFormat($registrations, 'n', $regCounts);
        $this->tallyByFormat($results, 'n', $quizCounts);
        $this->tallyByFormat($contacts, 'n', $contactCounts);

        return $this->formatDatasets(
            $labels,
            array_values($regCounts),
            array_values($quizCounts),
            array_values($contactCounts),
        );
    }

    /**
     * @param  Collection<int, mixed>  $timestamps
     * @param  array<int|string, int>  $buckets
     */
    private function tallyByFormat(Collection $timestamps, string $format, array &$buckets): void
    {
        foreach ($timestamps as $timestamp) {
            if (! $timestamp) {
                continue;
            }
            $key = CarbonImmutable::parse($timestamp)->format($format);
            if ($format === 'n') {
                $key = (int) $key;
            }
            if (array_key_exists($key, $buckets)) {
                $buckets[$key]++;
            }
        }
    }

    /**
     * @param  list<string>  $labels
     * @param  list<int>  $registrations
     * @param  list<int>  $results
     * @param  list<int>  $contacts
     * @return array<string, mixed>
     */
    private function formatDatasets(array $labels, array $registrations, array $results, array $contacts): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Đăng ký khóa học',
                    'data' => $registrations,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.14)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Bài nộp EduQuiz',
                    'data' => $results,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Liên hệ khách hàng',
                    'data' => $contacts,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
