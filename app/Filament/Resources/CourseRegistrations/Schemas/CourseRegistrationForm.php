<?php

namespace App\Filament\Resources\CourseRegistrations\Schemas;

use App\Models\CourseRegistration;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin đăng ký')
                    ->schema([
                        TextInput::make('code')
                            ->label('Mã đăng ký')
                            ->readOnly(),
                        Select::make('course_id')
                            ->label('Khóa học')
                            ->relationship('course', 'title')
                            ->disabled(),
                        TextInput::make('company_name')
                            ->label('Tên công ty')
                            ->readOnly(),
                        TextInput::make('tax_code')
                            ->label('Mã số thuế')
                            ->readOnly(),
                        Textarea::make('company_address')
                            ->label('Địa chỉ')
                            ->rows(2)
                            ->readOnly()
                            ->columnSpanFull(),
                        TextInput::make('contact_name')
                            ->label('Người liên hệ chính')
                            ->readOnly(),
                        TextInput::make('contact_email')
                            ->label('Email liên hệ')
                            ->readOnly(),
                        TextInput::make('contact_phone')
                            ->label('Số điện thoại')
                            ->readOnly(),
                        TextInput::make('invoice_email')
                            ->label('Email nhận hóa đơn')
                            ->readOnly(),
                        TextInput::make('attendee_count')
                            ->label('Số người tham dự')
                            ->numeric()
                            ->readOnly(),
                        TextInput::make('source')
                            ->label('Nguồn đăng ký')
                            ->formatStateUsing(fn (?string $state): string => $state === 'qr' ? 'QR' : 'Website')
                            ->dehydrated(false)
                            ->readOnly(),
                        Select::make('status')
                            ->label('Trạng thái')
                            ->options(CourseRegistration::STATUSES)
                            ->required(),
                        Toggle::make('is_read')
                            ->label('Đã xử lý/xem')
                            ->default(false),
                        Textarea::make('consultation_question')
                            ->label('Câu hỏi dành cho giảng viên')
                            ->rows(4)
                            ->readOnly()
                            ->columnSpanFull(),
                        Textarea::make('admin_notes')
                            ->label('Ghi chú nội bộ')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Danh sách người tham dự')
                    ->schema([
                        Repeater::make('attendees')
                            ->relationship()
                            ->schema([
                                TextInput::make('full_name')
                                    ->label('Họ và tên')
                                    ->readOnly(),
                                TextInput::make('job_title')
                                    ->label('Chức vụ')
                                    ->readOnly(),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->readOnly(),
                                TextInput::make('phone')
                                    ->label('Số điện thoại')
                                    ->readOnly(),
                            ])
                            ->columns(2)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
