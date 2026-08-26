<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SamperinUser;

class SamperinUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL USER UID DARI SESSION
        |--------------------------------------------------------------------------
        */

        $userUid = session('samperin_user_uid');

        if (!$userUid) {
            abort(401, 'Belum login melalui ARIN.');
        }

        /*
        |--------------------------------------------------------------------------
        | CARI USER
        |--------------------------------------------------------------------------
        */

        $user = SamperinUser::with(['roles', 'jabatan', 'bidang', 'golongan', 'eselon', 'pendidikan', 'statusPegawai', 'jenisKerja', 'foto'])
            ->where('user_uid', $userUid)
            ->where('user_status', 1)
            ->first();

        if (!$user) {
            session()->forget('samperin_user_uid');

            abort(403, 'Data pegawai tidak ditemukan atau tidak aktif.');
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN KE REQUEST
        |--------------------------------------------------------------------------
        */

        $request->attributes->set('samperin_user', $user);

        return $next($request);
    }
}