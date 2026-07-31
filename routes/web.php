<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseRegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SlugController;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Support\Facades\Route;

// Subdomain Khóa học (Tự động thích ứng với khoahoc.localhost, khoahoc.greeco.test, khoahoc.greeco.vn)
$currentHost = request()->getHost();
$khoahocDomain = str_starts_with($currentHost, 'khoahoc.')
    ? $currentHost
    : 'khoahoc.' . (preg_replace('/^www\./', '', $currentHost ?: 'greeco.vn'));

Route::domain($khoahocDomain)->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('khoahoc.home');
    Route::get('/course-details', fn () => view('histudy.course-details'))->name('khoahoc.details');
});

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

// Dịch vụ (Lĩnh vực hoạt động) — index pages keep their URLs
Route::get('/dich-vu', [ServiceController::class, 'index'])->name('services.index');
Route::get('/dao-tao-va-boi-duong', [CourseController::class, 'index'])->name('services.dao-tao');

// 301 redirects: old detail URLs → new flat /{slug}
Route::get('/dao-tao-va-boi-duong/{slug}', fn (string $slug) => redirect('/' . $slug, 301));
Route::get('/dich-vu/dao-tao-va-boi-duong', fn () => redirect('/dao-tao-va-boi-duong', 301));
Route::get('/dich-vu/dao-tao-va-boi-duong/{slug}', fn (string $slug) => redirect('/' . $slug, 301));

Route::view('/dich-vu/tu-van', 'services.tu-van')->name('services.tu-van');
Route::view('/dich-vu/du-an', 'services.du-an')->name('services.du-an');
Route::view('/dich-vu/nghien-cuu', 'services.nghien-cuu')->name('services.nghien-cuu');
Route::view('/dich-vu/hoi-thao', 'services.hoi-thao')->name('services.hoi-thao');
Route::get('/dich-vu/{service:slug}', fn (Service $service) => redirect('/' . $service->slug, 301));

// Đăng ký khóa học trực tiếp trên website.
Route::get('/dang-ky-khoa-hoc', [CourseRegistrationController::class, 'index'])
    ->name('course-registration.index');
Route::get('/dang-ky-khoa-hoc/qr.{format}', [CourseRegistrationController::class, 'selectionQr'])
    ->whereIn('format', ['png', 'svg'])
    ->middleware('throttle:60,1')
    ->name('course-registration.selection-qr');
Route::get('/dang-ky-khoa-hoc/xac-nhan/{registration:code}', [CourseRegistrationController::class, 'success'])
    ->name('course-registration.success');
Route::get('/dang-ky-khoa-hoc/{course:slug}/qr.{format}', [CourseRegistrationController::class, 'qr'])
    ->whereIn('format', ['png', 'svg'])
    ->middleware('throttle:60,1')
    ->name('course-registration.qr');
Route::get('/dang-ky-khoa-hoc/{course:slug}', [CourseRegistrationController::class, 'create'])
    ->name('course-registration.create');
Route::post('/dang-ky-khoa-hoc/{course:slug}', [CourseRegistrationController::class, 'store'])
    ->middleware('throttle:8,1')
    ->name('course-registration.store');

// Dự án & Nghiên cứu
Route::get('/du-an', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/du-an/{project:slug}', fn (Project $project) => redirect('/' . $project->slug, 301));

// Tin tức & Sự kiện
Route::get('/tin-tuc', [PostController::class, 'index'])->name('posts.index');
Route::get('/tin-tuc/{post:slug}', fn (Post $post) => redirect('/' . $post->slug, 301));

// Liên hệ
Route::get('/lien-he', [ContactController::class, 'index'])->name('contact');
Route::post('/lien-he', [ContactController::class, 'store'])->name('contact.store');

// Sitemap.xml
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Universal slug resolver — MUST be last so explicit routes keep priority.
// Resolves slugs across all content types: Post → Course → Project → Service → Page.
Route::get('/{slug}', [SlugController::class, 'show'])
    ->where('slug', '^(?!admin$|login$|register$|dashboard$|assets$|build$|storage$).+')
    ->name('slug.show');
