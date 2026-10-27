@php
    $seoTitle = __('Không tìm thấy trang (404) - Viện GREECO');
    $seoDescription = __('Trang bạn yêu cầu không tồn tại hoặc đã được di chuyển.');
    $indexable = false;
@endphp

@extends('layouts.app')

@section('content')
    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <section id="section-404" class="error-page-section text-light relative jarallax">
            <img src="{{ asset('assets/images/background/8.webp') }}" width="1024" height="1024" class="jarallax-img" alt="404 Background" loading="eager" decoding="async" fetchpriority="high">
            <div class="error-page-overlay de-overlay"></div>
            <div class="container relative z-index-1000">
                <div class="row align-items-center justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1 class="error-page-code mb-0 id-color">404</h1>
                        <h2 class="error-page-title mb-3 text-uppercase">{{ __('Không tìm thấy trang') }}</h2>
                        <p class="lead mb-4">{{ __('Xin lỗi, trang bạn đang tìm kiếm không tồn tại, đã bị xóa hoặc đã được di chuyển sang một địa chỉ khác.') }}</p>
                        <a href="{{ website_route('home') }}" class="btn-main">{{ __('Quay về trang chủ') }}</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
