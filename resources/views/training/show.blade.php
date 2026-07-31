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

    $plainDescription = trim(strip_tags($course->description ?: ''));
    $heroDescription = (string) \Illuminate\Support\Str::of($plainDescription)
        ->lower()
        ->ucfirst()
        ->replace(['iso', 'pdca'], ['ISO', 'PDCA'])
        ->limit(230);
@endphp

@extends('layouts.app')

@push('styles')
    <style>
        .course-page {
            --course-ink: #092f28;
            --course-green: #008f79;
            --course-green-dark: #006c5c;
            --course-mint: #dff4ee;
            --course-line: #dce8e4;
            --course-paper: #f4f8f6;
            background: #fff;
            color: #000000;
            font-family: 'Satoshi', 'Be Vietnam Pro', sans-serif;
            overflow-x: hidden;
        }

        .course-shell {
            margin: 0 auto;
            max-width: 1500px;
            padding-left: 28px;
            padding-right: 28px;
            width: 100%;
        }

        .course-hero {
            background:
                radial-gradient(circle at 15% 5%, rgba(0, 143, 121, .13), transparent 34%),
                linear-gradient(135deg, #f5faf8 0%, #edf5f2 100%);
            min-height: 540px;
            padding: 132px 0 64px;
            position: relative;
        }

        .course-hero-grid {
            align-items: center;
            display: grid;
            gap: clamp(36px, 4vw, 60px);
            grid-template-columns: minmax(0, 1.35fr) minmax(320px, .65fr);
        }

        .course-breadcrumbs {
            align-items: center;
            color: #000000;
            display: flex;
            flex-wrap: wrap;
            font-size: .78rem;
            font-weight: 700;
            gap: 9px;
            margin: 0 0 20px;
            padding: 0;
        }

        .course-breadcrumbs li {
            align-items: center;
            display: flex;
            gap: 9px;
        }

        .course-breadcrumbs li:not(:last-child)::after {
            color: #777777;
            content: '/';
        }

        .course-breadcrumbs a {
            color: inherit;
            text-decoration: none;
        }

        .course-hero-title {
            color: #000000;
            font-size: clamp(2.1rem, 2.7vw, 3.2rem);
            font-weight: 800;
            letter-spacing: -.045em;
            line-height: 1.05;
            margin: 0;
            max-width: 920px;
            text-wrap: balance;
            text-transform: none;
        }

        .course-hero-copy {
            color: #000000;
            font-size: clamp(.95rem, 1.1vw, 1.05rem);
            line-height: 1.65;
            margin: 20px 0 0;
            max-width: 640px;
        }

        .course-hero-price {
            align-items: baseline;
            display: flex;
            gap: 14px;
            margin-top: 22px;
        }

        .course-hero-price span {
            color: #000000;
            font-size: .85rem;
            font-weight: 700;
        }

        .course-hero-price strong {
            color: var(--course-green);
            font-size: 1.3rem;
            letter-spacing: -.025em;
        }

        .course-hero-price del {
            color: #889994;
            font-size: .88rem;
        }

        .course-hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 26px;
        }

        .course-button {
            align-items: center;
            border: 1px solid transparent;
            border-radius: 6px;
            display: inline-flex;
            font-size: .88rem;
            font-weight: 800;
            gap: 8px;
            justify-content: center;
            min-height: 46px;
            padding: 0 20px;
            text-decoration: none;
            transition: background .25s ease, border-color .25s ease, color .25s ease, transform .25s ease;
        }

        .course-button:hover {
            transform: translateY(-2px);
        }

        .course-button-primary {
            background: var(--course-green);
            color: #fff !important;
        }

        .course-button-primary:hover {
            background: var(--course-green-dark);
        }

        .course-button-secondary {
            background: transparent;
            border-color: #a9bdb7;
            color: var(--course-ink) !important;
        }

        .course-button-secondary:hover {
            background: #fff;
            border-color: var(--course-ink);
        }

        .course-hero-visual {
            aspect-ratio: 4 / 5;
            border-radius: 4px 32px 4px 32px;
            box-shadow: 24px 24px 0 var(--course-mint);
            max-height: 430px;
            overflow: hidden;
            position: relative;
        }

        .course-hero-visual::after {
            background: linear-gradient(180deg, transparent 60%, rgba(3, 34, 28, .36));
            content: '';
            inset: 0;
            pointer-events: none;
            position: absolute;
            z-index: 2;
        }

        .course-hero-visual::before {
            background: linear-gradient(90deg, #f8fbfa 0%, #f8fbfa 45%, rgba(248, 251, 250, 0) 100%);
            content: '';
            inset: 0 auto 0 0;
            pointer-events: none;
            position: absolute;
            width: 18%;
            z-index: 3;
        }

        .course-hero-visual img {
            height: 100%;
            object-fit: cover;
            object-position: 78% center;
            transform: scale(1.85);
            transform-origin: 78% center;
            transition: transform .8s cubic-bezier(.2, .8, .2, 1);
            width: 100%;
        }

        .course-hero-visual:hover img {
            transform: scale(1.9);
        }

        .course-curriculum {
            background: #f0f6f3;
            padding: 70px 0;
        }

        .course-curriculum-grid {
            align-items: start;
            display: grid;
            gap: clamp(54px, 8vw, 120px);
            grid-template-columns: minmax(260px, .7fr) minmax(0, 1.3fr);
        }

        .course-section-heading {
            max-width: 410px;
        }

        .course-section-heading h2 {
            color: var(--course-ink);
            font-size: clamp(1.75rem, 2.8vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -.04em;
            line-height: 1.05;
            margin: 0 0 18px;
            text-wrap: balance;
        }

        .course-section-heading p {
            color: #000000;
            line-height: 1.75;
            margin: 0;
        }

        .course-accordion {
            border-top: 1px solid #bfcfc9;
        }

        .course-accordion .accordion-item {
            background: transparent;
            border: 0;
            border-bottom: 1px solid #bfcfc9;
            border-radius: 0;
        }

        .course-accordion .accordion-button {
            background: transparent !important;
            box-shadow: none !important;
            color: var(--course-ink) !important;
            font-size: clamp(.98rem, 1.25vw, 1.1rem);
            font-weight: 800;
            gap: 16px;
            padding: 18px 0;
        }

        .course-chapter-number {
            color: var(--course-green);
            flex: 0 0 36px;
            font-size: .78rem;
            letter-spacing: .08em;
        }

        .course-accordion .accordion-body {
            padding: 0 0 18px 48px;
        }

        .course-lesson {
            align-items: flex-start;
            color: #000000;
            display: flex;
            gap: 12px;
            justify-content: space-between;
            padding: 8px 0;
        }

        .course-lesson-title {
            align-items: flex-start;
            display: flex;
            gap: 12px;
        }

        .course-lesson i {
            color: var(--course-green);
            margin-top: 5px;
        }

        .course-lesson-duration {
            color: #555555;
            flex-shrink: 0;
            font-size: .78rem;
        }

        .course-instructor {
            padding: 60px 0;
        }

        .course-instructor-card {
            align-items: center;
            background: var(--course-paper);
            display: grid;
            gap: clamp(24px, 4vw, 56px);
            grid-template-columns: minmax(200px, .7fr) minmax(0, 1.3fr);
            padding: clamp(24px, 4vw, 50px);
        }

        .course-instructor-card img {
            aspect-ratio: 1;
            filter: saturate(.78);
            object-fit: cover;
            width: 100%;
        }

        .course-instructor-card h2 {
            color: var(--course-ink);
            font-size: clamp(1.6rem, 2.6vw, 2.6rem);
            font-weight: 800;
            letter-spacing: -.04em;
            line-height: 1.05;
            margin: 0 0 10px;
        }

        .course-instructor-role {
            color: var(--course-green);
            font-weight: 800;
            margin-bottom: 16px;
        }

        .course-instructor-description {
            color: #000000;
            line-height: 1.7;
            margin: 0;
        }

        .course-action-section {
            padding: 20px 0 60px;
        }

        .course-action-panel {
            align-items: center;
            background:
                radial-gradient(circle at 10% 0%, rgba(0, 199, 167, .21), transparent 35%),
                var(--course-ink);
            color: #fff;
            display: grid;
            gap: clamp(48px, 8vw, 110px);
            grid-template-columns: minmax(0, 1.25fr) minmax(300px, .75fr);
            padding: clamp(42px, 7vw, 88px);
        }

        .course-action-copy h2 {
            color: #fff;
            font-size: clamp(2.35rem, 4.4vw, 4.8rem);
            font-weight: 800;
            letter-spacing: -.055em;
            line-height: .98;
            margin: 0;
            max-width: 720px;
            text-wrap: balance;
        }

        .course-action-copy p {
            color: rgba(255, 255, 255, .72);
            line-height: 1.75;
            margin: 26px 0 32px;
            max-width: 600px;
        }

        .course-action-copy .course-button-primary {
            background: #00c7a7;
            color: #052a23 !important;
        }

        .course-action-copy .course-button-primary:hover {
            background: #fff;
        }

        .course-qr-card {
            background: #fff;
            color: var(--course-ink);
            padding: 24px;
            text-align: center;
        }

        .course-qr-frame {
            align-items: center;
            background: #fff;
            border: 1px solid var(--course-line);
            display: flex;
            justify-content: center;
            padding: 12px;
        }

        .course-qr-image {
            aspect-ratio: 1;
            display: block;
            height: auto;
            image-rendering: crisp-edges;
            max-width: 240px;
            width: 100%;
        }

        .course-qr-card h3 {
            color: var(--course-ink);
            font-size: 1.05rem;
            font-weight: 800;
            margin: 20px 0 6px;
        }

        .course-qr-card p {
            color: #687e78;
            font-size: .8rem;
            line-height: 1.55;
            margin: 0 auto 16px;
            max-width: 260px;
        }

        .course-qr-downloads {
            display: grid;
            gap: 8px;
            grid-template-columns: repeat(2, 1fr);
        }

        .course-qr-downloads a {
            border: 1px solid #aac0ba;
            color: var(--course-green-dark);
            font-size: .78rem;
            font-weight: 800;
            padding: 9px 12px;
            text-decoration: none;
        }

        .course-qr-downloads a:hover {
            background: var(--course-mint);
        }

        .course-mobile-action {
            display: none;
        }

        @media (max-width: 991px) {
            .course-shell {
                padding-left: 20px;
                padding-right: 20px;
            }

            .course-hero {
                min-height: auto;
                padding: 132px 0 80px;
            }

            .course-hero-grid,
            .course-curriculum-grid,
            .course-instructor-card,
            .course-action-panel {
                grid-template-columns: 1fr;
            }

            .course-hero-visual {
                aspect-ratio: 16 / 10;
                box-shadow: 18px 18px 0 var(--course-mint);
                max-height: 480px;
            }

            .course-action-panel {
                justify-items: stretch;
            }

            .course-qr-card {
                max-width: 360px;
            }

            .course-mobile-action {
                background: #fff;
                border-top: 1px solid var(--course-line);
                bottom: 0;
                display: block;
                left: 0;
                padding: 12px 16px;
                position: fixed;
                right: 0;
                z-index: 1000;
            }

            .course-mobile-action .course-button {
                width: 100%;
            }
        }

        @media (max-width: 575px) {
            .course-shell {
                padding-left: 16px;
                padding-right: 16px;
            }

            .course-hero {
                padding-bottom: 64px;
            }

            .course-hero-title {
                font-size: clamp(2.35rem, 11vw, 3.4rem);
            }

            .course-hero-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .course-curriculum,
            .course-instructor {
                padding: 84px 0;
            }

            .course-accordion .accordion-body {
                padding-left: 0;
            }

            .course-lesson {
                flex-direction: column;
                gap: 4px;
            }

            .course-action-section {
                padding-bottom: 90px;
            }

            .course-action-panel {
                margin-left: -16px;
                margin-right: -16px;
                padding: 42px 20px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .course-button,
            .course-hero-visual img {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />
    <x-schema.course :course="$course" :url="$canonical" :image="$ogImage" />

    <main class="course-page">
        <section class="course-hero">
            <div class="course-shell">
                <div class="course-hero-grid">
                    <div>
                        <ul class="course-breadcrumbs" aria-label="Đường dẫn">
                            @foreach ($breadcrumbs as $index => $crumb)
                                <li>
                                    @if ($index === count($breadcrumbs) - 1)
                                        <span>{{ $crumb['name'] }}</span>
                                    @else
                                        <a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <h1 class="course-hero-title" data-course-hero-title>{{ $course->title }}</h1>

                        @if ($heroDescription)
                            <p class="course-hero-copy" data-course-hero-copy>{{ $heroDescription }}</p>
                        @endif

                        <div class="course-hero-price">
                            <span>Học phí</span>
                            <strong>{{ $course->price ?: 'Liên hệ' }}</strong>
                            @if ($course->original_price)
                                <del>{{ $course->original_price }}</del>
                            @endif
                        </div>

                        <div class="course-hero-actions" data-course-hero-actions>
                            <a href="{{ route('course-registration.create', $course) }}" class="course-button course-button-primary">
                                Đăng ký khóa học
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                            <a href="{{ ! empty($chapters) ? '#course-curriculum' : '#course-registration' }}" class="course-button course-button-secondary">
                                Xem nội dung
                            </a>
                        </div>
                    </div>

                    <div class="course-hero-visual" data-scale-reveal>
                        <img
                            src="{{ $ogImage }}"
                            width="900"
                            height="1100"
                            alt="{{ $course->title }}"
                            fetchpriority="high"
                            decoding="async"
                        >
                    </div>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div class="course-shell pt-4">
                <div class="alert alert-success border-0 shadow-sm" role="alert">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (! empty($chapters))
            <section class="course-curriculum" id="course-curriculum">
                <div class="course-shell">
                    <div class="course-curriculum-grid" data-pin-container>
                        <div class="course-section-heading" data-pin-panel>
                            <h2>Chương trình học</h2>
                            <p>
                                Nội dung được chia thành từng phần để học viên dễ theo dõi, thực hành và áp dụng vào tình huống thực tế.
                            </p>
                        </div>

                        <div class="accordion course-accordion" id="curriculumAccordion">
                            @foreach ($chapters as $index => $chapter)
                                <article class="accordion-item">
                                    <h3 class="accordion-header" id="course-heading-{{ $index }}">
                                        <button
                                            class="accordion-button collapsed"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#course-collapse-{{ $index }}"
                                            aria-expanded="false"
                                            aria-controls="course-collapse-{{ $index }}"
                                        >
                                            <span class="course-chapter-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                            <span>{{ $chapter['title'] }}</span>
                                        </button>
                                    </h3>
                                    <div
                                        id="course-collapse-{{ $index }}"
                                        class="accordion-collapse collapse"
                                        aria-labelledby="course-heading-{{ $index }}"
                                        data-bs-parent="#curriculumAccordion"
                                    >
                                        <div class="accordion-body">
                                            @foreach (($chapter['lessons'] ?? []) as $lesson)
                                                <div class="course-lesson">
                                                    <div class="course-lesson-title">
                                                        <i class="fa-regular fa-circle-check"></i>
                                                        <span>{{ $lesson['title'] }}</span>
                                                    </div>
                                                    @if (! empty($lesson['duration']))
                                                        <span class="course-lesson-duration">{{ $lesson['duration'] }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if ($course->instructor_name)
            <section class="course-instructor">
                <div class="course-shell">
                    <article class="course-instructor-card">
                        <img
                            src="{{ $course->instructor_image ? asset('storage/' . $course->instructor_image) : asset('assets/images/team/team-1.jpg') }}"
                            width="640"
                            height="640"
                            alt="{{ $course->instructor_name }}"
                            loading="lazy"
                            decoding="async"
                            data-scale-reveal
                        >
                        <div>
                            <h2>{{ $course->instructor_name }}</h2>
                            @if ($course->instructor_role)
                                <div class="course-instructor-role">{{ $course->instructor_role }}</div>
                            @endif
                            @if ($course->instructor_description)
                                <p class="course-instructor-description">{{ $course->instructor_description }}</p>
                            @endif
                        </div>
                    </article>
                </div>
            </section>
        @endif

        <section class="course-action-section" id="course-registration">
            <div class="course-shell">
                <div class="course-action-panel">
                    <div class="course-action-copy">
                        <h2>Sẵn sàng tham gia khóa học?</h2>
                        <p>
                            Đăng ký trực tiếp trên website hoặc quét mã QR bằng điện thoại. GREECO sẽ liên hệ xác nhận lịch học, địa điểm và học phí.
                        </p>
                        <a href="{{ route('course-registration.create', $course) }}" class="course-button course-button-primary">
                            Đi đến form đăng ký
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>

                    <aside class="course-qr-card">
                        <div class="course-qr-frame">
                            <img
                                src="{{ route('course-registration.qr', ['course' => $course, 'format' => 'png']) }}"
                                alt="Mã QR đăng ký khóa học {{ $course->title }}"
                                width="240"
                                height="240"
                                class="course-qr-image"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                        <h3 class="mb-0">Quét mã để đăng ký</h3>
                    </aside>
                </div>
            </div>
        </section>
    </main>

    <div class="course-mobile-action">
        <a href="{{ route('course-registration.create', $course) }}" class="course-button course-button-primary">
            Đăng ký khóa học
        </a>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.classList.add('has-mobile-bottom-bar');
        });
    </script>
@endpush
