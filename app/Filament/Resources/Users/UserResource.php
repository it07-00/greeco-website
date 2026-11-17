<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use App\Services\EduQuiz\Access;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static string|UnitEnum|null $navigationGroup = 'SEO & Cấu hình';

    protected static ?string $navigationLabel = 'Tài khoản Quản trị';

    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return 'Tài khoản';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Tài khoản Quản trị';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin tài khoản')
                    ->components([
                        TextInput::make('name')
                            ->label('Họ và tên')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Địa chỉ Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(User::class, 'email', ignoreRecord: true),
                        TextInput::make('password')
                            ->label('Mật khẩu')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(255),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Họ tên')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('role')
                    ->label('Vai trò')
                    ->state(fn (User $record): string => Access::instructor($record) ? 'Giảng viên EduQuiz' : 'Quản trị viên')
                    ->badge()
                    ->color(fn (User $record): string => Access::instructor($record) ? 'info' : 'success'),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
