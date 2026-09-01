<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_bidang', function (Blueprint $table) {

            $table->bigIncrements('bidang_id');

            $table->char('bidang_uid', 36)
                ->unique();

            $table->string('bidang_kode', 20)
                ->unique();

            $table->string('bidang_nama', 150);

            $table->tinyInteger('bidang_status')
                ->default(1);

            $table->dateTime('bidang_created_at')
                ->useCurrent();

            $table->dateTime('bidang_updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_bidang');
    }
};