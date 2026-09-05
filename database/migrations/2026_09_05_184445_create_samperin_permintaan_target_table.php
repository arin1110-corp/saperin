<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_permintaan_target', function (Blueprint $table) {
            $table->bigIncrements('target_id');

            $table->uuid('target_uid')->unique();

            // Relasi ke permintaan berkas
            $table->unsignedBigInteger('target_permintaan_id');

            // JENIS_KERJA
            $table->string('target_tipe', 30);

            // Jenis kerja yang menjadi target
            $table->unsignedBigInteger('target_jenis_kerja_id');

            // Folder Drive yang digunakan untuk jenis kerja tersebut
            $table->unsignedBigInteger('target_folder_id');

            $table->boolean('target_status')->default(true);

            $table->timestamp('target_created_at')->nullable();
            $table->timestamp('target_updated_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Foreign Key
            |--------------------------------------------------------------------------
            */

            $table->foreign('target_permintaan_id')
                ->references('permintaan_id')
                ->on('samperin_permintaan_berkas')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('target_jenis_kerja_id')
                ->references('jenis_kerja_id')
                ->on('samperin_jenis_kerja')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('target_folder_id', 'fk_target_folder')
                ->references('folder_id')
                ->on('samperin_folder')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('target_permintaan_id');
            $table->index('target_tipe');
            $table->index('target_jenis_kerja_id');
            $table->index('target_folder_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_permintaan_target');
    }
};