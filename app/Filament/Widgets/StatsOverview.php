<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Contacts\ContactResource;
use App\Filament\Resources\CourseRegistrations\CourseRegistrationResource;
use App\Filament\Resources\Courses\CourseResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Services\ServiceResource;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\EduQuiz\Question;
use App\Models\EduQuiz\Quiz;
use App\Models\EduQuiz\Result;
use App\Models\EduQuiz\Room;
use App\Models\Post;
use App\Models\Project;
use App\Models\RegistrationAttendee;
use App\Models\Service;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected ?string $heading = 'Tổng quan hệ thống';

    protected function getStats(): array
    {
        $monthStart = now()->startOfMonth();

        $newRegistrations = CourseRegistration::where('status', 'new')->count();
        $todayRegistrations = CourseRegistration::whereDate('created_at', today())->count();
        $totalRegistrations = CourseRegistration::count();
        $totalAttendees = RegistrationAttendee::count();

        $unreadContacts = Contact::where('is_read', false)->count();
        $todayContacts = Contact::whereDate('created_at', today())->count();
        $totalContacts = Contact::count();

        $totalCourses = Course::count();
        $activeCourses = Course::where('is_active', true)->count();

        $totalPosts = Post::count();
        $monthPosts = Post::where('created_at', '>=', $monthStart)->count();
        $publishedPosts = Post::published()->count();

        $totalServices = Service::count();
        $totalProjects = Project::count();

        $openRooms = Room::whereNull('finished_at')->count();
        $totalRooms = Room::count();
        $totalQuizzes = Quiz::count();
        $totalQuestions = Question::count();
        $totalResults = Result::count();

        return [
            Stat::make('Đăng ký khóa học mới', $newRegistrations)
                ->description("Hôm nay: {$todayRegistrations} • Tổng: {$totalRegistrations} ({$totalAttendees} học viên)")
                ->descriptionIcon($newRegistrations > 0 ? Heroicon::OutlinedExclamationCircle : Heroicon::OutlinedCheckCircle)
                ->color($newRegistrations > 0 ? 'warning' : 'success')
                ->url(CourseRegistrationResource::getUrl('index')),

            Stat::make('Liên hệ chưa đọc', $unreadContacts)
                ->description("Hôm nay: {$todayContacts} • Tổng: {$totalContacts}")
                ->descriptionIcon($unreadContacts > 0 ? Heroicon::OutlinedEnvelope : Heroicon::OutlinedCheckCircle)
                ->color($unreadContacts > 0 ? 'warning' : 'success')
                ->url(ContactResource::getUrl('index')),

            Stat::make('Khóa học đào tạo', $activeCourses)
                ->description("Đang hiển thị: {$activeCourses} / {$totalCourses} khóa")
                ->descriptionIcon(Heroicon::OutlinedAcademicCap)
                ->color('info')
                ->url(CourseResource::getUrl('index')),

            Stat::make('EduQuiz - Trắc nghiệm', $openRooms)
                ->description("Phòng đang mở: {$openRooms}/{$totalRooms} • {$totalQuizzes} bộ đề • {$totalQuestions} câu • {$totalResults} bài nộp")
                ->descriptionIcon(Heroicon::OutlinedClipboardDocumentCheck)
                ->color($openRooms > 0 ? 'success' : 'primary')
                ->url(route('eduquiz.staff.index')),

            Stat::make('Bài viết / Tin tức', $totalPosts)
                ->description("Tháng này +{$monthPosts} • Đã xuất bản: {$publishedPosts}")
                ->descriptionIcon(Heroicon::OutlinedNewspaper)
                ->color('primary')
                ->url(PostResource::getUrl('index')),

            Stat::make('Dịch vụ & Dự án', $totalServices + $totalProjects)
                ->description("{$totalServices} dịch vụ • {$totalProjects} dự án")
                ->descriptionIcon(Heroicon::OutlinedBriefcase)
                ->color('primary')
                ->url(ServiceResource::getUrl('index')),
        ];
    }
}
