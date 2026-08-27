<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('samperin_user', function (Blueprint $table) {
            $table->string('user_kelasjabatan', 100)->nullable()->after('user_eselon_id');

            $table->string('user_jmltanggungan', 10)->nullable()->after('user_keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('samperin_user', function (Blueprint $table) {
            $table->dropColumn(['user_kelasjabatan', 'user_jmltanggungan']);
        });
    }
};