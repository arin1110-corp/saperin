<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_kgb_batch', function (Blueprint $table) {

            $table->bigIncrements('kgb_batch_id');

            $table->char(
                'kgb_batch_uid',
                36
            );

            $table->string(
                'kgb_batch_nama',
                255
            );

            $table->unsignedBigInteger(
                'kgb_batch_peraturan_gaji_id'
            );

            $table->unsignedBigInteger(
                'kgb_batch_pejabat_id'
            );

            $table->string('kgb_batch_oleh_pejabat', 255)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | TANGGAL SURAT
            |--------------------------------------------------------------------------
            */

            $table->date(
                'kgb_batch_tanggal'
            );

            /*
            |--------------------------------------------------------------------------
            | MULAI BERLAKU
            |--------------------------------------------------------------------------
            */

            $table->date(
                'kgb_batch_mulai_berlaku'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | FORMAT NOMOR
            |--------------------------------------------------------------------------
            |
            | Contoh:
            | KGB/001/DISBUD/2027
            |
            | Admin memasukkan:
            |
            | KGB/{nomor}/DISBUD/2027
            |
            */

            $table->string(
                'kgb_batch_nomor_format',
                255
            );

            /*
            |--------------------------------------------------------------------------
            | NOMOR AWAL
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger(
                'kgb_batch_nomor_awal'
            );

            /*
            |--------------------------------------------------------------------------
            | NOMOR AKHIR
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger(
                'kgb_batch_nomor_akhir'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->tinyInteger(
                'kgb_batch_status'
            )->default(1);

            $table->timestamp(
                'kgb_batch_created_at'
            )->nullable();

            $table->timestamp(
                'kgb_batch_updated_at'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | UNIQUE UID
            |--------------------------------------------------------------------------
            */

            $table->unique(
                'kgb_batch_uid',
                'kgb_batch_uid_unique'
            );

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign(
                'kgb_batch_peraturan_gaji_id',
                'kgb_batch_peraturan_fk'
            )
                ->references('peraturan_gaji_id')
                ->on('samperin_peraturan_gaji')
                ->restrictOnDelete();

            $table->foreign(
                'kgb_batch_pejabat_id',
                'kgb_batch_pejabat_fk'
            )
                ->references('user_id')
                ->on('samperin_user')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'samperin_kgb_batch'
        );
    }
};