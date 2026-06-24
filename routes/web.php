<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Giới thiệu & trang tĩnh
Route::get('/gioi-thieu', [PageController::class, 'about'])->name('about');
Route::get('/ban-lanh-dao-va-chuyen-gia', [PageController::class, 'team'])->name('team');
Route::get('/thu-vien-anh', [PageController::class, 'gallery'])->name('gallery');
Route::view('/ho-so-nang-luc', 'pages.capability-profile')->name('capability-profile');
Route::get('/thu-vien', [PageController::class, 'library'])->name('library');

// Bảng giá & Gói dịch vụ
Route::view('/bang-gia', 'pages.price-list')->name('price-list');
Route::view('/goi-dich-vu', 'pages.pricing-plans')->name('pricing-plans');

// Dịch vụ (Lĩnh vực hoạt động)
Route::get('/dich-vu', [ServiceController::class, 'index'])->name('services.index');
Route::get('/dao-tao-va-boi-duong', [CourseController::class, 'index'])->name('services.dao-tao');
Route::get('/dao-tao-va-boi-duong/{slug}', [CourseController::class, 'show'])->name('services.course-detail');

// Redirects for backward compatibility
Route::redirect('/dich-vu/dao-tao-va-boi-duong', '/dao-tao-va-boi-duong', 301);
Route::redirect('/dich-vu/dao-tao-va-boi-duong/{slug}', '/dao-tao-va-boi-duong/{slug}', 301);
Route::view('/dich-vu/tu-van', 'services.tu-van')->name('services.tu-van');
Route::view('/dich-vu/du-an', 'services.du-an')->name('services.du-an');
Route::view('/dich-vu/nghien-cuu', 'services.nghien-cuu')->name('services.nghien-cuu');
Route::view('/dich-vu/hoi-thao', 'services.hoi-thao')->name('services.hoi-thao');
Route::get('/dich-vu/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

// Dự án & Nghiên cứu
Route::get('/du-an', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/du-an/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

// Tin tức & Sự kiện
Route::get('/tin-tuc', [PostController::class, 'index'])->name('posts.index');
Route::get('/tin-tuc/{post:slug}', [PostController::class, 'show'])->name('posts.show');

// Liên hệ
Route::get('/lien-he', [ContactController::class, 'index'])->name('contact');
Route::post('/lien-he', [ContactController::class, 'store'])->name('contact.store');

// Sitemap.xml
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Generic CMS pages must stay last so explicit public routes keep priority.
Route::get('/{page:slug}', [PageController::class, 'show'])
    ->where('page', '^(?!admin$|login$|register$|dashboard$|assets$|build$|storage$).+')
    ->name('pages.show');
