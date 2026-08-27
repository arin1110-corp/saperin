<?php

namespace App\Http\Controllers;

use App\Models\SamperinRole;
use App\Models\SamperinUser;
use Illuminate\Http\Request;

class SamperinRoleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SWITCH ROLE
    |--------------------------------------------------------------------------
    */

    public function switch(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'role' => ['required', 'in:admin,pegawai'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */

        $userId = session('samperin_user_id');

        if (!$userId) {
            return redirect()->route('samperin.login')->with('error', 'Session login tidak ditemukan. Silakan login kembali.');
        }

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $user = SamperinUser::find($userId);

        if (!$user) {
            session()->invalidate();

            session()->regenerateToken();

            return redirect()->route('samperin.login')->with('error', 'Data pengguna tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | USER TIDAK AKTIF
        |--------------------------------------------------------------------------
        */

        if ((int) $user->user_status !== 1) {
            session()->invalidate();

            session()->regenerateToken();

            return redirect()->route('samperin.login')->with('error', 'Akun Anda sudah tidak aktif.');
        }

        /*
        |--------------------------------------------------------------------------
        | CARI ROLE
        |--------------------------------------------------------------------------
        */

        $role = SamperinRole::where('role_slug', $request->role)->where('role_status', 1)->first();

        if (!$role) {
            return back()->with('error', 'Role yang dipilih belum tersedia.');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK USER MEMILIKI ROLE
        |--------------------------------------------------------------------------
        |
        | Relasi menggunakan UID.
        |
        */

        $hasRole = $user->roles()->where('samperin_role.role_uid', $role->role_uid)->where('samperin_role.role_status', 1)->exists();

        if (!$hasRole) {
            return back()->with('error', 'Anda tidak memiliki role tersebut.');
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN ROLE AKTIF
        |--------------------------------------------------------------------------
        */

        session([
            'samperin_active_role' => $role->role_slug,
        ]);

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($role->role_slug === 'admin') {
            return redirect()->route('samperin.admin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI
        |--------------------------------------------------------------------------
        */

        if ($role->role_slug === 'pegawai') {
            return redirect()->route('kepeg.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        return redirect()->route('samperin.dashboard');
    }
}