<?php

namespace App\Services\EduQuiz;

use Illuminate\Validation\ValidationException;

class Contact
{
    public static function email(?string $value): ?string
    {
        return $value ? mb_strtolower(trim($value)) : null;
    }

    public static function phone(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $number = preg_replace('/[\s().-]+/u', '', $value);
        if (preg_match('/^0([1-9]\d{8,9})$/D', $number, $matches)) {
            $number = '+84'.$matches[1];
        } elseif (str_starts_with($number, '00')) {
            $number = '+'.substr($number, 2);
        } elseif (preg_match('/^84[1-9]\d{8,9}$/D', $number)) {
            $number = '+'.$number;
        }

        if (! preg_match('/^\+[1-9]\d{7,14}$/D', $number)) {
            throw ValidationException::withMessages(['phone' => 'Nhập số điện thoại Việt Nam hoặc số quốc tế kèm mã quốc gia.']);
        }

        return $number;
    }
}
