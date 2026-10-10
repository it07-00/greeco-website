<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCourseRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        abort_unless($this->route('course')?->is_active, 404);

        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_array($this->input('attendees'))) {
            $this->merge(['attendees' => array_values($this->input('attendees'))]);
        }

        $this->merge([
            'source' => $this->input('source') === 'qr' ? 'qr' : 'website',
        ]);
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:191'],
            'company_address' => ['required', 'string', 'max:1000'],
            'tax_code' => ['required', 'string', 'max:50'],
            'attendee_count' => ['required', 'integer', 'min:1', 'max:20'],
            'attendees' => ['required', 'array', 'min:1', 'max:20'],
            'attendees.*' => ['required', 'array'],
            'attendees.*.full_name' => ['required', 'string', 'max:191'],
            'attendees.*.job_title' => ['nullable', 'string', 'max:191'],
            'attendees.*.email' => ['required', 'email:rfc', 'max:191'],
            'attendees.*.phone' => ['required', 'string', 'max:50'],
            'invoice_email' => ['required', 'email:rfc', 'max:191'],
            'consultation_question' => ['nullable', 'string', 'max:5000'],
            'source' => ['required', 'in:website,qr'],
            'consent' => ['accepted'],
            'website' => ['prohibited'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(
            function (Validator $validator): void {
                if (
                    ! $validator->errors()->has('attendees')
                    && count($this->input('attendees', [])) !== (int) $this->input('attendee_count')
                ) {
                    $validator->errors()->add(
                        'attendees',
                        'Số người tham dự không khớp với danh sách học viên.'
                    );
                }
            }
        );
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Vui lòng nhập tên công ty.',
            'company_address.required' => 'Vui lòng nhập địa chỉ công ty.',
            'tax_code.required' => 'Vui lòng nhập mã số thuế.',
            'attendee_count.required' => 'Vui lòng chọn số người tham dự.',
            'attendee_count.max' => 'Mỗi lần chỉ có thể đăng ký tối đa 20 người.',
            'attendees.required' => 'Vui lòng nhập thông tin người tham dự.',
            'attendees.*.full_name.required' => 'Vui lòng nhập họ tên người tham dự.',
            'attendees.*.email.required' => 'Vui lòng nhập email người tham dự.',
            'attendees.*.email.email' => 'Email người tham dự không hợp lệ.',
            'attendees.*.phone.required' => 'Vui lòng nhập số điện thoại người tham dự.',
            'invoice_email.required' => 'Vui lòng nhập email nhận hóa đơn.',
            'invoice_email.email' => 'Email nhận hóa đơn không hợp lệ.',
            'consent.accepted' => 'Bạn cần đồng ý cung cấp thông tin để hoàn tất đăng ký.',
            'website.prohibited' => 'Không thể gửi biểu mẫu này.',
        ];
    }
}
