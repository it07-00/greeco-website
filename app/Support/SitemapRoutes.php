<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class SitemapRoutes
{
    public static function static(): Collection
    {
        return collect([
            ['route' => 'home', 'changefreq' => 'daily', 'priority' => '1.0'],
            ['route' => 'about', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'team', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['route' => 'gallery', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['route' => 'capability-profile', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'library', 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['route' => 'price-list', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['route' => 'pricing-plans', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['route' => 'services.index', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['route' => 'services.dao-tao', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['route' => 'services.tu-van', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'services.du-an', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'services.nghien-cuu', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'services.hoi-thao', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['route' => 'projects.index', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['route' => 'posts.index', 'changefreq' => 'daily', 'priority' => '0.9'],
            ['route' => 'contact', 'changefreq' => 'monthly', 'priority' => '0.5'],
        ])->filter(fn (array $entry): bool => Route::has($entry['route']))->values();
    }

    public static function staticPageSlugs(): array
    {
        return [
            'gioi-thieu',
            'ban-lanh-dao-va-chuyen-gia',
            'thu-vien-anh',
            'ho-so-nang-luc',
            'thu-vien',
            'bang-gia',
            'goi-dich-vu',
            'lien-he',
        ];
    }
}
