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
                    <!-- team begin -->
                    <div class="col-lg-4 col-md-6">
                        <div class="relative">
                            <div class="abs bottom-0 w-100">
                                <div class="d-flex justify-content-between align-items-center rounded-1 m-4 bg-white p-4">
                                    <div>
                                        <h4 class="mb-0">GS. TS. Lâm Hoài Nam</h4>
                                        <p class="mb-0">Chủ tịch Hội đồng Khoa học</p>                                        
                                    </div>
                                    <div class="text-end">
                                        <a href="{{ setting('facebook_url', 'https://www.facebook.com/greecoofficial?locale=vi_VN') }}"><i class="fa-brands fa-facebook-f fs-24 id-color bg-light w-40px h-40px pt-2 circle text-center"></i></a>
                                    </div>
                                </div>
                            </div>
                            <img src="{{ asset('assets/images/team/1.webp') }}" width="500" height="600" class="w-100 rounded-10px" alt="GS. TS. Lâm Hoài Nam" loading="lazy" decoding="async">
                        </div>
                    </div>
                    <!-- team end -->

                    <!-- team begin -->
                    <div class="col-lg-4 col-md-6">
                        <div class="relative">
                            <div class="abs bottom-0 w-100">
                                <div class="d-flex justify-content-between align-items-center rounded-1 m-4 bg-white p-4">
                                    <div>
                                        <h4 class="mb-0">PGS. TS. Trần Thị Mai</h4>
                                        <p class="mb-0">Phó Viện trưởng / Chuyên gia ESG</p>                                        
                                    </div>
                                    <div class="text-end">
                                        <a href="{{ setting('facebook_url', 'https://www.facebook.com/greecoofficial?locale=vi_VN') }}"><i class="fa-brands fa-facebook-f fs-24 id-color bg-light w-40px h-40px pt-2 circle text-center"></i></a>
                                    </div>
                                </div>
                            </div>
                            <img src="{{ asset('assets/images/team/2.webp') }}" width="500" height="600" class="w-100 rounded-10px" alt="PGS. TS. Trần Thị Mai" loading="lazy" decoding="async">
                        </div>
                    </div>
                    <!-- team end -->

                    <!-- team begin -->
                    <div class="col-lg-4 col-md-6">
                        <div class="relative">
                            <div class="abs bottom-0 w-100">
                                <div class="d-flex justify-content-between align-items-center rounded-1 m-4 bg-white p-4">
                                    <div>
                                        <h4 class="mb-0">TS. Phạm Hoàng Nam</h4>
                                        <p class="mb-0">Trưởng phòng Nghiên cứu Công nghệ Xanh</p>                                        
                                    </div>
                                    <div class="text-end">
                                        <a href="{{ setting('facebook_url', 'https://www.facebook.com/greecoofficial?locale=vi_VN') }}"><i class="fa-brands fa-facebook-f fs-24 id-color bg-light w-40px h-40px pt-2 circle text-center"></i></a>
                                    </div>
                                </div>
                            </div>
                            <img src="{{ asset('assets/images/team/3.webp') }}" width="500" height="600" class="w-100 rounded-10px" alt="TS. Phạm Hoàng Nam" loading="lazy" decoding="async">
                        </div>
                    </div>
                    <!-- team end -->

                    <!-- team begin -->
                    <div class="col-lg-4 col-md-6">
                        <div class="relative">
                            <div class="abs bottom-0 w-100">
                                <div class="d-flex justify-content-between align-items-center rounded-1 m-4 bg-white p-4">
                                    <div>
                                        <h4 class="mb-0">ThS. Lê Thị Thu Hương</h4>
                                        <p class="mb-0">Chuyên gia Đánh giá Carbon & Khí hậu</p>                                        
                                    </div>
                                    <div class="text-end">
                                        <a href="{{ setting('facebook_url', 'https://www.facebook.com/greecoofficial?locale=vi_VN') }}"><i class="fa-brands fa-facebook-f fs-24 id-color bg-light w-40px h-40px pt-2 circle text-center"></i></a>
                                    </div>
                                </div>
                            </div>
                            <img src="{{ asset('assets/images/team/4.webp') }}" width="500" height="600" class="w-100 rounded-10px" alt="ThS. Lê Thị Thu Hương" loading="lazy" decoding="async">
                        </div>
                    </div>
                    <!-- team end -->

                    <!-- team begin -->
                    <div class="col-lg-4 col-md-6">
                        <div class="relative">
                            <div class="abs bottom-0 w-100">
                                <div class="d-flex justify-content-between align-items-center rounded-1 m-4 bg-white p-4">
                                    <div>
                                        <h4 class="mb-0">ThS. Nguyễn Minh Triết</h4>
                                        <p class="mb-0">Chuyên viên Chuyển giao Công nghệ</p>                                        
                                    </div>
                                    <div class="text-end">
                                        <a href="{{ setting('facebook_url', 'https://www.facebook.com/greecoofficial?locale=vi_VN') }}"><i class="fa-brands fa-facebook-f fs-24 id-color bg-light w-40px h-40px pt-2 circle text-center"></i></a>
                                    </div>
                                </div>
                            </div>
                            <img src="{{ asset('assets/images/team/5.webp') }}" width="500" height="600" class="w-100 rounded-10px" alt="ThS. Nguyễn Minh Triết" loading="lazy" decoding="async">
                        </div>
                    </div>
                    <!-- team end -->

                    <!-- team begin -->
                    <div class="col-lg-4 col-md-6">
                        <div class="relative">
                            <div class="abs bottom-0 w-100">
                                <div class="d-flex justify-content-between align-items-center rounded-1 m-4 bg-white p-4">
                                    <div>
                                        <h4 class="mb-0">Bà Hoàng Anh Thư</h4>
                                        <p class="mb-0">Thư ký Hội đồng Chuyên gia</p>                                        
                                    </div>
                                    <div class="text-end">
                                        <a href="{{ setting('facebook_url', 'https://www.facebook.com/greecoofficial?locale=vi_VN') }}"><i class="fa-brands fa-facebook-f fs-24 id-color bg-light w-40px h-40px pt-2 circle text-center"></i></a>
                                    </div>
                                </div>
                            </div>
                            <img src="{{ asset('assets/images/team/6.webp') }}" width="500" height="600" class="w-100 rounded-10px" alt="Bà Hoàng Anh Thư" loading="lazy" decoding="async">
                        </div>
                    </div>
                    <!-- team end -->
                                 
                </div>
            </div>
        </section>
        
    </div>
@endsection
