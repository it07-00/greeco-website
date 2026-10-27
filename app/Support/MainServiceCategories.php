<?php

namespace App\Support;

use App\Models\ServiceCategory;
use Illuminate\Support\Collection;

class MainServiceCategories
{
    private const DEFINITIONS = [
        [
            'slug' => 'dao-tao-boi-duong',
            'name' => 'Đào tạo & Bồi dưỡng',
            'description' => 'Các khóa học đào tạo, bồi dưỡng nâng cao năng lực doanh nghiệp',
            'route' => 'services.dao-tao',
        ],
        [
            'slug' => 'dich-vu-tu-van',
            'name' => 'Dịch vụ Tư vấn',
            'description' => 'Các dịch vụ tư vấn phát triển bền vững và giảm phát thải',
            'route' => 'services.tu-van',
        ],
        [
            'slug' => 'phat-trien-du-an',
            'name' => 'Phát triển Dự án',
            'description' => 'Phát triển dự án giảm phát thải carbon và tín chỉ nhựa',
            'route' => 'services.du-an',
        ],
        [
            'slug' => 'nghien-cuu-chuyen-giao',
            'name' => 'Nghiên cứu và Chuyển giao Công nghệ',
            'description' => 'Nghiên cứu ứng dụng và chuyển giao công nghệ xanh',
            'route' => 'services.nghien-cuu',
        ],
        [
            'slug' => 'hoi-thao-truyen-thong',
            'name' => 'Hội thảo & Truyền thông',
            'description' => 'Tổ chức hội thảo khoa học và truyền thông Net Zero',
            'route' => 'services.hoi-thao',
        ],
        [
            'slug' => 'thu-vien-ho-so',
            'name' => 'Văn bản pháp luật',
            'description' => 'Hệ thống văn bản pháp luật về môi trường và khí hậu',
            'route' => 'library',
        ],
    ];

    public static function get(): Collection
    {
        $categories = ServiceCategory::query()
            ->where('is_active', true)
            ->whereIn('slug', array_column(self::DEFINITIONS, 'slug'))
            ->get()
            ->keyBy('slug');

        return collect(self::DEFINITIONS)
            ->map(function (array $item, int $index) use ($categories): array {
                $category = $categories->get($item['slug']);

                return [
                    'slug' => $item['slug'],
                    'name' => __($category?->name ?? $item['name']),
                    'description' => __($category?->description ?? $item['description']),
                    'url' => website_route($item['route']),
                    'image' => self::imageUrl(
                        $category?->image,
                        'assets/images/services/'.($index + 1).'.webp',
                    ),
                ];
            });
    }

    private static function imageUrl(?string $image, string $fallback): string
    {
        if (! $image) {
            return asset($fallback);
        }

        return asset(str_starts_with($image, 'assets/') ? $image : 'storage/'.$image);
    }
}
