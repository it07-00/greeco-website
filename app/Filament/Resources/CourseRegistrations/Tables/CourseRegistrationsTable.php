<?php

namespace App\Filament\Resources\CourseRegistrations\Tables;

use App\Models\CourseRegistration;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
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
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Đã sao chép mã đăng ký'),
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
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Đã sao chép số điện thoại'),
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
                TernaryFilter::make('is_read')
                    ->label('Trạng thái xem')
                    ->trueLabel('Đã xem')
                    ->falseLabel('Chưa xem'),
            ])
            ->recordActions([
                Action::make('mark_read')
                    ->label('Đã xem')
                    ->icon(Heroicon::OutlinedEye)
                    ->color('gray')
                    ->visible(fn (CourseRegistration $record): bool => ! $record->is_read)
                    ->action(fn (CourseRegistration $record) => $record->update(['is_read' => true])),
                Action::make('confirm')
                    ->label('Xác nhận')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->visible(fn (CourseRegistration $record): bool => $record->status !== 'confirmed')
                    ->requiresConfirmation()
                    ->action(fn (CourseRegistration $record) => $record->update(['status' => 'confirmed', 'is_read' => true])),
                EditAction::make(),
            ]);
    }
}
