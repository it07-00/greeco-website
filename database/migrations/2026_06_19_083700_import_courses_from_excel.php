<?php

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks to safely clear existing training records
        Schema::disableForeignKeyConstraints();
        Course::truncate();
        CourseCategory::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Seed Categories
        $categoriesData = json_decode('[
            {
                        "name": "Hệ thống quản lý ISO và cải tiến doanh nghiệp",
                        "slug": "iso",
                        "icon": "fa-certificate",
                        "color": "#008f79",
                        "bg": "#e6f4f1",
                        "sort_order": 1
            },
            {
                        "name": "An toàn – Sức khỏe – Môi trường (HSE)",
                        "slug": "hse",
                        "icon": "fa-shield-heart",
                        "color": "#1f487e",
                        "bg": "#e8eef5",
                        "sort_order": 2
            },
            {
                        "name": "Kiểm kê khí nhà kính và quản lý carbon",
                        "slug": "carbon",
                        "icon": "fa-cloud",
                        "color": "#03a9f4",
                        "bg": "#e1f5fe",
                        "sort_order": 3
            },
            {
                        "name": "ESG và phát triển bền vững",
                        "slug": "esg",
                        "icon": "fa-leaf",
                        "color": "#4caf50",
                        "bg": "#edf7ed",
                        "sort_order": 4
            },
            {
                        "name": "Chuỗi cung ứng bền vững và kinh tế tuần hoàn",
                        "slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
                        "icon": "fa-globe",
                        "color": "#009688",
                        "bg": "#e0f2f1",
                        "sort_order": 5
            },
            {
                        "name": "Công trình xanh và sản phẩm bền vững",
                        "slug": "cong-trinh-xanh-va-san-pham-ben-vung",
                        "icon": "fa-building-shield",
                        "color": "#607d8b",
                        "bg": "#eceff1",
                        "sort_order": 6
            }
]', true);

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = CourseCategory::create($catData);
        }

        // 2. Seed Courses
        $coursesData = json_decode('[
            {
                        "category_slug": "iso",
                        "title": "Xây dựng Hệ thống Quản lý Chất lượng ISO 9001:2015",
                        "slug": "xay-dung-he-thong-quan-ly-chat-luong-iso-9001-2015",
                        "description": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU\nGIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG\nGIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ\nGIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC</p>\n<p>GIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU</p>\n<p>GIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG</p>\n<p>GIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ</p>\n<p>GIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG</p>\n",
                        "curriculum": [
                                    {
                                                "title": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC",
                                                "lessons": [
                                                            {
                                                                        "title": "Thành lập Ban ISO",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng kế hoạch triển khai",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân công vai trò và trách nhiệm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo nhận thức ISO 9001:2015",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng chính sách chất lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng mục tiêu chất lượng",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU",
                                                "lessons": [
                                                            {
                                                                        "title": "Xây dựng quy trình quản lý",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng SOP (Standard Operating Procedure – Quy trình thao tác chuẩn)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng hướng dẫn công việc",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng biểu mẫu quản lý",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập hệ thống kiểm soát tài liệu và hồ sơ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập KPI chất lượng",
                                                                        "duration": "04 – 05 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG",
                                                "lessons": [
                                                            {
                                                                        "title": "Hướng dẫn áp dụng quy trình",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn sử dụng biểu mẫu",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Coaching áp dụng thực tế tại hiện trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát hồ sơ và tài liệu",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi việc áp dụng hệ thống",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn kiểm soát sản phẩm không phù hợp",
                                                                        "duration": "03 – 04 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ",
                                                "lessons": [
                                                            {
                                                                        "title": "Đào tạo Internal Auditor ISO 9001:2015",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng đánh giá nội bộ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng viết báo cáo đánh giá",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Root Cause Analysis",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "CAPA Management",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG",
                                                "lessons": [
                                                            {
                                                                        "title": "Thực hiện Internal Audit",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi hành động khắc phục",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Họp xem xét lãnh đạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hoàn thiện hồ sơ hệ thống",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuẩn bị đánh giá chứng nhận ISO 9001:2015",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 1
            },
            {
                        "category_slug": "iso",
                        "title": "Xây dựng Hệ thống Quản lý Môi trường ISO 14001:2015",
                        "slug": "xay-dung-he-thong-quan-ly-moi-truong-iso-14001-2015",
                        "description": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU\nGIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG\nGIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ\nGIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC</p>\n<p>GIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU</p>\n<p>GIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG</p>\n<p>GIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ</p>\n<p>GIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG</p>\n",
                        "curriculum": [
                                    {
                                                "title": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC",
                                                "lessons": [
                                                            {
                                                                        "title": "Thành lập Ban ISO",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng kế hoạch triển khai",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân công vai trò và trách nhiệm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo nhận thức ISO 14001:2015",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng chính sách môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng mục tiêu môi trường",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU",
                                                "lessons": [
                                                            {
                                                                        "title": "Xây dựng quy trình quản lý môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng SOP (Standard Operating Procedure – Quy trình thao tác chuẩn)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng hướng dẫn công việc",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng biểu mẫu quản lý môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập hệ thống kiểm soát tài liệu và hồ sơ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập KPI môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng ma trận khía cạnh và tác động môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng danh mục yêu cầu pháp luật môi trường",
                                                                        "duration": "04 – 05 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG",
                                                "lessons": [
                                                            {
                                                                        "title": "Hướng dẫn áp dụng quy trình môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn nhận diện khía cạnh môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn kiểm soát chất thải",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn quản lý hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn kiểm soát sự cố môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Coaching áp dụng thực tế tại hiện trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát hồ sơ và tài liệu",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi việc áp dụng hệ thống",
                                                                        "duration": "03 – 04 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ",
                                                "lessons": [
                                                            {
                                                                        "title": "Đào tạo Internal Auditor ISO 14001:2015",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng đánh giá nội bộ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng viết báo cáo đánh giá",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Root Cause Analysis",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "CAPA Management",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá tuân thủ pháp luật môi trường",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG",
                                                "lessons": [
                                                            {
                                                                        "title": "Thực hiện Internal Audit",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi hành động khắc phục",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Họp xem xét lãnh đạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hoàn thiện hồ sơ hệ thống",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuẩn bị đánh giá chứng nhận ISO 14001:2015",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 2
            },
            {
                        "category_slug": "iso",
                        "title": "Xây dựng Hệ thống Quản lý An toàn và Sức khỏe Nghề nghiệp ISO 45001:2018",
                        "slug": "xay-dung-he-thong-quan-ly-an-toan-va-suc-khoe-nghe-nghiep-iso-45001-2018",
                        "description": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU\nGIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG\nGIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ\nGIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC</p>\n<p>GIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU</p>\n<p>GIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG</p>\n<p>GIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ</p>\n<p>GIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG</p>\n",
                        "curriculum": [
                                    {
                                                "title": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC",
                                                "lessons": [
                                                            {
                                                                        "title": "Thành lập Ban ISO",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng kế hoạch triển khai",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân công vai trò và trách nhiệm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo nhận thức ISO 45001:2018",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng chính sách an toàn và sức khỏe nghề nghiệp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng mục tiêu an toàn",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU",
                                                "lessons": [
                                                            {
                                                                        "title": "Xây dựng quy trình quản lý an toàn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng SOP (Standard Operating Procedure – Quy trình thao tác chuẩn)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng hướng dẫn công việc an toàn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng biểu mẫu quản lý an toàn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập hệ thống kiểm soát tài liệu và hồ sơ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập KPI an toàn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng ma trận nhận diện mối nguy và đánh giá rủi ro",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng danh mục yêu cầu pháp luật an toàn lao động",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng quy trình ứng phó tình huống khẩn cấp",
                                                                        "duration": "04 – 05 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG",
                                                "lessons": [
                                                            {
                                                                        "title": "Hướng dẫn áp dụng quy trình an toàn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn nhận diện mối nguy và đánh giá rủi ro",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn kiểm soát nhà thầu",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn kiểm soát công việc nguy hiểm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn điều tra tai nạn và sự cố",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn kiểm soát hóa chất và thiết bị",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Coaching áp dụng thực tế tại hiện trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát hồ sơ và tài liệu",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi việc áp dụng hệ thống",
                                                                        "duration": "03 – 04 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ",
                                                "lessons": [
                                                            {
                                                                        "title": "Đào tạo Internal Auditor ISO 45001:2018",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng đánh giá nội bộ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng viết báo cáo đánh giá",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Root Cause Analysis",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "CAPA Management",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá tuân thủ pháp luật an toàn lao động",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG",
                                                "lessons": [
                                                            {
                                                                        "title": "Thực hiện Internal Audit",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi hành động khắc phục",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Họp xem xét lãnh đạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hoàn thiện hồ sơ hệ thống",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuẩn bị đánh giá chứng nhận ISO 45001:2018",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 3
            },
            {
                        "category_slug": "iso",
                        "title": "Xây dựng Hệ thống Quản lý Năng lượng ISO 50001:2018",
                        "slug": "xay-dung-he-thong-quan-ly-nang-luong-iso-50001-2018",
                        "description": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2 – ĐÁNH GIÁ NĂNG LƯỢNG VÀ XÁC ĐỊNH SEU\nGIAI ĐOẠN 3 – XÂY DỰNG HỆ THỐNG QUẢN LÝ NĂNG LƯỢNG\nGIAI ĐOẠN 4 – TRIỂN KHAI ÁP DỤNG VÀ ĐÀO TẠO\nGIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC</p>\n<p>GIAI ĐOẠN 2 – ĐÁNH GIÁ NĂNG LƯỢNG VÀ XÁC ĐỊNH SEU</p>\n<p>GIAI ĐOẠN 3 – XÂY DỰNG HỆ THỐNG QUẢN LÝ NĂNG LƯỢNG</p>\n<p>GIAI ĐOẠN 4 – TRIỂN KHAI ÁP DỤNG VÀ ĐÀO TẠO</p>\n<p>GIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG</p>\n",
                        "curriculum": [
                                    {
                                                "title": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC",
                                                "lessons": [
                                                            {
                                                                        "title": "Thành lập Ban Energy Team",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng kế hoạch triển khai",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân công vai trò và trách nhiệm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo nhận thức ISO 50001:2018",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan về quản lý năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan về Energy Performance",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan về GHG và Carbon Management",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng chính sách năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng mục tiêu năng lượng",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 2 – ĐÁNH GIÁ NĂNG LƯỢNG VÀ XÁC ĐỊNH SEU",
                                                "lessons": [
                                                            {
                                                                        "title": "Thu thập dữ liệu năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân tích sử dụng năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định Significant Energy Use (SEU)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá hiệu suất thiết bị",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá hệ thống điện",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá hệ thống khí nén",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá hệ thống hơi",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá động cơ và thiết bị tiêu thụ năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng Energy Review",
                                                                        "duration": "03 – 04 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 3 – XÂY DỰNG HỆ THỐNG QUẢN LÝ NĂNG LƯỢNG",
                                                "lessons": [
                                                            {
                                                                        "title": "Xây dựng quy trình quản lý năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng SOP vận hành năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập Energy Baseline",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập Energy Performance Indicator (EnPI)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập hệ thống giám sát năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng KPI năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng kế hoạch tiết kiệm năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập hệ thống quản lý dữ liệu năng lượng",
                                                                        "duration": "03 – 04 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 4 – TRIỂN KHAI ÁP DỤNG VÀ ĐÀO TẠO",
                                                "lessons": [
                                                            {
                                                                        "title": "Hướng dẫn áp dụng hệ thống ISO 50001",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Coaching hiện trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo kiểm soát vận hành năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo quản lý dữ liệu năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo Internal Auditor ISO 50001",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi hiệu quả áp dụng thực tế",
                                                                        "duration": "02 – 03 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG",
                                                "lessons": [
                                                            {
                                                                        "title": "Thực hiện Internal Audit",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi hành động khắc phục",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Họp xem xét lãnh đạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Rà soát hệ thống năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hoàn thiện hồ sơ chứng nhận",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuẩn bị đánh giá chứng nhận ISO 50001:2018",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 4
            },
            {
                        "category_slug": "iso",
                        "title": "Xây dựng Hệ thống Quản lý Hiệu quả Sử dụng Nước ISO 46001:2019",
                        "slug": "xay-dung-he-thong-quan-ly-hieu-qua-su-dung-nuoc-iso-46001-2019",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 5
            },
            {
                        "category_slug": "hse",
                        "title": "Đánh giá Rủi ro An toàn – Sức khỏe – Môi trường",
                        "slug": "danh-gia-rui-ro-an-toan-suc-khoe-moi-truong",
                        "description": "NGÀY 1 – NHẬN DIỆN MỐI NGUY VÀ ĐÁNH GIÁ RỦI RO\nNGÀY 2 – KIỂM SOÁT RỦI RO VÀ TRIỂN KHAI THỰC TẾ",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>NGÀY 1 – NHẬN DIỆN MỐI NGUY VÀ ĐÁNH GIÁ RỦI RO</p>\n<p>NGÀY 2 – KIỂM SOÁT RỦI RO VÀ TRIỂN KHAI THỰC TẾ</p>\n",
                        "curriculum": [
                                    {
                                                "title": "NGÀY 1 – NHẬN DIỆN MỐI NGUY VÀ ĐÁNH GIÁ RỦI RO",
                                                "lessons": []
                                    },
                                    {
                                                "title": "PHẦN 1 – TỔNG QUAN VỀ ĐÁNH GIÁ RỦI RO",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm Hazard và Risk",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tư duy phòng ngừa trong công tác an toàn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Risk-Based Thinking",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tai nạn và nguyên nhân tai nạn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Yêu cầu pháp luật liên quan",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Yêu cầu ISO 45001 và ISO 14001",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò của đánh giá rủi ro trong doanh nghiệp",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 2 – NHẬN DIỆN MỐI NGUY",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phương pháp nhận diện mối nguy",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhận diện mối nguy theo công việc",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhận diện mối nguy theo thiết bị",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhận diện mối nguy theo khu vực",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhận diện hành vi không an toàn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhận diện điều kiện không an toàn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhận diện tác động môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các nhóm mối nguy chính",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cơ học",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Điện",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cháy nổ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tiếng ồn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhiệt",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ergonomic",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Làm việc trên cao",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Không gian hạn chế",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết bị nâng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xe nâng và giao thông nội bộ",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 3 – PHƯƠNG PHÁP ĐÁNH GIÁ RỦI RO",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định khả năng xảy ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định mức độ hậu quả",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định mức độ rủi ro",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ma trận đánh giá rủi ro",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân loại mức độ rủi ro",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định mức độ chấp nhận rủi ro",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các phương pháp áp dụng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "HIRA (Hazard Identification and Risk Assessment)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "JSA (Job Safety Analysis)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "What-if Analysis",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Checklist Method",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bowtie Concept",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 4 – THỰC HÀNH NHẬN DIỆN MỐI NGUY",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung thực hành",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành nhận diện mối nguy tại hiện trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành đánh giá rủi ro theo công việc",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân tích tình huống tai nạn thực tế",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thảo luận nhóm và trình bày kết quả",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "NGÀY 2 – KIỂM SOÁT RỦI RO VÀ TRIỂN KHAI THỰC TẾ",
                                                "lessons": []
                                    },
                                    {
                                                "title": "PHẦN 5 – BIỆN PHÁP KIỂM SOÁT RỦI RO",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hierarchy of Controls",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Elimination",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Substitution",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Engineering Control",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Administrative Control",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "PPE (Personal Protective Equipment – Phương tiện bảo vệ cá nhân)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập biện pháp kiểm soát",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát kỹ thuật",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát hành chính",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "SOP an toàn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Permit To Work",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Lockout Tagout",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Biển báo và cảnh báo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra và giám sát",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 6 – ĐÁNH GIÁ RỦI RO TRONG CÁC CÔNG VIỆC NGUY HIỂM",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Làm việc trên cao",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hot Work",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Không gian hạn chế",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Điện",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết bị nâng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hóa chất nguy hiểm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xe nâng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bảo trì máy móc",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhà thầu thi công",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 7 – ĐIỀU TRA TAI NẠN VÀ ROOT CAUSE ANALYSIS",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình điều tra tai nạn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thu thập bằng chứng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân tích nguyên nhân trực tiếp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân tích nguyên nhân gốc rễ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "5 Why",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Fishbone Diagram",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập CAPA",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 8 – TRIỂN KHAI HỆ THỐNG ĐÁNH GIÁ RỦI RO TẠI DOANH NGHIỆP",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập Risk Assessment Procedure",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập Risk Register",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cập nhật đánh giá rủi ro",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá rủi ro theo thay đổi",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi hành động kiểm soát",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tích hợp với ISO 45001 và ISO 14001",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 9 – THỰC HÀNH VÀ ĐÁNH GIÁ CUỐI KHÓA",
                                                "lessons": [
                                                            {
                                                                        "title": "Thực hành đánh giá rủi ro theo nhóm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trình bày kết quả",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Coaching và góp ý",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra cuối khóa",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng kết đào tạo",
                                                                        "duration": ""
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 6
            },
            {
                        "category_slug": "hse",
                        "title": "Quản lý Hóa chất trong Doanh nghiệp",
                        "slug": "quan-ly-hoa-chat-trong-doanh-nghiep",
                        "description": "NGÀY 1 – TỔNG QUAN VỀ QUẢN LÝ HÓA CHẤT VÀ NHẬN DIỆN RỦI RO\nNGÀY 2 – KIỂM SOÁT HÓA CHẤT VÀ ỨNG PHÓ SỰ CỐ",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>NGÀY 1 – TỔNG QUAN VỀ QUẢN LÝ HÓA CHẤT VÀ NHẬN DIỆN RỦI RO</p>\n<p>NGÀY 2 – KIỂM SOÁT HÓA CHẤT VÀ ỨNG PHÓ SỰ CỐ</p>\n",
                        "curriculum": [
                                    {
                                                "title": "NGÀY 1 – TỔNG QUAN VỀ QUẢN LÝ HÓA CHẤT VÀ NHẬN DIỆN RỦI RO",
                                                "lessons": []
                                    },
                                    {
                                                "title": "PHẦN 1 – TỔNG QUAN VỀ QUẢN LÝ HÓA CHẤT",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan về hóa chất trong doanh nghiệp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nguy cơ và tác động của hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các sự cố hóa chất điển hình",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Yêu cầu pháp luật liên quan",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Yêu cầu ISO 14001 và ISO 45001 liên quan đến hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò và trách nhiệm trong quản lý hóa chất",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 2 – NHẬN DIỆN MỐI NGUY HÓA CHẤT",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân loại hóa chất nguy hiểm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhận diện mối nguy hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tính chất vật lý và hóa học nguy hiểm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hóa chất cháy nổ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hóa chất ăn mòn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hóa chất độc hại",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hóa chất phản ứng mạnh",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tác động sức khỏe nghề nghiệp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tác động môi trường",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 3 – SDS VÀ GHS",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cấu trúc SDS",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cách đọc SDS",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhãn GHS",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Pictogram cảnh báo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Signal Word",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hazard Statement",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Precautionary Statement",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Yêu cầu ghi nhãn hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung thực hành",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành đọc SDS",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhận diện nhãn GHS",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân tích thông tin hóa chất thực tế",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 4 – ĐÁNH GIÁ RỦI RO HÓA CHẤT",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhận diện rủi ro hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá mức độ rủi ro",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Exposure Assessment",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Risk Matrix",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Biện pháp kiểm soát hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hierarchy of Controls",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung thực hành",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành đánh giá rủi ro hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thảo luận tình huống thực tế",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Coaching hiện trường",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "NGÀY 2 – KIỂM SOÁT HÓA CHẤT VÀ ỨNG PHÓ SỰ CỐ",
                                                "lessons": []
                                    },
                                    {
                                                "title": "PHẦN 5 – QUẢN LÝ LƯU TRỮ VÀ SỬ DỤNG HÓA CHẤT",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nguyên tắc lưu trữ hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân khu hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tương thích hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Secondary Containment",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản lý tồn kho hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát cấp phát hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản lý hóa chất hết hạn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát nhà thầu hóa chất",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 6 – KIỂM SOÁT PHƠI NHIỄM VÀ PPE",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đường phơi nhiễm hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát phơi nhiễm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thông gió và hút cục bộ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "PPE (Personal Protective Equipment – Phương tiện bảo vệ cá nhân)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Eyewash và Safety Shower",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Monitoring hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra sức khỏe nghề nghiệp",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 7 – ỨNG PHÓ SỰ CỐ HÓA CHẤT",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình ứng phó sự cố hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Spill Control",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Sự cố rò rỉ hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cháy nổ hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Sơ cứu phơi nhiễm hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Emergency Response",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Điều tra sự cố hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung thực hành",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành ứng phó sự cố hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành xử lý spill kit",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thảo luận tình huống thực tế",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 8 – XÂY DỰNG HỆ THỐNG QUẢN LÝ HÓA CHẤT",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chemical Management Procedure",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chemical Register",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chemical Approval Process",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Risk Assessment",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Inspection Checklist",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chemical Audit",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tích hợp với ISO 14001 và ISO 45001",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 9 – TỔNG KẾT VÀ ĐÁNH GIÁ CUỐI KHÓA",
                                                "lessons": [
                                                            {
                                                                        "title": "Ôn tập nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra cuối khóa",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá năng lực học viên",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng kết chương trình đào tạo",
                                                                        "duration": ""
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 7
            },
            {
                        "category_slug": "hse",
                        "title": "Điều tra Sự cố và Phân tích Nguyên nhân Gốc rễ (Root Cause Analysis – RCA)",
                        "slug": "dieu-tra-su-co-va-phan-tich-nguyen-nhan-goc-re-root-cause-analysis-rca",
                        "description": "NGÀY 1 – TỔNG QUAN ĐIỀU TRA SỰ CỐ VÀ PHÂN TÍCH NGUYÊN NHÂN\nPHẦN 1 – TỔNG QUAN VỀ ĐIỀU TRA SỰ CỐ VÀ RCA\nPHẦN 2 – QUY TRÌNH ĐIỀU TRA SỰ CỐ\nPHẦN 3 – CÁC PHƯƠNG PHÁP PHÂN TÍCH NGUYÊN NHÂN GỐC RỄ\nPHẦN 4 – HUMAN FACTOR VÀ HÀNH VI TRONG SỰ CỐ\nNGÀY 2 – THIẾT LẬP GIẢI PHÁP VÀ TRIỂN KHAI THỰC TẾ\nPHẦN 5 – THIẾT LẬP HÀNH ĐỘNG KHẮC PHỤC VÀ PHÒNG NGỪA\nPHẦN 6 – RCA TRONG CÁC HOẠT ĐỘNG DOANH NGHIỆP\nPHẦN 7 – XÂY DỰNG HỆ THỐNG ĐIỀU TRA SỰ CỐ TẠI DOANH NGHIỆP\nPHẦN 8 – WORKSHOP THỰC HÀNH RCA\nPHẦN 9 – TỔNG KẾT VÀ ĐÁNH GIÁ CUỐI KHÓA",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>NGÀY 1 – TỔNG QUAN ĐIỀU TRA SỰ CỐ VÀ PHÂN TÍCH NGUYÊN NHÂN</p>\n<p>PHẦN 1 – TỔNG QUAN VỀ ĐIỀU TRA SỰ CỐ VÀ RCA</p>\n<p>PHẦN 2 – QUY TRÌNH ĐIỀU TRA SỰ CỐ</p>\n<p>PHẦN 3 – CÁC PHƯƠNG PHÁP PHÂN TÍCH NGUYÊN NHÂN GỐC RỄ</p>\n<p>PHẦN 4 – HUMAN FACTOR VÀ HÀNH VI TRONG SỰ CỐ</p>\n<p>NGÀY 2 – THIẾT LẬP GIẢI PHÁP VÀ TRIỂN KHAI THỰC TẾ</p>\n<p>PHẦN 5 – THIẾT LẬP HÀNH ĐỘNG KHẮC PHỤC VÀ PHÒNG NGỪA</p>\n<p>PHẦN 6 – RCA TRONG CÁC HOẠT ĐỘNG DOANH NGHIỆP</p>\n<p>PHẦN 7 – XÂY DỰNG HỆ THỐNG ĐIỀU TRA SỰ CỐ TẠI DOANH NGHIỆP</p>\n<p>PHẦN 8 – WORKSHOP THỰC HÀNH RCA</p>\n<p>PHẦN 9 – TỔNG KẾT VÀ ĐÁNH GIÁ CUỐI KHÓA</p>\n",
                        "curriculum": [
                                    {
                                                "title": "NGÀY 1 – TỔNG QUAN ĐIỀU TRA SỰ CỐ VÀ PHÂN TÍCH NGUYÊN NHÂN",
                                                "lessons": []
                                    },
                                    {
                                                "title": "PHẦN 1 – TỔNG QUAN VỀ ĐIỀU TRA SỰ CỐ VÀ RCA",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm Incident, Accident và Near Miss",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tầm quan trọng của điều tra sự cố",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chi phí ẩn của tai nạn và sự cố",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nguyên tắc điều tra sự cố",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm Root Cause Analysis",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Direct Cause và Root Cause",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tư duy phòng ngừa và cải tiến liên tục",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 2 – QUY TRÌNH ĐIỀU TRA SỰ CỐ",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình điều tra sự cố",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thu thập bằng chứng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thu thập thông tin hiện trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phỏng vấn nhân chứng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chụp hình và lưu hồ sơ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định chuỗi sự kiện",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân tích điều kiện và hành vi",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng Timeline sự cố",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 3 – CÁC PHƯƠNG PHÁP PHÂN TÍCH NGUYÊN NHÂN GỐC RỄ",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "5 Why Analysis",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Fishbone Diagram",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Fault Tree Analysis",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Barrier Analysis",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cause and Effect Analysis",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Human Factor Analysis",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung thực hành",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành phân tích tình huống sự cố",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng Fishbone Diagram",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành 5 Why",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thảo luận nhóm và trình bày kết quả",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 4 – HUMAN FACTOR VÀ HÀNH VI TRONG SỰ CỐ",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Human Error",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Unsafe Behavior",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Unsafe Condition",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Organizational Failure",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Leadership Failure",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Communication Failure",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Work Pressure",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Fatigue",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Culture",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "NGÀY 2 – THIẾT LẬP GIẢI PHÁP VÀ TRIỂN KHAI THỰC TẾ",
                                                "lessons": []
                                    },
                                    {
                                                "title": "PHẦN 5 – THIẾT LẬP HÀNH ĐỘNG KHẮC PHỤC VÀ PHÒNG NGỪA",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "CAPA (Corrective and Preventive Action)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập hành động hiệu quả",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát tái diễn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi hiệu quả hành động",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá tính bền vững của giải pháp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung thực hành",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập CAPA cho tình huống thực tế",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá hiệu lực hành động",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thảo luận nhóm",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 6 – RCA TRONG CÁC HOẠT ĐỘNG DOANH NGHIỆP",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "RCA trong tai nạn lao động",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "RCA trong lỗi chất lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "RCA trong sự cố môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "RCA trong sự cố thiết bị",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "RCA trong Near Miss",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "RCA trong hoạt động sản xuất",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 7 – XÂY DỰNG HỆ THỐNG ĐIỀU TRA SỰ CỐ TẠI DOANH NGHIỆP",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng Incident Investigation Procedure",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập Incident Reporting System",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập RCA Team",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản lý hồ sơ điều tra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi xu hướng sự cố",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tích hợp RCA với ISO 45001 và ISO 9001",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 8 – WORKSHOP THỰC HÀNH RCA",
                                                "lessons": [
                                                            {
                                                                        "title": "Phân tích Case Study thực tế",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Điều tra tình huống mô phỏng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng Timeline sự cố",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hiện RCA theo nhóm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trình bày kết quả và coaching",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 9 – TỔNG KẾT VÀ ĐÁNH GIÁ CUỐI KHÓA",
                                                "lessons": [
                                                            {
                                                                        "title": "Ôn tập nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra cuối khóa",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá năng lực học viên",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng kết chương trình đào tạo",
                                                                        "duration": ""
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 8
            },
            {
                        "category_slug": "hse",
                        "title": "Phát triển năng lực cán bộ An toàn – Sức khỏe – Môi trường (HSE OFFICER)",
                        "slug": "phat-trien-nang-luc-can-bo-an-toan-suc-khoe-moi-truong-hse-officer",
                        "description": "NGÀY 01\nNỀN TẢNG QUẢN LÝ HSE VÀ ĐÁNH GIÁ RỦI RO\nChuyên đề 1\nTổng quan về HSE trong doanh nghiệp\nChuyên đề 2\nNhận diện mối nguy và đánh giá rủi ro\nChuyên đề 3\nKiểm tra hiện trường và Safety Patrol\nNGÀY 02\nQUẢN LÝ AN TOÀN – SỨC KHỎE NGHỀ NGHIỆP\nChuyên đề 4\nQuản lý máy móc thiết bị\nChuyên đề 5\nQuản lý hóa chất\nChuyên đề 6\nQuản lý sức khỏe nghề nghiệp\nChuyên đề 7\nĐiều tra tai nạn lao động\nNGÀY 03\nQUẢN LÝ MÔI TRƯỜNG VÀ KỸ NĂNG HSE OFFICER\nChuyên đề 8\nQuản lý môi trường\nChuyên đề 9\nỨng phó khẩn cấp\nChuyên đề 10\nKỹ năng chuyên nghiệp của HSE Officer",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>NGÀY 01</p>\n<p>NỀN TẢNG QUẢN LÝ HSE VÀ ĐÁNH GIÁ RỦI RO</p>\n<p>Chuyên đề 1</p>\n<p>Tổng quan về HSE trong doanh nghiệp</p>\n<p>Chuyên đề 2</p>\n<p>Nhận diện mối nguy và đánh giá rủi ro</p>\n<p>Chuyên đề 3</p>\n<p>Kiểm tra hiện trường và Safety Patrol</p>\n<p>NGÀY 02</p>\n<p>QUẢN LÝ AN TOÀN – SỨC KHỎE NGHỀ NGHIỆP</p>\n<p>Chuyên đề 4</p>\n<p>Quản lý máy móc thiết bị</p>\n<p>Chuyên đề 5</p>\n<p>Quản lý hóa chất</p>\n<p>Chuyên đề 6</p>\n<p>Quản lý sức khỏe nghề nghiệp</p>\n<p>Chuyên đề 7</p>\n<p>Điều tra tai nạn lao động</p>\n<p>NGÀY 03</p>\n<p>QUẢN LÝ MÔI TRƯỜNG VÀ KỸ NĂNG HSE OFFICER</p>\n<p>Chuyên đề 8</p>\n<p>Quản lý môi trường</p>\n<p>Chuyên đề 9</p>\n<p>Ứng phó khẩn cấp</p>\n<p>Chuyên đề 10</p>\n<p>Kỹ năng chuyên nghiệp của HSE Officer</p>\n",
                        "curriculum": [
                                    {
                                                "title": "NGÀY 01",
                                                "lessons": [
                                                            {
                                                                        "title": "NỀN TẢNG QUẢN LÝ HSE VÀ ĐÁNH GIÁ RỦI RO",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 1 – Tổng quan về HSE trong doanh nghiệp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung đào tạo:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò của HSE Officer.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trách nhiệm pháp lý của doanh nghiệp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trách nhiệm của cán bộ HSE.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hệ thống quản lý HSE hiện đại.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan ISO 14001.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan ISO 45001.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan SMETA.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan BSCI.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các yêu cầu khách hàng thường gặp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 2 – Nhận diện mối nguy và đánh giá rủi ro",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung đào tạo:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm mối nguy.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm rủi ro.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân loại mối nguy.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bao gồm:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mối nguy cơ học.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mối nguy điện.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mối nguy hóa chất.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mối nguy tiếng ồn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mối nguy bụi.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mối nguy nhiệt.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mối nguy công thái học.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mối nguy giao thông nội bộ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng bảng nhận diện mối nguy.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng ma trận đánh giá rủi ro.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành đánh giá rủi ro thực tế.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 3 – Kiểm tra hiện trường và Safety Patrol",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung đào tạo:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phương pháp kiểm tra hiện trường.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quan sát hành vi không an toàn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Điều kiện không an toàn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng giao tiếp với người lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng ghi nhận bằng chứng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng lập biên bản.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Walk.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Observation.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Near Miss Reporting.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "NGÀY 02",
                                                "lessons": [
                                                            {
                                                                        "title": "QUẢN LÝ AN TOÀN – SỨC KHỎE NGHỀ NGHIỆP",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 4 – Quản lý máy móc thiết bị",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung đào tạo:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát thiết bị có yêu cầu nghiêm ngặt.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm định thiết bị.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bảo trì phòng ngừa.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khóa và gắn thẻ (LOTO).",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Che chắn máy.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "An toàn điện.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 5 – Quản lý hóa chất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung đào tạo:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hệ thống GHS.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhãn hóa chất.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phiếu SDS.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá rủi ro hóa chất.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kho hóa chất.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ứng phó tràn đổ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "PPE đối với hóa chất.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra nhãn hóa chất.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá khu vực lưu trữ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 6 – Quản lý sức khỏe nghề nghiệp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung đào tạo:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bệnh nghề nghiệp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quan trắc môi trường lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khám sức khỏe định kỳ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khám bệnh nghề nghiệp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản lý lao động nữ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản lý lao động chưa thành niên.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 7 – Điều tra tai nạn lao động",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung đào tạo:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm tai nạn lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Near Miss.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Incident.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Root Cause Analysis.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các công cụ:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "5 Why.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Fishbone Diagram.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Corrective Action Plan.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân tích tình huống thực tế.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Lập báo cáo điều tra.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "NGÀY 03",
                                                "lessons": [
                                                            {
                                                                        "title": "QUẢN LÝ MÔI TRƯỜNG VÀ KỸ NĂNG HSE OFFICER",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 8 – Quản lý môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung đào tạo:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khía cạnh môi trường.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tác động môi trường.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chất thải nguy hại.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chất thải thông thường.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nước thải.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khí thải.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tiếng ồn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tiết kiệm năng lượng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nhận diện khía cạnh môi trường.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá mức độ tác động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 9 – Ứng phó khẩn cấp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung đào tạo:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cháy nổ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tràn đổ hóa chất.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tai nạn lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mất điện.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiên tai.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng kịch bản ứng phó.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổ chức diễn tập.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 10 – Kỹ năng chuyên nghiệp của HSE Officer",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung đào tạo:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng đào tạo nội bộ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng thuyết trình.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng viết báo cáo.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng lập KPI.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng xây dựng chương trình HSE.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng làm việc với cơ quan chức năng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng làm việc với khách hàng đánh giá.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 9
            },
            {
                        "category_slug": "hse",
                        "title": "LÃNH ĐẠO AN TOÀN – XÂY DỰNG VĂN HÓA AN TOÀN VÀ QUẢN LÝ RỦI RO THẢM HỌA",
                        "slug": "lanh-dao-an-toan-xay-dung-van-hoa-an-toan-va-quan-ly-rui-ro-tham-hoa",
                        "description": "NGÀY 01\nPROCESS SAFETY MANAGEMENT (PSM)\nLÃNH ĐẠO VÀ QUẢN TRỊ RỦI RO THẢM HỌA\nChuyên đề 1\nBài học từ các thảm họa công nghiệp trên thế giới\nPhân tích các vụ việc điển hình:\nChuyên đề 2\nTổng quan Process Safety Management (PSM)\nChuyên đề 3\nVai trò lãnh đạo trong quản lý rủi ro thảm họa\nNGÀY 02\nXÂY DỰNG VĂN HÓA AN TOÀN\nChuyên đề 4\nSafety Culture – Văn hóa an toàn là gì?\nChuyên đề 5\nHành vi lãnh đạo ảnh hưởng đến văn hóa an toàn\nChuyên đề 6\nTrách nhiệm giải trình về an toàn (Safety Accountability)\nNGÀY 03\nTỪ CHIẾN LƯỢC ĐẾN HÀNH ĐỘNG\nChuyên đề 7\nCác công cụ lãnh đạo an toàn hiện đại\nChuyên đề 8\nXây dựng chiến lược văn hóa an toàn\nChuyên đề 9\nCam kết lãnh đạo và kế hoạch hành động",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>NGÀY 01</p>\n<p>PROCESS SAFETY MANAGEMENT (PSM)</p>\n<p>LÃNH ĐẠO VÀ QUẢN TRỊ RỦI RO THẢM HỌA</p>\n<p>Chuyên đề 1</p>\n<p>Bài học từ các thảm họa công nghiệp trên thế giới</p>\n<p>Phân tích các vụ việc điển hình:</p>\n<p>Chuyên đề 2</p>\n<p>Tổng quan Process Safety Management (PSM)</p>\n<p>Chuyên đề 3</p>\n<p>Vai trò lãnh đạo trong quản lý rủi ro thảm họa</p>\n<p>NGÀY 02</p>\n<p>XÂY DỰNG VĂN HÓA AN TOÀN</p>\n<p>Chuyên đề 4</p>\n<p>Safety Culture – Văn hóa an toàn là gì?</p>\n<p>Chuyên đề 5</p>\n<p>Hành vi lãnh đạo ảnh hưởng đến văn hóa an toàn</p>\n<p>Chuyên đề 6</p>\n<p>Trách nhiệm giải trình về an toàn (Safety Accountability)</p>\n<p>NGÀY 03</p>\n<p>TỪ CHIẾN LƯỢC ĐẾN HÀNH ĐỘNG</p>\n<p>Chuyên đề 7</p>\n<p>Các công cụ lãnh đạo an toàn hiện đại</p>\n<p>Chuyên đề 8</p>\n<p>Xây dựng chiến lược văn hóa an toàn</p>\n<p>Chuyên đề 9</p>\n<p>Cam kết lãnh đạo và kế hoạch hành động</p>\n",
                        "curriculum": [
                                    {
                                                "title": "NGÀY 01",
                                                "lessons": [
                                                            {
                                                                        "title": "PROCESS SAFETY MANAGEMENT (PSM)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "LÃNH ĐẠO VÀ QUẢN TRỊ RỦI RO THẢM HỌA",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 1 – Bài học từ các thảm họa công nghiệp trên thế giới",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân tích các vụ việc điển hình:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bhopal – Ấn Độ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Piper Alpha – Vương quốc Anh.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Texas City – Hoa Kỳ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Deepwater Horizon.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Beirut Explosion.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các sự cố cháy nổ lớn tại Việt Nam.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung trọng tâm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vì sao doanh nghiệp thất bại?",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò của lãnh đạo trong các thảm họa.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các dấu hiệu cảnh báo bị bỏ qua.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chi phí thực sự của một tai nạn nghiêm trọng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 2 – Tổng quan Process Safety Management (PSM)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm PSM.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Sự khác biệt giữa:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "An toàn nghề nghiệp\tAn toàn quá trình",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tai nạn cá nhân\tSự cố thảm họa",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tác động cục bộ\tTác động quy mô lớn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mất ngày công\tMất nhà máy",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chấn thương\tTử vong hàng loạt",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các yếu tố cốt lõi của PSM",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Leadership Commitment.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Risk Management.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Asset Integrity.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Management of Change.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Emergency Preparedness.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Incident Investigation.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Process Safety Information.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 3 – Vai trò lãnh đạo trong quản lý rủi ro thảm họa",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Risk Governance.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Risk Appetite.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Risk Ownership.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Leadership Visibility.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Accountability.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá mức độ trưởng thành của hệ thống PSM hiện tại.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "NGÀY 02",
                                                "lessons": [
                                                            {
                                                                        "title": "XÂY DỰNG VĂN HÓA AN TOÀN",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 4 – Safety Culture – Văn hóa an toàn là gì?",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các cấp độ trưởng thành văn hóa an toàn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mô hình Bradley Curve.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mô hình Hudson Safety Culture.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Từ \"Compliance\" đến \"Commitment\".",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá hiện trạng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Doanh nghiệp đang ở cấp độ nào?",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Reactive.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Dependent.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Independent.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Interdependent.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 5 – Hành vi lãnh đạo ảnh hưởng đến văn hóa an toàn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Những hành vi lãnh đạo tạo nên văn hóa an toàn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Những hành vi lãnh đạo phá hủy văn hóa an toàn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quyền lực của người quản lý tuyến đầu.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Leadership Walkthrough.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Gemba Walk.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tự đánh giá phong cách lãnh đạo an toàn của từng học viên.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 6 – Trách nhiệm giải trình về an toàn (Safety Accountability)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khác biệt giữa Responsibility và Accountability.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trách nhiệm của Tổng Giám đốc.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trách nhiệm của Giám đốc nhà máy.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trách nhiệm của Quản lý cấp trung.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trách nhiệm của Giám sát.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Accountability Matrix.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety KPI Cascade.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "NGÀY 03",
                                                "lessons": [
                                                            {
                                                                        "title": "TỪ CHIẾN LƯỢC ĐẾN HÀNH ĐỘNG",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 7 – Các công cụ lãnh đạo an toàn hiện đại",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Near Miss Management.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Observation.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Life Saving Rules.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Walk.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Management Safety Tour.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Leadership Field Visit.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "High Risk Activity Review.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng hệ thống KPI chủ động",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bao gồm:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Near Miss Reporting Rate.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Walk Completion.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Action Closure Rate.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Training Completion Rate.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "High Risk Audit Score.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 8 – Xây dựng chiến lược văn hóa an toàn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Vision.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Mission.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Strategic Plan.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Communication Campaign.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Employee Engagement.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng Roadmap Văn hóa An toàn 12 tháng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 9 – Cam kết lãnh đạo và kế hoạch hành động",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thảo luận:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các rào cản hiện tại.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các cơ hội cải tiến.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các mục tiêu ưu tiên.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kết quả cuối khóa",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mỗi lãnh đạo xây dựng:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cam kết an toàn cá nhân.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch hành động 90 ngày.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch cải tiến 12 tháng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chương trình hiện diện lãnh đạo tại hiện trường.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 10
            },
            {
                        "category_slug": "hse",
                        "title": "XÂY DỰNG VĂN HÓA AN TOÀN VÀ QUẢN LÝ RỦI RO CHỦ ĐỘNG CHO QUẢN LÝ VỀ AN TOÀN",
                        "slug": "xay-dung-van-hoa-an-toan-va-quan-ly-rui-ro-chu-dong-cho-quan-ly-ve-an-toan",
                        "description": "NGÀY 01\nXÂY DỰNG VĂN HÓA AN TOÀN VÀ HỆ THỐNG NEAR MISS\nChuyên đề 1\nVai trò lãnh đạo trong quản lý an toàn\nChuyên đề 2\nQuản lý Near Miss\nChuyên đề 3\nĐiều tra Near Miss\nNGÀY 02\nBEHAVIOR BASED SAFETY (BBS)\nChuyên đề 4\nTổng quan BBS\nChuyên đề 5\nThiết kế chương trình BBS\nChuyên đề 6\nKỹ năng Safety Coaching\nNGÀY 03\nLIFE SAVING RULES (LSR) VÀ WSFI\nChuyên đề 7\nLife Saving Rules\nChuyên đề 8\nWorkplace Safety Field Inspection (WSFI)\nNGÀY 04\nJOB HAZARD ANALYSIS (JHA)\nChuyên đề 9\nPhân tích công việc và nhận diện nguy cơ\nChuyên đề 10\nThực hành JHA\nChuyên đề 11\nPermit To Work liên kết JHA\nNGÀY 05\nĐÁNH GIÁ RỦI RO (DGRR) VÀ XÂY DỰNG CHIẾN LƯỢC HSE\nChuyên đề 12\nĐánh giá rủi ro nâng cao\nChuyên đề 13\nThiết lập KPI an toàn chủ động\nChuyên đề 14\nXây dựng Roadmap HSE 12 tháng",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>NGÀY 01</p>\n<p>XÂY DỰNG VĂN HÓA AN TOÀN VÀ HỆ THỐNG NEAR MISS</p>\n<p>Chuyên đề 1</p>\n<p>Vai trò lãnh đạo trong quản lý an toàn</p>\n<p>Chuyên đề 2</p>\n<p>Quản lý Near Miss</p>\n<p>Chuyên đề 3</p>\n<p>Điều tra Near Miss</p>\n<p>NGÀY 02</p>\n<p>BEHAVIOR BASED SAFETY (BBS)</p>\n<p>Chuyên đề 4</p>\n<p>Tổng quan BBS</p>\n<p>Chuyên đề 5</p>\n<p>Thiết kế chương trình BBS</p>\n<p>Chuyên đề 6</p>\n<p>Kỹ năng Safety Coaching</p>\n<p>NGÀY 03</p>\n<p>LIFE SAVING RULES (LSR) VÀ WSFI</p>\n<p>Chuyên đề 7</p>\n<p>Life Saving Rules</p>\n<p>Chuyên đề 8</p>\n<p>Workplace Safety Field Inspection (WSFI)</p>\n<p>NGÀY 04</p>\n<p>JOB HAZARD ANALYSIS (JHA)</p>\n<p>Chuyên đề 9</p>\n<p>Phân tích công việc và nhận diện nguy cơ</p>\n<p>Chuyên đề 10</p>\n<p>Thực hành JHA</p>\n<p>Chuyên đề 11</p>\n<p>Permit To Work liên kết JHA</p>\n<p>NGÀY 05</p>\n<p>ĐÁNH GIÁ RỦI RO (DGRR) VÀ XÂY DỰNG CHIẾN LƯỢC HSE</p>\n<p>Chuyên đề 12</p>\n<p>Đánh giá rủi ro nâng cao</p>\n<p>Chuyên đề 13</p>\n<p>Thiết lập KPI an toàn chủ động</p>\n<p>Chuyên đề 14</p>\n<p>Xây dựng Roadmap HSE 12 tháng</p>\n",
                        "curriculum": [
                                    {
                                                "title": "NGÀY 01",
                                                "lessons": [
                                                            {
                                                                        "title": "XÂY DỰNG VĂN HÓA AN TOÀN VÀ HỆ THỐNG NEAR MISS",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 1 – Vai trò lãnh đạo trong quản lý an toàn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung đào tạo:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Từ quản lý an toàn sang lãnh đạo an toàn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mô hình Bradley Curve.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Culture Maturity.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các cấp độ trưởng thành văn hóa an toàn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò của lãnh đạo tuyến đầu.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 2 – Quản lý Near Miss",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung đào tạo:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm Near Miss.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mối liên hệ giữa Near Miss và tai nạn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mô hình Heinrich Pyramid.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các loại Near Miss phổ biến.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình báo cáo Near Miss.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình Near Miss.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phiếu báo cáo Near Miss.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "KPI Near Miss.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chương trình thưởng khuyến khích báo cáo.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 3 – Điều tra Near Miss",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Công cụ áp dụng:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "5 Why.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Fishbone Diagram.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Root Cause Analysis.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Corrective Action Plan.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Điều tra 05 tình huống Near Miss thực tế.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "NGÀY 02",
                                                "lessons": [
                                                            {
                                                                        "title": "BEHAVIOR BASED SAFETY (BBS)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 4 – Tổng quan BBS",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hành vi ảnh hưởng đến tai nạn như thế nào.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mô hình ABC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Human Factors.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Unsafe Act.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Unsafe Condition.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 5 – Thiết kế chương trình BBS",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định Critical Behaviors.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng Checklist BBS.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân công Observer.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phương pháp thu thập dữ liệu.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết kế:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "BBS Observation Card.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "BBS KPI.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Dashboard BBS.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 6 – Kỹ năng Safety Coaching",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quan sát hành vi.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phản hồi tích cực.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Coaching tại hiện trường.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Giao tiếp với người lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Role Play BBS Coaching.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "NGÀY 03",
                                                "lessons": [
                                                            {
                                                                        "title": "LIFE SAVING RULES (LSR) VÀ WSFI",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 7 – Life Saving Rules",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm Life Saving Rules.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các hành vi có nguy cơ tử vong.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các nguyên nhân gây tai nạn nghiêm trọng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng bộ LSR",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bao gồm:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khóa và gắn thẻ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Làm việc trên cao.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Không gian hạn chế.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Điện.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xe nâng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hóa chất.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết bị nâng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bộ 10 – 12 Life Saving Rules cho nhà máy.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 8 – Workplace Safety Field Inspection (WSFI)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khác biệt giữa Audit và Inspection.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phương pháp WSFI.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ thuật quan sát hiện trường.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng phát hiện nguy cơ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Checklist WSFI.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Lịch WSFI.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "KPI WSFI.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "NGÀY 04",
                                                "lessons": [
                                                            {
                                                                        "title": "JOB HAZARD ANALYSIS (JHA)",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 9 – Phân tích công việc và nhận diện nguy cơ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm JHA.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khi nào cần JHA.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các bước thực hiện JHA.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 10 – Thực hành JHA",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hiện JHA cho:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xe nâng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Làm việc trên cao.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bảo trì máy.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hàn cắt.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hóa chất.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Không gian hạn chế.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Biểu mẫu JHA.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình JHA.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ma trận phê duyệt JHA.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 11 – Permit To Work liên kết JHA",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Giấy phép làm việc.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát công việc nguy hiểm.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trách nhiệm các bên.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "NGÀY 05",
                                                "lessons": [
                                                            {
                                                                        "title": "ĐÁNH GIÁ RỦI RO (DGRR) VÀ XÂY DỰNG CHIẾN LƯỢC HSE",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 12 – Đánh giá rủi ro nâng cao",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "ISO 45001 Risk Based Thinking.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ma trận rủi ro.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "HIRA (Hazard Identification and Risk Assessment).",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "ALARP Principle.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Risk Register.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 13 – Thiết lập KPI an toàn chủ động",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "KPI đề xuất:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Near Miss Rate.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "BBS Observation.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "JHA Completion.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "WSFI Closure Rate.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Action Closure Rate.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Training Completion.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 14 – Xây dựng Roadmap HSE 12 tháng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop nhóm:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch Near Miss.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch BBS.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch JHA.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch WSFI.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch đào tạo.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch cải tiến văn hóa an toàn.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 11
            },
            {
                        "category_slug": "hse",
                        "title": "XÂY DỰNG HỆ THỐNG QUẢN LÝ LAO ĐỘNG VÀ TRÁCH NHIỆM XÃ HỘI THEO YÊU CẦU CASCALE",
                        "slug": "xay-dung-he-thong-quan-ly-lao-dong-va-trach-nhiem-xa-hoi-theo-yeu-cau-cascale",
                        "description": "GIAI ĐOẠN 1\nKHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2\nĐÁNH GIÁ THỰC TRẠNG (GAP ASSESSMENT)\nGIAI ĐOẠN 3\nXÂY DỰNG HỆ THỐNG HIGG FSLM\nGIAI ĐOẠN 4\nTRIỂN KHAI VÀ ĐÀO TẠO CHUYÊN SÂU\nGIAI ĐOẠN 5\nTỰ ĐÁNH GIÁ HIGG FSLM\nGIAI ĐOẠN 6\nPRE-ASSESSMENT VÀ CHUẨN BỊ ĐÁNH GIÁ KHÁCH HÀNG",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>GIAI ĐOẠN 1</p>\n<p>KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC</p>\n<p>GIAI ĐOẠN 2</p>\n<p>ĐÁNH GIÁ THỰC TRẠNG (GAP ASSESSMENT)</p>\n<p>GIAI ĐOẠN 3</p>\n<p>XÂY DỰNG HỆ THỐNG HIGG FSLM</p>\n<p>GIAI ĐOẠN 4</p>\n<p>TRIỂN KHAI VÀ ĐÀO TẠO CHUYÊN SÂU</p>\n<p>GIAI ĐOẠN 5</p>\n<p>TỰ ĐÁNH GIÁ HIGG FSLM</p>\n<p>GIAI ĐOẠN 6</p>\n<p>PRE-ASSESSMENT VÀ CHUẨN BỊ ĐÁNH GIÁ KHÁCH HÀNG</p>\n",
                        "curriculum": [
                                    {
                                                "title": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC",
                                                "lessons": [
                                                            {
                                                                        "title": "Tổng quan Cascale và Higg Index.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan Higg FSLM.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mục tiêu và lợi ích của Higg FSLM.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cấu trúc đánh giá Higg FSLM.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò của các bộ phận.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thành lập nhóm triển khai.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng kế hoạch thực hiện.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch triển khai.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hồ sơ đào tạo nhận thức.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ban triển khai Higg FSLM.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 2 – ĐÁNH GIÁ THỰC TRẠNG (GAP ASSESSMENT)",
                                                "lessons": [
                                                            {
                                                                        "title": "Đánh giá hiện trạng hệ thống quản lý lao động và trách nhiệm xã hội:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản trị và hệ thống quản lý",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chính sách.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mục tiêu.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trách nhiệm.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Truyền thông.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá nội bộ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tuyển dụng và lao động",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tuyển dụng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hợp đồng lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hồ sơ lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Lao động thời vụ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Lao động chưa thành niên.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tiền lương và phúc lợi",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Lương tối thiểu.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Làm thêm giờ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phụ cấp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phúc lợi.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bảo hiểm.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thời giờ làm việc",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chấm công.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tăng ca.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nghỉ phép.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ngày nghỉ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quan hệ lao động",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khiếu nại.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đối thoại.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Công đoàn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đại diện người lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Sức khỏe và an toàn nghề nghiệp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá rủi ro.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "PPE.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hóa chất.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Máy móc thiết bị.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "PCCC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ứng phó khẩn cấp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Báo cáo Gap Assessment.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Danh mục điểm chưa phù hợp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch cải tiến.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 3 – XÂY DỰNG HỆ THỐNG HIGG FSLM",
                                                "lessons": [
                                                            {
                                                                        "title": "Xây dựng và chuẩn hóa:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chính sách",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chính sách trách nhiệm xã hội.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chính sách nhân quyền.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chính sách lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chính sách chống phân biệt đối xử.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chính sách chống quấy rối.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chính sách an toàn sức khỏe nghề nghiệp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình tuyển dụng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình quản lý hồ sơ nhân sự.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình khiếu nại.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình đối thoại người lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình đánh giá rủi ro.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình quản lý nhà thầu.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình quản lý nhà cung cấp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình điều tra sự cố.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình hành động khắc phục.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bộ tài liệu Higg FSLM.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bộ quy trình quản lý lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bộ biểu mẫu quản lý.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 4 – TRIỂN KHAI VÀ ĐÀO TẠO CHUYÊN SÂU",
                                                "lessons": []
                                    },
                                    {
                                                "title": "Mô-đun 1 – Quản lý lao động",
                                                "lessons": [
                                                            {
                                                                        "title": "Tuyển dụng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hợp đồng lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hồ sơ nhân sự.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Lao động chưa thành niên.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "Mô-đun 2 – Tiền lương và thời giờ làm việc",
                                                "lessons": [
                                                            {
                                                                        "title": "Kiểm soát bảng công.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát bảng lương.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản lý tăng ca.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phúc lợi.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "Mô-đun 3 – Sức khỏe và an toàn nghề nghiệp",
                                                "lessons": [
                                                            {
                                                                        "title": "Đánh giá rủi ro.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "PPE.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hóa chất.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tai nạn lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "PCCC.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "Mô-đun 4 – Quan hệ lao động",
                                                "lessons": [
                                                            {
                                                                        "title": "Khiếu nại.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đối thoại.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Truyền thông nội bộ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hồ sơ đào tạo.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hồ sơ triển khai.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hồ sơ vận hành thực tế.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 5 – TỰ ĐÁNH GIÁ HIGG FSLM",
                                                "lessons": [
                                                            {
                                                                        "title": "Hướng dẫn thực hiện Self-Assessment.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thu thập bằng chứng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá mức độ trưởng thành.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chấm điểm theo từng phần.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân tích kết quả.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng kế hoạch cải tiến.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Báo cáo Self-Assessment.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bảng điểm Higg FSLM.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch cải tiến.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 6 – PRE-ASSESSMENT VÀ CHUẨN BỊ ĐÁNH GIÁ KHÁCH HÀNG",
                                                "lessons": [
                                                            {
                                                                        "title": "Đánh giá thử toàn bộ hệ thống.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phỏng vấn người lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra hồ sơ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra hiện trường.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Rà soát điểm chưa phù hợp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn khắc phục.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuẩn bị hồ sơ khách hàng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Báo cáo Pre-Assessment.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Danh mục hồ sơ sẵn sàng đánh giá.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch hành động cuối cùng.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 12
            },
            {
                        "category_slug": "hse",
                        "title": "XÂY DỰNG TẦM NHÌN – SỨ MỆNH – CHIẾN LƯỢC HSE – TỪ QUẢN LÝ HSE ĐẾN LÃNH ĐẠO HSE XUẤT SẮC",
                        "slug": "xay-dung-tam-nhin-su-menh-chien-luoc-hse-tu-quan-ly-hse-den-lanh-dao-hse-xuat-sac",
                        "description": "PHẦN 1\nHSE TRONG CHIẾN LƯỢC PHÁT TRIỂN DOANH NGHIỆP\nChuyên đề 1\nTừ HSE Compliance đến HSE Excellence\nChuyên đề 2\nĐánh giá mức độ trưởng thành của hệ thống HSE\nPHẦN 2\nXÂY DỰNG TẦM NHÌN HSE\nChuyên đề 3\nHSE Vision – Tầm nhìn HSE\nPHẦN 3\nXÂY DỰNG SỨ MỆNH HSE\nChuyên đề 4\nHSE Mission – Sứ mệnh HSE\nPHẦN 4\nXÂY DỰNG GIÁ TRỊ CỐT LÕI HSE\nChuyên đề 5\nHSE Core Values\nPHẦN 5\nXÂY DỰNG CHIẾN LƯỢC HSE\nChuyên đề 6\nHSE Strategic Planning\nChuyên đề 7\nXác định các trụ cột chiến lược\nPHẦN 6\nXÂY DỰNG KPI VÀ MỤC TIÊU HSE\nChuyên đề 8\nThiết lập mục tiêu HSE\nPHẦN 7\nXÂY DỰNG ROADMAP HSE 3 NĂM\nChuyên đề 9\nHSE Transformation Roadmap\nPHẦN 8\nCAM KẾT LÃNH ĐẠO\nChuyên đề 10\nLeadership Commitment Workshop",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>PHẦN 1</p>\n<p>HSE TRONG CHIẾN LƯỢC PHÁT TRIỂN DOANH NGHIỆP</p>\n<p>Chuyên đề 1</p>\n<p>Từ HSE Compliance đến HSE Excellence</p>\n<p>Chuyên đề 2</p>\n<p>Đánh giá mức độ trưởng thành của hệ thống HSE</p>\n<p>PHẦN 2</p>\n<p>XÂY DỰNG TẦM NHÌN HSE</p>\n<p>Chuyên đề 3</p>\n<p>HSE Vision – Tầm nhìn HSE</p>\n<p>PHẦN 3</p>\n<p>XÂY DỰNG SỨ MỆNH HSE</p>\n<p>Chuyên đề 4</p>\n<p>HSE Mission – Sứ mệnh HSE</p>\n<p>PHẦN 4</p>\n<p>XÂY DỰNG GIÁ TRỊ CỐT LÕI HSE</p>\n<p>Chuyên đề 5</p>\n<p>HSE Core Values</p>\n<p>PHẦN 5</p>\n<p>XÂY DỰNG CHIẾN LƯỢC HSE</p>\n<p>Chuyên đề 6</p>\n<p>HSE Strategic Planning</p>\n<p>Chuyên đề 7</p>\n<p>Xác định các trụ cột chiến lược</p>\n<p>PHẦN 6</p>\n<p>XÂY DỰNG KPI VÀ MỤC TIÊU HSE</p>\n<p>Chuyên đề 8</p>\n<p>Thiết lập mục tiêu HSE</p>\n<p>PHẦN 7</p>\n<p>XÂY DỰNG ROADMAP HSE 3 NĂM</p>\n<p>Chuyên đề 9</p>\n<p>HSE Transformation Roadmap</p>\n<p>PHẦN 8</p>\n<p>CAM KẾT LÃNH ĐẠO</p>\n<p>Chuyên đề 10</p>\n<p>Leadership Commitment Workshop</p>\n",
                        "curriculum": [
                                    {
                                                "title": "PHẦN 1 – HSE TRONG CHIẾN LƯỢC PHÁT TRIỂN DOANH NGHIỆP",
                                                "lessons": [
                                                            {
                                                                        "title": "Chuyên đề 1 – Từ HSE Compliance đến HSE Excellence",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Sự khác biệt giữa doanh nghiệp tuân thủ và doanh nghiệp xuất sắc về HSE.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xu hướng quản trị HSE trên thế giới.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò của HSE trong ESG.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò của HSE trong phát triển bền vững.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò của HSE trong chuỗi cung ứng toàn cầu.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chi phí hữu hình và vô hình của tai nạn lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá hiện trạng HSE của doanh nghiệp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 2 – Đánh giá mức độ trưởng thành của hệ thống HSE",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mô hình đánh giá:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Reactive.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Dependent.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Independent.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Interdependent.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định doanh nghiệp đang ở cấp độ nào.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định khoảng cách cần cải thiện.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 2 – XÂY DỰNG TẦM NHÌN HSE",
                                                "lessons": [
                                                            {
                                                                        "title": "Chuyên đề 3 – HSE Vision – Tầm nhìn HSE",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm tầm nhìn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò của tầm nhìn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đặc điểm của một tầm nhìn hiệu quả.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tầm nhìn HSE của các tập đoàn hàng đầu thế giới.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ví dụ tham khảo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Zero Harm.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Everyone Goes Home Safe Every Day.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety is Our Value.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng dự thảo tầm nhìn HSE cho doanh nghiệp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bản dự thảo Tầm nhìn HSE.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 3 – XÂY DỰNG SỨ MỆNH HSE",
                                                "lessons": [
                                                            {
                                                                        "title": "Chuyên đề 4 – HSE Mission – Sứ mệnh HSE",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm sứ mệnh.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò của sứ mệnh.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cách xây dựng sứ mệnh tạo động lực.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đối tượng được bảo vệ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Giá trị doanh nghiệp tạo ra.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cam kết đối với người lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cam kết đối với cộng đồng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bản dự thảo Sứ mệnh HSE.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 4 – XÂY DỰNG GIÁ TRỊ CỐT LÕI HSE",
                                                "lessons": [
                                                            {
                                                                        "title": "Chuyên đề 5 – HSE Core Values",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các giá trị cốt lõi thường gặp:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety First.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Respect for People.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Accountability.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Integrity.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Continuous Improvement.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Zero Tolerance for Unsafe Acts.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng bộ giá trị cốt lõi HSE riêng của doanh nghiệp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bộ giá trị cốt lõi HSE.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 5 – XÂY DỰNG CHIẾN LƯỢC HSE",
                                                "lessons": [
                                                            {
                                                                        "title": "Chuyên đề 6 – HSE Strategic Planning",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Khái niệm chiến lược HSE.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chiến lược 3 năm.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chiến lược 5 năm.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mô hình Balanced Scorecard cho HSE.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 7 – Xác định các trụ cột chiến lược",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop xây dựng:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trụ cột 1 – Lãnh đạo và văn hóa an toàn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trụ cột 2 – Quản lý rủi ro.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trụ cột 3 – Năng lực và nhận thức.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trụ cột 4 – Hiệu suất môi trường.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trụ cột 5 – Sức khỏe nghề nghiệp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Trụ cột 6 – Số hóa và chuyển đổi HSE.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bản đồ chiến lược HSE.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 6 – XÂY DỰNG KPI VÀ MỤC TIÊU HSE",
                                                "lessons": [
                                                            {
                                                                        "title": "Chuyên đề 8 – Thiết lập mục tiêu HSE",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "KPI dẫn dắt (Leading Indicators).",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "KPI kết quả (Lagging Indicators).",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ví dụ KPI",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Near Miss Reporting.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "BBS Observation.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "JHA Completion.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Safety Walk.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "WSFI.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Closure Rate.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "LTIFR.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "TRIR.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng KPI cho:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ban lãnh đạo.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản lý.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "HSE.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Sản xuất.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 7 – XÂY DỰNG ROADMAP HSE 3 NĂM",
                                                "lessons": [
                                                            {
                                                                        "title": "Chuyên đề 9 – HSE Transformation Roadmap",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Năm 1",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây nền tảng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Năm 2",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuẩn hóa hệ thống.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Năm 3",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Văn hóa an toàn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mục tiêu năm.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch năm.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Dự án ưu tiên.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ngân sách HSE.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Roadmap HSE 3 năm.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 8 – CAM KẾT LÃNH ĐẠO",
                                                "lessons": [
                                                            {
                                                                        "title": "Chuyên đề 10 – Leadership Commitment Workshop",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ban lãnh đạo cùng xây dựng:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tuyên bố cam kết HSE.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cam kết trách nhiệm giải trình.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chương trình Leadership Safety Walk.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chương trình Safety Culture Campaign.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 13
            },
            {
                        "category_slug": "carbon",
                        "title": "Kiểm kê Khí nhà kính theo Nghị định 06/2022/NĐ-CP",
                        "slug": "kiem-ke-khi-nha-kinh-theo-nghi-dinh-06-2022-nd-cp",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 14
            },
            {
                        "category_slug": "carbon",
                        "title": "Kiểm kê Khí nhà kính theo ISO 14064-1:2018",
                        "slug": "kiem-ke-khi-nha-kinh-theo-iso-14064-1-2018",
                        "description": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2 – XÁC ĐỊNH NGUỒN PHÁT THẢI VÀ THU THẬP DỮ LIỆU\nGIAI ĐOẠN 3 – XÂY DỰNG HỆ THỐNG TÍNH TOÁN PHÁT THẢI\nGIAI ĐOẠN 4 – XÂY DỰNG HỆ THỐNG TÀI LIỆU VÀ BÁO CÁO\nGIAI ĐOẠN 5 – ĐÀO TẠO VÀ HOÀN THIỆN HỆ THỐNG",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC</p>\n<p>GIAI ĐOẠN 2 – XÁC ĐỊNH NGUỒN PHÁT THẢI VÀ THU THẬP DỮ LIỆU</p>\n<p>GIAI ĐOẠN 3 – XÂY DỰNG HỆ THỐNG TÍNH TOÁN PHÁT THẢI</p>\n<p>GIAI ĐOẠN 4 – XÂY DỰNG HỆ THỐNG TÀI LIỆU VÀ BÁO CÁO</p>\n<p>GIAI ĐOẠN 5 – ĐÀO TẠO VÀ HOÀN THIỆN HỆ THỐNG</p>\n",
                        "curriculum": [
                                    {
                                                "title": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC",
                                                "lessons": [
                                                            {
                                                                        "title": "Thành lập Ban GHG",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng kế hoạch triển khai",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân công vai trò và trách nhiệm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo nhận thức ISO 14064-1:2018",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan về biến đổi khí hậu và khí nhà kính",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan Scope 1, Scope 2 và Scope 3",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan ESG và Carbon Management",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 2 – XÁC ĐỊNH NGUỒN PHÁT THẢI VÀ THU THẬP DỮ LIỆU",
                                                "lessons": [
                                                            {
                                                                        "title": "Xác định Organizational Boundary",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định Operational Boundary",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định nguồn phát thải Scope 1",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định nguồn phát thải Scope 2",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xác định nguồn phát thải Scope 3",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập danh mục nguồn phát thải",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thu thập Activity Data",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng Data Collection System",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá chất lượng dữ liệu",
                                                                        "duration": "03 – 04 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 3 – XÂY DỰNG HỆ THỐNG TÍNH TOÁN PHÁT THẢI",
                                                "lessons": [
                                                            {
                                                                        "title": "Xây dựng phương pháp tính toán phát thải",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Áp dụng Emission Factor",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập bảng tính GHG",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tính toán Scope 1",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tính toán Scope 2",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tính toán Scope 3",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra và rà soát dữ liệu",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng hệ thống quản lý dữ liệu GHG",
                                                                        "duration": "03 – 04 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 4 – XÂY DỰNG HỆ THỐNG TÀI LIỆU VÀ BÁO CÁO",
                                                "lessons": [
                                                            {
                                                                        "title": "Xây dựng GHG Procedure",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng SOP thu thập dữ liệu",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng Data Management Procedure",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng Risk and Opportunity Assessment",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng Quality Management Plan",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng báo cáo kiểm kê khí nhà kính",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng hồ sơ phục vụ xác minh",
                                                                        "duration": "02 – 03 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 5 – ĐÀO TẠO VÀ HOÀN THIỆN HỆ THỐNG",
                                                "lessons": [
                                                            {
                                                                        "title": "Đào tạo đội ngũ thực hiện GHG",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo quản lý dữ liệu phát thải",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo kiểm soát chất lượng dữ liệu",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn chuẩn bị xác minh",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Rà soát và hoàn thiện hệ thống",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Coaching thực tế tại doanh nghiệp",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 15
            },
            {
                        "category_slug": "carbon",
                        "title": "Hướng dẫn Định lượng Khí nhà kính theo ISO 14069:2013",
                        "slug": "huong-dan-dinh-luong-khi-nha-kinh-theo-iso-14069-2013",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 16
            },
            {
                        "category_slug": "carbon",
                        "title": "Greenhouse Gas Protocol (GHG Protocol)",
                        "slug": "greenhouse-gas-protocol-ghg-protocol",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 17
            },
            {
                        "category_slug": "carbon",
                        "title": "Tính toán Vết Carbon của Sản phẩm theo ISO 14067:2018",
                        "slug": "tinh-toan-vet-carbon-cua-san-pham-theo-iso-14067-2018",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 18
            },
            {
                        "category_slug": "carbon",
                        "title": "PAS 2060:2014 – Chứng nhận Trung hòa Carbon",
                        "slug": "pas-2060-2014-chung-nhan-trung-hoa-carbon",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 19
            },
            {
                        "category_slug": "carbon",
                        "title": "ISO 14068-1:2023 – Trung hòa Carbon",
                        "slug": "iso-14068-1-2023-trung-hoa-carbon",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 20
            },
            {
                        "category_slug": "carbon",
                        "title": "Verified Carbon Standard (VCS) – Tiêu chuẩn Carbon đã được Xác minh",
                        "slug": "verified-carbon-standard-vcs-tieu-chuan-carbon-da-duoc-xac-minh",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 21
            },
            {
                        "category_slug": "esg",
                        "title": "Environmental, Social and Governance (ESG) – Môi trường, Xã hội và Quản trị",
                        "slug": "environmental-social-and-governance-esg-moi-truong-xa-hoi-va-quan-tri",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 22
            },
            {
                        "category_slug": "esg",
                        "title": "Global Reporting Initiative (GRI) – Sáng kiến Báo cáo Toàn cầu",
                        "slug": "global-reporting-initiative-gri-sang-kien-bao-cao-toan-cau",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 23
            },
            {
                        "category_slug": "esg",
                        "title": "QUẢN LÝ NGUỒN CUNG ỨNG RỪNG BỀN VỮNG VÀ TRÁCH NHIỆM",
                        "slug": "quan-ly-nguon-cung-ung-rung-ben-vung-va-trach-nhiem",
                        "description": "Chuyên đề 1\nTổng quan FSC\nChuyên đề 2\n10 Nguyên tắc FSC\nChuyên đề 3\nFSC và Phát triển bền vững\nChuyên đề 4\nFSC và Chuỗi cung ứng\nChuyên đề 5\nFSC và Doanh nghiệp",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>Chuyên đề 1</p>\n<p>Tổng quan FSC</p>\n<p>Chuyên đề 2</p>\n<p>10 Nguyên tắc FSC</p>\n<p>Chuyên đề 3</p>\n<p>FSC và Phát triển bền vững</p>\n<p>Chuyên đề 4</p>\n<p>FSC và Chuỗi cung ứng</p>\n<p>Chuyên đề 5</p>\n<p>FSC và Doanh nghiệp</p>\n",
                        "curriculum": [
                                    {
                                                "title": "Nội dung chi tiết",
                                                "lessons": [
                                                            {
                                                                        "title": "Chuyên đề 1 – Tổng quan FSC",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Lịch sử hình thành FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Cơ cấu tổ chức FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hệ thống chứng nhận FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 2 – 10 Nguyên tắc FSC",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bao gồm:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tuân thủ pháp luật.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quyền người lao động.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quyền cộng đồng địa phương.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quan hệ với người dân bản địa.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Lợi ích từ rừng.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Giá trị môi trường.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch quản lý.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Giám sát.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Giá trị bảo tồn cao.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hiện các hoạt động quản lý.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 3 – FSC và Phát triển bền vững",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "ESG.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kinh tế tuần hoàn.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Truy xuất nguồn gốc.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bảo tồn đa dạng sinh học.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản lý tài nguyên thiên nhiên.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 4 – FSC và Chuỗi cung ứng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản lý nhà cung cấp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Truy xuất nguồn gốc.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá rủi ro nguồn cung.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tuyên bố FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuyên đề 5 – FSC và Doanh nghiệp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Lợi ích thương mại.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Yêu cầu khách hàng quốc tế.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "FSC và các thị trường xuất khẩu.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "FSC và chiến lược phát triển bền vững.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 24
            },
            {
                        "category_slug": "esg",
                        "title": "Science Based Targets initiative (SBTi) – Sáng kiến Mục tiêu Dựa trên Cơ sở Khoa học",
                        "slug": "science-based-targets-initiative-sbti-sang-kien-muc-tieu-dua-tren-co-so-khoa-hoc",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 25
            },
            {
                        "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
                        "title": "HỆ THỐNG QUẢN LÝ CHUỖI HÀNH TRÌNH SẢN PHẨM FSC",
                        "slug": "he-thong-quan-ly-chuoi-hanh-trinh-san-pham-fsc",
                        "description": "GIAI ĐOẠN 1\nKHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2\nĐÁNH GIÁ THỰC TRẠNG (GAP ASSESSMENT)\nGIAI ĐOẠN 3\nXÂY DỰNG HỆ THỐNG FSC CoC\nGIAI ĐOẠN 4\nTRIỂN KHAI HỆ THỐNG VÀ ĐÀO TẠO CHUYÊN SÂU\nGIAI ĐOẠN 5\nĐÁNH GIÁ NỘI BỘ VÀ CHUẨN BỊ CHỨNG NHẬN",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>GIAI ĐOẠN 1</p>\n<p>KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC</p>\n<p>GIAI ĐOẠN 2</p>\n<p>ĐÁNH GIÁ THỰC TRẠNG (GAP ASSESSMENT)</p>\n<p>GIAI ĐOẠN 3</p>\n<p>XÂY DỰNG HỆ THỐNG FSC CoC</p>\n<p>GIAI ĐOẠN 4</p>\n<p>TRIỂN KHAI HỆ THỐNG VÀ ĐÀO TẠO CHUYÊN SÂU</p>\n<p>GIAI ĐOẠN 5</p>\n<p>ĐÁNH GIÁ NỘI BỘ VÀ CHUẨN BỊ CHỨNG NHẬN</p>\n",
                        "curriculum": [
                                    {
                                                "title": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC",
                                                "lessons": [
                                                            {
                                                                        "title": "Tổng quan FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan FSC CoC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Các loại chứng nhận FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "FSC 100%.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "FSC Mix.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "FSC Recycled.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Yêu cầu khách hàng và thị trường.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò và trách nhiệm các bộ phận.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thành lập nhóm triển khai FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch triển khai.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hồ sơ đào tạo nhận thức.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Ban triển khai FSC.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 2 – ĐÁNH GIÁ THỰC TRẠNG (GAP ASSESSMENT)",
                                                "lessons": [
                                                            {
                                                                        "title": "Đánh giá hệ thống hiện tại.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá nhà cung cấp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá quản lý kho.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá truy xuất nguồn gốc.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá quản lý hồ sơ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá hoạt động sản xuất.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá ghi nhãn sản phẩm.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá năng lực nhân sự.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Báo cáo GAP.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Danh mục điểm chưa phù hợp.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kế hoạch hành động.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 3 – XÂY DỰNG HỆ THỐNG FSC CoC",
                                                "lessons": [
                                                            {
                                                                        "title": "Xây dựng:",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chính sách FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mục tiêu FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình mua hàng FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình tiếp nhận nguyên liệu FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình quản lý kho FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình truy xuất nguồn gốc.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình sản xuất sản phẩm FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình bán hàng FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình quản lý tuyên bố FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình quản lý nhãn FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình kiểm soát hồ sơ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quy trình đánh giá nội bộ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Bộ tài liệu FSC CoC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Biểu mẫu FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Danh mục hồ sơ kiểm soát.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 4 – TRIỂN KHAI HỆ THỐNG VÀ ĐÀO TẠO CHUYÊN SÂU",
                                                "lessons": [
                                                            {
                                                                        "title": "Quản lý nhà cung cấp FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra chứng chỉ FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát hóa đơn và chứng từ FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát tồn kho FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản lý sản xuất sản phẩm FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản lý tuyên bố FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quản lý nhãn FSC.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Truy xuất nguồn gốc sản phẩm.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hồ sơ đào tạo.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hồ sơ vận hành thực tế.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hồ sơ truy xuất nguồn gốc.",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ CHUẨN BỊ CHỨNG NHẬN",
                                                "lessons": [
                                                            {
                                                                        "title": "Đào tạo đánh giá viên nội bộ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hiện đánh giá nội bộ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hành động khắc phục.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xem xét của lãnh đạo.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuẩn bị đánh giá chứng nhận.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đầu ra",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Báo cáo đánh giá nội bộ.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "CAPA Plan.",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hồ sơ sẵn sàng chứng nhận",
                                                                        "duration": ""
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 26
            },
            {
                        "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
                        "title": "ISCC (PLUS/EU/CORSIA) & GRS – Tiêu chuẩn Tái chế Toàn cầu",
                        "slug": "iscc-plus-eu-corsia-grs-tieu-chuan-tai-che-toan-cau",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 27
            },
            {
                        "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
                        "title": "EN 15343 – Truy xuất nguồn gốc và xác định hàm lượng nhựa tái chế trong sản phẩm nhựa",
                        "slug": "en-15343-truy-xuat-nguon-goc-va-xac-dinh-ham-luong-nhua-tai-che-trong-san-pham-nhua",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 28
            },
            {
                        "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
                        "title": "Cradle to Cradle Certified® – Tiêu chuẩn Thiết kế Sản phẩm Tuần hoàn",
                        "slug": "cradle-to-cradle-certified-tieu-chuan-thiet-ke-san-pham-tuan-hoan",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 29
            },
            {
                        "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
                        "title": "Higg Facility Environmental Module (Higg FEM)",
                        "slug": "higg-facility-environmental-module-higg-fem",
                        "description": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2 – THU THẬP DỮ LIỆU VÀ XÂY DỰNG HỆ THỐNG QUẢN LÝ\nGIAI ĐOẠN 3 – TRIỂN KHAI CÁC MODULE HIGG FEM\nGIAI ĐOẠN 4 – ĐÀO TẠO VÀ TRIỂN KHAI THỰC TẾ\nGIAI ĐOẠN 5 – RÀ SOÁT VÀ HOÀN THIỆN HỆ THỐNG",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC</p>\n<p>GIAI ĐOẠN 2 – THU THẬP DỮ LIỆU VÀ XÂY DỰNG HỆ THỐNG QUẢN LÝ</p>\n<p>GIAI ĐOẠN 3 – TRIỂN KHAI CÁC MODULE HIGG FEM</p>\n<p>GIAI ĐOẠN 4 – ĐÀO TẠO VÀ TRIỂN KHAI THỰC TẾ</p>\n<p>GIAI ĐOẠN 5 – RÀ SOÁT VÀ HOÀN THIỆN HỆ THỐNG</p>\n",
                        "curriculum": [
                                    {
                                                "title": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC",
                                                "lessons": [
                                                            {
                                                                        "title": "Thành lập Ban Higg FEM",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng kế hoạch triển khai",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân công vai trò và trách nhiệm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo nhận thức Higg FEM",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan về Sustainability và ESG",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan cấu trúc Higg FEM",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng quan Verification Process",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn sử dụng nền tảng Higg FEM",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 2 – THU THẬP DỮ LIỆU VÀ XÂY DỰNG HỆ THỐNG QUẢN LÝ",
                                                "lessons": [
                                                            {
                                                                        "title": "Thu thập dữ liệu môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng hệ thống quản lý dữ liệu",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát tài liệu và hồ sơ môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập KPI môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát dữ liệu tiêu thụ năng lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát dữ liệu nước",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát dữ liệu chất thải",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát dữ liệu khí thải",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm soát hóa chất",
                                                                        "duration": "03 – 04 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 3 – TRIỂN KHAI CÁC MODULE HIGG FEM",
                                                "lessons": [
                                                            {
                                                                        "title": "Environmental Management System",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chính sách môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Mục tiêu môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Legal Compliance",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Internal Audit",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Management Review",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Energy and GHG",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Energy Management",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "GHG Inventory",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Energy KPI",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Energy Saving Program",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Water Management",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Water Consumption",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Water Balance",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Water Reduction Program",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Wastewater Management",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Waste Management",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Waste Inventory",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Waste Segregation",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Waste Tracking",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hazardous Waste Management",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Air Emission",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Air Emission Inventory",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Air Pollution Control",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Monitoring and Reporting",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chemical Management",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chemical Inventory",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "SDS Management",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chemical Risk Assessment",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Spill Response",
                                                                        "duration": "03 – 04 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 4 – ĐÀO TẠO VÀ TRIỂN KHAI THỰC TẾ",
                                                "lessons": [
                                                            {
                                                                        "title": "Đào tạo đội ngũ thực hiện Higg FEM",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo quản lý dữ liệu môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đào tạo kiểm soát hồ sơ",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Coaching hiện trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn nhập dữ liệu Higg FEM",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hướng dẫn chuẩn bị Verification",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi hiệu quả áp dụng thực tế",
                                                                        "duration": "02 – 03 ngày"
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "GIAI ĐOẠN 5 – RÀ SOÁT VÀ HOÀN THIỆN HỆ THỐNG",
                                                "lessons": [
                                                            {
                                                                        "title": "Rà soát dữ liệu Higg FEM",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra hồ sơ môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra hiện trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá mức độ đáp ứng Higg FEM",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi hành động cải tiến",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Hoàn thiện hồ sơ Verification",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Chuẩn bị tiếp đoàn Verification",
                                                                        "duration": "02 ngày"
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 30
            },
            {
                        "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
                        "title": "Đánh giá Nhà cung cấp",
                        "slug": "danh-gia-nha-cung-cap",
                        "description": "NGÀY 1 – TỔNG QUAN VỀ ĐÁNH GIÁ NHÀ CUNG CẤP\nPHẦN 1 – TỔNG QUAN QUẢN LÝ NHÀ CUNG CẤP\nPHẦN 2 – HỆ THỐNG ĐÁNH GIÁ NHÀ CUNG CẤP\nPHẦN 3 – ĐÁNH GIÁ HỆ THỐNG NHÀ CUNG CẤP\nPHẦN 4 – KỸ NĂNG ĐÁNH GIÁ NHÀ CUNG CẤP\nNGÀY 2 – TRIỂN KHAI ĐÁNH GIÁ VÀ CẢI TIẾN NHÀ CUNG CẤP\nPHẦN 5 – ĐÁNH GIÁ HIỆN TRƯỜNG NHÀ CUNG CẤP\nPHẦN 6 – PHÂN TÍCH RỦI RO NHÀ CUNG CẤP\nPHẦN 7 – CAPA VÀ PHÁT TRIỂN NHÀ CUNG CẤP\nPHẦN 8 – XÂY DỰNG HỆ THỐNG QUẢN LÝ NHÀ CUNG CẤP\nPHẦN 9 – TỔNG KẾT VÀ ĐÁNH GIÁ CUỐI KHÓA",
                        "content": "<h3><strong>Nội dung đào tạo</strong></h3>\n<p>NGÀY 1 – TỔNG QUAN VỀ ĐÁNH GIÁ NHÀ CUNG CẤP</p>\n<p>PHẦN 1 – TỔNG QUAN QUẢN LÝ NHÀ CUNG CẤP</p>\n<p>PHẦN 2 – HỆ THỐNG ĐÁNH GIÁ NHÀ CUNG CẤP</p>\n<p>PHẦN 3 – ĐÁNH GIÁ HỆ THỐNG NHÀ CUNG CẤP</p>\n<p>PHẦN 4 – KỸ NĂNG ĐÁNH GIÁ NHÀ CUNG CẤP</p>\n<p>NGÀY 2 – TRIỂN KHAI ĐÁNH GIÁ VÀ CẢI TIẾN NHÀ CUNG CẤP</p>\n<p>PHẦN 5 – ĐÁNH GIÁ HIỆN TRƯỜNG NHÀ CUNG CẤP</p>\n<p>PHẦN 6 – PHÂN TÍCH RỦI RO NHÀ CUNG CẤP</p>\n<p>PHẦN 7 – CAPA VÀ PHÁT TRIỂN NHÀ CUNG CẤP</p>\n<p>PHẦN 8 – XÂY DỰNG HỆ THỐNG QUẢN LÝ NHÀ CUNG CẤP</p>\n<p>PHẦN 9 – TỔNG KẾT VÀ ĐÁNH GIÁ CUỐI KHÓA</p>\n",
                        "curriculum": [
                                    {
                                                "title": "NGÀY 1 – TỔNG QUAN VỀ ĐÁNH GIÁ NHÀ CUNG CẤP",
                                                "lessons": []
                                    },
                                    {
                                                "title": "PHẦN 1 – TỔNG QUAN QUẢN LÝ NHÀ CUNG CẤP",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò của nhà cung cấp trong chuỗi cung ứng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Rủi ro trong chuỗi cung ứng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Lifecycle Management",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tiêu chí lựa chọn nhà cung cấp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Yêu cầu khách hàng và tiêu chuẩn quốc tế",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Vai trò của Supplier Assessment",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 2 – HỆ THỐNG ĐÁNH GIÁ NHÀ CUNG CẤP",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Evaluation Process",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Approval Process",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Risk Assessment",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Classification",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier KPI",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Approved Vendor List",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung thực hành",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Xây dựng tiêu chí đánh giá nhà cung cấp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập bảng chấm điểm",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Phân loại nhà cung cấp",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 3 – ĐÁNH GIÁ HỆ THỐNG NHÀ CUNG CẤP",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá hệ thống quản lý chất lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá năng lực sản xuất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá kiểm soát chất lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá truy xuất nguồn gốc",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá năng lực giao hàng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá quản lý kho",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá quản lý thay đổi",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá quản lý rủi ro",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 4 – KỸ NĂNG ĐÁNH GIÁ NHÀ CUNG CẤP",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng phỏng vấn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng quan sát hiện trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng đặt câu hỏi",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng thu thập bằng chứng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng viết báo cáo đánh giá",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kỹ năng giao tiếp với nhà cung cấp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung thực hành",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành phỏng vấn",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thực hành đánh giá tình huống",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Workshop đánh giá nhà cung cấp",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "NGÀY 2 – TRIỂN KHAI ĐÁNH GIÁ VÀ CẢI TIẾN NHÀ CUNG CẤP",
                                                "lessons": []
                                    },
                                    {
                                                "title": "PHẦN 5 – ĐÁNH GIÁ HIỆN TRƯỜNG NHÀ CUNG CẤP",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá hiện trường sản xuất",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá kiểm soát chất lượng",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá thiết bị và bảo trì",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá nhân sự và đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá quản lý nguyên vật liệu",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá an toàn và môi trường",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá kiểm soát sản phẩm không phù hợp",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 6 – PHÂN TÍCH RỦI RO NHÀ CUNG CẤP",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Risk Matrix",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Quality Risk",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Delivery Risk",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Capacity Risk",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Compliance Risk",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "ESG Risk",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Business Continuity Risk",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Nội dung thực hành",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá rủi ro nhà cung cấp",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thiết lập Supplier Risk Register",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Thảo luận tình huống thực tế",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 7 – CAPA VÀ PHÁT TRIỂN NHÀ CUNG CẤP",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier CAPA Management",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Root Cause Analysis",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Theo dõi hành động cải tiến",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Development Program",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Improvement Plan",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Performance Monitoring",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 8 – XÂY DỰNG HỆ THỐNG QUẢN LÝ NHÀ CUNG CẤP",
                                                "lessons": [
                                                            {
                                                                        "title": "Nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Management Procedure",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier KPI Dashboard",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Audit Checklist",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Scorecard",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Supplier Monitoring System",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tích hợp với ISO 9001, ISO 14001 và ISO 45001",
                                                                        "duration": ""
                                                            }
                                                ]
                                    },
                                    {
                                                "title": "PHẦN 9 – TỔNG KẾT VÀ ĐÁNH GIÁ CUỐI KHÓA",
                                                "lessons": [
                                                            {
                                                                        "title": "Ôn tập nội dung đào tạo",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Kiểm tra cuối khóa",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Đánh giá năng lực học viên",
                                                                        "duration": ""
                                                            },
                                                            {
                                                                        "title": "Tổng kết chương trình đào tạo",
                                                                        "duration": ""
                                                            }
                                                ]
                                    }
                        ],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 31
            },
            {
                        "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
                        "title": "Social Accountability 8000 (SA8000) – Tiêu chuẩn Trách nhiệm Xã hội",
                        "slug": "social-accountability-8000-sa8000-tieu-chuan-trach-nhiem-xa-hoi",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 32
            },
            {
                        "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
                        "title": "Sedex Members Ethical Trade Audit (SMETA)",
                        "slug": "sedex-members-ethical-trade-audit-smeta",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 33
            },
            {
                        "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
                        "title": "Business Social Compliance Initiative (BSCI) – Bộ Tiêu chuẩn Tuân thủ Trách nhiệm Xã hội trong Kinh doanh",
                        "slug": "business-social-compliance-initiative-bsci-bo-tieu-chuan-tuan-thu-trach-nhiem-xa-hoi-trong-kinh-doanh",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 34
            },
            {
                        "category_slug": "cong-trinh-xanh-va-san-pham-ben-vung",
                        "title": "Leadership in Energy and Environmental Design (LEED) – Hệ thống Đánh giá Công trình Xanh",
                        "slug": "leadership-in-energy-and-environmental-design-leed-he-thong-danh-gia-cong-trinh-xanh",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 35
            },
            {
                        "category_slug": "cong-trinh-xanh-va-san-pham-ben-vung",
                        "title": "Environmental Product Declaration (EPD) – Tuyên bố Sản phẩm Môi trường",
                        "slug": "environmental-product-declaration-epd-tuyen-bo-san-pham-moi-truong",
                        "description": "",
                        "content": "",
                        "curriculum": [],
                        "price": "Liên hệ",
                        "is_active": true,
                        "sort_order": 36
            }
]', true);

        foreach ($coursesData as $courseData) {
            $catSlug = $courseData['category_slug'];
            unset($courseData['category_slug']);

            $courseData['course_category_id'] = $categories[$catSlug]->id;

            Course::create($courseData);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Course::truncate();
        CourseCategory::truncate();
        Schema::enableForeignKeyConstraints();
    }
};
