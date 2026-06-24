<?php

namespace App\Support;

use App\Models\Setting;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;

class OptimizedImageUpload
{
    private const MAX_UPLOAD_SIZE_KB = 51200;

    private const OPTIMIZABLE_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public static function configure(
        FileUpload $upload,
        int $maxWidth,
        int $maxHeight,
        int $quality = 82,
        ?string $helperText = null,
    ): FileUpload {
        $upload
            ->image()
            ->imageEditor()
            ->maxSize(self::MAX_UPLOAD_SIZE_KB)
            ->automaticallyResizeImagesMode('contain')
            ->automaticallyResizeImagesToWidth((string) $maxWidth)
            ->automaticallyResizeImagesToHeight((string) $maxHeight)
            ->automaticallyUpscaleImagesWhenResizing(false)
            ->saveUploadedFileUsing(
                fn (BaseFileUpload $component, TemporaryUploadedFile $file): ?string => self::store(
                    $component,
                    $file,
                    $maxWidth,
                    $maxHeight,
                    $quality,
                ),
            );

        if ($helperText) {
            $upload->helperText($helperText);
        }

        return $upload;
    }

    public static function configureRichEditor(
        RichEditor $editor,
        int $maxWidth = 1920,
        int $maxHeight = 1920,
        int $quality = 82,
    ): RichEditor {
        return $editor
            ->fileAttachmentsAcceptedFileTypes([
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
            ])
            ->fileAttachmentsMaxSize(self::MAX_UPLOAD_SIZE_KB)
            ->saveUploadedFileAttachmentUsing(
                fn (RichEditor $component, TemporaryUploadedFile $file): ?string => self::storeRichEditorAttachment(
                    $component,
                    $file,
                    $maxWidth,
                    $maxHeight,
                    $quality,
                ),
            );
    }

    public static function store(
        BaseFileUpload $component,
        TemporaryUploadedFile $file,
        int $maxWidth = 3840,
        int $maxHeight = 2160,
        int $quality = 84,
    ): ?string {
        // Skip WebP optimization for website favicon setting so it preserves its format (like PNG/ICO)
        $record = $component->getRecord();
        $key = null;
        if ($record instanceof Setting) {
            $key = $record->key;
        } else {
            try {
                $state = $component->getContainer()->getRawState();
                $key = $state['key'] ?? null;
            } catch (Throwable $e) {
                // Ignore
            }
        }

        if ($key === 'favicon') {
            return $component->saveUploadedFile($file);
        }

        if (! self::shouldOptimize($file)) {
            return $component->saveUploadedFile($file);
        }

        $temporaryBase = tempnam(sys_get_temp_dir(), 'greeco-image-');

        if ($temporaryBase === false) {
            return $component->saveUploadedFile($file);
        }

        $temporaryWebp = $temporaryBase.'.webp';
        @unlink($temporaryBase);

        try {
            if (! self::optimize($file->getRealPath(), $temporaryWebp, $maxWidth, $maxHeight, $quality)) {
                return $component->saveUploadedFile($file);
            }

            $directory = trim((string) $component->getDirectory(), '/');
            $path = trim($directory.'/'.Str::ulid().'.webp', '/');
            $stream = fopen($temporaryWebp, 'rb');

            if ($stream === false) {
                return $component->saveUploadedFile($file);
            }

            try {
                if (! $component->getDisk()->writeStream($path, $stream)) {
                    return $component->saveUploadedFile($file);
                }
            } finally {
                fclose($stream);
            }

            if ($component->getVisibility() === 'public') {
                rescue(
                    fn () => $component->getDisk()->setVisibility($path, 'public'),
                    report: false,
                );
            }

            return $path;
        } catch (Throwable $exception) {
            report($exception);

            return $component->saveUploadedFile($file);
        } finally {
            @unlink($temporaryWebp);
        }
    }

    public static function storeRichEditorAttachment(
        RichEditor $component,
        TemporaryUploadedFile $file,
        int $maxWidth = 1920,
        int $maxHeight = 1920,
        int $quality = 82,
    ): ?string {
        if (! self::shouldOptimize($file)) {
            return self::storeOriginalRichEditorAttachment($component, $file);
        }

        $temporaryBase = tempnam(sys_get_temp_dir(), 'greeco-editor-image-');

        if ($temporaryBase === false) {
            return self::storeOriginalRichEditorAttachment($component, $file);
        }

        $temporaryWebp = $temporaryBase.'.webp';
        @unlink($temporaryBase);

        try {
            if (! self::optimize($file->getRealPath(), $temporaryWebp, $maxWidth, $maxHeight, $quality)) {
                return self::storeOriginalRichEditorAttachment($component, $file);
            }

            $directory = trim((string) $component->getFileAttachmentsDirectory(), '/');
            $path = trim($directory.'/'.Str::ulid().'.webp', '/');
            $stream = fopen($temporaryWebp, 'rb');

            if ($stream === false) {
                return self::storeOriginalRichEditorAttachment($component, $file);
            }

            try {
                if (! $component->getFileAttachmentsDisk()->writeStream($path, $stream)) {
                    return self::storeOriginalRichEditorAttachment($component, $file);
                }
            } finally {
                fclose($stream);
            }

            if ($component->getFileAttachmentsVisibility() === 'public') {
                rescue(
                    fn () => $component->getFileAttachmentsDisk()->setVisibility($path, 'public'),
                    report: false,
                );
            }

            return $path;
        } catch (Throwable $exception) {
            report($exception);

            return self::storeOriginalRichEditorAttachment($component, $file);
        } finally {
            @unlink($temporaryWebp);
        }
    }

    public static function optimize(
        string $sourcePath,
        string $destinationPath,
        int $maxWidth = 3840,
        int $maxHeight = 2160,
        int $quality = 84,
    ): bool {
        if (! function_exists('imagewebp')) {
            return false;
        }

        $imageData = @file_get_contents($sourcePath);
        $source = $imageData === false ? false : @imagecreatefromstring($imageData);

        if ($source === false) {
            return false;
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $scale = min(1, $maxWidth / $sourceWidth, $maxHeight / $sourceHeight);
        $targetWidth = max(1, (int) round($sourceWidth * $scale));
        $targetHeight = max(1, (int) round($sourceHeight * $scale));
        $target = imagecreatetruecolor($targetWidth, $targetHeight);

        if ($target === false) {
            imagedestroy($source);

            return false;
        }

        imagealphablending($target, false);
        imagesavealpha($target, true);
        $transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
        imagefilledrectangle($target, 0, 0, $targetWidth, $targetHeight, $transparent);

        $resampled = imagecopyresampled(
            $target,
            $source,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $sourceWidth,
            $sourceHeight,
        );

        $saved = $resampled && imagewebp($target, $destinationPath, max(0, min(100, $quality)));

        imagedestroy($source);
        imagedestroy($target);

        return $saved;
    }

    private static function shouldOptimize(TemporaryUploadedFile $file): bool
    {
        return in_array($file->getMimeType(), self::OPTIMIZABLE_MIME_TYPES, true);
    }

    private static function storeOriginalRichEditorAttachment(
        RichEditor $component,
        TemporaryUploadedFile $file,
    ): ?string {
        $path = $file->store(
            $component->getFileAttachmentsDirectory(),
            $component->getFileAttachmentsDiskName(),
        );

        if ($component->getFileAttachmentsVisibility() === 'public') {
            rescue(
                fn () => $component->getFileAttachmentsDisk()->setVisibility($path, 'public'),
                report: false,
            );
        }

        return $path;
    }
}
