<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('samperin_folder', function (Blueprint $table) {
            $table->id('folder_id');

            $table->uuid('folder_uid')->unique();

            $table->string('folder_kode', 100);

            $table->string('folder_nama', 150);

            /*
             * Jenis penggunaan folder
             * Contoh:
             * foto
             * berkas
             * dokumen
             */
            $table->string('folder_jenis', 50);

            /*
             * ID master jenis kerja
             *
             * NULL = berlaku untuk semua jenis kerja
             */
            $table->unsignedBigInteger('folder_jenis_kerja_id')->nullable();

            $table->string('folder_prefix', 50)->nullable();

            /*
             * Google Drive Folder ID
             * ID ini diambil dari URL folder Google Drive.
             */
            $table->string('folder_drive_id', 255);

            $table->text('folder_keterangan')->nullable();

            $table->boolean('folder_status')->default(true);

            $table->timestamp('folder_created_at')->nullable();

            $table->timestamp('folder_updated_at')->nullable();

            /*
             * Satu kode + jenis kerja hanya boleh
             * memiliki satu konfigurasi folder.
             */
            $table->unique(['folder_kode', 'folder_jenis_kerja_id'], 'folder_kode_jenis_kerja_unique');

            $table->index('folder_jenis');

            $table->index('folder_jenis_kerja_id');

            $table->index('folder_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_folder');
    }
};