<!-- header begin -->
<header class="transparent scroll-light">
    <div id="topbar">
        <div class="container-fluid px-custom px-3">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between xs-hide">
                        <div class="d-flex">
                            <div class="topbar-widget me-3"><a href="#"><i
                                        class="icofont-clock-time"></i>{{ setting('work_hours', __('Thứ 2 - Thứ 7: 08:00 - 17:00')) }}</a>
                            </div>
                            <div class="topbar-widget me-3"><a href="#"><i
                                        class="icofont-location-pin"></i>{{ setting('address', __('150 Đường 38-CL, Phường Cát Lái, TP. HCM')) }}</a>
                            </div>
                            <div class="topbar-widget me-3"><a href="mailto:{{ setting('email', 'info@greeco.vn') }}"><i
                                        class="icofont-envelope"></i>{{ setting('email', 'info@greeco.vn') }}</a></div>
                        </div>

                        <div class="d-flex">
                            <div class="social-icons">
                                <a href="{{ setting('facebook_url', 'https://www.facebook.com/greecoofficial?locale=vi_VN') }}"
                                    aria-label="{{ __('GREECO trên Facebook') }}"><i class="fa-brands fa-facebook fa-lg"
                                        aria-hidden="true"></i></a>
                                <a href="{{ setting('twitter_url', '#') }}" aria-label="{{ __('GREECO trên X (Twitter)') }}"><i
                                        class="fa-brands fa-x-twitter fa-lg" aria-hidden="true"></i></a>
                                <a href="{{ setting('youtube_url', '#') }}" aria-label="{{ __('GREECO trên YouTube') }}"><i
                                        class="fa-brands fa-youtube fa-lg" aria-hidden="true"></i></a>
                                <a href="{{ setting('pinterest_url', '#') }}" aria-label="{{ __('GREECO trên Pinterest') }}"><i
                                        class="fa-brands fa-pinterest fa-lg" aria-hidden="true"></i></a>
                                <a href="{{ setting('instagram_url', '#') }}" aria-label="{{ __('GREECO trên Instagram') }}"><i
                                        class="fa-brands fa-instagram fa-lg" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="container-fluid px-custom px-3">
        <div class="row">
            <div class="col-md-12">
                <div class="de-flex sm-pt10">
                    <div class="de-flex-col">
                        <div id="logo">
                            <a href="{{ website_route('home') }}" class="logo-container">
                                <div class="logo-text-wrap">
                                    <img class="logo-main"
                                        src="{{ setting('logo_light') ? asset('storage/' . setting('logo_light')) : asset('assets/images/logo-text-white-cropped.png') }}"
                                        width="678" height="222" style="height: auto;" alt="GREECO logo">
                                    <img class="logo-scroll"
                                        src="{{ setting('logo_dark') ? asset('storage/' . setting('logo_dark')) : asset('assets/images/logo-text-cropped.png') }}"
                                        width="678" height="222" style="height: auto;" alt="GREECO logo">
                                    <img class="logo-mobile"
                                        src="{{ setting('logo_light') ? asset('storage/' . setting('logo_light')) : asset('assets/images/logo-text-white-cropped.png') }}"
                                        width="678" height="222" style="height: auto;" alt="GREECO logo">
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="de-flex-col header-col-mid">
                        <!-- mainmenu begin -->
                        <ul id="mainmenu">
                            <li><a class="menu-item" href="{{ website_route('home') }}">{{ __('Trang chủ') }}</a></li>
                            <li><a class="menu-item" href="{{ website_route('about') }}">{{ __('Giới thiệu') }}</a>
                                <ul>
                                    <li><a href="{{ website_route('about') }}">{{ __('Giới thiệu') }}</a></li>
                                    <li><a href="{{ website_route('team') }}">{{ __('Ban lãnh đạo & Chuyên gia') }}</a></li>
                                    <li><a href="{{ website_route('capability-profile') }}">{{ __('Hồ sơ năng lực') }}</a></li>
                                    <li><a href="{{ website_route('gallery') }}">{{ __('Thư viện ảnh') }}</a></li>
                                </ul>
                            </li>
                            <li><a class="menu-item" href="{{ website_route('posts.index') }}">{{ __('Tin tức') }}</a></li>
                            <li><a class="menu-item" href="{{ website_route('services.index') }}">{{ __('Lĩnh vực hoạt động') }}</a>
                                <ul>
                                    <li><a href="{{ website_route('services.dao-tao') }}">{{ __('Đào tạo & Bồi dưỡng') }}</a></li>
                                    <li><a href="{{ website_route('services.tu-van') }}">{{ __('Dịch vụ Tư vấn') }}</a></li>
                                    <li><a href="{{ website_route('services.du-an') }}">{{ __('Phát triển Dự án') }}</a></li>
                                    <li><a href="{{ website_route('services.nghien-cuu') }}">{{ __('Nghiên cứu và Chuyển giao Công nghệ') }}</a></li>
                                    <li><a href="{{ website_route('services.hoi-thao') }}">{{ __('Hội thảo & Truyền thông') }}</a></li>
                                </ul>
                            </li>
                            <li><a class="menu-item" href="{{ website_route('library') }}">{{ __('Văn bản pháp luật') }}</a></li>
                            <li><a class="menu-item" href="{{ website_route('projects.index') }}">{{ __('Dự án & Nghiên cứu') }}</a></li>
                            <li><a class="menu-item" href="{{ website_route('contact') }}">{{ __('Liên hệ') }}</a></li>
                        </ul>
                        <!-- mainmenu end -->
                    </div>
                    <div class="de-flex-col">
                        <div class="menu_side_area">
                            <x-language-switcher />
                            <a href="{{ website_route('contact') }}" class="btn-main btn-line">{{ __('Liên hệ ngay') }}</a>
                            <span id="menu-btn"></span>
                        </div>

                        <div id="btn-extra">
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header end -->
