<?php

namespace App\Filament\Resources\CourseRegistrations\Tables;

use App\Models\CourseRegistration;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CourseRegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('code')
                    ->label('Mã đăng ký')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('course.title')
                    ->label('Khóa học')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                TextColumn::make('company_name')
                    ->label('Doanh nghiệp')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contact_name')
                    ->label('Người liên hệ')
                    ->searchable(),
                TextColumn::make('contact_phone')
                    ->label('Điện thoại')
                    ->searchable(),
                TextColumn::make('attendee_count')
                    ->label('Học viên')
                    ->numeric()
                    ->badge()
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Nguồn')
                    ->formatStateUsing(fn (?string $state): string => $state === 'qr' ? 'QR' : 'Website')
                    ->badge()
                    ->color(fn (?string $state): string => $state === 'qr' ? 'info' : 'gray'),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(
                        fn (?string $state): string => CourseRegistration::STATUSES[$state] ?? (string) $state
                    )
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'contacted' => 'info',
                        'cancelled' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
                IconColumn::make('is_read')
                    ->label('Đã xem')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày đăng ký')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('course_id')
                    ->label('Khóa học')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options(CourseRegistration::STATUSES),
                SelectFilter::make('source')
                    ->label('Nguồn')
                    ->options([
                        'website' => 'Website',
                        'qr' => 'QR',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
