<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_berkas_kategori', function (Blueprint $table) {
            $table->bigIncrements('kategori_id');
            $table->uuid('kategori_uid')->unique();

            $table->string('kategori_kode', 100)->unique();
            $table->string('kategori_nama', 150);
            $table->text('kategori_keterangan')->nullable();

            $table->boolean('kategori_status')->default(true);

            $table->timestamp('kategori_created_at')->nullable();
            $table->timestamp('kategori_updated_at')->nullable();

            $table->index('kategori_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_berkas_kategori');
    }
};