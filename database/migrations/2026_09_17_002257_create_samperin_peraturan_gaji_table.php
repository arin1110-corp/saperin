<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_peraturan_gaji', function (Blueprint $table) {

            $table->bigIncrements('peraturan_gaji_id');

            $table->char('peraturan_gaji_uid', 36);

            $table->string('peraturan_gaji_nama', 255);

            $table->string('peraturan_gaji_nomor', 100);

            $table->year('peraturan_gaji_tahun');

            $table->date('peraturan_gaji_tanggal')->nullable();

            $table->tinyInteger('peraturan_gaji_status')
                ->default(1);

            $table->timestamp('peraturan_gaji_created_at')
                ->nullable();

            $table->timestamp('peraturan_gaji_updated_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | UNIQUE
            |--------------------------------------------------------------------------
            */

            $table->unique(
                'peraturan_gaji_uid',
                'pg_uid_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_peraturan_gaji');
    }
};