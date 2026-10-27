@php
    $seoTitle = $service->meta_title ?: $service->name . ' | ' . config('app.name');
    $seoDescription = $service->meta_description ?: $service->short_description;
    $canonical = $service->canonical_url ?: website_route('slug.show', $service->slug);
    $ogImage = $service->og_image
        ? asset('storage/' . $service->og_image)
        : asset('assets/images/og-default.jpg');
    $indexable = $service->is_indexable;

    $breadcrumbs = [
        ['name' => __('Trang chủ'), 'url' => website_route('home')],
        ['name' => __('Lĩnh vực'), 'url' => website_route('services.index')],
        ['name' => $service->name, 'url' => website_route('slug.show', $service->slug)]
    ];
@endphp

@extends('layouts.app')


@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />
    <x-schema.service 
        :name="$service->name" 
        :description="$service->short_description" 
        :url="$canonical" 
    />

    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <section id="subheader" class="relative">
            <div class="container relative z-index-1000">
                <div class="row">
                    <div class="col-lg-8">
                        <ul class="crumb">
                            <li><a href="{{ website_route('home') }}">{{ __('Trang chủ') }}</a></li>
                            <li><a href="{{ website_route('services.index') }}">{{ __('Lĩnh vực') }}</a></li>
                            <li class="active">{{ $service->name }}</li>
                        </ul>
                        <h1 class="text-uppercase">{{ $service->name }}</h1>
                        <p class="col-lg-10">{{ $service->short_description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4 gx-5">
                    <!-- Sidebar -->
                    <div class="col-lg-3">
                        <div class="me-lg-3">
                            <div class="bg-color text-light p-3 px-4 rounded-10px mb-3">
                                <h4 class="mb-0 text-white">{{ __('Dịch vụ liên quan') }}</h4>
                            </div>
                            @if($relatedServices->isNotEmpty())
                                @foreach($relatedServices as $relService)
                                    <a href="{{ website_route('slug.show', $relService->slug) }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3 service-sidebar-link text-dark">
                                        <h4 class="mb-0 fs-16">{{ $relService->name }}</h4>
                                    </a>
                                @endforeach
                            @else
                                <p class="text-muted ps-3">{{ __('Không có dịch vụ liên quan khác.') }}</p>
                            @endif
                            
                            <a href="{{ website_route('services.index') }}" class="btn-main d-block text-center mt-4">
                                <i class="fa fa-arrow-left me-2"></i>{{ __('Tất cả dịch vụ') }}
                            </a>
                        </div>
                    </div>

                    <!-- Main Content Area -->
                    <div class="col-lg-9">
                        <div class="service-content">
                            {!! $service->content !!}
                        </div>

                        <div class="border-top pt-3 mt-4 d-flex align-items-center gap-3 flex-wrap">
                            <span class="fw-bold text-dark"><i class="fa-solid fa-share-nodes me-2"></i>{{ __('Chia sẻ dịch vụ:') }}</span>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($canonical) }}" target="_blank" rel="noopener noreferrer" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center p-0 share-btn share-facebook" title="{{ __('Chia sẻ lên Facebook') }}">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode($canonical) }}&text={{ urlencode($service->name) }}" target="_blank" rel="noopener noreferrer" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center p-0 share-btn share-twitter" title="{{ __('Chia sẻ lên Twitter / X') }}">
                                    <i class="fa-brands fa-x-twitter"></i>
                                </a>
                                <a href="https://zalo.me/s/share?url={{ urlencode($canonical) }}" target="_blank" rel="noopener noreferrer" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center p-0 share-btn share-zalo" title="{{ __('Chia sẻ lên Zalo') }}">
                                    <span class="zalo-share-icon">Z</span>
                                </a>
                                <button class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center p-0 share-btn share-copy position-relative" id="copy-share-link" data-url="{{ $canonical }}" title="{{ __('Sao chép liên kết') }}">
                                    <i class="fa-solid fa-link"></i>
                                    <span class="copy-tooltip" id="copy-tooltip">{{ __('Đã sao chép!') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ versioned_asset('assets/js/post-detail.js') }}" defer></script>
@endpush
