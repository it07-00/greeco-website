<?php

namespace App\Services\EduQuiz;

use App\Models\EduQuiz\Room;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ThrottlePlayer
{
    public function handle(Request $request, Closure $next)
    {
        $room = $request->route('room');
        $token = $room instanceof Room ? $request->cookie('eduquiz_'.$room->pin) : null;
        $key = 'eduquiz:'.$request->route()->getName().':'.hash('sha256', $token ?? $request->ip());
        $limit = $token ? 120 : 240;

        abort_if(RateLimiter::tooManyAttempts($key, $limit), 429, 'Quá nhiều yêu cầu. Vui lòng thử lại sau.');
        RateLimiter::hit($key);

        return $next($request);
    }
}
