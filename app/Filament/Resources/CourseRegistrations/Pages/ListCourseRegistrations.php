<?php

namespace App\Filament\Resources\CourseRegistrations\Pages;

use App\Filament\Resources\CourseRegistrations\CourseRegistrationResource;
use Filament\Resources\Pages\ListRecords;

class ListCourseRegistrations extends ListRecords
{
    protected static string $resource = CourseRegistrationResource::class;
}
