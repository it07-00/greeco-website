<?php

namespace Tests\Feature;

use App\Filament\Resources\Banners\Schemas\BannerForm;
use App\Filament\Resources\Pages\Schemas\PageForm;
use App\Filament\Resources\Partners\Schemas\PartnerForm;
use App\Filament\Resources\Posts\Schemas\PostForm;
use App\Filament\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Services\Schemas\ServiceForm;
use App\Filament\Resources\Settings\Schemas\SettingForm;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\FormsComponent;
use Filament\Schemas\Schema;
use Tests\TestCase;

class ImageUploadOptimizationTest extends TestCase
{
    public function test_all_admin_image_uploads_are_optimized_for_their_usage(): void
    {
        $expectedUploads = [
            BannerForm::class => [
                'image' => ['1920', '910'],
            ],
            PostForm::class => [
                'thumbnail' => ['2560', '1440'],
                'og_image' => ['1600', '900'],
            ],
            PageForm::class => [
                'thumbnail' => ['2560', '1440'],
                'og_image' => ['1600', '900'],
            ],
            ServiceForm::class => [
                'thumbnail' => ['2560', '1440'],
                'og_image' => ['1600', '900'],
            ],
            ProjectForm::class => [
                'thumbnail' => ['2560', '1440'],
                'og_image' => ['1600', '900'],
            ],
            PartnerForm::class => [
                'logo' => ['1600', '1600'],
            ],
            SettingForm::class => [
                'image_value' => ['2560', '1440'],
            ],
        ];

        foreach ($expectedUploads as $schemaClass => $fields) {
            $components = $this->componentsFor($schemaClass);

            foreach ($fields as $name => [$width, $height]) {
                $upload = $components
                    ->first(fn ($component) => $component instanceof FileUpload && $component->getName() === $name);

                $this->assertInstanceOf(FileUpload::class, $upload, "{$schemaClass}:{$name}");
                $this->assertContains($upload->getAutomaticallyResizeImagesMode(), ['contain', 'cover']);
                $this->assertSame($width, $upload->getAutomaticallyResizeImagesWidth());
                $this->assertSame($height, $upload->getAutomaticallyResizeImagesHeight());
                $this->assertFalse($upload->shouldAutomaticallyUpscaleImagesWhenResizing());
                $this->assertSame(51200, $upload->getMaxSize());
            }
        }
    }

    public function test_rich_editor_images_are_limited_and_optimized(): void
    {
        $expectedEditors = [
            PostForm::class => 'content',
            PageForm::class => 'content',
            ServiceForm::class => 'content',
            ProjectForm::class => 'content',
            SettingForm::class => 'editor_value',
        ];

        foreach ($expectedEditors as $schemaClass => $fieldName) {
            $editor = $this->componentsFor($schemaClass)
                ->first(fn ($component) => $component instanceof RichEditor && $component->getName() === $fieldName);

            $this->assertInstanceOf(RichEditor::class, $editor, $schemaClass);
            $this->assertSame(51200, $editor->getFileAttachmentsMaxSize());
            $this->assertSame(
                ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                $editor->getFileAttachmentsAcceptedFileTypes(),
            );
        }
    }

    private function componentsFor(string $schemaClass)
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
            $schemaClass::configure(Schema::make($livewire))
                ->statePath('data')
                ->getFlatComponents(withHidden: true),
        );
    }
}
