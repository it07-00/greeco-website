<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Thông tin chung')->schema([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->label('Họ và tên')
                        ->required()
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('role')
                        ->label('Chức vụ / Vai trò')
                        ->maxLength(255),
                    \App\Support\OptimizedImageUpload::configure(
                        \Filament\Forms\Components\FileUpload::make('image')->label('Hình ảnh đại diện'),
                        600,
                        600,
                        84
                    )->disk('public')->directory('team_members'),
                    \Filament\Forms\Components\TextInput::make('facebook_url')
                        ->label('Link Facebook')
                        ->url()
                        ->maxLength(255),
                    \Filament\Forms\Components\TextInput::make('linkedin_url')
                        ->label('Link LinkedIn')
                        ->url()
                        ->maxLength(255),
                    \Filament\Forms\Components\Toggle::make('is_active')
                        ->label('Hiển thị')
                        ->default(true),
                ]),
            ]);
    }
}
