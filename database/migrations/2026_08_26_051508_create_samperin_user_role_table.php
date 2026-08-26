<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_user_role', function (Blueprint $table) {

            $table->bigIncrements('user_role_id');

            $table->char('user_role_uid', 36)
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | RELASI MENGGUNAKAN UID
            |--------------------------------------------------------------------------
            */

            $table->char('user_role_user_uid', 36);

            $table->char('user_role_role_uid', 36);


            $table->dateTime('user_role_created_at')
                ->useCurrent();

            $table->dateTime('user_role_updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();


            /*
            |--------------------------------------------------------------------------
            | TIDAK BOLEH DUPLIKAT
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'user_role_user_uid',
                'user_role_role_uid'
            ]);


            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign('user_role_user_uid')
                ->references('user_uid')
                ->on('samperin_user')
                ->cascadeOnDelete();

            $table->foreign('user_role_role_uid')
                ->references('role_uid')
                ->on('samperin_role')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_user_role');
    }
};