@extends('dashboard.layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb', 'Dashboard')

@section('header-title', 'Dashboard')


@section('page-style')

    <style>
        /* =====================================================
                           DASHBOARD
                        ===================================================== */

        .admin-content {
            width: 100%;
            min-width: 0;
        }


        /* =====================================================
                           HERO
                        ===================================================== */

        .admin-hero {

            position: relative;

            overflow: hidden;

            min-height: 205px;

            padding: 32px 42px;

            border-radius: 17px;

            background:
                linear-gradient(120deg,
                    #14223b 0%,
                    #1d3355 100%);

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

            pointer-events: none;

        }


        .admin-hero::after {

            content: "";

            position: absolute;

            width: 200px;
            height: 200px;

            border-radius: 50%;

            border:
                27px solid rgba(255, 255, 255, .025);

            right: 55px;
            bottom: -145px;

            pointer-events: none;

        }


        /* =====================================================
                           HERO LAYOUT
                        ===================================================== */

        .admin-hero-layout {

            position: relative;

            z-index: 3;

            display: grid;

            grid-template-columns:
                minmax(0, 1fr) 315px;

            gap: 30px;

            align-items: center;

            min-height: 140px;

        }


        .admin-hero-content {

            min-width: 0;

        }


        .admin-hero-label {

            font-size: 12px;

            text-transform: uppercase;

            letter-spacing: 1.5px;

            color:
                rgba(255, 255, 255, .48);

        }


        .admin-hero h1 {

            margin:
                10px 0 9px;

            font-size: 34px;

            line-height: 1.2;

            letter-spacing: -1px;

            font-weight: 800;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

        }


        .admin-hero h1 span {

            color: #eb974e;

        }


        .admin-hero p {

            margin: 0;

            max-width: 850px;

            font-size: 15px;

            line-height: 1.75;

            color:
                rgba(255, 255, 255, .64);

        }


        /* =====================================================
                           HERO PROFILE
                        ===================================================== */

        .admin-hero-profile {

            display: flex;

            align-items: center;

            justify-content: flex-end;

            gap: 18px;

            min-width: 0;

        }


        .admin-hero-avatar {

            width: 88px;
            height: 88px;

            flex-shrink: 0;

            border-radius: 50%;

            overflow: hidden;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                #f5f6f8;

            border:
                3px solid #eb974e;

            box-shadow:
                0 8px 25px rgba(0, 0, 0, .18);

            color: #b96a2d;

            font-size: 27px;

            font-weight: 800;

        }


        .admin-hero-avatar img {

            width: 100%;
            height: 100%;

            object-fit: cover;

        }


        .admin-hero-profile-info {

            min-width: 0;

            max-width: 190px;

        }


        /* =====================================================
                           BADGE
                        ===================================================== */

        .admin-hero-badge {

            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding:
                8px 11px;

            border-radius: 8px;

            background:
                rgba(255, 255, 255, .09);

            border:
                1px solid rgba(255, 255, 255, .05);

            color:
                rgba(255, 255, 255, .78);

            font-size: 11px;

            white-space: nowrap;

            margin-bottom: 9px;

        }


        .admin-hero-badge i {

            color: #eb974e;

        }


        .admin-hero-profile-name {

            font-size: 15px;

            font-weight: 800;

            color: #fff;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

        }


        .admin-hero-profile-nip {

            margin-top: 5px;

            font-size: 11px;

            color:
                rgba(255, 255, 255, .42);

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

        }


        /* =====================================================
                           STATS
                        ===================================================== */

        .admin-stats {

            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 16px;

            margin-top: 19px;

        }


        .admin-stat {

            min-width: 0;

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

            font-size: 12px;

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

            min-width: 0;

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

            gap: 15px;

        }


        .admin-panel-title {

            margin: 0;

            font-size: 17px;

            font-weight: 750;

            color: #182238;

        }


        .admin-panel-description {

            margin:
                5px 0 0;

            font-size: 12px;

            color: #969eaa;

        }


        .admin-panel-link {

            flex-shrink: 0;

            font-size: 11px;

            color: #c66d29;

            font-weight: 700;

            text-decoration: none;

        }


        .admin-panel-link:hover {

            color: #99501d;

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

            overflow: hidden;

        }


        .admin-employee-avatar img {

            width: 100%;
            height: 100%;

            object-fit: cover;

        }


        .admin-employee-info {

            flex: 1;

            min-width: 0;

        }


        .admin-employee-info strong {

            display: block;

            font-size: 14px;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

            color: #182238;

        }


        .admin-employee-info span {

            display: block;

            margin-top: 4px;

            color: #969eaa;

            font-size: 11px;

        }


        .employee-status {

            padding:
                6px 9px;

            border-radius: 6px;

            font-size: 10px;

            font-weight: 700;

            flex-shrink: 0;

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

            text-decoration: none;

            transition:
                background .15s ease,
                border-color .15s ease,
                transform .15s ease;

        }


        .admin-quick-link:last-child {

            margin-bottom: 0;

        }


        .admin-quick-link:hover {

            background: #fff9f4;

            border-color: #efd4bd;

            transform: translateX(2px);

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

            flex-shrink: 0;

        }


        .admin-quick-text {

            flex: 1;

            min-width: 0;

        }


        .admin-quick-text strong {

            display: block;

            font-size: 14px;

            color: #182238;

        }


        .admin-quick-text span {

            display: block;

            margin-top: 4px;

            font-size: 11px;

            color: #969eaa;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

        }


        .admin-quick-arrow {

            color: #b2bac5;

            font-size: 11px;

            flex-shrink: 0;

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

            font-size: 14px;

            color: #99501d;

        }


        .admin-info p {

            margin:
                5px 0 0;

            font-size: 11px;

            line-height: 1.6;

            color: #9b8675;

        }


        /* =====================================================
                           EMPTY
                        ===================================================== */

        .admin-empty {

            padding: 40px 10px;

            text-align: center;

            color: #9aa2ae;

            font-size: 12px;

        }


        .admin-empty i {

            display: block;

            font-size: 29px;

            margin-bottom: 9px;

        }


        /* =====================================================
                           RESPONSIVE 1100
                        ===================================================== */

        @media (max-width: 1100px) {

            .admin-hero-layout {

                grid-template-columns:
                    minmax(0, 1fr) 280px;

                gap: 20px;

            }


            .admin-hero {

                padding-left: 30px;
                padding-right: 30px;

            }


            .admin-stats {

                grid-template-columns:
                    repeat(2, minmax(0, 1fr));

            }


            .admin-dashboard-grid {

                grid-template-columns: 1fr;

            }

        }


        /* =====================================================
                           TABLET
                        ===================================================== */

        @media (max-width: 850px) {

            .admin-hero-layout {

                grid-template-columns: 1fr;

                gap: 20px;

            }


            .admin-hero {

                min-height: auto;

                padding:
                    27px 28px 28px;

            }


            .admin-hero h1 {

                white-space: normal;

                overflow: visible;

                text-overflow: unset;

            }


            .admin-hero-profile {

                justify-content: flex-start;

            }


            .admin-hero-profile-info {

                max-width: 300px;

            }

        }


        /* =====================================================
                           MOBILE
                        ===================================================== */

        @media (max-width: 600px) {

            .admin-stats {

                grid-template-columns: 1fr;

            }


            .admin-hero {

                padding: 24px;

                border-radius: 14px;

            }


            .admin-hero h1 {

                font-size: 24px;

                letter-spacing: -.5px;

            }


            .admin-hero p {

                font-size: 13px;

                line-height: 1.7;

            }


            .admin-hero-profile {

                gap: 13px;

            }


            .admin-hero-avatar {

                width: 70px;
                height: 70px;

                font-size: 22px;

            }


            .admin-hero-profile-info {

                max-width: calc(100% - 85px);

            }


            .admin-stat {

                padding: 17px;

            }


            .admin-panel-header {

                padding:
                    17px;

            }


            .admin-employee-list {

                padding-left: 17px;
                padding-right: 17px;

            }

        }


        /* =====================================================
                           VERY SMALL
                        ===================================================== */

        @media (max-width: 400px) {

            .admin-hero-profile-name {

                font-size: 12px;

            }


            .admin-hero-profile-nip {

                font-size: 10px;

            }


            .admin-hero-badge {

                font-size: 10px;

            }

        }
    </style>

@endsection


@section('content')

    @php

        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */

        $dashboardUser = $user ?? (session('user_info') ?? session('user'));

        $dashboardNama = $dashboardUser->user_nama ?? 'Administrator';

        $dashboardNip = $dashboardUser->user_nip ?? '-';

        /*
|--------------------------------------------------------------------------
| FOTO USER
|--------------------------------------------------------------------------
*/

        $dashboardFotoUrl = null;

        if ($dashboardUser) {
            try {
                $foto = $dashboardUser->foto()->first();

                if ($foto) {
                    $dashboardFotoUrl = $foto->thumbnail_url;
                }
            } catch (\Throwable $e) {
                $dashboardFotoUrl = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | INITIAL
        |--------------------------------------------------------------------------
        */

        $dashboardInitial = strtoupper(substr(trim($dashboardNama), 0, 1));

    @endphp


    <div class="admin-content">


        {{-- =====================================================
             HERO
        ====================================================== --}}

        <section class="admin-hero">


            <div class="admin-hero-layout">


                {{-- =================================================
                     HERO LEFT
                ================================================== --}}

                <div class="admin-hero-content">


                    <div class="admin-hero-label">

                        SAMPERIN · ADMINISTRATOR

                    </div>


                    <h1>

                        Halo,

                        <span>

                            {{ $dashboardNama }}

                        </span>

                    </h1>


                    <p>

                        Kelola data pegawai, administrasi
                        kepegawaian, dan sistem internal
                        Dinas Kebudayaan Provinsi Bali
                        dari satu dashboard.

                    </p>


                </div>



                {{-- =================================================
                     HERO RIGHT
                ================================================== --}}

                <div class="admin-hero-profile">


                    {{-- FOTO --}}

                    <div class="admin-hero-avatar">

                        @if ($dashboardFotoUrl)
                            <img src="{{ $dashboardFotoUrl }}" alt="{{ $dashboardNama }}">
                        @else
                            <i class="bi bi-person"></i>
                        @endif

                    </div>


                    {{-- INFO --}}

                    <div class="admin-hero-profile-info">


                        <div class="admin-hero-badge">

                            <i class="bi bi-shield-check"></i>

                            Administrator Aktif

                        </div>


                        <div class="admin-hero-profile-name">

                            {{ $dashboardNama }}

                        </div>


                        <div class="admin-hero-profile-nip">

                            NIP. {{ $dashboardNip }}

                        </div>


                    </div>


                </div>


            </div>


        </section>



        {{-- =====================================================
             STATS
        ====================================================== --}}

        <section class="admin-stats">


            {{-- TOTAL PEGAWAI --}}

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



            {{-- PEGAWAI AKTIF --}}

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



            {{-- PEGAWAI NONAKTIF --}}

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



            {{-- PERSENTASE --}}

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



        {{-- =====================================================
             CONTENT GRID
        ====================================================== --}}

        <section class="admin-dashboard-grid">


            {{-- =================================================
                 PEGAWAI TERBARU
            ================================================== --}}

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


                    @if (\Illuminate\Support\Facades\Route::has('kepeg.pegawai.index'))
                        <a href="{{ route('kepeg.pegawai.index') }}" class="admin-panel-link">

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

                            /*
                            |--------------------------------------------------------------------------
                            | FOTO PEGAWAI
                            |--------------------------------------------------------------------------
                            */

                            $pegawaiFotoUrl = null;

                            try {
                                $pegawaiFoto = $pegawai->foto()->first();

                                if ($pegawaiFoto) {
                                    $pegawaiFotoUrl = $pegawaiFoto->thumbnail_url;
                                }
                            } catch (\Throwable $e) {
                                $pegawaiFotoUrl = null;
                            }

                        @endphp


                        <div class="admin-employee">


                            {{-- FOTO / INITIAL --}}

                            <div class="admin-employee-avatar">


                                @if ($pegawaiFotoUrl)
                                    <img src="{{ $pegawaiFotoUrl }}" alt="{{ $nama }}">
                                @else
                                    {{ $initial }}
                                @endif


                            </div>



                            {{-- INFO --}}

                            <div class="admin-employee-info">


                                <strong>

                                    {{ $nama }}

                                </strong>


                                <span>

                                    NIP.
                                    {{ $pegawai->user_nip ?? '-' }}

                                </span>


                            </div>



                            {{-- STATUS --}}

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


                        <div class="admin-empty">

                            <i class="bi bi-people"></i>

                            Belum ada data pegawai.

                        </div>
                    @endforelse


                </div>


            </div>



            {{-- =================================================
                 RIGHT COLUMN
            ================================================== --}}

            <div>


                {{-- =================================================
                     AKSES CEPAT
                ================================================== --}}

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


                        {{-- DATA PEGAWAI --}}

                        @if (\Illuminate\Support\Facades\Route::has('kepeg.pegawai.index'))
                            <a href="{{ route('kepeg.pegawai.index') }}" class="admin-quick-link">

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



                        {{-- IMPORT DATA --}}

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



                        {{-- BERKAS --}}

                        @if (\Illuminate\Support\Facades\Route::has('kepeg.berkas.index'))
                            <a href="{{ route('kepeg.berkas.index') }}" class="admin-quick-link">

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
                        @endif



                        {{-- ROLE --}}

                        @if (isset($isAdmin) && $isAdmin && \Illuminate\Support\Facades\Route::has('samperin.admin.roles.index'))
                            <a href="{{ route('samperin.admin.roles.index') }}" class="admin-quick-link">

                                <div class="admin-quick-icon">

                                    <i class="bi bi-person-gear"></i>

                                </div>


                                <div class="admin-quick-text">

                                    <strong>

                                        Role & Hak Akses

                                    </strong>


                                    <span>

                                        Atur akses pengguna

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


                    </div>


                </div>



                {{-- =================================================
                     STATUS SISTEM
                ================================================== --}}

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

                            Dashboard SAMPERIN siap digunakan
                            untuk pengelolaan data internal
                            Dinas Kebudayaan Provinsi Bali.

                        </p>


                    </div>


                </div>


            </div>


        </section>


    </div>

@endsection
