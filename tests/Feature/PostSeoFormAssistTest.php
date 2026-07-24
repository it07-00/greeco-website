<?php

namespace Tests\Feature;

use App\Filament\Resources\Posts\Schemas\PostForm;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\ToolbarButtonGroup;
use Filament\Forms\Components\TextInput;
use Filament\Forms\FormsComponent;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Schema;
use Tests\TestCase;

class PostSeoFormAssistTest extends TestCase
{
    public function test_post_form_has_seo_assist_actions_and_guidance(): void
    {
        $components = $this->postFormComponents();

        $this->assertTrue($components->contains(function ($component): bool {
            if (! $component instanceof Actions) {
                return false;
            }

            return collect($component->getChildSchema()->getComponents())
                ->contains(fn ($action): bool => method_exists($action, 'getName') && $action->getName() === 'generatePostSeo');
        }));

        $metaTitle = $components
            ->first(fn ($component) => $component instanceof TextInput && $component->getName() === 'meta_title');

        $this->assertInstanceOf(TextInput::class, $metaTitle);
        $this->assertStringContainsString('Khuyến nghị 45-60 ký tự', PostForm::seoLengthHint('Tiêu đề SEO mẫu', 45, 60));
    }

    public function test_post_seo_suggestions_are_generated_from_post_fields(): void
    {
        $title = 'Định hướng thúc đẩy Kinh tế tuần hoàn tại Việt Nam';
        $metaTitle = PostForm::suggestMetaTitle($title);

        $this->assertStringEndsWith(' | GREECO', $metaTitle);
        $this->assertLessThanOrEqual(60, mb_strlen($metaTitle));

        $description = PostForm::suggestMetaDescription(
            "Cập nhật xu hướng thúc đẩy mô hình kinh tế tuần hoàn tại Việt Nam.\nTìm hiểu vai trò của GREECO.",
        );

        $this->assertStringNotContainsString("\n", $description);
        $this->assertLessThanOrEqual(155, mb_strlen($description));

        $this->assertSame('ESG, Net Zero', PostForm::suggestMetaKeywords(['ESG', 'Net Zero', 'ESG']));
        $this->assertSame(route('slug.show', ['slug' => 'kinh-te-tuan-hoan']), PostForm::suggestCanonicalUrl('kinh-te-tuan-hoan'));
    }

    public function test_post_content_editor_has_complete_article_toolbar_without_h1(): void
    {
        $editor = $this->postFormComponents()
            ->first(fn ($component) => $component instanceof RichEditor && $component->getName() === 'content');

        $this->assertInstanceOf(RichEditor::class, $editor);

        $toolbarButtons = collect($editor->getToolbarButtons())
            ->flatten()
            ->flatMap(fn ($button): array => $button instanceof ToolbarButtonGroup ? $button->getButtons() : [$button])
            ->all();

        foreach (['paragraph', 'lead', 'h2', 'h3', 'h4', 'h5', 'h6', 'small', 'alignJustify', 'blockquote', 'code', 'codeBlock', 'horizontalRule', 'clearFormatting'] as $button) {
            $this->assertContains($button, $toolbarButtons);
        }

        $this->assertNotContains('h1', $toolbarButtons);

        $floatingToolbars = $editor->getFloatingToolbars();

        $this->assertContains('h4', $floatingToolbars['heading']);
        $this->assertContains('tableMergeCells', $floatingToolbars['table']);
        $this->assertContains('tableDelete', $floatingToolbars['table']);
    }

    private function postFormComponents()
    {
        $livewire = new class extends FormsComponent
        {
            public array $data = [];

            public function render(): string
            {
                return '';
            }
        };

        return collect(
            PostForm::configure(Schema::make($livewire))
                ->statePath('data')
                ->getFlatComponents(withHidden: true),
        );
    }
}
