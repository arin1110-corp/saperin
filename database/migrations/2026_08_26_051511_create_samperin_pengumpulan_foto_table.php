<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'samperin_pengumpulan_foto',
            function (Blueprint $table) {

                $table->bigIncrements(
                    'pengumpulan_foto_id'
                );

                $table->char(
                    'pengumpulan_foto_uid',
                    36
                )->unique();

                $table->char(
                    'pengumpulan_foto_user_uid',
                    36
                );

                $table->string(
                    'pengumpulan_foto_file',
                    255
                );

                $table->string(
                    'pengumpulan_foto_nama',
                    255
                );

                $table->string(
                    'pengumpulan_foto_mime',
                    100
                );

                $table->unsignedBigInteger(
                    'pengumpulan_foto_size'
                );

                $table->date(
                    'pengumpulan_foto_tanggal'
                );

                $table->string(
                    'pengumpulan_foto_keterangan',
                    255
                )->nullable();

                $table->dateTime(
                    'pengumpulan_foto_created_at'
                )->useCurrent();

                $table->dateTime(
                    'pengumpulan_foto_updated_at'
                )->useCurrent()
                 ->useCurrentOnUpdate();


                $table->foreign(
                    'pengumpulan_foto_user_uid'
                )
                ->references('user_uid')
                ->on('samperin_user')
                ->cascadeOnDelete();

            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'samperin_pengumpulan_foto'
        );
    }
};