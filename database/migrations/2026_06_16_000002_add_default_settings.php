<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            // Testimonial
            ['key' => 'testimonial_content', 'value' => 'Chúng tôi đánh giá rất cao sự chuyên nghiệp và kiến thức chuyên sâu của Viện GREECO trong việc tư vấn lộ trình Net Zero và ESG. Giải pháp của Viện rất thiết thực, hiệu quả và dễ dàng tích hợp vào hệ thống của chúng tôi.', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'testimonial_author', 'value' => 'ThS. Lê Hoàng Nam', 'type' => 'text', 'group' => 'general'],

            // Journey
            ['key' => 'journey_subtitle', 'value' => 'Hành trình phát triển', 'type' => 'text', 'group' => 'general'],
            ['key' => 'journey_title_1', 'value' => 'Kiến tạo Giá trị Kinh tế Xanh', 'type' => 'text', 'group' => 'general'],
            ['key' => 'journey_title_2', 'value' => 'từ năm 2019', 'type' => 'text', 'group' => 'general'],
            ['key' => 'journey_desc', 'value' => 'Được thành lập bởi đội ngũ nhà khoa học, chuyên gia tâm huyết, Viện GREECO đã và đang khẳng định vị thế tiên phong trong tư vấn chiến lược chuyển đổi xanh, thực hiện nghiên cứu thực chứng và hỗ trợ cộng đồng doanh nghiệp đạt mục tiêu phát triển bền vững.', 'type' => 'textarea', 'group' => 'general'],
            
            ['key' => 'journey_box1_title', 'value' => 'Uy tín & Khoa học', 'type' => 'text', 'group' => 'general'],
            ['key' => 'journey_box1_desc', 'value' => 'Đánh giá từ đối tác chiến lược', 'type' => 'text', 'group' => 'general'],

            ['key' => 'journey_box2_count', 'value' => '550', 'type' => 'text', 'group' => 'general'],
            ['key' => 'journey_box2_desc', 'value' => 'Dự án tư vấn thành công', 'type' => 'text', 'group' => 'general'],

            ['key' => 'journey_box3_count', 'value' => '850', 'type' => 'text', 'group' => 'general'],
            ['key' => 'journey_box3_desc', 'value' => 'Doanh nghiệp đồng hành', 'type' => 'text', 'group' => 'general'],

            ['key' => 'journey_box4_title', 'value' => 'Xếp hạng 4.8/5', 'type' => 'text', 'group' => 'general'],
            ['key' => 'journey_box4_desc', 'value' => 'Khảo sát chất lượng dịch vụ', 'type' => 'text', 'group' => 'general'],

            // About Journey specific
            ['key' => 'about_box1_desc', 'value' => 'Dự án hoàn thành', 'type' => 'text', 'group' => 'general'],
            ['key' => 'about_box2_desc', 'value' => 'Đối tác tin cậy', 'type' => 'text', 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = [
            'testimonial_content', 'testimonial_author',
            'journey_subtitle', 'journey_title_1', 'journey_title_2', 'journey_desc',
            'journey_box1_title', 'journey_box1_desc',
            'journey_box2_count', 'journey_box2_desc',
            'journey_box3_count', 'journey_box3_desc',
            'journey_box4_title', 'journey_box4_desc',
            'about_box1_desc', 'about_box2_desc'
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
