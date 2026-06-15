<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_category_id')
                    ->label('Danh mục khóa học')
                    ->relationship('category', 'name')
                    ->nullable()
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->label('Tiêu đề khóa học')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->label('Mô tả ngắn (Hiển thị ở danh sách thẻ)')
                    ->columnSpanFull()
                    ->rows(3),
                RichEditor::make('content')
                    ->label('Nội dung chi tiết khóa học')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Hình ảnh đại diện khóa học')
                    ->image()
                    ->directory('courses'),
                TextInput::make('tag')
                    ->label('Nhãn nổi bật (Ví dụ: BÁN CHẠY, HOT, MỚI)'),
                TextInput::make('badge_color')
                    ->label('Màu sắc nhãn (Ví dụ: bg-success, bg-danger, bg-info)')
                    ->default('bg-success'),
                TextInput::make('rating')
                    ->label('Đánh giá (Số sao từ 1.0 đến 5.0)')
                    ->required()
                    ->numeric()
                    ->default(5.0),
                TextInput::make('rating_count')
                    ->label('Số lượng đánh giá')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('price')
                    ->label('Giá hiện tại (Ví dụ: 1.500.000đ hoặc Liên hệ)')
                    ->required()
                    ->default('Liên hệ'),
                TextInput::make('original_price')
                    ->label('Giá gốc (Nếu có, ví dụ: 2.000.000đ)'),
                TextInput::make('discount')
                    ->label('Thẻ giảm giá (Ví dụ: -25%)'),
                
                // Instructor section
                TextInput::make('instructor_name')
                    ->label('Tên giảng viên'),
                TextInput::make('instructor_role')
                    ->label('Vai trò/Chức danh giảng viên')
                    ->placeholder('Ví dụ: Chuyên gia cấp cao về ISO & ESG'),
                FileUpload::make('instructor_image')
                    ->label('Ảnh đại diện giảng viên')
                    ->image()
                    ->directory('instructors'),
                Textarea::make('instructor_description')
                    ->label('Mô tả/Tiểu sử giảng viên')
                    ->columnSpanFull()
                    ->rows(3),

                // Curriculum block
                Repeater::make('curriculum')
                    ->label('Chương trình học (Chapters & Lessons)')
                    ->schema([
                        TextInput::make('title')
                            ->label('Tên chương (Ví dụ: Chương 1: Tổng quan...)')
                            ->required(),
                        TextInput::make('progress')
                            ->label('Tiến độ hiển thị (Ví dụ: 5/8 bài)'),
                        Repeater::make('lessons')
                            ->label('Danh sách bài học')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Tên bài học')
                                    ->required(),
                                TextInput::make('duration')
                                    ->label('Thời lượng bài học (Ví dụ: 15:30)')
                                    ->required(),
                                Toggle::make('completed')
                                    ->label('Đã học xong (mặc định cho tài khoản demo)')
                                    ->default(false),
                            ])
                            ->columnSpanFull()
                    ])
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Hiển thị trên website')
                    ->required()
                    ->default(true),
                TextInput::make('sort_order')
                    ->label('Thứ tự sắp xếp')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
