<?php

namespace App\Filament\Resources\CourseRegistrations\Pages;

use App\Filament\Resources\CourseRegistrations\CourseRegistrationResource;
use App\Services\ActivityStatsExcelExport;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListCourseRegistrations extends ListRecords
{
    protected static string $resource = CourseRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label('Xuất Excel (.xlsx)')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('success')
                ->action(function () {
                    $records = $this->getFilteredTableQuery()
                        ->with(['course:id,title', 'attendees'])
                        ->get();

                    return app(ActivityStatsExcelExport::class)->downloadCourseRegistrations($records);
                }),
        ];
    }
}
