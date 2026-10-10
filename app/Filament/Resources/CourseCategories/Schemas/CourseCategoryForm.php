<?php

namespace App\Filament\Resources\CourseCategories\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
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
                    ->maxLength(191)
                    ->label('Tên danh mục')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')
                    ->maxLength(191)
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('icon')
                    ->label('Biểu tượng (Icon)')
                    ->options([
                        'fa-certificate' => 'Chứng chỉ / ISO (fa-certificate)',
                        'fa-shield-heart' => 'An toàn / HSE (fa-shield-heart)',
                        'fa-leaf' => 'Phát triển bền vững / ESG (fa-leaf)',
                        'fa-cloud' => 'Khí thải / Carbon (fa-cloud)',
                        'fa-globe' => 'Môi trường (fa-globe)',
                        'fa-award' => 'Giải thưởng (fa-award)',
                        'fa-graduation-cap' => 'Đào tạo / Training (fa-graduation-cap)',
                        'fa-ellipsis' => 'Khác (fa-ellipsis)',
                    ])
                    ->searchable()
                    ->default('fa-certificate'),
                ColorPicker::make('color')
                    ->label('Màu sắc chữ (Hex)')
                    ->placeholder('#008f79'),
                ColorPicker::make('bg')
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
