<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Samperin\SamperinLoginController;
use App\Http\Controllers\Samperin\SamperinAdminController;
use App\Http\Controllers\Samperin\SamperinRoleController;
use App\Http\Controllers\Samperin\SamperinPenggunaController;
use App\Http\Controllers\Samperin\SamperinActivityController;
use App\Http\Controllers\Samperin\SamperinKepegController;
use App\Http\Controllers\Samperin\SamperinPegawaiImportController;

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
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->name('samperin.login');

Route::post('/login', [
    SamperinLoginController::class,
    'login',
])->name('samperin.login.process');

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [
    SamperinLoginController::class,
    'logout',
])->name('samperin.logout');

/*
|--------------------------------------------------------------------------
| INTERNAL SYSTEM
|--------------------------------------------------------------------------
|
| Semua halaman setelah login menggunakan middleware:
|
| samperin.auth
|
*/

Route::middleware('samperin.auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        SamperinAdminController::class,
        'dashboard',
    ])->name('samperin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | SWITCH ROLE
    |--------------------------------------------------------------------------
    |
    | User dapat berpindah ke role lain yang memang dimilikinya.
    |
    */

    Route::post('/switch-role', [
        SamperinAdminController::class,
        'switchRole',
    ])->name('samperin.role.switch');


    /*
    |--------------------------------------------------------------------------
    | ADMINISTRASI
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')
        ->name('samperin.admin.')
        ->group(function () {

        /*
            |--------------------------------------------------------------------------
            | ROLE
            |--------------------------------------------------------------------------
            */

        Route::prefix('roles')
            ->name('roles.')
            ->middleware('samperin.role:admin')
            ->group(function () {

            /*
                    | INDEX
                    */

            Route::get('/', [
                SamperinRoleController::class,
                'index',
            ])->name('index');

            /*
                    | STORE
                    */

            Route::post('/', [
                SamperinRoleController::class,
                'store',
            ])->name('store');

            /*
                    | UPDATE
                    */

            Route::put('/{role_uid}', [
                SamperinRoleController::class,
                'update',
            ])->name('update');

            /*
                    | TOGGLE STATUS
                    */

            Route::patch('/{role_uid}/status', [
                SamperinRoleController::class,
                'toggleStatus',
            ])->name('status');

                /*
                    | DELETE
                    */

                Route::delete('/{role_uid}', [
                    SamperinRoleController::class,
                    'destroy',
                ])->name('destroy');
            });


        /*
            |--------------------------------------------------------------------------
            | PENGGUNA
            |--------------------------------------------------------------------------
            |
            | Pengguna = pegawai yang mempunyai role.
            |
            */

        Route::prefix('users')
            ->name('users.')
            ->middleware('samperin.role:admin')
            ->group(function () {

            /*
                    | INDEX
                    */

            Route::get('/', [
                SamperinPenggunaController::class,
                'index',
            ])->name('index');

            /*
                    | TAMBAH PENGGUNA
                    */

            Route::post('/', [
                SamperinPenggunaController::class,
                'store',
            ])->name('store');

            /*
                    | UPDATE ROLE
                    */

            Route::put('/{uid}', [
                SamperinPenggunaController::class,
                'update',
            ])->name('update');

            /*
                    | STATUS PEGAWAI
                    */

            Route::patch('/{uid}/status', [
                SamperinPenggunaController::class,
                'status',
            ])->name('status');

            /*
                    | HAPUS ROLE PENGGUNA
                    |
                    | Tidak menghapus data pegawai.
                    */

            Route::delete('/{uid}', [
                SamperinPenggunaController::class,
                'destroy',
            ])->name('destroy');

            /*
                    |--------------------------------------------------------------------------
                    | TAMBAHKAN SEMUA PEGAWAI
                    |--------------------------------------------------------------------------
                    |
                    | Memberikan role "Pegawai" kepada seluruh
                    | pegawai aktif yang belum memilikinya.
                    |
                    */

            Route::post('/assign-default-pegawai', [
                SamperinPenggunaController::class,
                'assignDefaultPegawai',
            ])->name('assign-default-pegawai');

                /*
                    |--------------------------------------------------------------------------
                    | IMPORT PENGGUNA
                    |--------------------------------------------------------------------------
                    */

                Route::get('/import', [
                    SamperinPenggunaController::class,
                    'import',
                ])->name('import');

                /*
                    | IMPORT SQL
                    */

                Route::post('/import/sql', [
                    SamperinPenggunaController::class,
                    'importSql',
                ])->name('import.sql');

                /*
                    | IMPORT EXCEL
                    */

                Route::post('/import/excel', [
                    SamperinPenggunaController::class,
                    'importExcel',
                ])->name('import.excel');
            });


        /*
            |--------------------------------------------------------------------------
            | LOG AKTIVITAS
            |--------------------------------------------------------------------------
            */

        Route::get('/activity-log', [
                SamperinActivityController::class,
                'index',
            ])->name('activity.index');
        });


    /*
    |--------------------------------------------------------------------------
    | KEPEGAWAIAN
    |--------------------------------------------------------------------------
    */

    Route::prefix('kepegawaian')
        ->name('kepeg.')
        ->group(function () {

        /*
            |--------------------------------------------------------------------------
            | DASHBOARD KEPEGAWAIAN
            |--------------------------------------------------------------------------
            */

        Route::get('/', [
            SamperinKepegController::class,
            'dashboard',
        ])->name('dashboard');

        /*
            |--------------------------------------------------------------------------
            | DATA PEGAWAI
            |--------------------------------------------------------------------------
            */

        Route::get('/pegawai', [
            SamperinKepegController::class,
            'pegawai',
        ])->name('pegawai.index');

        /*
            |--------------------------------------------------------------------------
            | IMPORT DATA PEGAWAI
            |--------------------------------------------------------------------------
            */

        Route::get('/import', [
            SamperinPegawaiImportController::class,
            'index',
        ])->name('pegawai.import');

        /*
            |--------------------------------------------------------------------------
            | BERKAS PEGAWAI
            |--------------------------------------------------------------------------
            */

        Route::get('/berkas', [
            SamperinKepegController::class,
            'berkas',
        ])->name('berkas.index');
        });


    /*
    |--------------------------------------------------------------------------
    | DATA MASTER
    |--------------------------------------------------------------------------
    */

    Route::prefix('master')
        ->name('master.')
        ->group(function () {

        /*
            |--------------------------------------------------------------------------
            | JABATAN
            |--------------------------------------------------------------------------
            */

        Route::get('/jabatan', function () {
            return view('dashboard.master.jabatan');
        })->name('jabatan.index');

        /*
            |--------------------------------------------------------------------------
            | BIDANG
            |--------------------------------------------------------------------------
            */

        Route::get('/bidang', function () {
            return view('dashboard.master.bidang');
        })->name('bidang.index');

        /*
            |--------------------------------------------------------------------------
            | GOLONGAN
            |--------------------------------------------------------------------------
            */

        Route::get('/golongan', function () {
            return view('dashboard.master.golongan');
        })->name('golongan.index');

        /*
            |--------------------------------------------------------------------------
            | ESELON
            |--------------------------------------------------------------------------
            */

        Route::get('/eselon', function () {
            return view('dashboard.master.eselon');
        })->name('eselon.index');

        /*
            |--------------------------------------------------------------------------
            | STATUS PEGAWAI
            |--------------------------------------------------------------------------
            */

        Route::get('/status-pegawai', function () {
                return view('dashboard.master.status-pegawai');
            })->name('status-pegawai.index');
        });


    /*
    |--------------------------------------------------------------------------
    | AKUN
    |--------------------------------------------------------------------------
    */

    Route::prefix('akun')
        ->name('akun.')
        ->group(function () {

        /*
            |--------------------------------------------------------------------------
            | PROFIL
            |--------------------------------------------------------------------------
            */

        Route::get('/profil', function () {
            return view('dashboard.akun.profil');
        })->name('profil');

        /*
            |--------------------------------------------------------------------------
            | BERKAS SAYA
            |--------------------------------------------------------------------------
            */

        Route::get('/berkas', function () {
            return view('dashboard.akun.berkas');
        })->name('berkas');

        /*
            |--------------------------------------------------------------------------
            | PENGATURAN
            |--------------------------------------------------------------------------
            */

        Route::get('/pengaturan', function () {
            return view('dashboard.akun.pengaturan');
        })->name('pengaturan');
        });
});