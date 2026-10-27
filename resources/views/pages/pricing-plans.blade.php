@php
    $seoTitle = __('Gói Dịch vụ Tư vấn - Viện GREECO');
    $seoDescription = __('Thông tin các gói dịch vụ tư vấn kiểm kê khí nhà kính, báo cáo ESG và chuyển đổi xanh cho doanh nghiệp.');
    $canonical = website_route('pricing-plans');
    
    $breadcrumbs = [
        ['name' => __('Trang chủ'), 'url' => website_route('home')],
        ['name' => __('Gói dịch vụ'), 'url' => website_route('pricing-plans')]
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
                            <li><a href="{{ website_route('home') }}">{{ __('Trang chủ') }}</a></li>
                            <li class="active">{{ __('Gói dịch vụ') }}</li>
                        </ul>
                        <h1 class="text-uppercase">{{ __('Các Gói Dịch vụ') }}</h1>
                        <p class="col-lg-10 lead">{{ __('Các gói dịch vụ tư vấn đào tạo ESG và quản lý carbon của Viện GREECO.') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 col-sm-6 wow fadeInRight" data-wow-delay=".0s" >
                        <div class="relative bg-light p-30 pb-70 rounded-1 h-100">
                            <div class="text-center mb-3">
                                <img src="{{ asset('assets/images/logo-icon-color.webp') }}" width="166" height="214" class="w-80px mb-3" alt="GREECO Logo icon" loading="lazy" decoding="async">
                                <h4>{{ __('Gói Đào tạo ESG') }}</h4>
                                <div class="spacer-30"></div>
                                <div class="fw-bold">
                                    <span class="fs-64 fw-bold text-dark">{{ __('Liên hệ') }}</span>
                                </div>
                                <div class="spacer-20"></div>
                            </div>

                            <h5 class="mb-2">{{ __('Quyền lợi gói dịch vụ') }}</h5>
                            <ul class="ul-style-2">
                                <li>{{ __('Đào tạo nhận thức ESG cơ bản cho nhân sự') }}</li>
                                <li>{{ __('Hướng dẫn lập báo cáo phát triển bền vững') }}</li>
                                <li>{{ __('Cung cấp bộ biểu mẫu chuẩn quốc tế GRI') }}</li>
                                <li>{{ __('Tập huấn kỹ thuật kiểm kê khí nhà kính') }}</li>
                                <li>{{ __('Đánh giá hiện trạng phát thải tại doanh nghiệp') }}</li>
                                <li>{{ __('Hỗ trợ xây dựng chính sách xanh nội bộ') }}</li>
                            </ul>
                            <div class="spacer-20"></div>

                            <div class="abs abs-center w-100 bottom-0 mb-5 px-5">
                                <a class="btn-main w-100" href="{{ website_route('contact') }}">{{ __('Đăng ký tư vấn') }}</a>
                            </div>
                            <div class="spacer-20"></div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 wow fadeInRight" data-wow-delay=".3s" >
                        <div class="relative overflow-hidden bg-light p-30 pb-70 rounded-1 h-100 jarallax text-light">
                            <img src="{{ asset('assets/images/misc/5.webp') }}" width="1024" height="1024" class="jarallax-img" alt="{{ __('Gói đánh giá carbon GREECO') }}" loading="lazy" decoding="async">
                            <div class="de-overlay op-5"></div>
                            <div class="text-center mb-3 relative z-2">
                                <img src="{{ asset('assets/images/logo-icon-color.webp') }}" width="166" height="214" class="w-80px mb-3" alt="GREECO Logo icon" loading="lazy" decoding="async">
                                <h4>{{ __('Gói Đánh giá Carbon') }}</h4>
                                <div class="spacer-30"></div>
                                <div class="fw-bold">
                                    <span class="fs-64 fw-bold">{{ __('Liên hệ') }}</span>
                                </div>
                                <div class="spacer-20"></div>
                            </div>

                            <h5 class="mb-2 relative z-2">{{ __('Quyền lợi gói dịch vụ') }}</h5>
                            <ul class="ul-style-2 relative z-2">
                                <li>{{ __('Bao gồm tất cả quyền lợi gói Đào tạo ESG') }}</li>
                                <li>{{ __('Đo đạc khí nhà kính Scope 1 & Scope 2') }}</li>
                                <li>{{ __('Xây dựng chiến lược giảm phát thải carbon') }}</li>
                                <li>{{ __('Tư vấn thiết lập hệ thống ISO 14064') }}</li>
                                <li>{{ __('Thiết kế lộ trình trung hòa carbon') }}</li>
                                <li>{{ __('Báo cáo đánh giá chi tiết định kỳ hàng năm') }}</li>
                            </ul>
                            <div class="spacer-20"></div>

                            <div class="abs abs-center w-100 bottom-0 mb-5 px-5 relative z-2">
                                <a class="btn-main w-100" href="{{ website_route('contact') }}">{{ __('Đăng ký tư vấn') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-6 wow fadeInRight" data-wow-delay=".6s" >
                        <div class="relative bg-light p-30 pb-70 rounded-1 h-100">
                            <div class="text-center mb-3">
                                <img src="{{ asset('assets/images/logo-icon-color.webp') }}" width="166" height="214" class="w-80px mb-3" alt="GREECO Logo icon" loading="lazy" decoding="async">
                                <h4>{{ __('Gói Chuyển giao Xanh') }}</h4>
                                <div class="spacer-30"></div>
                                <div class="fw-bold">
                                    <span class="fs-64 fw-bold text-dark">{{ __('Liên hệ') }}</span>
                                </div>
                                <div class="spacer-20"></div>
                            </div>

                            <h5 class="mb-2">{{ __('Quyền lợi gói dịch vụ') }}</h5>
                            <ul class="ul-style-2">
                                <li>{{ __('Bao gồm tất cả quyền lợi gói Đánh giá Carbon') }}</li>
                                <li>{{ __('Tư vấn chuyển dịch mô hình kinh tế tuần hoàn') }}</li>
                                <li>{{ __('Hỗ trợ thủ tục đăng ký tín chỉ carbon') }}</li>
                                <li>{{ __('Kết nối đối tác tài chính xanh quốc tế') }}</li>
                                <li>{{ __('Chuyển giao công nghệ tiết kiệm năng lượng') }}</li>
                                <li>{{ __('Chiến lược hành động Net Zero toàn diện') }}</li>
                            </ul>
                            <div class="spacer-20"></div>

                            <div class="abs abs-center w-100 bottom-0 mb-5 px-5">
                                <a class="btn-main w-100" href="{{ website_route('contact') }}">{{ __('Đăng ký tư vấn') }}</a>
                            </div>
                            <div class="spacer-20"></div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
        
    </div>
@endsection
