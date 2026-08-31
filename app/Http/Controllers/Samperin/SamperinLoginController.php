<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SamperinLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        if (session()->has('samperin_user_id')) {
            return redirect()->route('samperin.dashboard');
        }

        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate(
            [
                'login' => ['required', 'string'],
                'password' => ['required', 'string'],
            ],
            [
                'login.required' => 'NIP, NIK atau email wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | INPUT LOGIN
        |--------------------------------------------------------------------------
        */

        $login = trim($request->input('login'));

        /*
        |--------------------------------------------------------------------------
        | CARI USER
        |--------------------------------------------------------------------------
        |
        | Bisa login menggunakan:
        |
        | - NIP
        | - NIK
        | - Email
        |
        */

        $user = SamperinUser::query()
            ->where(function ($query) use ($login) {
                $query->where('user_nip', $login)->orWhere('user_nik', $login)->orWhere('user_email', $login);
            })
            ->first();

        /*
        |--------------------------------------------------------------------------
        | USER TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors([
                    'login' => 'NIP, NIK/email atau password salah.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS USER
        |--------------------------------------------------------------------------
        */

        if ((int) $user->user_status !== 1) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors([
                    'login' => 'Akun Anda sudah tidak aktif.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK PASSWORD
        |--------------------------------------------------------------------------
        */

        if (!Hash::check($request->input('password'), $user->user_password)) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors([
                    'login' => 'NIP, NIK/email atau password salah.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | REGENERATE SESSION
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN USER
        |--------------------------------------------------------------------------
        */

        session([
            'samperin_user_id' => $user->user_id,
            'samperin_user_uid' => $user->user_uid,
        ]);

        /*
        |--------------------------------------------------------------------------
        | AMBIL ROLE USER
        |--------------------------------------------------------------------------
        */

        $roles = DB::table('samperin_user_role')->join('samperin_role', 'samperin_role.role_uid', '=', 'samperin_user_role.user_role_role_uid')->where('samperin_user_role.user_role_user_uid', $user->user_uid)->where('samperin_role.role_status', 1)->select('samperin_role.role_uid', 'samperin_role.role_nama', 'samperin_role.role_slug')->get();

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN ROLE DEFAULT
        |--------------------------------------------------------------------------
        |
        | PRIORITAS:
        |
        | 1. Administrator
        | 2. Pegawai
        | 3. Kepegawaian
        | 4. Role lainnya
        |
        */

        $activeRole = $this->resolveDefaultRole($roles);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN ROLE AKTIF
        |--------------------------------------------------------------------------
        */

        if ($activeRole) {
            session([
                'samperin_role_uid' => $activeRole->role_uid,
                'samperin_role_nama' => $activeRole->role_nama,
                'samperin_role_slug' => $activeRole->role_slug,
            ]);
        } else {
            /*
            |--------------------------------------------------------------------------
            | USER BELUM MEMILIKI ROLE
            |--------------------------------------------------------------------------
            */

            session()->forget(['samperin_role_uid', 'samperin_role_nama', 'samperin_role_slug']);
        }

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()->route('samperin.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | DEFAULT ROLE
    |--------------------------------------------------------------------------
    */

    private function resolveDefaultRole($roles)
    {
        if ($roles->isEmpty()) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | PRIORITAS 1 - ADMINISTRATOR
        |--------------------------------------------------------------------------
        */

        $administrator = $roles->first(function ($role) {
            return in_array(strtolower(trim($role->role_slug)), ['administrator', 'admin', 'admin-full'], true);
        });

        if ($administrator) {
            return $administrator;
        }

        /*
        |--------------------------------------------------------------------------
        | PRIORITAS 2 - PEGAWAI
        |--------------------------------------------------------------------------
        */

        $pegawai = $roles->first(function ($role) {
            return in_array(strtolower(trim($role->role_slug)), ['pegawai'], true);
        });

        if ($pegawai) {
            return $pegawai;
        }

        /*
        |--------------------------------------------------------------------------
        | PRIORITAS 3 - KEPEGAWAIAN
        |--------------------------------------------------------------------------
        */

        $kepegawaian = $roles->first(function ($role) {
            return in_array(strtolower(trim($role->role_slug)), ['kepegawaian'], true);
        });

        if ($kepegawaian) {
            return $kepegawaian;
        }

        /*
        |--------------------------------------------------------------------------
        | PRIORITAS 4 - ROLE LAINNYA
        |--------------------------------------------------------------------------
        */

        return $roles->sortBy('role_nama')->first();
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('samperin.login');
    }
}