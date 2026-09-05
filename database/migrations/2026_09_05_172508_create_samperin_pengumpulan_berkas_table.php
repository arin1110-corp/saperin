<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_pengumpulan_berkas', function (Blueprint $table) {
            $table->bigIncrements('pengumpulan_berkas_id');
            $table->uuid('pengumpulan_berkas_uid')->unique();

            $table->char('pengumpulan_berkas_user_uid', 36);

            $table->unsignedBigInteger('pengumpulan_berkas_permintaan_id');

            // Link / referensi file yang sudah ada di Drive
            $table->string('pengumpulan_berkas_file', 500);

            $table->string('pengumpulan_berkas_nama', 255)->nullable();
            $table->string('pengumpulan_berkas_mime', 100)->nullable();
            $table->unsignedBigInteger('pengumpulan_berkas_size')->nullable();

            $table->dateTime('pengumpulan_berkas_tanggal')->nullable();

            // terkirim, diverifikasi, ditolak
            $table->string('pengumpulan_berkas_status', 30)
                ->default('terkirim');

            $table->dateTime('pengumpulan_berkas_verified_at')->nullable();
            $table->char('pengumpulan_berkas_verified_by', 36)->nullable();

            $table->text('pengumpulan_berkas_keterangan')->nullable();

            // Rekam jejak migrasi dari SADARIN
            $table->string('pengumpulan_berkas_sumber', 30)->nullable();
            $table->unsignedBigInteger('pengumpulan_berkas_sumber_id')->nullable();

            $table->timestamp('pengumpulan_berkas_created_at')->nullable();
            $table->timestamp('pengumpulan_berkas_updated_at')->nullable();

            // Foreign key
            $table->foreign(
                'pengumpulan_berkas_user_uid',
                'fk_pengumpulan_user'
            )
                ->references('user_uid')
                ->on('samperin_user')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign(
                'pengumpulan_berkas_permintaan_id',
                'fk_pengumpulan_permintaan'
            )
                ->references('permintaan_id')
                ->on('samperin_permintaan_berkas')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Satu pegawai hanya punya satu file
            // untuk satu permintaan berkas.
            $table->unique(
                [
                    'pengumpulan_berkas_user_uid',
                    'pengumpulan_berkas_permintaan_id'
                ],
                'pengumpulan_user_permintaan_unique'
            );

            $table->index(
                [
                    'pengumpulan_berkas_user_uid',
                    'pengumpulan_berkas_permintaan_id'
                ],
                'pengumpulan_user_permintaan_idx'
            );

            $table->index(
                [
                    'pengumpulan_berkas_permintaan_id',
                    'pengumpulan_berkas_status'
                ],
                'pengumpulan_permintaan_status_idx'
            );

            $table->index(
                [
                    'pengumpulan_berkas_sumber',
                    'pengumpulan_berkas_sumber_id'
                ],
                'pengumpulan_sumber_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_pengumpulan_berkas');
    }
};