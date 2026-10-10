<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Project;
use App\Support\OptimizedImageUpload;
use App\Support\PublicSlug;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('ProjectTabs')
                    ->tabs([
                        Tab::make('Thông tin chính')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Tên dự án')
                                    ->required()
                                    ->maxLength(191)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                PublicSlug::configure(TextInput::make('slug'), Project::class)
                                    ->label('Slug')
                                    ->required(),
                                TextInput::make('client_name')
                                    ->label('Khách hàng / Đối tác')
                                    ->maxLength(191),
                                TextInput::make('location')
                                    ->label('Địa điểm')
                                    ->maxLength(191),
                                DatePicker::make('started_at')
                                    ->label('Ngày bắt đầu'),
                                DatePicker::make('completed_at')
                                    ->label('Ngày hoàn thành'),
                                Textarea::make('excerpt')
                                    ->maxLength(191)
                                    ->label('Mô tả ngắn/Tóm tắt')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])->columns(2),
                        Tab::make('Nội dung')
                            ->schema([
                                OptimizedImageUpload::configureRichEditor(
                                    RichEditor::make('content')->label('Nội dung chi tiết'),
                                )
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('projects/attachments')
                                    ->resizableImages()
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Hình ảnh')
                            ->schema([
                                OptimizedImageUpload::configure(
                                    FileUpload::make('thumbnail')->label('Ảnh đại diện dự án'),
                                    2560,
                                    1440,
                                )
                                    ->disk('public')
                                    ->directory('projects'),
                                OptimizedImageUpload::configure(
                                    FileUpload::make('og_image')->label('Ảnh chia sẻ Facebook/Zalo'),
                                    1600,
                                    900,
                                )
                                    ->disk('public')
                                    ->directory('seo/og-images'),
                            ]),
                        Tab::make('SEO')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(191),
                                Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3),
                                TextInput::make('canonical_url')
                                    ->maxLength(191)
                                    ->label('Canonical URL'),
                            ]),
                        Tab::make('Trạng thái')
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Nổi bật')
                                    ->default(false),
                                Toggle::make('is_indexable')
                                    ->label('Cho Google index')
                                    ->default(true),
                                Toggle::make('is_active')
                                    ->label('Hiển thị')
                                    ->default(true),
                                TextInput::make('sort_order')
                                    ->label('Thứ tự hiển thị')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
