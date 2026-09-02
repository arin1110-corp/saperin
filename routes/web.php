<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Samperin\SamperinLoginController;
use App\Http\Controllers\Samperin\SamperinAdminController;
use App\Http\Controllers\Samperin\SamperinRoleController;
use App\Http\Controllers\Samperin\SamperinBidangController;
use App\Http\Controllers\Samperin\SamperinJabatanController;
use App\Http\Controllers\Samperin\SamperinGolonganController;
use App\Http\Controllers\Samperin\SamperinJenisKerjaController;
use App\Http\Controllers\Samperin\SamperinEselonController;
use App\Http\Controllers\Samperin\SamperinPendidikanController;
use App\Http\Controllers\Samperin\SamperinPenggunaController;
use App\Http\Controllers\Samperin\SamperinKepegController;
use App\Http\Controllers\Samperin\SamperinUserController;
use App\Http\Controllers\Samperin\SamperinPegawaiImportController;
use App\Http\Controllers\Samperin\SamperinPengaturanController;
use App\Http\Controllers\Samperin\SamperinFotoImportController;

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

Route::post('/login', [SamperinLoginController::class, 'login'])->name('samperin.login.process');

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [SamperinLoginController::class, 'logout'])->name('samperin.logout');

/*
|--------------------------------------------------------------------------
| INTERNAL SYSTEM
|--------------------------------------------------------------------------
*/

Route::middleware('samperin.auth')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [SamperinAdminController::class, 'dashboard'])->name('samperin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | SWITCH ROLE
    |--------------------------------------------------------------------------
    */

    Route::post('/switch-role', [SamperinAdminController::class, 'switchRole'])->name('samperin.role.switch');

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
        | MANAJEMEN ROLE
        |--------------------------------------------------------------------------
        */

        Route::prefix('roles')
            ->name('roles.')
            ->middleware('samperin.role:admin')
            ->group(function () {
            Route::get('/', [SamperinRoleController::class, 'index'])->name('index');

            Route::post('/', [SamperinRoleController::class, 'store'])->name('store');

            Route::put('/{role_uid}', [SamperinRoleController::class, 'update'])->name('update');

            Route::patch('/{role_uid}/status', [SamperinRoleController::class, 'toggleStatus'])->name('status');

            Route::delete('/{role_uid}', [SamperinRoleController::class, 'destroy'])->name('destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | MANAJEMEN PENGGUNA
        |--------------------------------------------------------------------------
        */

        Route::prefix('users')
            ->name('users.')
            ->middleware('samperin.role:admin')
            ->group(function () {
            Route::get('/', [SamperinPenggunaController::class, 'index'])->name('index');

            Route::post('/', [SamperinPenggunaController::class, 'store'])->name('store');

            Route::put('/{uid}', [SamperinPenggunaController::class, 'update'])->name('update');

            Route::patch('/{uid}/status', [SamperinPenggunaController::class, 'status'])->name('status');

            Route::delete('/{uid}', [SamperinPenggunaController::class, 'destroy'])->name('destroy');

            Route::post('/assign-default-pegawai', [SamperinPenggunaController::class, 'assignDefaultPegawai'])->name('assign-default-pegawai');

            Route::get('/import', [SamperinPenggunaController::class, 'import'])->name('import');

            Route::post('/import/sql', [SamperinPenggunaController::class, 'importSql'])->name('import.sql');

            Route::post('/import/excel', [SamperinPenggunaController::class, 'importExcel'])->name('import.excel');
            });

        /*
        |--------------------------------------------------------------------------
        | LOG AKTIVITAS
        |--------------------------------------------------------------------------
        */

        Route::get('/activity-log', [SamperinBidangController::class, 'index'])->name('activity.index');
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

        /*
|--------------------------------------------------------------------------
| DATA PEGAWAI
|--------------------------------------------------------------------------
*/

        Route::prefix('pegawai')
            ->name('pegawai.')
            ->group(function () {
            /*
        |--------------------------------------------------------------------------
        | DAFTAR PEGAWAI
        |--------------------------------------------------------------------------
        */

            Route::get('/', [SamperinUserController::class, 'index'])->name('index');

            /*
        |--------------------------------------------------------------------------
        | TAMBAH PEGAWAI
        |--------------------------------------------------------------------------
        */

            Route::post('/', [SamperinUserController::class, 'store'])->name('store');

            /*
        |--------------------------------------------------------------------------
        | IMPORT PEGAWAI
        |--------------------------------------------------------------------------
        */

            Route::get('/import', [SamperinUserController::class, 'import'])->name('import');

            /*
        |--------------------------------------------------------------------------
        | PROSES IMPORT
        |--------------------------------------------------------------------------
        */

            Route::post('/import', [SamperinUserController::class, 'importProcess'])->name('import.process');

            /*
        |--------------------------------------------------------------------------
        | DETAIL PEGAWAI
        |--------------------------------------------------------------------------
        */

            /*
        |--------------------------------------------------------------------------
        | UPDATE PEGAWAI
        |--------------------------------------------------------------------------
        */

            Route::put('/{uid}', [SamperinUserController::class, 'update'])->name('update');

            /*
        |--------------------------------------------------------------------------
        | TOGGLE STATUS
        |--------------------------------------------------------------------------
        */

            Route::patch('/{uid}/status', [SamperinUserController::class, 'toggleStatus'])->name('status');

            /*
        |--------------------------------------------------------------------------
        | HAPUS PEGAWAI
        |--------------------------------------------------------------------------
        */

            Route::delete('/{uid}', [SamperinUserController::class, 'destroy'])->name('destroy');

            /*
        |--------------------------------------------------------------------------
        | IMPORT FOTO PEGAWAI
        |--------------------------------------------------------------------------
        */
            Route::get('/import-foto', [SamperinFotoImportController::class, 'index'])->name('import-foto');

            Route::post('/import-foto', [SamperinFotoImportController::class, 'import'])->name('import-foto.process');
            });
        /*
        |--------------------------------------------------------------------------
        | BERKAS PEGAWAI
        |--------------------------------------------------------------------------
        */

        Route::get('/berkas', [SamperinKepegController::class, 'berkas'])->name('berkas.index');
        });

    /*
|--------------------------------------------------------------------------
| DATA MASTER
|--------------------------------------------------------------------------
|
| Prefix URL : /master
|
| Nama route:
|
| master.bidang.index
| master.bidang.store
| master.bidang.import
| master.bidang.import.process
| master.bidang.update
| master.bidang.status
| master.bidang.destroy
|
| master.jabatan.index
| master.jabatan.store
| master.jabatan.import
| master.jabatan.import.process
| master.jabatan.update
| master.jabatan.status
| master.jabatan.destroy
|
| master.golongan.index
| master.eselon.index
| master.status-pegawai.index
|
|--------------------------------------------------------------------------
*/

    Route::prefix('master')
        ->name('master.')
        ->middleware('samperin.role:admin')
        ->group(function () {
        /*
        |--------------------------------------------------------------------------
        | MASTER BIDANG
        |--------------------------------------------------------------------------
        */

        Route::prefix('bidang')
            ->name('bidang.')
            ->group(function () {
                // Halaman daftar bidang
                Route::get('/', [SamperinBidangController::class, 'index'])->name('index');

            // Tambah bidang
            Route::post('/', [SamperinBidangController::class, 'store'])->name('store');

            // Halaman import bidang
            Route::get('/import', [SamperinBidangController::class, 'import'])->name('import');

            // Proses import bidang SQL / Excel
            Route::post('/import', [SamperinBidangController::class, 'importProcess'])->name('import.process');

            // Update bidang
            Route::put('/{uid}', [SamperinBidangController::class, 'update'])->name('update');

            // Aktif / nonaktif bidang
            Route::patch('/{uid}/status', [SamperinBidangController::class, 'toggleStatus'])->name('status');

                // Hapus bidang
                Route::delete('/{uid}', [SamperinBidangController::class, 'destroy'])->name('destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | MASTER JABATAN
        |--------------------------------------------------------------------------
        */

        Route::prefix('jabatan')
            ->name('jabatan.')
            ->group(function () {
                // Halaman daftar jabatan
                Route::get('/', [SamperinJabatanController::class, 'index'])->name('index');

            // Tambah jabatan
            Route::post('/', [SamperinJabatanController::class, 'store'])->name('store');

            // Halaman import jabatan
            Route::get('/import', [SamperinJabatanController::class, 'import'])->name('import');

            // Proses import jabatan SQL / Excel
            Route::post('/import', [SamperinJabatanController::class, 'importProcess'])->name('import.process');

            // Update jabatan
            Route::put('/{uid}', [SamperinJabatanController::class, 'update'])->name('update');

            // Aktif / nonaktif jabatan
            Route::patch('/{uid}/status', [SamperinJabatanController::class, 'toggleStatus'])->name('status');

                // Hapus jabatan
                Route::delete('/{uid}', [SamperinJabatanController::class, 'destroy'])->name('destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | MASTER GOLONGAN
        |--------------------------------------------------------------------------
        */

        Route::prefix('golongan')
            ->name('golongan.')
            ->group(function () {
                Route::get('/', [SamperinGolonganController::class, 'index'])->name('index');

            Route::post('/', [SamperinGolonganController::class, 'store'])->name('store');

            Route::put('/{id}', [SamperinGolonganController::class, 'update'])->name('update');

            Route::patch('/{id}/status', [SamperinGolonganController::class, 'toggleStatus'])->name('status');

            Route::delete('/{id}', [SamperinGolonganController::class, 'destroy'])->name('destroy');

            Route::get('/import', [SamperinGolonganController::class, 'import'])->name('import');

                Route::post('/import', [SamperinGolonganController::class, 'importProcess'])->name('import.process');
            });

        /*
        |--------------------------------------------------------------------------
        | MASTER ESELON
        |--------------------------------------------------------------------------
        */

        Route::prefix('master/eselon')
            ->name('eselon.')
            ->group(function () {
                Route::get('/', [SamperinEselonController::class, 'index'])->name('index');

            Route::post('/', [SamperinEselonController::class, 'store'])->name('store');

            Route::put('/{uid}', [SamperinEselonController::class, 'update'])->name('update');

            Route::patch('/{uid}/status', [SamperinEselonController::class, 'toggleStatus'])->name('status');

            Route::delete('/{uid}', [SamperinEselonController::class, 'destroy'])->name('destroy');

            Route::get('/import', [SamperinEselonController::class, 'import'])->name('import');

                Route::post('/import', [SamperinEselonController::class, 'importProcess'])->name('import.process');
            });

        Route::prefix('master/pendidikan')
            ->name('pendidikan.')
            ->group(function () {
                Route::get('/', [SamperinPendidikanController::class, 'index'])->name('index');

            Route::post('/', [SamperinPendidikanController::class, 'store'])->name('store');

            Route::put('/{uid}', [SamperinPendidikanController::class, 'update'])->name('update');

            Route::patch('/{uid}/status', [SamperinPendidikanController::class, 'toggleStatus'])->name('status');

            Route::delete('/{uid}', [SamperinPendidikanController::class, 'destroy'])->name('destroy');

            Route::get('/import', [SamperinPendidikanController::class, 'import'])->name('import');

                Route::post('/import/process', [SamperinPendidikanController::class, 'importProcess'])->name('import.process');
            });

        /*
        |--------------------------------------------------------------------------
        | MASTER JENIS KERJA
        |--------------------------------------------------------------------------
        */
        Route::prefix('master/jenis-kerja')
            ->name('jenis-kerja.')
            ->group(function () {
                Route::get('/', [SamperinJenisKerjaController::class, 'index'])->name('index');

            Route::post('/', [SamperinJenisKerjaController::class, 'store'])->name('store');

            Route::put('/{uid}', [SamperinJenisKerjaController::class, 'update'])->name('update');

            Route::patch('/{uid}/status', [SamperinJenisKerjaController::class, 'toggleStatus'])->name('status');

                Route::delete('/{uid}', [SamperinJenisKerjaController::class, 'destroy'])->name('destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | MASTER STATUS PEGAWAI
        |--------------------------------------------------------------------------
        */

        Route::get('/status-pegawai', function () {
                return view('dashboard.master.status-pegawai');
            })->name('status-pegawai.index');
        });

    /*
    |--------------------------------------------------------------------------
    | PENGATURAN
    |--------------------------------------------------------------------------
    */

    Route::prefix('pengaturan')
        ->name('pengaturan.')
        ->group(function () {
            // Pengaturan Sistem
            Route::get('/', [SamperinPengaturanController::class, 'index'])->name('index');

            Route::post('/store', [SamperinPengaturanController::class, 'pengaturanStore'])->name('store');

            Route::put('/{uid}', [SamperinPengaturanController::class, 'pengaturanUpdate'])->name('update');

            Route::delete('/{uid}', [SamperinPengaturanController::class, 'pengaturanDelete'])->name('delete');

            // Setting API
            Route::get('/api', [SamperinPengaturanController::class, 'api'])->name('api');

            Route::post('/api/store', [SamperinPengaturanController::class, 'apiStore'])->name('api.store');

            Route::put('/api/{uid}', [SamperinPengaturanController::class, 'apiUpdate'])->name('api.update');

            Route::delete('/api/{uid}', [SamperinPengaturanController::class, 'apiDelete'])->name('api.delete');

            // Setting Folder
            Route::get('/folder', [SamperinPengaturanController::class, 'folder'])->name('folder');

            Route::post('/folder/store', [SamperinPengaturanController::class, 'folderStore'])->name('folder.store');

            Route::put('/folder/{uid}', [SamperinPengaturanController::class, 'folderUpdate'])->name('folder.update');

            Route::delete('/folder/{uid}', [SamperinPengaturanController::class, 'folderDelete'])->name('folder.delete');
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