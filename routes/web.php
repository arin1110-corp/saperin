<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\SamperinLoginController;
use App\Http\Controllers\Admin\SamperinAdminController;
use App\Http\Controllers\Kepeg\SamperinPegawaiImportController;
use App\Http\Controllers\SamperinRoleController;
use App\Models\SamperinUser;

/*
|--------------------------------------------------------------------------
| SAMPERIN ROUTES
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| GUEST / LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [SamperinLoginController::class, 'showLogin'])->name('samperin.login');

    Route::post('/login', [SamperinLoginController::class, 'login'])->name('samperin.login.process');
});

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (session()->has('samperin_user_id')) {
        return redirect()->route('samperin.dashboard');
    }

    return redirect()->route('samperin.login');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [SamperinLoginController::class, 'logout'])->name('samperin.logout');

/*
|--------------------------------------------------------------------------
| USER LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('samperin.auth')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD AWAL
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        $userId = session('samperin_user_id');

        if (!$userId) {
            return redirect()->route('samperin.login');
        }

        $user = SamperinUser::find($userId);

        if (!$user) {
            session()->invalidate();

            session()->regenerateToken();

            return redirect()->route('samperin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | USER STATUS
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
        | CEK ROLE ADMIN
        |--------------------------------------------------------------------------
        */

        $isAdmin = DB::table('samperin_user_role')

            ->join('samperin_role', 'samperin_role.role_uid', '=', 'samperin_user_role.user_role_role_uid')

            ->where('samperin_user_role.user_role_user_uid', $user->user_uid)

            ->where('samperin_role.role_slug', 'admin')

            ->where('samperin_role.role_status', 1)

            ->exists();

        if ($isAdmin) {
            if (!session()->has('active_role')) {
                session([
                    'active_role' => 'admin',
                    'role_slug' => 'admin',
                ]);
            }

            return redirect()->route('samperin.admin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK ROLE PEGAWAI
        |--------------------------------------------------------------------------
        */

        $isPegawai = DB::table('samperin_user_role')

            ->join('samperin_role', 'samperin_role.role_uid', '=', 'samperin_user_role.user_role_role_uid')

            ->where('samperin_user_role.user_role_user_uid', $user->user_uid)

            ->where('samperin_role.role_slug', 'pegawai')

            ->where('samperin_role.role_status', 1)

            ->exists();

        if ($isPegawai) {
            if (!session()->has('active_role')) {
                session([
                    'active_role' => 'pegawai',
                    'role_slug' => 'pegawai',
                ]);
            }

            return redirect()->route('kepeg.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD AWAL
        |--------------------------------------------------------------------------
        */

        return view('dashboard-awal.index', compact('user'));
    })->name('samperin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | SWITCH ROLE
    |--------------------------------------------------------------------------
    */

    Route::post('/switch-role', [SamperinRoleController::class, 'switch'])->name('samperin.role.switch');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')
        ->name('samperin.admin.')
        ->middleware(['samperin.role:admin'])
        ->group(function () {
        /*
            |--------------------------------------------------------------------------
            | DASHBOARD ADMIN
            |--------------------------------------------------------------------------
            */

        Route::get('/dashboard', [SamperinAdminController::class, 'dashboard'])->name('dashboard');

        /*
            |--------------------------------------------------------------------------
            | MANAJEMEN ROLE PEGAWAI
            |--------------------------------------------------------------------------
            */

        Route::get('/role/pegawai', [SamperinAdminController::class, 'pegawai'])->name('pegawai');

        /*
            |--------------------------------------------------------------------------
            | ALIAS MANAJEMEN ROLE
            |--------------------------------------------------------------------------
            |
            | Digunakan oleh sidebar:
            |
            | route('samperin.admin.roles.index')
            |
            */

        Route::get('/roles', [SamperinAdminController::class, 'pegawai'])->name('roles.index');

        /*
            |--------------------------------------------------------------------------
            | TOGGLE ROLE PEGAWAI
            |--------------------------------------------------------------------------
            */

        Route::patch('/role/pegawai/{userId}', [SamperinAdminController::class, 'togglePegawai'])->name('pegawai.toggle');
        });

    /*
    |--------------------------------------------------------------------------
    | KEPEGAWAIAN
    |--------------------------------------------------------------------------
    */

    Route::prefix('kepeg')
        ->name('kepeg.')
        ->middleware(['samperin.role:admin,pegawai'])
        ->group(function () {
        /*
            |--------------------------------------------------------------------------
            | DASHBOARD KEPEGAWAIAN
            |--------------------------------------------------------------------------
            */

        Route::get('/dashboard', function () {
            $userId = session('samperin_user_id');

            $user = SamperinUser::find($userId);

            if (!$user) {
                session()->invalidate();

                session()->regenerateToken();

                return redirect()->route('samperin.login');
            }

            /*
                    |--------------------------------------------------------------------------
                    | STATISTIK
                    |--------------------------------------------------------------------------
                    */

            $totalPegawai = SamperinUser::count();

            $pegawaiAktif = SamperinUser::where('user_status', 1)->count();

            $pegawaiNonaktif = SamperinUser::where('user_status', 0)->count();

            return view('dashboard-kepeg.dashboard', compact('user', 'totalPegawai', 'pegawaiAktif', 'pegawaiNonaktif'));
        })->name('dashboard');

        /*
            |--------------------------------------------------------------------------
            | IMPORT DATA PEGAWAI
            |--------------------------------------------------------------------------
            */

        Route::get('/pegawai/import', [SamperinPegawaiImportController::class, 'index'])->name('pegawai.import');

        /*
            |--------------------------------------------------------------------------
            | PROSES IMPORT
            |--------------------------------------------------------------------------
            */

        Route::post('/pegawai/import', [SamperinPegawaiImportController::class, 'import'])->name('pegawai.import.process');
        });
});