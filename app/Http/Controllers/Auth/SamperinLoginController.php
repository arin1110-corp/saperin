<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
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
                'login' => 'required|string',

                'password' => 'required|string',
            ],
            [
                'login.required' => 'NIP atau email wajib diisi.',

                'password.required' => 'Password wajib diisi.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | INPUT
        |--------------------------------------------------------------------------
        */

        $login = trim($request->input('login'));

        /*
        |--------------------------------------------------------------------------
        | CARI USER
        |--------------------------------------------------------------------------
        */

        $user = SamperinUser::query()

            ->where('user_nip', $login)

            ->orWhere('user_email', $login)

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
                'login' => 'NIP/email atau password salah.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
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
        | PASSWORD
        |--------------------------------------------------------------------------
        */

        if (!Hash::check($request->input('password'), $user->user_password)) {
            return back()
                ->withInput($request->only('login'))

                ->withErrors([
                'login' => 'NIP/email atau password salah.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SESSION
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN USER ID
        |--------------------------------------------------------------------------
        */

        $request->session()->put('samperin_user_id', $user->user_id);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN UID
        |--------------------------------------------------------------------------
        */

        $request->session()->put('samperin_user_uid', $user->user_uid);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
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
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('samperin.login');
    }
}