@php
    $seoTitle = 'Hội đồng Khoa học & Đội ngũ Chuyên gia - Viện GREECO';
    $seoDescription = 'Đội ngũ lãnh đạo và chuyên gia đầu ngành trong lĩnh vực phát triển kinh tế xanh, môi trường và hóa học của Viện GREECO.';
    $canonical = route('team');
    
    $breadcrumbs = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Hội đồng Khoa học', 'url' => route('team')]
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
                            <li class="active">Hội đồng Khoa học</li>
                        </ul>
                        <h1 class="text-uppercase">Hội đồng Khoa học & Đội ngũ Chuyên gia</h1>
                        <p class="col-lg-10 lead">Đội ngũ lãnh đạo và chuyên gia đầu ngành trong lĩnh vực kinh tế xanh và phát triển bền vững.</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4">
                    @foreach($teamMembers as $member)
                    <!-- team begin -->
                    <div class="col-lg-4 col-md-6">
                        <div class="relative team-member-card">
                            <div class="abs bottom-0 w-100">
                                <div class="d-flex justify-content-between align-items-center rounded-1 m-4 bg-white p-4">
                                    <div>
                                        <h4 class="mb-0">{{ $member->name }}</h4>
                                        <p class="mb-0">{{ $member->role }}</p>                                        
                                    </div>
                                    <div class="text-end">
                                        <a href="{{ $member->facebook_url ?? setting('facebook_url', 'https://www.facebook.com/greecoofficial?locale=vi_VN') }}"><i class="fa-brands fa-facebook-f fs-24 id-color bg-light w-40px h-40px pt-2 circle text-center"></i></a>
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
