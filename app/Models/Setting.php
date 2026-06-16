<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    protected $appends = [
        'label',
        'description',
    ];

    public function getLabelAttribute(): string
    {
        return self::getSettingInfo($this->key)['label'];
    }

    public function getDescriptionAttribute(): string
    {
        return self::getSettingInfo($this->key)['description'];
    }

    public static function getSettingInfo(string $key): array
    {
        $info = [
            'site_name' => [
                'label' => 'Tên website',
                'description' => 'Tên chính thức của website/thương hiệu hiển thị trên thanh trình duyệt và SEO.',
            ],
            'phone' => [
                'label' => 'Số điện thoại hotline',
                'description' => 'Hotline liên hệ hiển thị ở Header, Footer và nút gọi nhanh.',
            ],
            'email' => [
                'label' => 'Địa chỉ email',
                'description' => 'Email chính thức nhận liên hệ và hiển thị trên website.',
            ],
            'address' => [
                'label' => 'Địa chỉ văn phòng',
                'description' => 'Địa chỉ trụ sở văn phòng hiển thị ở Footer và trang Liên hệ.',
            ],
            'work_hours' => [
                'label' => 'Thời gian làm việc',
                'description' => 'Thời gian làm việc hiển thị ở Footer và thanh Slideout.',
            ],
            'facebook_url' => [
                'label' => 'Link Fanpage Facebook',
                'description' => 'Đường dẫn đầy đủ tới Fanpage Facebook của Viện.',
            ],
            'twitter_url' => [
                'label' => 'Link Twitter (X)',
                'description' => 'Đường dẫn đầy đủ tới trang Twitter/X.',
            ],
            'youtube_url' => [
                'label' => 'Link kênh YouTube',
                'description' => 'Đường dẫn đầy đủ tới kênh YouTube.',
            ],
            'pinterest_url' => [
                'label' => 'Link Pinterest',
                'description' => 'Đường dẫn đầy đủ tới trang Pinterest.',
            ],
            'instagram_url' => [
                'label' => 'Link Instagram',
                'description' => 'Đường dẫn đầy đủ tới trang Instagram.',
            ],
            'whatsapp_url' => [
                'label' => 'Link WhatsApp',
                'description' => 'Số điện thoại hoặc link chat WhatsApp.',
            ],
            'contact_image' => [
                'label' => 'Hình ảnh trang liên hệ',
                'description' => 'Ảnh nền hiển thị trên trang Liên hệ.',
            ],
            'favicon' => [
                'label' => 'Favicon',
                'description' => 'Biểu tượng nhỏ hiển thị trên tab trình duyệt (tự động tạo ra file .ico và .png 192x192).',
            ],
            'logo_light' => [
                'label' => 'Logo sáng màu',
                'description' => 'Logo màu trắng/sáng dùng cho nền tối.',
            ],
            'logo_dark' => [
                'label' => 'Logo tối màu',
                'description' => 'Logo chính thức dùng cho nền sáng.',
            ],
            'logo_icon' => [
                'label' => 'Biểu tượng logo (Icon)',
                'description' => 'Icon logo nhỏ dạng vuông/tròn dùng ở một số góc trang trí.',
            ],
            'logo_wm' => [
                'label' => 'Watermark logo',
                'description' => 'Logo chìm làm họa tiết trang trí.',
            ],
            'icon_journey_tree' => [
                'label' => 'Icon Cây xanh (Hành trình)',
                'description' => 'Hình ảnh icon cây xanh dùng trong phần Hành trình phát triển.',
            ],
            'icon_journey_happy' => [
                'label' => 'Icon Hài lòng (Hành trình)',
                'description' => 'Hình ảnh icon mặt cười/hài lòng dùng trong phần Hành trình phát triển.',
            ],
            'capability_profile' => [
                'label' => 'Hồ sơ năng lực (PDF)',
                'description' => 'File hồ sơ năng lực định dạng PDF cho phép khách tải xuống.',
            ],
            'zalo_url' => [
                'label' => 'Link chat Zalo',
                'description' => 'Đường dẫn liên kết Zalo chat (thường là Zalo OA hoặc zalo.me/sodt).',
            ],
            'messenger_url' => [
                'label' => 'Link chat Messenger',
                'description' => 'Đường dẫn chat Facebook Messenger.',
            ],
            'testimonial_content' => [
                'label' => 'Nội dung phản hồi từ khách hàng',
                'description' => 'Câu trích dẫn đánh giá của khách hàng tiêu biểu hiển thị ở trang chủ.',
            ],
            'testimonial_author' => [
                'label' => 'Tác giả đánh giá',
                'description' => 'Tên và chức vụ của người đánh giá.',
            ],
            'journey_subtitle' => [
                'label' => 'Dòng phụ Hành trình',
                'description' => 'Tiêu đề nhỏ phụ phía trên phần Hành trình phát triển.',
            ],
            'journey_title_1' => [
                'label' => 'Tiêu đề Hành trình dòng 1',
                'description' => 'Dòng chữ thứ nhất của tiêu đề chính phần Hành trình.',
            ],
            'journey_title_2' => [
                'label' => 'Tiêu đề Hành trình dòng 2',
                'description' => 'Dòng chữ thứ hai của tiêu đề chính phần Hành trình.',
            ],
            'journey_desc' => [
                'label' => 'Mô tả ngắn Hành trình',
                'description' => 'Đoạn giới thiệu ngắn về chặng đường phát triển.',
            ],
            'journey_box1_title' => [
                'label' => 'Khối 1: Tiêu đề',
                'description' => 'Chữ hiển thị chính của ô số 1 phần Hành trình.',
            ],
            'journey_box1_desc' => [
                'label' => 'Khối 1: Mô tả phụ',
                'description' => 'Chú thích nhỏ bên dưới của ô số 1 phần Hành trình.',
            ],
            'journey_box2_count' => [
                'label' => 'Khối 2: Chỉ số',
                'description' => 'Con số thống kê hiển thị chính của ô số 2 phần Hành trình.',
            ],
            'journey_box2_desc' => [
                'label' => 'Khối 2: Mô tả phụ',
                'description' => 'Chú thích nhỏ bên dưới của ô số 2 phần Hành trình.',
            ],
            'journey_box3_count' => [
                'label' => 'Khối 3: Chỉ số',
                'description' => 'Con số thống kê hiển thị chính của ô số 3 phần Hành trình.',
            ],
            'journey_box3_desc' => [
                'label' => 'Khối 3: Mô tả phụ',
                'description' => 'Chú thích nhỏ bên dưới của ô số 3 phần Hành trình.',
            ],
            'journey_box4_title' => [
                'label' => 'Khối 4: Tiêu đề',
                'description' => 'Chữ hiển thị chính của ô số 4 phần Hành trình.',
            ],
            'journey_box4_desc' => [
                'label' => 'Khối 4: Mô tả phụ',
                'description' => 'Chú thích nhỏ bên dưới của ô số 4 phần Hành trình.',
            ],
            'about_box1_desc' => [
                'label' => 'Trang giới thiệu: Mô tả khối 1',
                'description' => 'Dòng chữ mô tả dưới chỉ số thống kê số 1 trên trang Giới thiệu.',
            ],
            'about_box2_desc' => [
                'label' => 'Trang giới thiệu: Mô tả khối 2',
                'description' => 'Dòng chữ mô tả dưới chỉ số thống kê số 2 trên trang Giới thiệu.',
            ],
        ];

        return $info[$key] ?? [
            'label' => $key,
            'description' => 'Cấu hình hệ thống.',
        ];
    }

    protected static function booted()
    {
        static::saved(function ($setting) {
            if ($setting->key === 'favicon') {
                $value = $setting->value;
                if ($value) {
                    $disk = \Illuminate\Support\Facades\Storage::disk('public');
                    if ($disk->exists($value)) {
                        $fullPath = $disk->path($value);
                        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                        
                        try {
                            // Load image based on extension
                            $image = null;
                            if ($ext === 'webp') {
                                $image = @imagecreatefromwebp($fullPath);
                            } elseif ($ext === 'png') {
                                $image = @imagecreatefrompng($fullPath);
                            } elseif ($ext === 'jpg' || $ext === 'jpeg') {
                                $image = @imagecreatefromjpeg($fullPath);
                            } else {
                                $imageData = @file_get_contents($fullPath);
                                if ($imageData !== false) {
                                    $image = @imagecreatefromstring($imageData);
                                }
                            }
                            
                            if ($image) {
                                // 1. Save 192x192 PNG version (ideal for Google Search & modern devices)
                                if ($ext === 'webp' || $ext === 'jpg' || $ext === 'jpeg') {
                                    // Generate the .png counterpart path
                                    $pngFullPath = str_replace('.' . $ext, '.png', $fullPath);
                                    
                                    $pngWidth = 192;
                                    $pngHeight = 192;
                                    $pngImage = imagecreatetruecolor($pngWidth, $pngHeight);
                                    if ($pngImage) {
                                        imagealphablending($pngImage, false);
                                        imagesavealpha($pngImage, true);
                                        $transparent = imagecolorallocatealpha($pngImage, 0, 0, 0, 127);
                                        imagefilledrectangle($pngImage, 0, 0, $pngWidth, $pngHeight, $transparent);
                                        
                                        imagecopyresampled(
                                            $pngImage,
                                            $image,
                                            0, 0, 0, 0,
                                            $pngWidth, $pngHeight,
                                            imagesx($image), imagesy($image)
                                        );
                                        imagepng($pngImage, $pngFullPath);
                                        imagedestroy($pngImage);
                                    } else {
                                        imagepng($image, $pngFullPath);
                                    }
                                } elseif ($ext === 'png') {
                                    // If it's already a PNG, resize/optimize it in place to 192x192
                                    $pngWidth = 192;
                                    $pngHeight = 192;
                                    if (imagesx($image) > $pngWidth || imagesy($image) > $pngHeight) {
                                        $pngImage = imagecreatetruecolor($pngWidth, $pngHeight);
                                        if ($pngImage) {
                                            imagealphablending($pngImage, false);
                                            imagesavealpha($pngImage, true);
                                            $transparent = imagecolorallocatealpha($pngImage, 0, 0, 0, 127);
                                            imagefilledrectangle($pngImage, 0, 0, $pngWidth, $pngHeight, $transparent);
                                            
                                            imagecopyresampled(
                                                $pngImage,
                                                $image,
                                                0, 0, 0, 0,
                                                $pngWidth, $pngHeight,
                                                imagesx($image), imagesy($image)
                                            );
                                            imagepng($pngImage, $fullPath);
                                            imagedestroy($pngImage);
                                        }
                                    }
                                }
                                
                                // 2. Generate and save favicon.ico in public root
                                // We resize the image to 32x32 for favicon.ico
                                $icoWidth = 32;
                                $icoHeight = 32;
                                $icoImage = imagecreatetruecolor($icoWidth, $icoHeight);
                                if ($icoImage) {
                                    imagealphablending($icoImage, false);
                                    imagesavealpha($icoImage, true);
                                    $transparent = imagecolorallocatealpha($icoImage, 0, 0, 0, 127);
                                    imagefilledrectangle($icoImage, 0, 0, $icoWidth, $icoHeight, $transparent);
                                    
                                    imagecopyresampled(
                                        $icoImage,
                                        $image,
                                        0, 0, 0, 0,
                                        $icoWidth, $icoHeight,
                                        imagesx($image), imagesy($image)
                                    );
                                    
                                    // Output PNG bytes to memory
                                    ob_start();
                                    imagepng($icoImage);
                                    $pngData = ob_get_clean();
                                    
                                    // Wrap in ICO container
                                    $icoData = self::wrapPngInIco($pngData, $icoWidth, $icoHeight);
                                    file_put_contents(public_path('favicon.ico'), $icoData);
                                    imagedestroy($icoImage);
                                }
                                
                                imagedestroy($image);
                            }
                        } catch (\Throwable $e) {
                            \Illuminate\Support\Facades\Log::error('Favicon conversion failed: ' . $e->getMessage());
                        }
                    }
                }
            }
        });
    }

    private static function wrapPngInIco($pngData, $width, $height)
    {
        $header = pack('vvv', 0, 1, 1);
        $widthByte = ($width >= 256) ? 0 : $width;
        $heightByte = ($height >= 256) ? 0 : $height;
        
        $dir = pack('CCCCvvVV', 
            $widthByte, 
            $heightByte, 
            0, // color palette size
            0, // reserved
            1, // color planes
            32, // bits per pixel
            strlen($pngData), // data size
            22 // data offset (6 bytes header + 16 bytes directory entry)
        );
        
        return $header . $dir . $pngData;
    }

    public static function getValue(string $key, $default = null)
    {
        static $settings = null;
        if ($settings === null) {
            $settings = self::pluck('value', 'key');
        }
        return $settings->get($key) ?? $default;
    }
}
