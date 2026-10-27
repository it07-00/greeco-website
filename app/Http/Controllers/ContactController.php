<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:191'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:191'],
            'message' => ['nullable', 'string'],
        ], [
            'name.required' => __('Vui lòng nhập họ và tên.'),
            'name.string' => __('Họ và tên phải là chuỗi ký tự.'),
            'name.max' => __('Họ và tên không được vượt quá 191 ký tự.'),
            'email.email' => __('Địa chỉ email không hợp lệ.'),
            'email.max' => __('Địa chỉ email không được vượt quá 191 ký tự.'),
            'phone.max' => __('Số điện thoại không được vượt quá 50 ký tự.'),
        ]);

        Contact::create($data);

        if ($request->input('_return') === 'training') {
            return back()->with('success', __('Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất.'));
        }

        return back()->with('success', __('Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất.'));
    }
}
