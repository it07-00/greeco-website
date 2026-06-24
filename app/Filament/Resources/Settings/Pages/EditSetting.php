<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Models\Setting;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $type = $data['type'] ?? 'text';

        $data['text_value'] = ($type === 'text') ? $data['value'] : null;
        $data['textarea_value'] = ($type === 'textarea') ? $data['value'] : null;
        $data['editor_value'] = ($type === 'editor') ? $data['value'] : null;
        $data['image_value'] = ($type === 'image') ? $data['value'] : null;
        $data['file_value'] = ($type === 'file') ? $data['value'] : null;
        $data['phone_name_value'] = $data['key'] === 'phone' ? Setting::getValue('phone_name') : null;
        $data['phone_2_name_value'] = $data['key'] === 'phone_2' ? Setting::getValue('phone_2_name') : null;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

        if ($this->record->key === 'phone') {
            Setting::query()->updateOrCreate(
                ['key' => 'phone_name'],
                [
                    'value' => $data['phone_name_value'] ?? '',
                    'type' => 'text',
                    'group' => 'contact',
                ],
            );
        }

        if ($this->record->key === 'phone_2') {
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
