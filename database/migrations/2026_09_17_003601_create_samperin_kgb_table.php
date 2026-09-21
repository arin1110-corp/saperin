<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('samperin_kgb', function (Blueprint $table) {
            $table->bigIncrements('kgb_id');

            $table->char('kgb_uid', 36);

            /*
            |--------------------------------------------------------------------------
            | BATCH
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('kgb_batch_id');

            /*
            |--------------------------------------------------------------------------
            | PEGAWAI
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('kgb_user_id');

            /*
            |--------------------------------------------------------------------------
            | GOLONGAN
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('kgb_golongan_id');

            /*
            |--------------------------------------------------------------------------
            | SURAT
            |--------------------------------------------------------------------------
            */

            $table->string('kgb_nomor_surat', 255);

            $table->date('kgb_tanggal_surat');

            /*
            |--------------------------------------------------------------------------
            | PEJABAT
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('kgb_pejabat_id');

            /*
|--------------------------------------------------------------------------
| MASA KERJA
|--------------------------------------------------------------------------
|
| Masa kerja merupakan data yang melekat pada KGB.
| Nilai gaji tidak disimpan di tabel KGB,
| tetapi diambil dari peraturan gaji berdasarkan
| peraturan yang digunakan oleh batch dan golongan pegawai.
|
|--------------------------------------------------------------------------
*/
            $table->unsignedInteger('kgb_masa_kerja_tahun')->default(0);

            $table->unsignedInteger('kgb_masa_kerja_bulan')->default(0);

            /*
            |--------------------------------------------------------------------------
            | MULAI BERLAKU
            |--------------------------------------------------------------------------
            */

            $table->date('kgb_mulai_berlaku')->nullable();

            /*
            |--------------------------------------------------------------------------
            | NOMOR SK
            |--------------------------------------------------------------------------
            |
            | Diisi oleh pegawai.
            |
            */

            $table->string('kgb_nomor_sk', 255)->nullable();

            /*
            |--------------------------------------------------------------------------
            | TANGGAL SK
            |--------------------------------------------------------------------------
            |
            | Disiapkan untuk kebutuhan dokumen.
            |
            */

            $table->date('kgb_tanggal_sk')->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->tinyInteger('kgb_status')->default(1);

            $table->timestamp('kgb_created_at')->nullable();

            $table->timestamp('kgb_updated_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | UNIQUE
            |--------------------------------------------------------------------------
            */

            $table->unique('kgb_uid', 'kgb_uid_unique');

            $table->unique(['kgb_batch_id', 'kgb_user_id'], 'kgb_batch_user_unique');

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign('kgb_batch_id', 'kgb_batch_batch_fk')->references('kgb_batch_id')->on('samperin_kgb_batch')->cascadeOnDelete();

            $table->foreign('kgb_user_id', 'kgb_user_fk')->references('user_id')->on('samperin_user')->restrictOnDelete();

            $table->foreign('kgb_golongan_id', 'kgb_golongan_fk')->references('golongan_id')->on('samperin_golongan')->restrictOnDelete();

            $table->foreign('kgb_pejabat_id', 'kgb_pejabat_fk')->references('user_id')->on('samperin_user')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_kgb');
    }
};