<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_user', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PRIMARY KEY + UID
            |--------------------------------------------------------------------------
            */

            $table->bigIncrements('user_id');

            $table->char('user_uid', 36)
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | IDENTITAS
            |--------------------------------------------------------------------------
            */

            $table->string('user_nip', 50)
                ->unique();

            $table->string('user_nik', 50)
                ->nullable()
                ->unique();

            $table->string('user_nama', 150);

            $table->string('user_gelardepan', 50)
                ->nullable();

            $table->string('user_gelarbelakang', 50)
                ->nullable();

            $table->string('user_tempatlahir', 100)
                ->nullable();

            $table->date('user_tgllahir')
                ->nullable();

            $table->char('user_jk', 1)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | REFERENSI
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('user_jabatan_id');

            $table->unsignedBigInteger('user_bidang_id');

            $table->unsignedBigInteger('user_golongan_id');

            $table->unsignedBigInteger('user_eselon_id');

            $table->unsignedBigInteger('user_pendidikan_id');

            $table->unsignedBigInteger('user_status_id');

            $table->unsignedBigInteger('user_jenis_kerja_id');


            /*
            |--------------------------------------------------------------------------
            | DATA KEPEGAWAIAN
            |--------------------------------------------------------------------------
            */

            $table->date('user_tmt')
                ->nullable();

            $table->date('user_spmt')
                ->nullable();

            $table->string('user_npwp', 50)
                ->nullable();

            $table->string('user_bpjs', 50)
                ->nullable();

            $table->string('user_norek_bpd', 100)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | KONTAK
            |--------------------------------------------------------------------------
            */

            $table->string('user_email', 150)
                ->nullable();

            $table->string('user_notelp', 50)
                ->nullable();

            $table->string('user_alamat', 255)
                ->nullable();

            $table->string('user_lokasikerja', 150)
                ->nullable();

            $table->string('user_keterangan', 255)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->tinyInteger('user_status')
                ->default(1);


            /*
            |--------------------------------------------------------------------------
            | TIMESTAMP
            |--------------------------------------------------------------------------
            */

            $table->dateTime('user_created_at')
                ->useCurrent();

            $table->dateTime('user_updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();


            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign('user_jabatan_id')
                ->references('jabatan_id')
                ->on('samperin_jabatan');

            $table->foreign('user_bidang_id')
                ->references('bidang_id')
                ->on('samperin_bidang');

            $table->foreign('user_golongan_id')
                ->references('golongan_id')
                ->on('samperin_golongan');

            $table->foreign('user_eselon_id')
                ->references('eselon_id')
                ->on('samperin_eselon');

            $table->foreign('user_pendidikan_id')
                ->references('pendidikan_id')
                ->on('samperin_pendidikan');

            $table->foreign('user_status_id')
                ->references('status_pegawai_id')
                ->on('samperin_status_pegawai');

            $table->foreign('user_jenis_kerja_id')
                ->references('jenis_kerja_id')
                ->on('samperin_jenis_kerja');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_user');
    }
};