<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saperin_user', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | IDENTITAS
            |--------------------------------------------------------------------------
            */

            $table->uuid('user_uid')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | DATA PEGAWAI
            |--------------------------------------------------------------------------
            */

            $table->string('user_nip', 30)
                ->unique();

            $table->string('user_nama', 150);

            $table->string('user_email', 150)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | LOGIN
            |--------------------------------------------------------------------------
            */

            $table->string('user_password');

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->boolean('user_status')
                ->default(true);

            $table->rememberToken();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saperin_user');
    }
};