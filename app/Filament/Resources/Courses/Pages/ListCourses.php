<?php

namespace App\Filament\Resources\Courses\Pages;

use App\Filament\Resources\Courses\CourseResource;
use App\Services\CourseQrSpreadsheetExporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadSharedQr')
                ->label('Tải QR chung')
                ->icon('heroicon-o-qr-code')
                ->color('gray')
                ->url(fn (): string => route('course-registration.selection-qr', [
                    'format' => 'png',
                    'download' => 1,
                ]))
                ->openUrlInNewTab(),
            Action::make('exportCourseQr')
                ->label('Xuất QR khóa học')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => app(CourseQrSpreadsheetExporter::class)->download()),
            CreateAction::make(),
        ];
    }
}
