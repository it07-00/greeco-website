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
            padding-bottom: 80px;
            background-color: #f8fafc;
        }
        #subheader .course-badge {
            background: rgba(0, 143, 121, 0.2);
            color: #00ffca;
            border: 1px solid rgba(0, 143, 121, 0.4);
            font-size: 0.85rem;
            padding: 4px 12px;
            border-radius: 50px;
            display: inline-block;
            margin-top: 12px;
            margin-bottom: 5px;
        }
        .course-rating i {
            color: #ffc107 !important;
        }
        .course-sidebar {
            position: sticky;
            top: 100px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            z-index: 10;
        }
        .course-sidebar-video {
            position: relative;
            cursor: pointer;
        }
        .course-sidebar-video img {
            width: 100%;
            height: auto;
            display: block;
        }
        .video-play-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 68px;
            height: 68px;
            background: rgba(0, 143, 121, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.6rem;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(0, 143, 121, 0.4);
        }
        .course-sidebar-video:hover .video-play-btn {
            transform: translate(-50%, -50%) scale(1.1);
            background: #008f79;
        }
        .video-label {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #ffc107;
            color: #000;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        
        /* Bootstrap Nav Tabs styling override */
        .course-detail-page .nav-tabs {
            border-bottom: 2px solid #e2e8f0 !important;
        }
        .course-detail-page .nav-tabs .nav-link {
            border: none !important;
            color: #64748b !important;
            background: none !important;
            padding: 15px 20px !important;
            font-weight: 600;
            font-size: 0.95rem;
            position: relative;
            transition: color 0.25s ease;
        }
        .course-detail-page .nav-tabs .nav-link.active {
            color: #006354 !important;
            font-weight: 700;
        }
        .course-detail-page .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #006354;
            border-radius: 3px 3px 0 0;
        }

        .course-feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        @media (max-width: 767px) {
            .course-feature-grid {
                grid-template-columns: 1fr;
            }
        }
        .course-feature-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .course-feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .course-detail-page .academy-heading {
            color: #0f172a !important;
        }

        /* Bootstrap Accordion custom styling overrides */
        .course-detail-page .accordion-button {
            background-color: #f8fafc !important;
            color: #072b54 !important;
            font-weight: 700;
            box-shadow: none !important;
            border: none !important;
            padding: 16px 20px;
        }
        .course-detail-page .accordion-button:not(.collapsed) {
            background-color: #e6f4f1 !important;
            color: #006354 !important;
        }
        .course-detail-page .accordion-button::after {
            background-size: 1rem;
            width: 1rem;
            height: 1rem;
        }
        .lesson-item {
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }
        .lesson-item:last-child {
            border-bottom: none;
        }
        .lesson-title-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .instructor-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
        }
        .instructor-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f2f8f6;
        }
        .rating-bar-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        .rating-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 10px;
            flex-grow: 1;
            overflow: hidden;
        }
        .rating-bar-fill {
            height: 100%;
            background: #ffc107;
            border-radius: 10px;
        }
        .mobile-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
            padding: 12px 20px;
            display: none;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            border-top: 1px solid #e2e8f0;
        }
        .payment-method-box {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        .payment-method-box.active {
            border-color: #008f79;
            background-color: #f0fdfa;
        }
        .payment-method-box input {
            position: absolute;
            top: 15px;
            right: 15px;
        }
        .bank-info {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 15px;
            margin-top: 12px;
            display: none;
        }
        .bank-info.active {
            display: block;
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
        <!-- Course Header Header -->
        <section id="subheader" class="relative">
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
                            <span class="course-badge mb-3"><i class="fa-solid fa-fire me-2"></i>{{ $course->tag }}</span>
                        @endif
                        <h1 class="text-uppercase">{{ $course->title }}</h1>
                        <p class="col-lg-10 lead" style="white-space: pre-line;">{{ $course->description }}</p>
                        <div class="d-flex flex-wrap gap-4 align-items-center mt-3 mb-2 text-white-50">
                            <span><i class="fa-solid fa-building me-2"></i>Viện GREECO</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Course Body Grid -->
        <main class="container py-5">
            @if(session('success'))
                <div class="alert alert-success mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                </div>
            @endif
            <div class="row g-4">
                <!-- Main Content (Left Column) -->
                <div class="col-lg-8">
                    <!-- Bootstrap 5 Native Nav Tabs -->
                    <ul class="nav nav-tabs mb-4" id="courseDetailTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="intro-tab" data-bs-toggle="tab" href="#intro-content" role="tab" aria-controls="intro-content" aria-selected="true">Giới thiệu</a>
                        </li>
                        @if(!empty($chapters))
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="curriculum-tab" data-bs-toggle="tab" href="#curriculum-content" role="tab" aria-controls="curriculum-content" aria-selected="false">Nội dung khóa học</a>
                            </li>
                        @endif
                        @if($course->instructor_name)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="instructor-tab" data-bs-toggle="tab" href="#instructor-content" role="tab" aria-controls="instructor-content" aria-selected="false">Giảng viên</a>
                            </li>
                        @endif

                    </ul>

                    <!-- Tab Panes -->
                    <div class="tab-content" id="courseDetailTabsContent">
                        <!-- Tab Content: Giới thiệu -->
                        <div class="tab-pane fade show active" id="intro-content" role="tabpanel" aria-labelledby="intro-tab">
                            <div class="card border-0 shadow-sm p-4 mb-4 bg-white rounded-3">
                                <h3 class="academy-heading fs-4 fw-bold mb-3">Thông tin khóa học</h3>
                                <div class="text-dark mb-4">
                                    @if($course->content)
                                        {!! $course->content !!}
                                    @else
                                        <p>{{ $course->description }}</p>
                                    @endif
                                </div>

                                <h3 class="academy-heading fs-4 fw-bold mb-3 mt-4">Thông số khóa học</h3>
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

                        <!-- Tab Content: Nội dung khóa học (Bootstrap 5 Accordion) -->
                        @if(!empty($chapters))
                            <div class="tab-pane fade" id="curriculum-content" role="tabpanel" aria-labelledby="curriculum-tab">
                                <div class="card border-0 shadow-sm p-4 mb-4 bg-white rounded-3">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <h3 class="academy-heading fs-4 fw-bold mb-0">Chương trình học</h3>
                                        <button class="btn btn-sm btn-outline-secondary" id="toggle-all-curriculum">Thu gọn tất cả</button>
                                    </div>

                                    <div class="accordion accordion-flush" id="curriculumAccordion">
                                        @foreach ($chapters as $index => $chapter)
                                            <div class="accordion-item border rounded-3 mb-2 overflow-hidden bg-white">
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
                                                                <div class="lesson-item d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
                                                                    <div class="lesson-title-wrapper d-flex align-items-center gap-2">
                                                                        <i class="fa-solid fa-circle-play" style="color: #006354; font-size: 15px;"></i>
                                                                        <span class="text-dark fw-bold" style="font-size: 0.95rem;">{{ $lesson['title'] }}</span>
                                                                    </div>
                                                                    @if(!empty($lesson['duration']))
                                                                        <span class="fw-bold" style="color: #006354; font-size: 0.85rem;">{{ $lesson['duration'] }}</span>
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
                                    <h3 class="academy-heading fs-4 fw-bold mb-4">Giảng viên hướng dẫn</h3>
                                    <div class="d-flex flex-column flex-sm-row gap-4 align-items-start">
                                        <img src="{{ $course->instructor_image ? asset('storage/' . $course->instructor_image) : asset('assets/images/team/team-1.jpg') }}" width="160" height="160" alt="{{ $course->instructor_name }}" class="instructor-avatar" loading="lazy" decoding="async">
                                        <div>
                                            <h4 class="academy-heading fs-5 fw-bold mb-1">{{ $course->instructor_name }}</h4>
                                            <p class="text-success small mb-3">{{ $course->instructor_role }}</p>
                                            <p class="text-dark mb-3">
                                                {{ $course->instructor_description }}
                                            </p>
                                            <div class="d-flex gap-3">
                                                <a href="mailto:info@greeco.vn" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-envelope me-2"></i>Gửi email</a>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#checkout-modal"><i class="fa-solid fa-comments me-2"></i>Đăng ký tư vấn</button>
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
                            <button class="btn btn-success btn-lg w-100 mb-4" data-bs-toggle="modal" data-bs-target="#checkout-modal">
                                <i class="fa-solid fa-comments me-2"></i>Liên hệ tư vấn
                            </button>

                            <div class="border-top pt-4">
                                <h4 class="academy-heading fs-6 fw-bold mb-3">Khóa học bao gồm:</h4>
                                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                                    <li class="d-flex align-items-center gap-3 text-dark small">
                                        <i class="fa-solid fa-circle-play text-dark fs-6" style="width: 20px;"></i>
                                        <span>{{ $totalLessons ?: 12 }} bài giảng học trực tuyến chất lượng</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-3 text-dark small">
                                        <i class="fa-solid fa-mobile-screen-button text-dark fs-6" style="width: 20px;"></i>
                                        <span>Học tập linh hoạt trên điện thoại & máy tính</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-3 text-dark small">
                                        <i class="fa-solid fa-download text-dark fs-6" style="width: 20px;"></i>
                                        <span>Biểu mẫu tài liệu học thực tế</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-3 text-dark small">
                                        <i class="fa-solid fa-award text-dark fs-6" style="width: 20px;"></i>
                                        <span>Cấp chứng nhận hoàn thành uy tín</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </main>
    </div>

    <!-- Mobile Bottom action bar -->
    <div class="mobile-bottom-bar justify-content-center">
        <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#checkout-modal">
            <i class="fa-solid fa-comments me-2"></i>Liên hệ tư vấn
        </button>
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
