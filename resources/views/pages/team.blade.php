@php
    $seoTitle = __('Hội đồng Khoa học & Đội ngũ Chuyên gia - Viện GREECO');
    $seoDescription = __('Đội ngũ lãnh đạo và chuyên gia đầu ngành trong lĩnh vực phát triển kinh tế xanh, môi trường và hóa học của Viện GREECO.');
    $canonical = website_route('team');
    
    $breadcrumbs = [
        ['name' => __('Trang chủ'), 'url' => website_route('home')],
        ['name' => __('Hội đồng Khoa học'), 'url' => website_route('team')]
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
                            <li class="active">{{ __('Hội đồng Khoa học') }}</li>
                        </ul>
                        <h1 class="text-uppercase">{{ __('Hội đồng Khoa học & Đội ngũ Chuyên gia') }}</h1>
                        <p class="col-lg-10 lead">{{ __('Đội ngũ lãnh đạo và chuyên gia đầu ngành trong lĩnh vực kinh tế xanh và phát triển bền vững.') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4">
                    @foreach($teamMembers as $member)
                    <!-- team begin -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="relative team-member-card">
                            <div class="abs bottom-0 w-100">
                                <div class="d-flex justify-content-between align-items-center rounded-1 m-4 bg-white p-4">
                                    <div>
                                        <h4 class="mb-0">{{ $member->name }}</h4>
                                        <p class="mb-0 team-member-summary">{{ $member->role }}</p>
                                    </div>
                                    <div class="team-member-info" tabindex="0" aria-label="{{ __('Xem chi tiết') }} {{ $member->name }}">
                                        <i class="fa-solid fa-circle-info"></i>
                                        <div class="team-member-detail">
                                            <strong>{{ $member->name }}</strong>
                                            <span>{{ $member->description ?: $member->role }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="team-member-img-wrap rounded-10px overflow-hidden">
                                <img src="{{ $member->image ? asset(str_starts_with($member->image, 'assets') ? $member->image : 'storage/' . $member->image) : asset('assets/images/team/1.webp') }}" width="500" height="600" class="w-100 h-100 object-fit-cover" alt="{{ $member->name }}" loading="lazy" decoding="async">
                            </div>
                        </div>
                    </div>
                    <!-- team end -->
                    @endforeach
                                 
                </div>
            </div>
        </section>
        
    </div>
@endsection
