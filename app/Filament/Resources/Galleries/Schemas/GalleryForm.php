<?php

namespace App\Filament\Resources\Galleries\Schemas;

use App\Support\OptimizedImageUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cấu hình hình ảnh')
                    ->schema([
                        OptimizedImageUpload::configure(
                            FileUpload::make('image')->label('Hình ảnh'),
                            1024,
                            1024,
                            84,
                            'Nên tải lên ảnh có tỉ lệ vuông 1:1 (ví dụ 1024×1024px) để hiển thị tốt nhất. Ảnh sẽ được nén và tự động chuyển sang WebP để tăng tốc độ tải trang.',
                        )
                            ->imageAspectRatio('1:1')
                            ->imageEditorAspectRatioOptions(['1:1'])
                            ->automaticallyCropImagesToAspectRatio()
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('1024')
                            ->automaticallyResizeImagesToHeight('1024')
                            ->disk('public')
                            ->directory('gallery')
                            ->required(),
                        TextInput::make('title')
                            ->label('Tiêu đề / Thẻ alt')
                            ->maxLength(191)
                            ->placeholder('Nhập tiêu đề hoặc mô tả ngắn cho ảnh...'),
                        Toggle::make('is_active')
                            ->label('Hoạt động')
                            ->default(true),
                        TextInput::make('sort_order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }
}
