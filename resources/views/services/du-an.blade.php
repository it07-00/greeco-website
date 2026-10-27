@php
    $seoTitle = __('Dự án Tăng trưởng Xanh & Bền vững - Viện GREECO');
    $seoDescription = __('Các dự án sinh thái của Viện GREECO bao gồm đăng ký tín chỉ carbon, tín chỉ nhựa, ứng dụng biochar và giải pháp tuần hoàn rác thải.');
    $canonical = website_route('services.du-an');
    
    $breadcrumbs = [
        ['name' => __('Trang chủ'), 'url' => website_route('home')],
        ['name' => __('Lĩnh vực'), 'url' => website_route('services.index')],
        ['name' => __('Phát triển Dự án'), 'url' => website_route('services.du-an')]
    ];
@endphp

@extends('layouts.app')


@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />
    <x-schema.service 
        name="Phát triển Dự án" 
        description="Triển khai các dự án tăng trưởng xanh, thiết lập tín chỉ carbon, tín chỉ nhựa và các giải pháp nông nghiệp sinh thái tuần hoàn." 
        :url="$canonical" 
    />

    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <section id="subheader" class="relative">
            <div class="container relative z-index-1000">
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="crumb">
                            <li><a href="{{ website_route('home') }}">{{ __('Trang chủ') }}</a></li>
                            <li><a href="{{ website_route('services.index') }}">{{ __('Lĩnh vực') }}</a></li>
                            <li class="active">{{ __('Phát triển Dự án') }}</li>
                        </ul>
                        <h1 class="text-uppercase">{{ __('Phát triển Dự án') }}</h1>
                        <p class="col-lg-10">{{ __('Triển khai các dự án tăng trưởng xanh, thiết lập tín chỉ carbon, tín chỉ nhựa và các giải pháp nông nghiệp sinh thái tuần hoàn.') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4 gx-5">
                    <div class="col-lg-3">
                        <div class="me-lg-3">
                            <a href="{{ website_route('services.dao-tao') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3"><h4 class="mb-0">{{ __('Đào tạo & Bồi dưỡng') }}</h4></a>
                            <a href="{{ website_route('services.tu-van') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3"><h4 class="mb-0">{{ __('Dịch vụ Tư vấn') }}</h4></a>
                            <a href="{{ website_route('services.du-an') }}" class="bg-color text-light d-block p-3 px-4 rounded-10px mb-3 relative"><h4 class="mb-0">{{ __('Phát triển Dự án') }}</h4><i class="icofont-long-arrow-right absolute abs-middle fs-24 end-20px"></i></a>
                            <a href="{{ website_route('services.nghien-cuu') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3"><h4 class="mb-0">{{ __('Nghiên cứu và Chuyển giao Công nghệ') }}</h4></a>
                            <a href="{{ website_route('services.hoi-thao') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3"><h4 class="mb-0">{{ __('Hội thảo & Truyền thông') }}</h4></a>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <h2><span class="id-color-2">{{ __('Triển khai Dự án') }}</span> {{ __('Sinh thái Tuần hoàn') }}</h2>
                                <p class="lead">{{ __('Viện GREECO thiết kế, phát triển và vận hành trực tiếp các dự án môi trường bền vững. Chúng tôi cung cấp các giải pháp hoàn chỉnh cho doanh nghiệp muốn đầu tư và thương mại hóa các dự án bù đắp carbon, rác thải nhựa và tái chế.') }}</p>
                            </div>
                        </div>

                        <div class="spacer-double"></div>

                        <div class="row g-4">
                            <!-- Project Service 1 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px project-item-box h-100">
                                    <div class="icon-circle">
                                        <i class="fa-solid fa-cloud"></i>
                                    </div>
                                    <h4>{{ __('Tín chỉ carbon') }}</h4>
                                    <p class="mb-0">{{ __('Phát triển dự án hấp thụ carbon (trồng rừng, bảo tồn rừng) và giảm phát thải khí nhà kính (năng lượng sạch, hiệu quả năng lượng) để đăng ký thẩm định, phát hành và giao dịch tín chỉ carbon theo các tiêu chuẩn quốc tế Verra (VCS), Gold Standard, J-Credit.') }}</p>
                                </div>
                            </div>

                            <!-- Project Service 2 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px project-item-box h-100">
                                    <div class="icon-circle">
                                        <i class="fa-solid fa-box-tissue"></i>
                                    </div>
                                    <h4>{{ __('Tín chỉ nhựa') }}</h4>
                                    <p class="mb-0">{{ __('Xây dựng và đăng ký các dự án thu gom, phân loại và tái chế rác thải nhựa đại dương hoặc rác thải nhựa đô thị để chuyển đổi thành tín chỉ nhựa (Plastic Credit), giúp doanh nghiệp hoàn thành trách nhiệm trung hòa nhựa hoặc gia tăng giá trị chuỗi cung ứng.') }}</p>
                                </div>
                            </div>

                            <!-- Project Service 3 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px project-item-box h-100">
                                    <div class="icon-circle">
                                        <i class="fa-solid fa-seedling"></i>
                                    </div>
                                    <h4>{{ __('Biochar (Than sinh học)') }}</h4>
                                    <p class="mb-0">{{ __('Triển khai dự án sản xuất và ứng dụng biochar từ phế phẩm nông nghiệp (trấu, rơm rạ, vỏ cà phê...). Biochar giúp lưu trữ carbon lâu dài trong đất (cố định carbon sinh học), đồng thời nâng cao độ phì nhiêu của đất, tối ưu hóa năng suất cây trồng.') }}</p>
                                </div>
                            </div>

                            <!-- Project Service 4 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px project-item-box h-100">
                                    <div class="icon-circle">
                                        <i class="fa-solid fa-recycle"></i>
                                    </div>
                                    <h4>{{ __('Thu gom, phân loại & tái chế chất thải') }}</h4>
                                    <p class="mb-0">{{ __('Nghiên cứu, quy hoạch và thiết kế hệ thống phân loại chất thải tại nguồn, quy trình thu gom tối ưu và xây dựng các mô hình sản xuất/chế biến phân bón hữu cơ, hạt nhựa tái sinh cho các địa phương, khu công nghiệp và đô thị sinh thái.') }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        
    </div>
@endsection
