<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_jabatan', function (Blueprint $table) {

            $table->bigIncrements('jabatan_id');

            $table->char('jabatan_uid', 36)
                ->unique();

            $table->string('jabatan_kode', 20)
                ->unique();

            $table->string('jabatan_nama', 150);

            $table->string('jabatan_kategori', 150)
                ->nullable();

            $table->tinyInteger('jabatan_status')
                ->default(1);

            $table->dateTime('jabatan_created_at')
                ->useCurrent();

            $table->dateTime('jabatan_updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_jabatan');
    }
};