@php
    $seoTitle = __('Hội thảo, Hội nghị & Truyền thông Khoa học - Viện GREECO');
    $seoDescription = __('Tổ chức hội thảo khoa học, diễn đàn đối thoại về kinh tế xanh, Net Zero và truyền thông nâng cao nhận thức cộng đồng.');
    $canonical = website_route('services.hoi-thao');
    
    $breadcrumbs = [
        ['name' => __('Trang chủ'), 'url' => website_route('home')],
        ['name' => __('Lĩnh vực'), 'url' => website_route('services.index')],
        ['name' => __('Hội thảo & Truyền thông'), 'url' => website_route('services.hoi-thao')]
    ];
@endphp

@extends('layouts.app')


@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />
    <x-schema.service 
        name="Hội thảo & Truyền thông" 
        description="Tổ chức các sự kiện học thuật, diễn đàn đối thoại doanh nghiệp và các chiến dịch truyền thông nâng cao nhận thức cộng đồng về phát triển bền vững." 
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
                            <li class="active">{{ __('Hội thảo & Truyền thông') }}</li>
                        </ul>
                        <h1 class="text-uppercase">{{ __('Hội thảo & Truyền thông') }}</h1>
                        <p class="col-lg-10">{{ __('Tổ chức các sự kiện học thuật, diễn đàn đối thoại doanh nghiệp và các chiến dịch truyền thông nâng cao nhận thức cộng đồng về phát triển bền vững.') }}</p>
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
                            <a href="{{ website_route('services.du-an') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3"><h4 class="mb-0">{{ __('Phát triển Dự án') }}</h4></a>
                            <a href="{{ website_route('services.nghien-cuu') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3"><h4 class="mb-0">{{ __('Nghiên cứu và Chuyển giao Công nghệ') }}</h4></a>
                            <a href="{{ website_route('services.hoi-thao') }}" class="bg-color text-light d-block p-3 px-4 rounded-10px mb-3 relative"><h4 class="mb-0">{{ __('Hội thảo & Truyền thông') }}</h4><i class="icofont-long-arrow-right absolute abs-middle fs-24 end-20px"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <h2><span class="id-color-2">{{ __('Hội thảo Khoa học') }}</span> {{ __('& Truyền thông Tri thức') }}</h2>
                                <p class="lead">{{ __('Viện GREECO chủ trì và phối hợp tổ chức các hội nghị, hội thảo chuyên đề quốc gia và quốc tế. Chúng tôi đồng thời cung cấp các dịch vụ thông tin khoa học công nghệ, phát triển nội dung truyền thông xanh và nâng cao nhận thức cộng đồng xã hội.') }}</p>
                            </div>
                        </div>

                        <div class="spacer-double"></div>

                        <div class="row g-4">
                            <!-- Event Activity 1 -->
                            <div class="col-lg-12">
                                <div class="p-4 rounded-10px event-feature-box mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="service-feature-icon service-feature-icon-sm bg-color text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="fa-solid fa-users-rectangle"></i>
                                        </div>
                                        <h4 class="id-color-2 mb-0">{{ __('Tổ chức hội thảo, hội nghị khoa học') }}</h4>
                                    </div>
                                    <p class="mb-3 text-justify">{{ __('Viện GREECO chủ trì và phối hợp cùng các cơ quan quản lý nhà nước, các viện nghiên cứu và trường đại học để tổ chức các hội nghị khoa học, diễn đàn thảo luận cấp cao và chuyên sâu:') }}</p>
                                    <ul class="ul-style-2 mb-0">
                                        <li><strong>{{ __('Chuỗi hội thảo khoa học chuyên đề:') }}</strong> {{ __('Nghiên cứu sâu về các chủ đề nóng như kiểm kê khí nhà kính, chuyển dịch năng lượng xanh, thu hồi carbon, và phát triển bền vững.') }}</li>
                                        <li><strong>{{ __('Diễn đàn Kinh tế Xanh thường niên:') }}</strong> {{ __('Nơi quy tụ các chuyên gia, nhà hoạch định chính sách, đại diện các tổ chức quốc tế và cộng đồng doanh nghiệp để đề xuất các sáng kiến và giải pháp tối ưu.') }}</li>
                                        <li><strong>{{ __('Hội thảo chuyên môn & Đào tạo thực hành:') }}</strong> {{ __('Cập nhật liên tục các quy định pháp luật mới nhất, xu hướng công nghệ xanh và quy chuẩn kỹ thuật cho doanh nghiệp.') }}</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Event Activity 2 -->
                            <div class="col-lg-12">
                                <div class="p-4 rounded-10px event-feature-box mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="service-feature-icon service-feature-icon-sm bg-color text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="fa-solid fa-comments"></i>
                                        </div>
                                        <h4 class="id-color-2 mb-0">{{ __('Diễn đàn đối thoại & kết nối đầu tư xanh') }}</h4>
                                    </div>
                                    <p class="mb-3 text-justify">{{ __('Chúng tôi xây dựng các không gian đối thoại mở, tin cậy nhằm thúc đẩy hợp tác công tư (PPP) và mở rộng nguồn lực đầu tư tài chính cho phát triển bền vững:') }}</p>
                                    <ul class="ul-style-2 mb-0">
                                        <li><strong>{{ __('Diễn đàn đối thoại công tư (PPD):') }}</strong> {{ __('Thảo luận trực tiếp giữa cơ quan chính phủ và khối doanh nghiệp tư nhân nhằm tháo gỡ khó khăn về cơ chế chính sách, thúc đẩy thực thi kinh tế tuần hoàn.') }}</li>
                                        <li><strong>{{ __('Kết nối đầu tư xanh (Green Pitching Day):') }}</strong> {{ __('Giới thiệu, kết nối các dự án khởi nghiệp sinh thái, dự án giảm phát thải carbon với các quỹ đầu tư mạo hiểm và tổ chức tài chính xanh trong nước và quốc tế.') }}</li>
                                        <li><strong>{{ __('Diễn đàn kết nối công nghệ:') }}</strong> {{ __('Giúp các doanh nghiệp tiếp cận các giải pháp công nghệ xanh tiên tiến trên thế giới, nâng cao hiệu suất và giảm chi phí sản xuất.') }}</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Event Activity 3 -->
                            <div class="col-lg-12">
                                <div class="p-4 rounded-10px event-feature-box mb-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="service-feature-icon service-feature-icon-sm bg-color text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="fa-solid fa-bullhorn"></i>
                                        </div>
                                        <h4 class="id-color-2 mb-0">{{ __('Truyền thông khoa học & nâng cao nhận thức') }}</h4>
                                    </div>
                                    <p class="mb-3 text-justify">{{ __('Nhằm phổ biến kiến thức khoa học và nâng cao nhận thức cộng đồng, GREECO triển khai các hoạt động truyền thông sáng tạo, thực chất và bền vững:') }}</p>
                                    <ul class="ul-style-2 mb-0">
                                        <li><strong>{{ __('Xuất bản ấn phẩm chuyên sâu:') }}</strong> {{ __('Phát hành các bản tin kinh tế xanh định kỳ, cẩm nang hướng dẫn kiểm kê khí nhà kính, báo cáo nghiên cứu và sách trắng Net Zero tại Việt Nam.') }}</li>
                                        <li><strong>{{ __('Chiến dịch truyền thông cộng đồng:') }}</strong> {{ __('Hợp tác chặt chẽ với các cơ quan thông tấn, báo chí xây dựng nội dung truyền thông đa phương tiện về lối sống xanh, tiêu dùng bền vững và tiết kiệm năng lượng.') }}</li>
                                        <li><strong>{{ __('Tư vấn truyền thông xanh cho doanh nghiệp:') }}</strong> {{ __('Hỗ trợ doanh nghiệp phát triển các chiến dịch truyền thông thương hiệu bền vững, minh bạch thông tin xanh, tránh hiện tượng "greenwashing" (tẩy xanh).') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        
    </div>
@endsection
