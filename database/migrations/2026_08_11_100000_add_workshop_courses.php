<?php

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $category = CourseCategory::firstOrCreate(
            ['slug' => 'workshop'],
            [
                'name' => 'Workshop',
                'icon' => 'fa-laptop-code',
                'color' => '#ff9800',
                'bg' => '#fff3e0',
                'sort_order' => 7,
            ]
        );

        $workshops = [
            [
                'title' => 'Workshop: Open-Source AI Agent Lab',
                'slug' => 'workshop-open-source-ai-agent-lab',
                'date' => '28/08/2026',
                'description' => 'Workshop chuyên sâu về xây dựng và triển khai các AI Agent nguồn mở, truy vấn dữ liệu kinh doanh (Pandas, DuckDB) và tích hợp giao diện Gradio.',
                'topics' => [
                    '1. What an AI agent is, and how it differs from a single model call',
                    '2. Reading and querying business data (Pandas, DuckDB)',
                    '3. Tool selection: the agent picks the correct analysis tool for the question',
                    '4. Calculating real results from the data, not canned answers',
                    '5. Turning results into useful business insights',
                    '6. A simple Gradio interface for the finished analyst',
                ],
                'sort_order' => 101,
            ],
            [
                'title' => 'Workshop: Data Engineering Lab',
                'slug' => 'workshop-data-engineering-lab',
                'date' => '04/09/2026',
                'description' => 'Workshop thực hành thiết kế đường ống dữ liệu (Data Pipeline), làm sạch dữ liệu thô (Pandas), kiểm tra quy tắc (Pandera) và truy vấn SQL (DuckDB).',
                'topics' => [
                    '1. Why messy data blocks AI projects',
                    '2. Cleaning raw business data (Pandas)',
                    '3. Validating data against rules (Pandera)',
                    '4. Transforming data into an analysis-ready shape',
                    '5. Storing and querying the result (DuckDB, SQL)',
                    '6. Assembling the steps into one repeatable pipeline',
                ],
                'sort_order' => 102,
            ],
            [
                'title' => 'Workshop: Computer Vision Lab',
                'slug' => 'workshop-computer-vision-lab',
                'date' => '11/09/2026',
                'description' => 'Workshop nghiên cứu và ứng dụng Thị giác máy tính (Computer Vision), huấn luyện mô hình Deep Learning (TensorFlow/PyTorch) để kiểm tra chất lượng hình ảnh sản phẩm.',
                'topics' => [
                    '1. Framing a visual quality-inspection problem',
                    '2. Preparing normal and defective product images',
                    '3. Training a small deep learning model (TensorFlow or PyTorch)',
                    '4. Checking what the model gets right and wrong',
                    '5. Wrapping the model in a Gradio inspection app',
                ],
                'sort_order' => 103,
            ],
            [
                'title' => 'Workshop: Edge AI Lab',
                'slug' => 'workshop-edge-ai-lab',
                'date' => '18/09/2026',
                'description' => 'Workshop tối ưu hóa và chuyển đổi các mô hình AI sang định dạng biên (TFLite, ONNX), nén mô hình và chạy thực tế trên Raspberry Pi / Jetson.',
                'topics' => [
                    '1. Why models must shrink for mobile, Raspberry Pi, and Jetson',
                    '2. Converting a model to edge formats (TensorFlow Lite, ONNX)',
                    '3. Quantising and compressing the model',
                    '4. Running the converted model (ONNX Runtime)',
                    '5. Comparing size and speed before and after',
                ],
                'sort_order' => 104,
            ],
            [
                'title' => 'Workshop: AI Deployment Lab',
                'slug' => 'workshop-ai-deployment-lab',
                'date' => '25/09/2026',
                'description' => 'Workshop thực hành MLOps & AI Deployment, chuẩn hóa cấu trúc dự án từ Colab, kiểm thử (Pytest), kiểm tra đầu vào (Pydantic) và phục vụ qua FastAPI & Gradio.',
                'topics' => [
                    '1. From Colab prototype to structured project layout',
                    '2. Evaluation and logging of an AI application',
                    '3. Input validation (Pydantic) and testing (Pytest)',
                    '4. Serving the application through an API (FastAPI) and interface (Gradio)',
                    '5. Storage with DuckDB and preparing deployment files',
                ],
                'sort_order' => 105,
            ],
            [
                'title' => 'Workshop: Robotics Lab (RoboNav Lite) - series finale',
                'slug' => 'workshop-robotics-lab-series-finale',
                'date' => '02/10/2026',
                'description' => 'Workshop phần kết series, tích hợp AI vật lý (Physical AI), cảm biến chu kỳ Sense-Think-Act, huấn luyện và mô phỏng robot di chuyển tự hành.',
                'topics' => [
                    '1. Introduction to Physical AI and robot navigation',
                    '2. Understanding sensors and the Sense, Think, Act loop',
                    '3. Exploring the robot navigation dataset',
                    '4. Visualizing sensor readings and movement commands',
                    '5. Training the robot navigation models',
                    '6. Evaluating model accuracy and decisions',
                    '7. Testing custom sensor scenarios',
                    '8. Running the autonomous robot simulation',
                    '9. Understanding edge deployment possibilities',
                    '10. Project recap and participant showcase',
                ],
                'sort_order' => 106,
            ],
        ];

        foreach ($workshops as $item) {
            $topicsHtml = implode('', array_map(fn ($t) => "<li>{$t}</li>", $item['topics']));
            $lessons = array_map(fn ($t) => ['title' => $t], $item['topics']);

            $content = '
                <p><strong>Ngày tổ chức dự kiến:</strong> '.$item['date'].'</p>
                <p>'.$item['description'].'</p>
                <h3><strong>Nội dung đào tạo thực hành:</strong></h3>
                <ul>'.$topicsHtml.'</ul>
            ';

            $curriculum = [
                [
                    'title' => 'Chương trình Workshop (Ngày: '.$item['date'].')',
                    'lessons' => $lessons,
                ],
            ];

            Course::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'course_category_id' => $category->id,
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'content' => trim($content),
                    'curriculum' => $curriculum,
                    'meta_title' => Str::limit($item['title'].' | GREECO', 70, ''),
                    'meta_description' => Str::limit($item['description'], 160, ''),
                    'meta_keywords' => 'workshop, AI, lab, GREECO, '.$item['title'],
                    'tag' => 'WORKSHOP',
                    'badge_color' => 'bg-warning',
                    'price' => 'Liên hệ',
                    'is_indexable' => true,
                    'is_active' => true,
                    'sort_order' => $item['sort_order'],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $slugs = [
            'workshop-open-source-ai-agent-lab',
            'workshop-data-engineering-lab',
            'workshop-computer-vision-lab',
            'workshop-edge-ai-lab',
            'workshop-ai-deployment-lab',
            'workshop-robotics-lab-series-finale',
        ];

        Course::query()->whereIn('slug', $slugs)->delete();

        $category = CourseCategory::query()->where('slug', 'workshop')->first();
        if ($category && $category->courses()->count() === 0) {
            $category->delete();
        }
    }
};
