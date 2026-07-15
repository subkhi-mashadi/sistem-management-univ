<?php

namespace App\Http\Middleware;

use App\Enums\Rbac\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserType
{
    public function handle(Request $request, Closure $next, string $type): Response
    {
        $user = $request->user();

        if (! $user || $user->user_type?->value !== $type) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
