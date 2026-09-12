<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('samperin_user', function (Blueprint $table) {

            $table->string('user_reset_token', 64)
                ->nullable()
                ->after('user_password');

            $table->timestamp('user_reset_expired')
                ->nullable()
                ->after('user_reset_token');

            $table->index('user_reset_token');
        });
    }

    public function down(): void
    {
        Schema::table('samperin_user', function (Blueprint $table) {

            $table->dropIndex([
                'user_reset_token'
            ]);

            $table->dropColumn([
                'user_reset_token',
                'user_reset_expired',
            ]);
        });
    }
};