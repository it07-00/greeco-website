<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
$faviconValue = setting('favicon');
$faviconPath = $faviconValue ? 'storage/' . $faviconValue : 'assets/images/favicon.png';
if (str_ends_with($faviconPath, '.webp')) {
    $pngPath = str_replace('.webp', '.png', $faviconPath);
    if (file_exists(public_path($pngPath))) {
        $faviconUrl = asset($pngPath);
    } else {
        $faviconUrl = asset('assets/images/favicon.png');
    }
} else {
    $faviconUrl = asset($faviconPath);
}
$faviconVersion = is_file(public_path($faviconPath)) ? filemtime(public_path($faviconPath)) : '2';
    ?>
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}?v={{ $faviconVersion }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}?v={{ $faviconVersion }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ $faviconVersion }}">

    @php
        $defaultSiteOgImage = setting('og_image')
            ? asset('storage/' . setting('og_image'))
            : asset('assets/images/og-default.jpg');
    @endphp

    <x-seo
        :title="$seoTitle ?? __('Viện Nghiên cứu và Phát triển Kinh tế Xanh - GREECO')"
        :description="$seoDescription ?? __('Viện Nghiên cứu và Phát triển Kinh tế Xanh (GREECO) - Đơn vị hàng đầu về chuyển giao công nghệ xanh, kinh tế tuần hoàn và ứng phó biến đổi khí hậu.')"
        :canonical="$canonical ?? url()->current()"
        :image="$ogImage ?? $defaultSiteOgImage"
        :indexable="$indexable ?? true"
        :type="$seoType ?? 'website'"
        :published-time="$seoPublishedTime ?? null"
        :modified-time="$seoModifiedTime ?? null"
        :section="$seoSection ?? null"
        :tags="$seoTags ?? []"
    />
    @stack('seo')

    @stack('vendor-styles')

    <link rel="preload" href="{{ asset('assets/fonts/be-vietnam-pro/BeVietnamPro-Regular.ttf') }}" as="font"
        type="font/ttf" crossorigin>
    <link rel="preload" href="{{ asset('assets/fonts/be-vietnam-pro/BeVietnamPro-Bold.ttf') }}" as="font"
        type="font/ttf" crossorigin>

    <link rel="preload" href="{{ versioned_asset('assets/css/bootstrap.min.css') }}" as="style">
    <link rel="preload" href="{{ versioned_asset('assets/css/style.css') }}" as="style">
    <link rel="preload" href="{{ versioned_asset('assets/css/custom.css') }}" as="style">

    @stack('preloads')

    <!-- CSS Files ================================================== -->
    <link href="{{ versioned_asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bootstrap">
    <link rel="preload" href="{{ versioned_asset('assets/css/plugins.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="{{ versioned_asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css">
    </noscript>
    <link rel="preload" href="{{ versioned_asset('assets/fonts/fontawesome4/css/font-awesome.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ versioned_asset('assets/fonts/fontawesome6/css/fontawesome.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ versioned_asset('assets/fonts/fontawesome6/css/brands.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ versioned_asset('assets/fonts/fontawesome6/css/solid.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ versioned_asset('assets/fonts/elegant_font/HTML_CSS/style.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ versioned_asset('assets/fonts/et-line-font/style.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ versioned_asset('assets/fonts/icofont/icofont.min.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="{{ versioned_asset('assets/fonts/fontawesome4/css/font-awesome.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ versioned_asset('assets/fonts/fontawesome6/css/fontawesome.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ versioned_asset('assets/fonts/fontawesome6/css/brands.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ versioned_asset('assets/fonts/fontawesome6/css/solid.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ versioned_asset('assets/fonts/elegant_font/HTML_CSS/style.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ versioned_asset('assets/fonts/et-line-font/style.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ versioned_asset('assets/fonts/icofont/icofont.min.css') }}" rel="stylesheet" type="text/css">
    </noscript>
    <link href="{{ versioned_asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link rel="preload" href="{{ versioned_asset('assets/css/coloring.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" id="colors" href="{{ versioned_asset('assets/css/colors/scheme-01.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="{{ versioned_asset('assets/css/coloring.css') }}" rel="stylesheet" type="text/css">
        <link id="colors-noscript" href="{{ versioned_asset('assets/css/colors/scheme-01.css') }}" rel="stylesheet" type="text/css">
    </noscript>
    <link href="{{ versioned_asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css">

    @stack('styles')
</head>

<body>
    <div id="wrapper">
        <a href="#" id="back-to-top" aria-label="{{ __('Cuộn lên đầu trang') }}"></a>

        <!-- Floating Contact Buttons -->
        @php
            $rawPhone = setting('phone', '09369 96390');
            $phoneName = setting('phone_name');
            $cleanPhone = preg_replace('/\D/', '', $rawPhone);
            $rawPhone2 = setting('phone_2');
            $phone2Name = setting('phone_2_name');
            $cleanPhone2 = $rawPhone2 ? preg_replace('/\D/', '', $rawPhone2) : null;

            $zaloUrl = setting('zalo_url');
            if (!$zaloUrl) {
                $zaloUrl = 'https://zalo.me/' . $cleanPhone;
            }

            $messengerUrl = setting('messenger_url');
            if (!$messengerUrl) {
                $fbUrl = setting('facebook_url');
                if ($fbUrl && preg_match('/facebook\.com\/([a-zA-Z0-9\.]+)/', $fbUrl, $matches)) {
                    $messengerUrl = 'https://m.me/' . $matches[1];
                } else {
                    $messengerUrl = 'https://m.me/greecoofficial';
                }
            }
        @endphp
        <div class="floating-contact-buttons">
            <a href="{{ $messengerUrl }}" target="_blank" rel="noopener noreferrer" class="contact-btn messenger-btn"
                data-tooltip="Chat Messenger" aria-label="Messenger">
                <i class="fa-brands fa-facebook-messenger"></i>
            </a>
            <a href="{{ $zaloUrl }}" target="_blank" rel="noopener noreferrer" class="contact-btn zalo-btn"
                data-tooltip="Chat Zalo" aria-label="Zalo">
                <svg viewBox="0 0 24 24" width="26" height="26">
                    <path
                        d="M12 2C6.5 2 2 6 2 11c0 2.3 1.1 4.4 2.8 6L3.5 21c-.3.5.3 1 1 .8l4.2-2.1c1.2.4 2.2.8 3.3.8 5.5 0 10-4 10-9s-4.5-9-10-9z"
                        fill="#ffffff" />
                    <text x="12" y="15" font-family="'Inter', sans-serif" font-weight="900" font-size="12"
                        fill="#0068ff" text-anchor="middle">Z</text>
                </svg>
            </a>
            @if($cleanPhone2)
                <div class="contact-btn phone-btn multi-phone-btn" data-tooltip="{{ __('Gọi điện thoại') }}" aria-label="{{ __('Gọi điện thoại') }}" tabindex="0">
                    <span class="pulse-ring"></span>
                    <i class="fa-solid fa-phone"></i>
                    <div class="phone-submenu">
                        <a href="tel:{{ $cleanPhone }}" class="submenu-item">
                            <i class="fa-solid fa-phone-volume me-2"></i> {{ $rawPhone }}{{ $phoneName ? ' - ' . $phoneName : '' }}
                        </a>
                        <a href="tel:{{ $cleanPhone2 }}" class="submenu-item">
                            <i class="fa-solid fa-phone-volume me-2"></i> {{ $rawPhone2 }}{{ $phone2Name ? ' - ' . $phone2Name : '' }}
                        </a>
                    </div>
                </div>
            @else
                <a href="tel:{{ $cleanPhone }}" class="contact-btn phone-btn" data-tooltip="{{ __('Gọi điện thoại') }}"
                    aria-label="{{ __('Gọi điện thoại') }}">
                    <span class="pulse-ring"></span>
                    <i class="fa-solid fa-phone"></i>
                </a>
            @endif
        </div>

        <x-header />

        <!-- content begin -->
        <main id="main-content">
            @yield('content')
        </main>
        <!-- content end -->

        <x-footer />
    </div>

    <x-extra-wrap />

    <!-- Javascript Files ================================================== -->
    <script src="{{ versioned_asset('assets/js/plugins.js') }}" defer></script>
    <script src="{{ versioned_asset('assets/js/designesia.js') }}" defer></script>
    @vite('resources/js/app.js')

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var multiPhoneBtn = document.querySelector('.multi-phone-btn');
            if (multiPhoneBtn) {
                // Toggle active class on click for touch support
                multiPhoneBtn.addEventListener('click', function(e) {
                    if (e.target.closest('.submenu-item')) {
                        return; // Let the link click go through
                    }
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.toggle('active');
                });
                
                // Close menu if clicked outside
                document.addEventListener('click', function() {
                    multiPhoneBtn.classList.remove('active');
                });
            }
        });
    </script>
</body>

</html>
