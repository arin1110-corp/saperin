<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_role', function (Blueprint $table) {

            $table->bigIncrements('role_id');

            $table->char('role_uid', 36)
                ->unique();

            $table->string('role_nama', 100);

            $table->string('role_slug', 100)
                ->unique();

            $table->string('role_deskripsi', 255)
                ->nullable();

            $table->tinyInteger('role_status')
                ->default(1);

            $table->dateTime('role_created_at')
                ->useCurrent();

            $table->dateTime('role_updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_role');
    }
};