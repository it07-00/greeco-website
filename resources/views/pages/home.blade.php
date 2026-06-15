@php
    $seoTitle = 'Viện Nghiên cứu và Phát triển Kinh tế Xanh - GREECO';
    $seoDescription = 'Viện Nghiên cứu và Phát triển Kinh tế Xanh (GREECO) - Đơn vị hàng đầu về chuyển giao công nghệ xanh, kinh tế tuần hoàn và ứng phó biến đổi khí hậu.';
    $canonical = route('home');
    $lcpBannerImage = $banners->isNotEmpty()
        ? asset('storage/' . $banners->first()->image)
        : asset('assets/images/5.webp');
@endphp

@extends('layouts.app')

@push('preloads')
    <link rel="preload" as="image" href="{{ $lcpBannerImage }}" fetchpriority="high">
@endpush

@push('vendor-styles')
    <link href="{{ versioned_asset('assets/css/swiper.css') }}" rel="stylesheet" type="text/css">
@endpush


@push('scripts')
    <script src="{{ versioned_asset('assets/js/swiper.js') }}" defer></script>
    <script src="{{ versioned_asset('assets/js/custom-swiper-2.js') }}" defer></script>
    <script src="{{ versioned_asset('assets/js/custom-marquee.js') }}" defer></script>
@endpush

@section('content')
    <x-schema.organization />

    <div class="no-bottom no-top" id="content">

        <div id="top"></div>

        <section id="section-intro" class="text-light no-top no-bottom relative overflow-hidden z-1000">
            <div class="v-center relative">

                <div class="swiper">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        @if($banners->isNotEmpty())
                            @foreach($banners as $banner)
                                <!-- Slide -->
                                <div class="swiper-slide banner-slide-{{ $loop->iteration }}">
                                    <div class="swiper-inner">
                                        @if($banner->url)
                                            <a href="{{ $banner->url }}">
                                                <img src="{{ asset('storage/' . $banner->image) }}" class="w-100 banner-img"
                                                    alt="GREECO Banner" width="2160" height="1215"
                                                    loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                                    fetchpriority="{{ $loop->first ? 'high' : 'low' }}" decoding="async">
                                            </a>
                                        @else
                                            <img src="{{ asset('storage/' . $banner->image) }}" class="w-100 banner-img"
                                                alt="GREECO Banner" width="2160" height="1215"
                                                loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                                fetchpriority="{{ $loop->first ? 'high' : 'low' }}" decoding="async">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Slide Fallback 1 -->
                            <div class="swiper-slide fallback-slide-1">
                                <div class="swiper-inner">
                                    <img src="{{ asset('assets/images/5.webp') }}" class="w-100 banner-img" alt="GREECO Banner"
                                        width="2160" height="1215" loading="eager" fetchpriority="high" decoding="async">
                                </div>
                            </div>
                            <!-- Slide Fallback 2 -->
                            <div class="swiper-slide fallback-slide-2">
                                <div class="swiper-inner">
                                    <img src="{{ asset('assets/images/6.webp') }}" class="w-100 banner-img" alt="GREECO Banner"
                                        width="2160" height="1215" loading="lazy" fetchpriority="low" decoding="async">
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Navigation buttons -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>

                </div>
            </div>
        </section>

        <section class="pb-50">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6 mb-5 mb-lg-0 pb-4 pb-lg-0">
                        <div class="relative">
                            <div class="rounded-1 bg-body w-90 overflow-hidden wow zoomIn">
                                <img src="{{ asset('assets/images/misc/1.webp') }}" width="1024" height="1024" class="w-100 wow scaleIn"
                                    style="height: auto;" loading="lazy" decoding="async"
                                    alt="Kinh tế xanh bền vững GREECO">
                             </div>
                             <div class="rounded-1 bg-body w-50 abs mb-min-50 end-0 bottom-0 z-2 overflow-hidden shadow-soft wow zoomIn"
                                 data-wow-delay=".2s">
                                 <img src="{{ asset('assets/images/misc/2.webp') }}" width="1024" height="1024" class="w-100 wow scaleIn"
                                     style="height: auto;" loading="lazy" decoding="async"
                                     data-wow-delay=".2s" alt="Giải pháp năng lượng tái tạo GREECO">
                             </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ps-lg-5 position-relative z-3">
                            <div class="subtitle id-color wow fadeInUp" data-wow-delay=".2s">Chào mừng đến với GREECO</div>
                            <h2 class="text-uppercase wow fadeInUp" data-wow-delay=".4s">Nghiên&nbsp;cứu & Thúc&nbsp;đẩy <span
                                    class="id-color-2">Kinh&nbsp;tế&nbsp;Xanh</span></h2>
                            <p class="wow fadeInUp" data-wow-delay=".6s">Viện Nghiên cứu và Phát triển Kinh tế Xanh (GREECO)
                                là tổ chức khoa học và công nghệ hoạt động trong lĩnh vực nghiên cứu, ứng dụng và chuyển
                                giao các giải pháp phát triển bền vững, kinh tế xanh và công nghệ môi trường. Với đội ngũ
                                chuyên gia đa ngành trong các lĩnh vực kỹ thuật hóa học, kỹ thuật môi trường, năng lượng tái
                                tạo, viễn thám, địa kỹ thuật và quản trị phát triển bền vững, GREECO hướng đến việc kết nối
                                nghiên cứu khoa học với thực tiễn, tạo ra các giải pháp đổi mới sáng tạo phục vụ doanh
                                nghiệp, cộng đồng và cơ quan quản lý.</p>
                            <a class="btn-main wow fadeInUp" href="{{ route('services.index') }}" data-wow-delay=".6s">Lĩnh
                                vực hoạt động</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- marquee section begin -->
        <section class="pt-2 pb-1 bg-light mt90">
            <div class="wow fadeInRight d-flex">
                <div class="de-marquee-list relative wow">
                    <span class="fs-36 lh-1 mx-4 text-uppercase title-font">01. Kinh tế Tuần hoàn</span>
                    <span class="fs-36 lh-1 mx-4 text-uppercase title-font">02. Tư vấn Môi trường & Khí hậu</span>
                    <span class="fs-36 lh-1 mx-4 text-uppercase title-font">03. Tín chỉ Carbon</span>
                    <span class="fs-36 lh-1 mx-4 text-uppercase title-font">04. Năng lượng Tái tạo</span>
                    <span class="fs-36 lh-1 mx-4 text-uppercase title-font">05. Chuyển giao Công nghệ Xanh</span>
                    <span class="fs-36 lh-1 mx-4 text-uppercase title-font">06. Đào tạo Phát triển Bền vững</span>
                </div>
            </div>
        </section>
        <!-- marquee section end -->

        <!-- marquee section begin -->
        <section class="pt-2 pb-1 bg-color mb60">
            <div class="wow fadeInLeft d-flex">
                <div class="de-marquee-list-2 relative wow">
                    <span class="fs-36 lh-1 mx-4 text-white text-uppercase title-font">01. Kinh tế Tuần hoàn</span>
                    <span class="fs-36 lh-1 mx-4 text-white text-uppercase title-font">02. Tư vấn Môi trường & Khí
                        hậu</span>
                    <span class="fs-36 lh-1 mx-4 text-white text-uppercase title-font">03. Tín chỉ Carbon</span>
                    <span class="fs-36 lh-1 mx-4 text-white text-uppercase title-font">04. Năng lượng Tái tạo</span>
                    <span class="fs-36 lh-1 mx-4 text-white text-uppercase title-font">05. Chuyển giao Công nghệ Xanh</span>
                    <span class="fs-36 lh-1 mx-4 text-white text-uppercase title-font">06. Đào tạo Phát triển Bền
                        vững</span>
                </div>
            </div>
        </section>
        <!-- marquee section end -->

        <section class="p-4">
            <div class="container-fluid featured-services-grid">
                <div class="row g-4">
                    @foreach($serviceCategories as $category)
                        <div class="col-lg-4 col-sm-6 wow fadeInRight" data-wow-delay="{{ ($loop->index % 3) * 0.3 }}s">
                            <a class="featured-service-link" href="{{ $category['url'] }}"
                                aria-label="Xem chi tiết {{ $category['name'] }}">
                                <div class="bg-color text-light rounded-1 overflow-hidden h-100 d-flex flex-column">
                                    <div class="hover relative overflow-hidden text-light text-center">
                                        <img src="{{ $category['image'] }}" width="1024" height="1024" class="hover-scale-1-1 w-100"
                                            style="height: auto;" loading="lazy" decoding="async"
                                            alt="{{ $category['name'] }}">
                                        <div class="abs w-100 px-4 hover-op-1 z-4 hover-mt-40 abs-centered">
                                            <span class="btn-main">Xem chi tiết</span>
                                        </div>
                                        <img src="{{ setting('logo_icon') ? asset('storage/' . setting('logo_icon')) : asset('assets/images/logo-icon.webp') }}" width="166" height="166" class="abs abs-centered w-20"
                                            style="height: auto;" loading="lazy" decoding="async"
                                            alt="GREECO icon">
                                        <div class="abs bg-color z-2 top-0 w-100 h-100 hover-op-1"></div>
                                        <div class="abs z-2 bottom-0 mb-3 w-100 text-center hover-op-0">
                                            <h4 class="mb-3">{{ $category['name'] }}</h4>
                                        </div>
                                        <div class="gradient-trans-color-bottom abs w-100 h-40 bottom-0 z-1"></div>
                                    </div>

                                    <div class="p-4 py-2 flex-grow-1">
                                        <p class="mt-3">{{ $category['description'] }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="jarallax text-light relative">
            <img src="{{ asset('assets/images/background/8.webp') }}" class="jarallax-img"
                loading="lazy" decoding="async"
                alt="Hành trình phát triển GREECO">
            <div class="de-overlay" style="background: linear-gradient(90deg, rgba(30, 31, 34, 0.85) 0%, rgba(30, 31, 34, 0.45) 100%);"></div>
            <div class="container relative z-1">
                <div class="row g-4 gx-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="subtitle wow fadeInUp mb-3">Hành trình phát triển</div>
                        <h2 class="text-uppercase wow fadeInUp" data-wow-delay=".2s">Kiến tạo Giá trị Kinh tế Xanh <span
                                class="id-color-2">từ năm 2019</span></h2>
                        <p class="wow fadeInUp">Được thành lập bởi đội ngũ nhà khoa học, chuyên gia tâm huyết, Viện GREECO
                            đã và đang khẳng định vị thế tiên phong trong tư vấn chiến lược chuyển đổi xanh, thực hiện
                            nghiên cứu thực chứng và hỗ trợ cộng đồng doanh nghiệp đạt mục tiêu phát triển bền vững.</p>
                        <a class="btn-main btn-line wow fadeInUp" href="{{ route('projects.index') }}"
                            data-wow-delay=".6s">Dự án nghiên cứu</a>
                    </div>

                    <div class="col-lg-6">
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <div class="text-center p-4 bg-blur rounded-1">
                                            <h4 class="mb-1 fs-24">Uy tín & Khoa học</h4>
                                            <div class="de-rating-ext fs-18">
                                                <div class="d-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <div class="fs-15 mb-2">Đánh giá từ đối tác chiến lược</div>
                                                <img src="{{ asset('assets/images/misc/trustpilot.webp') }}" width="600" height="148" class="w-120px"
                                                    style="height: auto;" loading="lazy" decoding="async"
                                                    alt="Trustpilot rating GREECO">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="rounded-1 relative bg-blur p-4">
                                            <img src="{{ setting('icon_journey_tree') ? asset('storage/' . setting('icon_journey_tree')) : asset('assets/images/icons/tree.png') }}"
                                                loading="lazy" decoding="async"
                                                class="abs abs-middle w-60px" alt="Dự án thành công icon">
                                            <div class="de_count ps-80 wow fadeInUp">
                                                <h2 class="mb-0 fs-32"><span class="timer" data-to="550"
                                                        data-speed="3000"></span>+</h2>
                                                <span class="op-7">Dự án tư vấn thành công</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row g-4">
                                    <div class="spacer-single sm-hide"></div>
                                    <div class="col-lg-12">
                                        <div class="rounded-1 relative bg-blur p-4">
                                            <img src="{{ setting('icon_journey_happy') ? asset('storage/' . setting('icon_journey_happy')) : asset('assets/images/icons/happy.png') }}"
                                                loading="lazy" decoding="async"
                                                class="abs abs-middle w-60px" alt="Doanh nghiệp đồng hành icon">
                                            <div class="de_count ps-80 wow fadeInUp">
                                                <h2 class="mb-0 fs-32"><span class="timer" data-to="850"
                                                        data-speed="3000"></span>+</h2>
                                                <span class="op-7">Doanh nghiệp đồng hành</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-center p-4 bg-blur rounded-1">
                                            <h4 class="mb-1 fs-24">Xếp hạng 4.8/5</h4>
                                            <div class="de-rating-ext fs-18">
                                                <div class="d-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <div class="fs-15 mb-2">Khảo sát chất lượng dịch vụ</div>
                                                <img src="{{ asset('assets/images/misc/google.webp') }}" width="600" height="148" class="w-120px"
                                                    style="height: auto;" loading="lazy" decoding="async"
                                                    alt="Google review GREECO">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    <section class="jarallax text-light relative">
        <img src="{{ asset('assets/images/background/11.webp') }}" class="jarallax-img" alt="Dịch vụ khoa học GREECO"
            loading="lazy" decoding="async">
        <div class="de-overlay" style="background: rgba(30, 31, 34, 0.90);"></div>
        <div class="container relative z-2">
            <div class="row g-4">
                <div class="row g-4 mb-3 align-items-center justify-content-center">
                    <div class="col-xl-6 text-center">
                        <div class="subtitle wow fadeInUp">Dịch vụ khoa học</div>
                        <h2 class="text-uppercase wow fadeInUp" data-wow-delay=".2s">Dịch vụ & <span class="id-color">Tư vấn</span></h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center g-lg-4 gx-lg-5 wow fadeInUp">
                <div class="col-xl-8">
                    <div class="d-flex justify-content-between pb-3 mb-3 border-bottom border-white-op-1 align-items-center">
                        <div>
                            <h5 class="mb-1 text-white">Tư vấn Kiểm kê Khí nhà kính</h5>
                            <p class="mb-0 op-7 fs-14">Xác định phát thải carbon cấp cơ sở và xây dựng báo cáo giảm phát thải.</p>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-500 mb-0"><a href="{{ route('contact') }}" class="id-color text-decoration-none">Liên hệ <i class="fa-solid fa-arrow-right ms-1 fs-12"></i></a></h5>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pb-3 mb-3 border-bottom border-white-op-1 align-items-center">
                        <div>
                            <h5 class="mb-1 text-white">Đánh giá tác động môi trường (ĐTM)</h5>
                            <p class="mb-0 op-7 fs-14">Khảo sát hiện trạng và thực hiện hồ sơ pháp lý ĐTM toàn diện.</p>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-500 mb-0"><a href="{{ route('contact') }}" class="id-color text-decoration-none">Liên hệ <i class="fa-solid fa-arrow-right ms-1 fs-12"></i></a></h5>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pb-3 mb-3 border-bottom border-white-op-1 align-items-center">
                        <div>
                            <h5 class="mb-1 text-white">Tư vấn dự án Tín chỉ Carbon</h5>
                            <p class="mb-0 op-7 fs-14">Khảo sát khả thi, đăng ký và theo dõi phát hành chứng chỉ carbon.</p>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-500 mb-0"><a href="{{ route('contact') }}" class="id-color text-decoration-none">Liên hệ <i class="fa-solid fa-arrow-right ms-1 fs-12"></i></a></h5>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pb-3 mb-3 border-bottom border-white-op-1 align-items-center">
                        <div>
                            <h5 class="mb-1 text-white">Xây dựng Mô hình Kinh tế Tuần hoàn</h5>
                            <p class="mb-0 op-7 fs-14">Tư vấn giải pháp giảm thiểu rác thải, tuần hoàn tài nguyên tối ưu.</p>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-500 mb-0"><a href="{{ route('contact') }}" class="id-color text-decoration-none">Liên hệ <i class="fa-solid fa-arrow-right ms-1 fs-12"></i></a></h5>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pb-3 mb-3 border-bottom border-white-op-1 align-items-center">
                        <div>
                            <h5 class="mb-1 text-white">Đào tạo và lập báo cáo ESG</h5>
                            <p class="mb-0 op-7 fs-14">Nâng cao năng lực và hướng dẫn lập báo cáo ESG chuẩn GRI cho doanh nghiệp.</p>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-500 mb-0"><a href="{{ route('contact') }}" class="id-color text-decoration-none">Liên hệ <i class="fa-solid fa-arrow-right ms-1 fs-12"></i></a></h5>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pb-3 mb-3 border-bottom border-white-op-1 align-items-center">
                        <div>
                            <h5 class="mb-1 text-white">Chuyển giao công nghệ xử lý nước & rác</h5>
                            <p class="mb-0 op-7 fs-14">Giải pháp kỹ thuật hiện đại cho xử lý nước thải và rác thải hữu cơ.</p>
                        </div>
                        <div class="text-end">
                            <h5 class="fw-500 mb-0"><a href="{{ route('contact') }}" class="id-color text-decoration-none">Liên hệ <i class="fa-solid fa-arrow-right ms-1 fs-12"></i></a></h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 text-center">
                    <a class="btn-main wow fadeInUp" href="{{ route('services.tu-van') }}">Xem thêm dịch vụ</a>
                </div>

            </div>
        </div>
    </section>

    <!-- Dynamic Latest Posts Section -->
    @if($latestPosts->isNotEmpty())
        <section class="bg-light">
            <div class="container">
                <div class="row g-4 mb-3 align-items-center justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="subtitle wow fadeInUp">Tin tức mới nhất</div>
                        <h2 class="text-uppercase wow fadeInUp" data-wow-delay=".2s">Tin tức & <span class="id-color-2">Sự
                                kiện</span></h2>
                    </div>
                </div>
                <div class="row g-4">
                    @foreach($latestPosts as $post)
                        @php
                            $day = $post->published_at ? $post->published_at->format('d') : $post->created_at->format('d');
                            $monthNum = $post->published_at ? $post->published_at->format('n') : $post->created_at->format('n');
                            $monthStr = 'Th' . $monthNum;
                            $postImg = $post->thumbnail ? asset('storage/' . $post->thumbnail) : asset('assets/images/blog/' . (($loop->index % 3) + 1) . '.webp');
                        @endphp
                        <!-- post -->
                        <div class="col-lg-6 wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
                            <div class="rounded-1 bg-light overflow-hidden blog-post-card">
                                <div class="row g-2 h-100">
                                    <div class="col-sm-6">
                                        <div class="blog-post-img-container relative" data-bgimage="url({{ $postImg }})">
                                            <div class="abs start-0 top-0 bg-color-2 text-white p-3 pb-2 m-3 text-center fw-600 rounded-5px">
                                                <div class="fs-36 mb-0">{{ $day }}</div>
                                                <span>{{ $monthStr }}</span>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="col-sm-6 relative">
                                        <div class="p-30 pb-40">
                                            <h4><a class="text-dark" href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h4>
                                            <p class="mb-0 fs-14 text-muted">{{ Str::limit($post->excerpt, 120) }}</p>
                                        </div>
                                    </div>                             
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="bg-light pt-0">
        <div class="container">
            <div class="row g-4 mb-3 align-items-center justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="subtitle wow fadeInUp">Tại sao chọn GREECO</div>
                    <h2 class="text-uppercase wow fadeInUp" data-wow-delay=".2s">Cam kết Hành động vì <span
                            class="id-color-2">Tương lai Xanh</span></h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 wow fadeInUp">
                    <div class="relative h-100 bg-color text-light padding30 rounded-1">
                        <img src="{{ setting('logo_icon') ? asset('storage/' . setting('logo_icon')) : asset('assets/images/logo-icon.webp') }}" width="166" height="166" class="w-50px mb-3" style="height: auto;" alt="GREECO Icon" loading="lazy" decoding="async">
                        <div class="abs m-3 top-0 end-0 p-2 rounded-2 mb-3">01</div>
                        <div>
                            <h4>Đội ngũ chuyên gia đầu ngành</h4>
                            <p class="mb-0">Quy tụ các nhà khoa học, giáo sư, tiến sĩ và chuyên gia giàu kinh nghiệm thực
                                tiễn trong lĩnh vực môi trường và kinh tế.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 wow fadeInUp">
                    <div class="relative h-100 bg-color text-light padding30 rounded-1">
                        <img src="{{ setting('logo_icon') ? asset('storage/' . setting('logo_icon')) : asset('assets/images/logo-icon.webp') }}" width="166" height="166" class="w-50px mb-3" style="height: auto;" alt="GREECO Icon" loading="lazy" decoding="async">
                        <div class="abs m-3 top-0 end-0 p-2 rounded-2 mb-3">02</div>
                        <div>
                            <h4>Giải pháp thiết thực & Tối ưu</h4>
                            <p class="mb-0">Chúng tôi nghiên cứu và thiết kế các giải pháp tối ưu, phù hợp nhất với điều
                                kiện đặc thù của từng địa phương và doanh nghiệp.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 wow fadeInUp">
                    <div class="relative h-100 bg-color text-light padding30 rounded-1">
                        <img src="{{ setting('logo_icon') ? asset('storage/' . setting('logo_icon')) : asset('assets/images/logo-icon.webp') }}" width="166" height="166" class="w-50px mb-3" style="height: auto;" alt="GREECO Icon" loading="lazy" decoding="async">
                        <div class="abs m-3 top-0 end-0 p-2 rounded-2 mb-3">03</div>
                        <div>
                            <h4>Dịch vụ khoa học toàn diện</h4>
                            <p class="mb-0">Cung cấp trọn gói từ nghiên cứu lý thuyết, chuyển giao công nghệ đến hỗ trợ
                                triển khai thực tế.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 wow fadeInUp">
                    <div class="relative h-100 bg-color-2 text-light padding30 rounded-1">
                        <img src="{{ setting('logo_icon') ? asset('storage/' . setting('logo_icon')) : asset('assets/images/logo-icon.webp') }}" width="166" height="166" class="w-50px mb-3" style="height: auto;" alt="GREECO Icon" loading="lazy" decoding="async">
                        <div class="abs m-3 top-0 end-0 p-2 rounded-2 mb-3">04</div>
                        <div>
                            <h4>Bảo chứng chất lượng khoa học</h4>
                            <p class="mb-0">Mọi sản phẩm tư vấn của GREECO đều dựa trên nền tảng nghiên cứu thực chứng và số
                                liệu chính xác.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 wow fadeInUp">
                    <div class="relative h-100 bg-color-2 text-light padding30 rounded-1">
                        <img src="{{ setting('logo_icon') ? asset('storage/' . setting('logo_icon')) : asset('assets/images/logo-icon.webp') }}" width="166" height="166" class="w-50px mb-3" style="height: auto;" alt="GREECO Icon" loading="lazy" decoding="async">
                        <div class="abs m-3 top-0 end-0 p-2 rounded-2 mb-3">05</div>
                        <div>
                            <h4>Mô hình phát triển bền vững</h4>
                            <p class="mb-0">Thúc đẩy tăng trưởng xanh thông qua các mô hình thực tiễn giúp bảo vệ và phục
                                hồi tài nguyên thiên nhiên.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 wow fadeInUp">
                    <div class="relative h-100 bg-color-2 text-light padding30 rounded-1">
                        <img src="{{ setting('logo_icon') ? asset('storage/' . setting('logo_icon')) : asset('assets/images/logo-icon.webp') }}" width="166" height="166" class="w-50px mb-3" style="height: auto;" alt="GREECO Icon" loading="lazy" decoding="async">
                        <div class="abs m-3 top-0 end-0 p-2 rounded-2 mb-3">06</div>
                        <div>
                            <h4>Cam kết chất lượng</h4>
                            <p class="mb-0">Sự tin cậy và phát triển bền vững của đối tác là ưu tiên hàng đầu của chúng tôi.
                                Những dự án thành công là minh chứng rõ nét cho năng lực và chất lượng dịch vụ của Viện.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="jarallax text-light relative">
        <img src="{{ asset('assets/images/background/4.webp') }}" class="jarallax-img" alt="Đánh giá đối tác GREECO"
            loading="lazy" decoding="async">
        <div class="de-overlay"></div>
        <div class="container relative z-2">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <div class="owl-single-dots owl-carousel owl-theme">
                        <div class="item">
                            <i class="icofont-quote-left fs-40 mb-4 wow fadeInUp id-color-2"></i>
                            <h3 class="mb-4 wow fadeInUp fs-32">Chúng tôi đánh giá rất cao sự chuyên nghiệp và kiến thức
                                chuyên sâu của Viện GREECO trong việc tư vấn lộ trình Net Zero và ESG. Giải pháp của Viện
                                rất thiết thực, hiệu quả và dễ dàng tích hợp vào hệ thống của chúng tôi.</h3>
                            <span>ThS. Lê Hoàng Nam</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row g-4 align-items-center justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="subtitle wow fadeInUp">Dự án & Công trình</div>
                    <h2 class="text-uppercase mb-4 wow fadeInUp" data-wow-delay=".2s">Nghiên cứu & <span
                            class="id-color-2">Dự án Mới</span></h2>
                </div>
            </div>

            <div class="row g-4">
                @if($featuredProjects->isNotEmpty())
                    @foreach($featuredProjects as $project)
                        <div class="col-lg-6">
                            <div class="hover project-card-custom rounded-1 overflow-hidden relative text-light wow fadeInRight"
                                data-wow-delay="{{ ($loop->index % 2) * 0.3 }}s">
                                <a href="{{ route('projects.show', $project) }}" class="abs w-100 h-100 z-5"></a>
                                <img src="{{ $project->thumbnail ? asset('storage/' . $project->thumbnail) : asset('assets/images/projects/' . (($loop->index % 2) + 1) . '.jpg') }}"
                                     width="1024" height="1024" class="hover-scale-1-1 w-100" style="height: auto;" alt="{{ $project->title }}" loading="lazy" decoding="async">
                                <div class="abs w-100 px-4 hover-op-1 z-4 hover-mt-40 abs-centered">
                                    <div class="mb-3">{{ Str::limit($project->excerpt, 150) }}</div>
                                </div>
                                <div class="abs bg-color z-2 top-0 w-100 h-100 hover-op-1"></div>
                                <div class="abs z-2 bottom-0 w-100 hover-op-0">
                                    <div class="bg-blur d-flex m-4 p-3 px-4 rounded-1 justify-content-between align-items-center">
                                        <div class="d-flex">
                                            <div class="me-5">
                                                Dự án
                                                <h5>{{ $project->title }}</h5>
                                            </div>
                                            <div>
                                                Địa điểm
                                                <h5>{{ $project->location ?: 'GREECO' }}</h5>
                                            </div>
                                        </div>

                                        <div class="w-40px">
                                            <img src="{{ asset('assets/images/misc/right-arrow.webp') }}" width="512" height="258" class="w-100" style="height: auto;" alt="arrow" loading="lazy" decoding="async">
                                        </div>
                                    </div>
                                </div>
                                <div class="gradient-trans-color-bottom abs w-100 h-40 bottom-0"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback static projects -->
                    <div class="col-lg-6">
                        <div class="hover project-card-custom rounded-1 overflow-hidden relative text-light wow fadeInRight" data-wow-delay=".3s">
                            <a href="{{ route('projects.index') }}" class="abs w-100 h-100 z-5"></a>
                            <img src="{{ asset('assets/images/projects/1.jpg') }}" width="1024" height="1024" class="hover-scale-1-1 w-100"
                                style="height: auto;" loading="lazy" decoding="async"
                                alt="Ứng dụng Kinh tế Tuần hoàn">
                            <div class="abs bg-color z-2 top-0 w-100 h-100 hover-op-1"></div>
                            <div class="abs z-2 bottom-0 w-100 hover-op-0">
                                <div class="bg-blur d-flex m-4 p-3 px-4 rounded-1 justify-content-between align-items-center">
                                    <div class="d-flex">
                                        <div class="me-5">
                                            Dự án
                                            <h5>Ứng dụng Kinh tế Tuần hoàn</h5>
                                        </div>
                                        <div>
                                            Địa điểm
                                            <h5>Dự án Cát Lái, TP. HCM</h5>
                                        </div>
                                    </div>
                                    <div class="w-40px">
                                        <img src="{{ asset('assets/images/misc/right-arrow.webp') }}" width="512" height="258" class="w-100" style="height: auto;" alt="arrow" loading="lazy" decoding="async">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-lg-12 text-center">
                    <a class="btn-main wow fadeInUp" href="{{ route('projects.index') }}">Xem tất cả dự án</a>
                </div>

            </div>
        </div>
    </section>

    <!-- Dynamic Partners Logos Marquee -->
    @if($partners->isNotEmpty())
        <section class="py-5 bg-white border-top border-bottom partners-marquee-section">
            <div class="container-fluid px-0">
                <div class="text-center mb-4 wow fadeInUp px-3">
                    <div class="subtitle">Đối tác của Greeco</div>
                    <h4 class="text-uppercase id-color-2 wow fadeInUp" data-wow-delay=".2s">Đối tác & Khách hàng đồng hành</h4>
                </div>
                <div class="partners-track-wrapper">
                    {{-- Render 3 lần để tạo vòng lặp liền mạch dù ít hay nhiều partners --}}
                    @foreach([1, 2, 3] as $repeat)
                        <div class="partners-track" aria-hidden="{{ $repeat > 1 ? 'true' : 'false' }}">
                            @foreach($partners as $partner)
                                <div class="partner-item">
                                    @if($partner->website_url)
                                        <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer"
                                            class="partner-logo-link" aria-label="Truy cập website {{ $partner->name }}">
                                    @else
                                        <div class="partner-logo-link">
                                    @endif
                                            <img src="{{ asset('storage/' . $partner->logo) }}" class="partner-logo-img"
                                                alt="{{ $partner->name }}" title="{{ $partner->name }}" loading="lazy" decoding="async">
                                    @if($partner->website_url)
                                        </a>
                                    @else
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    </div>
@endsection
