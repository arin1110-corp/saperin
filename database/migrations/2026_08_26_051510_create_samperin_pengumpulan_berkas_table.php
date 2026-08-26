<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('samperin_pengumpulan_berkas', function (Blueprint $table) {
            $table->bigIncrements('pengumpulan_berkas_id');

            $table->char('pengumpulan_berkas_uid', 36)->unique();

            $table->char('pengumpulan_berkas_user_uid', 36);

            $table->string('pengumpulan_berkas_jenis', 150);

            $table->string('pengumpulan_berkas_file', 255);

            $table->string('pengumpulan_berkas_nama', 255);

            $table->string('pengumpulan_berkas_mime', 100);

            $table->unsignedBigInteger('pengumpulan_berkas_size');

            $table->date('pengumpulan_berkas_tanggal');

            $table->string('pengumpulan_berkas_keterangan', 255)->nullable();

            $table->dateTime('pengumpulan_berkas_created_at')->useCurrent();

            $table->dateTime('pengumpulan_berkas_updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('pengumpulan_berkas_user_uid')->references('user_uid')->on('samperin_user')->cascadeOnDelete();

            $table->index(['pengumpulan_berkas_user_uid', 'pengumpulan_berkas_jenis'], 'berkas_user_jenis_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_pengumpulan_berkas');
    }
};