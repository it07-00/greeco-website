<?php

namespace App\Services;

use App\Models\Course;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Endroid\QrCode\Writer\SvgWriter;

class CourseRegistrationQrCode
{
    public function selectionUrl(): string
    {
        return rtrim((string) config('app.url'), '/').'/dang-ky-khoa-hoc/';
    }

    public function registrationUrl(Course $course): string
    {
        return route('course-registration.create', [
            'course' => $course,
            'source' => 'qr',
        ]);
    }

    public function render(Course $course, string $format = 'png'): ResultInterface
    {
        return $this->renderUrl($this->registrationUrl($course), $format);
    }

    public function renderSelection(string $format = 'png'): ResultInterface
    {
        return $this->renderUrl($this->selectionUrl(), $format);
    }

    private function renderUrl(string $url, string $format): ResultInterface
    {
        $qrCode = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 360,
            margin: 18,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $writer = $format === 'svg' ? new SvgWriter : new PngWriter;

        return $writer->write($qrCode);
    }
}
