<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\SamperinUser;

class SamperinUserSeeder extends Seeder
{
    public function run(): void
    {
        SamperinUser::updateOrCreate(
            [
                'user_email' => 'admin@samperin.local',
            ],

            [
                'user_uid' => 'USR-' . strtoupper(Str::random(12)),

                'user_nip' => '000000000000000000',

                'user_name' => 'Administrator SAMPERIN',

                'user_password' => Hash::make('password'),

                'user_status' => 1,
            ],
        );
    }
}