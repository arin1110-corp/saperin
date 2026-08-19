<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saperin_user_role', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained(
                    'saperin_user'
                )
                ->cascadeOnDelete();

            $table->foreignId('role_id')
                ->constrained(
                    'saperin_role'
                )
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'user_id',
                'role_id',
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saperin_user_role');
    }
};