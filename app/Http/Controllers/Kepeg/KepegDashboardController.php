<?php

namespace App\Http\Controllers\Kepeg;

use App\Http\Controllers\Controller;
use App\Models\SamperinUser;

class KepegDashboardController extends Controller
{
    public function index()
    {
        $user = request()->attributes->get('samperin_user');

        $totalPegawai = SamperinUser::count();

        $pegawaiAktif = SamperinUser::where('user_status', 1)->count();

        $pegawaiNonaktif = SamperinUser::where('user_status', '!=', 1)->count();

        return view('dashboard-kepeg.dashboard', compact('user', 'totalPegawai', 'pegawaiAktif', 'pegawaiNonaktif'));
    }
}