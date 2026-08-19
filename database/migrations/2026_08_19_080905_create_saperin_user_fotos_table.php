<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saperin_user_foto', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained(
                    'saperin_user'
                )
                ->cascadeOnDelete();

            $table->string(
                'user_foto_nama',
                255
            );

            $table->string(
                'user_foto_path',
                500
            );

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saperin_user_foto');
    }
};