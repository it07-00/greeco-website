<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->whereNotIn('key', ['phone_name', 'phone_2_name']))
            ->groups([
                Group::make('group')
                    ->label('Nhóm cấu hình')
                    ->getTitleFromRecordUsing(fn ($record): string => match ($record->group) {
                        'general' => 'Logo & nhận diện',
                        'contact' => 'Liên hệ',
                        'social' => 'Mạng xã hội',
                        'seo' => 'SEO',
                        default => 'Khác',
                    })
                    ->collapsible(),
            ])
            ->defaultGroup('group')
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
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'general' => 'Logo & nhận diện',
                        'contact' => 'Liên hệ',
                        'social' => 'Mạng xã hội',
                        'seo' => 'SEO',
                        default => $state,
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
