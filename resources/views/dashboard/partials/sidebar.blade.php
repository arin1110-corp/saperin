@php
    /*
    |--------------------------------------------------------------------------
    | USER LOGIN
    |--------------------------------------------------------------------------
    */

    $sidebarUser = session('user_info') ?? session('user');

    /*
    |--------------------------------------------------------------------------
    | ROLE AKTIF
    |--------------------------------------------------------------------------
    |
    | Sumber utama adalah session yang dibuat oleh
    | SamperinLoginController dan SamperinAdminController.
    |
    */

    $activeRoleUid = session('samperin_role_uid');

    $activeRoleName = session('samperin_role_nama', 'Pegawai');

    $activeRoleSlug = session('samperin_role_slug');

    /*
    |--------------------------------------------------------------------------
    | ROLE YANG TERSEDIA
    |--------------------------------------------------------------------------
    */

    $availableRoles = $roles ?? collect();

    /*
    |--------------------------------------------------------------------------
    | JIKA ACTIVE ROLE TERSEDIA DI COLLECTION
    |--------------------------------------------------------------------------
    */

    if ($activeRoleUid && $availableRoles instanceof \Illuminate\Support\Collection) {
        $sessionRole = $availableRoles->firstWhere('role_uid', $activeRoleUid);

        if ($sessionRole) {
            $activeRoleName = $sessionRole->role_nama;

            $activeRoleSlug = $sessionRole->role_slug;
        }
    }

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

    /*
    |--------------------------------------------------------------------------
    | INISIAL
    |--------------------------------------------------------------------------
    */

    $sidebarInitial = 'U';

    if ($sidebarUser && !empty($sidebarUser->user_nama)) {
        $sidebarInitial = strtoupper(substr(trim($sidebarUser->user_nama), 0, 1));
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

        transform: translateX(0);

        transition:
            transform .25s ease;
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

        font-size: 10px;
        line-height: 1.45;

        color:
            rgba(255, 255, 255, .42);
    }


    /* =====================================================
       MOBILE CLOSE BUTTON
    ===================================================== */

    .samperin-mobile-close {
        display: none;

        width: 34px;
        height: 34px;

        margin-left: auto;

        border: 0;

        border-radius: 9px;

        background:
            rgba(255, 255, 255, .08);

        color:
            rgba(255, 255, 255, .7);

        align-items: center;
        justify-content: center;

        font-size: 16px;

        cursor: pointer;

        flex-shrink: 0;
    }


    .samperin-mobile-close:hover {
        background:
            rgba(255, 255, 255, .14);

        color: #fff;
    }


    /* =====================================================
       MOBILE TOGGLE
    ===================================================== */

    .samperin-mobile-toggle {
        display: none;

        position: fixed;

        top: 16px;
        left: 16px;

        width: 42px;
        height: 42px;

        border: 0;

        border-radius: 11px;

        background: #111e35;

        color: #fff;

        align-items: center;
        justify-content: center;

        font-size: 20px;

        cursor: pointer;

        z-index: 1200;

        box-shadow:
            0 6px 20px rgba(0, 0, 0, .15);
    }


    .samperin-mobile-toggle:hover {
        background: #1b2c49;
    }


    /* =====================================================
       MOBILE OVERLAY
    ===================================================== */

    .samperin-mobile-overlay {
        display: none;

        position: fixed;

        inset: 0;

        background:
            rgba(8, 16, 29, .48);

        backdrop-filter:
            blur(2px);

        z-index: 900;
    }


    .samperin-mobile-overlay.show {
        display: block;
    }


    /* =====================================================
       ACCOUNT / ROLE
    ===================================================== */

    .admin-sidebar-profile {
        margin:
            18px 14px 10px;

        padding: 14px;

        border-radius: 13px;

        background:
            rgba(255, 255, 255, .045);

        border:
            1px solid rgba(255, 255, 255, .07);

        flex-shrink: 0;
    }


    .admin-profile-caption {
        color:
            rgba(255, 255, 255, .3);

        font-size: 10px;

        letter-spacing: 1.2px;

        text-transform: uppercase;

        margin-bottom: 11px;
    }


    .admin-role-button {
        width: 100%;

        border:
            1px solid rgba(255, 255, 255, .08);

        background:
            rgba(255, 255, 255, .035);

        border-radius: 11px;

        padding: 10px;

        display: flex;
        align-items: center;

        gap: 10px;

        color: #fff;

        text-align: left;

        cursor: pointer;

        transition:
            background .15s ease,
            border-color .15s ease;
    }


    .admin-role-button:hover {
        background:
            rgba(255, 255, 255, .07);

        border-color:
            rgba(255, 255, 255, .12);
    }


    .admin-role-avatar {
        width: 42px;
        height: 42px;

        border-radius: 10px;

        background:
            linear-gradient(135deg,
                #df883c,
                #c96520);

        border:
            1px solid rgba(255, 255, 255, .12);

        display: flex;
        align-items: center;
        justify-content: center;

        overflow: hidden;

        flex-shrink: 0;

        color: #fff;

        font-size: 16px;

        font-weight: 800;
    }


    .admin-role-avatar img {
        width: 100%;
        height: 100%;

        object-fit: cover;
    }


    .admin-role-info {
        flex: 1;

        min-width: 0;
    }


    .admin-role-name {
        font-size: 14px;

        font-weight: 700;

        white-space: nowrap;

        overflow: hidden;

        text-overflow: ellipsis;
    }


    .admin-role-hint {
        margin-top: 4px;

        color:
            rgba(255, 255, 255, .38);

        font-size: 10px;
    }


    .admin-role-arrow {
        color:
            rgba(255, 255, 255, .35);

        font-size: 13px;

        flex-shrink: 0;
    }


    /* =====================================================
       NAVIGATION
    ===================================================== */

    .admin-nav {
        flex: 1;

        overflow-y: auto;

        padding:
            8px 11px 20px;
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
            rgba(255, 255, 255, .27);

        text-transform: uppercase;

        letter-spacing: 1px;

        font-size: 10px;
    }


    .admin-nav-link {
        display: flex;

        align-items: center;

        gap: 12px;

        padding:
            11px 12px;

        margin-bottom: 3px;

        border-radius: 9px;

        color:
            rgba(255, 255, 255, .57);

        font-size: 15px;

        font-weight: 500;

        text-decoration: none;

        transition:
            background .15s ease,
            color .15s ease;
    }


    .admin-nav-link i {
        width: 20px;

        text-align: center;

        font-size: 17px;

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
                #df8339,
                #c35e1d);

        box-shadow:
            0 7px 18px rgba(195, 94, 29, .22);
    }


    /* =====================================================
       FOOTER / LOGOUT
    ===================================================== */

    .admin-sidebar-footer {
        padding:
            13px 18px 17px;

        border-top:
            1px solid rgba(255, 255, 255, .06);

        flex-shrink: 0;
    }


    .admin-logout-button {
        width: 100%;

        border: 0;

        background: transparent;

        color:
            rgba(255, 255, 255, .55);

        display: flex;

        align-items: center;

        gap: 11px;

        padding:
            10px 12px;

        border-radius: 9px;

        font-size: 13px;

        font-weight: 500;

        cursor: pointer;

        text-align: left;

        transition:
            background .15s ease,
            color .15s ease;
    }


    .admin-logout-button i {
        width: 20px;

        text-align: center;

        font-size: 25px;
    }


    .admin-logout-button:hover {
        color: #fff;

        background:
            rgba(238, 0, 0, .921);
    }


    /* =====================================================
       ROLE MODAL
    ===================================================== */

    .samperin-role-modal {
        position: fixed;

        inset: 0;

        z-index: 5000;

        display: none;

        align-items: center;
        justify-content: center;

        padding: 20px;

        background:
            rgba(10, 18, 32, .68);

        backdrop-filter:
            blur(5px);
    }


    .samperin-role-modal.show {
        display: flex;
    }


    .samperin-role-dialog {
        width: 100%;

        max-width: 520px;

        background: #fff;

        border-radius: 20px;

        overflow: hidden;

        box-shadow:
            0 25px 70px rgba(0, 0, 0, .25);

        animation:
            samperinModalIn .18s ease;
    }


    @keyframes samperinModalIn {
        from {
            opacity: 0;

            transform:
                translateY(10px) scale(.98);
        }

        to {
            opacity: 1;

            transform:
                translateY(0) scale(1);
        }
    }


    /* =====================================================
       MODAL HEADER
    ===================================================== */

    .samperin-role-header {
        padding:
            20px 22px;

        background:
            linear-gradient(135deg,
                #14223b,
                #1d3558);

        display: flex;

        align-items: center;

        gap: 13px;
    }


    .samperin-role-header-avatar {
        width: 50px;
        height: 50px;

        border-radius: 13px;

        background:
            linear-gradient(135deg,
                #df883c,
                #c96520);

        border:
            1px solid rgba(255, 255, 255, .15);

        display: flex;

        align-items: center;
        justify-content: center;

        overflow: hidden;

        flex-shrink: 0;

        color: #fff;

        font-size: 18px;

        font-weight: 800;
    }


    .samperin-role-header-avatar img {
        width: 100%;
        height: 100%;

        object-fit: cover;
    }


    .samperin-role-header-text {
        flex: 1;
    }


    .samperin-role-header-title {
        color: #fff;

        font-size: 20px;

        font-weight: 800;
    }


    .samperin-role-header-subtitle {
        margin-top: 4px;

        color:
            rgba(255, 255, 255, .58);

        font-size: 12px;
    }


    .samperin-role-close {
        width: 40px;
        height: 40px;

        border: 0;

        border-radius: 10px;

        background:
            rgba(255, 255, 255, .08);

        color:
            rgba(255, 255, 255, .65);

        display: flex;

        align-items: center;
        justify-content: center;

        font-size: 20px;

        cursor: pointer;

        transition: .15s ease;
    }


    .samperin-role-close:hover {
        background:
            rgba(255, 255, 255, .14);

        color: #fff;
    }


    /* =====================================================
       MODAL BODY
    ===================================================== */

    .samperin-role-body {
        padding:
            20px 22px 22px;
    }


    .samperin-role-body-title {
        margin-bottom: 12px;

        color: #3b4657;

        font-size: 14px;

        font-weight: 700;
    }


    .samperin-role-list {
        display: flex;

        flex-direction: column;

        gap: 9px;
    }


    .samperin-role-list form {
        margin: 0;
    }


    .samperin-role-item {
        width: 100%;

        min-height: 70px;

        padding:
            10px 13px;

        border-radius: 13px;

        border:
            1px solid #e3e7ed;

        background: #fff;

        display: flex;

        align-items: center;

        gap: 12px;

        text-align: left;

        cursor: pointer;

        transition:
            border-color .15s ease,
            background .15s ease,
            transform .15s ease;
    }


    .samperin-role-item:hover {
        border-color: #cbd2dc;

        background: #fafbfc;

        transform:
            translateY(-1px);
    }


    .samperin-role-item.active {
        border-color: #df8339;

        background: #fff8f2;
    }


    .samperin-role-item.inactive {
        background: #f4f5f7;

        border-color: #e1e4e8;
    }


    .samperin-role-item.inactive .samperin-role-icon {
        background: #e5e7eb;

        color: #8a919b;
    }


    .samperin-role-icon {
        width: 42px;
        height: 42px;

        border-radius: 10px;

        background: #fff0e3;

        color: #d2742d;

        display: flex;

        align-items: center;
        justify-content: center;

        font-size: 19px;

        flex-shrink: 0;
    }


    .samperin-role-item.active .samperin-role-icon {
        background: #fff0e3;

        color: #d2742d;
    }


    .samperin-role-item-text {
        flex: 1;

        min-width: 0;
    }


    .samperin-role-item-name {
        color: #273449;

        font-size: 15px;

        font-weight: 750;
    }


    .samperin-role-item-slug {
        margin-top: 4px;

        color: #929aa6;

        font-size: 11px;
    }


    .samperin-role-item-arrow {
        color: #a9b1bc;

        font-size: 16px;
    }


    .samperin-role-item.active .samperin-role-item-arrow {
        width: 22px;
        height: 22px;

        border-radius: 50%;

        background: #d8782d;

        color: #fff;

        display: flex;

        align-items: center;
        justify-content: center;

        font-size: 12px;
    }


    /* =====================================================
       TABLET
    ===================================================== */

    @media(max-width:850px) and (min-width:601px) {

        .admin-sidebar {
            width: 255px;
        }

    }


    /* =====================================================
       MOBILE DRAWER
    ===================================================== */

    @media(max-width:600px) {

        .admin-sidebar {
            width: 235px;

            transform:
                translateX(-100%);

            box-shadow:
                12px 0 35px rgba(0, 0, 0, .18);
        }


        .admin-sidebar.mobile-open {
            transform:
                translateX(0);
        }


        .samperin-mobile-toggle {
            display: flex;
        }


        .samperin-mobile-close {
            display: flex;
        }


        /* =================================================
           BRAND
        ================================================= */

        .admin-brand {
            height: 76px;

            padding:
                0 12px 0 15px;

            gap: 10px;
        }


        .admin-brand-logo {
            width: 40px;
            height: 40px;

            border-radius: 10px;
        }


        .admin-brand-logo img {
            width: 32px;
            height: 32px;
        }


        .admin-brand-title {
            font-size: 20px;
        }


        .admin-brand-subtitle {
            font-size: 8px;
        }


        /* =================================================
           AKUN / ROLE
        ================================================= */

        .admin-sidebar-profile {
            display: block;

            margin:
                13px 10px 8px;

            padding: 11px;
        }


        .admin-profile-caption {
            font-size: 9px;

            margin-bottom: 8px;
        }


        .admin-role-button {
            padding: 9px;
        }


        .admin-role-avatar {
            width: 38px;
            height: 38px;

            border-radius: 9px;

            font-size: 14px;
        }


        .admin-role-name {
            font-size: 12px;
        }


        .admin-role-hint {
            font-size: 9px;
        }


        .admin-role-arrow {
            font-size: 11px;
        }


        /* =================================================
           NAVIGATION
        ================================================= */

        .admin-nav {
            padding:
                6px 8px 15px;
        }


        .admin-nav-label {
            padding:
                13px 8px 7px;

            font-size: 8px;
        }


        .admin-nav-link {
            padding:
                10px 10px;

            gap: 10px;

            font-size: 12px;

            margin-bottom: 2px;
        }


        .admin-nav-link i {
            width: 19px;

            font-size: 15px;
        }


        .admin-nav-link span {
            display: inline;
        }


        /* =================================================
           LOGOUT
        ================================================= */

        .admin-sidebar-footer {
            display: block;

            padding:
                8px 10px 10px;
        }


        .admin-logout-button {
            padding:
                9px 10px;

            font-size: 12px;
        }


        .admin-logout-button i {
            width: 19px;

            font-size: 19px;
        }


        /* =================================================
           ROLE MODAL
        ================================================= */

        .samperin-role-modal {
            padding: 12px;
        }


        .samperin-role-dialog {
            max-width: 100%;

            border-radius: 16px;
        }

    }
</style>


{{-- =====================================================
     MOBILE TOGGLE
===================================================== --}}

<button type="button" class="samperin-mobile-toggle" id="samperinMobileToggle" onclick="toggleSamperinSidebar()"
    aria-label="Buka menu">

    <i class="bi bi-list"></i>

</button>


{{-- =====================================================
     MOBILE OVERLAY
===================================================== --}}

<div id="samperinMobileOverlay" class="samperin-mobile-overlay" onclick="closeSamperinSidebar()">
</div>


{{-- =====================================================
     SIDEBAR
===================================================== --}}

<aside class="admin-sidebar" id="samperinSidebar">


    {{-- =====================================================
         BRAND
    ====================================================== --}}

    <div class="admin-brand">

        <div class="admin-brand-logo">

            <img src="{{ asset('assets/images/logo-samperin.png') }}" alt="Logo SAMPERIN">

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


        {{-- MOBILE CLOSE --}}

        <button type="button" class="samperin-mobile-close" onclick="closeSamperinSidebar()" aria-label="Tutup menu">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>



    {{-- =====================================================
         AKUN / ROLE AKTIF
    ====================================================== --}}

    <div class="admin-sidebar-profile">

        <div class="admin-profile-caption">

            AKUN

        </div>


        <button type="button" class="admin-role-button" onclick="openSamperinRoleModal()">

            <div class="admin-role-avatar">

                @if ($sidebarFotoUrl)
                    <img src="{{ $sidebarFotoUrl }}" alt="Foto Profil">
                @else
                    {{ $sidebarInitial }}
                @endif

            </div>


            <div class="admin-role-info">

                <div class="admin-role-name">

                    {{ $activeRoleName }}

                </div>

                <div class="admin-role-hint">

                    Ganti Role

                </div>

            </div>


            <i class="bi bi-chevron-right admin-role-arrow"></i>

        </button>

    </div>



    {{-- =====================================================
         NAVIGATION
    ====================================================== --}}

    <nav class="admin-nav">


        {{-- =================================================
             UTAMA
        ================================================== --}}

        <div class="admin-nav-label">

            UTAMA

        </div>


        <a href="{{ route('samperin.dashboard') }}"
            class="admin-nav-link {{ request()->routeIs('samperin.dashboard') ? 'active' : '' }}">

            <i class="bi bi-grid-1x2-fill"></i>

            <span>
                Dashboard
            </span>

        </a>



        {{-- =================================================
             ADMINISTRASI
        ================================================== --}}

        <div class="admin-nav-label">

            ADMINISTRASI

        </div>


        <a href="{{ route('samperin.admin.roles.index') }}"
            class="admin-nav-link {{ request()->routeIs('samperin.admin.roles.*') ? 'active' : '' }}">

            <i class="bi bi-person-gear"></i>

            <span>
                Manajemen Role
            </span>

        </a>


        <a href="{{ route('samperin.admin.users.index') }}"
            class="admin-nav-link {{ request()->routeIs('samperin.admin.users.*') ? 'active' : '' }}">

            <i class="bi bi-people-fill"></i>

            <span>
                Pengguna
            </span>

        </a>


        <a href="{{ route('samperin.admin.activity.index') }}"
            class="admin-nav-link {{ request()->routeIs('samperin.admin.activity.*') ? 'active' : '' }}">

            <i class="bi bi-clock-history"></i>

            <span>
                Log Aktivitas
            </span>

        </a>



        {{-- =================================================
             KEPEGAWAIAN
        ================================================== --}}

        <div class="admin-nav-label">

            KEPEGAWAIAN

        </div>


        <a href="{{ route('kepeg.pegawai.index') }}"
            class="admin-nav-link {{ request()->routeIs('kepeg.pegawai.*') ? 'active' : '' }}">

            <i class="bi bi-person-lines-fill"></i>

            <span>
                Data Pegawai
            </span>

        </a>


        <a href="{{ route('kepeg.pegawai.import') }}"
            class="admin-nav-link {{ request()->routeIs('kepeg.pegawai.import') ? 'active' : '' }}">

            <i class="bi bi-database-add"></i>

            <span>
                Import Data
            </span>

        </a>


        <a href="{{ route('kepeg.berkas.index') }}"
            class="admin-nav-link {{ request()->routeIs('kepeg.berkas.*') ? 'active' : '' }}">

            <i class="bi bi-folder2-open"></i>

            <span>
                Berkas Pegawai
            </span>

        </a>



        {{-- =================================================
             DATA MASTER
        ================================================== --}}

        <div class="admin-nav-label">

            DATA MASTER

        </div>


        <a href="{{ route('master.jabatan.index') }}"
            class="admin-nav-link {{ request()->routeIs('master.jabatan.*') ? 'active' : '' }}">

            <i class="bi bi-diagram-3-fill"></i>

            <span>
                Jabatan
            </span>

        </a>


        <a href="{{ route('master.bidang.index') }}"
            class="admin-nav-link {{ request()->routeIs('master.bidang.*') ? 'active' : '' }}">

            <i class="bi bi-building"></i>

            <span>
                Bidang
            </span>

        </a>


        <a href="{{ route('master.golongan.index') }}"
            class="admin-nav-link {{ request()->routeIs('master.golongan.*') ? 'active' : '' }}">

            <i class="bi bi-layers-fill"></i>

            <span>
                Golongan
            </span>

        </a>


        <a href="{{ route('master.eselon.index') }}"
            class="admin-nav-link {{ request()->routeIs('master.eselon.*') ? 'active' : '' }}">

            <i class="bi bi-bar-chart-steps"></i>

            <span>
                Eselon
            </span>

        </a>

        <a href="{{ route('master.pendidikan.index') }}"
            class="admin-nav-link {{ request()->routeIs('master.pendidikan.*') ? 'active' : '' }}">

            <i class="bi bi-mortarboard-fill"></i>

            <span>
                Pendidikan
            </span>

        </a>

        <a href="{{ route('master.jenis-kerja.index') }}"
            class="admin-nav-link {{ request()->routeIs('master.jenis-kerja.*') ? 'active' : '' }}">

            <i class="bi bi-briefcase-fill"></i>

            <span>
                Jenis Kerja
            </span>

        </a>


        <a href="{{ route('master.status-pegawai.index') }}"
            class="admin-nav-link {{ request()->routeIs('master.status-pegawai.*') ? 'active' : '' }}">

            <i class="bi bi-person-badge-fill"></i>

            <span>
                Status Pegawai
            </span>

        </a>

        <div class="admin-nav-label">

            PENGATURAN

        </div>

        <a href="{{ route('pengaturan.api') }}"
            class="admin-nav-link {{ request()->routeIs('pengaturan.api') ? 'active' : '' }}">

            <i class="bi bi-key-fill"></i>

            <span>
                API Key
            </span>

        </a>

        <a href="{{ route('pengaturan.folder') }}"
            class="admin-nav-link {{ request()->routeIs('pengaturan.folder') ? 'active' : '' }}">

            <i class="bi bi-folder-fill"></i>

            <span>
                Folder Berkas
            </span>

        </a>

        {{-- =================================================
             AKUN
        ================================================== --}}

        <div class="admin-nav-label">

            AKUN

        </div>


        <a href="{{ route('akun.profil') }}"
            class="admin-nav-link {{ request()->routeIs('akun.profil') ? 'active' : '' }}">

            <i class="bi bi-person-circle"></i>

            <span>
                Profil Saya
            </span>

        </a>


        <a href="{{ route('akun.berkas') }}"
            class="admin-nav-link {{ request()->routeIs('akun.berkas') ? 'active' : '' }}">

            <i class="bi bi-folder2-open"></i>

            <span>
                Berkas Saya
            </span>

        </a>


        <a href="{{ route('akun.pengaturan') }}"
            class="admin-nav-link {{ request()->routeIs('akun.pengaturan') ? 'active' : '' }}">

            <i class="bi bi-sliders"></i>

            <span>
                Pengaturan Akun
            </span>

        </a>


    </nav>



    {{-- =====================================================
         FOOTER / LOGOUT
    ====================================================== --}}

    <div class="admin-sidebar-footer">

        <form method="POST" action="{{ route('samperin.logout') }}">

            @csrf

            <button type="submit" class="admin-logout-button">

                <i class="bi bi-box-arrow-right"></i>

                <span>
                    Logout
                </span>

            </button>

        </form>

    </div>

</aside>



{{-- =========================================================
     MODAL GANTI ROLE
========================================================= --}}

<div id="samperinRoleModal" class="samperin-role-modal" onclick="closeSamperinRoleModal(event)">


    <div class="samperin-role-dialog" onclick="event.stopPropagation()">


        {{-- =================================================
             HEADER
        ================================================== --}}

        <div class="samperin-role-header">


            <div class="samperin-role-header-avatar">

                @if ($sidebarFotoUrl)
                    <img src="{{ $sidebarFotoUrl }}" alt="Foto Profil">
                @else
                    {{ $sidebarInitial }}
                @endif

            </div>


            <div class="samperin-role-header-text">

                <div class="samperin-role-header-title">

                    Ganti Role

                </div>


                <div class="samperin-role-header-subtitle">

                    Pilih role yang ingin digunakan

                </div>

            </div>


            <button type="button" class="samperin-role-close" onclick="closeSamperinRoleModal()">

                <i class="bi bi-x-lg"></i>

            </button>


        </div>



        {{-- =================================================
             BODY
        ================================================== --}}

        <div class="samperin-role-body">


            <div class="samperin-role-body-title">

                Role Tersedia

            </div>


            <div class="samperin-role-list">


                @forelse ($availableRoles as $role)
                    @php
                        $isActive = $activeRoleUid && $activeRoleUid === $role->role_uid;
                    @endphp

                    <form method="POST" action="{{ route('samperin.role.switch') }}">

                        @csrf

                        <input type="hidden" name="role_uid" value="{{ $role->role_uid }}">

                        <button type="submit"
                            class="
                samperin-role-item
                {{ $isActive ? 'active' : 'inactive' }}
            ">

                            <div class="samperin-role-icon">

                                <i class="bi bi-person-badge-fill"></i>

                            </div>

                            <div class="samperin-role-item-text">

                                <div class="samperin-role-item-name">

                                    {{ $role->role_nama }}

                                </div>

                                <div class="samperin-role-item-slug">

                                    {{ $role->role_slug }}

                                </div>

                            </div>

                            @if ($isActive)
                                <div class="samperin-role-item-arrow">

                                    <i class="bi bi-check-lg"></i>

                                </div>
                            @else
                                <i
                                    class="
                        bi
                        bi-chevron-right
                        samperin-role-item-arrow
                    "></i>
                            @endif

                        </button>

                    </form>

                @empty

                    <div
                        style="
            padding:25px;
            text-align:center;
            color:#8d96a3;
            font-size:13px;
            background:#f5f6f8;
            border-radius:12px;
        ">

                        Belum ada role yang tersedia.

                    </div>
                @endforelse


            </div>


        </div>


    </div>

</div>



<script>
    /* =====================================================
       MOBILE SIDEBAR
    ===================================================== */

    function openSamperinSidebar() {

        const sidebar =
            document.getElementById(
                'samperinSidebar'
            );

        const overlay =
            document.getElementById(
                'samperinMobileOverlay'
            );

        if (!sidebar) {
            return;
        }

        sidebar.classList.add(
            'mobile-open'
        );

        if (overlay) {

            overlay.classList.add(
                'show'
            );

        }

        document.body.style.overflow =
            'hidden';
    }


    function closeSamperinSidebar() {

        const sidebar =
            document.getElementById(
                'samperinSidebar'
            );

        const overlay =
            document.getElementById(
                'samperinMobileOverlay'
            );

        if (!sidebar) {
            return;
        }

        sidebar.classList.remove(
            'mobile-open'
        );

        if (overlay) {

            overlay.classList.remove(
                'show'
            );

        }

        const roleModal =
            document.getElementById(
                'samperinRoleModal'
            );

        if (
            !roleModal ||
            !roleModal.classList.contains(
                'show'
            )
        ) {

            document.body.style.overflow =
                '';

        }
    }


    function toggleSamperinSidebar() {

        const sidebar =
            document.getElementById(
                'samperinSidebar'
            );

        if (!sidebar) {
            return;
        }

        if (
            sidebar.classList.contains(
                'mobile-open'
            )
        ) {

            closeSamperinSidebar();

        } else {

            openSamperinSidebar();

        }
    }


    /* =====================================================
       TUTUP SIDEBAR KETIKA MENU DIKLIK
    ===================================================== */

    document.addEventListener(
        'click',
        function(event) {

            if (
                window.innerWidth > 600
            ) {
                return;
            }

            const sidebar =
                document.getElementById(
                    'samperinSidebar'
                );

            if (!sidebar) {
                return;
            }

            const clickedLink =
                event.target.closest(
                    '.admin-nav-link'
                );

            if (
                clickedLink &&
                sidebar.contains(
                    clickedLink
                )
            ) {

                closeSamperinSidebar();

            }

        }
    );


    /* =====================================================
       ROLE MODAL
    ===================================================== */

    function openSamperinRoleModal() {

        if (window.innerWidth <= 600) {

            closeSamperinSidebar();

        }

        const modal =
            document.getElementById(
                'samperinRoleModal'
            );

        if (!modal) {
            return;
        }

        modal.classList.add(
            'show'
        );

        document.body.style.overflow =
            'hidden';
    }


    function closeSamperinRoleModal(event) {

        if (
            event &&
            event.target &&
            event.target.id !==
            'samperinRoleModal'
        ) {

            return;

        }

        const modal =
            document.getElementById(
                'samperinRoleModal'
            );

        if (!modal) {
            return;
        }

        modal.classList.remove(
            'show'
        );

        document.body.style.overflow =
            '';
    }


    /* =====================================================
       ESC KEY
    ===================================================== */

    document.addEventListener(
        'keydown',
        function(event) {

            if (
                event.key !== 'Escape'
            ) {

                return;

            }

            const roleModal =
                document.getElementById(
                    'samperinRoleModal'
                );

            if (
                roleModal &&
                roleModal.classList.contains(
                    'show'
                )
            ) {

                closeSamperinRoleModal();

                return;

            }

            closeSamperinSidebar();

        }
    );


    /* =====================================================
       RESPONSIVE RESET
    ===================================================== */

    window.addEventListener(
        'resize',
        function() {

            if (
                window.innerWidth > 600
            ) {

                const sidebar =
                    document.getElementById(
                        'samperinSidebar'
                    );

                const overlay =
                    document.getElementById(
                        'samperinMobileOverlay'
                    );

                if (sidebar) {

                    sidebar.classList.remove(
                        'mobile-open'
                    );

                }

                if (overlay) {

                    overlay.classList.remove(
                        'show'
                    );

                }

                document.body.style.overflow =
                    '';

            }

        }
    );
</script>
