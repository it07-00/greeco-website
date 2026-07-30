@php
    $seoTitle = 'Phiên đã hết hạn (419) - Viện GREECO';
    $seoDescription = 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang.';
    $indexable = false;
@endphp

@extends('layouts.app')

@section('content')
    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <section id="section-419" class="error-page-section text-light relative jarallax">
            <img src="{{ asset('assets/images/background/8.webp') }}" width="1024" height="1024" class="jarallax-img" alt="419 Background" loading="eager" decoding="async" fetchpriority="high">
            <div class="error-page-overlay de-overlay"></div>
            <div class="container relative z-index-1000">
                <div class="row align-items-center justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1 class="error-page-code mb-0 id-color">419</h1>
                        <h2 class="error-page-title mb-3 text-uppercase">Phiên đã hết hạn</h2>
                        <p class="lead mb-4">Phiên làm việc của bạn đã hết hạn do không hoạt động quá lâu. Vui lòng tải lại trang và thử lại.</p>
                        <a href="javascript:location.reload()" class="btn-main">Tải lại trang</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
