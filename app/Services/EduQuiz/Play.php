<?php

namespace App\Services\EduQuiz;

use App\Models\EduQuiz\Attempt;
use App\Models\EduQuiz\Room;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Play
{
    public function attempt(Room $room, ?string $token): ?Attempt
    {
        if (! $token || strlen($token) !== 64) {
            return null;
        }

        return Attempt::where('token_hash', hash('sha256', $token))
            ->whereHas('participant', fn (Builder $query) => $query->where('session_id', $room->id))
            ->first();
    }

    public function join(Room $room, array $data, ?string $token): string
    {
        return DB::transaction(function () use ($room, $data, $token) {
            $room = Room::lockForUpdate()->findOrFail($room->id);
            if ($this->attempt($room, $token)) {
                return $token;
            }

            abort_if($room->finished_at, 409, 'Phiên thi đã kết thúc.');
            $email = Contact::email($data['email'] ?? null);
            $phone = Contact::phone($data['phone'] ?? null);

            if ($room->participants()->where(function (Builder $query) use ($email, $phone) {
                if ($email) {
                    $query->orWhere('email', $email);
                }
                if ($phone) {
                    $query->orWhere('phone', $phone);
                }
            })->exists()) {
                throw ValidationException::withMessages(['email' => 'Thông tin liên hệ đã được đăng ký. Hãy mở lại trình duyệt đã dùng để vào phòng hoặc liên hệ giảng viên.']);
            }

            if ($room->requires_invite) {
                $invitation = DB::table('eduquiz_invitations')->where('session_id', $room->id)
                    ->where('token_hash', hash('sha256', $data['invite'] ?? ''))
                    ->where('contact', $email ?? $phone)
                    ->whereNull('used_at')->first();

                if (! $invitation || ($email && $phone)) {
                    throw ValidationException::withMessages(['invite' => 'Mã mời cần khớp với đúng một email hoặc số điện thoại được mời.']);
                }

                DB::table('eduquiz_invitations')->where('id', $invitation->id)->update(['used_at' => now()]);
            }

            $participant = $room->participants()->create(['name' => $data['name'], 'email' => $email, 'phone' => $phone]);

            return $this->startAttempt($room, $participant->id, 1);
        }, 3);
    }

    public function retry(Room $room, string $token): string
    {
        return DB::transaction(function () use ($room, $token) {
            $room = Room::lockForUpdate()->findOrFail($room->id);
            $attempt = $this->attempt($room, $token);
            abort_unless($attempt, 403);
            $this->expire($attempt, $room);

            abort_unless($room->mode === 'self' && ! $room->finished_at && $attempt->finished_at
                && $attempt->attempt < $room->attempt_limit, 409, 'Không còn lượt làm bài.');

            $attempt->update(['token_hash' => null]);

            return $this->startAttempt($room, $attempt->participant_id, $attempt->attempt + 1);
        }, 3);
    }

    private function startAttempt(Room $room, int $participant, int $number): string
    {
        $token = Str::random(64);
        $now = now()->toImmutable();
        Attempt::create([
            'participant_id' => $participant,
            'attempt' => $number,
            'token_hash' => hash('sha256', $token),
            'started_at' => $now,
            'deadline' => $room->mode === 'self' ? $now->addSeconds($room->duration_seconds) : null,
        ]);

        return $token;
    }

    public function answer(Room $room, string $token, int $position, array $choices): void
    {
        DB::transaction(function () use ($room, $token, $position, $choices) {
            $room = Room::lockForUpdate()->findOrFail($room->id);
            $attempt = $this->attempt($room, $token);
            abort_unless($attempt, 403);
            $now = now();
            abort_if($room->finished_at || $attempt->finished_at || ($attempt->deadline && $attempt->deadline <= $now), 409, 'Đã hết thời gian trả lời.');

            if ($room->mode === 'live') {
                abort_unless($room->status === 'open' && $room->deadline > $now && $room->current === $position, 409, 'Câu hỏi đã đóng hoặc chưa mở.');
                $previous = $attempt->answers()->where('position', $position)->first();
                if ($previous && $previous->choices === $choices) {
                    return;
                }

                abort_if($previous, 409, 'Bạn đã gửi đáp án cho câu này.');
            }

            $question = $room->questions[$position] ?? null;
            abort_unless($question, 422, 'Câu hỏi không hợp lệ.');
            $correct = array_keys(array_filter($question['options'], fn ($option) => $option['correct']));
            abort_if(
                array_diff($choices, array_keys($question['options'])) || (count($correct) === 1 && count($choices) > 1),
                422,
                'Lựa chọn không hợp lệ.',
            );

            $elapsed = $room->mode === 'live'
                ? $question['seconds'] - $now->diffInSeconds($room->deadline)
                : $attempt->started_at->diffInSeconds($now);
            $attempt->answers()->updateOrCreate(['position' => $position], [
                'choices' => $choices,
                'correct' => $choices === $correct,
                'elapsed_seconds' => max(0, (int) $elapsed),
                'answered_at' => $now,
            ]);
        }, 3);
    }

    public function submit(Room $room, string $token): void
    {
        DB::transaction(function () use ($room, $token) {
            $room = Room::lockForUpdate()->findOrFail($room->id);
            $attempt = $this->attempt($room, $token);
            abort_unless($attempt, 403);
            abort_unless($room->mode === 'self', 409);
            $this->finishAttempt($attempt, $room, now());
        }, 3);
    }

    public function expire(Attempt $attempt, Room $room): void
    {
        if ($attempt->deadline && $attempt->deadline <= now()) {
            $this->finishAttempt($attempt, $room, $attempt->deadline);
        }
    }

    private function finishAttempt(Attempt $attempt, Room $room, CarbonInterface $when): void
    {
        if ($attempt->finished_at) {
            return;
        }

        if ($attempt->deadline && $attempt->deadline < $when) {
            $when = $attempt->deadline;
        }

        $attempt->result()->create([
            'score' => $attempt->answers()->where('correct', true)->count(),
            'total' => count($room->questions),
            'elapsed_seconds' => max(0, (int) $attempt->started_at->diffInSeconds($when)),
            'created_at' => $when,
        ]);
        $attempt->update(['finished_at' => $when]);
    }

    public function control(Room $room, string $action, int $version): void
    {
        DB::transaction(function () use ($room, $action, $version) {
            $room = Room::lockForUpdate()->findOrFail($room->id);
            abort_if($room->version !== $version || $room->finished_at, 409, 'Trạng thái đã thay đổi. Vui lòng thử lại.');

            if ($action === 'finish') {
                $this->finishRoom($room);

                return;
            }

            abort_unless($room->mode === 'live', 409);
            $phase = $room->phase();
            $question = $room->current;
            $seconds = $room->questions[$question]['seconds'];

            $attributes = match (true) {
                $action === 'start' && $phase === 'lobby' => ['status' => 'open', 'deadline' => now()->addSeconds($seconds)],
                $action === 'pause' && $phase === 'open' => [
                    'status' => 'paused', 'paused_seconds' => max(1, (int) ceil(now()->diffInSeconds($room->deadline))), 'deadline' => null,
                ],
                $action === 'resume' && $phase === 'paused' => ['status' => 'open', 'deadline' => now()->addSeconds($room->paused_seconds)],
                $action === 'close' && in_array($phase, ['open', 'paused'], true) => ['status' => 'closed', 'deadline' => null],
                $action === 'reveal' && $phase === 'closed' => ['status' => 'revealed', 'deadline' => null],
                $action === 'next' && in_array($phase, ['closed', 'revealed'], true) && isset($room->questions[$question + 1]) => [
                    'status' => 'open', 'current' => $question + 1,
                    'deadline' => now()->addSeconds($room->questions[$question + 1]['seconds']),
                ],
                default => null,
            };

            abort_unless($attributes, 409, 'Thao tác chưa phù hợp với trạng thái phiên.');
            $room->update([...$attributes, 'version' => $version + 1]);
        }, 3);
    }

    public function deleteRoom(Room $room): bool
    {
        return DB::transaction(function () use ($room) {
            $room = Room::lockForUpdate()->find($room->id);
            if (! $room) {
                return false;
            }

            if (! $room->finished_at) {
                $this->finishRoom($room);
            }

            return $room->delete();
        }, 3);
    }

    private function finishRoom(Room $room): void
    {
        $now = now();
        $room->update(['status' => 'finished', 'deadline' => null, 'finished_at' => $now, 'version' => $room->version + 1]);
        $this->attempts($room)->whereNull('finished_at')->eachById(fn (Attempt $attempt) => $this->finishAttempt($attempt, $room, $now));
    }

    public function attempts(Room $room): Builder
    {
        return Attempt::whereHas('participant', fn (Builder $query) => $query->where('session_id', $room->id));
    }

    public function expireDue(): void
    {
        Attempt::whereNull('finished_at')->where('deadline', '<=', now())
            ->with('participant:id,session_id')
            ->eachById(function (Attempt $attempt) {
                DB::transaction(function () use ($attempt) {
                    $room = Room::lockForUpdate()->find($attempt->participant->session_id);
                    if ($room && $attempt->refresh()->finished_at === null) {
                        $this->expire($attempt, $room);
                    }
                }, 3);
            });
    }
}
