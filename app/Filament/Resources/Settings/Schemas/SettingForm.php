<?php

namespace App\Filament\Resources\Settings\Schemas;

use App\Support\OptimizedImageUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cấu hình hệ thống')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('label_placeholder')
                            ->label('Tên cấu hình')
                            ->content(fn ($record) => $record ? $record->label : '-')
                            ->visible(fn ($record) => $record !== null),
                        \Filament\Forms\Components\Placeholder::make('description_placeholder')
                            ->label('Mô tả ý nghĩa')
                            ->content(fn ($record) => $record ? $record->description : '-')
                            ->visible(fn ($record) => $record !== null),
                        TextInput::make('key')
                            ->label('Khóa kỹ thuật (Key)')
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn ($context) => $context === 'edit')
                            ->unique(ignoreRecord: true),
                        Select::make('group')
                            ->label('Nhóm cấu hình')
                            ->options([
                                'general' => 'Thông tin chung',
                                'contact' => 'Thông tin liên hệ',
                                'social' => 'Mạng xã hội',
                                'seo' => 'SEO mặc định',
                            ])
                            ->default('general')
                            ->required(),
                        Select::make('type')
                            ->label('Kiểu dữ liệu')
                            ->options([
                                'text' => 'Văn bản ngắn (Text)',
                                'textarea' => 'Văn bản dài (Textarea)',
                                'editor' => 'Trình soạn thảo (Rich Text)',
                                'image' => 'Hình ảnh (Image)',
                                'file' => 'Tệp tin (File / PDF)',
                            ])
                            ->default('text')
                            ->required()
                            ->live(),

                        TextInput::make('value')
                            ->hidden()
                            ->dehydrated(true),

                        TextInput::make('text_value')
                            ->label('Giá trị')
                            ->visible(fn (callable $get) => in_array($get('type'), ['text', null, '']))
                            ->columnSpanFull(),

                        Textarea::make('textarea_value')
                            ->label('Giá trị')
                            ->visible(fn (callable $get) => $get('type') === 'textarea')
                            ->rows(5)
                            ->columnSpanFull(),

                        OptimizedImageUpload::configureRichEditor(
                            RichEditor::make('editor_value')->label('Giá trị'),
                        )
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('settings/attachments')
                            ->visible(fn (callable $get) => $get('type') === 'editor')
                            ->columnSpanFull(),

                        OptimizedImageUpload::configure(
                            FileUpload::make('image_value')->label('Hình ảnh'),
                            2560,
                            1440,
                            84,
                        )
                            ->disk('public')
                            ->directory('settings')
                            ->visible(fn (callable $get) => $get('type') === 'image')
                            ->columnSpanFull(),

                        FileUpload::make('file_value')
                            ->label('Tệp tin (PDF, Doc, v.v.)')
                            ->disk('public')
                            ->directory('settings')
                            ->maxSize(config('uploads.max_size_kb'))
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->visible(fn (callable $get) => $get('type') === 'file')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
