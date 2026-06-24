<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const DEFAULT_IMAGES = [
        'dao-tao-boi-duong' => 'assets/images/services/1.webp',
        'dich-vu-tu-van' => 'assets/images/services/2.webp',
        'phat-trien-du-an' => 'assets/images/services/3.webp',
        'nghien-cuu-chuyen-giao' => 'assets/images/services/4.webp',
        'hoi-thao-truyen-thong' => 'assets/images/services/5.webp',
        'thu-vien-ho-so' => 'assets/images/services/6.webp',
    ];

    public function up(): void
    {
        Schema::table('service_categories', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description');
        });

        foreach (self::DEFAULT_IMAGES as $slug => $image) {
            DB::table('service_categories')
                ->where('slug', $slug)
                ->whereNull('image')
                ->update(['image' => $image]);
        }
    }

    public function down(): void
    {
        Schema::table('service_categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
