<!-- footer begin -->
<footer class="footer-light">
    <div class="container relative z-2">
        <div class="row gx-5">
            <div class="col-lg-3 col-sm-12">
                <div class="footer-logo-container">
                    <img src="{{ setting('logo_dark') ? asset('storage/' . setting('logo_dark')) : asset('assets/images/logo-text-cropped.png') }}"
                        width="678" height="222" class="footer-logo-text" alt="GREECO Logo">
                </div>
                <div class="spacer-20"></div>
                <p>Viện Nghiên cứu và Phát triển Kinh tế Xanh (GREECO) là đơn vị khoa học công nghệ hàng đầu, chuyên
                    nghiên cứu, tư vấn và chuyển giao các giải pháp phát triển bền vững, kinh tế tuần hoàn và ứng phó
                    biến đổi khí hậu.</p>

                <div class="social-icons mb-sm-30">
                    <a href="{{ setting('facebook_url', 'https://www.facebook.com/greecoofficial?locale=vi_VN') }}" aria-label="GREECO trên Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
                    <a href="{{ setting('twitter_url', '#') }}" aria-label="GREECO trên X (Twitter)"><i class="fa-brands fa-x-twitter" aria-hidden="true"></i></a>
                    <a href="{{ setting('instagram_url', '#') }}" aria-label="GREECO trên Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                    <a href="{{ setting('youtube_url', '#') }}" aria-label="GREECO trên YouTube"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a>
                    <a href="{{ setting('whatsapp_url', '#') }}" aria-label="Liên hệ GREECO qua WhatsApp"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-sm-12">
                <div class="row">
                    <div class="col-6">
                        <div class="widget">
                            <h5>Giới thiệu</h5>
                            <ul>
                                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                                <li><a href="{{ route('about') }}">Giới thiệu</a></li>
                                <li><a href="{{ route('capability-profile') }}">Hồ sơ năng lực</a></li>
                                <li><a href="{{ route('services.index') }}">Lĩnh vực hoạt động</a></li>
                                <li><a href="{{ route('projects.index') }}">Dự án nghiên cứu</a></li>
                                <li><a href="{{ route('posts.index') }}">Tin tức</a></li>
                                <li><a href="{{ route('contact') }}">Liên hệ</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="widget">
                            <h5>Lĩnh vực</h5>
                            <ul>
                                <li><a href="{{ route('services.nghien-cuu') }}">Nghiên cứu khoa học</a></li>
                                <li><a href="{{ route('services.dao-tao') }}">Đào tạo & Bồi dưỡng</a></li>
                                <li><a href="{{ route('services.tu-van') }}">Tư vấn môi trường</a></li>
                                <li><a href="{{ route('services.du-an') }}">Dự án tăng trưởng xanh</a></li>
                                <li><a href="{{ route('services.hoi-thao') }}">Hội thảo & Truyền thông</a></li>
                                <li><a href="{{ route('library') }}">Văn bản pháp luật</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-sm-12">
                <div class="row g-4">
                    <div class="col-sm-5 col-12">
                        <div class="widget">
                            <h5>Liên hệ</h5>
                            <div class="fw-bold text-dark"><i class="icofont-clock-time me-2 id-color-2"></i>Thời gian
                                làm việc</div>
                            {{ setting('work_hours', 'Thứ 2 - Thứ 7: 08:00 - 17:00') }}

                            <div class="spacer-20"></div>

                            <div class="fw-bold text-dark"><i class="icofont-location-pin me-2 id-color-2"></i>Địa chỉ
                                văn phòng</div>
                            {{ setting('address', '150 Đường 38-CL, Phường Cát Lái, TP. HCM') }}

                            <div class="spacer-20"></div>

                            <div class="fw-bold text-dark"><i class="icofont-envelope me-2 id-color-2"></i>Liên hệ qua
                                Email</div>
                            <a href="mailto:{{ setting('email', 'info@greeco.vn') }}"
                                class="text-dark text-decoration-none hover-underline d-block">{{ setting('email',
                                'info@greeco.vn') }}</a>

                            <div class="spacer-20"></div>

                            <div class="fw-bold text-dark"><i class="icofont-phone me-2 id-color-2"></i>Hotline</div>
                            <a href="tel:{{ str_replace(' ', '', setting('phone', '0936996390')) }}"
                                class="text-dark text-decoration-none hover-underline d-block hotline-link">
                                @if(setting('phone_name'))
                                    <span class="hotline-person">{{ setting('phone_name') }}</span>
                                @endif
                                <span>{{ setting('phone', '09369 96390') }}</span>
                            </a>
                            @if(setting('phone_2'))
                                <a href="tel:{{ str_replace(' ', '', setting('phone_2')) }}"
                                    class="text-dark text-decoration-none hover-underline d-block mt-1 hotline-link">
                                    @if(setting('phone_2_name'))
                                        <span class="hotline-person">{{ setting('phone_2_name') }}</span>
                                    @endif
                                    <span>{{ setting('phone_2') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-7 col-12">
                        <div class="widget">
                            <h5>Bản đồ đường đi</h5>
                            <div class="footer-map-container">
                                <iframe
                                    title="Bản đồ vị trí Viện GREECO"
                                    src="https://maps.google.com/maps?q={{ urlencode(setting('address', '150 Đường 38-CL, Phường Cát Lái, TP. HCM')) }}&t=&z=15&ie=UTF8&iwloc=&output=embed"
                                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="subfooter">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="de-flex">
                        <div class="de-flex-col">
                            &copy; 2026 Viện Nghiên cứu và Phát triển Kinh tế Xanh - GREECO. Bảo lưu mọi quyền.
                        </div>
                        <ul class="menu-simple">
                            <li><a href="#">Điều khoản sử dụng</a></li>
                            <li><a href="#">Chính sách bảo mật</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer end -->
