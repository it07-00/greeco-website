<?php

namespace App\Filament\Resources\Contacts\Tables;

use App\Models\Contact;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Họ tên')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Đã sao chép email'),
                TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Đã sao chép số điện thoại'),
                TextColumn::make('subject')
                    ->label('Tiêu đề')
                    ->searchable(),
                IconColumn::make('is_read')
                    ->label('Đã đọc')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày gửi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Trạng thái đọc')
                    ->trueLabel('Đã đọc')
                    ->falseLabel('Chưa đọc'),
            ])
            ->recordActions([
                Action::make('mark_read')
                    ->label('Đã đọc')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->visible(fn (Contact $record): bool => ! $record->is_read)
                    ->action(fn (Contact $record) => $record->update(['is_read' => true])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
