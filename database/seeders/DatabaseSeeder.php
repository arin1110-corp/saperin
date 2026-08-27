<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $this->call([SamperinUserSeeder::class]);

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        $this->call([SamperinRoleSeeder::class]);

        /*
        |--------------------------------------------------------------------------
        | USER ROLE
        |--------------------------------------------------------------------------
        */

        $this->call([SamperinUserRoleSeeder::class]);
    }
}