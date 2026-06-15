<?php

namespace App\Filament\Resources\CourseCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CourseCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên danh mục')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('icon')
                    ->label('Icon Class (Ví dụ: fa-certificate)')
                    ->placeholder('fa-certificate'),
                TextInput::make('color')
                    ->label('Màu sắc chữ (Hex)')
                    ->placeholder('#008f79'),
                TextInput::make('bg')
                    ->label('Màu nền (Hex)')
                    ->placeholder('#e6f4f1'),
                TextInput::make('sort_order')
                    ->label('Thứ tự sắp xếp')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
