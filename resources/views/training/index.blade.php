@php
    $seoTitle = 'Đào tạo & Bồi dưỡng Chuyên môn Bền vững - Viện GREECO';
    $seoDescription = 'Viện GREECO cung cấp các chương trình đào tạo và bồi dưỡng chuyên sâu, thực tiễn từ cơ bản đến nâng cao. Đội ngũ chuyên gia của chúng tôi hỗ trợ doanh nghiệp xây dựng năng lực nội tại, chuẩn hóa hệ thống quản lý và đáp ứng các tiêu chuẩn xuất khẩu khắt khe về phát triển bền vững và trung hòa carbon.';
    $canonical = route('services.dao-tao');
    $ogImage = asset('assets/images/services/iso-9001-course.png');

    $breadcrumbs = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Đào tạo & Bồi dưỡng', 'url' => route('services.dao-tao')],
    ];
@endphp

@extends('layouts.app')

@push('styles')
    <style>
        .academy-catalog-page {
            font-family: 'Be Vietnam Pro', sans-serif;
            background-color: #f8fafc;
        }

        .academy-hero-banner {
            background: linear-gradient(135deg, #0f2c28 0%, #173d37 100%) !important;
            color: #ffffff !important;
            padding: 9.5rem 0 5.5rem !important;
            position: relative;
            text-align: center;
        }

        .search-box-wrapper {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .search-box-input {
            border: 2px solid #008f79;
            border-radius: 50px;
            padding: 14px 25px;
            width: 100%;
            font-size: 1rem;
            outline: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .search-box-btn {
            position: absolute;
            right: 6px;
            top: 6px;
            background: #008f79;
            border: none;
            color: #ffffff;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: background 0.2s ease;
        }

        .search-box-btn:hover {
            background: #007463;
        }

        .stat-item {
            text-align: center;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }

        .stat-icon {
            font-size: 1.8rem;
            color: #00ffca;
            margin-bottom: 8px;
            display: block;
        }

        .category-box {
            display: flex;
            flex-column: true;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border-radius: 12px;
            padding: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .category-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-color: #008f79;
        }

        .category-box.active {
            border-color: #008f79 !important;
            background-color: #f0fdfa !important;
            box-shadow: 0 8px 20px rgba(0, 143, 121, 0.1) !important;
            transform: translateY(-3px);
        }

        .category-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 12px;
        }

        .course-catalog-card {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            transition: all 0.3s ease;
        }

        .course-catalog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .course-card-img-wrapper {
            position: relative;
            overflow: hidden;
            padding-top: 56.25%;
            /* 16:9 aspect ratio */
        }

        .course-card-img-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .course-catalog-card:hover .course-card-img-wrapper img {
            transform: scale(1.05);
        }

        .course-tag-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
        }

        .course-rating-stars i {
            color: #ffc107;
            font-size: 0.85rem;
        }

        .instructor-avatar-card {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .testimonial-bubble {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            position: relative;
        }

        .newsletter-section {
            background: linear-gradient(135deg, #102a26 0%, #163d37 100%) !important;
            color: #ffffff;
            border-radius: 16px;
            padding: 50px 40px;
        }

        .btn-register-now {
            background: #008f79;
            color: #ffffff;
            font-weight: 700;
            border-radius: 50px;
            padding: 10px 24px;
            border: none;
            transition: background 0.2s ease;
        }

        .btn-register-now:hover {
            background: #007463;
            color: #ffffff;
        }
    </style>
@endpush

@section('content')
    <div class="academy-catalog-page">
        <!-- Hero Section -->
        <section class="academy-hero-banner">
            <div class="container">
                <h1 class="display-4 fw-bold mb-3 text-white">Nâng cao năng lực Phát triển bền vững</h1>
                <p class="lead text-white-50 max-width-600 mx-auto mb-4">
                    Các khóa học chất lượng cao về ISO, HSE, ESG, Carbon giúp bạn và doanh nghiệp bứt phá trong kỷ nguyên
                    xanh.
                </p>

                <!-- Search box -->
                <div class="search-box-wrapper mb-5">
                    <form action="#" method="GET" id="search-courses-form">
                        <input type="text" class="search-box-input" placeholder="Tìm khóa học bạn quan tâm..."
                            id="search-input">
                        <button type="submit" class="search-box-btn" aria-label="Tìm kiếm"><i
                                class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                </div>

                <!-- 4 Quick Stats -->
                <div class="row row-cols-2 row-cols-lg-4 g-4 mt-2 justify-content-center">
                    <div class="col">
                        <div class="stat-item">
                            <span class="stat-icon"><i class="fa-solid fa-user-tie"></i></span>
                            <strong>Giảng viên chuyên gia</strong>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-item">
                            <span class="stat-icon"><i class="fa-solid fa-graduation-cap"></i></span>
                            <strong>Nội dung thực tiễn</strong>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-item">
                            <span class="stat-icon"><i class="fa-solid fa-clock"></i></span>
                            <strong>Học mọi lúc mọi nơi</strong>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-item">
                            <span class="stat-icon"><i class="fa-solid fa-award"></i></span>
                            <strong>Chứng chỉ uy tín</strong>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Course Categories -->
        <section class="py-5">
            <div class="container py-lg-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h2 class="academy-heading fw-bold fs-4 mb-0">Danh mục khóa học</h2>
                    <a href="#chuong-trinh-dao-tao" id="clear-category-filter" class="text-success fw-bold text-decoration-none small">Xem tất cả</a>
                </div>

                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                    @foreach ($categories as $cat)
                        <div class="col">
                            <a href="#chuong-trinh-dao-tao" class="category-box h-100" data-filter="{{ $cat->name }}">
                                <div class="category-icon-wrapper"
                                    style="background-color: {{ $cat->bg }}; color: {{ $cat->color }};">
                                    <i class="fa-solid {{ $cat->icon }}"></i>
                                </div>
                                <strong class="academy-heading small text-dark">{{ $cat->name }}</strong>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Featured Courses Section -->
        <section class="py-5 bg-white" id="chuong-trinh-dao-tao">
            <div class="container py-lg-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h2 class="academy-heading fw-bold fs-4 mb-0">Khóa học nổi bật</h2>
                    <a href="#tu-van-dao-tao" class="text-success fw-bold text-decoration-none small">Yêu cầu chương trình
                        riêng</a>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="courses-catalog-grid">
                    @foreach ($programs as $program)
                        <div class="col course-catalog-item" data-category="{{ $program['category_name'] }}">
                            <article class="course-catalog-card h-100 d-flex flex-column">
                                <div class="course-card-img-wrapper">
                                    @if(isset($program['slug']))
                                        <a href="{{ route('services.course-detail', ['slug' => $program['slug']]) }}">
                                    @else
                                            <a href="#tu-van-dao-tao" class="scroll-to-inquiry"
                                                data-inquiry-topic="{{ $program['title'] }}">
                                        @endif
                                            <img src="{{ asset($program['image']) }}" alt="{{ $program['title'] }}">
                                        </a>
                                        <span class="badge course-tag-badge {{ $program['badge_color'] }} text-white">
                                            {{ $program['tag'] }}
                                        </span>
                                </div>
                                <div class="card-body p-4 d-flex flex-column">
                                    <h3 class="academy-heading fs-6 fw-bold mb-2">
                                        @if(isset($program['slug']))
                                            <a href="{{ route('services.course-detail', ['slug' => $program['slug']]) }}"
                                                class="text-reset text-decoration-none">
                                                {{ $program['title'] }}
                                            </a>
                                        @else
                                            <a href="#tu-van-dao-tao" class="text-reset text-decoration-none scroll-to-inquiry"
                                                data-inquiry-topic="{{ $program['title'] }}">
                                                {{ $program['title'] }}
                                            </a>
                                        @endif
                                    </h3>
                                    <p class="text-secondary small mb-3 flex-grow-1"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $program['description'] }}
                                    </p>
                                    <div class="text-secondary small mb-3">Viện GREECO</div>

                                    <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top">
                                        <div>
                                            <strong class="text-success fs-5 d-block">{{ $program['price'] }}</strong>
                                            <small
                                                class="text-secondary text-decoration-line-through">{{ $program['original_price'] }}</small>
                                        </div>
                                        @if(isset($program['slug']))
                                            <a href="{{ route('services.course-detail', ['slug' => $program['slug']]) }}"
                                                class="btn btn-sm btn-register-now">
                                                Xem chi tiết
                                            </a>
                                        @else
                                            <a href="#tu-van-dao-tao" class="btn btn-sm btn-outline-success scroll-to-inquiry"
                                                data-inquiry-topic="{{ $program['title'] }}">
                                                Đăng ký tư vấn
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Instructors Section -->
        <section class="py-5 bg-light">
            <div class="container py-lg-4 text-center">
                <h2 class="academy-heading fw-bold fs-4 mb-5">Giảng viên tiêu biểu</h2>
                <div class="row justify-content-center g-4">
                    @foreach ($instructors as $inst)
                        <div class="col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm p-4 h-100 bg-white rounded-3">
                                <img src="{{ asset($inst['image']) }}" alt="{{ $inst['name'] }}"
                                    class="instructor-avatar-card mx-auto mb-3">
                                <h3 class="academy-heading fs-6 fw-bold mb-1">{{ $inst['name'] }}</h3>
                                <p class="text-success small mb-0">{{ $inst['role'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-5 bg-white">
            <div class="container py-lg-4 max-width-800">
                <h2 class="academy-heading fw-bold fs-4 text-center mb-5">Học viên nói gì về GREECO</h2>
                <div class="testimonial-bubble mx-auto mb-4">
                    <p class="text-secondary italic mb-4">
                        "Khóa học rất thực tế, giảng viên giàu kinh nghiệm, hỗ trợ nhiệt tình. Chứng chỉ của GREECO giúp ích
                        rất nhiều cho công việc của tôi."
                    </p>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span class="academy-icon-sm" style="width: 40px; height: 40px;"><i
                                    class="fa-solid fa-user"></i></span>
                            <div class="text-start">
                                <strong class="d-block academy-heading small">Nguyễn Hoàng Nam</strong>
                                <small class="text-secondary" style="font-size: 0.75rem;">Chuyên viên QHSE</small>
                            </div>
                        </div>
                        <div class="course-rating-stars">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                            <small class="fw-bold text-dark ms-2">5.0</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Inquiry Form & Newsletter Section -->
        <section class="py-5 bg-light" id="tu-van-dao-tao">
            <div class="container py-lg-4">
                <div class="newsletter-section">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-6">
                            <h2 class="text-white fw-bold mb-3">Đăng ký nhận thông tin khóa học mới nhất</h2>
                            <p class="text-white-50 mb-0">
                                Nhập email của bạn để nhận thông tin ưu đãi và các khóa học mới cập nhật từ Viện GREECO.
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <form method="POST" action="{{ route('contact.store') }}" id="newsletter-form">
                                @csrf
                                <input type="hidden" name="_return" value="training">
                                <input type="hidden" name="subject" value="Đăng ký nhận bản tin Khóa học">
                                <input type="hidden" name="message"
                                    value="Đăng ký nhận bản tin Khóa học từ trang Đào tạo & Bồi dưỡng">

                                <div class="input-group">
                                    <input type="email" class="form-control py-3 px-4 rounded-start"
                                        placeholder="Nhập email của bạn..." name="email" required>
                                    <button class="btn btn-success px-4 rounded-end fw-bold" type="submit">Đăng ký</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Inquiry Box Form below for detailed requests -->
                <div class="card border-0 shadow mt-5">
                    <div class="card-body p-4 p-md-5">
                        @if(session('success'))
                            <div class="alert alert-success" role="alert">
                                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <strong>Vui lòng kiểm tra lại thông tin.</strong>
                            </div>
                        @endif

                        <h3 class="academy-heading fs-4 fw-bold mb-2">Yêu cầu tư vấn chương trình chi tiết</h3>
                        <p class="text-secondary small mb-4">
                            Điền thông tin bên dưới, GREECO sẽ liên hệ để trao đổi nhu cầu đào tạo cụ thể của cá nhân hoặc
                            doanh nghiệp.
                        </p>

                        <form method="post" action="{{ route('contact.store') }}" class="row g-3">
                            @csrf
                            <input type="hidden" name="_return" value="training">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small" for="academy_inquiry_name">Họ và tên</label>
                                <input class="form-control @error('name') is-invalid @enderror" id="academy_inquiry_name"
                                    name="name" type="text" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small" for="academy_inquiry_phone">Số điện thoại</label>
                                <input class="form-control @error('phone') is-invalid @enderror" id="academy_inquiry_phone"
                                    name="phone" type="tel" value="{{ old('phone') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small" for="academy_inquiry_email">Email</label>
                                <input class="form-control @error('email') is-invalid @enderror" id="academy_inquiry_email"
                                    name="email" type="email" value="{{ old('email') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small" for="academy_inquiry_subject">Chương trình quan
                                    tâm</label>
                                <input class="form-control academy-inquiry-subject" id="academy_inquiry_subject"
                                    name="subject" type="text" value="{{ old('subject', 'Tư vấn chương trình đào tạo') }}">
                                <small class="form-text text-secondary" id="academy_inquiry_selection" aria-live="polite">
                                    Nội dung này sẽ tự cập nhật khi bạn chọn một chương trình hoặc danh mục phía trên.
                                </small>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small" for="academy_inquiry_message">Nhu cầu cụ thể của
                                    doanh nghiệp</label>
                                <textarea class="form-control" id="academy_inquiry_message" name="message" rows="4"
                                    placeholder="Ví dụ: số lượng học viên, hình thức mong muốn, thời gian dự kiến..."></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-success w-100 py-3 fw-bold" type="submit">Gửi yêu cầu tư vấn</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var inquirySubject = document.getElementById('academy_inquiry_subject');
            var inquirySelection = document.getElementById('academy_inquiry_selection');

            // Prevent search form submission reload
            var searchForm = document.getElementById('search-courses-form');
            if (searchForm) {
                searchForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                });
            }

            var activeCategory = 'all';
            var searchInput = document.getElementById('search-input');

            function filterCourses() {
                var query = searchInput ? searchInput.value.toLowerCase().trim() : '';
                var cards = document.querySelectorAll('.course-catalog-item');
                
                cards.forEach(function (card) {
                    var title = card.querySelector('h3').textContent.toLowerCase();
                    var descEl = card.querySelector('p');
                    var desc = descEl ? descEl.textContent.toLowerCase() : '';
                    var cardCategory = card.getAttribute('data-category');
                    
                    // Match category
                    var matchesCategory = (activeCategory === 'all' || cardCategory === activeCategory);
                    
                    // Match search query
                    var matchesSearch = (title.indexOf(query) !== -1 || desc.indexOf(query) !== -1);
                    
                    if (matchesCategory && matchesSearch) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Quick search filter logic
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    filterCourses();
                });
            }

            // Category filter click handler
            var categoryLinks = document.querySelectorAll('.category-box');
            categoryLinks.forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    var filterVal = link.getAttribute('data-filter');

                    if (activeCategory === filterVal) {
                        // Toggle off if clicking the already active category
                        activeCategory = 'all';
                        link.classList.remove('active');
                    } else {
                        // Set new active category
                        activeCategory = filterVal;
                        categoryLinks.forEach(function (item) {
                            item.classList.remove('active');
                        });
                        link.classList.add('active');
                    }

                    // Apply filter
                    filterCourses();

                    // Update inquiry subject
                    if (inquirySubject) {
                        inquirySubject.value = 'Tư vấn chương trình: ' + (activeCategory === 'all' ? '' : activeCategory);
                    }
                    if (inquirySelection) {
                        inquirySelection.textContent = activeCategory === 'all' ? '' : ('Đã chọn danh mục: ' + activeCategory);
                    }

                    // Scroll smoothly to course catalog section
                    var target = document.getElementById('chuong-trinh-dao-tao');
                    if (target) {
                        var header = document.querySelector('header');
                        var headerHeight = header ? header.offsetHeight : 90;
                        var elementPosition = target.getBoundingClientRect().top;
                        var offsetPosition = elementPosition + (window.pageYOffset || document.documentElement.scrollTop) - headerHeight - 20;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Clear category filter link click handler
            var clearFilterLink = document.getElementById('clear-category-filter');
            if (clearFilterLink) {
                clearFilterLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    activeCategory = 'all';
                    categoryLinks.forEach(function (item) {
                        item.classList.remove('active');
                    });
                    if (searchInput) {
                        searchInput.value = '';
                    }
                    filterCourses();

                    if (inquirySubject) inquirySubject.value = '';
                    if (inquirySelection) inquirySelection.textContent = '';
                });
            }

            // Smooth scrolling logic for catalog scroll-to-inquiry links
            document.querySelectorAll('.scroll-to-inquiry, a[href^="#"]').forEach(function (link) {
                link.addEventListener('click', function (event) {
                    var href = link.getAttribute('href');
                    if (!href || href === '#' || href.indexOf('#') === -1) return;

                    var target = document.querySelector(href.substring(href.indexOf('#')));
                    var topic = link.getAttribute('data-inquiry-topic');

                    if (inquirySubject && topic) {
                        inquirySubject.value = topic;
                    }
                    if (inquirySelection && topic) {
                        inquirySelection.textContent = 'Đã chọn khóa học: ' + topic;
                    }

                    if (!target) return;

                    event.preventDefault();
                    event.stopPropagation();
                    event.stopImmediatePropagation();

                    var header = document.querySelector('header');
                    var headerHeight = header ? header.offsetHeight : 90;
                    var elementPosition = target.getBoundingClientRect().top;
                    var offsetPosition = elementPosition + (window.pageYOffset || document.documentElement.scrollTop) - headerHeight - 20;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });

                    window.history.replaceState(null, '', href);
                });
            });
        });
    </script>
@endpush