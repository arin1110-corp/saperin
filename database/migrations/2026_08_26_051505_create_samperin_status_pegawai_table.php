<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_status_pegawai', function (Blueprint $table) {

            $table->bigIncrements('status_pegawai_id');

            $table->char('status_pegawai_uid', 36)
                ->unique();

            $table->string('status_pegawai_kode', 20)
                ->unique();

            $table->string('status_pegawai_nama', 100);

            $table->tinyInteger('status_pegawai_status')
                ->default(1);

            $table->dateTime('status_pegawai_created_at')
                ->useCurrent();

            $table->dateTime('status_pegawai_updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_status_pegawai');
    }
};