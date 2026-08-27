@php

    /*
    |--------------------------------------------------------------------------
    | USER LOGIN
    |--------------------------------------------------------------------------
    */

    $sidebarUser = session('user_info') ?? session('user');

    $sidebarNama = $sidebarUser->user_nama ?? 'User';

    $sidebarNip = $sidebarUser->user_nip ?? '-';

    /*
    |--------------------------------------------------------------------------
    | FOTO USER
    |--------------------------------------------------------------------------
    */

    $sidebarFoto = null;

    if ($sidebarUser) {
        try {
            $foto = $sidebarUser->foto()->first();

            if ($foto && !empty($foto->user_foto_file)) {
                $sidebarFoto = $foto->user_foto_file;
            }
        } catch (\Throwable $e) {
            $sidebarFoto = null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FOTO URL
    |--------------------------------------------------------------------------
    */

    if ($sidebarFoto) {
        if (str_starts_with($sidebarFoto, 'http://') || str_starts_with($sidebarFoto, 'https://')) {
            $sidebarFotoUrl = $sidebarFoto;
        } else {
            $sidebarFotoUrl = asset(ltrim($sidebarFoto, '/'));
        }
    } else {
        $sidebarFotoUrl = null;
    }

@endphp


<style>
    /* =====================================================
       SIDEBAR
    ===================================================== */

    .admin-sidebar {

        position: fixed;

        left: 0;
        top: 0;
        bottom: 0;

        width: 255px;

        background:
            linear-gradient(180deg,
                #111e35 0%,
                #0c172a 100%);

        color: #fff;

        z-index: 1000;

        display: flex;

        flex-direction: column;

    }


    /* =====================================================
       BRAND
    ===================================================== */

    .admin-brand {

        height: 82px;

        display: flex;

        align-items: center;

        gap: 12px;

        padding: 0 20px;

        border-bottom:
            1px solid rgba(255, 255, 255, .07);

        flex-shrink: 0;

    }


    .admin-brand-logo {

        width: 44px;
        height: 44px;

        border-radius: 11px;

        background: #fff;

        display: flex;

        align-items: center;

        justify-content: center;

        overflow: hidden;

        flex-shrink: 0;

    }


    .admin-brand-logo img {

        width: 35px;
        height: 35px;

        object-fit: contain;

    }


    .admin-brand-title {

        font-size: 22px;

        line-height: 1;

        font-weight: 800;

        letter-spacing: -.8px;

    }


    .admin-brand-title span {

        color: #df8339;

    }


    .admin-brand-subtitle {

        margin-top: 5px;

        font-size: 9px;

        line-height: 1.45;

        color:
            rgba(255, 255, 255, .42);

    }


    /* =====================================================
       PROFILE
    ===================================================== */

    .admin-sidebar-profile {

        margin:
            18px 14px 10px;

        padding: 14px;

        border-radius: 12px;

        background:
            rgba(255, 255, 255, .05);

        border:
            1px solid rgba(255, 255, 255, .06);

        flex-shrink: 0;

    }


    .admin-profile-caption {

        color:
            rgba(255, 255, 255, .32);

        font-size: 9px;

        letter-spacing: 1px;

        text-transform: uppercase;

        margin-bottom: 10px;

    }


    .admin-profile-row {

        display: flex;

        align-items: center;

        gap: 10px;

    }


    .admin-profile-avatar {

        width: 40px;
        height: 40px;

        border-radius: 10px;

        background:
            linear-gradient(135deg,
                #dd863d,
                #b85d20);

        display: flex;

        align-items: center;

        justify-content: center;

        overflow: hidden;

        flex-shrink: 0;

        color: #fff;

        font-size: 13px;

        font-weight: 800;

    }


    .admin-profile-avatar img {

        width: 100%;
        height: 100%;

        object-fit: cover;

    }


    .admin-profile-name {

        font-size: 11px;

        font-weight: 700;

        white-space: nowrap;

        overflow: hidden;

        text-overflow: ellipsis;

    }


    .admin-profile-nip {

        margin-top: 4px;

        color:
            rgba(255, 255, 255, .4);

        font-size: 9px;

    }


    /* =====================================================
       NAVIGATION
    ===================================================== */

    .admin-nav {

        flex: 1;

        overflow-y: auto;

        padding: 8px 11px;

    }


    .admin-nav::-webkit-scrollbar {

        width: 4px;

    }


    .admin-nav::-webkit-scrollbar-track {

        background: transparent;

    }


    .admin-nav::-webkit-scrollbar-thumb {

        background:
            rgba(255, 255, 255, .1);

        border-radius: 10px;

    }


    .admin-nav-label {

        padding:
            16px 10px 9px;

        color:
            rgba(255, 255, 255, .28);

        text-transform: uppercase;

        letter-spacing: 1px;

        font-size: 9px;

    }


    .admin-nav-link {

        display: flex;

        align-items: center;

        gap: 12px;

        padding:
            12px 12px;

        margin-bottom: 4px;

        border-radius: 9px;

        color:
            rgba(255, 255, 255, .55);

        font-size: 14px;

        font-weight: 500;

        transition:
            background .15s ease,
            color .15s ease;

    }


    .admin-nav-link i {

        width: 20px;

        text-align: center;

        font-size: 16px;

        flex-shrink: 0;

    }


    .admin-nav-link:hover {

        color: #fff;

        background:
            rgba(255, 255, 255, .06);

    }


    .admin-nav-link.active {

        color: #fff;

        background:
            linear-gradient(135deg,
                #d77d33,
                #b75a1e);

        box-shadow:
            0 7px 18px rgba(183, 90, 30, .2);

    }


    /* =====================================================
       FOOTER
    ===================================================== */

    .admin-sidebar-footer {

        padding:
            14px 18px 18px;

        border-top:
            1px solid rgba(255, 255, 255, .06);

        color:
            rgba(255, 255, 255, .25);

        font-size: 9px;

        line-height: 1.6;

        flex-shrink: 0;

    }


    /* =====================================================
       MOBILE
    ===================================================== */

    @media(max-width:850px) {

        .admin-sidebar {

            width: 72px;

        }


        .admin-brand {

            justify-content: center;

            padding: 0;

        }


        .admin-brand-text,
        .admin-sidebar-profile,
        .admin-nav-label,
        .admin-nav-link span,
        .admin-sidebar-footer {

            display: none;

        }


        .admin-nav {

            padding: 10px 8px;

        }


        .admin-nav-link {

            justify-content: center;

            padding: 13px 7px;

        }


        .admin-nav-link i {

            width: auto;

        }

    }
</style>


<aside class="admin-sidebar">


    {{-- =====================================================
         BRAND
    ====================================================== --}}

    <div class="admin-brand">

        <div class="admin-brand-logo">

            <img src="{{ asset('assets/images/lambang-pemprov.png') }}" alt="Pemprov Bali">

        </div>


        <div class="admin-brand-text">

            <div class="admin-brand-title">

                SAMPER<span>IN</span>

            </div>


            <div class="admin-brand-subtitle">

                Sistem Administrasi Manajemen
                Pegawai dan Berkas Internal

            </div>

        </div>

    </div>



    {{-- =====================================================
         USER LOGIN
    ====================================================== --}}

    <div class="admin-sidebar-profile">

        <div class="admin-profile-caption">

            AKUN

        </div>


        <div class="admin-profile-row">


            <div class="admin-profile-avatar">

                @if ($sidebarFotoUrl)
                    <img src="{{ $sidebarFotoUrl }}" alt="{{ $sidebarNama }}">
                @else
                    {{ strtoupper(substr(trim($sidebarNama), 0, 1)) }}
                @endif

            </div>


            <div style="min-width:0;">

                <div class="admin-profile-name">

                    {{ $sidebarNama }}

                </div>


                <div class="admin-profile-nip">

                    {{ $sidebarNip }}

                </div>

            </div>


        </div>

    </div>



    {{-- =====================================================
         MENU
    ====================================================== --}}

    <nav class="admin-nav">


        {{-- =================================================
             UTAMA
        ================================================== --}}

        <div class="admin-nav-label">

            UTAMA

        </div>


        <a href="{{ route('samperin.admin.dashboard') }}"
            class="
                admin-nav-link
                {{ request()->routeIs('samperin.admin.dashboard') ? 'active' : '' }}
            ">

            <i class="bi bi-grid-1x2-fill"></i>

            <span>

                Dashboard

            </span>

        </a>



        {{-- =================================================
             KEPEGAWAIAN
             
             MENU INI TETAP SEPERTI SIDEBAR PEGAWAI
        ================================================== --}}

        <div class="admin-nav-label">

            KEPEGAWAIAN

        </div>


        @if (\Illuminate\Support\Facades\Route::has('kepeg.dashboard'))
            <a href="{{ route('kepeg.dashboard') }}"
                class="
                    admin-nav-link
                    {{ request()->routeIs('kepeg.dashboard') ? 'active' : '' }}
                ">

                <i class="bi bi-people-fill"></i>

                <span>

                    Dashboard Kepegawaian

                </span>

            </a>
        @endif



        @if (\Illuminate\Support\Facades\Route::has('kepeg.pegawai.index'))
            <a href="{{ route('kepeg.pegawai.index') }}"
                class="
                    admin-nav-link
                    {{ request()->routeIs('kepeg.pegawai.*') ? 'active' : '' }}
                ">

                <i class="bi bi-person-lines-fill"></i>

                <span>

                    Data Pegawai

                </span>

            </a>
        @endif



        @if (\Illuminate\Support\Facades\Route::has('kepeg.import'))
            <a href="{{ route('kepeg.import') }}"
                class="
                    admin-nav-link
                    {{ request()->routeIs('kepeg.import') ? 'active' : '' }}
            ">

                <i class="bi bi-file-earmark-arrow-up"></i>

                <span>

                    Import Data

                </span>

            </a>
        @endif



        @if (\Illuminate\Support\Facades\Route::has('kepeg.berkas.index'))
            <a href="{{ route('kepeg.berkas.index') }}"
                class="
                    admin-nav-link
                    {{ request()->routeIs('kepeg.berkas.*') ? 'active' : '' }}
                ">

                <i class="bi bi-folder2-open"></i>

                <span>

                    Berkas Pegawai

                </span>

            </a>
        @endif



        {{-- =================================================
             ADMINISTRASI
             
             TAMBAHAN KHUSUS ADMIN:
             MANAJEMEN ROLE
        ================================================== --}}

        <div class="admin-nav-label">

            ADMINISTRASI

        </div>


        <a href="{{ route('samperin.admin.roles.index') }}"
            class="
        admin-nav-link
        {{ request()->routeIs('samperin.admin.roles.*') ? 'active' : '' }}
    ">
            <i class="bi bi-person-gear"></i>

            <span>
                Manajemen Role
            </span>
        </a>


    </nav>



    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <div class="admin-sidebar-footer">

        SAMPERIN © {{ date('Y') }}

        <br>

        Dinas Kebudayaan Provinsi Bali

    </div>


</aside>
