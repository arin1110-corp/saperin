<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_jenis_berkas', function (Blueprint $table) {
            $table->bigIncrements('jenis_berkas_id');
            $table->uuid('jenis_berkas_uid')->unique();

            $table->unsignedBigInteger('jenis_berkas_kategori_id');

            $table->string('jenis_berkas_kode', 100)->unique();
            $table->string('jenis_berkas_nama', 200);

            // tetap, tahunan, periodik
            $table->string('jenis_berkas_sifat', 30)->default('tetap');

            $table->string('jenis_berkas_format', 100)->nullable();
            $table->unsignedInteger('jenis_berkas_maksimal_mb')->nullable();

            $table->text('jenis_berkas_keterangan')->nullable();

            $table->boolean('jenis_berkas_status')->default(true);

            $table->timestamp('jenis_berkas_created_at')->nullable();
            $table->timestamp('jenis_berkas_updated_at')->nullable();

            $table->foreign('jenis_berkas_kategori_id')
                ->references('kategori_id')
                ->on('samperin_berkas_kategori')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->index('jenis_berkas_kategori_id');
            $table->index('jenis_berkas_status');
            $table->index('jenis_berkas_sifat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_jenis_berkas');
    }
};