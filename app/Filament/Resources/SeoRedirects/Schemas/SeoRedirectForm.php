<?php

namespace App\Filament\Resources\SeoRedirects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SeoRedirectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin Redirect SEO')
                    ->schema([
                        TextInput::make('old_url')
                            ->label('Đường dẫn cũ (ví dụ: /dich-vu-cu)')
                            ->required()
                            ->maxLength(191)
                            ->unique(ignoreRecord: true),
                        TextInput::make('new_url')
                            ->label('Đường dẫn mới (ví dụ: /dich-vu-moi)')
                            ->required()
                            ->maxLength(191),
                        Select::make('status_code')
                            ->label('Mã trạng thái HTTP')
                            ->options([
                                301 => '301 Moved Permanently',
                                302 => '302 Found (Temporary)',
                            ])
                            ->default(301)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true),
                    ]),
            ]);
    }
}
