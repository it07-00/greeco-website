<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRegistrationRequest;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Services\CourseRegistrationQrCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class CourseRegistrationController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->filled('course')) {
            $course = Course::query()
                ->where('slug', $request->string('course')->toString())
                ->where('is_active', true)
                ->firstOrFail();

            return redirect()->route('course-registration.create', [
                'course' => $course,
                'source' => 'qr',
            ]);
        }

        $courses = Course::query()
            ->with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('course-registrations.index', compact('courses'));
    }

    public function create(Course $course): View
    {
        abort_unless($course->is_active, 404);

        return view('course-registrations.create', compact('course'));
    }

    public function store(
        StoreCourseRegistrationRequest $request,
        Course $course
    ): RedirectResponse {
        abort_unless($course->is_active, 404);

        $validated = $request->validated();
        $attendees = $validated['attendees'];
        $primaryAttendee = $attendees[0];

        $registration = DB::transaction(function () use (
            $course,
            $validated,
            $attendees,
            $primaryAttendee
        ): CourseRegistration {
            $registration = CourseRegistration::create([
                'course_id' => $course->id,
                'code' => $this->generateCode(),
                'company_name' => $validated['company_name'],
                'company_address' => $validated['company_address'],
                'tax_code' => $validated['tax_code'],
                'contact_name' => $primaryAttendee['full_name'],
                'contact_email' => $primaryAttendee['email'],
                'contact_phone' => $primaryAttendee['phone'],
                'attendee_count' => count($attendees),
                'invoice_email' => $validated['invoice_email'],
                'consultation_question' => $validated['consultation_question'] ?? null,
                'source' => $validated['source'],
                'status' => 'new',
                'consent_at' => now(),
            ]);

            $registration->attendees()->createMany($attendees);

            return $registration;
        });

        return redirect()->route('course-registration.success', $registration);
    }

    public function success(CourseRegistration $registration): View
    {
        $registration->load('course');

        return view('course-registrations.success', compact('registration'));
    }

    public function qr(
        Request $request,
        Course $course,
        string $format,
        CourseRegistrationQrCode $qrCode,
    ): Response {
        abort_unless($course->is_active, 404);
        abort_unless(in_array($format, ['png', 'svg'], true), 404);

        $result = $qrCode->render($course, $format);
        $disposition = $request->boolean('download') ? 'attachment' : 'inline';
        $filename = 'qr-dang-ky-'.Str::slug($course->slug).'.'.$format;

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Content-Disposition' => $disposition.'; filename="'.$filename.'"',
            'Cache-Control' => 'public, max-age=86400',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function selectionQr(
        Request $request,
        string $format,
        CourseRegistrationQrCode $qrCode,
    ): Response {
        abort_unless(in_array($format, ['png', 'svg'], true), 404);

        $result = $qrCode->renderSelection($format);
        $disposition = $request->boolean('download') ? 'attachment' : 'inline';
        $filename = 'qr-chung-dang-ky-khoa-hoc.'.$format;

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Content-Disposition' => $disposition.'; filename="'.$filename.'"',
            'Cache-Control' => 'public, max-age=86400',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function generateCode(): string
    {
        do {
            $code = 'DK-'.now()->format('ymd').'-'.Str::upper(Str::random(6));
        } while (CourseRegistration::query()->where('code', $code)->exists());

        return $code;
    }
}
