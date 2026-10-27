@php
    $seoTitle = $project->meta_title ?: $project->title . ' | ' . config('app.name');
    $seoDescription = $project->meta_description ?: $project->excerpt;
    $canonical = $project->canonical_url ?: website_route('slug.show', $project->slug);
    $ogImage = $project->og_image
        ? asset('storage/' . $project->og_image)
        : asset('assets/images/og-default.jpg');
    $indexable = $project->is_indexable;
    
    $breadcrumbs = [
        ['name' => __('Trang chủ'), 'url' => website_route('home')],
        ['name' => __('Dự án & Nghiên cứu'), 'url' => website_route('projects.index')],
        ['name' => $project->title, 'url' => $canonical]
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
                    <div class="col-lg-8">
                        <ul class="crumb">
                            <li><a href="{{ website_route('home') }}">{{ __('Trang chủ') }}</a></li>
                            <li><a href="{{ website_route('projects.index') }}">{{ __('Dự án & Nghiên cứu') }}</a></li>
                            <li class="active">{{ __('Chi tiết Dự án') }}</li>
                        </ul>
                        <h1 class="text-uppercase">{{ $project->title }}</h1>
                        <p class="col-lg-10 lead">{{ $project->excerpt }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Project Hero Image -->
        @if($project->thumbnail)
            <div class="container mb-5">
                <div class="row">
                    <div class="col-12">
                        <img src="{{ asset('storage/' . $project->thumbnail) }}" class="w-100 rounded-10px" alt="{{ $project->title }}">
                    </div>
                </div>
            </div>
        @endif
        
        <section class="pt-0">
            <div class="container">
                <div class="row g-4">
                    <!-- Main Content -->
                    <div class="col-lg-8">
                        <div class="me-lg-3">
                            <div class="project-intro-box mb-4">
                                <h5 class="text-uppercase mb-3 id-color fw-700"><i class="fa-solid fa-leaf me-2"></i>{{ __('Tóm tắt dự án') }}</h5>
                                <p>{{ $project->excerpt }}</p>
                            </div>

                            <div class="project-content mt-4">
                                {!! $project->content !!}
                            </div>

                            <div class="border-top pt-3 mt-4 d-flex align-items-center gap-3 flex-wrap">
                                <span class="fw-bold text-dark"><i class="fa-solid fa-share-nodes me-2"></i>{{ __('Chia sẻ dự án:') }}</span>
                                <div class="d-flex gap-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($canonical) }}" target="_blank" rel="noopener noreferrer" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center p-0 share-btn share-facebook" title="{{ __('Chia sẻ lên Facebook') }}">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($canonical) }}&text={{ urlencode($project->title) }}" target="_blank" rel="noopener noreferrer" class="btn btn-light rounded-circle d-inline-flex align-items-center justify-content-center p-0 share-btn share-twitter" title="{{ __('Chia sẻ lên Twitter / X') }}">
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

                    <!-- Sidebar Details -->
                    <div class="col-lg-4 text-dark">
                        <div class="project-details-box">
                            <h5 class="text-uppercase mb-4 id-color fw-700">
                                <i class="fa-solid fa-circle-info me-2"></i>{{ __('Chi tiết dự án') }}
                            </h5>
                            @if($project->client_name)
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fa-solid fa-handshake id-color-2"></i> {{ __('Khách hàng / Đối tác') }}
                                    </div>
                                    <div class="detail-value">{{ $project->client_name }}</div>
                                </div>
                            @endif
                            @if($project->location)
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fa-solid fa-location-dot id-color-2"></i> {{ __('Địa điểm') }}
                                    </div>
                                    <div class="detail-value">{{ $project->location }}</div>
                                </div>
                            @endif
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fa-solid fa-calendar-days id-color-2"></i> {{ __('Bắt đầu') }}
                                </div>
                                <div class="detail-value">{{ $project->started_at ? \Carbon\Carbon::parse($project->started_at)->format('d/m/Y') : __('Chưa xác định') }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fa-solid fa-calendar-check id-color-2"></i> {{ __('Hoàn thành') }}
                                </div>
                                <div class="detail-value">{{ $project->completed_at ? \Carbon\Carbon::parse($project->completed_at)->format('d/m/Y') : __('Đang triển khai') }}</div>
                            </div>
                        </div>
                        
                        <a href="{{ website_route('projects.index') }}" class="btn-main w-100 text-center">
                            <i class="fa fa-arrow-left me-2"></i>{{ __('Xem tất cả dự án') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ versioned_asset('assets/js/post-detail.js') }}" defer></script>
@endpush
