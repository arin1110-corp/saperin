<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('samperin_user', function (Blueprint $table) {
            /*
            |--------------------------------------------------------------------------
            | PRIMARY KEY
            |--------------------------------------------------------------------------
            */

            /*
             * user_id sengaja mempertahankan ID dari SADARIN.
             *
             * Contoh:
             * SADARIN user_id = 1919
             * SAMPERIN user_id = 1919
             */
            $table->bigIncrements('user_id');

            /*
            |--------------------------------------------------------------------------
            | USER UID
            |--------------------------------------------------------------------------
            */

            /*
             * UID internal SAMPERIN.
             * Berbeda dengan user_id lama dari SADARIN.
             */
            $table->char('user_uid', 36)->unique();

            /*
            |--------------------------------------------------------------------------
            | IDENTITAS PEGAWAI
            |--------------------------------------------------------------------------
            */

            /*
             * NIP unik.
             */
            $table->string('user_nip', 50)->nullable()->unique();

            /*
             * NIK unik.
             *
             * Nullable karena pada data tertentu
             * NIK mungkin belum tersedia.
             */
            $table->string('user_nik', 50)->nullable()->unique();

            $table->string('user_nama', 150);

            /*
            |--------------------------------------------------------------------------
            | GELAR
            |--------------------------------------------------------------------------
            */

            $table->string('user_gelardepan', 50)->nullable();

            $table->string('user_gelarbelakang', 100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | TEMPAT / TANGGAL LAHIR
            |--------------------------------------------------------------------------
            */

            $table->string('user_tempatlahir', 100)->nullable();

            $table->date('user_tgllahir')->nullable();

            /*
            |--------------------------------------------------------------------------
            | JENIS KELAMIN
            |--------------------------------------------------------------------------
            |
            | L = Laki-laki
            | P = Perempuan
            |
            */

            $table->char('user_jk', 1)->nullable();

            /*
            |--------------------------------------------------------------------------
            | MASTER KEPEGAWAIAN
            |--------------------------------------------------------------------------
            |
            | SENGAJA NULLABLE.
            |
            | Karena saat migrasi awal dari SADARIN,
            | master SAMPERIN belum tentu tersedia.
            |
            | Nanti setelah master selesai dimigrasikan,
            | field ini akan diisi dengan ID master SAMPERIN.
            |
            */

            $table->unsignedBigInteger('user_jabatan_id')->nullable();

            $table->unsignedBigInteger('user_bidang_id')->nullable();

            $table->unsignedBigInteger('user_golongan_id')->nullable();

            $table->unsignedBigInteger('user_eselon_id')->nullable();

            $table->unsignedBigInteger('user_pendidikan_id')->nullable();

            $table->unsignedBigInteger('user_jenis_kerja_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | DATA KEPEGAWAIAN
            |--------------------------------------------------------------------------
            */

            $table->date('user_tmt')->nullable();

            $table->date('user_spmt')->nullable();

            /*
            |--------------------------------------------------------------------------
            | NPWP
            |--------------------------------------------------------------------------
            */

            $table->string('user_npwp', 50)->nullable();

            /*
            |--------------------------------------------------------------------------
            | BPJS
            |--------------------------------------------------------------------------
            */

            $table->string('user_bpjs', 50)->nullable();

            /*
            |--------------------------------------------------------------------------
            | REKENING BPD
            |--------------------------------------------------------------------------
            */

            $table->string('user_norek_bpd', 100)->nullable()->unique();

            /*
            |--------------------------------------------------------------------------
            | KELAS JABATAN
            |--------------------------------------------------------------------------
            |
            | Berupa angka.
            |
            | Contoh:
            | 0
            | 1
            | 2
            | 8
            | 9
            |
            | Nilai 0 diperbolehkan.
            |
            */

            $table->string('user_kelasjabatan', 100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | JUMLAH TANGGUNGAN
            |--------------------------------------------------------------------------
            |
            | Berupa angka.
            |
            */

            $table->integer('user_jmltanggungan')->default(0);

            /*
            |--------------------------------------------------------------------------
            | KONTAK
            |--------------------------------------------------------------------------
            */

            $table->string('user_email', 150)->nullable()->unique();

            $table->string('user_notelp', 50)->nullable()->unique();

            $table->string('user_alamat', 255)->nullable();

            /*
            |--------------------------------------------------------------------------
            | LOKASI KERJA
            |--------------------------------------------------------------------------
            */

            $table->string('user_lokasikerja', 150)->nullable();

            /*
            |--------------------------------------------------------------------------
            | KETERANGAN
            |--------------------------------------------------------------------------
            */

            $table->string('user_keterangan', 255)->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS USER
            |--------------------------------------------------------------------------
            |
            | Tidak menggunakan tabel status.
            |
            | 1 = Aktif
            | 0 = Nonaktif
            |
            */

            $table->tinyInteger('user_status')->default(1);

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMP
            |--------------------------------------------------------------------------
            */

            $table->dateTime('user_created_at')->useCurrent();

            $table->dateTime('user_updated_at')->useCurrent()->useCurrentOnUpdate();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY - JABATAN
            |--------------------------------------------------------------------------
            */

            $table->foreign('user_jabatan_id')->references('jabatan_id')->on('samperin_jabatan')->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY - BIDANG
            |--------------------------------------------------------------------------
            */

            $table->foreign('user_bidang_id')->references('bidang_id')->on('samperin_bidang')->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY - GOLONGAN
            |--------------------------------------------------------------------------
            */

            $table->foreign('user_golongan_id')->references('golongan_id')->on('samperin_golongan')->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY - ESELON
            |--------------------------------------------------------------------------
            */

            $table->foreign('user_eselon_id')->references('eselon_id')->on('samperin_eselon')->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY - PENDIDIKAN
            |--------------------------------------------------------------------------
            */

            $table->foreign('user_pendidikan_id')->references('pendidikan_id')->on('samperin_pendidikan')->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY - JENIS KERJA
            |--------------------------------------------------------------------------
            */

            $table->foreign('user_jenis_kerja_id')->references('jenis_kerja_id')->on('samperin_jenis_kerja')->nullOnDelete();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DOWN
    |--------------------------------------------------------------------------
    */

    public function down(): void
    {
        Schema::dropIfExists('samperin_user');
    }
};