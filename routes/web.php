<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\SamperinLoginController;
use App\Models\SamperinUser;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', [SamperinLoginController::class, 'showLogin'])->name('samperin.login');

Route::get('/login', [SamperinLoginController::class, 'showLogin'])->name('samperin.login');

Route::post('/login', [SamperinLoginController::class, 'login'])->name('samperin.login.process');

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [SamperinLoginController::class, 'logout'])->name('samperin.logout');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED
|--------------------------------------------------------------------------
*/

Route::middleware('samperin.auth')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD AWAL
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        /*
            |--------------------------------------------------------------------------
            | USER ID
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
            session()->invalidate();

            return redirect()->route('samperin.login');
        }

        /*
            |--------------------------------------------------------------------------
            | USER NONAKTIF
            |--------------------------------------------------------------------------
            */

        if ((int) $user->user_status !== 1) {
            session()->invalidate();

            return redirect()
                ->route('samperin.login')
                ->withErrors([
                    'login' => 'Akun Anda sudah tidak aktif.',
                ]);
        }

        /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            |
            | Kalau administrator mencoba membuka
            | /dashboard, arahkan ke admin dashboard.
            |
            */

        if (session('samperin_is_admin')) {
            return redirect()->route('samperin.admin.dashboard');
        }

        /*
            |--------------------------------------------------------------------------
            | USER BIASA
            |--------------------------------------------------------------------------
            */

        return view('dashboard-awal.index', compact('user'));
    })->name('samperin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', function () {
        /*
            |--------------------------------------------------------------------------
            | USER ID
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
            session()->invalidate();

            return redirect()->route('samperin.login');
        }

        /*
            |--------------------------------------------------------------------------
            | USER NONAKTIF
            |--------------------------------------------------------------------------
            */

        if ((int) $user->user_status !== 1) {
            session()->invalidate();

            return redirect()
                ->route('samperin.login')
                ->withErrors([
                    'login' => 'Akun Anda sudah tidak aktif.',
                ]);
        }

        /*
            |--------------------------------------------------------------------------
            | CEK ROLE ADMIN
            |--------------------------------------------------------------------------
            |
            | Struktur database:
            |
            | samperin_user
            |       ↓
            | samperin_user_role
            |       ↓
            | samperin_role
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
            | BUKAN ADMIN
            |--------------------------------------------------------------------------
            */

        if (!$isAdmin) {
            return redirect()->route('samperin.dashboard');
        }

        /*
            |--------------------------------------------------------------------------
            | STATISTIK
            |--------------------------------------------------------------------------
            */

        $totalPegawai = SamperinUser::count();

        $pegawaiAktif = SamperinUser::where('user_status', 1)->count();

        $pegawaiNonaktif = SamperinUser::where('user_status', '!=', 1)->count();

        $pegawaiDenganEmail = SamperinUser::whereNotNull('user_email')->where('user_email', '!=', '')->count();

        /*
            |--------------------------------------------------------------------------
            | PEGAWAI TERBARU
            |--------------------------------------------------------------------------
            */

        $pegawaiTerbaru = SamperinUser::query()->orderByDesc('user_id')->limit(8)->get();

        /*
            |--------------------------------------------------------------------------
            | DASHBOARD ADMIN
            |--------------------------------------------------------------------------
            */

        return view('dashboard-admin.dashboard', compact('user', 'totalPegawai', 'pegawaiAktif', 'pegawaiNonaktif', 'pegawaiDenganEmail', 'pegawaiTerbaru'));
    })->name('samperin.admin.dashboard');
});