<?php

namespace App\Http\Middleware;

use App\Models\SamperinUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SamperinAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL USER ID
        |--------------------------------------------------------------------------
        */

        $userId = $request->session()->get('samperin_user_id');

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
        | CARI USER
        |--------------------------------------------------------------------------
        */

        $user = SamperinUser::find($userId);

        /*
        |--------------------------------------------------------------------------
        | USER TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->route('samperin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | USER NONAKTIF
        |--------------------------------------------------------------------------
        */

        if ((int) $user->user_status !== 1) {
            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()
                ->route('samperin.login')
                ->withErrors([
                    'login' => 'Akun Anda sudah tidak aktif.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN USER KE REQUEST
        |--------------------------------------------------------------------------
        |
        | Bisa dipanggil dari Blade:
        |
        | request()->attributes->get('samperin_user')
        |
        */

        $request->attributes->set('samperin_user', $user);

        /*
        |--------------------------------------------------------------------------
        | LANJUT
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}