<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class SamperinAdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $user = $this->getLoginUser();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalPegawai = SamperinUser::count();

        $pegawaiAktif = SamperinUser::where('user_status', 1)->count();

        $pegawaiNonaktif = SamperinUser::where('user_status', 0)->count();

        /*
        |--------------------------------------------------------------------------
        | USER TERBARU
        |--------------------------------------------------------------------------
        */

        $pegawaiTerbaru = SamperinUser::query()->orderByDesc('user_id')->limit(5)->get();

        /*
        |--------------------------------------------------------------------------
        | ROLE USER
        |--------------------------------------------------------------------------
        */

        $roles = $this->getUserRoles($user);

        /*
        |--------------------------------------------------------------------------
        | ROLE AKTIF
        |--------------------------------------------------------------------------
        */

        $activeRoleUid = session('samperin_role_uid');

        $activeRole = $roles->firstWhere('role_uid', $activeRoleUid);

        /*
        |--------------------------------------------------------------------------
        | JIKA SESSION ROLE TIDAK VALID
        |--------------------------------------------------------------------------
        |
        | Misalnya role sudah dihapus/nonaktif.
        |
        */

        if (!$activeRole && $roles->isNotEmpty()) {
            $activeRole = $this->resolveDefaultRole($roles);

            $this->setActiveRole($activeRole);
        }

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view('dashboard.dashboard', compact('user', 'totalPegawai', 'pegawaiAktif', 'pegawaiNonaktif', 'pegawaiTerbaru', 'roles', 'activeRole'));
    }

    /*
    |--------------------------------------------------------------------------
    | SWITCH ROLE
    |--------------------------------------------------------------------------
    */

    public function switchRole(Request $request)
    {
        $user = $this->getLoginUser();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'role_uid' => ['required', 'string'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CARI ROLE
        |--------------------------------------------------------------------------
        |
        | Hanya role yang benar-benar dimiliki user.
        |
        */

        $role = DB::table('samperin_user_role')->join('samperin_role', 'samperin_role.role_uid', '=', 'samperin_user_role.user_role_role_uid')->where('samperin_user_role.user_role_user_uid', $user->user_uid)->where('samperin_user_role.user_role_role_uid', $request->role_uid)->where('samperin_role.role_status', 1)->select('samperin_role.role_uid', 'samperin_role.role_nama', 'samperin_role.role_slug')->first();

        /*
        |--------------------------------------------------------------------------
        | ROLE TIDAK VALID
        |--------------------------------------------------------------------------
        */

        if (!$role) {
            return redirect()
                ->back()
                ->withErrors([
                    'role' => 'Role tidak valid atau tidak dimiliki oleh akun Anda.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN ROLE AKTIF
        |--------------------------------------------------------------------------
        */

        $this->setActiveRole($role);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT BERDASARKAN ROLE
        |--------------------------------------------------------------------------
        */

        $slug = strtolower(trim($role->role_slug));

        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATOR
        |--------------------------------------------------------------------------
        */

        if (in_array($slug, ['administrator', 'admin', 'admin-full'], true)) {
            return redirect()
                ->route('samperin.dashboard')
                ->with('success', 'Role berhasil diganti menjadi ' . $role->role_nama);
        }

        /*
        |--------------------------------------------------------------------------
        | KEPEGAWAIAN
        |--------------------------------------------------------------------------
        */

        if (in_array($slug, ['kepegawaian'], true)) {
            if (Route::has('kepeg.dashboard')) {
                return redirect()
                    ->route('kepeg.dashboard')
                    ->with('success', 'Role berhasil diganti menjadi ' . $role->role_nama);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI
        |--------------------------------------------------------------------------
        */

        if (in_array($slug, ['pegawai'], true)) {
            if (Route::has('samperin.dashboard')) {
                return redirect()
                    ->route('samperin.dashboard')
                    ->with('success', 'Role berhasil diganti menjadi ' . $role->role_nama);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE LAIN
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('samperin.dashboard')
            ->with('success', 'Role berhasil diganti menjadi ' . $role->role_nama);
    }

    /*
    |--------------------------------------------------------------------------
    | GET USER ROLES
    |--------------------------------------------------------------------------
    */

    private function getUserRoles(SamperinUser $user)
    {
        return DB::table('samperin_user_role')->join('samperin_role', 'samperin_role.role_uid', '=', 'samperin_user_role.user_role_role_uid')->where('samperin_user_role.user_role_user_uid', $user->user_uid)->where('samperin_role.role_status', 1)->select('samperin_role.role_uid', 'samperin_role.role_nama', 'samperin_role.role_slug')->orderBy('samperin_role.role_nama')->get();
    }

    /*
    |--------------------------------------------------------------------------
    | RESOLVE DEFAULT ROLE
    |--------------------------------------------------------------------------
    */

    private function resolveDefaultRole($roles)
    {
        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATOR
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
        | PEGAWAI
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
        | KEPEGAWAIAN
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
        | ROLE LAIN
        |--------------------------------------------------------------------------
        */

        return $roles->sortBy('role_nama')->first();
    }

    /*
    |--------------------------------------------------------------------------
    | SET ACTIVE ROLE
    |--------------------------------------------------------------------------
    */

    private function setActiveRole($role)
    {
        if (!$role) {
            session()->forget(['samperin_role_uid', 'samperin_role_nama', 'samperin_role_slug']);

            return;
        }

        session([
            'samperin_role_uid' => $role->role_uid,
            'samperin_role_nama' => $role->role_nama,
            'samperin_role_slug' => $role->role_slug,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN ROLE
    |--------------------------------------------------------------------------
    */

    public function roles()
    {
        $user = $this->getLoginUser();

        $roles = DB::table('samperin_role')->where('role_status', 1)->orderBy('role_nama')->get();

        return view('dashboard.admin.roles', compact('user', 'roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN PENGGUNA
    |--------------------------------------------------------------------------
    */

    public function users()
    {
        $user = $this->getLoginUser();

        $users = SamperinUser::query()->orderBy('user_nama')->paginate(20);

        return view('dashboard.admin.users', compact('user', 'users'));
    }

    /*
    |--------------------------------------------------------------------------
    | DATA MASTER
    |--------------------------------------------------------------------------
    */

    public function master()
    {
        $user = $this->getLoginUser();

        return view('dashboard.admin.master', compact('user'));
    }

    /*
    |--------------------------------------------------------------------------
    | LOG AKTIVITAS
    |--------------------------------------------------------------------------
    */

    public function activityLog()
    {
        $user = $this->getLoginUser();

        $logs = DB::table('samperin_activity_log')->orderByDesc('activity_log_id')->paginate(30);

        return view('dashboard.admin.activity-log', compact('user', 'logs'));
    }

    /*
    |--------------------------------------------------------------------------
    | PENGATURAN SISTEM
    |--------------------------------------------------------------------------
    */

    public function settings()
    {
        $user = $this->getLoginUser();

        return view('dashboard.admin.settings', compact('user'));
    }

    /*
    |--------------------------------------------------------------------------
    | USER LOGIN
    |--------------------------------------------------------------------------
    */

    private function getLoginUser()
    {
        $userId = session('samperin_user_id');

        /*
        |--------------------------------------------------------------------------
        | BELUM LOGIN
        |--------------------------------------------------------------------------
        */

        if (!$userId) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL USER
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

            session()->regenerateToken();

            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | USER NONAKTIF
        |--------------------------------------------------------------------------
        */

        if ((int) $user->user_status !== 1) {
            session()->invalidate();

            session()->regenerateToken();

            abort(403);
        }

        return $user;
    }
}
