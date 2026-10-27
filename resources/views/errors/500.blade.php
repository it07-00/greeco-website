@php
    $seoTitle = __('Lỗi hệ thống (500) - Viện GREECO');
    $seoDescription = __('Đã xảy ra lỗi kỹ thuật. Vui lòng thử lại sau hoặc liên hệ bộ phận hỗ trợ.');
    $indexable = false;
@endphp

@extends('layouts.app')

@section('content')
    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <section id="section-500" class="error-page-section text-light relative jarallax">
            <img src="{{ asset('assets/images/background/8.webp') }}" width="1024" height="1024" class="jarallax-img" alt="500 Background" loading="eager" decoding="async" fetchpriority="high">
            <div class="error-page-overlay de-overlay"></div>
            <div class="container relative z-index-1000">
                <div class="row align-items-center justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1 class="error-page-code mb-0 id-color">500</h1>
                        <h2 class="error-page-title mb-3 text-uppercase">{{ __('Đã xảy ra lỗi') }}</h2>
                        <p class="lead mb-4">{{ __('Xin lỗi, hệ thống đang gặp sự cố kỹ thuật. Đội ngũ kỹ thuật đã được thông báo và đang xử lý. Vui lòng thử lại sau ít phút.') }}</p>
                        <a href="{{ website_route('home') }}" class="btn-main">{{ __('Quay về trang chủ') }}</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
