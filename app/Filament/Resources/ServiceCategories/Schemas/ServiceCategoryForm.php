<?php

namespace App\Filament\Resources\ServiceCategories\Schemas;

use App\Support\OptimizedImageUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin danh mục dịch vụ')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên danh mục')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3),
                        OptimizedImageUpload::configure(
                            FileUpload::make('image')->label('Ảnh hiển thị'),
                            1024,
                            1024,
                            84,
                            'Nên dùng ảnh vuông tối thiểu 1024×1024px. Ảnh sẽ được tối ưu sang WebP khi lưu.',
                        )
                            ->disk('public')
                            ->directory('service_categories')
                            ->columnSpanFull(),
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
