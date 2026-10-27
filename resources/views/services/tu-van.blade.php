@php
    $seoTitle = __('Dịch vụ Tư vấn Doanh nghiệp & Môi trường - Viện GREECO');
    $seoDescription = __('Dịch vụ tư vấn về ESG, kiểm kê khí nhà kính, lộ trình Net Zero (SBTi), kiểm toán năng lượng, báo cáo CBAM, EPR, chống phá rừng (EUDR) và cảng xanh.');
    $canonical = website_route('services.tu-van');

    $breadcrumbs = [
        ['name' => __('Trang chủ'), 'url' => website_route('home')],
        ['name' => __('Lĩnh vực'), 'url' => website_route('services.index')],
        ['name' => __('Dịch vụ Tư vấn'), 'url' => website_route('services.tu-van')]
    ];
@endphp

@extends('layouts.app')


@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />
    <x-schema.service name="Dịch vụ Tư vấn"
        description="Tư vấn toàn diện giúp doanh nghiệp tối ưu năng lượng, giảm phát thải carbon và tuân thủ các quy định môi trường quốc tế."
        :url="$canonical" />

    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <section id="subheader" class="relative">
            <div class="container relative z-index-1000">
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="crumb">
                            <li><a href="{{ website_route('home') }}">{{ __('Trang chủ') }}</a></li>
                            <li><a href="{{ website_route('services.index') }}">{{ __('Lĩnh vực') }}</a></li>
                            <li class="active">{{ __('Dịch vụ Tư vấn') }}</li>
                        </ul>
                        <h1 class="text-uppercase">{{ __('Dịch vụ Tư vấn') }}</h1>
                        <p class="col-lg-10">{{ __('Tư vấn toàn diện giúp doanh nghiệp tối ưu năng lượng, giảm phát thải carbon và tuân thủ các quy định môi trường quốc tế.') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4 gx-5">
                    <div class="col-lg-3">
                        <div class="me-lg-3">
                            <a href="{{ website_route('services.dao-tao') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3">
                                <h4 class="mb-0">{{ __('Đào tạo & Bồi dưỡng') }}</h4>
                            </a>
                            <a href="{{ website_route('services.tu-van') }}"
                                class="bg-color text-light d-block p-3 px-4 rounded-10px mb-3 relative">
                                <h4 class="mb-0">{{ __('Dịch vụ Tư vấn') }}</h4><i
                                    class="icofont-long-arrow-right absolute abs-middle fs-24 end-20px"></i>
                            </a>
                            <a href="{{ website_route('services.du-an') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3">
                                <h4 class="mb-0">{{ __('Phát triển Dự án') }}</h4>
                            </a>
                            <a href="{{ website_route('services.nghien-cuu') }}"
                                class="bg-light d-block p-3 px-4 rounded-10px mb-3">
                                <h4 class="mb-0">{{ __('Nghiên cứu và Chuyển giao Công nghệ') }}</h4>
                            </a>
                            <a href="{{ website_route('services.hoi-thao') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3">
                                <h4 class="mb-0">{{ __('Hội thảo & Truyền thông') }}</h4>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <h2><span class="id-color-2">{{ __('Tư vấn Chiến lược') }}</span> {{ __('& Giải pháp Môi trường') }}</h2>
                                <p class="lead">{{ __('Viện GREECO hỗ trợ các tổ chức và doanh nghiệp vượt qua thách thức chuyển đổi xanh. Chúng tôi thiết lập lộ trình phát triển bền vững, thực hiện các báo cáo chuyên sâu và tư vấn tuân thủ pháp lý theo các chuẩn mực toàn cầu mới nhất.') }}</p>
                            </div>
                        </div>

                        <div class="spacer-double"></div>

                        <div class="row g-4">
                            <!-- Consulting Service 1 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px icon-box-service h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-chart-pie me-3"></i>
                                        <h4 class="mb-0">{{ __('ESG và phát triển bền vững') }}</h4>
                                    </div>
                                    <p class="mb-0">{{ __('Tư vấn xây dựng chiến lược tích hợp các tiêu chuẩn Môi trường, Xã hội và Quản trị (ESG), xây dựng chỉ số đo lường hiệu quả và hỗ trợ lập Báo cáo Phát triển Bền vững đạt chuẩn GRI.') }}</p>
                                </div>
                            </div>

                            <!-- Consulting Service 2 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px icon-box-service h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-cloud-arrow-down me-3"></i>
                                        <h4 class="mb-0">{{ __('Kiểm kê khí nhà kính & Giảm phát thải') }}</h4>
                                    </div>
                                    <p class="mb-0">{{ __('Khảo sát ranh giới phát thải, định lượng khí nhà kính tại cơ sở sản xuất và xây dựng các phương án, lộ trình khả thi để giảm thiểu dấu chân carbon theo Nghị định 06 và ISO 14064.') }}</p>
                                </div>
                            </div>

                            <!-- Consulting Service 3 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px icon-box-service h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-bullseye me-3"></i>
                                        <h4 class="mb-0">{{ __('SBTi và lộ trình Net Zero') }}</h4>
                                    </div>
                                    <p class="mb-0">{{ __('Thiết lập mục tiêu giảm phát thải carbon dựa trên cơ sở khoa học (SBTi) và hoạch định lộ trình chuyển đổi năng lượng để đạt phát thải ròng bằng không (Net Zero) cho doanh nghiệp.') }}</p>
                                </div>
                            </div>

                            <!-- Consulting Service 4 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px icon-box-service h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-bolt me-3"></i>
                                        <h4 class="mb-0">{{ __('Kiểm toán năng lượng') }}</h4>
                                    </div>
                                    <p class="mb-0">{{ __('Khảo sát và đánh giá hệ thống tiêu thụ năng lượng của nhà máy, phát hiện lãng phí và đề xuất giải pháp kỹ thuật tiết kiệm năng lượng tối ưu, giảm chi phí vận hành.') }}</p>
                                </div>
                            </div>

                            <!-- Consulting Service 5 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px icon-box-service h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-file-invoice me-3"></i>
                                        <h4 class="mb-0">{{ __('Tư vấn & lập báo cáo CBAM') }}</h4>
                                    </div>
                                    <p class="mb-0">{{ __('Hướng dẫn đo lường hàm lượng carbon tích lũy trong sản phẩm và lập báo cáo Cơ chế điều chỉnh biên giới carbon (CBAM) phục vụ xuất khẩu hàng hóa sang thị trường Liên minh Châu Âu (EU).') }}</p>
                                </div>
                            </div>

                            <!-- Consulting Service 6 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px icon-box-service h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-recycle me-3"></i>
                                        <h4 class="mb-0">{{ __('EPR cho doanh nghiệp') }}</h4>
                                    </div>
                                    <p class="mb-0">{{ __('Tư vấn thực thi Trách nhiệm mở rộng của nhà sản xuất (EPR), lập kế hoạch thu hồi, phân loại và tái chế chất thải, bao bì sản phẩm theo quy định của Luật Bảo vệ Môi trường.') }}</p>
                                </div>
                            </div>

                            <!-- Consulting Service 7 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px icon-box-service h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-leaf me-3"></i>
                                        <h4 class="mb-0">{{ __('Kinh tế xanh & Kinh tế tuần hoàn') }}</h4>
                                    </div>
                                    <p class="mb-0">{{ __('Tư vấn tái cấu trúc quy trình sản xuất theo mô hình tuần hoàn, khép kín dòng nguyên liệu, tận dụng tối đa phế phụ phẩm làm đầu vào sản xuất.') }}</p>
                                </div>
                            </div>

                            <!-- Consulting Service 8 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px icon-box-service h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-tree me-3"></i>
                                        <h4 class="mb-0">{{ __('Phòng vệ thương mại & EUDR') }}</h4>
                                    </div>
                                    <p class="mb-0">{{ __('Tư vấn hệ thống truy xuất nguồn gốc nông, lâm sản chống phá rừng theo Quy định chống phá rừng của EU (EUDR) và phòng vệ thương mại cho hàng xuất khẩu.') }}</p>
                                </div>
                            </div>

                            <!-- Consulting Service 9 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px icon-box-service h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-volume-high me-3"></i>
                                        <h4 class="mb-0">{{ __('Xây dựng bản đồ tiếng ồn (Noise Map)') }}</h4>
                                    </div>
                                    <p class="mb-0">{{ __('Khảo sát, mô phỏng và xây dựng bản đồ tiếng ồn số hóa cho các khu công nghiệp, đô thị, công trình giao thông nhằm đưa ra giải pháp giảm âm, cách âm hiệu quả.') }}</p>
                                </div>
                            </div>

                            <!-- Consulting Service 10 -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-4 rounded-10px icon-box-service h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-ship me-3"></i>
                                        <h4 class="mb-0">{{ __('Tiêu chí cảng xanh') }}</h4>
                                    </div>
                                    <p class="mb-0">{{ __('Tư vấn xây dựng mô hình cảng xanh bền vững thông qua việc kiểm soát ô nhiễm, tối ưu hóa năng lượng cảng và đáp ứng các tiêu chuẩn chứng nhận cảng xanh khu vực và quốc tế.') }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection