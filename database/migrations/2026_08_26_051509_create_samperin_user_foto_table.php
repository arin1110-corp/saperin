<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_user_foto', function (Blueprint $table) {

            $table->bigIncrements('user_foto_id');

            $table->char('user_foto_uid', 36)
                ->unique();

            $table->char('user_foto_user_uid', 36);

            $table->string('user_foto_file', 255);

            $table->string('user_foto_nama', 255);

            $table->string('user_foto_mime', 100);

            $table->unsignedBigInteger('user_foto_size');

            $table->date('user_foto_tanggal');

            $table->string('user_foto_keterangan', 255)
                ->nullable();

            $table->dateTime('user_foto_created_at')
                ->useCurrent();

            $table->dateTime('user_foto_updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();


            $table->foreign('user_foto_user_uid')
                ->references('user_uid')
                ->on('samperin_user')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_user_foto');
    }
};