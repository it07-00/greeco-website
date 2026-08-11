<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Categories
        $categoriesData = [
            [
                'name' => 'ISO',
                'slug' => 'iso',
                'icon' => 'fa-certificate',
                'color' => '#008f79',
                'bg' => '#e6f4f1',
                'sort_order' => 1,
            ],
            [
                'name' => 'HSE',
                'slug' => 'hse',
                'icon' => 'fa-shield-heart',
                'color' => '#1f487e',
                'bg' => '#e8eef5',
                'sort_order' => 2,
            ],
            [
                'name' => 'ESG',
                'slug' => 'esg',
                'icon' => 'fa-leaf',
                'color' => '#4caf50',
                'bg' => '#edf7ed',
                'sort_order' => 3,
            ],
            [
                'name' => 'Carbon',
                'slug' => 'carbon',
                'icon' => 'fa-cloud',
                'color' => '#03a9f4',
                'bg' => '#e1f5fe',
                'sort_order' => 4,
            ],
            [
                'name' => 'Môi trường',
                'slug' => 'moi-truong',
                'icon' => 'fa-globe',
                'color' => '#009688',
                'bg' => '#e0f2f1',
                'sort_order' => 5,
            ],
            [
                'name' => 'Khác',
                'slug' => 'khac',
                'icon' => 'fa-ellipsis',
                'color' => '#607d8b',
                'bg' => '#eceff1',
                'sort_order' => 6,
            ],
            [
                'name' => 'Workshop',
                'slug' => 'workshop',
                'icon' => 'fa-laptop-code',
                'color' => '#ff9800',
                'bg' => '#fff3e0',
                'sort_order' => 7,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = CourseCategory::updateOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );
        }

        // 2. Seed Courses (Only titles, categories, and price set to 'Liên hệ')
        $coursesData = [
            [
                'course_category_id' => $categories['iso']->id,
                'title' => 'ISO 9001:2015 – Hệ thống quản lý chất lượng',
                'slug' => 'iso-9001-2015-he-thong-quan-ly-chat-luong',
                'price' => 'Liên hệ',
                'sort_order' => 1,
            ],
            [
                'course_category_id' => $categories['iso']->id,
                'title' => 'ISO 14001:2015 – Hệ thống quản lý môi trường',
                'slug' => 'iso-14001-2015-he-thong-quan-ly-moi-truong',
                'price' => 'Liên hệ',
                'sort_order' => 2,
            ],
            [
                'course_category_id' => $categories['carbon']->id,
                'title' => 'Kiểm kê khí nhà kính và quản lý carbon',
                'slug' => 'kiem-ke-khi-nha-kinh-va-quan-ly-carbon',
                'price' => 'Liên hệ',
                'sort_order' => 3,
            ],
            [
                'course_category_id' => $categories['esg']->id,
                'title' => 'ESG và phát triển bền vững trong doanh nghiệp',
                'slug' => 'esg-va-phat-trien-ben-vung-trong-doanh-nghiep',
                'price' => 'Liên hệ',
                'sort_order' => 4,
            ],
            [
                'course_category_id' => $categories['moi-truong']->id,
                'title' => 'Chuỗi cung ứng bền vững và kinh tế tuần hoàn',
                'slug' => 'chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan',
                'price' => 'Liên hệ',
                'sort_order' => 5,
            ],
            [
                'course_category_id' => $categories['khac']->id,
                'title' => 'Trách nhiệm xã hội và đánh giá chuỗi cung ứng',
                'slug' => 'trach-nhiem-xa-hoi-va-danh-gia-chuoi-cung-ung',
                'price' => 'Liên hệ',
                'sort_order' => 6,
            ],
            [
                'course_category_id' => $categories['workshop']->id,
                'title' => 'Workshop: Open-Source AI Agent Lab',
                'slug' => 'workshop-open-source-ai-agent-lab',
                'description' => 'Workshop chuyên sâu về xây dựng và triển khai các AI Agent nguồn mở, truy vấn dữ liệu kinh doanh (Pandas, DuckDB) và tích hợp giao diện Gradio.',
                'price' => 'Liên hệ',
                'sort_order' => 101,
            ],
            [
                'course_category_id' => $categories['workshop']->id,
                'title' => 'Workshop: Data Engineering Lab',
                'slug' => 'workshop-data-engineering-lab',
                'description' => 'Workshop thực hành thiết kế đường ống dữ liệu (Data Pipeline), làm sạch dữ liệu thô (Pandas), kiểm tra quy tắc (Pandera) và truy vấn SQL (DuckDB).',
                'price' => 'Liên hệ',
                'sort_order' => 102,
            ],
            [
                'course_category_id' => $categories['workshop']->id,
                'title' => 'Workshop: Computer Vision Lab',
                'slug' => 'workshop-computer-vision-lab',
                'description' => 'Workshop nghiên cứu và ứng dụng Thị giác máy tính (Computer Vision), huấn luyện mô hình Deep Learning (TensorFlow/PyTorch) để kiểm tra chất lượng hình ảnh sản phẩm.',
                'price' => 'Liên hệ',
                'sort_order' => 103,
            ],
            [
                'course_category_id' => $categories['workshop']->id,
                'title' => 'Workshop: Edge AI Lab',
                'slug' => 'workshop-edge-ai-lab',
                'description' => 'Workshop tối ưu hóa và chuyển đổi các mô hình AI sang định dạng biên (TFLite, ONNX), nén mô hình và chạy thực tế trên Raspberry Pi / Jetson.',
                'price' => 'Liên hệ',
                'sort_order' => 104,
            ],
            [
                'course_category_id' => $categories['workshop']->id,
                'title' => 'Workshop: AI Deployment Lab',
                'slug' => 'workshop-ai-deployment-lab',
                'description' => 'Workshop thực hành MLOps & AI Deployment, chuẩn hóa cấu trúc dự án từ Colab, kiểm thử (Pytest), kiểm tra đầu vào (Pydantic) và phục vụ qua FastAPI & Gradio.',
                'price' => 'Liên hệ',
                'sort_order' => 105,
            ],
            [
                'course_category_id' => $categories['workshop']->id,
                'title' => 'Workshop: Robotics Lab - series finale',
                'slug' => 'workshop-robotics-lab-series-finale',
                'description' => 'Workshop phần kết series, tích hợp AI vật lý (Physical AI), cảm biến chu kỳ Sense-Think-Act, huấn luyện và mô phỏng robot di chuyển tự hành.',
                'price' => 'Liên hệ',
                'sort_order' => 106,
            ],
        ];

        foreach ($coursesData as $courseData) {
            Course::updateOrCreate(
                ['slug' => $courseData['slug']],
                $courseData
            );
        }
    }
}
