<?php

namespace App\Http\Controllers\EduQuiz;

use App\Models\EduQuiz\Question;
use App\Models\EduQuiz\Quiz;
use App\Models\EduQuiz\Room;
use App\Services\EduQuiz\Access;
use App\Services\EduQuiz\Privacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController
{
    public function index(Request $request)
    {
        $user = $request->user();
        $rooms = Access::scope(Room::query(), $user);

        return view('eduquiz.admin.index', [
            'counts' => [
                'Câu hỏi' => Access::scope(Question::query(), $user)->count(),
                'Bộ đề' => Access::scope(Quiz::query(), $user)->count(),
                'Phiên đang mở' => (clone $rooms)->whereNull('finished_at')->count(),
            ],
            'rooms' => $rooms->latest('id')->limit(5)->get(),
        ]);
    }

    public function settings(Request $request, Privacy $privacy)
    {
        abort_if(Access::instructor($request->user()), 403);

        return view('eduquiz.admin.settings', [
            'retentionDays' => $privacy->days(),
            'instructors' => DB::table('eduquiz_instructors')->join('users', 'users.id', '=', 'eduquiz_instructors.user_id')
                ->orderBy('users.name')->get(['users.name', 'users.email']),
        ]);
    }

    public function saveSettings(Request $request)
    {
        abort_if(Access::instructor($request->user()), 403);
        $data = $request->validate(['retention_days' => ['required', 'integer', 'between:1,3650']]);
        DB::table('eduquiz_settings')->updateOrInsert(['id' => 1], $data);

        return back()->with('notice', 'Đã lưu thời hạn lưu giữ.');
    }
}
