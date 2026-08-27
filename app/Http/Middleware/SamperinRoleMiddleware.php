<?php

namespace App\Http\Middleware;

use App\Models\SamperinUser;

use Closure;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

class SamperinRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        /*
        |--------------------------------------------------------------------------
        | USER ID SESSION
        |--------------------------------------------------------------------------
        */

        $userId = session('samperin_user_id');

        /*
        |--------------------------------------------------------------------------
        | BELUM LOGIN
        |--------------------------------------------------------------------------
        */

        if (!$userId) {
            return redirect()->route('samperin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $user = SamperinUser::find($userId);

        /*
        |--------------------------------------------------------------------------
        | USER TIDAK ADA
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            session()->invalidate();

            session()->regenerateToken();

            return redirect()->route('samperin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if ((int) $user->user_status !== 1) {
            session()->invalidate();

            session()->regenerateToken();

            return redirect()
                ->route('samperin.login')
                ->withErrors([
                    'login' => 'Akun Anda sudah tidak aktif.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        |
        | Role menggunakan UID.
        |
        | user_role_user_uid
        | user_role_role_uid
        |
        */

        $hasRole = $user
            ->roles()

            ->whereIn('role_slug', $roles)

            ->where('role_status', 1)

            ->exists();

        /*
        |--------------------------------------------------------------------------
        | TIDAK PUNYA ROLE
        |--------------------------------------------------------------------------
        */

        if (!$hasRole) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN USER KE REQUEST
        |--------------------------------------------------------------------------
        */

        $request->attributes->set('samperin_user', $user);

        return $next($request);
    }
}