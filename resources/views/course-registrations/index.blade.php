@php
    $seoTitle = 'Đăng ký khóa học - Viện nghiên cứu và phát triển kinh tế xanh - Greeco';
    $seoDescription = 'Chọn khóa học phù hợp và đăng ký tham gia tại Viện nghiên cứu và phát triển kinh tế xanh - Greeco.';
    $canonical = route('course-registration.index');
    $indexable = false;
    $courseCategories = $courses
        ->pluck('category.name')
        ->filter()
        ->unique()
        ->values();

    if ($courseCategories->isEmpty()) {
        $courseCategories = $courses->pluck('title')->take(8)->values();
    }
@endphp

@extends('layouts.app')

@push('styles')
    <style>
        .course-picker-page {
            --picker-black: #081d18;
            --picker-deep: #073f35;
            --picker-green: #008f79;
            --picker-mint: #dff2ec;
            --picker-paper: #f2f6f4;
            --picker-line: #cbdcd6;
            background: var(--picker-paper);
            color: #111111;
            font-family: 'Satoshi', 'Be Vietnam Pro', sans-serif;
            overflow-x: hidden;
            width: 100%;
        }

        .course-picker-shell {
            margin: 0 auto;
            max-width: 1320px;
            padding-inline: clamp(20px, 4vw, 64px);
            width: 100%;
        }

        .picker-hero {
            background:
                radial-gradient(circle at 77% 28%, rgba(57, 184, 151, .22), transparent 25%),
                linear-gradient(135deg, #062d26 0%, #073f35 62%, #095649 100%);
            min-height: auto;
            overflow: hidden;
            padding: 130px 0 65px !important;
            position: relative;
        }

        .picker-hero::before {
            background-image: radial-gradient(rgba(255, 255, 255, .14) .7px, transparent .7px);
            background-size: 8px 8px;
            content: '';
            inset: 0;
            opacity: .16;
            pointer-events: none;
            position: absolute;
        }

        .picker-hero-grid {
            align-items: center;
            display: grid;
            gap: clamp(30px, 5vw, 60px);
            grid-template-columns: minmax(0, 1.2fr) minmax(280px, .8fr);
            position: relative;
            z-index: 1;
        }

        .picker-hero-kicker {
            color: rgba(255, 255, 255, .9);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .picker-hero-title {
            color: #ffffff;
            font-size: clamp(2rem, 3.8vw, 3.4rem);
            font-weight: 900;
            letter-spacing: -.035em;
            line-height: 1.15;
            margin: 0;
            max-width: 650px;
        }

        .picker-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }

        .picker-button {
            align-items: center;
            border: 1px solid transparent;
            border-radius: 7px;
            display: inline-flex;
            font-size: .88rem;
            font-weight: 800;
            gap: 8px;
            justify-content: center;
            min-height: 46px;
            padding: 10px 20px;
            text-decoration: none;
            transition: background-color .25s ease, border-color .25s ease, color .25s ease, transform .25s ease;
        }

        .picker-button:hover {
            transform: translateY(-2px);
        }

        .picker-button-primary {
            background: #ffffff;
            color: #073f35 !important;
        }

        .picker-button-primary:hover {
            background: var(--picker-mint);
        }

        .picker-button-secondary {
            border-color: rgba(255, 255, 255, .55);
            color: #ffffff !important;
        }

        .picker-button-secondary:hover {
            background: rgba(255, 255, 255, .1);
            border-color: #ffffff;
        }

        .picker-hero-visual {
            aspect-ratio: 4 / 5;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 12px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, .25);
            margin-left: auto;
            max-width: 360px;
            overflow: hidden;
            position: relative;
            width: 100%;
        }

        .picker-hero-visual img {
            height: 100%;
            object-fit: cover;
            transition: transform .5s ease;
            width: 100%;
        }

        .picker-hero-visual:hover img {
            transform: scale(1.03);
        }

        .picker-marquee {
            background: #ffffff;
            border-bottom: 1px solid var(--picker-line);
            border-top: 1px solid var(--picker-line);
            overflow: hidden;
            padding: 19px 0 !important;
        }

        .picker-marquee-track {
            animation: picker-marquee 32s linear infinite;
            display: flex;
            width: max-content;
        }

        .picker-marquee-group {
            align-items: center;
            display: flex;
            flex-shrink: 0;
        }

        .picker-marquee-item {
            align-items: center;
            color: #111111;
            display: inline-flex;
            font-size: clamp(.9rem, 1.25vw, 1.05rem);
            font-weight: 800;
            gap: 22px;
            padding-right: 22px;
            white-space: nowrap;
        }

        .picker-marquee-item::after {
            background: var(--picker-green);
            border-radius: 50%;
            content: '';
            height: 6px;
            width: 6px;
        }

        @keyframes picker-marquee {
            to {
                transform: translateX(-50%);
            }
        }

        .picker-interest {
            background:
                radial-gradient(circle at 12% 24%, rgba(0, 143, 121, .08), transparent 24%),
                var(--picker-paper);
            padding: 50px 0 !important;
        }

        .picker-section-heading {
            align-items: center;
            display: flex;
            gap: 28px;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .picker-section-heading h2 {
            color: #111111;
            font-size: clamp(1.6rem, 3vw, 2.5rem);
            font-weight: 900;
            letter-spacing: -.035em;
            line-height: 1.15;
            margin: 0;
            max-width: 820px;
        }

        .picker-section-heading p {
            color: #111111;
            font-size: .9rem;
            line-height: 1.65;
            margin: 0;
            max-width: 330px;
        }

        .picker-bento {
            border: 1px solid var(--picker-line);
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            display: grid;
            grid-auto-flow: dense;
            grid-template-columns: minmax(0, 7fr) minmax(300px, 5fr);
            overflow: hidden;
        }

        .picker-form-panel {
            background: #ffffff;
            padding: 32px 36px;
        }

        .picker-form-label {
            color: #111111;
            display: block;
            font-size: .95rem;
            font-weight: 850;
            margin-bottom: 9px;
        }

        .picker-form-help {
            color: #111111;
            font-size: .86rem;
            line-height: 1.55;
            margin: 0 0 24px;
        }

        .picker-select {
            appearance: auto;
            background: #ffffff;
            border: 1.5px solid #8aa79f;
            border-radius: 7px;
            color: #111111;
            font-size: .92rem;
            min-height: 50px;
            padding: 9px 12px;
            width: 100%;
        }

        .picker-select optgroup {
            color: #073f35;
            font-size: .88rem;
            font-weight: 800;
            padding: 6px 0 2px;
        }

        .picker-select option {
            color: #111111;
            font-size: .88rem;
            padding: 4px 8px;
        }

        .picker-select:focus {
            border-color: var(--picker-green);
            box-shadow: 0 0 0 4px rgba(0, 143, 121, .12);
            outline: none;
        }

        .picker-submit {
            align-items: center;
            background: var(--picker-green);
            border: 0;
            border-radius: 7px;
            color: #ffffff;
            display: flex;
            font-size: .94rem;
            font-weight: 850;
            gap: 10px;
            justify-content: center;
            margin-top: 16px;
            min-height: 48px;
            padding: 10px 20px;
            transition: background-color .25s ease, transform .25s ease;
            width: 100%;
        }

        .picker-submit:hover {
            background: #006f5f;
            color: #ffffff;
            transform: translateY(-2px);
        }

        .picker-context-panel {
            background:
                linear-gradient(rgba(7, 63, 53, .92), rgba(7, 63, 53, .96)),
                url('{{ asset('assets/images/services/2.webp') }}') center / cover;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: auto;
            padding: 32px 36px;
        }

        .picker-context-panel h3 {
            color: #ffffff;
            font-size: clamp(1.4rem, 2.2vw, 2.2rem);
            font-weight: 900;
            letter-spacing: -.03em;
            line-height: 1.15;
            margin: 0;
        }

        .picker-context-link {
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, .35);
            color: #ffffff !important;
            display: flex;
            font-size: .85rem;
            font-weight: 850;
            justify-content: space-between;
            margin-top: 24px;
            padding-top: 16px;
            text-decoration: none;
        }

        .picker-desire {
            background: #ffffff;
            padding: 45px 0 60px !important;
        }

        .picker-steps {
            border: 1px solid var(--picker-line);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            display: flex;
            min-height: auto;
            overflow: hidden;
        }

        .picker-step {
            background: #ffffff;
            border-right: 1px solid var(--picker-line);
            display: flex;
            flex: 1;
            flex-direction: column;
            gap: 12px;
            min-width: 0;
            overflow: hidden;
            padding: 24px 28px;
            transition: background-color .3s ease;
        }

        .picker-step:last-child {
            border-right: 0;
        }

        .picker-step:hover {
            background: var(--picker-mint);
        }

        .picker-step-number {
            color: var(--picker-green);
            font-size: .82rem;
            font-weight: 900;
        }

        .picker-step h3 {
            color: #111111;
            font-size: 1.15rem;
            font-weight: 900;
            letter-spacing: -.02em;
            margin: 0 0 6px;
        }

        .picker-step p {
            color: #4b5563;
            font-size: .84rem;
            line-height: 1.5;
            margin: 0;
        }

        .picker-action {
            background: var(--picker-black);
            padding: 50px 0 !important;
        }

        .picker-action-inner {
            align-items: center;
            display: flex;
            gap: 40px;
            justify-content: space-between;
        }

        .picker-action h2 {
            color: #ffffff;
            font-size: clamp(2.4rem, 5vw, 5rem);
            font-weight: 900;
            letter-spacing: -.055em;
            line-height: .98;
            margin: 0;
            max-width: 850px;
            text-wrap: balance;
        }

        .picker-action .picker-button {
            background: var(--picker-green);
            color: #ffffff !important;
            flex-shrink: 0;
        }

        .picker-action .picker-button:hover {
            background: #00a98f;
        }

        @media (max-width: 991px) {
            .picker-hero {
                min-height: auto;
                padding: 135px 0 90px !important;
            }

            .picker-hero-grid,
            .picker-bento {
                grid-template-columns: 1fr;
            }

            .picker-hero-visual {
                aspect-ratio: 16 / 10;
                box-shadow: 18px 18px 0 rgba(3, 27, 23, .32);
            }

            .picker-section-heading,
            .picker-action-inner {
                align-items: flex-start;
                flex-direction: column;
            }

            .picker-steps {
                flex-direction: column;
            }

            .picker-step {
                border-bottom: 1px solid var(--picker-line);
                border-right: 0;
                min-height: 220px;
            }

            .picker-step:last-child {
                border-bottom: 0;
            }
        }

        @media (max-width: 575px) {
            .course-picker-shell {
                padding-inline: 16px;
            }

            .picker-hero {
                padding-top: 116px !important;
            }

            .picker-hero-title {
                font-size: clamp(2.5rem, 13vw, 4rem);
            }

            .picker-inline-image {
                display: none;
            }

            .picker-hero-actions,
            .picker-button,
            .picker-action .picker-button {
                width: 100%;
            }

            .picker-interest,
            .picker-desire,
            .picker-action {
                padding: 92px 0 !important;
            }

            .picker-form-panel,
            .picker-context-panel {
                padding: 28px 22px;
            }

            .picker-context-panel {
                min-height: 360px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .picker-marquee-track {
                animation-play-state: paused;
            }

            .picker-button,
            .picker-hero-visual img,
            .picker-step,
            .picker-submit {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <main class="course-picker-page" data-course-picker>
        <section class="picker-hero">
            <div class="course-picker-shell">
                <div class="picker-hero-grid">
                    <div>
                        <div class="picker-hero-kicker" data-picker-kicker>Đăng ký khóa học tại Viện nghiên cứu và phát triển kinh tế xanh - Greeco</div>
                        <h1 class="picker-hero-title" data-picker-title>
                            Chọn khóa học bạn muốn đăng ký
                        </h1>
                        <div class="picker-hero-actions" data-picker-actions>
                            <a class="picker-button picker-button-primary" href="#chon-khoa-hoc">
                                Chọn khóa học
                                <i class="fa-solid fa-arrow-down" aria-hidden="true"></i>
                            </a>
                            <a class="picker-button picker-button-secondary" href="{{ route('services.dao-tao') }}">
                                Xem nội dung đào tạo
                            </a>
                        </div>
                    </div>

                    <figure class="picker-hero-visual" data-picker-visual>
                        <img
                            src="{{ asset('assets/images/services/iso-9001-course.png') }}"
                            alt="Chương trình đào tạo chuyên nghiệp tại GREECO"
                            width="900"
                            height="1125"
                            loading="eager"
                            fetchpriority="high"
                        >
                    </figure>
                </div>
            </div>
        </section>

        @if ($courseCategories->isNotEmpty())
            <div class="picker-marquee" aria-label="Các nhóm khóa học">
                <div class="picker-marquee-track">
                    @foreach ([1, 2] as $group)
                        <div class="picker-marquee-group" @if ($group === 2) aria-hidden="true" @endif>
                            @foreach ($courseCategories as $category)
                                <span class="picker-marquee-item">{{ $category }}</span>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <section class="picker-interest" id="chon-khoa-hoc">
            <div class="course-picker-shell">
                <div class="picker-section-heading">
                    <h2>Chọn chương trình đào tạo phù hợp</h2>
                </div>

                <div class="picker-bento" data-picker-panel>
                    <div class="picker-form-panel">
                        @if ($courses->isNotEmpty())
                            @php
                                $groupedCourses = $courses->groupBy(fn ($c) => $c->category?->name ?: 'Khóa học khác');
                            @endphp
                            <form method="GET" action="{{ route('course-registration.index') }}">
                                <label class="picker-form-label" for="course">
                                    Khóa học <span class="text-danger" aria-hidden="true">*</span>
                                </label>
                                <select class="picker-select" id="course" name="course" required>
                                    <option value="" selected disabled>-- Chọn khóa học --</option>
                                    @foreach ($groupedCourses as $categoryName => $group)
                                        <optgroup label="{{ $categoryName }}">
                                            @foreach ($group as $course)
                                                <option value="{{ $course->slug }}">
                                                    {{ $course->title }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>

                                <button class="picker-submit" type="submit">
                                    Tiếp tục đăng ký
                                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info mb-0" role="status">
                                Hiện chưa có khóa học nào đang mở đăng ký. Vui lòng quay lại sau.
                            </div>
                        @endif
                    </div>

                    <aside class="picker-context-panel">
                        <div class="text-center text-md-start">
                            <div class="qr-code-box bg-white p-2 rounded-3 d-inline-block shadow-sm my-2">
                                <img src="{{ route('course-registration.selection-qr', ['format' => 'png']) }}"
                                     alt="Mã QR đăng ký khóa học"
                                     width="130"
                                     height="130"
                                     class="d-block rounded">
                            </div>
                        </div>
                        <a class="picker-context-link" href="{{ route('services.dao-tao') }}">
                            Khám phá toàn bộ khóa học
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </aside>
                </div>
            </div>
        </section>

        <section class="picker-desire">
            <div class="course-picker-shell">
                <div class="picker-steps">
                    <article class="picker-step" data-picker-step>
                        <span class="picker-step-number">01</span>
                        <div>
                            <h3>Chọn khóa học</h3>
                            <p>Lựa chọn chương trình đào tạo phù hợp với nhu cầu.</p>
                        </div>
                    </article>
                    <article class="picker-step" data-picker-step>
                        <span class="picker-step-number">02</span>
                        <div>
                            <h3>Điền thông tin</h3>
                            <p>Cung cấp thông tin đăng ký cho cá nhân hoặc doanh nghiệp.</p>
                        </div>
                    </article>
                    <article class="picker-step" data-picker-step>
                        <span class="picker-step-number">03</span>
                        <div>
                            <h3>GREECO xác nhận</h3>
                            <p>Đội ngũ GREECO liên hệ xác nhận lịch học & học phí.</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="picker-action">
            <div class="course-picker-shell">
                <div class="picker-action-inner">
                    <h2>Sẵn sàng chọn khóa học phù hợp?</h2>
                    <a class="picker-button" href="#chon-khoa-hoc">
                        Bắt đầu đăng ký
                        <i class="fa-solid fa-arrow-up" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection
