<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} · GREECO EduQuiz</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ versioned_asset('assets/eduquiz/eduquiz.css') }}">
    <script type="module" src="{{ versioned_asset('assets/eduquiz/eduquiz.js') }}"></script>
</head>
<body>
@php
    $staff = $staff ?? false;
    $instructor = $staff && \App\Services\EduQuiz\Access::instructor(auth()->user());
@endphp
<header class="site-header">
    <div class="page-width header-inner">
        <a class="brand" href="{{ route($staff ? 'eduquiz.staff.index' : 'eduquiz.join') }}">
            <span class="brand-mark">
                <img src="{{ asset('assets/images/logo-emblem-cropped.png') }}" width="42" height="42" alt="Logo Viện GREECO">
            </span>
            <span class="brand-text">
                <span class="brand-title">GREECO <strong>EduQuiz</strong></span>
                <span class="brand-sub">{{ $staff ? 'Quản trị đào tạo & trắc nghiệm' : 'Nền tảng trắc nghiệm trực tuyến' }}</span>
            </span>
        </a>
        @if ($staff)
            <form method="post" action="{{ route($instructor ? 'filament.instructor.auth.logout' : 'filament.admin.auth.logout') }}">
                @csrf
                @unless ($instructor)
                    <a class="header-link" href="{{ route('filament.admin.pages.dashboard') }}">GREECO Admin</a>
                @endunless
                <button class="button quiet" type="submit">Đăng xuất</button>
            </form>
        @else
            <a class="header-link" href="{{ route('home') }}">← Về GREECO</a>
        @endif
    </div>
</header>

<main class="page-width {{ $staff ? 'staff' : '' }}" id="main">
    @if ($staff)
        <nav class="module-nav" aria-label="Quản lý trắc nghiệm">
            @foreach ([
                'index' => 'Tổng quan',
                'guide' => 'Quy trình',
                'questions' => 'Ngân hàng câu hỏi',
                'quizzes' => 'Bộ đề thi',
                'courses' => 'Khóa đào tạo',
                'rooms' => 'Phòng thi',
                'participants' => 'Học viên',
                'results' => 'Kết quả và thống kê',
                ...($instructor ? [] : ['settings' => 'Cài đặt']),
            ] as $item => $label)
                @php($active = request()->routeIs('eduquiz.staff.'.$item, 'eduquiz.staff.'.$item.'.*'))
                <a href="{{ route('eduquiz.staff.'.$item) }}" @if($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </nav>
    @endif
    @if (session('notice'))
        <p class="notice" role="status">{{ session('notice') }}</p>
    @endif
    @if ($errors->any())
        <div class="notice error" role="alert">
            <strong>Vui lòng kiểm tra thông tin</strong>
            <ul>@foreach ($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul>
        </div>
    @endif
    @yield('content')
</main>
<footer class="site-footer">
    <div class="page-width footer-inner">
        <span>Viện Nghiên cứu và Phát triển Kinh tế Xanh · GREECO</span>
        <span class="muted">Hệ thống đánh giá & kiểm tra đào tạo trực tuyến</span>
    </div>
</footer>
</body>
</html>
