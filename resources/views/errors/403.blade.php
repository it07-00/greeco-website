@php
    $seoTitle = __('Không có quyền truy cập (403) - Viện GREECO');
    $seoDescription = __('Bạn không có quyền truy cập trang này.');
    $indexable = false;
@endphp

@extends('layouts.app')

@section('content')
    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <section id="section-403" class="error-page-section text-light relative jarallax">
            <img src="{{ asset('assets/images/background/8.webp') }}" width="1024" height="1024" class="jarallax-img" alt="403 Background" loading="eager" decoding="async" fetchpriority="high">
            <div class="error-page-overlay de-overlay"></div>
            <div class="container relative z-index-1000">
                <div class="row align-items-center justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1 class="error-page-code mb-0 id-color">403</h1>
                        <h2 class="error-page-title mb-3 text-uppercase">{{ __('Không có quyền truy cập') }}</h2>
                        <p class="lead mb-4">{{ __('Bạn không có quyền truy cập vào trang này. Nếu bạn cho rằng đây là nhầm lẫn, vui lòng liên hệ quản trị viên.') }}</p>
                        <a href="{{ website_route('home') }}" class="btn-main">{{ __('Quay về trang chủ') }}</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
