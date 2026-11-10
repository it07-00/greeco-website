<?php

namespace App\Services\EduQuiz;

use App\Models\EduQuiz\Attempt;
use App\Models\EduQuiz\Room;
use Illuminate\Support\Facades\DB;

class Privacy
{
    public function days(): int
    {
        return (int) (DB::table('eduquiz_settings')->where('id', 1)->value('retention_days') ?? 365);
    }

    public function anonymize(Room $room): void
    {
        DB::transaction(function () use ($room) {
            $room = Room::lockForUpdate()->findOrFail($room->id);
            abort_unless($room->finished_at, 409, 'Hãy kết thúc phiên thi trước khi ẩn danh.');
            if ($room->anonymized_at) {
                return;
            }

            Attempt::whereIn('participant_id', $room->participants()->select('id'))->update(['token_hash' => null]);
            $room->participants()->update(['name' => 'Học viên đã ẩn danh', 'email' => null, 'phone' => null]);
            DB::table('eduquiz_invitations')->where('session_id', $room->id)->delete();
            $room->update(['anonymized_at' => now()]);
        }, 3);
    }

    public function anonymizeDue(): void
    {
        Room::whereNull('anonymized_at')->where('finished_at', '<=', now()->subDays($this->days()))
            ->eachById(fn (Room $room) => $this->anonymize($room));
    }
}
