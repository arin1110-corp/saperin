<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinUser;

class SamperinKepegController extends Controller
{
    /**
     * Dashboard Kepegawaian
     */
    public function dashboard()
    {
        $user = $this->getLoginUser();

        $totalPegawai = SamperinUser::count();

        $pegawaiAktif = SamperinUser::where('user_status', 1)->count();

        $pegawaiNonaktif = SamperinUser::where('user_status', 0)->count();

        return view('dashboard.kepeg.dashboard', compact('user', 'totalPegawai', 'pegawaiAktif', 'pegawaiNonaktif'));
    }

    /**
     * Data Pegawai
     */
    public function pegawai()
    {
        $user = $this->getLoginUser();

        $pegawai = SamperinUser::query()->orderBy('user_nama')->paginate(20);

        return view('dashboard.kepeg.pegawai', compact('user', 'pegawai'));
    }

    /**
     * Import Pegawai
     */
    public function import()
    {
        $user = $this->getLoginUser();

        return view('dashboard.kepeg.import', compact('user'));
    }

    /**
     * Berkas Pegawai
     */
    public function berkas()
    {
        $user = $this->getLoginUser();

        return view('dashboard.kepeg.berkas', compact('user'));
    }

    /**
     * User login
     */
    private function getLoginUser()
    {
        $userId = session('samperin_user_id');

        $user = SamperinUser::find($userId);

        if (!$user) {
            abort(403);
        }

        return $user;
    }
}