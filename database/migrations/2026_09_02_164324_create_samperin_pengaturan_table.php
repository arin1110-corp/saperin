<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_pengaturan', function (Blueprint $table) {
            $table->id('pengaturan_id');

            $table->uuid('pengaturan_uid')
                ->unique();

            $table->string('pengaturan_kode', 100)
                ->unique();

            $table->string('pengaturan_nama', 150);

            $table->text('pengaturan_nilai')
                ->nullable();

            $table->string('pengaturan_tipe', 30)
                ->default('text');

            $table->text('pengaturan_keterangan')
                ->nullable();

            $table->boolean('pengaturan_status')
                ->default(true);

            $table->timestamp('pengaturan_created_at')
                ->nullable();

            $table->timestamp('pengaturan_updated_at')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_pengaturan');
    }
};