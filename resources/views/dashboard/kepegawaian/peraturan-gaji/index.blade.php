@extends('dashboard.layouts.app')

@section('title', 'Peraturan Gaji')

@section('header-title', 'Peraturan Gaji')

@section('breadcrumb', 'Peraturan Gaji')

@section('page-style')

    <style>
        .gaji-page {
            width: 100%;
            max-width: 100%;
        }

        /* =========================================================
               HEADER
               ========================================================= */

        .gaji-header {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .gaji-header-left {
            min-width: 0;
        }

        .gaji-header-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
            line-height: 1.2;
        }

        .gaji-header-description {
            margin: 6px 0 0;
            color: #8993a3;
            font-size: 13px;
        }

        .gaji-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .gaji-add-button {
            height: 44px;
            padding: 0 17px;
            border-radius: 10px;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            border: 0;

            background:
                linear-gradient(135deg,
                    #df8339,
                    #c35e1d);

            color: #fff;

            font-size: 13px;
            font-weight: 700;

            text-decoration: none;
            cursor: pointer;

            transition: .15s ease;

            box-shadow:
                0 7px 18px rgba(195, 94, 29, .18);
        }

        .gaji-add-button:hover {
            color: #fff;
            transform: translateY(-1px);

            box-shadow:
                0 9px 22px rgba(195, 94, 29, .25);
        }


        /* =========================================================
               ALERT
               ========================================================= */

        .gaji-alert {
            border: 0;
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 18px;
            font-size: 12px;
        }

        .gaji-alert ul {
            margin: 5px 0 0;
            padding-left: 18px;
        }


        /* =========================================================
               STATISTIK
               ========================================================= */

        .gaji-stat-grid {
            width: 100%;

            display: grid;
            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 14px;
            margin-bottom: 18px;
        }

        .gaji-stat {
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

        .gaji-stat-icon {
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

        .gaji-stat-content {
            min-width: 0;
        }

        .gaji-stat-label {
            color: #929ba9;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .gaji-stat-number {
            color: #273449;
            font-size: 21px;
            font-weight: 800;
            line-height: 1.1;
        }


        /* =========================================================
               PANEL
               ========================================================= */

        .gaji-panel {
            width: 100%;

            background: #fff;

            border:
                1px solid #e6eaf0;

            border-radius: 16px;

            overflow: hidden;

            box-shadow:
                0 8px 25px rgba(20, 35, 60, .04);
        }

        .gaji-panel-header {
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

        .gaji-filter {
            width: 100%;
            max-width: 600px;

            display: flex;
            align-items: center;
            gap: 8px;
        }

        .gaji-search {
            position: relative;
            flex: 1;
            min-width: 0;
        }

        .gaji-search i {
            position: absolute;

            left: 12px;
            top: 50%;

            transform: translateY(-50%);

            color: #9aa3b0;
            font-size: 15px;

            pointer-events: none;
        }

        .gaji-search input {
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

        .gaji-search input:focus {
            border-color: #df8339;

            box-shadow:
                0 0 0 3px rgba(223, 131, 57, .08);
        }

        .gaji-filter-select {
            width: 125px;
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

        .gaji-filter-select:focus {
            border-color: #df8339;

            box-shadow:
                0 0 0 3px rgba(223, 131, 57, .08);
        }


        /* =========================================================
               TABLE
               ========================================================= */

        .gaji-table-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .gaji-table {
            width: 100%;
            min-width: 900px;

            border-collapse: collapse;
            table-layout: fixed;
        }

        .gaji-table th {
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

        .gaji-table td {
            padding: 15px 18px;

            border-bottom:
                1px solid #f0f2f5;

            color: #475467;

            font-size: 13px;

            vertical-align: middle;
        }

        .gaji-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .gaji-table tbody tr:hover {
            background: #fcfcfd;
        }

        .gaji-table th:nth-child(1),
        .gaji-table td:nth-child(1) {
            width: 32%;
        }

        .gaji-table th:nth-child(2),
        .gaji-table td:nth-child(2) {
            width: 21%;
        }

        .gaji-table th:nth-child(3),
        .gaji-table td:nth-child(3) {
            width: 10%;
        }

        .gaji-table th:nth-child(4),
        .gaji-table td:nth-child(4) {
            width: 13%;
        }

        .gaji-table th:nth-child(5),
        .gaji-table td:nth-child(5) {
            width: 12%;
        }

        .gaji-table th:nth-child(6),
        .gaji-table td:nth-child(6) {
            width: 12%;
        }


        /* =========================================================
               PERATURAN
               ========================================================= */

        .gaji-name {
            color: #273449;

            font-size: 14px;
            font-weight: 750;

            line-height: 1.35;
        }

        .gaji-uid {
            margin-top: 4px;

            color: #a0a8b4;

            font-size: 10px;
            font-family: monospace;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .gaji-nomor {
            color: #667085;

            font-size: 12px;
            line-height: 1.4;
        }

        .gaji-tahun {
            display: inline-block;

            padding: 5px 8px;

            border-radius: 6px;

            background: #f3f5f8;

            color: #6d7683;

            font-family: monospace;

            font-size: 10px;
        }

        .gaji-count {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            min-width: 32px;
            height: 26px;

            padding: 0 8px;

            border-radius: 7px;

            background: #fff0e3;

            color: #c86520;

            font-size: 11px;
            font-weight: 700;
        }


        /* =========================================================
               STATUS
               ========================================================= */

        .gaji-status {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 6px 9px;

            border-radius: 20px;

            font-size: 10px;
            font-weight: 700;
        }

        .gaji-status.active {
            color: #247a4b;
            background: #eaf8f0;
        }

        .gaji-status.inactive {
            color: #7b8491;
            background: #f0f2f5;
        }

        .gaji-status-dot {
            width: 6px;
            height: 6px;

            border-radius: 50%;

            background: currentColor;
        }


        /* =========================================================
               ACTION
               ========================================================= */

        .gaji-actions {
            display: flex;

            align-items: center;
            justify-content: flex-end;

            gap: 6px;
        }

        .gaji-action-button {
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

            text-decoration: none;
        }

        .gaji-action-button:hover {
            border-color: #cdd3db;

            background: #f8f9fb;

            color: #273449;
        }

        .gaji-action-button.warning:hover {
            background: #fff8e8;
            color: #b87800;
            border-color: #efd99d;
        }


        /* =========================================================
               EMPTY
               ========================================================= */

        .gaji-empty {
            padding: 55px 20px;

            text-align: center;
        }

        .gaji-empty-icon {
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

        .gaji-empty-title {
            color: #4b5565;

            font-size: 14px;
            font-weight: 700;
        }

        .gaji-empty-text {
            margin-top: 4px;

            color: #9aa2ad;

            font-size: 11px;
        }


        /* =========================================================
               PAGINATION
               ========================================================= */

        .gaji-pagination {
            width: 100%;

            padding: 15px 19px;

            border-top:
                1px solid #edf0f4;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
        }

        .gaji-pagination-info {
            color: #7b8491;

            font-size: 11px;

            white-space: nowrap;
        }

        .gaji-pagination-nav {
            display: flex;
            align-items: center;

            gap: 5px;

            flex-wrap: nowrap;
        }

        .gaji-pagination-link {
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

        .gaji-pagination-link:hover {
            background: #f8f9fb;
            border-color: #cdd3db;
            color: #273449;
        }

        .gaji-pagination-link.active {
            border-color: #df8339;

            background:
                linear-gradient(135deg,
                    #df8339,
                    #c35e1d);

            color: #fff;
        }

        .gaji-pagination-link.disabled {
            color: #c0c6cf;
            background: #f8f9fb;

            pointer-events: none;
        }


        /* =========================================================
               MODAL
               ========================================================= */

        .gaji-modal .modal-content {
            border: 0;

            border-radius: 17px;

            overflow: hidden;

            box-shadow:
                0 25px 70px rgba(0, 0, 0, .20);
        }

        .gaji-modal .modal-header {
            padding: 18px 21px;

            border: 0;

            background:
                linear-gradient(135deg,
                    #14223b,
                    #1d3558);

            color: #fff;
        }

        .gaji-modal .modal-title {
            font-size: 16px;
            font-weight: 800;
        }

        .gaji-modal .modal-subtitle {
            margin-top: 4px;

            color: rgba(255, 255, 255, .52);

            font-size: 10px;
        }

        .gaji-modal .btn-close {
            filter:
                brightness(0) invert(1);

            opacity: .7;
        }

        .gaji-modal .modal-body {
            padding: 21px;
        }

        .gaji-modal .modal-footer {
            padding: 14px 21px;

            border-top:
                1px solid #edf0f4;
        }

        .gaji-form-group {
            margin-bottom: 16px;
        }

        .gaji-form-label {
            display: block;

            margin-bottom: 7px;

            color: #475467;

            font-size: 11px;
            font-weight: 700;
        }

        .gaji-form-control {
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

        .gaji-form-control:focus {
            border-color: #df8339;

            box-shadow:
                0 0 0 3px rgba(223, 131, 57, .08);
        }

        .gaji-status-options {
            display: flex;
            gap: 8px;
        }

        .gaji-status-option {
            flex: 1;

            position: relative;
        }

        .gaji-status-option input {
            position: absolute;

            opacity: 0;

            pointer-events: none;
        }

        .gaji-status-option label {
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

        .gaji-status-option input:checked+label {
            border-color: #df8339;

            background: #fff8f2;

            color: #c86520;
        }

        .gaji-submit-button {
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

        .gaji-cancel-button {
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
               RESPONSIVE
               ========================================================= */

        @media (max-width: 900px) {

            .gaji-stat-grid {
                grid-template-columns:
                    repeat(3, minmax(0, 1fr));
            }

            .gaji-header {
                align-items: flex-start;
            }

        }

        @media (max-width: 750px) {

            .gaji-header {
                flex-direction: column;
            }

            .gaji-header-actions {
                width: 100%;
            }

            .gaji-add-button {
                flex: 1;
            }

            .gaji-stat-grid {
                grid-template-columns: 1fr;
            }

            .gaji-panel-header {
                align-items: stretch;
                flex-direction: column;
            }

            .gaji-filter {
                max-width: none;
            }

            .gaji-pagination {
                align-items: flex-start;
                flex-direction: column;
            }

        }

        @media (max-width: 550px) {

            .gaji-header-actions {
                width: 100%;
            }

            .gaji-add-button {
                width: 100%;
            }

            .gaji-filter {
                flex-direction: column;
            }

            .gaji-search,
            .gaji-filter-select {
                width: 100%;
            }

            .gaji-pagination-nav {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 2px;
            }

        }
    </style>

@endsection


@section('content')

    <div class="gaji-page">

        {{-- =========================================================
             ALERT
        ========================================================== --}}

        @if (session('success'))
            <div class="alert alert-success gaji-alert">

                <i class="bi bi-check-circle me-1"></i>

                {{ session('success') }}

            </div>
        @endif


        @if ($errors->any())

            <div class="alert alert-danger gaji-alert">

                <div>

                    <i class="bi bi-exclamation-circle me-1"></i>

                    Terjadi kesalahan.

                </div>

                <ul>

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================================
             HEADER
        ========================================================== --}}

        <div class="gaji-header">

            <div class="gaji-header-left">

                <h1 class="gaji-header-title">
                    Peraturan Gaji
                </h1>

                <p class="gaji-header-description">
                    Kelola peraturan dan tarif gaji berdasarkan golongan pada SAMPERIN.
                </p>

            </div>


            <div class="gaji-header-actions">

                <button type="button" class="gaji-add-button" data-bs-toggle="modal" data-bs-target="#gajiCreateModal">

                    <i class="bi bi-plus-lg"></i>

                    Tambah Peraturan

                </button>

            </div>

        </div>


        {{-- =========================================================
             STATISTIK
        ========================================================== --}}

        <div class="gaji-stat-grid">

            <div class="gaji-stat">

                <div class="gaji-stat-icon">

                    <i class="bi bi-file-earmark-text-fill"></i>

                </div>

                <div class="gaji-stat-content">

                    <div class="gaji-stat-label">
                        Total Peraturan
                    </div>

                    <div class="gaji-stat-number">
                        {{ $totalPeraturan }}
                    </div>

                </div>

            </div>


            <div class="gaji-stat">

                <div class="gaji-stat-icon">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <div class="gaji-stat-content">

                    <div class="gaji-stat-label">
                        Peraturan Aktif
                    </div>

                    <div class="gaji-stat-number">
                        {{ $peraturanAktif }}
                    </div>

                </div>

            </div>


            <div class="gaji-stat">

                <div class="gaji-stat-icon">

                    <i class="bi bi-slash-circle-fill"></i>

                </div>

                <div class="gaji-stat-content">

                    <div class="gaji-stat-label">
                        Peraturan Nonaktif
                    </div>

                    <div class="gaji-stat-number">
                        {{ $peraturanNonaktif }}
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             TABLE PANEL
        ========================================================== --}}

        <div class="gaji-panel">

            {{-- FILTER --}}

            <div class="gaji-panel-header">

                <form method="GET" action="{{ route('samperin.admin.peraturan-gaji.index') }}" class="gaji-filter">

                    <div class="gaji-search">

                        <i class="bi bi-search"></i>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau nomor peraturan...">

                    </div>


                    <select name="status" class="gaji-filter-select" onchange="this.form.submit()">

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

            <div class="gaji-table-wrapper">

                <table class="gaji-table">

                    <thead>

                        <tr>

                            <th>
                                Peraturan
                            </th>

                            <th>
                                Nomor
                            </th>

                            <th>
                                Tahun
                            </th>

                            <th>
                                Golongan
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

                        @forelse ($peraturan as $item)
                            <tr>

                                {{-- PERATURAN --}}

                                <td>

                                    <div class="gaji-name">
                                        {{ $item->peraturan_gaji_nama }}
                                    </div>

                                    <div class="gaji-uid" title="{{ $item->peraturan_gaji_uid }}">
                                        {{ $item->peraturan_gaji_uid }}
                                    </div>

                                </td>


                                {{-- NOMOR --}}

                                <td>

                                    <div class="gaji-nomor">
                                        {{ $item->peraturan_gaji_nomor }}
                                    </div>

                                </td>


                                {{-- TAHUN --}}

                                <td>

                                    <span class="gaji-tahun">
                                        {{ $item->peraturan_gaji_tahun }}
                                    </span>

                                </td>


                                {{-- GOLONGAN --}}

                                <td>

                                    <span class="gaji-count">
                                        {{ $item->golongan_count }}
                                    </span>

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if ((int) $item->peraturan_gaji_status === 1)
                                        <span class="gaji-status active">

                                            <span class="gaji-status-dot"></span>

                                            Aktif

                                        </span>
                                    @else
                                        <span class="gaji-status inactive">

                                            <span class="gaji-status-dot"></span>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                {{-- AKSI --}}

                                <td>

                                    <div class="gaji-actions">

                                        {{-- TARIF --}}

                                        <a href="{{ route('samperin.admin.peraturan-gaji.show', $item->peraturan_gaji_id) }}"
                                            class="gaji-action-button" title="Atur Tarif Gaji">

                                            <i class="bi bi-cash-stack"></i>

                                        </a>


                                        {{-- EDIT --}}

                                        <a href="{{ route('samperin.admin.peraturan-gaji.edit', $item->peraturan_gaji_id) }}"
                                            class="gaji-action-button warning" title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        {{-- STATUS --}}

                                        <form method="POST"
                                            action="{{ route('samperin.admin.peraturan-gaji.toggle-status', $item->peraturan_gaji_id) }}"
                                            style="margin:0;">

                                            @csrf

                                            <button type="submit" class="gaji-action-button"
                                                title="{{ (int) $item->peraturan_gaji_status === 1 ? 'Nonaktifkan' : 'Aktifkan' }}">

                                                @if ((int) $item->peraturan_gaji_status === 1)
                                                    <i class="bi bi-pause-circle"></i>
                                                @else
                                                    <i class="bi bi-play-circle"></i>
                                                @endif

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6">

                                    <div class="gaji-empty">

                                        <div class="gaji-empty-icon">

                                            <i class="bi bi-cash-stack"></i>

                                        </div>

                                        <div class="gaji-empty-title">
                                            Belum Ada Peraturan Gaji
                                        </div>

                                        <div class="gaji-empty-text">
                                            Belum terdapat data peraturan gaji yang dapat ditampilkan.
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

            @if ($peraturan->total() > 0)

                <div class="gaji-pagination">

                    <div class="gaji-pagination-info">

                        Menampilkan

                        <strong>
                            {{ $peraturan->firstItem() }}
                        </strong>

                        -

                        <strong>
                            {{ $peraturan->lastItem() }}
                        </strong>

                        dari

                        <strong>
                            {{ $peraturan->total() }}
                        </strong>

                        data

                    </div>


                    @if ($peraturan->hasPages())

                        <div class="gaji-pagination-nav">

                            @if ($peraturan->onFirstPage())
                                <span class="gaji-pagination-link disabled">

                                    <i class="bi bi-chevron-left me-1"></i>

                                    Sebelumnya

                                </span>
                            @else
                                <a href="{{ $peraturan->previousPageUrl() }}" class="gaji-pagination-link">

                                    <i class="bi bi-chevron-left me-1"></i>

                                    Sebelumnya

                                </a>
                            @endif


                            @foreach ($peraturan->getUrlRange(max(1, $peraturan->currentPage() - 2), min($peraturan->lastPage(), $peraturan->currentPage() + 2)) as $page => $url)
                                @if ($page == $peraturan->currentPage())
                                    <span class="gaji-pagination-link active">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="gaji-pagination-link">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach


                            @if ($peraturan->hasMorePages())
                                <a href="{{ $peraturan->nextPageUrl() }}" class="gaji-pagination-link">

                                    Selanjutnya

                                    <i class="bi bi-chevron-right ms-1"></i>

                                </a>
                            @else
                                <span class="gaji-pagination-link disabled">

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
    =============================================================== --}}

    <div class="modal fade gaji-modal" id="gajiCreateModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form method="POST"
                    action="{{ route('samperin.admin.peraturan-gaji.store') }}">

                    @csrf


                    <div class="modal-header">

                        <div>

                            <div class="modal-title">
                                Tambah Peraturan Gaji
                            </div>

                            <div class="modal-subtitle">
                                Tambahkan peraturan gaji baru ke SAMPERIN.
                            </div>

                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                    </div>


                    <div class="modal-body">

                        {{-- NAMA --}}

                        <div class="gaji-form-group">

                            <label class="gaji-form-label">
                                Nama Peraturan
                            </label>

                            <input type="text" name="peraturan_gaji_nama" class="gaji-form-control"
                                value="{{ old('peraturan_gaji_nama') }}" placeholder="Contoh: Peraturan Gaji Tahun 2027"
                                required>

                        </div>


                        {{-- NOMOR --}}

                        <div class="gaji-form-group">

                            <label class="gaji-form-label">
                                Nomor Peraturan
                            </label>

                            <input type="text" name="peraturan_gaji_nomor" class="gaji-form-control"
                                value="{{ old('peraturan_gaji_nomor') }}" placeholder="Nomor peraturan" required>

                        </div>


                        {{-- TAHUN + TANGGAL --}}

                        <div class="row">

                            <div class="col-md-6">

                                <div class="gaji-form-group">

                                    <label class="gaji-form-label">
                                        Tahun
                                    </label>

                                    <input type="number" name="peraturan_gaji_tahun" class="gaji-form-control"
                                        value="{{ old('peraturan_gaji_tahun', date('Y')) }}"
                                        min="2000" max="2100" required>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="gaji-form-group">

                                    <label class="gaji-form-label">
                                        Tanggal
                                    </label>

                                    <input type="date" name="peraturan_gaji_tanggal" class="gaji-form-control"
                                        value="{{ old('peraturan_gaji_tanggal') }}">

                                </div>

                            </div>

                        </div>


                        {{-- STATUS --}}

                        <div class="gaji-form-group">

                            <label class="gaji-form-label">
                                Status
                            </label>

                            <div class="gaji-status-options">

                                <div class="gaji-status-option">

                                    <input type="radio" name="peraturan_gaji_status" id="gajiAktif" value="1"
                                        {{ old('peraturan_gaji_status', '1') == '1' ? 'checked' : '' }}>

                                    <label for="gajiAktif">
                                        Aktif
                                    </label>

                                </div>


                                <div class="gaji-status-option">

                                    <input type="radio" name="peraturan_gaji_status" id="gajiNonaktif" value="0"
                                        {{ old('peraturan_gaji_status') === '0' ? 'checked' : '' }}>

                                    <label for="gajiNonaktif">
                                        Nonaktif
                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="gaji-cancel-button" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="gaji-submit-button">
                            Tambah Peraturan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
