<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_eselon', function (Blueprint $table) {

            $table->bigIncrements('eselon_id');

            $table->char('eselon_uid', 36)
                ->unique();

            $table->string('eselon_kode', 20)
                ->unique();

            $table->string('eselon_nama', 100);

            $table->tinyInteger('eselon_status')
                ->default(1);

            $table->dateTime('eselon_created_at')
                ->useCurrent();

            $table->dateTime('eselon_updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_eselon');
    }
};