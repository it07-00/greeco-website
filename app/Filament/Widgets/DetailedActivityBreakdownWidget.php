<?php

namespace App\Filament\Widgets;

use App\Services\ActivityStatisticsService;
use App\Services\ActivityStatsExcelExport;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DetailedActivityBreakdownWidget extends Widget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.detailed-activity-breakdown-widget';

    /**
     * @return list<array{
     *     label: string,
     *     group: string,
     *     url: string,
     *     today: int,
     *     week: int,
     *     month: int,
     *     year: int,
     *     total: int,
     *     highlight_label: string,
     *     highlight_tone: string
     * }>
     */
    public function getRows(): array
    {
        return app(ActivityStatisticsService::class)->getSummaryRows();
    }

    public function exportExcel(): StreamedResponse
    {
        return app(ActivityStatsExcelExport::class)->download();
    }

    public function getDateSummary(): string
    {
        return Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y H:i');
    }
}
