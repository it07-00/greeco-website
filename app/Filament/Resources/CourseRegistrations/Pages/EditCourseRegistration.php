<?php

namespace App\Filament\Resources\CourseRegistrations\Pages;

use App\Filament\Resources\CourseRegistrations\CourseRegistrationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCourseRegistration extends EditRecord
{
    protected static string $resource = CourseRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
