@php
    $seoTitle = $course->meta_title ?: $course->title . ' - Viện GREECO';
    $seoDescription = $course->meta_description ?: ($course->description ?: 'Thông tin chương trình đào tạo ' . $course->title . ' tại Viện GREECO.');
    $canonical = $course->canonical_url ?: route('slug.show', $course->slug);
    $ogImage = $course->og_image
        ? asset('storage/' . $course->og_image)
        : ($course->image ? asset('storage/' . $course->image) : asset('assets/images/services/iso-9001-course.png'));
    $indexable = $course->is_indexable;

    $breadcrumbs = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Đào tạo & Bồi dưỡng', 'url' => route('services.dao-tao')],
        ['name' => $course->title, 'url' => $canonical],
    ];

    $totalLessons = 0;
    if (!empty($chapters)) {
        foreach ($chapters as $chapter) {
            if (!empty($chapter['lessons'])) {
                $totalLessons += count($chapter['lessons']);
            }
        }
    }
@endphp

@extends('layouts.app')

@push('styles')
    <style>
        .course-detail-page {
            padding-bottom: 90px;
            background-color: #f8fafc;
        }

        #subheader.course-hero-header {
            background: linear-gradient(135deg, #09201c 0%, #123832 50%, #0d2d28 100%) !important;
            padding: 140px 0 65px !important;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        #subheader.course-hero-header::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, rgba(0, 255, 202, 0.08) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .course-badge-hero {
            background: rgba(0, 255, 202, 0.14);
            color: #00ffca;
            border: 1px solid rgba(0, 255, 202, 0.35);
            font-size: 0.8rem;
            font-weight: 700;
            padding: 5px 15px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .course-hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
            margin-top: 24px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.88rem;
        }

        .course-hero-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.08);
            padding: 7px 16px;
            border-radius: 50px;
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .course-hero-meta-item i {
            color: #00ffca;
        }

        /* Modern Nav Tabs */
        .course-nav-tabs-wrapper {
            background: #ffffff;
            border-radius: 14px;
            padding: 6px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            margin-bottom: 25px;
        }

        .course-detail-page .nav-pills .nav-link {
            border-radius: 10px;
            color: #475569 !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 12px 24px !important;
            transition: all 0.25s ease;
        }

        .course-detail-page .nav-pills .nav-link.active {
            background-color: #008f79 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(0, 143, 121, 0.3);
        }

        /* Main Card Container */
        .course-main-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            padding: 32px;
            margin-bottom: 30px;
        }

        .course-feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 20px;
        }

        @media (max-width: 767px) {
            .course-feature-grid {
                grid-template-columns: 1fr;
            }
        }

        .course-feature-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.25s ease;
        }

        .course-feature-item:hover {
            transform: translateY(-3px);
            border-color: #008f79;
            box-shadow: 0 6px 16px rgba(0, 143, 121, 0.08);
        }

        .course-feature-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: linear-gradient(135deg, #008f79 0%, #006354 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0, 143, 121, 0.2);
        }

        /* Modern Accordion */
        .course-detail-page .accordion-item {
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            margin-bottom: 12px;
            overflow: hidden;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .course-detail-page .accordion-item:hover {
            border-color: #cbd5e1 !important;
        }

        .course-detail-page .accordion-button {
            background-color: #f8fafc !important;
            color: #0f172a !important;
            font-weight: 700;
            font-size: 1rem;
            padding: 18px 24px;
            box-shadow: none !important;
        }

        .course-detail-page .accordion-button:not(.collapsed) {
            background-color: #f0fdfa !important;
            color: #006354 !important;
        }

        .lesson-item {
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.92rem;
            transition: background 0.15s ease;
        }

        .lesson-item:hover {
            background: #f8fafc;
        }

        .lesson-item:last-child {
            border-bottom: none;
        }

        .lesson-title-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .lesson-icon {
            color: #008f79;
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* Instructor Card */
        .instructor-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        }

        .instructor-avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #f0fdfa;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        /* Conversion Sidebar */
        .course-sidebar {
            position: sticky;
            top: 100px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.09);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            z-index: 10;
        }

        .course-sidebar-video {
            position: relative;
            cursor: pointer;
            overflow: hidden;
        }

        .course-sidebar-video img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.4s ease;
        }

        .course-sidebar-video:hover img {
            transform: scale(1.04);
        }

        .video-play-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 72px;
            height: 72px;
            background: rgba(0, 143, 121, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.7rem;
            transition: all 0.3s ease;
            box-shadow: 0 0 25px rgba(0, 143, 121, 0.5);
            animation: play-pulse 2s infinite;
        }

        @keyframes play-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 143, 121, 0.7);
            }
            70% {
                box-shadow: 0 0 0 18px rgba(0, 143, 121, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(0, 143, 121, 0);
            }
        }

        .course-sidebar-video:hover .video-play-btn {
            transform: translate(-50%, -50%) scale(1.1);
            background: #008f79;
        }

        .video-label {
            position: absolute;
            top: 16px;
            left: 16px;
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #000000;
            font-weight: 800;
            font-size: 0.75rem;
            padding: 5px 14px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-consult-primary {
            background: linear-gradient(135deg, #008f79 0%, #006354 100%);
            color: #ffffff !important;
            font-weight: 700;
            border-radius: 50px;
            padding: 14px 28px;
            font-size: 1.05rem;
            border: none;
            box-shadow: 0 8px 22px rgba(0, 143, 121, 0.3);
            transition: all 0.3s ease;
        }

        .btn-consult-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0, 143, 121, 0.4);
            background: linear-gradient(135deg, #007463 0%, #005245 100%);
        }

        .sidebar-trust-badge {
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #0f766e;
            font-size: 0.82rem;
            font-weight: 600;
            margin-top: 20px;
        }

        .sidebar-trust-badge i {
            font-size: 1.25rem;
            color: #008f79;
        }

        .mobile-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            box-shadow: 0 -6px 25px rgba(0, 0, 0, 0.1);
            padding: 14px 20px;
            display: none;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            border-top: 1px solid #e2e8f0;
        }

        @media (max-width: 991px) {
            .course-sidebar {
                position: static;
                margin-bottom: 30px;
            }
            .mobile-bottom-bar {
                display: flex;
            }
        }
    </style>
@endpush

@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />
    <x-schema.course :course="$course" :url="$canonical" :image="$ogImage" />

    <div class="academy-page course-detail-page">
        <!-- Hero Header -->
        <section id="subheader" class="relative course-hero-header">
            <div class="container relative z-index-1000">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="crumb">
                            @foreach ($breadcrumbs as $index => $crumb)
                                @if ($index === count($breadcrumbs) - 1)
                                    <li class="active">{{ $crumb['name'] }}</li>
                                @else
                                    <li><a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                        @if($course->tag)
                            <div class="mb-3">
                                <span class="course-badge-hero"><i class="fa-solid fa-fire me-2"></i>{{ $course->tag }}</span>
                            </div>
                        @endif
                        <h1 class="text-uppercase fw-bold mb-3">{{ $course->title }}</h1>
                        <p class="col-lg-10 lead text-white-75 mb-0" style="white-space: pre-line;">{{ $course->description }}</p>

                        <div class="course-hero-meta">
                            <div class="course-hero-meta-item">
                                <i class="fa-solid fa-building"></i>
                                <span>Viện GREECO</span>
                            </div>
                            <div class="course-hero-meta-item">
                                <i class="fa-solid fa-book-open"></i>
                                <span>{{ $totalLessons ?: 12 }} Bài giảng</span>
                            </div>
                            <div class="course-hero-meta-item">
                                <i class="fa-solid fa-award"></i>
                                <span>Chứng nhận Uy tín</span>
                            </div>
                            <div class="course-hero-meta-item">
                                <i class="fa-solid fa-star text-warning"></i>
                                <span class="fw-bold">5.0 (Đánh giá cao)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Course Body Grid -->
        <main class="container py-5">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 p-3 d-flex align-items-center" role="alert">
                    <i class="fa-solid fa-circle-check me-3 fs-4"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            <div class="row g-4">
                <!-- Main Content (Left Column) -->
                <div class="col-lg-8">
                    <!-- Pill Nav Tabs -->
                    <div class="course-nav-tabs-wrapper">
                        <ul class="nav nav-pills nav-fill" id="courseDetailTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="intro-tab" data-bs-toggle="tab" href="#intro-content" role="tab" aria-controls="intro-content" aria-selected="true"><i class="fa-solid fa-circle-info me-2"></i>Giới thiệu</a>
                            </li>
                            @if(!empty($chapters))
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="curriculum-tab" data-bs-toggle="tab" href="#curriculum-content" role="tab" aria-controls="curriculum-content" aria-selected="false"><i class="fa-solid fa-list-check me-2"></i>Nội dung khóa học</a>
                                </li>
                            @endif
                            @if($course->instructor_name)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="instructor-tab" data-bs-toggle="tab" href="#instructor-content" role="tab" aria-controls="instructor-content" aria-selected="false"><i class="fa-solid fa-user-tie me-2"></i>Giảng viên</a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <!-- Tab Panes -->
                    <div class="tab-content" id="courseDetailTabsContent">
                        <!-- Tab Content: Giới thiệu -->
                        <div class="tab-pane fade show active" id="intro-content" role="tabpanel" aria-labelledby="intro-tab">
                            <div class="course-main-card">
                                <h3 class="academy-heading fs-4 fw-bold mb-4 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-file-lines text-success"></i>Thông tin khóa học
                                </h3>
                                <div class="text-dark mb-4 lh-lg">
                                    @if($course->content)
                                        {!! $course->content !!}
                                    @else
                                        <p>{{ $course->description }}</p>
                                    @endif
                                </div>

                                <h3 class="academy-heading fs-4 fw-bold mb-3 mt-5 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-sliders text-success"></i>Thông số & Giá trị đạt được
                                </h3>
                                <div class="course-feature-grid">
                                    <div class="course-feature-item">
                                        <div class="course-feature-icon"><i class="fa-solid fa-book-open"></i></div>
                                        <div>
                                            <small class="text-secondary d-block">Số bài học</small>
                                            <strong class="academy-heading">{{ $totalLessons ?: 12 }} bài giảng</strong>
                                        </div>
                                    </div>
                                    <div class="course-feature-item">
                                        <div class="course-feature-icon"><i class="fa-solid fa-layer-group"></i></div>
                                        <div>
                                            <small class="text-secondary d-block">Cấp độ</small>
                                            <strong class="academy-heading">Mọi cấp độ</strong>
                                        </div>
                                    </div>
                                    <div class="course-feature-item">
                                        <div class="course-feature-icon"><i class="fa-solid fa-award"></i></div>
                                        <div>
                                            <small class="text-secondary d-block">Chứng chỉ</small>
                                            <strong class="academy-heading">Chứng nhận hoàn thành</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Content: Nội dung khóa học (Accordion) -->
                        @if(!empty($chapters))
                            <div class="tab-pane fade" id="curriculum-content" role="tabpanel" aria-labelledby="curriculum-tab">
                                <div class="course-main-card">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <h3 class="academy-heading fs-4 fw-bold mb-0 d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-graduation-cap text-success"></i>Chương trình học
                                        </h3>
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="toggle-all-curriculum">Thu gọn tất cả</button>
                                    </div>

                                    <div class="accordion accordion-flush" id="curriculumAccordion">
                                        @foreach ($chapters as $index => $chapter)
                                            <div class="accordion-item border rounded-3 mb-3 overflow-hidden bg-white">
                                                <h4 class="accordion-header" id="heading-{{ $index }}">
                                                    <button 
                                                        class="accordion-button d-flex align-items-center justify-content-between py-3 px-4 bg-light text-dark fw-bold" 
                                                        type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#collapse-{{ $index }}" 
                                                        aria-expanded="true" 
                                                        aria-controls="collapse-{{ $index }}"
                                                    >
                                                        <span class="academy-heading mb-0">{{ $chapter['title'] }}</span>
                                                    </button>
                                                </h4>
                                                <div 
                                                    id="collapse-{{ $index }}" 
                                                    class="accordion-collapse collapse show" 
                                                    aria-labelledby="heading-{{ $index }}"
                                                >
                                                    <div class="accordion-body p-0">
                                                        @if(!empty($chapter['lessons']))
                                                            @foreach ($chapter['lessons'] as $lesson)
                                                                <div class="lesson-item">
                                                                    <div class="lesson-title-wrapper">
                                                                        <i class="fa-solid fa-circle-play lesson-icon"></i>
                                                                        <span class="text-dark fw-semibold">{{ $lesson['title'] }}</span>
                                                                    </div>
                                                                    @if(!empty($lesson['duration']))
                                                                        <span class="badge bg-light text-success border border-success-subtle fw-bold rounded-pill px-3">{{ $lesson['duration'] }}</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Tab Content: Giảng viên -->
                        @if($course->instructor_name)
                            <div class="tab-pane fade" id="instructor-content" role="tabpanel" aria-labelledby="instructor-tab">
                                <div class="instructor-card border shadow-sm rounded-3 bg-white p-4 mb-4">
                                    <h3 class="academy-heading fs-4 fw-bold mb-4 d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-user-tie text-success"></i>Giảng viên hướng dẫn
                                    </h3>
                                    <div class="d-flex flex-column flex-sm-row gap-4 align-items-start">
                                        <img src="{{ $course->instructor_image ? asset('storage/' . $course->instructor_image) : asset('assets/images/team/team-1.jpg') }}" width="160" height="160" alt="{{ $course->instructor_name }}" class="instructor-avatar" loading="lazy" decoding="async">
                                        <div>
                                            <h4 class="academy-heading fs-5 fw-bold mb-1">{{ $course->instructor_name }}</h4>
                                            <p class="text-success fw-semibold small mb-3"><i class="fa-solid fa-circle-check me-1"></i>{{ $course->instructor_role }}</p>
                                            <p class="text-dark mb-4 lh-lg">
                                                {{ $course->instructor_description }}
                                            </p>
                                            <div class="d-flex gap-3">
                                                <a href="mailto:info@greeco.vn" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="fa-solid fa-envelope me-2"></i>Gửi email</a>
                                                <button class="btn btn-sm btn-consult-primary" data-bs-toggle="modal" data-bs-target="#checkout-modal"><i class="fa-solid fa-comments me-2"></i>Đăng ký tư vấn</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Course Sidebar (Right Column) -->
                <div class="col-lg-4">
                    <aside class="course-sidebar">
                        <div class="course-sidebar-video" data-bs-toggle="modal" data-bs-target="#checkout-modal">
                            <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('assets/images/services/iso-9001-course.png') }}" width="1024" height="1024" alt="{{ $course->title }} Course preview" loading="lazy" decoding="async">
                            @if($course->tag)
                                <span class="video-label">{{ $course->tag }}</span>
                            @endif
                            <div class="video-play-btn"><i class="fa-solid fa-play"></i></div>
                        </div>
                        <div class="p-4">
                            <div class="mb-4 text-center">
                                <span class="text-secondary small d-block mb-1">Học phí & Ưu đãi doanh nghiệp</span>
                                <h3 class="fw-bold text-success mb-0 fs-3">{{ $course->price ?: 'Liên hệ' }}</h3>
                                @if($course->original_price)
                                    <span class="text-muted text-decoration-line-through small me-2">{{ $course->original_price }}</span>
                                @endif
                                @if($course->discount)
                                    <span class="badge bg-danger rounded-pill">{{ $course->discount }}</span>
                                @endif
                            </div>

                            <a href="{{ route('course-registration.create', $course) }}" class="btn btn-consult-primary btn-lg w-100 mb-3">
                                <i class="fa-solid fa-pen-to-square me-2"></i>Đăng ký khóa học
                            </a>

                            <div class="text-center border rounded-3 p-3 mb-4 bg-light">
                                <img
                                    src="{{ route('course-registration.qr', ['course' => $course, 'format' => 'png']) }}"
                                    alt="Mã QR đăng ký khóa học {{ $course->title }}"
                                    width="180"
                                    height="180"
                                    class="img-fluid"
                                    loading="lazy"
                                >
                                <div class="fw-bold text-dark small mt-2">Quét mã để đăng ký</div>
                                <div class="d-flex justify-content-center gap-2 mt-2">
                                    <a
                                        href="{{ route('course-registration.qr', ['course' => $course, 'format' => 'png', 'download' => 1]) }}"
                                        class="btn btn-sm btn-outline-success"
                                    >
                                        Tải PNG
                                    </a>
                                    <a
                                        href="{{ route('course-registration.qr', ['course' => $course, 'format' => 'svg', 'download' => 1]) }}"
                                        class="btn btn-sm btn-outline-success"
                                    >
                                        Tải SVG
                                    </a>
                                </div>
                            </div>

                            <div class="border-top pt-4">
                                <h4 class="academy-heading fs-6 fw-bold mb-3">Khóa học bao gồm:</h4>
                                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                                    <li class="d-flex align-items-center gap-3 text-dark small">
                                        <i class="fa-solid fa-circle-check text-success fs-6" style="width: 20px;"></i>
                                        <span>{{ $totalLessons ?: 12 }} bài giảng trực tuyến chất lượng</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-3 text-dark small">
                                        <i class="fa-solid fa-circle-check text-success fs-6" style="width: 20px;"></i>
                                        <span>Học tập linh hoạt trên điện thoại & máy tính</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-3 text-dark small">
                                        <i class="fa-solid fa-circle-check text-success fs-6" style="width: 20px;"></i>
                                        <span>Biểu mẫu tài liệu học thực tế</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-3 text-dark small">
                                        <i class="fa-solid fa-circle-check text-success fs-6" style="width: 20px;"></i>
                                        <span>Cấp chứng nhận hoàn thành uy tín</span>
                                    </li>
                                </ul>

                                <div class="sidebar-trust-badge">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    <span>Đảm bảo chất lượng theo tiêu chuẩn Viện GREECO</span>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </main>
    </div>

    <!-- Mobile Bottom action bar -->
    <div class="mobile-bottom-bar justify-content-center">
        <a href="{{ route('course-registration.create', $course) }}" class="btn btn-success w-100">
            <i class="fa-solid fa-pen-to-square me-2"></i>Đăng ký khóa học
        </a>
    </div>

    <!-- Checkout / Registration Modal (Bootstrap 5) -->
    <div class="modal fade" id="checkout-modal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-white" id="checkoutModalLabel">Liên hệ tư vấn khóa học: {{ $course->title }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="checkout-form" method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <input type="hidden" name="_return" value="training">
                        <input type="hidden" name="subject" value="Đăng ký khóa học {{ $course->title }}">

                        <div class="mb-3">
                            <label for="reg_name" class="form-label fw-bold small">Họ và tên *</label>
                            <input type="text" class="form-control" id="reg_name" name="name" required placeholder="Họ và Tên">
                        </div>
                        <div class="mb-3">
                            <label for="reg_phone" class="form-label fw-bold small">Số điện thoại *</label>
                            <input type="tel" class="form-control" id="reg_phone" name="phone" required placeholder="Số điện thoại">
                        </div>
                        <div class="mb-3">
                            <label for="reg_email" class="form-label fw-bold small">Email liên hệ *</label>
                            <input type="email" class="form-control" id="reg_email" name="email" required placeholder="name@company.com">
                        </div>
                        <div class="mb-3">
                            <label for="reg_message" class="form-label fw-bold small">Yêu cầu cụ thể hoặc thông tin doanh nghiệp</label>
                            <textarea class="form-control" id="reg_message" name="message" rows="3" placeholder="Ví dụ: đăng ký học cho doanh nghiệp, số lượng học viên..."></textarea>
                        </div>

                        <input type="hidden" name="payment_method" value="consult">

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Gửi yêu cầu tư vấn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Expand/collapse all accordion chapters logic using Bootstrap 5 Collapse Instance
            var toggleAllBtn = document.getElementById('toggle-all-curriculum');
            var allExpanded = true;
            
            if (toggleAllBtn) {
                toggleAllBtn.addEventListener('click', function () {
                    allExpanded = !allExpanded;
                    var collapses = document.querySelectorAll('#curriculumAccordion .accordion-collapse');
                    collapses.forEach(function (el) {
                        var bsCollapse = bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });
                        if (allExpanded) {
                            bsCollapse.show();
                        } else {
                            bsCollapse.hide();
                        }
                    });
                    toggleAllBtn.textContent = allExpanded ? 'Thu gọn tất cả' : 'Mở rộng tất cả';
                });
            }

            // Add body class for shifting floating buttons up
            document.body.classList.add('has-mobile-bottom-bar');
        });
    </script>
@endpush
