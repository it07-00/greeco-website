@php
    $seoTitle = __('Lĩnh vực hoạt động - Viện Nghiên cứu và Phát triển Kinh tế Xanh - GREECO');
    $seoDescription = __('Chi tiết các lĩnh vực hoạt động của Viện GREECO bao gồm nghiên cứu khoa học, chuyển giao công nghệ xanh, tư vấn ESG và tín chỉ carbon.');
    $canonical = website_route('services.index');
    
    $breadcrumbs = [
        ['name' => __('Trang chủ'), 'url' => website_route('home')],
        ['name' => __('Lĩnh vực hoạt động'), 'url' => website_route('services.index')]
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
                            <li class="active">{{ __('Lĩnh vực hoạt động') }}</li>
                        </ul>
                        <h1 class="text-uppercase">{{ __('Lĩnh vực hoạt động') }}</h1>
                        <p class="col-lg-10 lead">{{ __('Tiên phong trong nghiên cứu, tư vấn và chuyển giao công nghệ vì một tương lai phát triển bền vững.') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="p-4">
            <div class="container-fluid service-categories-grid">
                <div class="row g-4">
                    @foreach($serviceCategories as $category)
                        <div class="col-lg-4 col-sm-6 wow fadeInRight" data-wow-delay="{{ ($loop->index % 3) * 0.3 }}s">
                            <a class="service-category-link" href="{{ $category['url'] }}" aria-label="{{ __('Xem chi tiết') }} {{ $category['name'] }}">
                                <div class="bg-color text-light rounded-1 overflow-hidden h-100 d-flex flex-column">
                                    <div class="hover relative overflow-hidden text-light text-center">
                                        <img src="{{ $category['image'] }}" width="1024" height="1024" class="hover-scale-1-1 w-100" style="height: auto;" alt="{{ $category['name'] }}" loading="lazy" decoding="async">
                                        <div class="abs w-100 px-4 hover-op-1 z-4 hover-mt-40 abs-centered">
                                            <span class="btn-line">{{ __('Xem chi tiết') }}</span>
                                        </div>
                                        <img src="{{ setting('logo_icon') ? asset('storage/' . setting('logo_icon')) : asset('assets/images/logo-icon.webp') }}" width="166" height="166" class="abs abs-centered w-20" style="height: auto;" alt="GREECO Icon" loading="lazy" decoding="async">
                                        <div class="abs bg-color z-2 top-0 w-100 h-100 hover-op-1"></div>
                                        <div class="abs z-2 bottom-0 mb-3 w-100 text-center hover-op-0">
                                            <h4 class="mb-3">{{ $category['name'] }}</h4>
                                        </div>
                                        <div class="gradient-trans-color-bottom abs w-100 h-40 bottom-0 z-1"></div>
                                    </div>
                                    <div class="p-4 py-3 flex-grow-1">
                                        <p class="mb-0">{{ $category['description'] }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="jarallax pt80 pb80 relative text-light">
            <img src="{{ asset('assets/images/background/3.webp') }}" width="1024" height="1024" class="jarallax-img" alt="{{ __('Tư vấn chuyên sâu GREECO') }}" loading="lazy" decoding="async">
            <div class="container relative z-2">
                <div class="row">
                    <div class="col-lg-8">
                        <img src="{{ setting('logo_icon') ? asset('storage/' . setting('logo_icon')) : asset('assets/images/logo-icon.webp') }}" width="166" height="166" class="w-60px mb-4" style="height: auto;" alt="GREECO logo icon" loading="lazy" decoding="async">
                        <h2 class="text-uppercase mb-4">{{ __('Hành động ngay vì tương lai bền vững – Liên hệ với GREECO để được tư vấn chuyên sâu') }}</h2>
                        <a class="btn-main" href="{{ website_route('contact') }}">{{ __('Liên hệ ngay') }}</a>
                    </div>
                </div>
            </div>
            <div class="de-gradient-edge-bottom dark"></div>
            <div class="de-overlay"></div>
        </section>
        
    </div>
@endsection
