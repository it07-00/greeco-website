<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Database\Seeder;

class DetailedCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create the ESG category
        $category = CourseCategory::firstOrCreate(
            ['slug' => 'esg'],
            [
                'name' => 'ESG',
                'icon' => 'fa-leaf',
                'color' => '#4caf50',
                'bg' => '#edf7ed',
                'sort_order' => 3,
            ]
        );

        $contentHtml = '
            <h3><strong>Tổng quan khóa học</strong></h3>
            <p>Khóa học <strong>ESG và Phát triển Bền vững trong Doanh nghiệp (Chuyên sâu)</strong> được thiết kế đặc biệt nhằm giúp các nhà quản trị, chủ doanh nghiệp và cán bộ chuyên trách nắm vững kiến thức nền tảng và phương pháp thực hành chuyên sâu để tích hợp các tiêu chuẩn E (Môi trường), S (Xã hội), G (Quản trị) vào mô hình vận hành cốt lõi của tổ chức.</p>
            
            <p>Trong bối cảnh chuyển dịch xanh toàn cầu và cam kết Net Zero của Chính phủ Việt Nam vào năm 2050, thực hành ESG không còn là hoạt động tự nguyện mà đã trở thành điều kiện tiên quyết giúp doanh nghiệp nâng cao năng lực cạnh tranh, tiếp cận nguồn vốn ưu đãi và phát triển bền vững vững chắc.</p>
            
            <h3><strong>Mục tiêu học tập</strong></h3>
            <ul>
                <li>Hiểu rõ bối cảnh, động lực toàn cầu và tầm quan trọng chiến lược của các tiêu chuẩn ESG.</li>
                <li>Nắm vững phương pháp xác định các vấn đề trọng yếu (Materiality Assessment) phù hợp với đặc thù từng ngành nghề.</li>
                <li>Biết cách thiết lập hệ thống chỉ số đo lường hiệu quả (KPIs) và thu thập dữ liệu ESG thực tế.</li>
                <li>Làm chủ quy trình xây dựng Báo cáo ESG theo chuẩn quốc tế GRI, SASB và TCFD.</li>
                <li>Biết cách lập lộ trình chuyển đổi xanh, giảm phát thải carbon hướng tới trung hòa carbon (Net Zero).</li>
            </ul>

            <h3><strong>Đối tượng tham gia</strong></h3>
            <ul>
                <li>Hội đồng Quản trị, Ban Giám đốc (CEO, CFO, COO) của các doanh nghiệp mong muốn định hướng chiến lược xanh.</li>
                <li>Quản lý bộ phận ESG, Môi trường - An toàn - Sức khỏe (HSE), CSR, Nhân sự, Pháp chế và Quan hệ cổ đông.</li>
                <li>Chuyên viên Tư vấn môi trường, kiểm toán viên, nhà phân tích tài chính và đầu tư.</li>
                <li>Cá nhân muốn định hướng nghề nghiệp và nâng cao năng lực trong lĩnh vực Phát triển Bền vững (Sustainability).</li>
            </ul>

            <h3><strong>Thông tin chi tiết khóa học</strong></h3>
            <ul>
                <li><strong>Thời lượng:</strong> 24 giờ học (học trực tiếp kết hợp thực hành tình huống thực tế).</li>
                <li><strong>Hình thức:</strong> Học Offline tại Viện GREECO hoặc Online qua Zoom có tương tác trực tiếp.</li>
                <li><strong>Chứng nhận:</strong> Cấp chứng chỉ hoàn thành khóa học do Viện Nghiên cứu và Phát triển Kinh tế Xanh (GREECO) cấp, có giá trị chuyên môn cao trên toàn quốc.</li>
            </ul>
        ';

        $curriculumData = [
            [
                'title' => 'Chương 1: Tổng quan về ESG và Xu hướng Phát triển Bền vững',
                'lessons' => [
                    ['title' => 'Bài 1: Bối cảnh toàn cầu và sự trỗi dậy của xu hướng ESG trong thế kỷ 21', 'duration' => '20:15'],
                    ['title' => 'Bài 2: Định nghĩa chi tiết 3 cột trụ: E (Môi trường) - S (Xã hội) - G (Quản trị)', 'duration' => '25:40'],
                    ['title' => 'Bài 3: Lợi ích tài chính và thương hiệu khi tích hợp ESG vào doanh nghiệp', 'duration' => '18:20'],
                    ['title' => 'Bài 4: Các chính sách pháp luật và xu hướng chuyển đổi xanh tại Việt Nam', 'duration' => '22:15']
                ]
            ],
            [
                'title' => 'Chương 2: Thiết lập Bộ chỉ số và Đo lường Hiệu quả ESG',
                'lessons' => [
                    ['title' => 'Bài 1: Tìm hiểu các khung báo cáo phổ biến (GRI Standards, SASB, TCFD)', 'duration' => '30:10'],
                    ['title' => 'Bài 2: Phương pháp đánh giá và xác định các chủ đề trọng yếu (Materiality Assessment)', 'duration' => '28:15'],
                    ['title' => 'Bài 3: Quy trình thu thập dữ liệu, đo lường và thiết lập KPI cho ESG', 'duration' => '35:50']
                ]
            ],
            [
                'title' => 'Chương 3: Lập Báo cáo ESG và Lộ trình Chuyển đổi Xanh',
                'lessons' => [
                    ['title' => 'Bài 1: Quy trình 5 bước xây dựng Báo cáo Phát triển Bền vững hoàn chỉnh', 'duration' => '40:20'],
                    ['title' => 'Bài 2: Phân tích các lỗi thường gặp trong báo cáo ESG (Greenwashing) và cách phòng tránh', 'duration' => '25:30'],
                    ['title' => 'Bài 3: Xây dựng lộ trình Net Zero và giảm thiểu dấu chân carbon (Carbon Footprint)', 'duration' => '35:10'],
                    ['title' => 'Bài 4: Thực hành xây dựng bản thảo báo cáo ESG sơ bộ cho doanh nghiệp học viên', 'duration' => '45:00']
                ]
            ]
        ];

        Course::updateOrCreate(
            ['slug' => 'esg-va-phat-trien-ben-vung-trong-doanh-nghiep'],
            [
                'course_category_id' => $category->id,
                'title' => 'ESG và phát triển bền vững trong doanh nghiệp',
                'description' => 'Khóa học cung cấp kiến thức nền tảng và thực hành chuyên sâu về ESG (Environmental, Social, Governance) và lộ trình phát triển bền vững dành cho doanh nghiệp và nhà quản trị.',
                'content' => $contentHtml,
                'image' => 'courses/esg_detail.webp',
                'meta_title' => 'Khóa học ESG và Phát triển Bền vững Doanh nghiệp Chuyên sâu',
                'meta_description' => 'Khóa học ESG & phát triển bền vững của Viện GREECO giúp doanh nghiệp nắm vững phương pháp kiểm kê khí nhà kính, xây dựng báo cáo ESG và chuyển đổi Net Zero.',
                'meta_keywords' => 'khoa hoc esg, phat trien ben vung, bao cao esg, net zero, kiem ke carbon, vien greeco',
                'canonical_url' => route('services.course-detail', 'esg-va-phat-trien-ben-vung-trong-doanh-nghiep'),
                'tag' => 'BÁN CHẠY',
                'badge_color' => 'bg-danger',
                'price' => '3.500.000đ',
                'original_price' => '5.000.000đ',
                'discount' => '-30%',
                'instructor_name' => 'PGS. TS. Trần Thị Mai',
                'instructor_role' => 'Phó Viện trưởng Viện GREECO & Chuyên gia ESG cấp cao',
                'instructor_image' => 'instructors/tran-thi-mai.jpg',
                'instructor_description' => 'PGS. TS. Trần Thị Mai đã có trên 15 năm giảng dạy, nghiên cứu khoa học và tư vấn triển khai ESG, báo cáo phát triển bền vững cho hơn 100 tập đoàn lớn tại Việt Nam và khu vực.',
                'curriculum' => $curriculumData,
                'is_indexable' => true,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }
}
