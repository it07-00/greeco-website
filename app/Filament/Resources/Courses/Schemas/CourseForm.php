<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Models\Course;
use App\Support\OptimizedImageUpload;
use App\Support\PublicSlug;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('CourseTabs')
                    ->tabs([
                        Tab::make('Thông tin chung')
                            ->schema([
                                Select::make('course_category_id')
                                    ->label('Danh mục khóa học')
                                    ->relationship('category', 'name')
                                    ->nullable()
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('title')
                                    ->maxLength(500)
                                    ->label('Tiêu đề khóa học')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                                        $oldSlug = Str::slug($old ?? '');
                                        $currentSlug = $get('slug');

                                        if (blank($currentSlug) || $currentSlug === $oldSlug) {
                                            $set('slug', Str::slug($state ?? ''));
                                        }
                                    }),
                                PublicSlug::configure(TextInput::make('slug'), Course::class)
                                    ->label('Slug')
                                    ->required()
                                    ->dehydrateStateUsing(fn (?string $state) => Str::slug($state ?? ''))
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? '')))
                                    ->live(onBlur: true),
                                Textarea::make('description')
                                    ->label('Mô tả ngắn (Hiển thị ở danh sách thẻ)')
                                    ->columnSpanFull()
                                    ->rows(3),
                                RichEditor::make('content')
                                    ->label('Nội dung chi tiết khóa học')
                                    ->columnSpanFull(),
                                OptimizedImageUpload::configure(
                                    FileUpload::make('image')->label('Hình ảnh đại diện khóa học'),
                                    1200,
                                    1200,
                                )
                                    ->disk('public')
                                    ->directory('courses'),
                                TextInput::make('tag')
                                    ->maxLength(500)
                                    ->label('Nhãn nổi bật (Ví dụ: BÁN CHẠY, HOT, MỚI)'),
                                Select::make('badge_color')
                                    ->label('Màu sắc nhãn')
                                    ->options([
                                        'bg-success' => 'Xanh lá (Success)',
                                        'bg-danger' => 'Đỏ (Danger)',
                                        'bg-info' => 'Xanh dương (Info)',
                                        'bg-warning' => 'Vàng (Warning)',
                                        'bg-primary' => 'Xanh lá thương hiệu (Primary)',
                                    ])
                                    ->default('bg-success'),
                            ]),

                        Tab::make('SEO')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(500),
                                Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3),
                                TextInput::make('meta_keywords')
                                    ->maxLength(500)
                                    ->label('Meta Keywords'),
                                TextInput::make('canonical_url')
                                    ->maxLength(500)
                                    ->label('Canonical URL'),
                                OptimizedImageUpload::configure(
                                    FileUpload::make('og_image')->label('Ảnh chia sẻ Facebook/Zalo'),
                                    1600,
                                    900,
                                )
                                    ->disk('public')
                                    ->directory('seo/og-images'),
                                Toggle::make('is_indexable')
                                    ->label('Cho Google index')
                                    ->default(true),
                            ]),

                        Tab::make('Học phí & Giảng viên')
                            ->schema([
                                TextInput::make('price')
                                    ->maxLength(191)
                                    ->label('Giá hiện tại (Ví dụ: 1.500.000đ hoặc Liên hệ)')
                                    ->required()
                                    ->default('Liên hệ'),
                                TextInput::make('original_price')
                                    ->maxLength(191)
                                    ->label('Giá gốc (Nếu có, ví dụ: 2.000.000đ)'),
                                TextInput::make('discount')
                                    ->maxLength(191)
                                    ->label('Thẻ giảm giá (Ví dụ: -25%)'),
                                TextInput::make('instructor_name')
                                    ->maxLength(500)
                                    ->label('Tên giảng viên'),
                                TextInput::make('instructor_role')
                                    ->maxLength(500)
                                    ->label('Vai trò/Chức danh giảng viên')
                                    ->placeholder('Ví dụ: Chuyên gia cấp cao về ISO & ESG'),
                                OptimizedImageUpload::configure(
                                    FileUpload::make('instructor_image')->label('Ảnh đại diện giảng viên'),
                                    400,
                                    400,
                                )
                                    ->disk('public')
                                    ->directory('instructors'),
                                Textarea::make('instructor_description')
                                    ->label('Mô tả/Tiểu sử giảng viên')
                                    ->columnSpanFull()
                                    ->rows(3),
                            ]),

                        Tab::make('Chương trình học')
                            ->schema([
                                Repeater::make('curriculum')
                                    ->label('Chương trình học (Chapters & Lessons)')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Tên chương (Ví dụ: Chương 1: Tổng quan...)')
                                            ->required(),
                                        Repeater::make('lessons')
                                            ->label('Danh sách bài học')
                                            ->schema([
                                                TextInput::make('title')
                                                    ->label('Tên bài học')
                                                    ->required(),
                                                TextInput::make('duration')
                                                    ->label('Thời lượng bài học (Ví dụ: 15:30)'),
                                            ])
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Thiết lập')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Hiển thị trên website')
                                    ->required()
                                    ->default(true),
                                TextInput::make('sort_order')
                                    ->label('Thứ tự sắp xếp')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
