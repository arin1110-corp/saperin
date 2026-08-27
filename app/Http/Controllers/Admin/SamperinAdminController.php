<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SamperinRole;
use App\Models\SamperinUser;
use Illuminate\Support\Facades\DB;

class SamperinAdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */

        $userId = session('samperin_user_id');

        if (!$userId) {
            return redirect()->route('samperin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | DATA USER
        |--------------------------------------------------------------------------
        */

        $user = SamperinUser::find($userId);

        if (!$user) {
            session()->invalidate();

            session()->regenerateToken();

            return redirect()->route('samperin.login');
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS USER
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
        |
        | User:
        | user_uid
        |
        | Pivot:
        | user_role_user_uid
        |
        | Role:
        | role_uid
        |
        */

        $isAdmin = DB::table('samperin_user_role')->join('samperin_role', 'samperin_role.role_uid', '=', 'samperin_user_role.user_role_role_uid')->where('samperin_user_role.user_role_user_uid', $user->user_uid)->where('samperin_role.role_slug', 'admin')->where('samperin_role.role_status', 1)->exists();

        if (!$isAdmin) {
            return redirect()->route('samperin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL PEGAWAI
        |--------------------------------------------------------------------------
        */

        $totalPegawai = SamperinUser::count();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI AKTIF
        |--------------------------------------------------------------------------
        */

        $pegawaiAktif = SamperinUser::where('user_status', 1)->count();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI NONAKTIF
        |--------------------------------------------------------------------------
        */

        $pegawaiNonaktif = SamperinUser::where('user_status', 0)->count();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI DENGAN EMAIL
        |--------------------------------------------------------------------------
        */

        $pegawaiDenganEmail = SamperinUser::whereNotNull('user_email')->where('user_email', '!=', '')->count();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI TANPA EMAIL
        |--------------------------------------------------------------------------
        */

        $pegawaiTanpaEmail = SamperinUser::where(function ($query) {
            $query->whereNull('user_email')->orWhere('user_email', '');
        })->count();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI DENGAN TELEPON
        |--------------------------------------------------------------------------
        */

        $pegawaiDenganTelepon = SamperinUser::whereNotNull('user_notelp')->where('user_notelp', '!=', '')->count();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI TANPA TELEPON
        |--------------------------------------------------------------------------
        */

        $pegawaiTanpaTelepon = SamperinUser::where(function ($query) {
            $query->whereNull('user_notelp')->orWhere('user_notelp', '');
        })->count();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI DENGAN FOTO
        |--------------------------------------------------------------------------
        |
        | Relasi FOTO:
        |
        | samperin_user.user_uid
        |        =
        | samperin_user_foto.user_foto_user_uid
        |
        */

        $pegawaiDenganFoto = DB::table('samperin_user')->join('samperin_user_foto', 'samperin_user_foto.user_foto_user_uid', '=', 'samperin_user.user_uid')->select('samperin_user.user_uid')->distinct()->count();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI TANPA FOTO
        |--------------------------------------------------------------------------
        */

        $pegawaiTanpaFoto = max(0, $totalPegawai - $pegawaiDenganFoto);

        /*
        |--------------------------------------------------------------------------
        | ROLE PEGAWAI
        |--------------------------------------------------------------------------
        */

        $rolePegawai = SamperinRole::where('role_slug', 'pegawai')->where('role_status', 1)->first();

        /*
        |--------------------------------------------------------------------------
        | TOTAL USER ROLE PEGAWAI
        |--------------------------------------------------------------------------
        */

        $totalRolePegawai = 0;

        if ($rolePegawai) {
            $totalRolePegawai = DB::table('samperin_user_role')->where('user_role_role_uid', $rolePegawai->role_uid)->count();
        }

        /*
        |--------------------------------------------------------------------------
        | USER TANPA ROLE
        |--------------------------------------------------------------------------
        */

        $userTanpaRole = max(0, $totalPegawai - $totalRolePegawai);

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI TERBARU
        |--------------------------------------------------------------------------
        */

        $pegawaiTerbaru = SamperinUser::query()->orderByDesc('user_id')->limit(10)->get();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH JABATAN
        |--------------------------------------------------------------------------
        */

        $totalJabatan = DB::table('samperin_jabatan')->count();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH BIDANG
        |--------------------------------------------------------------------------
        */

        $totalBidang = DB::table('samperin_bidang')->count();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH GOLONGAN
        |--------------------------------------------------------------------------
        */

        $totalGolongan = DB::table('samperin_golongan')->count();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH ESELON
        |--------------------------------------------------------------------------
        */

        $totalEselon = DB::table('samperin_eselon')->count();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH PENDIDIKAN
        |--------------------------------------------------------------------------
        */

        $totalPendidikan = DB::table('samperin_pendidikan')->count();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH JENIS KERJA
        |--------------------------------------------------------------------------
        */

        $totalJenisKerja = DB::table('samperin_jenis_kerja')->count();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        |
        | TIDAK ADA PERUBAHAN PADA BLADE.
        |
        */

        return view(
            'dashboard.dashboard',
            compact(
                'user',

                'totalPegawai',

                'pegawaiAktif',

                'pegawaiNonaktif',

                'pegawaiDenganEmail',

                'pegawaiTanpaEmail',

                'pegawaiDenganTelepon',

                'pegawaiTanpaTelepon',

                'pegawaiDenganFoto',

                'pegawaiTanpaFoto',

                'totalRolePegawai',

                'userTanpaRole',

                'pegawaiTerbaru',

                'totalJabatan',

                'totalBidang',

                'totalGolongan',

                'totalEselon',

                'totalPendidikan',

                'totalJenisKerja',
            ),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DAFTAR ROLE PEGAWAI
    |--------------------------------------------------------------------------
    */

    public function pegawai()
    {
        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $users = SamperinUser::with('roles')->orderBy('user_nama', 'asc')->get();

        /*
        |--------------------------------------------------------------------------
        | ROLE PEGAWAI
        |--------------------------------------------------------------------------
        */

        $rolePegawai = SamperinRole::where('role_slug', 'pegawai')->where('role_status', 1)->first();

        return view('dashboard.pegawai', compact('users', 'rolePegawai'));
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE ROLE PEGAWAI
    |--------------------------------------------------------------------------
    */

    public function togglePegawai($userId)
    {
        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        |
        | user_id tetap ID lama SADARIN.
        |
        */

        $user = SamperinUser::findOrFail($userId);

        /*
        |--------------------------------------------------------------------------
        | UID USER
        |--------------------------------------------------------------------------
        */

        if (empty($user->user_uid)) {
            return back()->with('error', 'UID SAMPERIN pengguna belum tersedia.');
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE PEGAWAI
        |--------------------------------------------------------------------------
        */

        $role = SamperinRole::where('role_slug', 'pegawai')->where('role_status', 1)->first();

        if (!$role) {
            return back()->with('error', 'Role "pegawai" belum tersedia di samperin_role.');
        }

        /*
        |--------------------------------------------------------------------------
        | UID ROLE
        |--------------------------------------------------------------------------
        */

        if (empty($role->role_uid)) {
            return back()->with('error', 'UID role "pegawai" belum tersedia.');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK ROLE
        |--------------------------------------------------------------------------
        */

        $pivot = DB::table('samperin_user_role')->where('user_role_user_uid', $user->user_uid)->where('user_role_role_uid', $role->role_uid)->first();

        /*
        |--------------------------------------------------------------------------
        | CABUT ROLE
        |--------------------------------------------------------------------------
        */

        if ($pivot) {
            DB::table('samperin_user_role')->where('user_role_user_uid', $user->user_uid)->where('user_role_role_uid', $role->role_uid)->delete();

            return back()->with('success', 'Role Pegawai berhasil dicabut dari pengguna.');
        }

        /*
        |--------------------------------------------------------------------------
        | TAMBAH ROLE
        |--------------------------------------------------------------------------
        */

        DB::table('samperin_user_role')->insert([
            'user_role_user_uid' => $user->user_uid,

            'user_role_role_uid' => $role->role_uid,
        ]);

        return back()->with('success', 'Role Pegawai berhasil diberikan kepada pengguna.');
    }
}