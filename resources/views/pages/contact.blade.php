@php
    $seoTitle = 'Liên hệ - Viện Nghiên cứu và Phát triển Kinh tế Xanh - GREECO';
    $seoDescription = 'Thông tin liên hệ chính thức của Viện Nghiên cứu và Phát triển Kinh tế Xanh - GREECO. Địa chỉ, số điện thoại, email và bản đồ hướng dẫn.';
    $canonical = route('contact');

    $breadcrumbs = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Liên hệ', 'url' => route('contact')]
    ];
@endphp

@extends('layouts.app')

@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />

    <div class="no-bottom no-top" id="content">
        <div id="top"></div>
        <section id="subheader" class="relative">
            <div class="container relative z-index-1000">
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="crumb">
                            <li><a href="{{ route('home') }}">Trang chủ</a></li>
                            <li class="active">Liên hệ</li>
                        </ul>
                        <h1 class="text-uppercase">Liên hệ</h1>
                        <p class="col-lg-10 lead">Kết nối với GREECO để cùng kiến tạo tương lai xanh bền vững.</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center mb-5">
                        <h3 class="wow fadeInUp text-dark fw-bold">Gửi tin nhắn cho chúng tôi</h3>
                        <p class="lead col-lg-8 mx-auto text-muted">Nếu bạn có bất kỳ câu hỏi, đề xuất hợp tác hoặc yêu cầu tư vấn nào, xin vui lòng điền vào biểu mẫu dưới đây. Đội ngũ chuyên gia của chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>
                    </div>
                </div>

                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-6">
                        <div class="contact-info-card h-100">
                            <div class="row g-0 h-100">
                                <div class="col-sm-6 d-flex flex-column">
                                    <div class="contact-image-col flex-grow-1"
                                        style="background-image: url({{ setting('contact_image') ? asset('storage/' . setting('contact_image')) : asset('assets/images/blog/1.webp') }});">
                                    </div>
                                </div>
                                <div class="col-sm-6 relative">
                                    <div class="p-30 d-flex flex-column justify-content-center h-100">
                                        <!-- Work Hours -->
                                        <div class="contact-item">
                                            <div class="contact-icon-badge">
                                                <i class="fa-solid fa-clock"></i>
                                            </div>
                                            <div class="contact-text-group">
                                                <span class="contact-label">Thời gian làm việc</span>
                                                <span class="contact-value">{{ setting('work_hours', 'Thứ 2 - Thứ 7: 08:00 - 17:00') }}</span>
                                            </div>
                                        </div>

                                        <!-- Address -->
                                        <div class="contact-item">
                                            <div class="contact-icon-badge">
                                                <i class="fa-solid fa-location-dot"></i>
                                            </div>
                                            <div class="contact-text-group">
                                                <span class="contact-label">Địa chỉ văn phòng</span>
                                                <span class="contact-value">{{ setting('address', '150 Đường 38-CL, Phường Cát Lái, TP. HCM') }}</span>
                                            </div>
                                        </div>

                                        <!-- Phone -->
                                        <div class="contact-item">
                                            <div class="contact-icon-badge">
                                                <i class="fa-solid fa-phone"></i>
                                            </div>
                                            <div class="contact-text-group">
                                                <span class="contact-label">Điện thoại liên hệ</span>
                                                @if(setting('phone_2'))
                                                    <a href="tel:{{ str_replace(' ', '', setting('phone_2')) }}" class="contact-value text-decoration-none hover-underline text-white d-block hotline-link">
                                                        <span>{{ setting('phone_2') }}</span>
                                                        @if(setting('phone_2_name'))
                                                            <span class="hotline-person"> - {{ setting('phone_2_name') }}</span>
                                                        @endif
                                                    </a>
                                                @endif
                                                <a href="tel:{{ str_replace(' ', '', setting('phone', '0936996390')) }}" class="contact-value text-decoration-none hover-underline text-white d-block {{ setting('phone_2') ? 'mt-1' : '' }} hotline-link">
                                                    <span>{{ setting('phone', '09369 96390') }}</span>
                                                    @if(setting('phone_name'))
                                                        <span class="hotline-person"> - {{ setting('phone_name') }}</span>
                                                    @endif
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="contact-item">
                                            <div class="contact-icon-badge">
                                                <i class="fa-solid fa-envelope"></i>
                                            </div>
                                            <div class="contact-text-group">
                                                <span class="contact-label">Email liên hệ</span>
                                                <a href="mailto:{{ setting('email', 'info@greeco.vn') }}" class="contact-value text-decoration-none hover-underline text-white">{{ setting('email', 'info@greeco.vn') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex flex-column justify-content-center">
                        @if(session('success'))
                            <div class="alert alert-success mb-4">
                                <i class="icofont-check-circled me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger mb-4">
                                <i class="icofont-warning-alt me-2"></i><strong>Có lỗi xảy ra:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="contact-form-wrapper h-100 d-flex flex-column justify-content-center">
                            <form name="contactForm" id="contact_form" class="position-relative z1000" method="post"
                                action="{{ route('contact.store') }}">
                                @csrf
                                <div class="field-set mb-3">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Họ và tên"
                                        value="{{ old('name') }}" required>
                                </div>

                                <div class="field-set mb-3">
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Địa chỉ Email"
                                        value="{{ old('email') }}">
                                </div>

                                <div class="field-set mb-3">
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Số điện thoại"
                                        value="{{ old('phone') }}">
                                </div>

                                <div class="field-set mb-4">
                                    <textarea name="message" id="message" class="form-control"
                                        placeholder="Nội dung tin nhắn / Yêu cầu tư vấn"
                                        required>{{ old('message', request('message')) }}</textarea>
                                </div>

                                <div id="submit" class="text-end">
                                    <input type="submit" id="send_message" value="Gửi tin nhắn" class="btn-main w-100 w-sm-auto">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Section -->
        <section class="no-top pt-0">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="contact-map-container">
                            <iframe 
                                src="https://maps.google.com/maps?q={{ urlencode(setting('address', '150 Đường 38-CL, Phường Cát Lái, TP. HCM')) }}&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
