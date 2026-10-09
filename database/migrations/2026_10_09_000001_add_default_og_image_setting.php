<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'og_image'],
            [
                'key' => 'og_image',
                'value' => 'settings/og_image.jpg',
                'type' => 'image',
                'group' => 'seo',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('settings')
            ->where('key', 'og_image')
            ->delete();
    }
};
