<?php

namespace Database\Seeders;

use App\Models\SamperinRole;
use App\Models\SamperinUser;
use App\Models\SamperinUserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SamperinUserRoleSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CARI USER
        |--------------------------------------------------------------------------
        */

        $user = SamperinUser::where('user_id', 1919)->first();

        if (!$user) {
            throw new \Exception('User SAMPERIN dengan user_id 1919 tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE YANG DIBERIKAN
        |--------------------------------------------------------------------------
        |
        | User Anda:
        |
        | Pegawai
        | Kepegawaian
        | Administrator
        |
        */

        $roles = ['pegawai', 'kepeg', 'admin'];

        /*
        |--------------------------------------------------------------------------
        | ASSIGN ROLE
        |--------------------------------------------------------------------------
        */

        foreach ($roles as $slug) {
            $role = SamperinRole::where('role_slug', $slug)->where('role_status', 1)->first();

            if (!$role) {
                throw new \Exception('Role "' . $slug . '" belum tersedia.');
            }

            /*
            |--------------------------------------------------------------------------
            | CEK USER ROLE
            |--------------------------------------------------------------------------
            */

            $exists = SamperinUserRole::where('user_role_user_uid', $user->user_uid)->where('user_role_role_uid', $role->role_uid)->exists();

            /*
            |--------------------------------------------------------------------------
            | INSERT
            |--------------------------------------------------------------------------
            */

            if (!$exists) {
                SamperinUserRole::create([
                    'user_role_uid' => (string) Str::uuid(),

                    'user_role_user_uid' => $user->user_uid,

                    'user_role_role_uid' => $role->role_uid,
                ]);
            }
        }
    }
}