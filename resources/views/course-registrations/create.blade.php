@php
    $seoTitle = 'Đăng ký khóa học ' . $course->title . ' - GREECO';
    $seoDescription = 'Đăng ký tham gia khóa học ' . $course->title . ' tại Viện GREECO.';
    $canonical = route('course-registration.create', $course);
    $indexable = false;
    $initialAttendees = old('attendees', [
        ['full_name' => '', 'job_title' => '', 'email' => '', 'phone' => ''],
    ]);
    $initialCount = max(1, min(20, (int) old('attendee_count', count($initialAttendees))));
@endphp

@extends('layouts.app')

@push('styles')
    <style>
        .registration-page {
            background:
                radial-gradient(circle at 15% 20%, rgba(0, 143, 121, .11), transparent 26%),
                radial-gradient(circle at 88% 42%, rgba(0, 199, 167, .08), transparent 25%),
                #f2f6f5;
            font-family: 'Satoshi', 'Be Vietnam Pro', sans-serif;
            overflow-x: hidden;
            padding: 110px 0 60px;
        }

        .registration-card,
        .course-summary-card {
            background: #fff;
            border: 1px solid #dfe9e6;
            border-radius: 12px;
            box-shadow: 0 12px 35px rgba(9, 32, 28, .07);
        }

        .registration-card {
            padding: clamp(20px, 3vw, 32px);
        }

        .registration-back-link {
            color: #48645e;
            display: inline-flex;
            font-size: .8rem;
            font-weight: 700;
            gap: 6px;
            margin-bottom: 16px;
            text-decoration: none;
        }

        .registration-back-link:hover {
            color: #008f79;
        }

        .registration-card-title {
            color: #092f28;
            font-size: clamp(1.4rem, 2.2vw, 1.95rem);
            font-weight: 800;
            letter-spacing: -.035em;
            line-height: 1.12;
            margin-bottom: 8px;
            text-wrap: balance;
        }

        .registration-eyebrow {
            color: #008f79;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .1em;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .registration-card-intro {
            color: #000000;
            font-size: .92rem;
            line-height: 1.6;
            max-width: 680px;
        }

        .course-summary-card {
            padding: 18px;
            position: relative;
        }

        .course-summary-image {
            aspect-ratio: 16 / 10;
            object-fit: cover;
            width: 100%;
        }

        .registration-section-title {
            border-bottom: 1px solid #e6eeec;
            color: #153b35;
            font-size: .98rem;
            font-weight: 800;
            margin: 22px 0 14px;
            padding-bottom: 8px;
        }

        .attendee-card {
            background:
                linear-gradient(135deg, rgba(0, 143, 121, .045), transparent 55%),
                #f8fbfa;
            border: 1px solid #dbe8e5;
            border-radius: 8px;
            margin-bottom: 12px;
            padding: 14px 16px;
            transition: border-color .35s ease, box-shadow .35s ease, transform .35s ease;
        }

        .attendee-card:focus-within {
            border-color: #008f79;
            box-shadow: 0 10px 24px rgba(0, 143, 121, .08);
            transform: translateY(-1px);
        }

        .attendee-number {
            align-items: center;
            background: #008f79;
            border-radius: 50%;
            color: #fff;
            display: inline-flex;
            font-size: .72rem;
            font-weight: 800;
            height: 24px;
            justify-content: center;
            width: 24px;
        }

        .required-mark {
            color: #dc3545;
        }

        .registration-submit {
            background: #008f79;
            border-color: #008f79;
            border-radius: 4px;
            font-size: .9rem;
            font-weight: 800;
            padding: 10px 20px;
        }

        .registration-submit:hover {
            background: #006f5f;
            border-color: #006f5f;
        }

        .registration-guide {
            border-top: 1px solid #e0e9e7;
            display: grid;
            gap: 10px;
            margin-top: 16px;
            padding-top: 16px;
        }

        .registration-guide-item {
            align-items: flex-start;
            display: flex;
            gap: 10px;
        }

        .registration-guide-number {
            align-items: center;
            background: #092f28;
            border-radius: 50%;
            color: #ffffff !important;
            display: flex !important;
            font-size: 0.78rem !important;
            font-weight: 800;
            height: 26px;
            justify-content: center;
            line-height: 1 !important;
            margin: 0 !important;
            width: 26px;
            min-width: 26px;
            flex-shrink: 0;
        }

        .registration-guide-item strong {
            color: #000000;
            display: block;
            font-size: .82rem;
        }

        .registration-guide-text {
            color: #000000;
            display: block;
            font-size: .72rem;
            line-height: 1.45;
            margin-top: 2px;
        }

        @media (max-width: 991px) {
            .registration-page {
                padding: 100px 0 60px;
            }

            .course-summary-card {
                position: static;
            }
        }

        @media (max-width: 575px) {
            .registration-card {
                padding: 18px 14px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .attendee-card {
                transition: none;
            }
        }
    </style>
@endpush

@section('content')
    <section class="registration-page">
        <div class="container">
            <div class="row g-4 align-items-start" data-pin-container>
                <div class="col-lg-8">
                    <div class="registration-card">
                        <a href="{{ route('slug.show', $course->slug) }}" class="registration-back-link">
                            <i class="fa-solid fa-arrow-left mt-1"></i>
                            Quay lại thông tin khóa học
                        </a>
                        <div class="registration-eyebrow">Thông tin đăng ký</div>
                        <h1 class="registration-card-title">Đăng ký {{ $course->title }}</h1>
                        <p class="registration-card-intro mb-0">
                            Hoàn tất thông tin doanh nghiệp và người tham dự. GREECO sẽ liên hệ để xác nhận lịch học, địa điểm và học phí. Các trường có dấu <span class="required-mark">*</span> là bắt buộc.
                        </p>

                        @if ($errors->any())
                            <div class="alert alert-danger mt-4" role="alert">
                                <div class="fw-bold mb-2">Vui lòng kiểm tra lại thông tin:</div>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('course-registration.store', $course) }}" class="mt-4" id="course-registration-form">
                            @csrf
                            <input type="hidden" name="source" value="{{ request('source') === 'qr' ? 'qr' : 'website' }}">
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off" aria-hidden="true" class="d-none">

                            <h2 class="registration-section-title">Thông tin doanh nghiệp</h2>

                            <div class="mb-3">
                                <label for="company_name" class="form-label fw-semibold">
                                    Tên công ty <span class="required-mark">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('company_name') is-invalid @enderror"
                                    id="company_name"
                                    name="company_name"
                                    value="{{ old('company_name') }}"
                                    maxlength="255"
                                    autocomplete="organization"
                                    required
                                >
                                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="company_address" class="form-label fw-semibold">
                                    Địa chỉ <span class="required-mark">*</span>
                                </label>
                                <textarea
                                    class="form-control @error('company_address') is-invalid @enderror"
                                    id="company_address"
                                    name="company_address"
                                    rows="2"
                                    maxlength="1000"
                                    autocomplete="street-address"
                                    required
                                >{{ old('company_address') }}</textarea>
                                @error('company_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="tax_code" class="form-label fw-semibold">
                                        Mã số thuế <span class="required-mark">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control @error('tax_code') is-invalid @enderror"
                                        id="tax_code"
                                        name="tax_code"
                                        value="{{ old('tax_code') }}"
                                        maxlength="50"
                                        required
                                    >
                                    @error('tax_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="attendee_count" class="form-label fw-semibold">
                                        Số người tham dự <span class="required-mark">*</span>
                                    </label>
                                    <select class="form-select" id="attendee_count" name="attendee_count" required>
                                        @for ($number = 1; $number <= 20; $number++)
                                            <option value="{{ $number }}" @selected($initialCount === $number)>
                                                {{ $number }} người
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <h2 class="registration-section-title">Thông tin người tham dự</h2>
                            <p class="text-secondary small">
                                Người đầu tiên sẽ được sử dụng làm người liên hệ chính cho đăng ký này.
                            </p>

                            <div id="attendees-container"></div>

                            <h2 class="registration-section-title">Hóa đơn và nội dung cần tư vấn</h2>

                            <div class="mb-3">
                                <label for="invoice_email" class="form-label fw-semibold">
                                    Email nhận hóa đơn <span class="required-mark">*</span>
                                </label>
                                <input
                                    type="email"
                                    class="form-control @error('invoice_email') is-invalid @enderror"
                                    id="invoice_email"
                                    name="invoice_email"
                                    value="{{ old('invoice_email') }}"
                                    maxlength="255"
                                    autocomplete="email"
                                    required
                                >
                                @error('invoice_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="consultation_question" class="form-label fw-semibold">
                                    Câu hỏi cần giảng viên tư vấn và giải đáp
                                </label>
                                <textarea
                                    class="form-control @error('consultation_question') is-invalid @enderror"
                                    id="consultation_question"
                                    name="consultation_question"
                                    rows="4"
                                    maxlength="5000"
                                >{{ old('consultation_question') }}</textarea>
                                @error('consultation_question')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-check my-4">
                                <input
                                    class="form-check-input @error('consent') is-invalid @enderror"
                                    type="checkbox"
                                    value="1"
                                    id="consent"
                                    name="consent"
                                    @checked(old('consent'))
                                    required
                                >
                                <label class="form-check-label" for="consent">
                                    Tôi đồng ý cung cấp thông tin để GREECO liên hệ và xử lý đăng ký khóa học.
                                </label>
                                @error('consent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-success registration-submit w-100">
                                <i class="fa-solid fa-paper-plane me-2"></i>Gửi đăng ký khóa học
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4">
                    <aside class="course-summary-card" data-pin-panel>
                        <img
                            src="{{ $course->image ? asset('storage/' . $course->image) : asset('assets/images/services/iso-9001-course.png') }}"
                            alt="{{ $course->title }}"
                            class="course-summary-image rounded-3 mb-3"
                            data-scale-reveal
                        >
                        <h2 class="h5 fw-bold text-dark">{{ $course->title }}</h2>
                        @if ($course->description)
                            <p class="text-secondary small">{{ \Illuminate\Support\Str::limit(strip_tags($course->description), 180) }}</p>
                        @endif
                        <div class="d-flex justify-content-between border-top pt-3 mt-3">
                            <span class="text-secondary">Học phí</span>
                            <strong class="text-success">{{ $course->price ?: 'Liên hệ' }}</strong>
                        </div>

                        <div class="registration-guide">
                            <div class="registration-guide-item">
                                <span class="registration-guide-number">1</span>
                                <div>
                                    <strong>Gửi thông tin</strong>
                                    <span class="registration-guide-text">Điền doanh nghiệp và danh sách người tham dự.</span>
                                </div>
                            </div>
                            <div class="registration-guide-item">
                                <span class="registration-guide-number">2</span>
                                <div>
                                    <strong>GREECO xác nhận</strong>
                                    <span class="registration-guide-text">Nhân viên phụ trách liên hệ qua email hoặc điện thoại.</span>
                                </div>
                            </div>
                            <div class="registration-guide-item">
                                <span class="registration-guide-number">3</span>
                                <div>
                                    <strong>Nhận thông tin lớp</strong>
                                    <span class="registration-guide-text">Lịch học, địa điểm và hướng dẫn được gửi sau xác nhận.</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('slug.show', $course->slug) }}" class="btn btn-outline-success w-100 mt-4">
                            <i class="fa-solid fa-arrow-left me-2"></i>Quay lại khóa học
                        </a>
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const attendeeCount = document.getElementById('attendee_count');
            const attendeesContainer = document.getElementById('attendees-container');
            const initialAttendees = @json($initialAttendees);

            function escapeHtml(value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function currentAttendees() {
                return Array.from(attendeesContainer.querySelectorAll('.attendee-card')).map(function (card) {
                    return {
                        full_name: card.querySelector('[data-field="full_name"]').value,
                        job_title: card.querySelector('[data-field="job_title"]').value,
                        email: card.querySelector('[data-field="email"]').value,
                        phone: card.querySelector('[data-field="phone"]').value,
                    };
                });
            }

            function renderAttendees(count, values) {
                attendeesContainer.innerHTML = '';

                for (let index = 0; index < count; index++) {
                    const attendee = values[index] || {};
                    const card = document.createElement('div');
                    card.className = 'attendee-card';
                    card.innerHTML = `
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="attendee-number">${index + 1}</span>
                            <strong class="text-dark">Người tham dự ${index + 1}${index === 0 ? ' · Liên hệ chính' : ''}</strong>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Họ và tên <span class="required-mark">*</span></label>
                                <input type="text" class="form-control" name="attendees[${index}][full_name]"
                                    data-field="full_name" value="${escapeHtml(attendee.full_name)}" maxlength="255" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Chức vụ</label>
                                <input type="text" class="form-control" name="attendees[${index}][job_title]"
                                    data-field="job_title" value="${escapeHtml(attendee.job_title)}" maxlength="255">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email <span class="required-mark">*</span></label>
                                <input type="email" class="form-control" name="attendees[${index}][email]"
                                    data-field="email" value="${escapeHtml(attendee.email)}" maxlength="255" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Số điện thoại <span class="required-mark">*</span></label>
                                <input type="tel" class="form-control" name="attendees[${index}][phone]"
                                    data-field="phone" value="${escapeHtml(attendee.phone)}" maxlength="50" required>
                            </div>
                        </div>
                    `;
                    attendeesContainer.appendChild(card);
                }
            }

            renderAttendees(Number(attendeeCount.value), initialAttendees);

            attendeeCount.addEventListener('change', function () {
                renderAttendees(Number(this.value), currentAttendees());
            });
        });
    </script>
@endpush
