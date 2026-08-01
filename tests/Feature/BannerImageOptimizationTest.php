<?php

namespace Tests\Feature;

use App\Filament\Resources\Banners\Schemas\BannerForm;
use App\Support\OptimizedImageUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\FormsComponent;
use Filament\Schemas\Schema;
use Tests\TestCase;

class BannerImageOptimizationTest extends TestCase
{
    public function test_banner_upload_resizes_images_before_uploading(): void
    {
        $livewire = new class extends FormsComponent
        {
            public array $data = [];

            public function render(): string
            {
                return '';
            }
        };

        $schema = BannerForm::configure(Schema::make($livewire))
            ->statePath('data');

        $upload = collect($schema->getFlatComponents(withHidden: true))
            ->first(fn ($component) => $component instanceof FileUpload && $component->getName() === 'image');

        $this->assertInstanceOf(FileUpload::class, $upload);
        $this->assertSame('cover', $upload->getAutomaticallyResizeImagesMode());
        $this->assertSame('1920', $upload->getAutomaticallyResizeImagesWidth());
        $this->assertSame('910', $upload->getAutomaticallyResizeImagesHeight());
        $this->assertFalse($upload->shouldAutomaticallyUpscaleImagesWhenResizing());
    }

    public function test_banner_optimizer_outputs_a_smaller_webp_image(): void
    {
        $source = tempnam(sys_get_temp_dir(), 'banner-source-');
        $destination = tempnam(sys_get_temp_dir(), 'banner-output-');
        $image = imagecreatetruecolor(800, 450);
        $seed = 123456789;

        for ($y = 0; $y < 450; $y++) {
            for ($x = 0; $x < 800; $x++) {
                $seed = (1664525 * $seed + 1013904223) & 0xFFFFFFFF;
                imagesetpixel($image, $x, $y, $seed & 0xFFFFFF);
            }
        }

        imagepng($image, $source);
        imagedestroy($image);

        try {
            $this->assertTrue(OptimizedImageUpload::optimize($source, $destination, 400, 225, 84));
            $this->assertSame('image/webp', mime_content_type($destination));
            $this->assertLessThan(filesize($source), filesize($destination));
            $this->assertSame([400, 225], array_slice(getimagesize($destination), 0, 2));
        } finally {
            @unlink($source);
            @unlink($destination);
        }
    }
}
