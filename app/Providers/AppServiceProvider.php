<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SamperinBerkasKategori;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer(
            'dashboard.partials.sidebar',
            function ($view) {

                $kategoriBerkas =
                    SamperinBerkasKategori::query()
                    ->with([
                        'jenisBerkas' => function ($jenisQuery) {

                            $jenisQuery
                                ->where(
                                    'jenis_berkas_status',
                                    true
                                )
                                ->with([
                                    'permintaan' => function ($permintaanQuery) {

                                        $permintaanQuery
                                            ->where(
                                                'permintaan_status',
                                                true
                                            )
                                            ->orderByDesc(
                                                'permintaan_tahun'
                                            )
                                            ->orderBy(
                                                'permintaan_judul'
                                            );
                                    },
                                ])
                                ->orderBy(
                                    'jenis_berkas_nama'
                                );
                        },
                    ])
                    ->where(
                        'kategori_status',
                        true
                    )
                    ->orderBy(
                        'kategori_nama'
                    )
                    ->get();

                $view->with(
                    'kategoriBerkas',
                    $kategoriBerkas
                );
            }
        );
    }
}