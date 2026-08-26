<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_jenis_kerja', function (Blueprint $table) {

            $table->bigIncrements('jenis_kerja_id');

            $table->char('jenis_kerja_uid', 36)
                ->unique();

            $table->string('jenis_kerja_kode', 20)
                ->unique();

            $table->string('jenis_kerja_nama', 100);

            $table->tinyInteger('jenis_kerja_status')
                ->default(1);

            $table->dateTime('jenis_kerja_created_at')
                ->useCurrent();

            $table->dateTime('jenis_kerja_updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_jenis_kerja');
    }
};