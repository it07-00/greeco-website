<?php

namespace App\Console\Commands\EduQuiz;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreateInstructor extends Command
{
    protected $signature = 'eduquiz:instructor {email} {--name=}';

    protected $description = 'Tạo tài khoản giảng viên chỉ có quyền truy cập EduQuiz';

    public function handle(): int
    {
        $email = mb_strtolower(trim($this->argument('email')));
        $name = $this->option('name') ?: $this->ask('Họ và tên');
        $password = $this->secret('Mật khẩu (ít nhất 12 ký tự)');
        $validation = Validator::make(compact('email', 'name', 'password'), [
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:12', 'max:72'],
        ]);
        if ($validation->fails()) {
            $this->error($validation->errors()->first());

            return self::FAILURE;
        }

        DB::transaction(function () use ($email, $name, $password) {
            $user = User::create(compact('email', 'name', 'password'));
            DB::table('eduquiz_instructors')->insert(['user_id' => $user->id]);
        });
        $this->info('Đã tạo tài khoản giảng viên. Đăng nhập tại /giang-vien/login.');

        return self::SUCCESS;
    }
}
