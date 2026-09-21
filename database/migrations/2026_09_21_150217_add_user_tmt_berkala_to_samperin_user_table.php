<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('samperin_user', function (Blueprint $table) {
            $table->date('user_tmt_berkala')
                ->nullable()
                ->after('user_tmt');
        });

        // Sementara nilai TMT berkala disamakan dengan TMT utama
        DB::table('samperin_user')
            ->whereNotNull('user_tmt')
            ->update([
                'user_tmt_berkala' => DB::raw('user_tmt'),
            ]);
    }

    public function down(): void
    {
        Schema::table('samperin_user', function (Blueprint $table) {
            $table->dropColumn('user_tmt_berkala');
        });
    }
};