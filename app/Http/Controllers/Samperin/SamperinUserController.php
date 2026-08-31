<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinUser;

class SamperinUserController extends Controller
{
    public function dashboard()
    {
        $user = $this->getLoginUser();

        return view('dashboard.user.dashboard', compact('user'));
    }

    public function profil()
    {
        $user = $this->getLoginUser();

        return view('dashboard.user.profil', compact('user'));
    }

    public function berkas()
    {
        $user = $this->getLoginUser();

        return view('dashboard.user.berkas', compact('user'));
    }

    public function pengaturan()
    {
        $user = $this->getLoginUser();

        return view('dashboard.user.pengaturan', compact('user'));
    }

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