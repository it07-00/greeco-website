<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            [
                'key' => 'phone_name',
                'value' => '',
                'type' => 'text',
                'group' => 'contact',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'phone_2_name',
                'value' => '',
                'type' => 'text',
                'group' => 'contact',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting,
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')
            ->whereIn('key', ['phone_name', 'phone_2_name'])
            ->delete();
    }
};
