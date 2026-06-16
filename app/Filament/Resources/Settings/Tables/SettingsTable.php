<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('key')
                    ->label('Tên cấu hình')
                    ->state(fn ($record) => $record->label)
                    ->description(fn ($record) => $record->key)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Mô tả ý nghĩa')
                    ->state(fn ($record) => $record->description)
                    ->wrap(),
                TextColumn::make('group')
                    ->label('Nhóm')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'general' => 'gray',
                        'contact' => 'info',
                        'social' => 'warning',
                        'seo' => 'success',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('value')
                    ->label('Giá trị')
                    ->limit(50)
                    ->searchable()
                    ->html()
                    ->state(fn ($record) => $record->type === 'image' && $record->value ? '<img src="'.asset('storage/'.$record->value).'" style="max-height: 35px; max-width: 100px; border-radius: 4px; object-fit: cover;"/>' : e($record->value)),
                TextColumn::make('type')
                    ->label('Kiểu')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
