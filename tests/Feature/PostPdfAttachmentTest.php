<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostCategory;
use App\Filament\Resources\Posts\Schemas\PostForm;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\FormsComponent;
use Filament\Schemas\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostPdfAttachmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_model_has_pdf_attributes(): void
    {
        $category = PostCategory::query()->create([
            'name' => 'Tin tức',
            'slug' => 'tin-tuc',
            'is_active' => true,
        ]);

        $post = Post::query()->create([
            'post_category_id' => $category->id,
            'title' => 'Bài viết PDF Test',
            'slug' => 'bai-viet-pdf-test',
            'content_type' => 'pdf',
            'pdf_path' => 'posts/pdfs/test.pdf',
            'pdf_layout' => 'single',
            'pdf_viewer_type' => 'reader',
            'is_published' => true,
        ]);

        $this->assertSame('pdf', $post->content_type);
        $this->assertSame('posts/pdfs/test.pdf', $post->pdf_path);
        $this->assertSame('single', $post->pdf_layout);
        $this->assertSame('reader', $post->pdf_viewer_type);
    }

    public function test_post_form_contains_content_type_and_pdf_path_fields(): void
    {
        $livewire = new class extends FormsComponent
        {
            public array $data = [];

            public function render(): string
            {
                return '';
            }
        };

        $components = collect(
            PostForm::configure(Schema::make($livewire))
                ->statePath('data')
                ->getFlatComponents(withHidden: true)
        );

        $contentTypeField = $components->first(fn ($c) => $c instanceof Select && $c->getName() === 'content_type');
        $this->assertInstanceOf(Select::class, $contentTypeField);
        $this->assertArrayHasKey('pdf', $contentTypeField->getOptions());

        $pdfPathField = $components->first(fn ($c) => $c instanceof FileUpload && $c->getName() === 'pdf_path');
        $this->assertInstanceOf(FileUpload::class, $pdfPathField);
    }

    public function test_post_detail_page_renders_pdf_iframe_and_mobile_card(): void
    {
        $category = PostCategory::query()->create([
            'name' => 'Tin tức',
            'slug' => 'tin-tuc',
            'is_active' => true,
        ]);

        $post = Post::query()->create([
            'post_category_id' => $category->id,
            'title' => 'Tài liệu hướng dẫn kiểm kê khí nhà kính',
            'slug' => 'huong-dan-kiem-ke-khi-nha-kinh',
            'excerpt' => 'Đây là bài viết dạng tài liệu PDF.',
            'content_type' => 'pdf',
            'pdf_path' => 'posts/pdfs/test-guide.pdf',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get(route('posts.show', $post));

        $response->assertStatus(200);
        $response->assertSee('<iframe src=', false);
        $response->assertSee('test-guide.pdf', false);
        $response->assertSee('Tải bản PDF', false);
        $response->assertSee('Xem trực tuyến (Toàn màn hình)', false);
    }

    public function test_post_detail_page_renders_standard_content_for_text_posts(): void
    {
        $category = PostCategory::query()->create([
            'name' => 'Tin tức',
            'slug' => 'tin-tuc',
            'is_active' => true,
        ]);

        $post = Post::query()->create([
            'post_category_id' => $category->id,
            'title' => 'Bài viết văn bản thông thường',
            'slug' => 'bai-viet-van-ban',
            'content' => '<p>Đây là nội dung văn bản html.</p>',
            'content_type' => 'text',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get(route('posts.show', $post));

        $response->assertStatus(200);
        $response->assertDontSee('_df_book', false);
        $response->assertSee('<p>Đây là nội dung văn bản html.</p>', false);
        $response->assertDontSee('Tải bản PDF', false);
    }
}
