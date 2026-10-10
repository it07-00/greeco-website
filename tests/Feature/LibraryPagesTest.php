<?php

namespace Tests\Feature;

use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LibraryPagesTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('documentLinks')]
    public function test_library_handles_document_links(?string $link, ?string $expectedUrl): void
    {
        Document::create([
            'number' => 'TEST-001',
            'type' => 'Luật Quốc hội',
            'title' => 'Văn bản kiểm tra liên kết',
            'link' => $link,
            'is_active' => true,
        ]);

        $response = $this->get(route('library'))
            ->assertOk()
            ->assertSee('Văn bản kiểm tra liên kết');

        if ($expectedUrl === null) {
            $response->assertSee('Chưa có liên kết')
                ->assertDontSee('Xem chi tiết')
                ->assertDontSee('Liên hệ Tư vấn');
        } else {
            if (! str_starts_with($expectedUrl, 'http') && ! str_starts_with($expectedUrl, '/')) {
                $expectedUrl = url($expectedUrl);
            }

            $response->assertSee('href="'.$expectedUrl.'" target="_blank"', false);
        }
    }

    public static function documentLinks(): array
    {
        return [
            'null' => [null, null],
            'empty' => ['', null],
            'whitespace' => ['   ', null],
            'external' => ['https://example.com/document.pdf', 'https://example.com/document.pdf'],
            'absolute path' => ['/lien-he', '/lien-he'],
            'relative path' => ['documents/law.pdf', 'documents/law.pdf'],
            'padded URL' => [' https://example.com/document.pdf ', 'https://example.com/document.pdf'],
        ];
    }

    public function test_library_only_displays_legal_documents_content(): void
    {
        $this->get(route('library'))
            ->assertOk()
            ->assertSee('Văn bản pháp luật')
            ->assertDontSee('Capability Profile')
            ->assertDontSee('Xem trực tuyến');
    }

    public function test_capability_profile_has_its_own_page_and_header_link(): void
    {
        $this->get(route('capability-profile'))
            ->assertOk()
            ->assertSee('Hồ sơ năng lực')
            ->assertSee('Capability Profile')
            ->assertSee('Xem trực tuyến')
            ->assertSee('href="'.route('capability-profile').'"', false);
    }
}
