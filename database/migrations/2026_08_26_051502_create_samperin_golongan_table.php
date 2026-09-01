<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_golongan', function (Blueprint $table) {

            $table->bigIncrements('golongan_id');

            $table->char('golongan_uid', 36)
                ->unique();

            $table->string('golongan_kode', 20)
                ->unique();

            $table->string('golongan_nama', 100);

            $table->string('golongan_pangkat', 100)
                ->nullable();

            $table->tinyInteger('golongan_status')
                ->default(1);

            $table->dateTime('golongan_created_at')
                ->useCurrent();

            $table->dateTime('golongan_updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_golongan');
    }
};