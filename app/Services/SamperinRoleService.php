<?php

namespace App\Services;

use App\Models\SamperinUser;
use Illuminate\Support\Str;

class SamperinRoleService
{
    public function syncRoles(SamperinUser $user, array $roleUids): void
    {
        $roleUids = array_values(array_unique($roleUids));

        /*
        |--------------------------------------------------------------------------
        | ROLE SAAT INI
        |--------------------------------------------------------------------------
        */

        $current = $user->roles()->pluck('role_uid')->all();

        /*
        |--------------------------------------------------------------------------
        | HAPUS ROLE
        |--------------------------------------------------------------------------
        */

        foreach (array_diff($current, $roleUids) as $uid) {
            $user->roles()->detach($uid);
        }

        /*
        |--------------------------------------------------------------------------
        | TAMBAH ROLE
        |--------------------------------------------------------------------------
        */

        foreach (array_diff($roleUids, $current) as $uid) {
            $user->roles()->attach(
                $uid,

                [
                    'user_role_uid' => (string) Str::uuid(),
                ],
            );
        }
    }
}