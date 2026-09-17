<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_peraturan_gaji_golongan', function (Blueprint $table) {

            $table->bigIncrements('peraturan_gaji_golongan_id');

            $table->char(
                'peraturan_gaji_golongan_uid',
                36
            );

            $table->unsignedBigInteger(
                'peraturan_gaji_id'
            );

            $table->unsignedBigInteger(
                'golongan_id'
            );

            $table->decimal(
                'peraturan_gaji_gaji_lama',
                15,
                2
            )->nullable();

            $table->decimal(
                'peraturan_gaji_gaji_baru',
                15,
                2
            );

            $table->tinyInteger(
                'peraturan_gaji_golongan_status'
            )->default(1);

            $table->timestamp(
                'peraturan_gaji_golongan_created_at'
            )->nullable();

            $table->timestamp(
                'peraturan_gaji_golongan_updated_at'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | UNIQUE UID
            |--------------------------------------------------------------------------
            */

            $table->unique(
                'peraturan_gaji_golongan_uid',
                'pg_gol_uid_unique'
            );

            /*
            |--------------------------------------------------------------------------
            | UNIQUE PERATURAN + GOLONGAN
            |--------------------------------------------------------------------------
            */

            $table->unique(
                [
                    'peraturan_gaji_id',
                    'golongan_id'
                ],
                'pg_gol_peraturan_unique'
            );

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign(
                'peraturan_gaji_id',
                'pg_gol_peraturan_fk'
            )
                ->references('peraturan_gaji_id')
                ->on('samperin_peraturan_gaji')
                ->cascadeOnDelete();

            $table->foreign(
                'golongan_id',
                'pg_gol_golongan_fk'
            )
                ->references('golongan_id')
                ->on('samperin_golongan')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'samperin_peraturan_gaji_golongan'
        );
    }
};