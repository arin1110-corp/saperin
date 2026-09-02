<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samperin_api', function (Blueprint $table) {
            $table->id('api_id');

            $table->uuid('api_uid')
                ->unique();

            $table->string('api_kode', 100)
                ->unique();

            $table->string('api_nama', 150);

            $table->text('api_url')
                ->nullable();

            $table->text('api_token')
                ->nullable();

            $table->boolean('api_status')
                ->default(true);

            $table->text('api_keterangan')
                ->nullable();

            $table->timestamp('api_created_at')
                ->nullable();

            $table->timestamp('api_updated_at')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samperin_api');
    }
};