<?php

namespace App\Filament\Resources\Banners\Schemas;

use App\Support\OptimizedImageUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cấu hình Banner')
                    ->schema([
                        OptimizedImageUpload::configure(
                            FileUpload::make('image')->label('Hình ảnh banner'),
                            1920,
                            910,
                            84,
                            'Nên thiết kế ảnh đúng kích cỡ 1920×910px. Đặt nội dung quan trọng ở vùng giữa, tránh sát mép. Ảnh sẽ được crop theo khung này và chuyển sang WebP để tải nhanh hơn.',
                        )
                            ->imageAspectRatio('192:91')
                            ->imageEditorAspectRatioOptions(['192:91'])
                            ->automaticallyCropImagesToAspectRatio()
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('1920')
                            ->automaticallyResizeImagesToHeight('910')
                            ->validationMessages([
                                'dimensions' => 'Ảnh banner phải đúng tỉ lệ 1920×910px. Vui lòng crop ảnh theo khung gợi ý trước khi lưu.',
                            ])
                            ->disk('public')
                            ->directory('banners')
                            ->required(),
                        TextInput::make('url')
                            ->label('Đường dẫn liên kết (khi click vào ảnh)')
                            ->maxLength(191),
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
