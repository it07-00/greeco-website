@php
    $seoTitle = 'Đào tạo & Bồi dưỡng Chuyên môn - Viện GREECO';
    $seoDescription = 'Đào tạo chuyên môn về Hệ thống quản lý ISO, HSE, Kiểm kê khí nhà kính, ESG, Chuỗi cung ứng bền vững, Trách nhiệm xã hội và Công trình xanh.';
    $canonical = route('services.dao-tao');
    
    $breadcrumbs = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Lĩnh vực', 'url' => route('services.index')],
        ['name' => 'Đào tạo & Bồi dưỡng', 'url' => route('services.dao-tao')]
    ];
@endphp

@extends('layouts.app')


@section('content')
    <x-schema.breadcrumb :items="$breadcrumbs" />
    <x-schema.service 
        name="Đào tạo & Bồi dưỡng Chuyên môn" 
        description="Đào tạo chuyên môn về Hệ thống quản lý ISO, HSE, Kiểm kê khí nhà kính, ESG, Chuỗi cung ứng bền vững, Trách nhiệm xã hội và Công trình xanh." 
        :url="$canonical" 
    />

    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        <section id="subheader" class="relative">
            <div class="container relative z-index-1000">
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="crumb">
                           <li><a href="{{ route('home') }}">Trang chủ</a></li>
                            <li><a href="{{ route('services.index') }}">Lĩnh vực</a></li>
                            <li class="active">Đào tạo & Bồi dưỡng</li>
                        </ul>
                        <h1 class="text-uppercase">Đào tạo & Bồi dưỡng</h1>
                        <p class="col-lg-10">Đào tạo và bồi dưỡng chuyên môn sâu rộng về các tiêu chuẩn phát triển bền vững và giảm phát thải.</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row g-4 gx-5">
                    <div class="col-lg-3">
                        <div class="me-lg-3">
                            <a href="{{ route('services.dao-tao') }}" class="bg-color text-light d-block p-3 px-4 rounded-10px mb-3 relative"><h4 class="mb-0">Đào tạo & Bồi dưỡng</h4><i class="icofont-long-arrow-right absolute abs-middle fs-24 end-20px"></i></a>
                            <a href="{{ route('services.tu-van') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3"><h4 class="mb-0">Dịch vụ Tư vấn</h4></a>
                            <a href="{{ route('services.du-an') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3"><h4 class="mb-0">Phát triển Dự án</h4></a>
                            <a href="{{ route('services.nghien-cuu') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3"><h4 class="mb-0">Nghiên cứu và Chuyển giao Công nghệ</h4></a>
                            <a href="{{ route('services.hoi-thao') }}" class="bg-light d-block p-3 px-4 rounded-10px mb-3"><h4 class="mb-0">Hội thảo & Truyền thông</h4></a>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <h2><span class="id-color-2">Đào tạo & Bồi dưỡng</span> Chuyên môn Bền vững</h2>
                                <p class="lead">Viện GREECO cung cấp các chương trình đào tạo và bồi dưỡng chuyên sâu, thực tiễn từ cơ bản đến nâng cao. Đội ngũ chuyên gia của chúng tôi hỗ trợ doanh nghiệp xây dựng năng lực nội tại, chuẩn hóa hệ thống quản lý và đáp ứng các tiêu chuẩn xuất khẩu khắt khe về phát triển bền vững và trung hòa carbon.</p>
                            </div>
                        </div>

                        <div class="spacer-double"></div>

                        <div class="row g-4">
                            <!-- Subcategory 1 -->
                            <div class="col-lg-12">
                                <div class="p-4 rounded-10px training-card mb-3">
                                    <h4 class="id-color-2 mb-2"><i class="fa-solid fa-gears me-2"></i>Hệ thống quản lý ISO và cải tiến doanh nghiệp</h4>
                                    <p class="mb-3">Cung cấp các khóa đào tạo từ nhận thức chung đến chuyên viên đánh giá nội bộ, giúp doanh nghiệp chuẩn hóa quy trình và nâng cao năng lực vận hành:</p>
                                    <ul class="ul-style-2 mb-0">
                                        <li><strong>ISO 9001:2015:</strong> Xây dựng Hệ thống Quản lý Chất lượng hiệu quả.</li>
                                        <li><strong>ISO 14001:2015:</strong> Thiết lập Hệ thống Quản lý Môi trường chuẩn quốc tế.</li>
                                        <li><strong>ISO 45001:2018:</strong> Xây dựng Hệ thống Quản lý An toàn và Sức khỏe Nghề nghiệp.</li>
                                        <li><strong>ISO 50001:2018:</strong> Thiết lập Hệ thống Quản lý Năng lượng bền vững.</li>
                                        <li><strong>ISO 46001:2019:</strong> Xây dựng Hệ thống Quản lý Hiệu quả Sử dụng Nước tối ưu.</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Subcategory 2 -->
                            <div class="col-lg-12">
                                <div class="p-4 rounded-10px training-card mb-3">
                                    <h4 class="id-color-2 mb-2"><i class="fa-solid fa-shield-halved me-2"></i>An toàn – Sức khỏe – Môi trường, HSE</h4>
                                    <p class="mb-3">Trang bị kiến thức và kỹ năng thực tiễn để kiểm soát rủi ro, bảo vệ sức khỏe người lao động và giảm thiểu tai nạn lao động:</p>
                                    <ul class="ul-style-2 mb-0">
                                        <li>Đánh giá Rủi ro An toàn – Sức khỏe – Môi trường toàn diện.</li>
                                        <li>Quản lý Hóa chất an toàn và hiệu quả trong Doanh nghiệp.</li>
                                        <li>Điều tra Sự cố và Phân tích Nguyên nhân Gốc rễ (Root Cause Analysis – RCA).</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Subcategory 3 -->
                            <div class="col-lg-12">
                                <div class="p-4 rounded-10px training-card mb-3">
                                    <h4 class="id-color-2 mb-2"><i class="fa-solid fa-cloud-sun me-2"></i>Kiểm kê khí nhà kính và quản lý carbon</h4>
                                    <p class="mb-3">Đầu ngành cùng mục tiêu giảm phát thải của quốc gia và quốc tế, đào tạo chuyên sâu về kỹ thuật kiểm kê và định lượng carbon:</p>
                                    <ul class="ul-style-2 mb-0">
                                        <li>Kiểm kê Khí nhà kính theo Nghị định 06/2022/NĐ-CP của Chính phủ.</li>
                                        <li>Kiểm kê Khí nhà kính chuẩn hóa quốc tế theo tiêu chuẩn ISO 14064-1:2018.</li>
                                        <li>Hướng dẫn Định lượng Khí nhà kính theo ISO 14069:2013.</li>
                                        <li>Đào tạo phương pháp Greenhouse Gas Protocol (GHG Protocol).</li>
                                        <li>Tính toán Vết Carbon của Sản phẩm theo ISO 14067:2018.</li>
                                        <li>PAS 2060:2014 – Hướng dẫn Chứng nhận Trung hòa Carbon.</li>
                                        <li>ISO 14068-1:2023 – Tiêu chuẩn mới về Trung hòa Carbon.</li>
                                        <li>Verified Carbon Standard, VCS – Tiêu chuẩn Carbon đã được Xác minh.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Subcategory 4 -->
                            <div class="col-lg-12">
                                <div class="p-4 rounded-10px training-card mb-3">
                                    <h4 class="id-color-2 mb-2"><i class="fa-solid fa-chart-line me-2"></i>ESG và phát triển bền vững</h4>
                                    <p class="mb-3">Nâng cao nhận thức và năng lực tích hợp tiêu chuẩn ESG vào chiến lược kinh doanh dài hạn của doanh nghiệp:</p>
                                    <ul class="ul-style-2 mb-0">
                                        <li><strong>Environmental, Social and Governance, ESG:</strong> Môi trường, Xã hội và Quản trị.</li>
                                        <li><strong>Global Reporting Initiative, GRI:</strong> Sáng kiến Báo cáo Toàn cầu.</li>
                                        <li><strong>Science Based Targets initiative, SBTi:</strong> Sáng kiến Mục tiêu Dựa trên Cơ sở Khoa học.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Subcategory 5 -->
                            <div class="col-lg-12">
                                <div class="p-4 rounded-10px training-card mb-3">
                                    <h4 class="id-color-2 mb-2"><i class="fa-solid fa-arrows-spin me-2"></i>Chuỗi cung ứng bền vững và kinh tế tuần hoàn</h4>
                                    <p class="mb-3">Hỗ trợ doanh nghiệp hội nhập chuỗi cung ứng toàn cầu thông qua các tiêu chuẩn tuần hoàn và truy xuất nguồn gốc:</p>
                                    <ul class="ul-style-2 mb-0">
                                        <li>Chứng nhận Bền vững và Carbon Quốc tế: ISCC PLUS, ISCC EU, ISCC CORSIA.</li>
                                        <li><strong>Global Recycled Standard, GRS:</strong> Tiêu chuẩn Tái chế Toàn cầu.</li>
                                        <li><strong>EN 15343:</strong> Truy xuất nguồn gốc và xác định hàm lượng nhựa tái chế trong sản phẩm nhựa.</li>
                                        <li><strong>Cradle to Cradle Certified®:</strong> Tiêu chuẩn Thiết kế Sản phẩm Tuần hoàn.</li>
                                        <li><strong>Higg Facility Environmental Module, Higg FEM:</strong> Đánh giá tác động môi trường tại nhà máy.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Subcategory 6 -->
                            <div class="col-lg-12">
                                <div class="p-4 rounded-10px training-card mb-3">
                                    <h4 class="id-color-2 mb-2"><i class="fa-solid fa-users me-2"></i>Trách nhiệm xã hội và đánh giá chuỗi cung ứng</h4>
                                    <p class="mb-3">Hướng dẫn tuân thủ các quy tắc đạo đức kinh doanh, bảo vệ quyền lợi người lao động và trách nhiệm xã hội:</p>
                                    <ul class="ul-style-2 mb-0">
                                        <li>Đánh giá Nhà cung cấp (Supplier Audit) theo chuẩn mực quốc tế.</li>
                                        <li><strong>Social Accountability 8000, SA8000:</strong> Tiêu chuẩn Trách nhiệm Xã hội.</li>
                                        <li><strong>SMETA (Sedex Members Ethical Trade Audit):</strong> Đánh giá đạo đức kinh doanh trong chuỗi cung ứng.</li>
                                        <li><strong>Business Social Compliance Initiative, BSCI:</strong> Bộ Tiêu chuẩn Tuân thủ Trách nhiệm Xã hội trong Kinh doanh.</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Subcategory 7 -->
                            <div class="col-lg-12">
                                <div class="p-4 rounded-10px training-card mb-3">
                                    <h4 class="id-color-2 mb-2"><i class="fa-solid fa-building-wheat me-2"></i>Công trình xanh và sản phẩm bền vững</h4>
                                    <p class="mb-3">Xây dựng giải pháp kỹ thuật cho công trình xây dựng và sản phẩm đạt chứng chỉ sinh thái:</p>
                                    <ul class="ul-style-2 mb-0">
                                        <li><strong>Leadership in Energy and Environmental Design, LEED:</strong> Hệ thống Đánh giá Công trình Xanh của Hoa Kỳ.</li>
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
