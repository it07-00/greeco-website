<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = CourseCategory::create($catData);
        }

        // Curriculum for ISO 9001
        $iso9001Curriculum = [
            [
                'title' => 'Chương 1: Tổng quan về ISO 9001:2015',
                'progress' => '5/8 bài',
                'lessons' => [
                    ['title' => 'Bài 1: Giới thiệu về ISO 9001:2015', 'duration' => '15:30', 'completed' => true],
                    ['title' => 'Bài 2: Lịch sử phát triển của ISO 9001', 'duration' => '12:45', 'completed' => true],
                    ['title' => 'Bài 3: Các nguyên tắc quản lý chất lượng', 'duration' => '18:20', 'completed' => true],
                    ['title' => 'Bài 4: Cấu trúc tiêu chuẩn ISO 9001:2015', 'duration' => '14:10', 'completed' => true],
                    ['title' => 'Bài 5: Thuật ngữ và định nghĩa', 'duration' => '16:30', 'completed' => true],
                    ['title' => 'Bài 6: Hướng dẫn tích hợp PDCA vào quy trình', 'duration' => '11:45', 'completed' => false],
                    ['title' => 'Bài 7: Quản lý rủi ro trong giai đoạn thiết kế', 'duration' => '13:50', 'completed' => false],
                    ['title' => 'Bài 8: Bài tập trắc nghiệm chương 1', 'duration' => '10 câu hỏi', 'completed' => false],
                ]
            ],
            [
                'title' => 'Chương 2: Bối cảnh của tổ chức',
                'progress' => '3/5 bài',
                'lessons' => [
                    ['title' => 'Bài 9: Hiểu tổ chức và bối cảnh của tổ chức (PESTEL & SWOT)', 'duration' => '18:15', 'completed' => true],
                    ['title' => 'Bài 10: Xác định nhu cầu và mong đợi của các bên quan tâm', 'duration' => '14:20', 'completed' => true],
                    ['title' => 'Bài 11: Xác định phạm vi của hệ thống QLCL', 'duration' => '11:50', 'completed' => true],
                    ['title' => 'Bài 12: Hệ thống quản lý chất lượng và các quá trình', 'duration' => '15:10', 'completed' => false],
                    ['title' => 'Bài 13: Bài tập thực hành: Thiết lập Sơ đồ quá trình Turtle Diagram', 'duration' => '20:00', 'completed' => false],
                ]
            ],
            [
                'title' => 'Chương 3: Sự lãnh đạo (Leadership)',
                'progress' => '0/5 bài',
                'lessons' => [
                    ['title' => 'Bài 14: Sự cam kết của lãnh đạo cao nhất', 'duration' => '15:40', 'completed' => false],
                    ['title' => 'Bài 15: Định hướng khách hàng làm trung tâm', 'duration' => '11:30', 'completed' => false],
                    ['title' => 'Bài 16: Thiết lập và truyền thông Chính sách chất lượng', 'duration' => '12:30', 'completed' => false],
                    ['title' => 'Bài 17: Phân định Vai trò, Trách nhiệm và Quyền hạn', 'duration' => '14:15', 'completed' => false],
                    ['title' => 'Bài 18: Bài kiểm tra tình huống thực tế của lãnh đạo', 'duration' => '15 phút', 'completed' => false],
                ]
            ],
            [
                'title' => 'Chương 4: Hoạch định (Planning)',
                'progress' => '0/3 bài',
                'lessons' => [
                    ['title' => 'Bài 19: Hành động giải quyết rủi ro và cơ hội (FMEA rút gọn)', 'duration' => '22:15', 'completed' => false],
                    ['title' => 'Bài 20: Xác định mục tiêu chất lượng và hoạch định đạt mục tiêu', 'duration' => '18:40', 'completed' => false],
                    ['title' => 'Bài 21: Quản lý sự thay đổi trong hệ thống QLCL', 'duration' => '14:10', 'completed' => false],
                ]
            ],
            [
                'title' => 'Chương 5: Hỗ trợ (Support)',
                'progress' => '0/6 bài',
                'lessons' => [
                    ['title' => 'Bài 22: Quản lý nguồn lực (Nhân lực, Cơ sở hạ tầng, Môi trường)', 'duration' => '20:10', 'completed' => false],
                    ['title' => 'Bài 23: Thiết bị theo dõi và đo lường - Hiệu chuẩn', 'duration' => '16:15', 'completed' => false],
                    ['title' => 'Bài 24: Kiến thức của tổ chức (Knowledge Management)', 'duration' => '12:30', 'completed' => false],
                    ['title' => 'Bài 25: Năng lực và nhận thức của người lao động', 'duration' => '14:50', 'completed' => false],
                    ['title' => 'Bài 26: Trao đổi thông tin nội bộ và bên ngoài', 'duration' => '10:15', 'completed' => false],
                    ['title' => 'Bài 27: Thông tin dạng văn bản (Kiểm soát tài liệu và hồ sơ)', 'duration' => '19:40', 'completed' => false],
                ]
            ],
            [
                'title' => 'Chương 6: Vận hành (Operation)',
                'progress' => '0/5 bài',
                'lessons' => [
                    ['title' => 'Bài 28: Hoạch định và kiểm soát vận hành', 'duration' => '25:30', 'completed' => false],
                    ['title' => 'Bài 29: Yêu cầu về sản phẩm và dịch vụ (Xem xét hợp đồng)', 'duration' => '17:20', 'completed' => false],
                    ['title' => 'Bài 30: Thiết kế và phát triển sản phẩm/dịch vụ', 'duration' => '24:10', 'completed' => false],
                    ['title' => 'Bài 31: Kiểm soát quá trình cung cấp bên ngoài (Nhà cung cấp)', 'duration' => '21:15', 'completed' => false],
                    ['title' => 'Bài 32: Sản xuất và cung cấp dịch vụ (Truy xuất nguồn gốc)', 'duration' => '19:30', 'completed' => false],
                ]
            ],
            [
                'title' => 'Chương 7: Đánh giá kết quả (Performance Evaluation)',
                'progress' => '0/3 bài',
                'lessons' => [
                    ['title' => 'Bài 33: Theo dõi, đo lường, phân tích và đánh giá sự hài lòng khách hàng', 'duration' => '19:40', 'completed' => false],
                    ['title' => 'Bài 34: Đánh giá nội bộ (Quy trình & Kỹ năng đánh giá viên)', 'duration' => '22:15', 'completed' => false],
                    ['title' => 'Bài 35: Xem xét của lãnh đạo (Management Review)', 'duration' => '18:10', 'completed' => false],
                ]
            ],
            [
                'title' => 'Chương 8: Cải tiến (Improvement)',
                'progress' => '0/2 bài',
                'lessons' => [
                    ['title' => 'Bài 36: Sự không phù hợp và hành động khắc phục (8D / 5 Why)', 'duration' => '15:10', 'completed' => false],
                    ['title' => 'Bài 37: Cải tiến liên tục (Kaizen & KAI)', 'duration' => '12:40', 'completed' => false],
                ]
            ],
            [
                'title' => 'Bài thi cuối khóa',
                'progress' => '0/1 bài',
                'lessons' => [
                    ['title' => 'Bài kiểm tra trắc nghiệm cuối khóa', 'duration' => '40 câu hỏi - 60 phút', 'completed' => false],
                ]
            ]
        ];

        // 2. Seed Courses
        $coursesData = [
            [
                'course_category_id' => $categories['iso']->id,
                'title' => 'ISO 9001:2015 – Hệ thống quản lý chất lượng',
                'slug' => 'iso-9001-2015-he-thong-quan-ly-chat-luong',
                'description' => 'Khóa học thực chiến giúp học viên nắm vững lý thuyết, cấu trúc tiêu chuẩn và các bước xây dựng, vận hành và đánh giá nội bộ Hệ thống Quản lý Chất lượng ISO 9001:2015 tại doanh nghiệp.',
                'image' => 'assets/images/services/iso-9001-course.png',
                'tag' => 'BÁN CHẠY',
                'badge_color' => 'bg-success',
                'price' => '1.500.000đ',
                'original_price' => '2.000.000đ',
                'discount' => '-25%',
                'instructor_name' => 'TS. Nguyễn Văn A',
                'instructor_role' => 'Chuyên gia cấp cao về ISO & ESG tại GREECO',
                'instructor_image' => 'assets/images/team/team-1.jpg',
                'instructor_description' => 'Hơn 15 năm kinh nghiệm tư vấn, đào tạo và thiết lập Hệ thống quản lý chất lượng ISO 9001, ISO 14001, ISO 45001 cho hàng trăm doanh nghiệp, tập đoàn đa quốc gia tại Việt Nam.',
                'curriculum' => $iso9001Curriculum,
                'sort_order' => 1,
            ],
            [
                'course_category_id' => $categories['iso']->id,
                'title' => 'ISO 14001:2015 – Hệ thống quản lý môi trường',
                'slug' => 'iso-14001-2015-he-thong-quan-ly-moi-truong',
                'description' => 'Cung cấp các kiến thức thiết lập Hệ thống Quản lý Môi trường chuẩn quốc tế, nhận diện khía cạnh môi trường và xây dựng chương trình quản lý.',
                'image' => 'assets/images/services/2.webp',
                'tag' => 'MỚI',
                'badge_color' => 'bg-info',
                'price' => '1.500.000đ',
                'original_price' => '2.000.000đ',
                'discount' => '-25%',
                'instructor_name' => 'ThS. Trần Thị B',
                'instructor_role' => 'Chuyên gia ESG',
                'instructor_image' => 'assets/images/team/team-2.jpg',
                'instructor_description' => 'Hơn 10 năm kinh nghiệm nghiên cứu và tư vấn triển khai ESG, báo cáo phát triển bền vững cho các tập đoàn công nghiệp nặng.',
                'curriculum' => [],
                'sort_order' => 2,
            ],
            [
                'course_category_id' => $categories['carbon']->id,
                'title' => 'Kiểm kê khí nhà kính và quản lý carbon',
                'slug' => 'kiem-ke-khi-nha-kinh-va-quan-ly-carbon',
                'description' => 'Đầu ngành cùng mục tiêu giảm phát thải của quốc gia và quốc tế, đào tạo chuyên sâu về kỹ thuật kiểm kê và định lượng carbon theo ISO 14064-1.',
                'image' => 'assets/images/services/3.webp',
                'tag' => 'BÁN CHẠY',
                'badge_color' => 'bg-success',
                'price' => '1.800.000đ',
                'original_price' => '2.400.000đ',
                'discount' => '-25%',
                'instructor_name' => 'PGS.TS. Lê Văn C',
                'instructor_role' => 'Chuyên gia HSE',
                'instructor_image' => 'assets/images/team/team-3.jpg',
                'instructor_description' => 'Hơn 20 năm giảng dạy và tư vấn về công nghệ môi trường, đánh giá tác động môi trường và ứng phó biến đổi khí hậu.',
                'curriculum' => [],
                'sort_order' => 3,
            ],
            [
                'course_category_id' => $categories['esg']->id,
                'title' => 'ESG và phát triển bền vững trong doanh nghiệp',
                'slug' => 'esg-va-phat-trien-ben-vung-trong-doanh-nghiep',
                'description' => 'Nâng cao nhận thức và năng lực tích hợp tiêu chuẩn ESG vào chiến lược kinh doanh dài hạn của doanh nghiệp và báo cáo phát triển bền vững.',
                'image' => 'assets/images/services/4.webp',
                'tag' => 'HOT',
                'badge_color' => 'bg-danger',
                'price' => '1.800.000đ',
                'original_price' => '2.400.000đ',
                'discount' => '-25%',
                'instructor_name' => 'ThS. Trần Thị B',
                'instructor_role' => 'Chuyên gia ESG',
                'instructor_image' => 'assets/images/team/team-2.jpg',
                'instructor_description' => 'Hơn 10 năm kinh nghiệm nghiên cứu và tư vấn triển khai ESG, báo cáo phát triển bền vững cho các tập đoàn công nghiệp nặng.',
                'curriculum' => [],
                'sort_order' => 4,
            ],
            [
                'course_category_id' => $categories['moi-truong']->id,
                'title' => 'Chuỗi cung ứng bền vững và kinh tế tuần hoàn',
                'slug' => 'chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan',
                'description' => 'Hỗ trợ doanh nghiệp hội nhập chuỗi cung ứng toàn cầu thông qua các tiêu chuẩn tuần hoàn, truy xuất nguồn gốc nhựa tái chế và GRS.',
                'image' => 'assets/images/services/5.webp',
                'tag' => 'MỚI',
                'badge_color' => 'bg-info',
                'price' => '1.600.000đ',
                'original_price' => '2.200.000đ',
                'discount' => '-27%',
                'instructor_name' => 'TS. Nguyễn Văn A',
                'instructor_role' => 'Chuyên gia cấp cao về ISO & ESG tại GREECO',
                'instructor_image' => 'assets/images/team/team-1.jpg',
                'instructor_description' => 'Hơn 15 năm kinh nghiệm tư vấn, đào tạo và thiết lập Hệ thống quản lý chất lượng ISO 9001, ISO 14001, ISO 45001 cho hàng trăm doanh nghiệp, tập đoàn đa quốc gia tại Việt Nam.',
                'curriculum' => [],
                'sort_order' => 5,
            ],
            [
                'course_category_id' => $categories['khac']->id,
                'title' => 'Trách nhiệm xã hội và đánh giá chuỗi cung ứng',
                'slug' => 'trach-nhiem-xa-hoi-va-danh-gia-chuoi-cung-ung',
                'description' => 'Hướng dẫn tuân thủ các quy tắc đạo đức kinh doanh, bảo vệ quyền lợi người lao động và trách nhiệm xã hội SA8000, BSCI, SMETA.',
                'image' => 'assets/images/services/6.webp',
                'tag' => 'MỚI',
                'badge_color' => 'bg-info',
                'price' => '1.500.000đ',
                'original_price' => '2.000.000đ',
                'discount' => '-25%',
                'instructor_name' => 'TS. Nguyễn Văn A',
                'instructor_role' => 'Chuyên gia cấp cao về ISO & ESG tại GREECO',
                'instructor_image' => 'assets/images/team/team-1.jpg',
                'instructor_description' => 'Hơn 15 năm kinh nghiệm tư vấn, đào tạo và thiết lập Hệ thống quản lý chất lượng ISO 9001, ISO 14001, ISO 45001 cho hàng trăm doanh nghiệp, tập đoàn đa quốc gia tại Việt Nam.',
                'curriculum' => [],
                'sort_order' => 6,
            ],
        ];

        foreach ($coursesData as $courseData) {
            Course::create($courseData);
        }
    }
}
