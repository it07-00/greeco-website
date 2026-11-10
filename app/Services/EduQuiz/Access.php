<?php

namespace App\Services\EduQuiz;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Access
{
    public static function instructor(User $user): bool
    {
        return DB::table('eduquiz_instructors')->where('user_id', $user->id)->exists();
    }

    public static function scope(Builder $query, User $user): Builder
    {
        return self::instructor($user) ? $query->where('owner_id', $user->id) : $query;
    }

    public static function authorize(Model $model, User $user): void
    {
        abort_unless(! self::instructor($user) || $model->owner_id === $user->id, 403);
    }

    public function handle(Request $request, \Closure $next)
    {
        if (! $request->user() instanceof User) {
            abort_if($request->expectsJson(), 401);

            return redirect()->guest(route('filament.admin.auth.login'));
        }

        $response = $next($request);
        $response->headers->set('Cache-Control', 'private, no-store');

        return $response;
    }
}
