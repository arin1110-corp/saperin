<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('samperin_user', 'user_password')) {
            Schema::table('samperin_user', function (Blueprint $table) {
                $table->string('user_password', 255)
                    ->nullable()
                    ->after('user_email');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('samperin_user', 'user_password')) {
            Schema::table('samperin_user', function (Blueprint $table) {
                $table->dropColumn('user_password');
            });
        }
    }
};