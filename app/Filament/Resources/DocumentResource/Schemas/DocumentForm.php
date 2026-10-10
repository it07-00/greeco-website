<?php

namespace App\Filament\Resources\DocumentResource\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin văn bản / tài liệu')
                    ->schema([
                        TextInput::make('number')
                            ->label('Số hiệu / Ký hiệu')
                            ->maxLength(191),
                        TextInput::make('type')
                            ->label('Loại văn bản (Ví dụ: Luật Quốc hội, Nghị định...)')
                            ->required()
                            ->maxLength(191),
                        TextInput::make('title')
                            ->label('Trích yếu nội dung (Tiêu đề)')
                            ->required()
                            ->maxLength(191),
                        Textarea::make('description')
                            ->label('Mô tả ngắn gọn')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('link')
                            ->label('Đường dẫn chi tiết / Tải về')
                            ->maxLength(191),
                        Toggle::make('is_active')
                            ->label('Hiển thị')
                            ->default(true),
                        TextInput::make('sort_order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }
}
