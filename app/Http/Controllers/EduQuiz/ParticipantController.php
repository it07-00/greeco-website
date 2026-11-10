<?php

namespace App\Http\Controllers\EduQuiz;

use App\Models\EduQuiz\Room;
use App\Services\EduQuiz\Play;
use App\Services\EduQuiz\Privacy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Cookie;

class ParticipantController
{
    public function __construct(private Play $play) {}

    public function entry(Request $request, ?Room $room = null)
    {
        if ($room && $this->play->attempt($room, $this->token($request, $room))) {
            return $this->private(response()->view('eduquiz.participant', ['room' => $room]));
        }

        return $this->private(response()->view('eduquiz.join', [
            'room' => $room,
            'retentionDays' => app(Privacy::class)->days(),
        ]));
    }

    public function join(Request $request, ?Room $room = null)
    {
        $data = $request->validate([
            'pin' => ['required', 'digits:8'],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required_without:phone', 'nullable', 'email', 'max:254'],
            'phone' => ['required_without:email', 'nullable', 'string', 'max:40'],
            'invite' => ['nullable', 'string', 'max:64'],
            'consent' => ['accepted'],
        ], [
            'pin.digits' => 'Mã phòng gồm 8 chữ số.',
            'name.required' => 'Nhập họ và tên.',
            'email.required_without' => 'Nhập email hoặc số điện thoại.',
            'phone.required_without' => 'Nhập email hoặc số điện thoại.',
            'consent.accepted' => 'Vui lòng xác nhận thông báo sử dụng dữ liệu.',
        ]);

        if ($room && $room->pin !== (string) $data['pin']) {
            throw ValidationException::withMessages(['pin' => 'Mã phòng không khớp với liên kết.']);
        }
        $room ??= Room::where('pin', $data['pin'])->first();
        if (! $room) {
            throw ValidationException::withMessages(['pin' => 'Không tìm thấy phòng thi.']);
        }
        if ($room->requires_invite && empty($data['invite'])) {
            return redirect()->route('eduquiz.room', $room->pin)
                ->withInput($request->except(['invite', 'consent']))
                ->withErrors(['invite' => 'Phiên này yêu cầu mã mời từ giảng viên.']);
        }

        $token = $this->play->join($room, $data, $this->token($request, $room));

        return $this->private(redirect()->route('eduquiz.room', $room->pin)->withCookie($this->cookie($request, $room, $token)));
    }

    public function state(Request $request, Room $room): JsonResponse
    {
        $token = $this->token($request, $room);
        $attempt = $this->play->attempt($room, $token);
        abort_unless($attempt, 403);

        if (! $attempt->finished_at && $attempt->deadline && $attempt->deadline <= now()) {
            $this->play->submit($room, $token);
            $attempt->refresh();
        }

        $phase = $room->mode === 'self'
            ? ($attempt->finished_at ? 'finished' : 'open')
            : $room->phase();

        // A finish can commit between route binding and reading the attempt.
        if ($attempt->finished_at) {
            $phase = 'finished';
        }

        $visible = match ($phase) {
            'lobby', 'finished' => [],
            default => $room->mode === 'self' ? array_keys($room->questions) : [$room->current],
        };
        $answers = $attempt->answers()->whereIn('position', $visible)->pluck('choices', 'position');
        $questions = [];
        foreach ($visible as $position) {
            $question = $room->questions[$position];
            $questions[] = [
                'position' => $position,
                'prompt' => $question['prompt'],
                'multiple' => count(array_filter(array_column($question['options'], 'correct'))) > 1,
                'options' => array_column($question['options'], 'text'),
                'image' => $question['image'] ? route('eduquiz.image', [$room->pin, $position]) : null,
                'choices' => $answers->get($position),
                'correct' => $phase === 'revealed' ? array_keys(array_filter(array_column($question['options'], 'correct'))) : null,
                'explanation' => $phase === 'revealed' ? $question['explanation'] : null,
            ];
        }

        return $this->private(response()->json([
            'phase' => $phase,
            'mode' => $room->mode,
            'total' => count($room->questions),
            'members' => $room->participants()->count(),
            'deadline' => ($room->mode === 'self' ? $attempt->deadline : $room->deadline)?->toIso8601String(),
            'pausedSeconds' => $phase === 'paused' ? $room->paused_seconds : null,
            'now' => now()->toIso8601String(),
            'questions' => $questions,
            'result' => $attempt->finished_at ? $attempt->result?->only('score', 'total', 'elapsed_seconds') : null,
            'attempt' => $attempt->attempt,
            'attemptLimit' => $room->attempt_limit,
            'retry' => $room->mode === 'self' && $attempt->finished_at && ! $room->finished_at && $attempt->attempt < $room->attempt_limit,
        ]));
    }

    public function answer(Request $request, Room $room)
    {
        $token = $this->token($request, $room);
        abort_unless($token, 403);
        $data = $request->validate([
            'position' => ['required', 'integer', 'min:0'],
            'choices' => [$room->mode === 'live' ? 'required' : 'present', 'array', 'max:6'],
            'choices.*' => ['integer', 'between:0,5', 'distinct'],
        ]);
        $choices = array_map('intval', $data['choices']);
        sort($choices, SORT_NUMERIC);
        $this->play->answer($room, $token, (int) $data['position'], $choices);

        return response()->noContent();
    }

    public function submit(Request $request, Room $room)
    {
        $token = $this->token($request, $room);
        abort_unless($token, 403);
        $this->play->submit($room, $token);

        return response()->noContent();
    }

    public function retry(Request $request, Room $room)
    {
        $token = $this->token($request, $room);
        abort_unless($token, 403);
        $token = $this->play->retry($room, $token);

        return response()->noContent()->withCookie($this->cookie($request, $room, $token));
    }

    public function image(Request $request, Room $room, int $position)
    {
        abort_unless($this->play->attempt($room, $this->token($request, $room)), 403);
        abort_unless(
            $room->mode === 'self' || ($room->current === $position && ! in_array($room->phase(), ['lobby', 'finished'], true)),
            404,
        );
        $path = $room->questions[$position]['image'] ?? null;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return $this->private(Storage::disk('local')->response($path));
    }

    private function token(Request $request, Room $room): ?string
    {
        return $request->cookie('eduquiz_'.$room->pin);
    }

    private function cookie(Request $request, Room $room, string $token): Cookie
    {
        return cookie('eduquiz_'.$room->pin, $token, 43200, '/eduquiz/'.$room->pin, null,
            $request->isSecure() || app()->isProduction(), true, false, 'lax');
    }

    private function private($response)
    {
        $response->headers->set('Cache-Control', 'private, no-store');
        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}
