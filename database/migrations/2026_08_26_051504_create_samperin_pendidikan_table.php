<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_pendidikan', function (Blueprint $table) {

            $table->bigIncrements('pendidikan_id');

            $table->char('pendidikan_uid', 36)
                ->unique();

            $table->string('pendidikan_kode', 20)
                ->unique();

            $table->string('pendidikan_nama', 100);

            $table->tinyInteger('pendidikan_status')
                ->default(1);

            $table->dateTime('pendidikan_created_at')
                ->useCurrent();

            $table->dateTime('pendidikan_updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_pendidikan');
    }
};