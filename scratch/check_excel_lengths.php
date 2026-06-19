<?php

// Include Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Mock the base Migration class so we can require the file without Laravel framework boot
class_alias(\Illuminate\Database\Migrations\Migration::class, 'Migration');

// Load the migration file
$migrationFile = __DIR__ . '/../database/migrations/2026_06_19_083700_import_courses_from_excel.php';
$migration = require $migrationFile;

// Reflection to make private methods accessible
$reflection = new ReflectionClass($migration);

$coursesDataMethod = $reflection->getMethod('coursesData');
$coursesDataMethod->setAccessible(true);
$courses = $coursesDataMethod->invoke($migration);

$categoriesDataMethod = $reflection->getMethod('categoriesData');
$categoriesDataMethod->setAccessible(true);
$categories = $categoriesDataMethod->invoke($migration);

echo "=== CATEGORIES DATA LENGTHS ===\n";
$maxCategoryName = 0;
$maxCategorySlug = 0;
$maxCategoryIcon = 0;
$maxCategoryColor = 0;
$maxCategoryBg = 0;

foreach ($categories as $cat) {
    $maxCategoryName = max($maxCategoryName, mb_strlen($cat['name']));
    $maxCategorySlug = max($maxCategorySlug, mb_strlen($cat['slug']));
    $maxCategoryIcon = max($maxCategoryIcon, mb_strlen($cat['icon'] ?? ''));
    $maxCategoryColor = max($maxCategoryColor, mb_strlen($cat['color'] ?? ''));
    $maxCategoryBg = max($maxCategoryBg, mb_strlen($cat['bg'] ?? ''));
}

echo "Max name length: $maxCategoryName\n";
echo "Max slug length: $maxCategorySlug\n";
echo "Max icon length: $maxCategoryIcon\n";
echo "Max color length: $maxCategoryColor\n";
echo "Max bg length: $maxCategoryBg\n\n";

echo "=== COURSES DATA LENGTHS ===\n";
$maxTitle = 0;
$maxSlug = 0;
$maxDescription = 0;
$maxDetail = 0;
$maxMetaTitle = 0;
$maxMetaDescription = 0;
$maxMetaKeywords = 0;
$maxTag = 0;

// Helper to simulate the migration's unique slug generation
function generateSlug($title) {
    return \Illuminate\Support\Str::slug($title);
}

foreach ($courses as $course) {
    $title = trim(preg_replace('/\s+/u', ' ', html_entity_decode($course['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    $slug = generateSlug($title);
    $description = trim(str_replace(["\r\n", "\r", "\u{00A0}"], ["\n", "\n", ' '], $course['description'] ?? ''));
    $detail = trim(str_replace(["\r\n", "\r", "\u{00A0}"], ["\n", "\n", ' '], $course['detail'] ?? ''));
    
    // Simulate meta properties
    $metaTitle = \Illuminate\Support\Str::limit($title.' | GREECO', 70, '');
    $plainDesc = trim(preg_replace('/\s+/u', ' ', strip_tags($description !== '' ? $description : $detail)));
    $metaDescription = \Illuminate\Support\Str::limit($plainDesc !== '' ? $plainDesc : 'Chương trình đào tạo ' . $title . ' tại Viện GREECO.', 160, '');
    
    $keywords = implode(', ', array_unique(array_filter([$title, 'Category', 'dao tao GREECO', 'dao tao phat trien ben vung', 'tu van doanh nghiep'])));
    $metaKeywords = \Illuminate\Support\Str::limit($keywords, 250, '');
    
    $maxTitle = max($maxTitle, mb_strlen($title));
    $maxSlug = max($maxSlug, mb_strlen($slug));
    $maxDescription = max($maxDescription, mb_strlen($description));
    $maxDetail = max($maxDetail, mb_strlen($detail));
    $maxMetaTitle = max($maxMetaTitle, mb_strlen($metaTitle));
    $maxMetaDescription = max($maxMetaDescription, mb_strlen($metaDescription));
    $maxMetaKeywords = max($maxMetaKeywords, mb_strlen($metaKeywords));
}

echo "Max title length: $maxTitle\n";
echo "Max slug length: $maxSlug\n";
echo "Max description length: $maxDescription (Text field in DB)\n";
echo "Max detail/content length: $maxDetail (LongText field in DB)\n";
echo "Max meta_title length: $maxMetaTitle\n";
echo "Max meta_description length: $maxMetaDescription\n";
echo "Max meta_keywords length: $maxMetaKeywords\n";
