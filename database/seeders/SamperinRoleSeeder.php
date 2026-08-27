<?php

namespace Database\Seeders;

use App\Models\SamperinRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SamperinRoleSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ROLE PEGAWAI
        |--------------------------------------------------------------------------
        */

        $this->createRole('pegawai', 'Pegawai');

        /*
        |--------------------------------------------------------------------------
        | ROLE KEPEGAWAIAN
        |--------------------------------------------------------------------------
        */

        $this->createRole('kepeg', 'Kepegawaian');

        /*
        |--------------------------------------------------------------------------
        | ROLE ADMIN
        |--------------------------------------------------------------------------
        */

        $this->createRole('admin', 'Administrator');
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE ROLE
    |--------------------------------------------------------------------------
    */

    private function createRole(string $slug, string $nama): SamperinRole
    {
        $role = SamperinRole::where('role_slug', $slug)->first();

        /*
        |--------------------------------------------------------------------------
        | BUAT ROLE BARU
        |--------------------------------------------------------------------------
        */

        if (!$role) {
            $role = SamperinRole::create([
                'role_uid' => (string) Str::uuid(),

                'role_nama' => $nama,

                'role_slug' => $slug,

                'role_status' => 1,
            ]);
        } else {
            /*
            |--------------------------------------------------------------------------
            | AKTIFKAN KEMBALI ROLE
            |--------------------------------------------------------------------------
            */

            $role->update([
                'role_nama' => $nama,

                'role_status' => 1,
            ]);
        }

        return $role;
    }
}