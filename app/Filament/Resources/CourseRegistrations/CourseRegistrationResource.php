<?php

namespace App\Filament\Resources\CourseRegistrations;

use App\Filament\Resources\CourseRegistrations\Pages\EditCourseRegistration;
use App\Filament\Resources\CourseRegistrations\Pages\ListCourseRegistrations;
use App\Filament\Resources\CourseRegistrations\Schemas\CourseRegistrationForm;
use App\Filament\Resources\CourseRegistrations\Tables\CourseRegistrationsTable;
use App\Models\CourseRegistration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CourseRegistrationResource extends Resource
{
    protected static ?string $model = CourseRegistration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Đăng ký khóa học';

    protected static ?string $modelLabel = 'Đăng ký khóa học';

    protected static ?string $pluralModelLabel = 'Đăng ký khóa học';

    protected static string|\UnitEnum|null $navigationGroup = 'Quản lý đào tạo';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return CourseRegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseRegistrationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourseRegistrations::route('/'),
            'edit' => EditCourseRegistration::route('/{record}/edit'),
        ];
    }
}
