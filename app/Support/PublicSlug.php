<?php

namespace App\Support;

use App\Models\Course;
use App\Rules\UniquePublicSlug;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PublicSlug
{
    public static function configure(TextInput $field, string $modelClass): TextInput
    {
        return $field
            ->maxLength($modelClass === Course::class ? 500 : 191)
            ->mutateStateForValidationUsing(fn (?string $state) => Str::slug($state ?? ''))
            ->dehydrateStateUsing(fn (?string $state) => Str::slug($state ?? ''))
            ->rules(fn (?Model $record) => [new UniquePublicSlug($modelClass, $record)]);
    }
}
