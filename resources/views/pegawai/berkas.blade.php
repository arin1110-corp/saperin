@extends('pegawai.layouts.app')

@section('title', 'Berkas Saya')

@section('content')

<style>

    .berkas-page {
        padding: 30px 0 50px;
    }

    .berkas-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 22px;
    }

    .berkas-heading h1 {
        margin: 0;
        font-size: 30px;
        font-weight: 800;
        color: #101f4b;
        letter-spacing: -0.5px;
    }

    .berkas-heading p {
        margin: 6px 0 0;
        color: #58709a;
        font-size: 15px;
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    .berkas-filters {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .berkas-search {
        width: 280px;
        height: 46px;
        border: 1px solid #dce6f3;
        background: #fff;
        border-radius: 9px;
        display: flex;
        align-items: center;
        padding: 0 15px;
        box-shadow: 0 2px 8px rgba(19, 48, 92, .03);
    }

    .berkas-search i {
        font-size: 19px;
        color: #56729b;
        margin-right: 10px;
    }

    .berkas-search input {
        border: 0;
        outline: none;
        width: 100%;
        font-size: 14px;
        color: #162650;
    }

    .berkas-search input::placeholder {
        color: #8b9ab3;
    }

    .berkas-status {
        height: 46px;
        min-width: 170px;
        border: 1px solid #dce6f3;
        background: #fff;
        border-radius: 9px;
        padding: 0 14px;
        color: #172753;
        font-size: 14px;
        outline: none;
        cursor: pointer;
    }


    /*
    |--------------------------------------------------------------------------
    | CATEGORY
    |--------------------------------------------------------------------------
    */

    .berkas-card {
        background: #fff;
        border: 1px solid #dce6f3;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(27, 58, 103, .035);
    }

    .kategori-scroll {
        display: flex;
        overflow-x: auto;
        border-bottom: 1px solid #e5ebf4;
        scrollbar-width: thin;
    }

    .kategori-scroll::-webkit-scrollbar {
        height: 4px;
    }

    .kategori-scroll::-webkit-scrollbar-thumb {
        background: #d4dfed;
        border-radius: 20px;
    }

    .kategori-item {
        flex: 0 0 auto;
        min-height: 72px;
        padding: 0 27px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #506789;
        text-decoration: none;
        border-right: 1px solid #edf1f6;
        position: relative;
        white-space: nowrap;
        font-size: 14px;
    }

    .kategori-item:hover {
        color: #126cff;
        background: #f8fbff;
    }

    .kategori-item.active {
        color: #126cff;
        font-weight: 700;
    }

    .kategori-item.active::after {
        content: "";
        position: absolute;
        left: 18px;
        right: 18px;
        bottom: 0;
        height: 3px;
        background: #126cff;
        border-radius: 4px 4px 0 0;
    }

    .kategori-item i {
        font-size: 17px;
    }

    .kategori-count {
        min-width: 27px;
        height: 25px;
        padding: 0 8px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef4fb;
        color: #55709a;
        font-size: 12px;
        font-weight: 700;
    }

    .kategori-item.active .kategori-count {
        background: #e8f1ff;
        color: #126cff;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    .berkas-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .berkas-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
    }

    .berkas-table thead th {
        height: 52px;
        background: #f8fafc;
        border-bottom: 1px solid #dfe7f2;
        color: #55709a;
        font-size: 13px;
        font-weight: 700;
        text-align: left;
        padding: 0 20px;
        white-space: nowrap;
    }

    .berkas-table tbody tr {
        border-bottom: 1px solid #e6edf5;
    }

    .berkas-table tbody tr:last-child {
        border-bottom: 0;
    }

    .berkas-table tbody td {
        padding: 12px 20px;
        height: 68px;
        color: #10214c;
        font-size: 14px;
        vertical-align: middle;
    }

    .berkas-no {
        width: 65px;
        color: #122655 !important;
        font-weight: 500;
    }

    .berkas-name-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 260px;
    }

    .berkas-icon {
        width: 43px;
        height: 43px;
        flex: 0 0 43px;
        border-radius: 9px;
        background: #edf5ff;
        color: #126cff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .berkas-name {
        font-weight: 700;
        color: #0f214f;
        line-height: 1.25;
    }

    .berkas-category {
        margin-top: 3px;
        font-size: 12px;
        color: #7590b6;
    }

    .berkas-date {
        color: #526b92;
        white-space: nowrap;
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 38px;
        padding: 0 14px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge i {
        font-size: 16px;
    }

    .status-sudah {
        color: #008b52;
        background: #e8f8f0;
    }

    .status-belum {
        color: #e31b23;
        background: #ffe9ea;
    }


    /*
    |--------------------------------------------------------------------------
    | AKSI DESKTOP
    |--------------------------------------------------------------------------
    */

    .btn-lihat {
        height: 38px;
        min-width: 145px;
        border: 0;
        border-radius: 8px;
        background: #eaf3ff;
        color: #126cff;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: .15s ease;
    }

    .btn-lihat:hover {
        background: #dcecff;
        color: #075ed8;
    }

    .btn-lihat i {
        font-size: 17px;
    }


    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    .berkas-footer {
        min-height: 68px;
        padding: 12px 20px;
        border-top: 1px solid #e5ebf4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .berkas-info {
        color: #63799c;
        font-size: 13px;
    }

    .pagination-custom {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pagination-custom a,
    .pagination-custom span {
        min-width: 38px;
        height: 38px;
        padding: 0 10px;
        border: 1px solid #dbe5f1;
        border-radius: 8px;
        background: #fff;
        color: #54709a;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 13px;
    }

    .pagination-custom .active {
        background: #126cff;
        border-color: #126cff;
        color: #fff;
        font-weight: 700;
    }

    .pagination-custom .disabled {
        color: #b5c1d0;
        background: #f8fafc;
    }


    /*
    |--------------------------------------------------------------------------
    | EMPTY
    |--------------------------------------------------------------------------
    */

    .berkas-empty {
        padding: 60px 25px;
        text-align: center;
        color: #7185a5;
    }

    .berkas-empty i {
        font-size: 42px;
        color: #b9c9dc;
        display: block;
        margin-bottom: 12px;
    }

    .berkas-empty strong {
        display: block;
        color: #243961;
        margin-bottom: 5px;
    }


    /*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    .berkas-modal-icon {
        width: 52px;
        height: 52px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
    }

    .modal-success-icon {
        background: #e8f8f0;
        color: #00915a;
    }

    .modal-warning-icon {
        background: #ffe9ea;
        color: #e31b23;
    }

    .modal-title-custom {
        font-weight: 800;
        color: #10214c;
        margin-bottom: 4px;
    }

    .modal-subtitle {
        color: #7185a5;
        font-size: 13px;
    }

    .modal-detail {
        margin-top: 18px;
        padding: 14px;
        background: #f7faff;
        border: 1px solid #e1eaf5;
        border-radius: 10px;
    }

    .modal-detail-label {
        font-size: 11px;
        color: #7890b2;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .modal-detail-value {
        color: #1a2c54;
        font-size: 14px;
        font-weight: 600;
    }

    .btn-drive {
        height: 43px;
        border-radius: 9px;
        background: #126cff;
        border: 0;
        color: #fff;
        font-weight: 700;
        padding: 0 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-drive:hover {
        background: #075ed8;
        color: #fff;
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 900px) {

        .berkas-page {
            padding: 20px 0 35px;
        }

        .berkas-heading {
            display: block;
        }

        .berkas-heading h1 {
            font-size: 25px;
        }

        .berkas-filters {
            margin-top: 17px;
            width: 100%;
        }

        .berkas-search {
            flex: 1;
            width: auto;
        }

        .berkas-status {
            min-width: 150px;
        }

        .kategori-item {
            min-height: 60px;
            padding: 0 18px;
        }

        .berkas-table {
            min-width: 0;
        }

        .berkas-table thead {
            display: none;
        }

        .berkas-table tbody,
        .berkas-table tr,
        .berkas-table td {
            display: block;
            width: 100%;
        }

        .berkas-table tbody tr {
            position: relative;
            padding: 13px 14px;
            min-height: 82px;
        }

        .berkas-table tbody td {
            height: auto;
            padding: 0;
            border: 0;
        }

        .berkas-table .col-no,
        .berkas-table .col-date,
        .berkas-table .col-status {
            display: none;
        }

        .berkas-table .col-name {
            padding-right: 52px;
        }

        .berkas-name-wrap {
            min-width: 0;
        }

        .berkas-icon {
            width: 40px;
            height: 40px;
            flex-basis: 40px;
        }

        .berkas-name {
            font-size: 13px;
        }

        .berkas-category {
            font-size: 11px;
        }

        .mobile-action {
            display: flex !important;
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
        }

        .btn-eye {
            width: 39px;
            height: 39px;
            border-radius: 9px;
            border: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .btn-eye-green {
            background: #e7f8ef;
            color: #00925a;
        }

        .btn-eye-red {
            background: #ffe8ea;
            color: #e21c27;
        }

        .berkas-footer {
            display: block;
            padding: 13px 14px;
        }

        .berkas-info {
            margin-bottom: 12px;
        }

        .pagination-custom {
            justify-content: center;
            overflow-x: auto;
        }

    }

    @media (min-width: 901px) {
        .mobile-action {
            display: none !important;
        }
    }


    @media (max-width: 575px) {

        .berkas-heading h1 {
            font-size: 23px;
        }

        .berkas-heading p {
            font-size: 13px;
        }

        .berkas-filters {
            display: grid;
            grid-template-columns: 1fr 145px;
            gap: 8px;
        }

        .berkas-search {
            width: 100%;
        }

        .berkas-status {
            min-width: 0;
            width: 100%;
        }

        .kategori-item {
            min-height: 56px;
            padding: 0 15px;
            font-size: 13px;
        }

        .kategori-item i {
            font-size: 15px;
        }

        .kategori-count {
            min-width: 23px;
            height: 22px;
            padding: 0 6px;
            font-size: 11px;
        }

        .berkas-card {
            border-radius: 10px;
        }

        .berkas-footer {
            min-height: auto;
        }

        .pagination-custom a,
        .pagination-custom span {
            min-width: 34px;
            height: 34px;
        }

    }

</style>


<div class="berkas-page">

    <div class="samperin-container">

        {{-- ============================================================= --}}
        {{-- HEADER --}}
        {{-- ============================================================= --}}

        <div class="berkas-heading">

            <div>
                <h1>Berkas Saya</h1>

                <p>
                    Kelola dan pantau kelengkapan berkas kepegawaian Anda.
                </p>
            </div>


            <form
                method="GET"
                action="{{ route('pegawai.berkas') }}"
                class="berkas-filters"
            >

                {{-- SEARCH --}}
                <div class="berkas-search">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari berkas..."
                    >

                </div>


                {{-- STATUS --}}
                <select
                    name="status"
                    class="berkas-status"
                    onchange="this.form.submit()"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="sudah"
                        @selected($statusFilter === 'sudah')
                    >
                        Sudah Dikumpulkan
                    </option>

                    <option
                        value="belum"
                        @selected($statusFilter === 'belum')
                    >
                        Belum Dikumpulkan
                    </option>

                </select>

            </form>

        </div>


        {{-- ============================================================= --}}
        {{-- CARD BERKAS --}}
        {{-- ============================================================= --}}

        <div class="berkas-card">


            {{-- ========================================================= --}}
            {{-- KATEGORI --}}
            {{-- ========================================================= --}}

            <div class="kategori-scroll">

                {{-- SEMUA --}}
                <a
                    href="{{ route('pegawai.berkas', array_filter([
                        'search' => $search,
                        'status' => $statusFilter,
                    ])) }}"
                    class="kategori-item {{ $kategoriUid === '' ? 'active' : '' }}"
                >

                    <i class="bi bi-file-earmark-text"></i>

                    <span>
                        Semua
                    </span>

                    <span class="kategori-count">
                        {{ $totalSemua }}
                    </span>

                </a>


                {{-- KATEGORI DATABASE --}}
                @foreach ($kategoriList as $kategori)

                    @php
                        $kategoriKey = strtolower(
                            (string) $kategori->kategori_uid
                        );

                        $jumlahKategori =
                            $kategoriCounts->get(
                                $kategoriKey,
                                0
                            );

                        $kategoriQuery = [
                            'kategori' =>
                                $kategori->kategori_uid,

                            'search' =>
                                $search,

                            'status' =>
                                $statusFilter,
                        ];
                    @endphp

                    <a
                        href="{{ route('pegawai.berkas', array_filter($kategoriQuery)) }}"
                        class="kategori-item
                            {{ strtolower((string) $kategoriUid) === $kategoriKey ? 'active' : '' }}"
                    >

                        <i class="bi bi-folder"></i>

                        <span>
                            {{ $kategori->kategori_nama }}
                        </span>

                        <span class="kategori-count">
                            {{ $jumlahKategori }}
                        </span>

                    </a>

                @endforeach

            </div>


            {{-- ========================================================= --}}
            {{-- TABLE --}}
            {{-- ========================================================= --}}

            @if ($berkasList->count() > 0)

                <div class="berkas-table-wrap">

                    <table class="berkas-table">

                        <thead>

                            <tr>

                                <th style="width:70px;">
                                    No
                                </th>

                                <th>
                                    Kategori / Permintaan
                                </th>

                                <th style="width:220px;">
                                    Tanggal Upload
                                </th>

                                <th style="width:220px;">
                                    Status
                                </th>

                                <th style="width:170px;">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($berkasList as $index => $item)

                                @php

                                    $nomor =
                                        (($berkasList->currentPage() - 1)
                                        * $berkasList->perPage())
                                        + $index
                                        + 1;

                                    $sudah =
                                        $item['sudah'];

                                    $pengumpulan =
                                        $item['pengumpulan'];

                                    $tanggal =
                                        $item['tanggal_upload'];

                                    $fileUrl =
                                        $item['file_url'];

                                    $fileNama =
                                        $item['file_nama'];

                                    $modalId =
                                        'lihatBerkasModal' .
                                        $item['permintaan_id'];

                                @endphp


                                <tr>

                                    {{-- NO --}}
                                    <td class="col-no berkas-no">
                                        {{ $nomor }}
                                    </td>


                                    {{-- NAMA --}}
                                    <td class="col-name">

                                        <div class="berkas-name-wrap">

                                            <div class="berkas-icon">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </div>

                                            <div>

                                                <div class="berkas-name">
                                                    {{ $item['judul'] }}
                                                </div>

                                                <div class="berkas-category">
                                                    {{ $item['kategori_nama'] }}
                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- TANGGAL --}}
                                    <td class="col-date berkas-date">

                                        @if ($tanggal)

                                            {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- STATUS --}}
                                    <td class="col-status">

                                        @if ($sudah)

                                            <span class="status-badge status-sudah">

                                                <i class="bi bi-check-circle-fill"></i>

                                                Sudah Dikumpulkan

                                            </span>

                                        @else

                                            <span class="status-badge status-belum">

                                                <i class="bi bi-exclamation-circle-fill"></i>

                                                Belum Dikumpulkan

                                            </span>

                                        @endif

                                    </td>


                                    {{-- AKSI DESKTOP --}}
                                    <td>

                                        <button
                                            type="button"
                                            class="btn-lihat d-none d-md-inline-flex"
                                            data-bs-toggle="modal"
                                            data-bs-target="#{{ $modalId }}"
                                        >

                                            <i class="bi bi-eye"></i>

                                            Lihat Data

                                        </button>


                                        {{-- AKSI MOBILE --}}
                                        <div class="mobile-action">

                                            <button
                                                type="button"
                                                class="btn-eye
                                                    {{ $sudah
                                                        ? 'btn-eye-green'
                                                        : 'btn-eye-red'
                                                    }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#{{ $modalId }}"
                                                title="{{ $sudah ? 'Sudah dikumpulkan' : 'Belum dikumpulkan' }}"
                                            >

                                                <i class="bi bi-eye-fill"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>


                                {{-- ================================================= --}}
                                {{-- MODAL --}}
                                {{-- ================================================= --}}

                                <div
                                    class="modal fade"
                                    id="{{ $modalId }}"
                                    tabindex="-1"
                                    aria-hidden="true"
                                >

                                    <div class="modal-dialog modal-dialog-centered">

                                        <div class="modal-content border-0 rounded-4 shadow-lg">

                                            <div class="modal-body p-4">

                                                <div class="d-flex align-items-start gap-3">

                                                    <div
                                                        class="berkas-modal-icon
                                                        {{ $sudah
                                                            ? 'modal-success-icon'
                                                            : 'modal-warning-icon'
                                                        }}"
                                                    >

                                                        @if ($sudah)

                                                            <i class="bi bi-check-circle-fill"></i>

                                                        @else

                                                            <i class="bi bi-exclamation-circle-fill"></i>

                                                        @endif

                                                    </div>


                                                    <div class="flex-grow-1">

                                                        <div class="modal-title-custom">

                                                            {{ $item['judul'] }}

                                                        </div>

                                                        <div class="modal-subtitle">

                                                            {{ $item['kategori_nama'] }}

                                                        </div>

                                                    </div>

                                                </div>


                                                {{-- ================================= --}}
                                                {{-- SUDAH --}}
                                                {{-- ================================= --}}

                                                @if ($sudah)

                                                    <div class="modal-detail">

                                                        <div class="modal-detail-label">
                                                            Status
                                                        </div>

                                                        <div class="modal-detail-value text-success">
                                                            <i class="bi bi-check-circle-fill me-1"></i>
                                                            Sudah Dikumpulkan
                                                        </div>

                                                    </div>


                                                    <div class="modal-detail">

                                                        <div class="modal-detail-label">
                                                            Nama Berkas
                                                        </div>

                                                        <div class="modal-detail-value">

                                                            {{ $fileNama ?: 'Berkas' }}

                                                        </div>

                                                    </div>


                                                    <div class="modal-detail">

                                                        <div class="modal-detail-label">
                                                            Tanggal Upload
                                                        </div>

                                                        <div class="modal-detail-value">

                                                            @if ($tanggal)

                                                                {{ \Carbon\Carbon::parse($tanggal)->format('d M Y H:i') }}

                                                            @else

                                                                -

                                                            @endif

                                                        </div>

                                                    </div>


                                                    {{-- LINK DRIVE --}}
                                                    @if ($fileUrl)

                                                        <div class="d-flex justify-content-end mt-4">

                                                            <a
                                                                href="{{ $fileUrl }}"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="btn-drive"
                                                            >

                                                                <i class="bi bi-box-arrow-up-right"></i>

                                                                Lihat Berkas di Drive

                                                            </a>

                                                        </div>

                                                    @endif


                                                {{-- ================================= --}}
                                                {{-- BELUM --}}
                                                {{-- ================================= --}}

                                                @else

                                                    <div class="modal-detail">

                                                        <div class="modal-detail-value text-danger">

                                                            <i class="bi bi-exclamation-circle-fill me-1"></i>

                                                            Berkas belum dikumpulkan.

                                                        </div>

                                                        <div class="small text-muted mt-2">

                                                            Data pengumpulan untuk permintaan ini
                                                            belum tersedia.

                                                        </div>

                                                    </div>


                                                    <div class="d-flex justify-content-end mt-4">

                                                        <button
                                                            type="button"
                                                            class="btn btn-light rounded-3 px-4"
                                                            data-bs-dismiss="modal"
                                                        >
                                                            Tutup
                                                        </button>

                                                    </div>

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- ========================================================= --}}
                {{-- FOOTER / PAGINATION --}}
                {{-- ========================================================= --}}

                <div class="berkas-footer">

                    <div class="berkas-info">

                        Menampilkan

                        <strong>
                            {{ $berkasList->firstItem() ?? 0 }}
                        </strong>

                        -

                        <strong>
                            {{ $berkasList->lastItem() ?? 0 }}
                        </strong>

                        dari

                        <strong>
                            {{ $berkasList->total() }}
                        </strong>

                        berkas

                    </div>


                    @if ($berkasList->lastPage() > 1)

                        <div class="pagination-custom">

                            {{-- PREV --}}

                            @if ($berkasList->onFirstPage())

                                <span class="disabled">
                                    <i class="bi bi-chevron-left"></i>
                                </span>

                            @else

                                <a href="{{ $berkasList->previousPageUrl() }}">
                                    <i class="bi bi-chevron-left"></i>
                                </a>

                            @endif


                            {{-- NOMOR HALAMAN --}}

                            @for (
                                $page = 1;
                                $page <= $berkasList->lastPage();
                                $page++
                            )

                                @if (
                                    $page == 1 ||
                                    $page == $berkasList->lastPage() ||
                                    abs($page - $berkasList->currentPage()) <= 1
                                )

                                    @if ($page == $berkasList->currentPage())

                                        <span class="active">
                                            {{ $page }}
                                        </span>

                                    @else

                                        <a href="{{ $berkasList->url($page) }}">
                                            {{ $page }}
                                        </a>

                                    @endif

                                @elseif (
                                    $page == 2 ||
                                    $page == $berkasList->lastPage() - 1
                                )

                                    <span class="disabled">
                                        ...
                                    </span>

                                @endif

                            @endfor


                            {{-- NEXT --}}

                            @if ($berkasList->hasMorePages())

                                <a href="{{ $berkasList->nextPageUrl() }}">
                                    <i class="bi bi-chevron-right"></i>
                                </a>

                            @else

                                <span class="disabled">
                                    <i class="bi bi-chevron-right"></i>
                                </span>

                            @endif

                        </div>

                    @endif

                </div>


            @else

                {{-- ========================================================= --}}
                {{-- EMPTY --}}
                {{-- ========================================================= --}}

                <div class="berkas-empty">

                    <i class="bi bi-folder2-open"></i>

                    <strong>
                        Tidak ada berkas
                    </strong>

                    <span>
                        Belum ada permintaan berkas yang sesuai dengan filter.
                    </span>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection