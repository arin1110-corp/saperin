<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SamperinRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        /*
        |--------------------------------------------------------------------------
        | USER SESSION
        |--------------------------------------------------------------------------
        */

        $user = $request->attributes->get('samperin_user');

        if (!$user) {
            abort(401, 'User SAMPERIN belum login.');
        }

        /*
        |--------------------------------------------------------------------------
        | USER AKTIF
        |--------------------------------------------------------------------------
        */

        if (!$user->isPegawai()) {
            abort(403, 'Akun pegawai tidak aktif.');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK ROLE
        |--------------------------------------------------------------------------
        */

        if (!$user->hasAnyRole($roles)) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        return $next($request);
    }
}