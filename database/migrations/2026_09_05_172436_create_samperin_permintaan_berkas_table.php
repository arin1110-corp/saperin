<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_permintaan_berkas', function (Blueprint $table) {
            $table->bigIncrements('permintaan_id');
            $table->uuid('permintaan_uid')->unique();

            $table->unsignedBigInteger('permintaan_jenis_berkas_id');

            $table->unsignedSmallInteger('permintaan_tahun')->nullable();
            $table->string('permintaan_periode', 50)->nullable();

            $table->string('permintaan_judul', 255)->nullable();
            $table->string('permintaan_tombol', 100)->nullable();

            $table->text('permintaan_keterangan')->nullable();

            $table->dateTime('permintaan_mulai')->nullable();
            $table->dateTime('permintaan_expired')->nullable();

            $table->boolean('permintaan_status')->default(true);

            $table->timestamp('permintaan_created_at')->nullable();
            $table->timestamp('permintaan_updated_at')->nullable();

            $table->foreign('permintaan_jenis_berkas_id')
                ->references('jenis_berkas_id')
                ->on('samperin_jenis_berkas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->index(
                ['permintaan_jenis_berkas_id', 'permintaan_tahun'],
                'permintaan_jenis_tahun_idx'
            );

            $table->index(
                ['permintaan_status', 'permintaan_expired'],
                'permintaan_status_expired_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_permintaan_berkas');
    }
};