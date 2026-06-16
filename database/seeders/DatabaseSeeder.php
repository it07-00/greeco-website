<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Contact;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Project;
use App\Models\SeoRedirect;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        User::query()->updateOrCreate(
            ['email' => 'admin@greeco.vn'],
            [
                'name' => 'GREECO Admin',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Seed Banners / Sliders (Only 2 records using the new visual banners)
        Banner::query()->delete();

        $storageBannersDir = public_path('storage/banners');
        if (!file_exists($storageBannersDir)) {
            mkdir($storageBannersDir, 0755, true);
            chmod($storageBannersDir, 0755);
        }
        foreach (['5.webp', '6.webp'] as $file) {
            $src = public_path("assets/images/{$file}");
            $dest = "{$storageBannersDir}/{$file}";
            if (file_exists($src)) {
                copy($src, $dest);
                chmod($dest, 0644);
            }
        }

        $bannersData = [
            [
                'image' => 'banners/5.webp',
                'url' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'image' => 'banners/6.webp',
                'url' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($bannersData as $b) {
            Banner::query()->create($b);
        }

        // 3. Seed Static Pages (At least 6 records)
        $pagesData = [
            [
                'slug' => 'gioi-thieu',
                'title' => 'Giới thiệu về Viện GREECO',
                'excerpt' => 'GREECO kết nối nghiên cứu khoa học với thực tiễn, phát triển và chuyển giao các giải pháp kinh tế xanh, công nghệ môi trường và phát triển bền vững.',
                'content' => '<p><strong>Viện Nghiên cứu và Phát triển Kinh tế Xanh (GREECO)</strong> là tổ chức khoa học và công nghệ hoạt động trong lĩnh vực nghiên cứu, ứng dụng và chuyển giao các giải pháp phát triển bền vững, kinh tế xanh và công nghệ môi trường. Với đội ngũ chuyên gia đa ngành trong các lĩnh vực kỹ thuật hóa học, kỹ thuật môi trường, năng lượng tái tạo, viễn thám, địa kỹ thuật và quản trị phát triển bền vững, GREECO hướng đến việc kết nối nghiên cứu khoa học với thực tiễn, tạo ra các giải pháp đổi mới sáng tạo phục vụ doanh nghiệp, cộng đồng và cơ quan quản lý.</p><p>GREECO tập trung nghiên cứu và phát triển công nghệ xanh, sản xuất sạch hơn, kinh tế tuần hoàn, kiểm kê và giảm phát thải khí nhà kính, ESG, Net Zero, quản lý tài nguyên và bảo vệ môi trường. Bên cạnh hoạt động nghiên cứu, Viện còn cung cấp các dịch vụ tư vấn, đào tạo, phản biện, thẩm định và chuyển giao công nghệ, góp phần thúc đẩy quá trình chuyển đổi xanh và nâng cao năng lực cạnh tranh bền vững cho các tổ chức, doanh nghiệp tại Việt Nam và khu vực.</p><p>Với định hướng trở thành đơn vị khoa học và công nghệ uy tín trong lĩnh vực kinh tế xanh, GREECO không ngừng mở rộng hợp tác với các trường đại học, viện nghiên cứu, doanh nghiệp và tổ chức quốc tế nhằm lan tỏa các giá trị phát triển bền vững, đóng góp tích cực vào mục tiêu tăng trưởng xanh và phát thải ròng bằng không (Net Zero).</p>',
                'meta_title' => 'Giới thiệu về Viện GREECO | Khoa học Công nghệ Xanh',
                'meta_description' => 'Giới thiệu về Viện Nghiên cứu và Phát triển Kinh tế Xanh (GREECO) - Sứ mệnh, tầm nhìn, cơ cấu tổ chức và đội ngũ chuyên gia.',
                'is_indexable' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'lien-he',
                'title' => 'Thông tin liên hệ',
                'excerpt' => 'Địa chỉ văn phòng, số điện thoại, email và các kênh thông tin hỗ trợ chính thức của Viện GREECO.',
                'content' => '<p>Mọi yêu cầu hỗ trợ, hợp tác nghiên cứu hoặc đề nghị tư vấn doanh nghiệp xin vui lòng gửi về văn phòng Viện GREECO...</p>',
                'meta_title' => 'Liên hệ GREECO | Kết nối kiến tạo tương lai xanh',
                'meta_description' => 'Địa chỉ văn phòng, số điện thoại, email và các kênh thông tin hỗ trợ chính thức của Viện GREECO.',
                'is_indexable' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'tam-nhin-su-menh',
                'title' => 'Tầm nhìn và Sứ mệnh',
                'excerpt' => 'Tầm nhìn chiến lược, sứ mệnh lịch sử và các giá trị cốt lõi làm kim chỉ nam cho hoạt động của Viện GREECO.',
                'content' => '<h4>Tầm nhìn</h4><p>GREECO hướng tới trở thành viện nghiên cứu và phát triển hàng đầu khu vực về kinh tế xanh và kinh tế tuần hoàn...</p>',
                'meta_title' => 'Tầm nhìn & Sứ mệnh | Viện GREECO',
                'meta_description' => 'Tầm nhìn chiến lược, sứ mệnh lịch sử và các giá trị cốt lõi làm kim chỉ nam cho hoạt động của Viện GREECO.',
                'is_indexable' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'slug' => 'chinh-sach-bao-mat',
                'title' => 'Chính sách bảo mật thông tin',
                'excerpt' => 'Cam kết bảo mật thông tin cá nhân và dữ liệu của khách hàng, đối tác khi tương tác với Viện GREECO.',
                'content' => '<p>Chúng tôi hiểu rằng sự riêng tư của bạn là vô cùng quan trọng. Chính sách này giải thích cách chúng tôi thu thập, sử dụng và bảo vệ thông tin...</p>',
                'meta_title' => 'Chính sách bảo mật | Viện GREECO',
                'meta_description' => 'Cam kết bảo mật thông tin cá nhân và dữ liệu của khách hàng, đối tác khi tương tác với Viện GREECO.',
                'is_indexable' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'slug' => 'dieu-khoan-dich-vu',
                'title' => 'Điều khoản sử dụng dịch vụ',
                'excerpt' => 'Các điều khoản, quy định pháp lý ràng buộc quyền lợi và trách nhiệm giữa người dùng và Viện GREECO.',
                'content' => '<p>Chào mừng bạn đến với hệ thống dịch vụ của GREECO. Bằng việc truy cập hoặc sử dụng các tài liệu, dịch vụ của chúng tôi...</p>',
                'meta_title' => 'Điều khoản sử dụng | Viện GREECO',
                'meta_description' => 'Các điều khoản, quy định pháp lý ràng buộc quyền lợi và trách nhiệm giữa người dùng và Viện GREECO.',
                'is_indexable' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'slug' => 'so-do-to-chuc',
                'title' => 'Sơ đồ cơ cấu tổ chức',
                'excerpt' => 'Cơ cấu sơ đồ nhân sự, ban lãnh đạo, hội đồng khoa học và các phòng ban nghiên cứu thuộc Viện GREECO.',
                'content' => '<p>Viện GREECO được vận hành dưới sự chỉ đạo của Hội đồng Khoa học và Ban Giám đốc Viện, bao gồm 4 trung tâm chuyên môn chính...</p>',
                'meta_title' => 'Sơ đồ tổ chức | Viện GREECO',
                'meta_description' => 'Cơ cấu sơ đồ nhân sự, ban lãnh đạo, hội đồng khoa học và các phòng ban nghiên cứu thuộc Viện GREECO.',
                'is_indexable' => true,
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($pagesData as $pg) {
            Page::query()->updateOrCreate(['slug' => $pg['slug']], $pg);
        }

        // 4. Seed Service Categories (At least 6 records)
        $categoriesData = [
            ['slug' => 'dao-tao-boi-duong', 'name' => 'Đào tạo & Bồi dưỡng', 'description' => 'Các khóa học đào tạo, bồi dưỡng nâng cao năng lực doanh nghiệp', 'sort_order' => 1, 'is_active' => true],
            ['slug' => 'dich-vu-tu-van', 'name' => 'Dịch vụ Tư vấn', 'description' => 'Các dịch vụ tư vấn phát triển bền vững và giảm phát thải', 'sort_order' => 2, 'is_active' => true],
            ['slug' => 'phat-trien-du-an', 'name' => 'Phát triển Dự án', 'description' => 'Phát triển dự án giảm phát thải carbon và tín chỉ nhựa', 'sort_order' => 3, 'is_active' => true],
            ['slug' => 'nghien-cuu-chuyen-giao', 'name' => 'Nghiên cứu và Chuyển giao Công nghệ', 'description' => 'Nghiên cứu ứng dụng và chuyển giao công nghệ xanh', 'sort_order' => 4, 'is_active' => true],
            ['slug' => 'hoi-thao-truyen-thong', 'name' => 'Hội thảo & Truyền thông', 'description' => 'Tổ chức hội thảo khoa học và truyền thông Net Zero', 'sort_order' => 5, 'is_active' => true],
            ['slug' => 'thu-vien-ho-so', 'name' => 'Văn bản pháp luật', 'description' => 'Hệ thống văn bản pháp luật về môi trường và khí hậu', 'sort_order' => 6, 'is_active' => true],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = ServiceCategory::query()->updateOrCreate(['slug' => $c['slug']], $c);
        }

        // 5. Seed Services (At least 6 records)
        $servicesData = [
            [
                'service_category_id' => $categories['dao-tao-boi-duong']->id,
                'name' => 'Đào tạo Hệ thống quản lý ISO',
                'slug' => 'dao-tao-he-thong-quan-ly-iso',
                'short_description' => 'Đào tạo từ nhận thức chung đến chuyên viên đánh giá nội bộ các tiêu chuẩn ISO 9001, 14001, 45001, 50001.',
                'content' => '<h4>Đào tạo Hệ thống quản lý ISO và cải tiến doanh nghiệp</h4><p>Đào tạo ISO giúp doanh nghiệp chuẩn hóa quy trình và nâng cao năng lực vận hành theo tiêu chuẩn quốc tế.</p>',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'service_category_id' => $categories['dao-tao-boi-duong']->id,
                'name' => 'Đào tạo An toàn – Sức khỏe – Môi trường (HSE)',
                'slug' => 'dao-tao-hse',
                'short_description' => 'Trang bị kiến thức và kỹ năng thực tiễn để kiểm soát rủi ro, bảo vệ sức khỏe người lao động.',
                'content' => '<h4>Đào tạo An toàn – Sức khỏe – Môi trường (HSE)</h4><p>Cung cấp kiến thức chuyên sâu về đánh giá rủi ro, quản lý hóa chất và điều tra sự cố trong doanh nghiệp.</p>',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'service_category_id' => $categories['dich-vu-tu-van']->id,
                'name' => 'Tư vấn ESG & Phát triển bền vững',
                'slug' => 'tu-van-esg-phat-trien-ben-vung',
                'short_description' => 'Tư vấn xây dựng chiến lược tích hợp tiêu chuẩn Môi trường, Xã hội và Quản trị (ESG).',
                'content' => '<h4>Tư vấn chiến lược ESG và Phát triển bền vững</h4><p>Hỗ trợ doanh nghiệp thiết lập chỉ số đo lường hiệu quả và lập Báo cáo Phát triển Bền vững đạt chuẩn GRI.</p>',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'service_category_id' => $categories['dich-vu-tu-van']->id,
                'name' => 'Kiểm kê khí nhà kính & SBTi',
                'slug' => 'kiem-ke-khi-nha-kinh-sbti',
                'short_description' => 'Khảo sát ranh giới phát thải, định lượng khí nhà kính và xây dựng lộ trình Net Zero.',
                'content' => '<h4>Dịch vụ Kiểm kê khí nhà kính & Thiết lập mục tiêu SBTi</h4><p>Chúng tôi đo đạc phát thải carbon tại cơ sở sản xuất và xây dựng lộ trình giảm phát thải khoa học theo ISO 14064.</p>',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'service_category_id' => $categories['phat-trien-du-an']->id,
                'name' => 'Phát triển dự án & đăng ký Tín chỉ Carbon',
                'slug' => 'phat-trien-du-an-dang-ky-tin-chi-carbon',
                'short_description' => 'Khảo sát khả thi, đăng ký và theo dõi phát hành chứng chỉ carbon theo chuẩn quốc tế.',
                'content' => '<h4>Phát triển dự án và đăng ký Tín chỉ Carbon</h4><p>Hỗ trợ doanh nghiệp thực hiện các dự án tín chỉ carbon tự nguyện như Gold Standard, Verra, VCS.</p>',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'service_category_id' => $categories['nghien-cuu-chuyen-giao']->id,
                'name' => 'Nghiên cứu ứng dụng & Chuyển giao công nghệ',
                'slug' => 'nghien-cuu-chuyen-giao-cong-nghe-xanh',
                'short_description' => 'Thực hiện đề tài khoa học và chuyển giao công nghệ xanh, công nghệ hóa học sạch.',
                'content' => '<h4>Nghiên cứu khoa học và Chuyển giao công nghệ xanh</h4><p>Hỗ trợ doanh nghiệp ứng dụng các quy trình sản xuất sạch hơn, nâng cao hiệu quả tài nguyên và giảm thiểu ô nhiễm.</p>',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($servicesData as $s) {
            Service::query()->updateOrCreate(['slug' => $s['slug']], $s);
        }

        // 6. Seed Post Categories (At least 6 records)
        $postCatsData = [
            ['slug' => 'tin-tuc-su-kien', 'name' => 'Tin tức & Sự kiện', 'description' => 'Tin tức hoạt động và sự kiện nổi bật của Viện GREECO', 'sort_order' => 1, 'is_active' => true],
            ['slug' => 'nghien-cuu-khoa-hoc', 'name' => 'Nghiên cứu khoa học', 'description' => 'Các công trình nghiên cứu khoa học xanh mới nhất', 'sort_order' => 2, 'is_active' => true],
            ['slug' => 'hoat-dong-cong-dong', 'name' => 'Hoạt động cộng đồng', 'description' => 'Chương trình phát triển cộng đồng xanh bền vững', 'sort_order' => 3, 'is_active' => true],
            ['slug' => 'thong-bao-greeco', 'name' => 'Thông báo GREECO', 'description' => 'Thông báo chính thức từ ban điều hành Viện', 'sort_order' => 4, 'is_active' => true],
            ['slug' => 'goc-nhin-chuyen-gia', 'name' => 'Góc nhìn chuyên gia', 'description' => 'Các bài viết bình luận, phân tích từ chuyên gia GREECO', 'sort_order' => 5, 'is_active' => true],
            ['slug' => 'ban-tin-chuyen-doi-xanh', 'name' => 'Bản tin Chuyển đổi xanh', 'description' => 'Cập nhật chính sách chuyển đổi xanh trong và ngoài nước', 'sort_order' => 6, 'is_active' => true],
        ];

        $postCategories = [];
        foreach ($postCatsData as $pc) {
            $postCategories[$pc['slug']] = PostCategory::query()->updateOrCreate(['slug' => $pc['slug']], $pc);
        }

        // 7. Seed Posts (At least 6 records)
        // Copy blog assets to storage/posts if they don't exist
        $storagePostsDir = public_path('storage/posts');
        if (!file_exists($storagePostsDir)) {
            mkdir($storagePostsDir, 0755, true);
            chmod($storagePostsDir, 0755);
        }
        for ($i = 1; $i <= 6; $i++) {
            $source = public_path("assets/images/blog/{$i}.webp");
            $dest = "{$storagePostsDir}/{$i}.webp";
            if (file_exists($source)) {
                copy($source, $dest);
                chmod($dest, 0644);
            }
        }

        $postsData = [
            [
                'post_category_id' => $postCategories['tin-tuc-su-kien']->id,
                'title' => 'Định hướng thúc đẩy Kinh tế tuần hoàn tại Việt Nam',
                'slug' => 'dinh-huong-thuc-day-kinh-te-tuan-hoan-tai-viet-nam',
                'excerpt' => 'Việt Nam đang đứng trước cơ hội và thách thức lớn khi chuyển dịch từ mô hình kinh tế tuyến tính sang kinh tế tuần hoàn nhằm tối ưu hóa nguồn lực và phát triển bền vững.',
                'content' => '<p>Kinh tế tuần hoàn (KTTH) đang trở thành xu hướng toàn cầu và là giải pháp tất yếu để giải quyết hài hòa giữa phát triển kinh tế và bảo vệ môi trường. Tại Việt Nam, thúc đẩy KTTH không chỉ giúp giải quyết các thách thức về suy thoái tài nguyên, ô nhiễm môi trường mà còn là động lực thúc đẩy tăng trưởng bền vững và đạt cam kết phát thải ròng bằng không (Net Zero) vào năm 2050.</p>

<h3>Kinh tế tuần hoàn là gì?</h3>
<p>Khác với mô hình kinh tế tuyến tính truyền thống hoạt động theo chu trình: <strong>Khai thác -> Sản xuất -> Thải bỏ</strong>, mô hình kinh tế tuần hoàn chú trọng thiết kế chu trình khép kín: <strong>Thiết kế -> Sản xuất -> Tiêu dùng -> Thu hồi -> Tái sử dụng/Tái chế</strong>. Trong mô hình này, giá trị của nguyên vật liệu, sản phẩm được duy trì lâu nhất có thể, giảm thiểu tối đa chất thải ra môi trường.</p>

<blockquote class="blockquote p-3 bg-light border-start border-success border-4 my-4">
    <p class="mb-0 fs-16 italic">"Kinh tế tuần hoàn không chỉ đơn thuần là tái chế chất thải. Đó là sự thay đổi mang tính hệ thống từ khâu thiết kế sản phẩm, lựa chọn nguyên vật liệu, tối ưu hóa quy trình sản xuất cho đến chuỗi tiêu dùng và thu hồi."</p>
    <footer class="blockquote-footer mt-1 text-muted">Chuyên gia Môi trường tại Viện GREECO</footer>
</blockquote>

<h3>So sánh Mô hình Kinh tế Tuyến tính và Tuần hoàn</h3>
<div class="table-responsive my-4">
    <table class="table table-bordered table-striped">
        <thead class="table-success text-dark">
            <tr>
                <th>Tiêu chí</th>
                <th>Kinh tế Tuyến tính (Linear)</th>
                <th>Kinh tế Tuần hoàn (Circular)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Mục tiêu</strong></td>
                <td>Tối đa hóa sản lượng tiêu thụ</td>
                <td>Tối ưu hóa giá trị vòng đời tài nguyên</td>
            </tr>
            <tr>
                <td><strong>Sử dụng tài nguyên</strong></td>
                <td>Khai thác cạn kiệt tài nguyên thô</td>
                <td>Sử dụng vật liệu tái sinh và năng lượng sạch</td>
            </tr>
            <tr>
                <td><strong>Chất thải</strong></td>
                <td>Chôn lấp hoặc xả thải trực tiếp</td>
                <td>Tái sử dụng làm nguyên liệu đầu vào cho ngành khác</td>
            </tr>
            <tr>
                <td><strong>Thiết kế sản phẩm</strong></td>
                <td>Tuổi thọ ngắn, khó sửa chữa, khó tái chế</td>
                <td>Bền vững, dễ tháo lắp, dễ sửa chữa và tái chế</td>
            </tr>
        </tbody>
    </table>
</div>

<h3>Các trụ cột thúc đẩy Kinh tế tuần hoàn tại Việt Nam</h3>
<p>Việc hiện thực hóa kinh tế tuần hoàn đòi hỏi sự chung tay phối hợp chặt chẽ giữa Cơ quan Quản lý nhà nước, Doanh nghiệp và Người tiêu dùng. Cụ thể:</p>
<ul>
    <li><strong>Chính sách & Pháp lý:</strong> Hoàn thiện các cơ chế khuyến khích, ưu đãi thuế, đất đai cho các dự án xanh, dự án tái chế và sản xuất tuần hoàn.</li>
    <li><strong>Sự chủ động của Doanh nghiệp:</strong> Ứng dụng các quy trình sản xuất sạch hơn, tuần hoàn nguồn nước, giảm thiểu bao bì nhựa sử dụng một lần và chuyển dịch sang năng lượng tái tạo.</li>
    <li><strong>Ý thức người tiêu dùng:</strong> Thay đổi hành vi tiêu dùng từ việc lựa chọn các sản phẩm bền vững, thực hành phân loại rác tại nguồn và ủng hộ các sản phẩm tái chế.</li>
</ul>

<h3>Vai trò của Viện GREECO</h3>
<p>Viện Nghiên cứu và Phát triển Kinh tế Xanh (GREECO) tự hào là đơn vị tiên phong hỗ trợ các tỉnh thành và doanh nghiệp xây dựng lộ trình, mô hình kinh tế tuần hoàn thực chứng. Chúng tôi cung cấp các dịch vụ khảo sát, đánh giá hiện trạng dòng chảy nguyên liệu, đề xuất các giải pháp kỹ thuật tối ưu hóa tài nguyên và tổ chức tập huấn nâng cao nhận thức cho đội ngũ nhân sự.</p>',
                'thumbnail' => 'posts/1.webp',
                'meta_title' => 'Định hướng thúc đẩy Kinh tế tuần hoàn tại Việt Nam | GREECO',
                'meta_description' => 'Cập nhật xu hướng thúc đẩy mô hình kinh tế tuần hoàn tại Việt Nam. Tìm hiểu sự khác biệt giữa kinh tế tuần hoàn và tuyến tính, vai trò của GREECO.',
                'is_featured' => true,
                'is_indexable' => true,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'post_category_id' => $postCategories['tin-tuc-su-kien']->id,
                'title' => 'Chuyển dịch xanh và cơ hội cho doanh nghiệp Việt hướng tới Net Zero',
                'slug' => 'chuyen-dich-xanh-va-co-hoi-cho-doanh-nghiep-viet-huong-toi-net-zero',
                'excerpt' => 'Chuyển dịch xanh không còn là sự lựa chọn mà đã trở thành yêu cầu bắt buộc để doanh nghiệp Việt Nam giữ vững vị thế và nắm bắt cơ hội tham gia chuỗi cung ứng toàn cầu.',
                'content' => '<p>Cam kết đạt phát thải ròng bằng không (Net Zero) vào năm 2050 của Chính phủ Việt Nam tại Hội nghị COP26 đã mở ra một hành trình chuyển dịch xanh đầy thách thức nhưng cũng ngập tràn cơ hội cho cộng đồng doanh nghiệp. Các doanh nghiệp chuyển dịch sớm sẽ có lợi thế cạnh tranh vượt trội, đón đầu làn sóng đầu tư xanh và tiếp cận thị trường quốc tế khắt khe.</p>

<h3>Net Zero là gì và tại sao lại quan trọng?</h3>
<p>Net Zero (Phát thải ròng bằng 0) nghĩa là lượng khí nhà kính (chủ yếu là CO2) thải vào bầu khí quyển không lớn hơn lượng khí nhà kính được loại bỏ thông qua các giải pháp hấp thụ hoặc giảm thiểu tự nhiên/nhân tạo. Đối với doanh nghiệp, đạt Net Zero đồng nghĩa với việc tối ưu hóa quy trình để phát thải cực ít và bù đắp lượng phát thải còn lại bằng cách mua tín chỉ carbon hoặc trồng rừng.</p>

<blockquote class="blockquote p-3 bg-light border-start border-success border-4 my-4">
    <p class="mb-0 fs-16 italic">"Chuyển đổi xanh không phải là chi phí, đó là khoản đầu tư cho tương lai. Các doanh nghiệp chậm chân sẽ sớm bị loại khỏi chuỗi cung ứng toàn cầu khi các thị trường lớn như EU áp dụng CBAM."</p>
    <footer class="blockquote-footer mt-1 text-muted">Hội đồng Khoa học GREECO</footer>
</blockquote>

<h3>Lộ trình 5 bước chuyển đổi xanh hướng tới Net Zero</h3>
<p>Để giúp doanh nghiệp dễ dàng tiếp cận và triển khai, Viện GREECO đề xuất lộ trình chuyển đổi xanh gồm 5 bước cốt lõi sau:</p>
<ol>
    <li><strong>Kiểm kê khí nhà kính (GHG Accounting):</strong> Xác định nguồn và lượng phát thải tại cơ sở theo tiêu chuẩn ISO 14064-1 hoặc GHG Protocol.</li>
    <li><strong>Thiết lập mục tiêu giảm phát thải khoa học (SBTi):</strong> Xây dựng mục tiêu ngắn hạn, trung hạn và dài hạn phù hợp với kịch bản tăng nhiệt độ toàn cầu 1.5°C.</li>
    <li><strong>Tối ưu hóa năng lượng & hiệu quả vận hành:</strong> Cải tiến công nghệ, tự động hóa, giảm thất thoát năng lượng trong hệ thống chiếu sáng, điều hòa và máy nén khí.</li>
    <li><strong>Ứng dụng năng lượng sạch:</strong> Lắp đặt hệ thống điện mặt trời áp mái, mua điện xanh (DPPA) hoặc sử dụng nhiên liệu sinh học (biomass).</li>
    <li><strong>Trung hòa phát thải (Carbon Offsetting):</strong> Đầu tư vào các dự án tín chỉ carbon uy tín để bù đắp cho lượng phát thải không thể giảm thiểu.</li>
</ol>

<h3>Cơ hội nâng cao năng lực cạnh tranh toàn cầu</h3>
<p>Việc thực hành chuyển dịch xanh mang lại nhiều giá trị thực tế cho doanh nghiệp:</p>
<ul>
    <li><strong>Vượt rào cản thương mại quốc tế:</strong> Đáp ứng yêu cầu báo cáo phát thải của các thị trường châu Âu, Mỹ, Nhật Bản (như đạo luật CBAM).</li>
    <li><strong>Thu hút dòng vốn đầu tư:</strong> Các quỹ tài chính lớn ngày nay ưu tiên rót vốn vào các doanh nghiệp tích hợp tốt tiêu chí ESG và có lộ trình giảm carbon rõ ràng.</li>
    <li><strong>Xây dựng uy tín thương hiệu:</strong> Tăng cường sự tin cậy từ phía khách hàng và đối tác kinh doanh thân thiện với môi trường.</li>
</ul>

<h3>Đồng hành cùng GREECO trên hành trình Net Zero</h3>
<p>Viện GREECO cung cấp giải pháp trọn gói từ tư vấn kiểm kê khí nhà kính, xây dựng báo cáo kiểm kê phát thải, đến thiết lập lộ trình giảm thiểu phát thải hiệu quả. Chúng tôi cam kết đồng hành cùng doanh nghiệp kiến tạo những giá trị bền vững và trung hòa Carbon hiệu quả.</p>',
                'thumbnail' => 'posts/2.webp',
                'meta_title' => 'Chuyển dịch xanh và lộ trình hướng tới Net Zero | GREECO',
                'meta_description' => 'Tìm hiểu lộ trình chuyển dịch xanh và cơ hội cho doanh nghiệp Việt hướng tới Net Zero vào năm 2050. Dịch vụ kiểm kê khí nhà kính từ Viện GREECO.',
                'is_featured' => true,
                'is_indexable' => true,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'post_category_id' => $postCategories['nghien-cuu-khoa-hoc']->id,
                'title' => 'Vai trò của Tín chỉ Carbon trong cuộc chiến chống biến đổi khí hậu',
                'slug' => 'vai-tro-cua-tin-chi-carbon-trong-cuoc-chien-chong-bien-doi-khi-hau',
                'excerpt' => 'Tìm hiểu cơ chế hoạt động, giá trị kinh tế và đóng góp bảo vệ môi trường từ thị trường tín chỉ carbon trong bối cảnh phát triển bền vững.',
                'content' => '<p>Trong bối cảnh nhiệt độ toàn cầu ngày càng tăng cao, thị trường tín chỉ carbon nổi lên như một công cụ tài chính mạnh mẽ và hiệu quả nhất nhằm định giá phát thải carbon, tạo động lực kinh tế cho các tổ chức, doanh nghiệp cắt giảm khí nhà kính.</p>

<h3>Tín chỉ carbon là gì?</h3>
<p>Một tín chỉ carbon tương đương với <strong>1 tấn khí CO2</strong> (hoặc lượng khí nhà kính khác quy đổi tương đương) đã được giảm thiểu, hấp thụ hoặc tránh phát thải vào bầu khí quyển thông qua một dự án bảo vệ môi trường đã được xác minh độc lập (như trồng rừng, lắp đặt năng lượng tái tạo, thu hồi khí sinh học).</p>

<blockquote class="blockquote p-3 bg-light border-start border-success border-4 my-4">
    <p class="mb-0 fs-16 italic">"Bằng cách biến việc giảm phát thải thành một tài sản có thể mua bán được, thị trường carbon tạo ra nguồn doanh thu mới cho các dự án bảo vệ rừng và phát triển năng lượng sạch."</p>
    <footer class="blockquote-footer mt-1 text-muted">Chuyên gia Tín chỉ Carbon tại GREECO</footer>
</blockquote>

<h3>Thị trường Carbon Tự nguyện vs. Thị trường Carbon Bắt buộc</h3>
<p>Thị trường carbon được chia thành hai phân khúc chính:</p>
<ul>
    <li><strong>Thị trường bắt buộc (Compliance Market):</strong> Hoạt động theo quy định của pháp luật quốc gia hoặc quốc tế (như hệ thống ETS của châu Âu). Cơ quan quản lý đặt ra hạn ngạch phát thải cho từng doanh nghiệp; đơn vị nào phát thải vượt mức phải mua thêm hạn ngạch/tín chỉ từ đơn vị phát thải dưới mức.</li>
    <li><strong>Thị trường tự nguyện (Voluntary Market):</strong> Cho phép các tổ chức, tập đoàn tự nguyện mua tín chỉ carbon để hiện thực hóa cam kết trung hòa carbon hoặc ESG của mình mà không bị ràng buộc về pháp lý. Các chuẩn phổ biến gồm Gold Standard (GS) và Verified Carbon Standard (VCS/Verra).</li>
</ul>

<h3>Lợi ích kinh tế xanh từ phát triển dự án Tín chỉ Carbon</h3>
<p>Việt Nam có tiềm năng rất lớn về phát triển các dự án tạo tín chỉ carbon nhờ diện tích rừng che phủ lớn và tốc độ phát triển năng lượng tái tạo nhanh. Các lợi ích mang lại bao gồm:</p>
<ol>
    <li><strong>Tạo dòng tiền tài chính xanh:</strong> Doanh thu từ bán tín chỉ carbon giúp tái đầu tư vào dự án, nâng cao hiệu quả tài chính.</li>
    <li><strong>Bảo vệ đa dạng sinh học & sinh kế:</strong> Các dự án lâm nghiệp không chỉ hấp thụ CO2 mà còn tạo việc làm bền vững cho người dân địa phương bảo vệ rừng.</li>
    <li><strong>Nâng tầm thương hiệu:</strong> Doanh nghiệp sở hữu dự án tín chỉ carbon khẳng định vị thế dẫn đầu trong trách nhiệm xã hội.</li>
</ol>

<h3>Dịch vụ tư vấn phát triển dự án Tín chỉ Carbon của GREECO</h3>
<p>Viện GREECO sở hữu mạng lưới chuyên gia quốc tế hỗ trợ doanh nghiệp trọn gói từ khâu: Khảo sát khả thi dự án (Feasibility Study), thiết kế tài liệu dự án (PDD), giám sát và thẩm định (Validation/Verification), đăng ký và phát hành tín chỉ trên các sàn giao dịch carbon quốc tế uy tín.</p>',
                'thumbnail' => 'posts/3.webp',
                'meta_title' => 'Vai trò của Tín chỉ Carbon trong biến đổi khí hậu | GREECO',
                'meta_description' => 'Tìm hiểu tín chỉ carbon là gì, cơ chế thị trường carbon tự nguyện và bắt buộc, và tiềm năng tài chính xanh từ việc phát triển tín chỉ carbon tại Việt Nam.',
                'is_featured' => true,
                'is_indexable' => true,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'post_category_id' => $postCategories['ban-tin-chuyen-doi-xanh']->id,
                'title' => 'Báo cáo ĐTM và các điểm mới trong Luật Bảo vệ Môi trường',
                'slug' => 'bao-cao-dtm-va-cac-diem-moi-trong-luat-bao-ve-moi-truong',
                'excerpt' => 'Tổng hợp các quy định pháp lý mới nhất về quy trình thẩm định báo cáo đánh giá tác động môi trường (ĐTM).',
                'content' => '<p>Đánh giá tác động môi trường (ĐTM) là công cụ pháp lý và khoa học quan trọng giúp dự báo, phòng ngừa các tác động xấu đến môi trường từ các dự án đầu tư phát triển. Việc cập nhật các điểm mới trong Luật Bảo vệ Môi trường là cực kỳ thiết yếu đối với các chủ đầu tư để đảm bảo tuân thủ pháp luật và đẩy nhanh tiến độ dự án.</p>

<h3>Phân loại dự án đầu tư theo tiêu chí môi trường</h3>
<p>Một trong những điểm mới căn bản nhất là phân loại dự án đầu tư thành 4 nhóm (Nhóm I, II, III và IV) dựa trên quy mô, công suất, loại hình sản xuất, diện tích sử dụng đất, tài nguyên nước và vị trí nhạy cảm về môi trường. Quy định này giúp tinh giản thủ tục hành chính, tập trung kiểm soát chặt chẽ các dự án có nguy cơ ô nhiễm cao:</p>
<ul>
    <li><strong>Nhóm I:</strong> Dự án có nguy cơ cao gây ô nhiễm môi trường (Bắt buộc phải làm ĐTM và đánh giá sơ bộ tác động môi trường).</li>
    <li><strong>Nhóm II:</strong> Dự án có nguy cơ gây ô nhiễm môi trường (Phải làm ĐTM trừ các trường hợp đặc biệt).</li>
    <li><strong>Nhóm III:</strong> Dự án ít có nguy cơ gây ô nhiễm môi trường (Không cần làm ĐTM, chỉ cần đăng ký môi trường hoặc cấp Giấy phép môi trường).</li>
    <li><strong>Nhóm IV:</strong> Dự án không có nguy cơ gây ô nhiễm môi trường (Được miễn toàn bộ thủ tục môi trường).</li>
</ul>

<h3>Quy trình thực hiện ĐTM chuẩn chỉnh</h3>
<p>Để báo cáo ĐTM được thông qua hội đồng thẩm định nhanh chóng, chủ đầu tư cần phối hợp chặt chẽ với đơn vị tư vấn có năng lực để thực hiện theo các bước:</p>
<ol>
    <li>Khảo sát điều kiện tự nhiên, kinh tế - xã hội xung quanh khu vực dự án.</li>
    <li>Đo đạc, phân tích mẫu không khí, đất, nước mặt, nước ngầm để xác định hiện trạng môi trường nền.</li>
    <li>Dự báo, đánh giá các tác động môi trường trong giai đoạn thi công xây dựng và vận hành dự án.</li>
    <li>Đề xuất các công trình và biện pháp giảm thiểu ô nhiễm (hệ thống xử lý nước thải, khí thải, bụi, chất thải nguy hại).</li>
    <li>Tham vấn ý kiến cộng đồng dân cư chịu ảnh hưởng trực tiếp bởi dự án.</li>
    <li>Trình nộp báo cáo và bảo vệ trước Hội đồng thẩm định cấp Bộ hoặc cấp Tỉnh.</li>
</ol>

<h3>Năng lực tư vấn ĐTM của Viện GREECO</h3>
<p>Với đội ngũ kỹ sư môi trường giàu kinh nghiệm và hệ thống phòng thí nghiệm hiện đại, Viện GREECO cam kết cung cấp dịch vụ tư vấn lập báo cáo ĐTM, Giấy phép môi trường (GPMT) và Đăng ký môi trường chính xác, chất lượng cao, đúng tiến độ và tuân thủ chặt chẽ các quy định pháp luật mới nhất.</p>',
                'thumbnail' => 'posts/4.webp',
                'meta_title' => 'Điểm mới về báo cáo ĐTM trong Luật Bảo vệ Môi trường | GREECO',
                'meta_description' => 'Hướng dẫn phân loại dự án đầu tư nhóm I, II, III, IV và quy trình lập báo cáo Đánh giá tác động môi trường (ĐTM) theo Luật Bảo vệ Môi trường mới nhất.',
                'is_featured' => false,
                'is_indexable' => true,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'post_category_id' => $postCategories['goc-nhin-chuyen-gia']->id,
                'title' => 'Ứng dụng năng lượng tái tạo và công nghệ tiết kiệm điện trong sản xuất',
                'slug' => 'ung-dung-nang-luong-tai-tao-va-cong-nghe-tiet-kiem-dien-trong-san-xuat',
                'excerpt' => 'Các giải pháp kỹ thuật tối ưu giảm tiêu thụ điện năng cho hệ thống điều hòa, chiếu sáng và máy nén khí cho doanh nghiệp sản xuất.',
                'content' => '<p>Chi phí điện năng luôn chiếm tỷ trọng lớn trong cơ cấu giá thành sản phẩm của ngành công nghiệp sản xuất. Trước sức ép của việc tăng giá điện và các tiêu chuẩn xuất khẩu xanh, ứng dụng năng lượng tái tạo và công nghệ tiết kiệm điện là chìa khóa vàng giúp doanh nghiệp phát triển bền vững và tối ưu hóa lợi nhuận.</p>

<h3>Xu hướng chuyển dịch sang năng lượng tái tạo</h3>
<p>Nhiều nhà máy hiện nay đang tích cực lắp đặt hệ thống điện mặt trời áp mái tự sản tự tiêu. Giải pháp này giúp cắt giảm tiền điện ở các khung giờ cao điểm, giảm nhiệt độ mái nhà xưởng từ 3-5°C (giúp tiết kiệm điện cho hệ thống làm mát) và cung cấp nguồn chứng chỉ năng lượng tái tạo (REC) phục vụ xuất khẩu.</p>

<blockquote class="blockquote p-3 bg-light border-start border-success border-4 my-4">
    <p class="mb-0 fs-16 italic">"Kiểm toán năng lượng định kỳ là điểm khởi đầu thiết yếu. Doanh nghiệp không thể tiết kiệm những gì họ không đo lường được."</p>
    <footer class="blockquote-footer mt-1 text-muted">Ban Chuyên gia Năng lượng GREECO</footer>
</blockquote>

<h3>Các công nghệ và giải pháp tiết kiệm điện năng hiệu quả</h3>
<p>Doanh nghiệp có thể áp dụng ngay các giải pháp kỹ thuật sau để tối ưu hóa điện năng tiêu thụ:</p>
<ol>
    <li><strong>Sử dụng biến tần (VFD):</strong> Lắp đặt biến tần cho hệ thống động cơ, bơm nước, quạt gió, máy nén khí giúp điều chỉnh công suất hoạt động theo nhu cầu thực tế, tiết kiệm từ 15% - 30% điện năng.</li>
    <li><strong>Chuẩn hóa hệ thống khí nén:</strong> Thường xuyên rà soát rò rỉ khí nén, tối ưu hóa áp suất vận hành của máy nén khí (giảm 1 bar áp suất giúp tiết kiệm 7% điện năng của máy).</li>
    <li><strong>Chuyển đổi sang đèn LED thông minh:</strong> Thay thế các bóng đèn huỳnh quang, cao áp bằng đèn LED hiệu suất cao kết hợp cảm biến chuyển động và ánh sáng tự nhiên.</li>
    <li><strong>Hệ thống quản lý năng lượng (EMS):</strong> Tích hợp phần mềm đo lường và giám sát điện năng theo thời gian thực để phát hiện các khu vực tiêu thụ điện bất thường.</li>
</ol>

<h3>Kiểm toán năng lượng cùng Viện GREECO</h3>
<p>Viện GREECO cung cấp dịch vụ kiểm toán năng lượng toàn diện theo quy định của Luật Sử dụng năng lượng tiết kiệm và hiệu quả. Chúng tôi giúp doanh nghiệp xác định chi tiết các cơ hội tiết kiệm năng lượng, tính toán thời gian hoàn vốn (ROI) cho từng giải pháp cải tiến kỹ thuật cụ thể và hỗ trợ doanh nghiệp tối ưu hóa chi phí vận hành ở mức cao nhất.</p>',
                'thumbnail' => 'posts/5.webp',
                'meta_title' => 'Giải pháp Tiết kiệm điện & Năng lượng tái tạo trong sản xuất | GREECO',
                'meta_description' => 'Khám phá xu hướng ứng dụng điện mặt trời áp mái và các giải pháp kỹ thuật tiết kiệm điện năng như biến tần, tối ưu khí nén để giảm chi phí sản xuất cho doanh nghiệp.',
                'is_featured' => true,
                'is_indexable' => true,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'post_category_id' => $postCategories['hoat-dong-cong-dong']->id,
                'title' => 'ESG - Thước đo năng lực cạnh tranh toàn cầu mới của doanh nghiệp',
                'slug' => 'esg-thuoc-do-nang-luc-canh-tranh-toan-cau-moi-cua-doanh-nghiep',
                'excerpt' => 'Tiêu chuẩn Môi trường, Xã hội và Quản trị đang trở thành điều kiện tiên quyết thu hút nhà đầu tư FDI và đối tác toàn cầu.',
                'content' => '<p>ESG (Environmental - Môi trường, Social - Xã hội, Governance - Quản trị) đã vượt qua ranh giới của một xu hướng tự nguyện để trở thành thước đo cốt lõi đánh giá mức độ phát triển bền vững và khả năng chống chịu của một doanh nghiệp trong dài hạn. Áp dụng ESG giúp doanh nghiệp mở rộng cánh cửa hội nhập sâu rộng vào nền kinh tế toàn cầu.</p>

<h3>Ba trụ cột cốt lõi của tiêu chuẩn ESG</h3>
<p>Tiêu chuẩn ESG được cấu thành bởi ba trụ cột chính, mỗi trụ cột bao gồm nhiều tiêu chí đánh giá chi tiết:</p>

<div class="row my-4 g-3">
    <div class="col-md-4">
        <div class="p-3 bg-light rounded-1 border h-100">
            <h5 class="text-success"><i class="fa fa-leaf me-2"></i>E - Environmental</h5>
            <p class="fs-14 mb-0 text-muted">Liên quan đến việc quản lý tài nguyên, kiểm kê khí nhà kính, xử lý chất thải, bảo tồn đa dạng sinh học và thích ứng biến đổi khí hậu.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-light rounded-1 border h-100">
            <h5 class="text-primary"><i class="fa fa-users me-2"></i>S - Social</h5>
            <p class="fs-14 mb-0 text-muted">Tập quan hệ với người lao động (an toàn HSE, công bằng lương, đào tạo), khách hàng, đối tác và đóng góp cho cộng đồng địa phương.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-light rounded-1 border h-100">
            <h5 class="text-dark"><i class="fa fa-shield-halved me-2"></i>G - Governance</h5>
            <p class="fs-14 mb-0 text-muted">Đánh giá tính minh bạch thông tin, đạo đức kinh doanh, phòng chống tham nhũng, quyền lợi của cổ đông và cấu trúc ban điều hành.</p>
        </div>
    </div>
</div>

<h3>Tại sao ESG lại trở thành điều kiện bắt buộc?</h3>
<p>Các doanh nghiệp bỏ qua tiêu chuẩn ESG sẽ phải đối mặt với nhiều rủi ro lớn:</p>
<ul>
    <li><strong>Khó khăn trong xuất khẩu:</strong> Các tập đoàn đa quốc gia ngày càng thắt chặt tiêu chí xanh đối với nhà cung cấp cấp 1, cấp 2 ở các nước đang phát triển.</li>
    <li><strong>Rào cản tiếp cận vốn:</strong> Hầu hết các ngân hàng thương mại và quỹ đầu tư quốc tế đã cam kết không cấp vốn hoặc áp thuế suất cao đối với các doanh nghiệp có hồ sơ môi trường hoặc quản trị kém.</li>
    <li><strong>Rủi ro pháp lý & hình ảnh:</strong> Các sự cố về ô nhiễm môi trường hoặc vi phạm an toàn lao động có thể hủy hoại thương hiệu của doanh nghiệp ngay lập tức.</li>
</ul>

<h3>Lộ trình tích hợp ESG cùng Viện GREECO</h3>
<p>Viện GREECO cung cấp giải pháp tư vấn lộ trình tích hợp ESG tùy biến theo quy mô và ngành nghề của doanh nghiệp. Dịch vụ của chúng tôi bao gồm: Đào tạo nhận thức ESG, đánh giá mức độ sẵn sàng, xác định các chủ đề trọng yếu, thiết lập hệ thống thu thập dữ liệu và hỗ trợ lập Báo cáo Phát triển Bền vững đạt chuẩn quốc tế GRI.</p>',
                'thumbnail' => 'posts/6.webp',
                'meta_title' => 'Tiêu chuẩn ESG và lộ trình tích hợp cho doanh nghiệp | GREECO',
                'meta_description' => 'Tìm hiểu 3 trụ cột của ESG (Môi trường, Xã hội, Quản trị) và lý do tại sao tiêu chuẩn này là thước đo năng lực cạnh tranh toàn cầu mới của doanh nghiệp.',
                'is_featured' => false,
                'is_indexable' => true,
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        $tagsMapping = [
            'dinh-huong-thuc-day-kinh-te-tuan-hoan-tai-viet-nam' => ['Kinh tế xanh', 'Kinh tế tuần hoàn', 'Tái chế'],
            'chuyen-dich-xanh-va-co-hoi-cho-doanh-nghiep-viet-huong-toi-net-zero' => ['Net Zero', 'ESG', 'Chuyển đổi xanh'],
            'vai-tro-cua-tin-chi-carbon-trong-cuoc-chien-chong-bien-doi-khi-hau' => ['Carbon', 'Tín chỉ carbon', 'Biến đổi khí hậu'],
            'bao-cao-dtm-va-cac-diem-moi-trong-luat-bao-ve-moi-truong' => ['ĐTM', 'Môi trường', 'Pháp luật'],
            'ung-dung-nang-luong-tai-tao-va-cong-nghe-tiet-kiem-dien-trong-san-xuat' => ['Năng lượng tái tạo', 'Tiết kiệm điện'],
            'esg-thuoc-do-nang-luc-canh-tranh-toan-cau-moi-cua-doanh-nghiep' => ['ESG', 'Môi trường', 'Quản trị'],
        ];

        foreach ($postsData as $p) {
            $p['tags'] = $tagsMapping[$p['slug']] ?? [];
            Post::query()->updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 8. Seed Projects (At least 6 records)
        $projectsData = [
            [
                'title' => 'Dự án Lâm nghiệp Bền vững Serenity',
                'slug' => 'du-an-lam-nghiep-ben-vung-serenity',
                'excerpt' => 'Nghiên cứu bảo tồn đa dạng sinh học và phát triển hệ sinh thái rừng tự nhiên bền vững chống xói mòn đất.',
                'content' => '<h4>Dự án Lâm nghiệp Bền vững Serenity</h4><p>Thực hiện tại Quảng Ninh nhằm phục hồi thảm thực vật bản địa, đo lường lượng hấp thụ carbon và nâng cao sinh kế cho cộng đồng địa phương.</p>',
                'client_name' => 'Quỹ Môi trường Toàn cầu',
                'location' => 'Quảng Ninh',
                'started_at' => '2025-01-01',
                'completed_at' => '2026-06-01',
                'is_featured' => true,
                'is_indexable' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Sắc màu Tự nhiên',
                'slug' => 'sac-mau-tu-nhien',
                'excerpt' => 'Nghiên cứu bảo tồn đa dạng sinh học và phát triển hệ sinh thái rừng tự nhiên bền vững chống xói mòn đất.',
                'content' => '<h4>Dự án Sắc màu Tự nhiên</h4><p>Dự án hợp tác quốc tế nghiên cứu hệ thực vật sinh thái bản địa tại California, xây dựng mô hình quản lý rừng bền vững thích ứng cháy rừng.</p>',
                'client_name' => 'Viện Sinh thái Học California',
                'location' => 'California',
                'started_at' => '2025-03-15',
                'completed_at' => '2025-12-20',
                'is_featured' => true,
                'is_indexable' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Trạm Nghiên cứu Công nghệ Sinh học Cây trồng',
                'slug' => 'tram-nghien-cuu-cong-nghe-sinh-hoc-cay-trong',
                'excerpt' => 'Nghiên cứu ứng dụng công nghệ sinh học nông nghiệp thích ứng và phát triển giống cây chịu hạn.',
                'content' => '<h4>Trạm Nghiên cứu Công nghệ Sinh học Cây trồng</h4><p>Triển khai tại Lâm Đồng nhằm nghiên cứu, khảo nghiệm và nhân giống các loại cây trồng chịu nhiệt cao, giảm lượng nước tưới cần thiết.</p>',
                'client_name' => 'Sở Khoa học và Công nghệ Lâm Đồng',
                'location' => 'Lâm Đồng',
                'started_at' => '2024-06-01',
                'completed_at' => '2025-06-01',
                'is_featured' => true,
                'is_indexable' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Dự án Tín chỉ Carbon Rừng Cộng đồng',
                'slug' => 'du-an-tin-chi-carbon-rung-cong-dong',
                'excerpt' => 'Nghiên cứu bảo tồn đa dạng sinh học và phát triển hệ sinh thái rừng tự nhiên bền vững chống xói mòn đất.',
                'content' => '<h4>Dự án Tín chỉ Carbon Rừng Cộng đồng</h4><p>Hỗ trợ đồng bào dân tộc tại Sơn La đo đạc sinh khối rừng, đăng ký tín chỉ carbon tự nguyện đạt tiêu chuẩn Verra/VCS.</p>',
                'client_name' => 'Tổ chức Phát triển Đức (GIZ)',
                'location' => 'Sơn La',
                'started_at' => '2025-02-01',
                'completed_at' => '2025-10-31',
                'is_featured' => false,
                'is_indexable' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Hệ thống Điện mặt trời Áp mái Kết hợp',
                'slug' => 'he-thong-dien-mat-troi-ap-mai-ket-hop',
                'excerpt' => 'Nghiên cứu bảo tồn đa dạng sinh học và phát triển hệ sinh thái rừng tự nhiên bền vững chống xói mòn đất.',
                'content' => '<h4>Hệ thống Điện mặt trời Áp mái Kết hợp</h4><p>Thiết kế lắp đặt hệ thống pin mặt trời thông minh tích hợp lưới điện sản xuất, tối ưu hóa công suất phụ tải tại Bình Thuận.</p>',
                'client_name' => 'Công ty Năng lượng Xanh Bình Thuận',
                'location' => 'Bình Thuận',
                'started_at' => '2024-09-01',
                'completed_at' => '2025-03-01',
                'is_featured' => false,
                'is_indexable' => true,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Đánh giá Tác động Môi trường KCN Xanh',
                'slug' => 'danh-gia-tac-dong-moi-truong-kcn-xanh',
                'excerpt' => 'Nghiên cứu bảo tồn đa dạng sinh học và phát triển hệ sinh thái rừng tự nhiên bền vững chống xói mòn đất.',
                'content' => '<h4>Đánh giá Tác động Môi trường KCN Xanh</h4><p>Thực hiện báo cáo ĐTM toàn diện cho khu công nghiệp sinh thái, đo đạc chỉ số phát thải, nước thải và tiếng ồn tại Bình Dương.</p>',
                'client_name' => 'Tổng công ty phát triển hạ tầng Becamex',
                'location' => 'Bình Dương',
                'started_at' => '2025-05-01',
                'completed_at' => '2026-05-01',
                'is_featured' => true,
                'is_indexable' => true,
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($projectsData as $pj) {
            Project::query()->updateOrCreate(['slug' => $pj['slug']], $pj);
        }

        // 9. Seed Partners (At least 6 records)
        $this->call(PartnerSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(TeamMemberSeeder::class);

        // 10. Seed Contact messages (At least 6 records)
        $contactsData = [
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'vana@gmail.com',
                'phone' => '0936996391',
                'subject' => 'Yêu cầu tư vấn ISO 14001',
                'message' => 'Tôi muốn được tư vấn lộ trình xây dựng và cấp chứng nhận ISO 14001 cho nhà máy sản xuất bao bì.',
                'is_read' => false,
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'thib@gmail.com',
                'phone' => '0936996392',
                'subject' => 'Hợp tác nghiên cứu vật liệu xanh',
                'message' => 'Đại diện doanh nghiệp muốn đặt hàng nghiên cứu sản xuất khay đựng từ bã mía tự hủy sinh học.',
                'is_read' => true,
            ],
            [
                'name' => 'Phạm Văn C',
                'email' => 'vanc@gmail.com',
                'phone' => '0936996393',
                'subject' => 'Đăng ký khóa học An toàn lao động',
                'message' => 'Xin báo giá khóa đào tạo an toàn lao động nhóm 3 cho 50 công nhân tại Bình Dương.',
                'is_read' => false,
            ],
            [
                'name' => 'Lê Văn D',
                'email' => 'vand@gmail.com',
                'phone' => '0936996394',
                'subject' => 'Tư vấn Báo cáo phát triển bền vững ESG',
                'message' => 'Doanh nghiệp xuất khẩu thủy sản cần tìm hiểu dịch vụ lập báo cáo đánh giá ESG theo chuẩn GRI.',
                'is_read' => false,
            ],
            [
                'name' => 'Hoàng Thị E',
                'email' => 'thie@gmail.com',
                'phone' => '0936996395',
                'subject' => 'Hỏi về kiểm kê khí nhà kính',
                'message' => 'Báo giá dịch vụ kiểm kê khí nhà kính cấp cơ sở cho doanh nghiệp may mặc tại Hải Phòng.',
                'is_read' => true,
            ],
            [
                'name' => 'Vũ Văn F',
                'email' => 'vanf@gmail.com',
                'phone' => '0936996396',
                'subject' => 'Đề xuất liên kết đào tạo HSE',
                'message' => 'Mong muốn liên kết tổ chức các khóa tập huấn vệ sinh lao động định kỳ năm 2026.',
                'is_read' => false,
            ],
        ];

        foreach ($contactsData as $ct) {
            Contact::query()->updateOrCreate(['email' => $ct['email'], 'subject' => $ct['subject']], $ct);
        }

        // 11. Seed Website Settings (At least 6 records)
        $storageSettingsDir = public_path('storage/settings');
        if (!file_exists($storageSettingsDir)) {
            mkdir($storageSettingsDir, 0755, true);
            chmod($storageSettingsDir, 0755);
        }

        $filesToCopy = [
            ['src' => 'logo-icon.webp', 'dest' => 'favicon.webp'],
            ['src' => 'favicon.png', 'dest' => 'favicon.png'],
            ['src' => 'logo-text-white-cropped.png', 'dest' => 'logo_light.png'],
            ['src' => 'logo-text-cropped.png', 'dest' => 'logo_dark.png'],
            ['src' => 'logo-icon.webp', 'dest' => 'logo_icon.webp'],
            ['src' => 'logo-wm.webp', 'dest' => 'logo_wm.webp'],
            ['src' => 'icons/tree.png', 'dest' => 'icon_journey_tree.png'],
            ['src' => 'icons/happy.png', 'dest' => 'icon_journey_happy.png'],
            ['src' => 'blog/1.webp', 'dest' => 'contact_image.webp'],
        ];

        foreach ($filesToCopy as $file) {
            $srcPath = public_path("assets/images/{$file['src']}");
            $destPath = "{$storageSettingsDir}/{$file['dest']}";
            if (file_exists($srcPath)) {
                copy($srcPath, $destPath);
                chmod($destPath, 0644);
            }
        }

        // Copy capability profile PDF to settings storage
        $pdfSrc = public_path('assets/docs/GREECO_Capability_Profile.pdf');
        $pdfDest = "{$storageSettingsDir}/GREECO_Capability_Profile.pdf";
        if (file_exists($pdfSrc)) {
            copy($pdfSrc, $pdfDest);
            chmod($pdfDest, 0644);
        }

        $settingsData = [
            ['key' => 'site_name', 'value' => 'Viện Nghiên cứu và Phát triển Kinh tế Xanh - GREECO', 'type' => 'text', 'group' => 'general'],
            ['key' => 'phone', 'value' => '09369 96390', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'phone_2', 'value' => '', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'email', 'value' => 'info@greeco.vn', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'address', 'value' => '150 Đường 38-CL, Phường Cát Lái, TP. HCM', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'work_hours', 'value' => 'Thứ 2 - Thứ 7: 08:00 - 17:00', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'facebook_url', 'value' => 'https://www.facebook.com/greecoofficial?locale=vi_VN', 'type' => 'text', 'group' => 'social'],
            ['key' => 'twitter_url', 'value' => '#', 'type' => 'text', 'group' => 'social'],
            ['key' => 'youtube_url', 'value' => '#', 'type' => 'text', 'group' => 'social'],
            ['key' => 'pinterest_url', 'value' => '#', 'type' => 'text', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => '#', 'type' => 'text', 'group' => 'social'],
            ['key' => 'whatsapp_url', 'value' => '#', 'type' => 'text', 'group' => 'social'],
            ['key' => 'contact_image', 'value' => 'settings/contact_image.webp', 'type' => 'image', 'group' => 'contact'],
            ['key' => 'favicon', 'value' => 'settings/favicon.webp', 'type' => 'image', 'group' => 'general'],
            ['key' => 'logo_light', 'value' => 'settings/logo_light.png', 'type' => 'image', 'group' => 'general'],
            ['key' => 'logo_dark', 'value' => 'settings/logo_dark.png', 'type' => 'image', 'group' => 'general'],
            ['key' => 'logo_icon', 'value' => 'settings/logo_icon.webp', 'type' => 'image', 'group' => 'general'],
            ['key' => 'logo_wm', 'value' => 'settings/logo_wm.webp', 'type' => 'image', 'group' => 'general'],
            ['key' => 'icon_journey_tree', 'value' => 'settings/icon_journey_tree.png', 'type' => 'image', 'group' => 'general'],
            ['key' => 'icon_journey_happy', 'value' => 'settings/icon_journey_happy.png', 'type' => 'image', 'group' => 'general'],
            ['key' => 'capability_profile', 'value' => 'settings/GREECO_Capability_Profile.pdf', 'type' => 'file', 'group' => 'general'],
            ['key' => 'zalo_url', 'value' => '', 'type' => 'text', 'group' => 'social'],
            ['key' => 'messenger_url', 'value' => '', 'type' => 'text', 'group' => 'social'],
        ];

        foreach ($settingsData as $st) {
            Setting::query()->updateOrCreate(['key' => $st['key']], $st);
        }

        // 12. Seed SEO Redirects (At least 6 records)
        $redirectsData = [
            ['old_url' => '/old-services', 'new_url' => '/dich-vu', 'status_code' => 301, 'is_active' => true],
            ['old_url' => '/old-news', 'new_url' => '/tin-tuc', 'status_code' => 301, 'is_active' => true],
            ['old_url' => '/old-about-us', 'new_url' => '/gioi-thieu', 'status_code' => 301, 'is_active' => true],
            ['old_url' => '/old-contact-us', 'new_url' => '/lien-he', 'status_code' => 301, 'is_active' => true],
            ['old_url' => '/old-projects', 'new_url' => '/du-an', 'status_code' => 301, 'is_active' => true],
            ['old_url' => '/cu-greeco-news', 'new_url' => '/tin-tuc', 'status_code' => 301, 'is_active' => true],
        ];

        foreach ($redirectsData as $rd) {
            SeoRedirect::query()->updateOrCreate(['old_url' => $rd['old_url']], $rd);
        }

        // 13. Seed Legal Documents (At least 6 records)
        $documentsData = [
            [
                'number' => 'Luật số 72/2020/QH14',
                'type' => 'Luật Quốc hội',
                'title' => 'Luật Bảo vệ Môi trường 2020',
                'description' => 'Khung pháp lý cao nhất về bảo vệ môi trường, quản lý chất thải, đánh giá tác động môi trường, và các công cụ kinh tế môi trường.',
                'link' => 'https://vanban.chinhphu.vn/?pageid=27160&docid=202616',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'number' => 'Nghị định 06/2022/NĐ-CP',
                'type' => 'Nghị định Chính phủ',
                'title' => 'Nghị định quy định giảm nhẹ phát thải khí nhà kính và bảo vệ tầng ô-dôn',
                'description' => 'Quy định chi tiết về hạn ngạch phát thải, tổ chức thị trường carbon trong nước, kiểm kê khí nhà kính (GHG) và hoạt động giảm nhẹ phát thải.',
                'link' => 'https://vanban.chinhphu.vn/?pageid=27160&docid=205169',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'number' => 'Quyết định 01/2022/QĐ-TTg',
                'type' => 'Quyết định Thủ tướng',
                'title' => 'Danh mục cơ sở phát thải khí nhà kính phải thực hiện kiểm kê khí nhà kính',
                'description' => 'Danh sách chi tiết các nhà máy, cơ sở sản xuất thuộc ngành công thương, xây dựng, giao thông vận tải, tài nguyên môi trường bắt buộc thực hiện kiểm kê phát thải.',
                'link' => 'https://vanban.chinhphu.vn/?pageid=27160&docid=205187',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'number' => 'Thông tư 01/2022/TT-BTNMT',
                'type' => 'Thông tư Bộ TN&MT',
                'title' => 'Thông tư quy định chi tiết thi hành Luật BVMT về ứng phó với biến đổi khí hậu',
                'description' => 'Hướng dẫn quy trình kỹ thuật kiểm kê khí nhà kính, xây dựng báo cáo giảm phát thải cấp cơ sở và thẩm định kết quả kiểm kê.',
                'link' => 'https://vanban.chinhphu.vn/?pageid=27160&docid=205216',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'number' => 'Nghị định 08/2022/NĐ-CP',
                'type' => 'Nghị định Chính phủ',
                'title' => 'Nghị định quy định chi tiết một số điều của Luật Bảo vệ Môi trường',
                'description' => 'Quy định chi tiết về đánh giá tác động môi trường (ĐTM), giấy phép môi trường (GPMT), đăng ký môi trường, và trách nhiệm tái chế sản phẩm của nhà sản xuất (EPR).',
                'link' => 'https://vanban.chinhphu.vn/?pageid=27160&docid=205168',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'number' => 'Khung báo cáo ESG & GRI',
                'type' => 'Khung Tiêu chuẩn Quốc tế',
                'title' => 'GRI Standards & Khung hướng dẫn báo cáo CBAM, EUDR',
                'description' => 'Tài liệu hướng dẫn xây dựng báo cáo phát triển bền vững ESG của doanh nghiệp; quy chế điều chỉnh biên giới carbon (CBAM) phục vụ xuất khẩu sang EU và tiêu chuẩn chống phá rừng EUDR.',
                'link' => '/dich-vu',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($documentsData as $doc) {
            \App\Models\Document::query()->updateOrCreate(['number' => $doc['number']], $doc);
        }
    }
}
