@extends('dashboard.layouts.app')

@section('title', 'Dashboard Admin')

@section('breadcrumb', 'Administrator')

@section('header-title', 'Administrasi Sistem')


@section('page-style')

    <style>
        /* =====================================================
           HERO
        ===================================================== */

        .admin-hero {

            position: relative;

            overflow: hidden;

            min-height: 205px;

            padding: 35px 38px;

            border-radius: 17px;

            background:
                linear-gradient(120deg,
                    #14223b,
                    #1d3355);

            color: #fff;

            box-shadow:
                0 12px 30px rgba(20, 35, 60, .12);

        }


        .admin-hero::before {

            content: "";

            position: absolute;

            width: 320px;
            height: 320px;

            border-radius: 50%;

            border:
                1px solid rgba(255, 255, 255, .06);

            right: -95px;
            top: -140px;

        }


        .admin-hero::after {

            content: "";

            position: absolute;

            width: 200px;
            height: 200px;

            border-radius: 50%;

            border:
                27px solid rgba(255, 255, 255, .025);

            right: 70px;
            bottom: -140px;

        }


        .admin-hero-content {

            position: relative;

            z-index: 2;

            max-width: 800px;

        }


        .admin-hero-label {

            font-size: 10px;

            text-transform: uppercase;

            letter-spacing: 1.5px;

            color:
                rgba(255, 255, 255, .42);

        }


        .admin-hero h1 {

            margin:
                10px 0 9px;

            font-size: 34px;

            line-height: 1.2;

            letter-spacing: -1px;

        }


        .admin-hero h1 span {

            color: #eb974e;

        }


        .admin-hero p {

            margin: 0;

            font-size: 13px;

            line-height: 1.75;

            color:
                rgba(255, 255, 255, .58);

        }


        .admin-hero-badge {

            position: absolute;

            right: 30px;
            bottom: 29px;

            z-index: 3;

            padding:
                9px 12px;

            border-radius: 8px;

            background:
                rgba(255, 255, 255, .08);

            color:
                rgba(255, 255, 255, .68);

            font-size: 9px;

        }


        .admin-hero-badge i {

            color: #e9984e;

        }


        /* =====================================================
           STATS
        ===================================================== */

        .admin-stats {

            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 16px;

            margin-top: 19px;

        }


        .admin-stat {

            background: #fff;

            border:
                1px solid #e7eaf0;

            border-radius: 14px;

            padding: 20px;

            display: flex;

            align-items: center;

            gap: 14px;

            box-shadow:
                0 5px 15px rgba(20, 30, 50, .025);

        }


        .admin-stat-icon {

            width: 49px;
            height: 49px;

            border-radius: 11px;

            display: flex;

            align-items: center;

            justify-content: center;

            flex-shrink: 0;

            font-size: 18px;

        }


        .stat-orange {

            background: #fff0e3;

            color: #c66d29;

        }


        .stat-green {

            background: #eaf7ef;

            color: #318b59;

        }


        .stat-red {

            background: #fff0f0;

            color: #d45a5a;

        }


        .stat-blue {

            background: #edf3ff;

            color: #4d76c5;

        }


        .admin-stat-label {

            font-size: 9px;

            color: #969eaa;

            margin-bottom: 6px;

        }


        .admin-stat-number {

            font-size: 27px;

            font-weight: 800;

            line-height: 1;

            color: #1b263b;

        }


        /* =====================================================
           GRID
        ===================================================== */

        .admin-dashboard-grid {

            display: grid;

            grid-template-columns:
                minmax(0, 1.6fr) minmax(280px, .9fr);

            gap: 19px;

            margin-top: 19px;

        }


        .admin-panel {

            background: #fff;

            border:
                1px solid #e7eaf0;

            border-radius: 14px;

            overflow: hidden;

            box-shadow:
                0 5px 15px rgba(20, 30, 50, .025);

        }


        .admin-panel-header {

            padding:
                19px 21px;

            border-bottom:
                1px solid #eef0f3;

            display: flex;

            align-items: center;

            justify-content: space-between;

        }


        .admin-panel-title {

            margin: 0;

            font-size: 15px;

            font-weight: 750;

        }


        .admin-panel-description {

            margin:
                5px 0 0;

            font-size: 10px;

            color: #969eaa;

        }


        .admin-panel-link {

            font-size: 9px;

            color: #c66d29;

            font-weight: 700;

        }


        /* =====================================================
           EMPLOYEE
        ===================================================== */

        .admin-employee-list {

            padding:
                3px 21px 12px;

        }


        .admin-employee {

            display: flex;

            align-items: center;

            gap: 13px;

            min-height: 67px;

            border-bottom:
                1px solid #f0f2f5;

        }


        .admin-employee:last-child {

            border-bottom: 0;

        }


        .admin-employee-avatar {

            width: 41px;
            height: 41px;

            border-radius: 10px;

            background: #f2f4f7;

            color: #697386;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 12px;

            font-weight: 800;

            flex-shrink: 0;

        }


        .admin-employee-info {

            flex: 1;

            min-width: 0;

        }


        .admin-employee-info strong {

            display: block;

            font-size: 12px;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

        }


        .admin-employee-info span {

            display: block;

            margin-top: 4px;

            color: #969eaa;

            font-size: 9px;

        }


        .employee-status {

            padding:
                6px 9px;

            border-radius: 6px;

            font-size: 8px;

            font-weight: 700;

        }


        .employee-active {

            background: #eaf7ef;

            color: #318957;

        }


        .employee-inactive {

            background: #fff0f0;

            color: #d45a5a;

        }


        /* =====================================================
           QUICK
        ===================================================== */

        .admin-quick {

            padding: 14px;

        }


        .admin-quick-link {

            display: flex;

            align-items: center;

            gap: 11px;

            padding: 13px;

            margin-bottom: 7px;

            border:
                1px solid #edf0f3;

            border-radius: 10px;

            background: #fafbfc;

        }


        .admin-quick-link:last-child {

            margin-bottom: 0;

        }


        .admin-quick-link:hover {

            background: #fff9f4;

            border-color: #efd4bd;

        }


        .admin-quick-icon {

            width: 40px;
            height: 40px;

            border-radius: 9px;

            background: #fff0e3;

            color: #c66d29;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 16px;

        }


        .admin-quick-text {

            flex: 1;

        }


        .admin-quick-text strong {

            display: block;

            font-size: 12px;

        }


        .admin-quick-text span {

            display: block;

            margin-top: 4px;

            font-size: 9px;

            color: #969eaa;

        }


        .admin-quick-arrow {

            color: #b2bac5;

            font-size: 10px;

        }


        /* =====================================================
           SYSTEM INFO
        ===================================================== */

        .admin-info {

            margin:
                0 14px 14px;

            padding: 15px;

            border-radius: 10px;

            background: #fff8f1;

            border:
                1px solid #f2dfcd;

        }


        .admin-info-icon {

            width: 31px;
            height: 31px;

            border-radius: 8px;

            background: #fff;

            color: #c66d29;

            display: flex;

            align-items: center;

            justify-content: center;

            margin-bottom: 8px;

        }


        .admin-info strong {

            display: block;

            font-size: 12px;

            color: #99501d;

        }


        .admin-info p {

            margin:
                5px 0 0;

            font-size: 9px;

            line-height: 1.6;

            color: #9b8675;

        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1050px) {

            .admin-stats {

                grid-template-columns:
                    repeat(2, 1fr);

            }

            .admin-dashboard-grid {

                grid-template-columns: 1fr;

            }

        }


        @media (max-width: 600px) {

            .admin-stats {

                grid-template-columns: 1fr;

            }

            .admin-hero {

                padding: 25px;

            }

            .admin-hero h1 {

                font-size: 23px;

            }

            .admin-hero p {

                font-size: 11px;

            }

            .admin-hero-badge {

                position: static;

                display: inline-block;

                margin-top: 15px;

            }

        }
    </style>

@endsection


@section('content')

    <div class="admin-content">


        {{-- HERO --}}

        <section class="admin-hero">


            <div class="admin-hero-content">


                <div class="admin-hero-label">

                    SAMPERIN · ADMINISTRATOR

                </div>


                <h1>

                    Halo,

                    <span>

                        {{ session('user_info')->user_nama ?? (session('user')->user_nama ?? 'Administrator') }}

                    </span>

                </h1>


                <p>

                    Kelola data pegawai, administrasi
                    kepegawaian, dan sistem internal
                    Dinas Kebudayaan Provinsi Bali
                    dari satu dashboard.

                </p>


                <div class="admin-hero-badge">

                    <i class="bi bi-shield-check"></i>

                    Administrator Aktif

                </div>


            </div>


        </section>



        {{-- STATS --}}

        <section class="admin-stats">


            <div class="admin-stat">

                <div class="admin-stat-icon stat-orange">

                    <i class="bi bi-people-fill"></i>

                </div>


                <div>

                    <div class="admin-stat-label">
                        Total Pegawai
                    </div>


                    <div class="admin-stat-number">

                        {{ number_format($totalPegawai ?? 0, 0, ',', '.') }}

                    </div>

                </div>

            </div>



            <div class="admin-stat">

                <div class="admin-stat-icon stat-green">

                    <i class="bi bi-person-check-fill"></i>

                </div>


                <div>

                    <div class="admin-stat-label">
                        Pegawai Aktif
                    </div>


                    <div class="admin-stat-number">

                        {{ number_format($pegawaiAktif ?? 0, 0, ',', '.') }}

                    </div>

                </div>

            </div>



            <div class="admin-stat">

                <div class="admin-stat-icon stat-red">

                    <i class="bi bi-person-x-fill"></i>

                </div>


                <div>

                    <div class="admin-stat-label">
                        Pegawai Nonaktif
                    </div>


                    <div class="admin-stat-number">

                        {{ number_format($pegawaiNonaktif ?? 0, 0, ',', '.') }}

                    </div>

                </div>

            </div>



            @php

                $total = (int) ($totalPegawai ?? 0);

                $aktif = (int) ($pegawaiAktif ?? 0);

                $persentase = $total > 0 ? round(($aktif / $total) * 100) : 0;
            @endphp


            <div class="admin-stat">

                <div class="admin-stat-icon stat-blue">

                    <i class="bi bi-graph-up-arrow"></i>

                </div>


                <div>

                    <div class="admin-stat-label">
                        Persentase Aktif
                    </div>


                    <div class="admin-stat-number">

                        {{ $persentase }}%

                    </div>

                </div>

            </div>


        </section>



        {{-- CONTENT GRID --}}

        <section class="admin-dashboard-grid">


            {{-- PEGAWAI TERBARU --}}

            <div class="admin-panel">


                <div class="admin-panel-header">


                    <div>

                        <h2 class="admin-panel-title">

                            Pegawai Terbaru

                        </h2>


                        <p class="admin-panel-description">

                            Data pegawai terakhir
                            yang tersedia di SAMPERIN

                        </p>

                    </div>


                    @if (\Illuminate\Support\Facades\Route::has('kepeg.dashboard'))
                        <a href="{{ route('kepeg.dashboard') }}" class="admin-panel-link">

                            Lihat Semua

                            <i class="bi bi-arrow-right"></i>

                        </a>
                    @endif


                </div>



                <div class="admin-employee-list">


                    @forelse($pegawaiTerbaru ?? []
                        as $pegawai)
                        @php

                            $nama = $pegawai->user_nama ?? 'Tanpa Nama';

                            $initial = strtoupper(substr(trim($nama), 0, 1));

                        @endphp


                        <div class="admin-employee">


                            <div class="admin-employee-avatar">

                                {{ $initial }}

                            </div>


                            <div class="admin-employee-info">

                                <strong>

                                    {{ $nama }}

                                </strong>


                                <span>

                                    NIP.
                                    {{ $pegawai->user_nip ?? '-' }}

                                </span>

                            </div>


                            @if ((int) ($pegawai->user_status ?? 0) === 1)
                                <span
                                    class="
                                    employee-status
                                    employee-active
                                ">

                                    Aktif

                                </span>
                            @else
                                <span
                                    class="
                                    employee-status
                                    employee-inactive
                                ">

                                    Nonaktif

                                </span>
                            @endif


                        </div>


                    @empty


                        <div
                            style="
                            padding:40px 10px;
                            text-align:center;
                            color:#9aa2ae;
                            font-size:10px;
                        ">

                            <i class="bi bi-people"
                                style="
                                display:block;
                                font-size:29px;
                                margin-bottom:9px;
                            "></i>

                            Belum ada data pegawai.

                        </div>
                    @endforelse


                </div>


            </div>



            {{-- RIGHT COLUMN --}}

            <div>


                <div class="admin-panel">


                    <div class="admin-panel-header">

                        <div>

                            <h2 class="admin-panel-title">
                                Akses Cepat
                            </h2>

                            <p class="admin-panel-description">
                                Administrasi SAMPERIN
                            </p>

                        </div>

                    </div>


                    <div class="admin-quick">


                        @if (\Illuminate\Support\Facades\Route::has('kepeg.dashboard'))
                            <a href="{{ route('kepeg.dashboard') }}" class="admin-quick-link">

                                <div class="admin-quick-icon">

                                    <i class="bi bi-person-vcard"></i>

                                </div>


                                <div class="admin-quick-text">

                                    <strong>
                                        Data Pegawai
                                    </strong>

                                    <span>
                                        Kelola data pegawai
                                    </span>

                                </div>


                                <i
                                    class="
                                    bi
                                    bi-chevron-right
                                    admin-quick-arrow
                                "></i>

                            </a>
                        @endif



                        @if (\Illuminate\Support\Facades\Route::has('kepeg.pegawai.import'))
                            <a href="{{ route('kepeg.pegawai.import') }}" class="admin-quick-link">

                                <div class="admin-quick-icon">

                                    <i class="bi bi-database-add"></i>

                                </div>


                                <div class="admin-quick-text">

                                    <strong>
                                        Import Data
                                    </strong>

                                    <span>
                                        Import master kepegawaian
                                    </span>

                                </div>


                                <i
                                    class="
                                    bi
                                    bi-chevron-right
                                    admin-quick-arrow
                                "></i>

                            </a>
                        @endif



                        <a href="#" class="admin-quick-link">

                            <div class="admin-quick-icon">

                                <i class="bi bi-folder2-open"></i>

                            </div>


                            <div class="admin-quick-text">

                                <strong>
                                    Berkas Pegawai
                                </strong>

                                <span>
                                    Kelola berkas internal
                                </span>

                            </div>


                            <i
                                class="
                                bi
                                bi-chevron-right
                                admin-quick-arrow
                            "></i>

                        </a>


                    </div>


                </div>



                <div class="admin-panel" style="margin-top:19px;">


                    <div class="admin-panel-header">

                        <div>

                            <h2 class="admin-panel-title">
                                Status Sistem
                            </h2>

                            <p class="admin-panel-description">
                                Informasi SAMPERIN
                            </p>

                        </div>

                    </div>


                    <div class="admin-info">


                        <div class="admin-info-icon">

                            <i class="bi bi-check-lg"></i>

                        </div>


                        <strong>

                            Sistem Berjalan Normal

                        </strong>


                        <p>

                            Dashboard administrator
                            SAMPERIN siap digunakan
                            untuk pengelolaan data internal.

                        </p>


                    </div>


                </div>


            </div>


        </section>


    </div>

@endsection
