<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Models\Setting;
use Filament\Resources\Pages\CreateRecord;

class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $type = $data['type'] ?? 'text';

        $data['value'] = match ($type) {
            'text' => $data['text_value'] ?? null,
            'textarea' => $data['textarea_value'] ?? null,
            'editor' => $data['editor_value'] ?? null,
            'image' => is_array($data['image_value'] ?? null) ? reset($data['image_value']) : ($data['image_value'] ?? null),
            'file' => is_array($data['file_value'] ?? null) ? reset($data['file_value']) : ($data['file_value'] ?? null),
            default => null,
        };

        if (($data['key'] ?? null) === 'phone') {
            Setting::query()->updateOrCreate(
                ['key' => 'phone_name'],
                [
                    'value' => $data['phone_name_value'] ?? '',
                    'type' => 'text',
                    'group' => 'contact',
                ],
            );
        }

        if (($data['key'] ?? null) === 'phone_2') {
            Setting::query()->updateOrCreate(
                ['key' => 'phone_2_name'],
                [
                    'value' => $data['phone_2_name_value'] ?? '',
                    'type' => 'text',
                    'group' => 'contact',
                ],
            );
        }

        unset(
            $data['text_value'],
            $data['textarea_value'],
            $data['editor_value'],
            $data['image_value'],
            $data['file_value'],
            $data['phone_name_value'],
            $data['phone_2_name_value'],
        );

        return $data;
    }
}
