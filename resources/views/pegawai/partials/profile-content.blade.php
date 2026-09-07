@php
    $fotoUrl = $user?->foto?->thumbnail_url;

    $namaLengkap = trim(
        ($user?->user_gelardepan ? $user->user_gelardepan . ' ' : '') .
            ($user?->user_nama ?? '') .
            ($user?->user_gelarbelakang ? ', ' . $user->user_gelarbelakang : ''),
    );

    if ($namaLengkap === '') {
        $namaLengkap = '-';
    }

    $tempatTanggalLahir = '-';

    if ($user?->user_tempatlahir || $user?->user_tgllahir) {
        $tempat = $user?->user_tempatlahir ?? '';

        $tanggal = $user?->user_tgllahir ? \Carbon\Carbon::parse($user->user_tgllahir)->translatedFormat('d F Y') : '';

        $tempatTanggalLahir = trim($tempat . ($tempat && $tanggal ? ', ' : '') . $tanggal);
    }

    $jenisKelamin = match ((string) $user?->user_jk) {
        'L', 'l', '1', 'LAKI-LAKI', 'Laki-laki' => 'Laki-laki',
        'P', 'p', '2', 'PEREMPUAN', 'Perempuan' => 'Perempuan',
        default => $user?->user_jk ?: '-',
    };

    $jabatan = $user?->jabatan?->jabatan_nama ?? '-';
    $bidang = $user?->bidang?->bidang_nama ?? '-';
    $golongan = $user?->golongan?->golongan_nama ?? '-';
    $eselon = $user?->eselon?->eselon_nama ?? '-';
    $pendidikan = $user?->pendidikan?->pendidikan_jenjang . ' - ' . $user?->pendidikan?->pendidikan_jurusan ?? '-';
    $jenisKerja = $user?->jenisKerja?->jenis_kerja_nama ?? '-';

    $tanggalTmt = $user?->user_tmt ? \Carbon\Carbon::parse($user->user_tmt)->translatedFormat('d F Y') : '-';

    $tanggalSpmt = $user?->user_spmt ? \Carbon\Carbon::parse($user->user_spmt)->translatedFormat('d F Y') : '-';
@endphp


<style>
    /* =========================================================
       CONTAINER
    ========================================================= */

    .samperin-container {
        width: 100%;
        max-width: 1450px;
        margin: 0 auto;
        padding: 24px 28px 50px;
        box-sizing: border-box;
    }


    /* =========================================================
       BREADCRUMB
    ========================================================= */

    .samperin-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        color: #7b8eaa;
        font-size: 13px;
        margin-bottom: 14px;
    }

    .samperin-breadcrumb i {
        font-size: 13px;
    }

    .samperin-breadcrumb .separator {
        color: #b3c0d1;
    }

    .samperin-breadcrumb .current {
        color: #31486f;
        font-weight: 600;
    }


    /* =========================================================
       PAGE TITLE
    ========================================================= */

    .samperin-page-title {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .samperin-page-title h1 {
        margin: 0;
        color: #132653;
        font-size: 29px;
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: -.5px;
    }

    .samperin-page-title p {
        margin: 6px 0 0;
        color: #667c9e;
        font-size: 14px;
    }


    /* =========================================================
       TOMBOL EDIT MOBILE
    ========================================================= */

    .samperin-profile-edit-action {
        margin: 0 0 14px;
    }

    .samperin-profile-edit-btn {
        width: 100%;
        min-height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border: 1px solid #b9d9ff;
        border-radius: 12px;
        background: #eef7ff;
        color: #1677ff;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .samperin-profile-edit-btn:hover {
        background: #e2f1ff;
        border-color: #1677ff;
        color: #1677ff;
    }

    .samperin-profile-edit-btn i {
        font-size: 16px;
    }


    /* =========================================================
       HERO
    ========================================================= */

    .samperin-profile-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid #dbe7f4;
        border-radius: 14px;
        background:
            linear-gradient(90deg,
                rgba(255, 255, 255, .98) 0%,
                rgba(255, 255, 255, .94) 50%,
                rgba(238, 247, 255, .78) 100%);
        margin-bottom: 22px;
    }

    .samperin-profile-hero::after {
        content: "";
        position: absolute;
        right: -100px;
        bottom: -110px;
        width: 390px;
        height: 240px;
        background: radial-gradient(ellipse,
                rgba(206, 225, 241, .65) 0%,
                rgba(206, 225, 241, 0) 70%);
        pointer-events: none;
    }

    .samperin-profile-hero-content {
        min-height: 225px;
        padding: 24px 28px;
        display: grid;
        grid-template-columns: 150px minmax(0, 1fr) auto;
        align-items: center;
        gap: 25px;
        position: relative;
        z-index: 2;
    }


    /* =========================================================
       PHOTO
    ========================================================= */

    .samperin-profile-photo-wrap {
        width: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .samperin-profile-photo,
    .samperin-profile-placeholder {
        width: 130px;
        height: 150px;
        border-radius: 11px;
        object-fit: cover;
        background: #edf3f9;
        border: 1px solid #d9e5f1;
    }

    .samperin-profile-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #91a5bf;
        font-size: 55px;
    }

    .samperin-photo-button {
        width: 130px;
        height: 35px;
        margin-top: -1px;
        border: 1px solid #126cff;
        border-radius: 0 0 7px 7px;
        background: #fff;
        color: #126cff;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .samperin-photo-button:hover {
        background: #edf5ff;
    }


    /* =========================================================
       PROFILE DATA
    ========================================================= */

    .samperin-profile-name {
        color: #132653;
        font-size: 24px;
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 5px;
    }

    .samperin-profile-position {
        color: #516a91;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .samperin-profile-office {
        color: #637a9c;
        font-size: 13px;
        margin-bottom: 14px;
    }

    .samperin-profile-badges {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .samperin-badge {
        min-height: 34px;
        padding: 0 12px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
    }

    .samperin-badge-nip {
        background: #fff7eb;
        border: 1px solid #f4dfbd;
        color: #97652b;
    }

    .samperin-badge-active {
        background: #ebfaf3;
        border: 1px solid #c8ebda;
        color: #008b56;
    }


    /* =========================================================
       QUOTE
    ========================================================= */

    .samperin-quote {
        min-width: 230px;
        padding-right: 10px;
        text-align: right;
    }

    .samperin-quote-text {
        color: #7388a6;
        font-size: 12px;
        line-height: 1.7;
        font-style: italic;
    }

    .samperin-quote-line {
        width: 70px;
        height: 2px;
        margin: 10px 0 0 auto;
        background: #d4e1ef;
    }


    /* =========================================================
       DATA CARDS GRID
    ========================================================= */

    .samperin-data-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .samperin-data-card {
        background: #fff;
        border: 1px solid #dce7f3;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(31, 65, 110, .025);
    }

    .samperin-data-card.full {
        grid-column: 1 / -1;
    }

    .samperin-data-card-header {
        min-height: 50px;
        padding: 0 16px;
        background: #f8fbfe;
        border-bottom: 1px solid #e3ebf4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .samperin-data-card-title {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #18305d;
        font-size: 13px;
        font-weight: 800;
    }

    .samperin-data-card-title i {
        color: #126cff;
        font-size: 16px;
    }

    .samperin-data-card-body {
        padding: 0;
    }


    /* =========================================================
       DATA ROW
    ========================================================= */

    .samperin-info-row {
        display: grid;
        grid-template-columns: 145px 18px minmax(0, 1fr);
        gap: 0;
        min-height: 39px;
        padding: 0 16px;
        border-bottom: 1px solid #edf1f6;
        align-items: center;
        font-size: 12px;
    }

    .samperin-info-row:last-child {
        border-bottom: 0;
    }

    .samperin-info-label {
        color: #5a7195;
        line-height: 1.4;
    }

    .samperin-info-value {
        color: #263e68;
        font-weight: 600;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }


    /* =========================================================
       REQUEST CARD
    ========================================================= */

    .samperin-request-card {
        margin-top: 18px;
        background: #fff;
        border: 1px solid #dce7f3;
        border-radius: 12px;
        overflow: hidden;
    }

    .samperin-request-title {
        padding: 15px 17px;
        display: flex;
        align-items: center;
        gap: 11px;
        border-bottom: 1px solid #e5edf5;
        background: #fbfdff;
    }

    .samperin-request-icon {
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        border-radius: 8px;
        background: #edf5ff;
        color: #126cff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .samperin-request-heading {
        color: #162d5a;
        font-size: 13px;
        font-weight: 800;
    }

    .samperin-request-description {
        color: #7186a4;
        font-size: 11px;
        margin-top: 2px;
    }

    .samperin-request-list {
        display: flex;
        flex-direction: column;
        gap: 10px;

        max-height: 180px;
        overflow-y: auto;
        overflow-x: hidden;

        padding-right: 4px;
    }

    .samperin-request-list::-webkit-scrollbar {
        width: 5px;
    }

    .samperin-request-list::-webkit-scrollbar-track {
        background: #edf2f8;
        border-radius: 10px;
    }

    .samperin-request-list::-webkit-scrollbar-thumb {
        background: #1677ff;
        border-radius: 10px;
    }


    /* ITEM */
    .samperin-request-item {
        min-height: 62px;

        display: flex;
        align-items: center;
        gap: 12px;

        padding: 9px 14px 9px 12px;

        border: 1px solid #a9ccff;
        border-left: 5px solid #1677ff;
        border-radius: 10px;

        background: #f5f9ff;

        text-decoration: none;

        transition: all .18s ease;
    }

    .samperin-request-item:hover {
        background: #eaf3ff;
        border-color: #1677ff;
        transform: translateX(2px);
        box-shadow: 0 4px 12px rgba(22, 119, 255, .12);
    }


    /* ICON */
    .samperin-request-item-icon {
        width: 34px;
        height: 34px;

        flex: 0 0 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        background: #1677ff;
        color: #fff;

        font-size: 16px;
    }


    /* CONTENT */
    .samperin-request-item-content {
        flex: 1;
        min-width: 0;

        display: flex;
        flex-direction: column;
        gap: 3px;
    }


    /* JUDUL */
    .samperin-request-item-text {
        display: block;

        color: #10204a;

        font-size: 14px;
        font-weight: 700;
        line-height: 1.25;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }


    /* DEADLINE */
    .samperin-request-item-deadline {
        display: flex;
        align-items: center;
        gap: 5px;

        color: #e05a47;

        font-size: 11px;
        font-style: italic;
        font-weight: 600;

        line-height: 1.2;
    }

    .samperin-request-item-deadline i {
        font-size: 11px;
    }


    /* ARROW */
    .samperin-request-item-arrow {
        flex: 0 0 auto;

        color: #1677ff;
        font-size: 14px;

        transition: transform .18s ease;
    }

    .samperin-request-item:hover .samperin-request-item-arrow {
        transform: translateX(3px);
    }

    .samperin-no-request {
        padding: 28px 18px;
        text-align: center;
        color: #8294ac;
        font-size: 12px;
    }

    .samperin-no-request i {
        display: block;
        margin-bottom: 7px;
        font-size: 27px;
        color: #69b995;
    }


    /* =========================================================
       MODAL
    ========================================================= */

    .samperin-modal-title {
        color: #152a56;
        font-weight: 800;
    }


    /* =========================================================
       TABLET
    ========================================================= */

    @media (max-width: 1100px) {

        .samperin-container {
            padding: 20px 20px 40px;
        }

        .samperin-profile-hero-content {
            grid-template-columns: 130px minmax(0, 1fr);
        }

        .samperin-profile-photo-wrap {
            width: 130px;
        }

        .samperin-quote {
            display: none;
        }

        .samperin-profile-name {
            font-size: 21px;
        }

        .samperin-info-row {
            grid-template-columns: 125px 16px minmax(0, 1fr);
        }
    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 767px) {

        .samperin-container {
            width: 100%;
            max-width: 100%;
            padding: 15px 10px 30px;
            overflow: hidden;
        }

        .samperin-breadcrumb {
            font-size: 11px;
            margin-bottom: 10px;
            gap: 6px;
        }

        .samperin-page-title {
            display: flex;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .samperin-page-title h1 {
            font-size: 21px;
        }

        .samperin-page-title p {
            font-size: 11px;
            line-height: 1.5;
            margin-top: 4px;
        }


        /* HERO */

        .samperin-profile-hero {
            border-radius: 10px;
            margin-bottom: 14px;
        }

        /* HERO PHOTO */

        .samperin-profile-hero-content {
            min-height: 0;
            padding: 14px 10px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-align: center;
        }

        .samperin-profile-photo-wrap {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .samperin-profile-photo,
        .samperin-profile-placeholder {
            width: 120px;
            height: 135px;
        }

        .samperin-photo-button {
            width: 120px;
            height: 33px;
            margin-top: -1px;
            font-size: 11px;
        }

        .samperin-profile-name {
            font-size: 18px;
            line-height: 1.3;
            margin-bottom: 4px;
            overflow-wrap: anywhere;
        }

        .samperin-profile-position {
            font-size: 12px;
        }

        .samperin-profile-office {
            font-size: 11px;
            margin-bottom: 9px;
        }

        .samperin-profile-badges {
            justify-content: center;
        }

        .samperin-badge {
            min-height: 30px;
            padding: 0 9px;
            font-size: 10px;
        }


        /* DATA CARDS */

        .samperin-data-grid {
            grid-template-columns: 1fr;
            gap: 11px;
        }

        .samperin-data-card.full {
            grid-column: auto;
        }

        .samperin-data-card {
            border-radius: 9px;
        }

        .samperin-data-card-header {
            min-height: 44px;
            padding: 0 12px;
        }

        .samperin-data-card-title {
            font-size: 12px;
        }

        .samperin-data-card-title i {
            font-size: 15px;
        }

        .samperin-info-row {
            grid-template-columns: 105px 13px minmax(0, 1fr);
            min-height: 36px;
            padding: 0 11px;
            font-size: 11px;
        }


        /* REQUEST */

        .samperin-request-card {
            margin-top: 11px;
            border-radius: 9px;
        }

        .samperin-request-title {
            padding: 11px 12px;
        }

        .samperin-request-icon {
            width: 34px;
            height: 34px;
            font-size: 15px;
        }

        .samperin-request-heading {
            font-size: 12px;
        }

        .samperin-request-description {
            font-size: 10px;
        }

        .samperin-request-item {
            min-height: 43px;
            padding: 0 12px;
            font-size: 11px;
        }
    }


    /* =========================================================
       VERY SMALL MOBILE
    ========================================================= */

    @media (max-width: 420px) {

        .samperin-container {
            padding-left: 7px;
            padding-right: 7px;
        }

        .samperin-profile-name {
            font-size: 17px;
        }

        .samperin-info-row {
            grid-template-columns: 95px 11px minmax(0, 1fr);
            font-size: 10.5px;
        }

        .samperin-badge {
            font-size: 9.5px;
        }
    }
</style>


<div class="samperin-container">

    {{-- =========================================================
         BREADCRUMB
    ========================================================== --}}

    <div class="samperin-breadcrumb">

        <i class="bi bi-house-door"></i>

        <span class="separator">
            <i class="bi bi-chevron-right"></i>
        </span>

        <span>
            Profil
        </span>

        <span class="separator">
            <i class="bi bi-chevron-right"></i>
        </span>

        <span class="current">
            Detail Pegawai
        </span>

    </div>


    {{-- =========================================================
         PAGE TITLE
    ========================================================== --}}

    <div class="samperin-page-title">

        <div>
            <h1>Detail Pegawai</h1>

            <p>
                Informasi lengkap data kepegawaian Anda di SAMPERIN.
            </p>
        </div>

    </div>


    {{-- =========================================================
         HERO
    ========================================================== --}}

    <section class="samperin-profile-hero">

        <div class="samperin-profile-hero-content">

            {{-- FOTO --}}
            <div class="samperin-profile-photo-wrap">

                @if ($fotoUrl)
                    <img src="{{ $fotoUrl }}" class="samperin-profile-photo" alt="Foto {{ $namaLengkap }}">
                @else
                    <div class="samperin-profile-placeholder">
                        <i class="bi bi-person"></i>
                    </div>
                @endif

                <button type="button" class="samperin-photo-button" data-bs-toggle="modal"
                    data-bs-target="#modalGantiFoto">
                    <i class="bi bi-image"></i>
                    Ganti Foto
                </button>

            </div>


            {{-- DATA UTAMA HERO --}}
            <div>

                <div class="samperin-profile-name">
                    {{ $namaLengkap }}
                </div>

                <div class="samperin-profile-position">
                    {{ $jabatan }}
                </div>

                <div class="samperin-profile-office">
                    {{ $bidang !== '-' ? $bidang . ' | ' : '' }}
                    Dinas Kebudayaan Provinsi Bali
                </div>

                <div class="samperin-profile-badges">

                    <div class="samperin-badge samperin-badge-nip">

                        <i class="bi bi-person"></i>

                        NIP
                        {{ $user->user_nip ?: '-' }}

                    </div>

                    <div class="samperin-badge samperin-badge-active">

                        <i class="bi bi-person-check"></i>

                        Pegawai Aktif

                    </div>

                </div>

            </div>


            {{-- QUOTE --}}
            <div class="samperin-quote">

                <div class="samperin-quote-text">

                    “Nangun Sat Kerthi Loka Bali<br>
                    melalui Pola Pembangunan<br>
                    Semesta Berencana”

                </div>

                <div class="samperin-quote-line"></div>

            </div>

        </div>

    </section>


    {{-- =========================================================
         SEMUA DATA DALAM CARD
    ========================================================== --}}
    <div class="samperin-profile-edit-action">
        <button href="#" class="samperin-profile-edit-btn" data-bs-toggle="modal"
            data-bs-target="#modalEditProfil">
            <i class="bi bi-pencil-square"></i>
            <span>Edit Data Profil</span>
        </button>
    </div>
    <div class="samperin-data-grid">


        {{-- =====================================================
             DATA UTAMA
        ====================================================== --}}

        <section class="samperin-data-card">

            <div class="samperin-data-card-header">

                <div class="samperin-data-card-title">

                    <i class="bi bi-person-vcard"></i>

                    Data Utama

                </div>

            </div>

            <div class="samperin-data-card-body">

                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        NIP
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_nip ?: '-' }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        NIK
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_nik ?: '-' }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Nama Lengkap
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $namaLengkap }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Tempat, Tanggal Lahir
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $tempatTanggalLahir }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Jenis Kelamin
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $jenisKelamin }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        NPWP
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_npwp ?: '-' }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        BPJS
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_bpjs ?: '-' }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Alamat
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_alamat ?: '-' }}
                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             DATA KONTAK
        ====================================================== --}}

        <section class="samperin-data-card">

            <div class="samperin-data-card-header">

                <div class="samperin-data-card-title">

                    <i class="bi bi-telephone"></i>

                    Informasi Kontak

                </div>

            </div>

            <div class="samperin-data-card-body">

                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Email
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_email ?: '-' }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        No. HP
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_notelp ?: '-' }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Lokasi Kerja
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_lokasikerja ?: '-' }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Keterangan
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_keterangan ?: '-' }}
                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             DATA KEPEGAWAIAN
        ====================================================== --}}

        <section class="samperin-data-card">

            <div class="samperin-data-card-header">

                <div class="samperin-data-card-title">

                    <i class="bi bi-briefcase"></i>

                    Data Kepegawaian

                </div>

            </div>

            <div class="samperin-data-card-body">

                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Jenis Kepegawaian
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $jenisKerja }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Golongan
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $golongan }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Eselon
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $eselon }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Kelas Jabatan
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_kelasjabatan ?: '-' }}
                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             RIWAYAT TANGGAL
        ====================================================== --}}

        <section class="samperin-data-card">

            <div class="samperin-data-card-header">

                <div class="samperin-data-card-title">

                    <i class="bi bi-calendar-check"></i>

                    Riwayat Tanggal

                </div>

            </div>

            <div class="samperin-data-card-body">

                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        TMT
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $tanggalTmt }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        SPMT
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $tanggalSpmt }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Jumlah Tanggungan
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_jmltanggungan ?? 0 }}
                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             PENDIDIKAN
        ====================================================== --}}

        <section class="samperin-data-card">

            <div class="samperin-data-card-header">

                <div class="samperin-data-card-title">

                    <i class="bi bi-mortarboard"></i>

                    Pendidikan

                </div>

            </div>

            <div class="samperin-data-card-body">

                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Pendidikan Terakhir
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $pendidikan }}
                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             DATA JABATAN
        ====================================================== --}}

        <section class="samperin-data-card">

            <div class="samperin-data-card-header">

                <div class="samperin-data-card-title">

                    <i class="bi bi-person-badge"></i>

                    Data Jabatan

                </div>

            </div>

            <div class="samperin-data-card-body">

                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Jabatan
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $jabatan }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Bidang
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $bidang }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        Eselon
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $eselon }}
                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
             DATA PENGGAJIAN
        ====================================================== --}}

        <section class="samperin-data-card">

            <div class="samperin-data-card-header">

                <div class="samperin-data-card-title">

                    <i class="bi bi-wallet2"></i>

                    Data Penggajian

                </div>

            </div>

            <div class="samperin-data-card-body">

                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        No. Rekening BPD
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_norek_bpd ?: '-' }}
                    </div>

                </div>


                <div class="samperin-info-row">

                    <div class="samperin-info-label">
                        NPWP
                    </div>

                    <div>:</div>

                    <div class="samperin-info-value">
                        {{ $user->user_npwp ?: '-' }}
                    </div>

                </div>

            </div>

        </section>


        {{-- =========================================================
         PERMINTAAN BERKAS
    ========================================================== --}}

        <aside class="samperin-request-card">

            <div class="samperin-request-title">

                <div class="samperin-request-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

                <div>

                    <div class="samperin-request-heading">
                        Permintaan Berkas
                    </div>

                    <div class="samperin-request-description">
                        Lengkapi berkas yang diminta oleh kepegawaian.
                    </div>

                </div>

            </div>


            @if ($permintaanAktif && $permintaanAktif->isNotEmpty())

                <div class="samperin-request-list">

                    @foreach ($permintaanAktif as $permintaan)
                        @php
                            $jenisNama = trim((string) ($permintaan->permintaan_judul ?? 'Berkas'));

                            $tahun = $permintaan->permintaan_tahun;

                            /*
                |--------------------------------------------------------------------------
                | JUDUL TOMBOL
                |--------------------------------------------------------------------------
                | Contoh:
                | Upload Coretax 2026
                | Upload Evaluasi Kinerja 2026
                |--------------------------------------------------------------------------
                */

                            $judulPermintaan = 'Upload ' . $jenisNama;

                            if ($tahun) {
                                $judulPermintaan;
                            }

                            /*
                |--------------------------------------------------------------------------
                | DEADLINE
                |--------------------------------------------------------------------------
                */

                            $deadline = $permintaan->permintaan_expired;
                        @endphp

                        <button type="button" class="samperin-request-item" data-bs-toggle="modal"
                            data-bs-target="#samperinUploadModal"
                            data-permintaan-uid="{{ $permintaan->permintaan_uid }}"
                            data-permintaan-id="{{ $permintaan->permintaan_id }}"
                            data-judul="{{ $judulPermintaan }}"
                            data-deadline="{{ $deadline ? $deadline->translatedFormat('d F Y H:i') : '-' }}">
                            <span class="samperin-request-item-icon">
                                <i class="bi bi-file-earmark-arrow-up"></i>
                            </span>

                            <span class="samperin-request-item-content">

                                <span class="samperin-request-item-text">
                                    {{ $judulPermintaan }}
                                </span>

                                @if ($deadline)
                                    <span class="samperin-request-item-deadline">
                                        <i class="bi bi-calendar3"></i>
                                        Batas waktu:
                                        {{ $deadline->translatedFormat('d F Y') }}
                                    </span>
                                @endif

                            </span>

                            <i class="bi bi-chevron-right samperin-request-item-arrow"></i>
                        </button>
                    @endforeach

                </div>
            @else
                <div class="samperin-no-request">

                    <i class="bi bi-check2-circle"></i>

                    Tidak ada permintaan berkas aktif saat ini.

                </div>

            @endif

        </aside>

    </div>


    {{-- =========================================================
     MODAL GANTI FOTO
========================================================== --}}

    <div class="modal fade" id="modalGantiFoto" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Ganti Foto Profil
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="text-center py-3">

                        @if ($fotoUrl)
                            <img src="{{ $fotoUrl }}"
                                style="
                                width:120px;
                                height:140px;
                                object-fit:cover;
                                border-radius:10px;
                            "
                                alt="Foto Profil">
                        @else
                            <div class="mx-auto d-flex align-items-center justify-content-center"
                                style="
                                width:120px;
                                height:140px;
                                background:#edf3f8;
                                border-radius:10px;
                                font-size:40px;
                                color:#8294a9;
                            ">
                                <i class="bi bi-person"></i>
                            </div>
                        @endif

                        <div class="mt-3 text-muted small">

                            Fitur perubahan foto akan dihubungkan ke ArinDrive.

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
     MODAL EDIT PROFIL
========================================================== --}}

    <div class="modal fade" id="modalEditProfil" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <div class="modal-header">

                    <h5 class="modal-title samperin-modal-title">
                        Edit Data Profil
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-info mb-0">

                        <i class="bi bi-info-circle me-1"></i>

                        Form perubahan data profil akan kita hubungkan
                        dengan controller update pegawai setelah UI ini selesai.

                    </div>

                </div>

            </div>

        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const modal = document.getElementById(
                'samperinUploadModal'
            );

            const form = document.getElementById(
                'samperinUploadForm'
            );

            const judul = document.getElementById(
                'samperinUploadJudul'
            );

            const deadline = document.getElementById(
                'samperinUploadDeadline'
            );

            const fileInput = document.getElementById(
                'samperinUploadFile'
            );

            const fileName = document.getElementById(
                'samperinFileName'
            );


            if (!modal || !form) {
                return;
            }


            modal.addEventListener(
                'show.bs.modal',
                function(event) {

                    const button = event.relatedTarget;

                    if (!button) {
                        return;
                    }

                    const permintaanUid =
                        button.getAttribute(
                            'data-permintaan-uid'
                        );

                    const namaPermintaan =
                        button.getAttribute(
                            'data-judul'
                        );

                    const deadlineText =
                        button.getAttribute(
                            'data-deadline'
                        );


                    judul.textContent =
                        namaPermintaan || '-';

                    deadline.textContent =
                        deadlineText ?
                        'Batas waktu: ' + deadlineText :
                        'Batas waktu: -';


                    /*
                    |--------------------------------------------------------------------------
                    | ACTION UPLOAD
                    |--------------------------------------------------------------------------
                    */

                    form.action =
                        "{{ route('pegawai.berkas.upload', ['permintaanUid' => '__UID__']) }}"
                        .replace('__UID__', permintaanUid);


                    /*
                    |--------------------------------------------------------------------------
                    | RESET FILE
                    |--------------------------------------------------------------------------
                    */

                    fileInput.value = '';

                    fileName.textContent =
                        'Pilih file untuk diupload';

                }
            );


            fileInput.addEventListener(
                'change',
                function() {

                    if (
                        this.files &&
                        this.files.length > 0
                    ) {
                        fileName.textContent =
                            this.files[0].name;
                    } else {
                        fileName.textContent =
                            'Pilih file untuk diupload';
                    }

                }
            );

        });
    </script>
