<?php

namespace App\Http\Controllers\Auth;

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
                'login.required' => 'NIP, NIK, atau email wajib diisi.',

                'password.required' => 'Password wajib diisi.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | DATA LOGIN
        |--------------------------------------------------------------------------
        */

        $login = trim($request->input('login'));

        $password = $request->input('password');

        /*
        |--------------------------------------------------------------------------
        | CARI USER
        |--------------------------------------------------------------------------
        |
        | Login dapat menggunakan:
        |
        | - NIP
        | - NIK
        | - Email
        |
        */

        $user = SamperinUser::query()

            ->where('user_status', 1)

            ->where(function ($query) use ($login) {
                $query
                    ->where('user_nip', $login)

                    ->orWhere('user_nik', $login)

                    ->orWhere('user_email', $login);
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
                    'login' => 'NIP, NIK, atau email tidak ditemukan.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PASSWORD BELUM ADA
        |--------------------------------------------------------------------------
        */

        if (empty($user->user_password)) {
            return back()
                ->withInput($request->only('login'))

                ->withErrors([
                    'password' => 'Akun ini belum memiliki password.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK PASSWORD
        |--------------------------------------------------------------------------
        */

        if (!Hash::check($password, $user->user_password)) {
            return back()
                ->withInput($request->only('login'))

                ->withErrors([
                    'password' => 'Password yang Anda masukkan salah.',
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
        | SIMPAN USER KE SESSION
        |--------------------------------------------------------------------------
        */

        $request->session()->put('samperin_user_id', $user->user_id);

        $request->session()->put('samperin_user_uid', $user->user_uid);

        /*
        |--------------------------------------------------------------------------
        | REMEMBER
        |--------------------------------------------------------------------------
        */

        if ($request->boolean('remember')) {
            $request->session()->put('samperin_remember', true);
        } else {
            $request->session()->forget('samperin_remember');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK ROLE ADMINISTRATOR
        |--------------------------------------------------------------------------
        |
        | Struktur:
        |
        | samperin_user
        |       ↓
        | samperin_user_role
        |       ↓
        | samperin_role
        |
        | Administrator:
        |
        | samperin_role.role_slug = admin
        |
        */

        $isAdmin = DB::table('samperin_user_role')

            ->join('samperin_role', 'samperin_role.role_uid', '=', 'samperin_user_role.user_role_role_uid')

            ->where('samperin_user_role.user_role_user_uid', $user->user_uid)

            ->where('samperin_role.role_slug', 'admin')

            ->where('samperin_role.role_status', 1)

            ->exists();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN STATUS ADMIN
        |--------------------------------------------------------------------------
        */

        $request->session()->put('samperin_is_admin', $isAdmin);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT ADMIN
        |--------------------------------------------------------------------------
        */

        if ($isAdmin) {
            return redirect()->route('samperin.admin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | REDIRECT USER BIASA
        |--------------------------------------------------------------------------
        */

        return redirect()->route('samperin.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | HAPUS SESSION SAMPERIN
        |--------------------------------------------------------------------------
        */

        $request->session()->forget(['samperin_user_id', 'samperin_user_uid', 'samperin_remember', 'samperin_is_admin']);

        /*
        |--------------------------------------------------------------------------
        | INVALIDATE
        |--------------------------------------------------------------------------
        */

        $request->session()->invalidate();

        /*
        |--------------------------------------------------------------------------
        | REGENERATE TOKEN
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerateToken();

        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE LOGIN
        |--------------------------------------------------------------------------
        */

        return redirect()->route('samperin.login');
    }
}