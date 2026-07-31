@php
    $seoTitle = 'Đăng ký thành công - GREECO';
    $seoDescription = 'GREECO đã nhận được thông tin đăng ký khóa học.';
    $canonical = route('course-registration.success', $registration);
    $indexable = false;
@endphp

@extends('layouts.app')

@push('styles')
    <style>
        .registration-success-page {
            align-items: center;
            background: #f4f8f7;
            display: flex;
            min-height: 75vh;
            padding: 140px 0 80px;
        }

        .registration-success-card {
            background: #fff;
            border: 1px solid #dfe9e6;
            border-radius: 20px;
            box-shadow: 0 16px 45px rgba(9, 32, 28, .08);
            margin: auto;
            max-width: 720px;
            padding: 48px;
            text-align: center;
        }

        .registration-success-card p {
            color: #000000 !important;
        }

        .success-icon {
            align-items: center;
            background: #e4f7f1;
            border-radius: 50%;
            color: #008f79;
            display: inline-flex;
            font-size: 2rem;
            height: 78px;
            justify-content: center;
            width: 78px;
        }

        .registration-code {
            background: #f3f7f6;
            border: 1px dashed #9abcb4;
            border-radius: 12px;
            color: #153b35;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: .06em;
            padding: 14px;
        }

        @media (max-width: 575px) {
            .registration-success-card {
                padding: 34px 20px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="registration-success-page">
        <div class="container">
            <div class="registration-success-card">
                <span class="success-icon">
                    <i class="fa-solid fa-check"></i>
                </span>
                <h1 class="h2 fw-bold text-dark mt-4">Đăng ký thành công</h1>
                <p class="text-secondary">
                    GREECO đã nhận được đăng ký của <strong>{{ $registration->company_name }}</strong>
                    cho khóa học <strong>{{ $registration->course->title }}</strong>.
                </p>

                <p class="small text-secondary mb-2">Mã đăng ký của bạn</p>
                <div class="registration-code mb-4">{{ $registration->code }}</div>

                <p class="text-secondary">
                    Ban tổ chức sẽ liên hệ qua số điện thoại
                    <strong>{{ $registration->contact_phone }}</strong>
                    hoặc email <strong>{{ $registration->contact_email }}</strong> để xác nhận.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center mt-4">
                    <a href="{{ route('slug.show', $registration->course->slug) }}" class="btn btn-success px-4">
                        Xem lại khóa học
                    </a>
                    <a href="{{ route('services.dao-tao') }}" class="btn btn-outline-success px-4">
                        Xem các khóa học khác
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
