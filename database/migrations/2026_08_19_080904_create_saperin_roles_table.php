<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saperin_role', function (Blueprint $table) {

            $table->id();

            $table->uuid('role_uid')
                ->unique();

            $table->string('role_nama', 100);

            $table->string('role_slug', 100)
                ->unique();

            $table->text('role_deskripsi')
                ->nullable();

            $table->boolean('role_status')
                ->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saperin_role');
    }
};