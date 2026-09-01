@extends('dashboard.layouts.app')

@section('title', 'Manajemen Bidang')

@section('header-title', 'Manajemen Bidang')

@section('breadcrumb', 'Manajemen Bidang')

@section('page-style')

    <style>
        .bidang-page {
            width: 100%;
            max-width: 100%;
        }

        /* =========================================================
           HEADER
           ========================================================= */

        .bidang-header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .bidang-header-left {
            min-width: 0;
        }

        .bidang-header-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
            line-height: 1.2;
        }

        .bidang-header-description {
            margin: 6px 0 0;
            color: #8993a3;
            font-size: 13px;
        }

        .bidang-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .bidang-add-button,
        .bidang-import-button {
            height: 44px;
            padding: 0 17px;
            border-radius: 10px;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            font-size: 13px;
            font-weight: 700;

            text-decoration: none;
            cursor: pointer;

            transition: .15s ease;
            white-space: nowrap;
        }

        .bidang-add-button {
            border: 0;

            background:
                linear-gradient(135deg,
                    #df8339,
                    #c35e1d);

            color: #fff;

            box-shadow:
                0 7px 18px rgba(195, 94, 29, .18);
        }

        .bidang-add-button:hover {
            color: #fff;
            transform: translateY(-1px);

            box-shadow:
                0 9px 22px rgba(195, 94, 29, .25);
        }

        .bidang-import-button {
            border: 1px solid #d8dee7;
            background: #fff;
            color: #667085;
        }

        .bidang-import-button:hover {
            color: #273449;
            background: #f8f9fb;
            border-color: #cbd2dc;
        }


        /* =========================================================
           ALERT
           ========================================================= */

        .bidang-alert {
            border: 0;
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 18px;

            font-size: 12px;
        }

        .bidang-alert ul {
            margin: 5px 0 0;
            padding-left: 18px;
        }


        /* =========================================================
           STATISTIK
           ========================================================= */

        .bidang-stat-grid {
            width: 100%;

            display: grid;
            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 14px;
            margin-bottom: 18px;
        }

        .bidang-stat {
            min-width: 0;

            background: #fff;

            border:
                1px solid #e7eaf0;

            border-radius: 14px;

            padding: 17px;

            display: flex;
            align-items: center;

            gap: 13px;

            box-shadow:
                0 5px 18px rgba(20, 35, 60, .035);
        }

        .bidang-stat-icon {
            width: 44px;
            height: 44px;

            border-radius: 11px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #fff0e3;
            color: #d2742d;

            font-size: 19px;

            flex-shrink: 0;
        }

        .bidang-stat-content {
            min-width: 0;
        }

        .bidang-stat-label {
            color: #929ba9;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .bidang-stat-number {
            color: #273449;
            font-size: 21px;
            font-weight: 800;
            line-height: 1.1;
        }


        /* =========================================================
           PANEL
           ========================================================= */

        .bidang-panel {
            width: 100%;

            background: #fff;

            border:
                1px solid #e6eaf0;

            border-radius: 16px;

            overflow: hidden;

            box-shadow:
                0 8px 25px rgba(20, 35, 60, .04);
        }

        .bidang-panel-header {
            width: 100%;

            padding: 17px 19px;

            border-bottom:
                1px solid #edf0f4;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
        }


        /* =========================================================
           FILTER
           ========================================================= */

        .bidang-filter {
            width: 100%;
            max-width: 520px;

            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bidang-search {
            position: relative;
            flex: 1;
            min-width: 0;
        }

        .bidang-search i {
            position: absolute;

            left: 12px;
            top: 50%;

            transform: translateY(-50%);

            color: #9aa3b0;
            font-size: 15px;

            pointer-events: none;
        }

        .bidang-search input {
            width: 100%;
            height: 40px;

            padding:
                0 12px 0 35px;

            border:
                1px solid #dfe4eb;

            border-radius: 9px;

            outline: none;

            color: #344054;
            background: #fff;

            font-size: 12px;

            transition: .15s ease;
        }

        .bidang-search input:focus {
            border-color: #df8339;

            box-shadow:
                0 0 0 3px rgba(223, 131, 57, .08);
        }

        .bidang-filter-select {
            width: 115px;
            height: 40px;

            border:
                1px solid #dfe4eb;

            border-radius: 9px;

            padding: 0 11px;

            color: #4b5565;
            background: #fff;

            font-size: 12px;
            outline: none;
        }

        .bidang-filter-select:focus {
            border-color: #df8339;

            box-shadow:
                0 0 0 3px rgba(223, 131, 57, .08);
        }


        /* =========================================================
           TABLE
           ========================================================= */

        .bidang-table-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .bidang-table {
            width: 100%;
            min-width: 720px;

            border-collapse: collapse;
            table-layout: fixed;
        }

        .bidang-table th {
            padding: 13px 18px;

            background: #fafbfc;

            color: #929aa6;

            font-size: 10px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .7px;

            text-align: left;
            white-space: nowrap;

            border-bottom:
                1px solid #edf0f4;
        }

        .bidang-table td {
            padding: 15px 18px;

            border-bottom:
                1px solid #f0f2f5;

            color: #475467;

            font-size: 13px;

            vertical-align: middle;
        }

        .bidang-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .bidang-table tbody tr:hover {
            background: #fcfcfd;
        }

        .bidang-table th:nth-child(1),
        .bidang-table td:nth-child(1) {
            width: 45%;
        }

        .bidang-table th:nth-child(2),
        .bidang-table td:nth-child(2) {
            width: 18%;
        }

        .bidang-table th:nth-child(3),
        .bidang-table td:nth-child(3) {
            width: 17%;
        }

        .bidang-table th:nth-child(4),
        .bidang-table td:nth-child(4) {
            width: 20%;
        }


        /* =========================================================
           BIDANG
           ========================================================= */

        .bidang-name {
            color: #273449;

            font-size: 14px;
            font-weight: 750;

            line-height: 1.35;
        }

        .bidang-uid {
            margin-top: 4px;

            color: #a0a8b4;

            font-size: 10px;
            font-family: monospace;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .bidang-kode {
            display: inline-block;

            max-width: 100%;

            padding: 5px 8px;

            border-radius: 6px;

            background: #f3f5f8;

            color: #6d7683;

            font-family: monospace;

            font-size: 10px;

            word-break: break-all;
        }


        /* =========================================================
           STATUS
           ========================================================= */

        .bidang-status {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 6px 9px;

            border-radius: 20px;

            font-size: 10px;
            font-weight: 700;
        }

        .bidang-status.active {
            color: #247a4b;
            background: #eaf8f0;
        }

        .bidang-status.inactive {
            color: #7b8491;
            background: #f0f2f5;
        }

        .bidang-status-dot {
            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: currentColor;
        }


        /* =========================================================
           ACTION
           ========================================================= */

        .bidang-actions {
            display: flex;

            align-items: center;
            justify-content: flex-end;

            gap: 6px;
        }

        .bidang-action-button {
            width: 33px;
            height: 33px;

            border:
                1px solid #e2e6eb;

            border-radius: 8px;

            background: #fff;
            color: #737d8c;

            display: flex;

            align-items: center;
            justify-content: center;

            cursor: pointer;

            transition: .15s ease;
        }

        .bidang-action-button:hover {
            border-color: #cdd3db;

            background: #f8f9fb;

            color: #273449;
        }

        .bidang-action-button.warning:hover {
            background: #fff8e8;
            color: #b87800;
            border-color: #efd99d;
        }

        .bidang-action-button.danger:hover {
            background: #fff1f1;
            color: #d33;
            border-color: #efcaca;
        }


        /* =========================================================
           EMPTY
           ========================================================= */

        .bidang-empty {
            padding: 55px 20px;

            text-align: center;
        }

        .bidang-empty-icon {
            width: 55px;
            height: 55px;

            margin: 0 auto 12px;

            border-radius: 15px;

            background: #f4f5f7;
            color: #9aa2ad;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 23px;
        }

        .bidang-empty-title {
            color: #4b5565;

            font-size: 14px;
            font-weight: 700;
        }

        .bidang-empty-text {
            margin-top: 4px;

            color: #9aa2ad;

            font-size: 11px;
        }


        /* =========================================================
           PAGINATION
           ========================================================= */

        .bidang-pagination {
            width: 100%;

            padding: 15px 19px;

            border-top:
                1px solid #edf0f4;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
        }

        .bidang-pagination-info {
            color: #7b8491;

            font-size: 11px;

            white-space: nowrap;
        }

        .bidang-pagination-nav {
            display: flex;
            align-items: center;

            gap: 5px;

            flex-wrap: nowrap;
        }

        .bidang-pagination-link {
            min-width: 36px;
            height: 34px;

            padding: 0 10px;

            border:
                1px solid #dfe4eb;

            border-radius: 8px;

            background: #fff;

            color: #536174;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            font-size: 11px;
            font-weight: 600;

            text-decoration: none;

            transition: .15s ease;
        }

        .bidang-pagination-link:hover {
            background: #f8f9fb;
            border-color: #cdd3db;
            color: #273449;
        }

        .bidang-pagination-link.active {
            border-color: #df8339;

            background:
                linear-gradient(135deg,
                    #df8339,
                    #c35e1d);

            color: #fff;
        }

        .bidang-pagination-link.disabled {
            color: #c0c6cf;
            background: #f8f9fb;

            pointer-events: none;
        }

        .bidang-pagination-dots {
            min-width: 28px;

            text-align: center;

            color: #98a1ad;

            font-size: 12px;
        }


        /* =========================================================
           MODAL
           ========================================================= */

        .bidang-modal .modal-content {
            border: 0;

            border-radius: 17px;

            overflow: hidden;

            box-shadow:
                0 25px 70px rgba(0, 0, 0, .20);
        }

        .bidang-modal .modal-header {
            padding: 18px 21px;

            border: 0;

            background:
                linear-gradient(135deg,
                    #14223b,
                    #1d3558);

            color: #fff;
        }

        .bidang-modal .modal-title {
            font-size: 16px;
            font-weight: 800;
        }

        .bidang-modal .modal-subtitle {
            margin-top: 4px;

            color: rgba(255, 255, 255, .52);

            font-size: 10px;
        }

        .bidang-modal .btn-close {
            filter:
                brightness(0) invert(1);

            opacity: .7;
        }

        .bidang-modal .modal-body {
            padding: 21px;
        }

        .bidang-modal .modal-footer {
            padding: 14px 21px;

            border-top:
                1px solid #edf0f4;
        }

        .bidang-form-label {
            display: block;

            margin-bottom: 7px;

            color: #475467;

            font-size: 11px;
            font-weight: 700;
        }

        .bidang-form-control {
            width: 100%;
            height: 42px;

            border:
                1px solid #dfe4eb;

            border-radius: 9px;

            padding: 0 12px;

            color: #344054;
            background: #fff;

            font-size: 12px;

            outline: none;
        }

        .bidang-form-control:focus {
            border-color: #df8339;

            box-shadow:
                0 0 0 3px rgba(223, 131, 57, .08);
        }

        .bidang-form-group {
            margin-bottom: 16px;
        }

        .bidang-status-options {
            display: flex;
            gap: 8px;
        }

        .bidang-status-option {
            flex: 1;

            position: relative;
        }

        .bidang-status-option input {
            position: absolute;

            opacity: 0;

            pointer-events: none;
        }

        .bidang-status-option label {
            display: flex;

            align-items: center;
            justify-content: center;

            height: 42px;

            border:
                1px solid #dfe4eb;

            border-radius: 9px;

            color: #737d8c;

            font-size: 12px;
            font-weight: 600;

            cursor: pointer;

            transition: .15s ease;
        }

        .bidang-status-option input:checked+label {
            border-color: #df8339;

            background: #fff8f2;

            color: #c86520;
        }

        .bidang-submit-button {
            border: 0;

            border-radius: 8px;

            padding: 10px 16px;

            background:
                linear-gradient(135deg,
                    #df8339,
                    #c35e1d);

            color: #fff;

            font-size: 12px;
            font-weight: 700;
        }

        .bidang-cancel-button {
            border:
                1px solid #dfe4eb;

            border-radius: 8px;

            padding: 10px 16px;

            background: #fff;

            color: #667085;

            font-size: 12px;
            font-weight: 600;
        }


        /* =========================================================
           DELETE MODAL
           ========================================================= */

        .bidang-delete-icon {
            width: 55px;
            height: 55px;

            margin: 0 auto 13px;

            border-radius: 15px;

            background: #fff0f0;

            color: #d33;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 23px;
        }

        .bidang-delete-title {
            color: #273449;

            font-size: 16px;
            font-weight: 800;
        }

        .bidang-delete-text {
            margin-top: 7px;

            color: #7b8491;

            font-size: 11px;

            line-height: 1.6;
        }


        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 900px) {

            .bidang-stat-grid {
                grid-template-columns:
                    repeat(3, minmax(0, 1fr));
            }

            .bidang-header {
                align-items: flex-start;
            }

        }

        @media (max-width: 750px) {

            .bidang-header {
                flex-direction: column;
            }

            .bidang-header-actions {
                width: 100%;
            }

            .bidang-add-button,
            .bidang-import-button {
                flex: 1;
            }

            .bidang-stat-grid {
                grid-template-columns: 1fr;
            }

            .bidang-panel-header {
                align-items: stretch;
                flex-direction: column;
            }

            .bidang-filter {
                max-width: none;
            }

            .bidang-pagination {
                align-items: flex-start;
                flex-direction: column;
            }

        }

        @media (max-width: 550px) {

            .bidang-header-actions {
                flex-direction: column;
            }

            .bidang-add-button,
            .bidang-import-button {
                width: 100%;
            }

            .bidang-filter {
                flex-direction: column;
            }

            .bidang-search,
            .bidang-filter-select {
                width: 100%;
            }

            .bidang-pagination-nav {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 2px;
            }

        }
    </style>

@endsection


@section('content')

    <div class="bidang-page">

        {{-- =========================================================
         ALERT
    ========================================================== --}}

        @if (session('success'))
            <div class="alert alert-success bidang-alert">

                <i class="bi bi-check-circle me-1"></i>

                {{ session('success') }}

            </div>
        @endif


        @if ($errors->any())

            <div class="alert alert-danger bidang-alert">

                <div>
                    <i class="bi bi-exclamation-circle me-1"></i>
                    Terjadi kesalahan.
                </div>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif


        {{-- =========================================================
         HEADER
    ========================================================== --}}

        <div class="bidang-header">

            <div class="bidang-header-left">

                <h1 class="bidang-header-title">
                    Manajemen Bidang
                </h1>

                <p class="bidang-header-description">
                    Kelola data bidang pada SAMPERIN.
                </p>

            </div>


            <div class="bidang-header-actions">

                <button type="button" class="bidang-add-button" data-bs-toggle="modal" data-bs-target="#bidangCreateModal">

                    <i class="bi bi-plus-lg"></i>

                    Tambah Bidang

                </button>


                <a href="{{ route('master.bidang.import') }}" class="bidang-import-button">

                    <i class="bi bi-upload"></i>

                    Import

                </a>

            </div>

        </div>


        {{-- =========================================================
         STATISTIK
    ========================================================== --}}

        <div class="bidang-stat-grid">

            <div class="bidang-stat">

                <div class="bidang-stat-icon">

                    <i class="bi bi-diagram-3-fill"></i>

                </div>

                <div class="bidang-stat-content">

                    <div class="bidang-stat-label">
                        Total Bidang
                    </div>

                    <div class="bidang-stat-number">
                        {{ $totalBidang }}
                    </div>

                </div>

            </div>


            <div class="bidang-stat">

                <div class="bidang-stat-icon">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <div class="bidang-stat-content">

                    <div class="bidang-stat-label">
                        Bidang Aktif
                    </div>

                    <div class="bidang-stat-number">
                        {{ $bidangAktif }}
                    </div>

                </div>

            </div>


            <div class="bidang-stat">

                <div class="bidang-stat-icon">

                    <i class="bi bi-slash-circle-fill"></i>

                </div>

                <div class="bidang-stat-content">

                    <div class="bidang-stat-label">
                        Bidang Nonaktif
                    </div>

                    <div class="bidang-stat-number">
                        {{ $bidangNonaktif }}
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
         TABLE PANEL
    ========================================================== --}}

        <div class="bidang-panel">


            {{-- FILTER --}}

            <div class="bidang-panel-header">

                <form method="GET" action="{{ route('master.bidang.index') }}" class="bidang-filter">

                    <div class="bidang-search">

                        <i class="bi bi-search"></i>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode atau nama bidang...">

                    </div>


                    <select name="status" class="bidang-filter-select" onchange="this.form.submit()">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                            Nonaktif
                        </option>

                    </select>

                </form>

            </div>


            {{-- TABLE --}}

            <div class="bidang-table-wrapper">

                <table class="bidang-table">

                    <thead>

                        <tr>

                            <th>
                                Bidang
                            </th>

                            <th>
                                Kode
                            </th>

                            <th>
                                Status
                            </th>

                            <th style="text-align:right;">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($bidangs as $bidang)
                            <tr>

                                {{-- BIDANG --}}

                                <td>

                                    <div class="bidang-name">
                                        {{ $bidang->bidang_nama }}
                                    </div>

                                    <div class="bidang-uid" title="{{ $bidang->bidang_uid }}">
                                        {{ $bidang->bidang_uid }}
                                    </div>

                                </td>


                                {{-- KODE --}}

                                <td>

                                    <span class="bidang-kode">
                                        {{ $bidang->bidang_kode }}
                                    </span>

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if ((int) $bidang->bidang_status === 1)
                                        <span class="bidang-status active">

                                            <span class="bidang-status-dot"></span>

                                            Aktif

                                        </span>
                                    @else
                                        <span class="bidang-status inactive">

                                            <span class="bidang-status-dot"></span>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                {{-- AKSI --}}

                                <td>

                                    <div class="bidang-actions">


                                        {{-- EDIT --}}

                                        <button type="button" class="bidang-action-button" data-bs-toggle="modal"
                                            data-bs-target="#bidangEditModal{{ $bidang->bidang_id }}" title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        {{-- STATUS --}}

                                        <form method="POST"
                                            action="{{ route('master.bidang.status', $bidang->bidang_uid) }}"
                                            style="margin:0;">

                                            @csrf

                                            @method('PATCH')

                                            <button type="submit" class="bidang-action-button warning"
                                                title="{{ (int) $bidang->bidang_status === 1 ? 'Nonaktifkan' : 'Aktifkan' }}">

                                                @if ((int) $bidang->bidang_status === 1)
                                                    <i class="bi bi-pause-circle"></i>
                                                @else
                                                    <i class="bi bi-play-circle"></i>
                                                @endif

                                            </button>

                                        </form>


                                        {{-- DELETE --}}

                                        <button type="button" class="bidang-action-button danger" data-bs-toggle="modal"
                                            data-bs-target="#bidangDeleteModal{{ $bidang->bidang_id }}" title="Hapus">

                                            <i class="bi bi-trash3"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>


                            {{-- =================================================
                             EDIT MODAL
                        ================================================== --}}

                            <div class="modal fade bidang-modal" id="bidangEditModal{{ $bidang->bidang_id }}"
                                tabindex="-1" aria-hidden="true">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form method="POST"
                                            action="{{ route('master.bidang.update', $bidang->bidang_uid) }}">

                                            @csrf

                                            @method('PUT')


                                            <div class="modal-header">

                                                <div>

                                                    <div class="modal-title">
                                                        Edit Bidang
                                                    </div>

                                                    <div class="modal-subtitle">
                                                        Perbarui data bidang SAMPERIN.
                                                    </div>

                                                </div>

                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                            </div>


                                            <div class="modal-body">


                                                {{-- KODE --}}

                                                <div class="bidang-form-group">

                                                    <label class="bidang-form-label">
                                                        Kode Bidang
                                                    </label>

                                                    <input type="text" name="bidang_kode" class="bidang-form-control"
                                                        value="{{ $bidang->bidang_kode }}" required>

                                                </div>


                                                {{-- NAMA --}}

                                                <div class="bidang-form-group">

                                                    <label class="bidang-form-label">
                                                        Nama Bidang
                                                    </label>

                                                    <input type="text" name="bidang_nama" class="bidang-form-control"
                                                        value="{{ $bidang->bidang_nama }}" required>

                                                </div>


                                                {{-- STATUS --}}

                                                <div class="bidang-form-group">

                                                    <label class="bidang-form-label">
                                                        Status
                                                    </label>

                                                    <div class="bidang-status-options">


                                                        <div class="bidang-status-option">

                                                            <input type="radio" name="bidang_status"
                                                                id="editAktif{{ $bidang->bidang_id }}" value="1"
                                                                {{ (int) $bidang->bidang_status === 1 ? 'checked' : '' }}>

                                                            <label for="editAktif{{ $bidang->bidang_id }}">
                                                                Aktif
                                                            </label>

                                                        </div>


                                                        <div class="bidang-status-option">

                                                            <input type="radio" name="bidang_status"
                                                                id="editNonaktif{{ $bidang->bidang_id }}" value="0"
                                                                {{ (int) $bidang->bidang_status === 0 ? 'checked' : '' }}>

                                                            <label for="editNonaktif{{ $bidang->bidang_id }}">
                                                                Nonaktif
                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button type="button" class="bidang-cancel-button"
                                                    data-bs-dismiss="modal">
                                                    Batal
                                                </button>

                                                <button type="submit" class="bidang-submit-button">
                                                    Simpan Perubahan
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                             DELETE MODAL
                        ================================================== --}}

                            <div class="modal fade bidang-modal" id="bidangDeleteModal{{ $bidang->bidang_id }}"
                                tabindex="-1" aria-hidden="true">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form method="POST"
                                            action="{{ route('master.bidang.destroy', $bidang->bidang_uid) }}">

                                            @csrf

                                            @method('DELETE')


                                            <div class="modal-body text-center">

                                                <div class="bidang-delete-icon">

                                                    <i class="bi bi-trash3"></i>

                                                </div>

                                                <div class="bidang-delete-title">
                                                    Hapus Bidang?
                                                </div>

                                                <div class="bidang-delete-text">

                                                    Anda akan menghapus bidang

                                                    <strong>
                                                        {{ $bidang->bidang_nama }}
                                                    </strong>.

                                                    Data yang sudah dihapus tidak dapat
                                                    dikembalikan.

                                                </div>

                                            </div>


                                            <div class="modal-footer justify-content-center">

                                                <button type="button" class="bidang-cancel-button"
                                                    data-bs-dismiss="modal">
                                                    Batal
                                                </button>

                                                <button type="submit" class="btn btn-danger"
                                                    style="
                                                    border-radius:8px;
                                                    padding:10px 16px;
                                                    font-size:12px;
                                                    font-weight:700;
                                                ">
                                                    Ya, Hapus

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <tr>

                                <td colspan="4">

                                    <div class="bidang-empty">

                                        <div class="bidang-empty-icon">

                                            <i class="bi bi-diagram-3"></i>

                                        </div>

                                        <div class="bidang-empty-title">
                                            Belum Ada Data Bidang
                                        </div>

                                        <div class="bidang-empty-text">
                                            Belum terdapat data bidang yang dapat ditampilkan.
                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =========================================================
             PAGINATION
        ========================================================== --}}

            @if ($bidangs->total() > 0)

                <div class="bidang-pagination">

                    <div class="bidang-pagination-info">

                        Menampilkan

                        <strong>
                            {{ $bidangs->firstItem() }}
                        </strong>

                        -

                        <strong>
                            {{ $bidangs->lastItem() }}
                        </strong>

                        dari

                        <strong>
                            {{ $bidangs->total() }}
                        </strong>

                        data

                    </div>


                    @if ($bidangs->hasPages())

                        <div class="bidang-pagination-nav">

                            {{-- PREVIOUS --}}

                            @if ($bidangs->onFirstPage())
                                <span class="bidang-pagination-link disabled">

                                    <i class="bi bi-chevron-left me-1"></i>

                                    Sebelumnya

                                </span>
                            @else
                                <a href="{{ $bidangs->previousPageUrl() }}" class="bidang-pagination-link">

                                    <i class="bi bi-chevron-left me-1"></i>

                                    Sebelumnya

                                </a>
                            @endif


                            {{-- NUMBER --}}

                            @foreach ($bidangs->getUrlRange(max(1, $bidangs->currentPage() - 2), min($bidangs->lastPage(), $bidangs->currentPage() + 2)) as $page => $url)
                                @if ($page == $bidangs->currentPage())
                                    <span class="bidang-pagination-link active">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="bidang-pagination-link">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach


                            {{-- NEXT --}}

                            @if ($bidangs->hasMorePages())
                                <a href="{{ $bidangs->nextPageUrl() }}" class="bidang-pagination-link">

                                    Selanjutnya

                                    <i class="bi bi-chevron-right ms-1"></i>

                                </a>
                            @else
                                <span class="bidang-pagination-link disabled">

                                    Selanjutnya

                                    <i class="bi bi-chevron-right ms-1"></i>

                                </span>
                            @endif

                        </div>

                    @endif

                </div>

            @endif

        </div>

    </div>


    {{-- =============================================================
     CREATE MODAL
============================================================== --}}

    <div class="modal fade bidang-modal" id="bidangCreateModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form method="POST" action="{{ route('master.bidang.store') }}">

                    @csrf


                    <div class="modal-header">

                        <div>

                            <div class="modal-title">
                                Tambah Bidang
                            </div>

                            <div class="modal-subtitle">
                                Tambahkan bidang baru ke SAMPERIN.
                            </div>

                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                    </div>


                    <div class="modal-body">


                        {{-- KODE --}}

                        <div class="bidang-form-group">

                            <label class="bidang-form-label">
                                Kode Bidang
                            </label>

                            <input type="text" name="bidang_kode" class="bidang-form-control"
                                value="{{ old('bidang_kode') }}" placeholder="Contoh: data.sekretariat" required>

                        </div>


                        {{-- NAMA --}}

                        <div class="bidang-form-group">

                            <label class="bidang-form-label">
                                Nama Bidang
                            </label>

                            <input type="text" name="bidang_nama" class="bidang-form-control"
                                value="{{ old('bidang_nama') }}" placeholder="Contoh: Sekretariat" required>

                        </div>


                        {{-- STATUS --}}

                        <div class="bidang-form-group">

                            <label class="bidang-form-label">
                                Status
                            </label>

                            <div class="bidang-status-options">


                                <div class="bidang-status-option">

                                    <input type="radio" name="bidang_status" id="createAktif" value="1"
                                        {{ old('bidang_status', '1') == '1' ? 'checked' : '' }}>

                                    <label for="createAktif">
                                        Aktif
                                    </label>

                                </div>


                                <div class="bidang-status-option">

                                    <input type="radio" name="bidang_status" id="createNonaktif" value="0"
                                        {{ old('bidang_status') === '0' ? 'checked' : '' }}>

                                    <label for="createNonaktif">
                                        Nonaktif
                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="bidang-cancel-button" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="bidang-submit-button">
                            Tambah Bidang
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
