<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            1 => 'Hoạt động nghiên cứu thực địa',
            2 => 'Hội thảo khoa học',
            3 => 'Đào tạo doanh nghiệp',
            4 => 'Hội nghị phát triển bền vững',
            5 => 'Chuyển giao công nghệ xanh',
            6 => 'Khảo sát môi trường thực tế',
            7 => 'Lớp học ESG và Net Zero',
            8 => 'Ký kết hợp tác công nghệ',
            9 => 'Thực nghiệm kỹ thuật hóa học',
        ];

        foreach ($images as $index => $title) {
            $fileName = "{$index}.webp";
            $sourcePath = public_path("assets/images/gallery/{$fileName}");
            $targetPath = "gallery/{$fileName}";

            if (file_exists($sourcePath)) {
                Storage::disk('public')->put($targetPath, file_get_contents($sourcePath));

                Gallery::query()->updateOrCreate(
                    ['image' => $targetPath],
                    [
                        'title' => $title,
                        'sort_order' => $index,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
