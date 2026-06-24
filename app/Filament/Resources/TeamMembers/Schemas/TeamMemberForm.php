<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use App\Support\OptimizedImageUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin chung')->schema([
                    TextInput::make('name')
                        ->label('Họ và tên')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('role')
                        ->label('Chức vụ / Vai trò')
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label('Chi tiết khi hover')
                        ->rows(4)
                        ->helperText('Nội dung này sẽ hiện khi rê chuột vào biểu tượng thông tin trên thẻ nhân sự.'),
                    OptimizedImageUpload::configure(
                        FileUpload::make('image')->label('Hình ảnh đại diện'),
                        600,
                        600,
                        84
                    )->disk('public')->directory('team_members'),
                    TextInput::make('facebook_url')
                        ->label('Link Facebook')
                        ->url()
                        ->maxLength(255),
                    TextInput::make('linkedin_url')
                        ->label('Link LinkedIn')
                        ->url()
                        ->maxLength(255),
                    Toggle::make('is_active')
                        ->label('Hiển thị')
                        ->default(true),
                ]),
            ]);
    }
}
