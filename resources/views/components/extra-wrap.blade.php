<!-- overlay content begin -->
<div id="extra-wrap" class="text-light">
    <div id="btn-close">
        <span></span>
        <span></span>
    </div>

    <div id="extra-content">
        <div class="extra-logo-container">
            <img src="{{ setting('logo_light') ? asset('storage/' . setting('logo_light')) : asset('assets/images/logo-text-white-cropped.png') }}" width="678" height="222" class="extra-logo-text" alt="GREECO Logo">
        </div>

        <div class="spacer-30-line"></div>

        <h5>{{ __('Lĩnh vực hoạt động') }}</h5>
        <ul class="no-style">
            <li><a href="{{ website_route('services.dao-tao') }}">{{ __('Đào tạo & Bồi dưỡng') }}</a></li>
            <li><a href="{{ website_route('services.tu-van') }}">{{ __('Dịch vụ Tư vấn') }}</a></li>
            <li><a href="{{ website_route('services.du-an') }}">{{ __('Phát triển Dự án') }}</a></li>
            <li><a href="{{ website_route('services.nghien-cuu') }}">{{ __('Nghiên cứu và Chuyển giao Công nghệ') }}</a></li>
            <li><a href="{{ website_route('services.hoi-thao') }}">{{ __('Hội thảo & Truyền thông') }}</a></li>
            <li><a href="{{ website_route('library') }}">{{ __('Văn bản pháp luật') }}</a></li>
        </ul>

        <div class="spacer-30-line"></div>

        <h5>{{ __('Liên hệ') }}</h5>
        <div><i class="icofont-clock-time me-2 op-5"></i>{{ setting('work_hours', __('Thứ 2 - Thứ 7: 08:00 - 17:00')) }}</div>
        <div><i class="icofont-location-pin me-2 op-5"></i>{{ setting('address', __('150 Đường 38-CL, Phường Cát Lái, TP. HCM')) }}</div>
        <div><i class="icofont-envelope me-2 op-5"></i><a href="mailto:{{ setting('email', 'info@greeco.vn') }}" class="text-white">{{ setting('email', 'info@greeco.vn') }}</a></div>    

        <div class="spacer-30-line"></div>

        <h5>{{ __('Về GREECO') }}</h5>
        <p>{{ __('Viện Nghiên cứu và Phát triển Kinh tế Xanh (GREECO) là đơn vị khoa học công nghệ hàng đầu, chuyên nghiên cứu, tư vấn và chuyển giao các giải pháp phát triển bền vững, kinh tế tuần hoàn và ứng phó biến đổi khí hậu.') }}</p>

        <div class="social-icons">
            <a href="{{ setting('facebook_url', 'https://www.facebook.com/greecoofficial?locale=vi_VN') }}" aria-label="{{ __('GREECO trên Facebook') }}"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
            <a href="{{ setting('twitter_url', '#') }}" aria-label="{{ __('GREECO trên X (Twitter)') }}"><i class="fa-brands fa-x-twitter" aria-hidden="true"></i></a>
            <a href="{{ setting('instagram_url', '#') }}" aria-label="{{ __('GREECO trên Instagram') }}"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
            <a href="{{ setting('youtube_url', '#') }}" aria-label="{{ __('GREECO trên YouTube') }}"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a>
            <a href="{{ setting('whatsapp_url', '#') }}" aria-label="{{ __('Liên hệ GREECO qua WhatsApp') }}"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></a>
        </div>
    </div>
</div>
<!-- overlay content end -->
