<?php

namespace App\Support;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class ContentTranslations
{
    public const FIELDS = [
        'Page' => ['title', 'excerpt', 'content', 'meta_title', 'meta_description', 'meta_keywords'],
        'Post' => ['title', 'excerpt', 'content', 'meta_title', 'meta_description', 'meta_keywords', 'tags'],
        'Service' => ['name', 'short_description', 'content', 'meta_title', 'meta_description', 'meta_keywords'],
        'Project' => ['title', 'excerpt', 'content', 'client_name', 'location', 'meta_title', 'meta_description'],
        'Course' => ['title', 'description', 'content', 'tag', 'price', 'instructor_role', 'instructor_description', 'curriculum', 'meta_title', 'meta_description', 'meta_keywords'],
        'ServiceCategory' => ['name', 'description'],
        'PostCategory' => ['name', 'description'],
        'CourseCategory' => ['name'],
        'TeamMember' => ['role', 'description'],
        'Gallery' => ['title'],
        'Document' => ['title', 'type', 'description'],
    ];

    public static function section(string $model): Section
    {
        return Section::make('English / Tiếng Anh')
            ->description('Nhập nội dung tiếng Anh. Để trống để hiển thị bản gốc tiếng Việt. Slug, tên riêng và tệp dùng chung.')
            ->schema(array_map(function (string $field) {
                $name = 'translations.en.'.$field;
                $label = ucfirst(str_replace('_', ' ', $field)).' (EN)';

                return match ($field) {
                    'content' => OptimizedImageUpload::configureRichEditor(RichEditor::make($name)->label($label))
                        ->fileAttachmentsDisk('public')->fileAttachmentsDirectory('translations/attachments')->columnSpanFull(),
                    'tags' => TagsInput::make($name)->label($label),
                    'curriculum' => Repeater::make($name)->label($label)->defaultItems(0)->schema([
                        TextInput::make('title')->label('Chapter')->required(),
                        Repeater::make('lessons')->defaultItems(0)->schema([
                            TextInput::make('title')->label('Lesson')->required(),
                            TextInput::make('duration')->label('Duration'),
                        ]),
                    ])->columnSpanFull(),
                    'excerpt', 'description', 'meta_description', 'instructor_description' => Textarea::make($name)->label($label)->rows(4),
                    default => TextInput::make($name)->label($label)->maxLength(191),
                };
            }, self::FIELDS[$model]))
            ->collapsible()->collapsed()->columnSpanFull();
    }
}
