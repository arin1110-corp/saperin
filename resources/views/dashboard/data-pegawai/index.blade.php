@extends('dashboard.layouts.app')

@section('title', 'Data Pegawai')

@section('header-title', 'Data Pegawai')

@section('breadcrumb', 'Data Pegawai')

@section('page-style')

    <style>
        .pegawai-page {
            width: 100%;
        }

        /* =========================================================
           HEADER
        ========================================================= */

        .pegawai-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .pegawai-header-left {
            min-width: 0;
        }

        .pegawai-header-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
        }

        .pegawai-header-description {
            margin: 6px 0 0;
            color: #8993a3;
            font-size: 13px;
        }

        .pegawai-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .pegawai-add-button,
        .pegawai-import-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 10px;
            padding: 11px 16px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: .15s ease;
        }

        .pegawai-add-button {
            border: 0;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            box-shadow: 0 7px 18px rgba(195, 94, 29, .18);
        }

        .pegawai-add-button:hover {
            transform: translateY(-1px);
            color: #fff;
        }

        .pegawai-import-button {
            border: 1px solid #dfe4eb;
            background: #fff;
            color: #667085;
        }

        .pegawai-import-button:hover {
            background: #f8f9fb;
            color: #273449;
        }

        /* =========================================================
           STATISTIK
        ========================================================= */

        .pegawai-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .pegawai-stat {
            background: #fff;
            border: 1px solid #e7eaf0;
            border-radius: 14px;
            padding: 17px;
            display: flex;
            align-items: center;
            gap: 13px;
            box-shadow: 0 5px 18px rgba(20, 35, 60, .035);
        }

        .pegawai-stat-icon {
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

        .pegawai-stat-label {
            color: #929ba9;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .pegawai-stat-number {
            color: #273449;
            font-size: 21px;
            font-weight: 800;
        }

        /* =========================================================
           PANEL
        ========================================================= */

        .pegawai-panel {
            background: #fff;
            border: 1px solid #e6eaf0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(20, 35, 60, .04);
        }

        .pegawai-panel-header {
            padding: 17px 19px;
            border-bottom: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .pegawai-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
        }

        .pegawai-search {
            position: relative;
            flex: 1;
        }

        .pegawai-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa3b0;
            font-size: 15px;
        }

        .pegawai-search input {
            width: 100%;
            height: 40px;
            padding: 0 12px 0 35px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            outline: none;
            color: #344054;
            font-size: 12px;
        }

        .pegawai-search input:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .pegawai-filter-select {
            height: 40px;
            min-width: 150px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 0 11px;
            color: #4b5565;
            background: #fff;
            font-size: 12px;
            outline: none;
            cursor: pointer;
        }


        /* =========================================================
           STATISTIK DISTRIBUSI
        ========================================================= */

        .pegawai-distribution-panel {
            background: #fff;
            border: 1px solid #e6eaf0;
            border-radius: 16px;
            margin-bottom: 18px;
            padding: 18px;
            box-shadow: 0 8px 25px rgba(20, 35, 60, .04);
        }

        .pegawai-distribution-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 14px;
        }

        .pegawai-distribution-title {
            color: #14223b;
            font-size: 13px;
            font-weight: 800;
        }

        .pegawai-distribution-description {
            margin-top: 3px;
            color: #98a2b3;
            font-size: 10px;
        }

        .pegawai-distribution-tabs {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 4px;
            background: #f4f5f7;
            border-radius: 11px;
            flex-shrink: 0;
        }

        .pegawai-distribution-tab {
            border: 0;
            border-radius: 8px;
            padding: 8px 13px;
            background: transparent;
            color: #7b8491;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
        }

        .pegawai-distribution-tab.active {
            background: #fff;
            color: #c86520;
            box-shadow: 0 3px 10px rgba(20, 35, 60, .07);
        }

        .pegawai-distribution-content {
            display: none;
        }

        .pegawai-distribution-content.active {
            display: block;
        }

        .pegawai-distribution-list {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding: 2px 2px 4px;
            scrollbar-width: thin;
        }

        .pegawai-distribution-card {
            min-width: 260px;
            flex: 1 0 260px;
            min-height: 112px;
            border: 1px solid #e1e6ed;
            border-radius: 13px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #fff;
        }

        .pegawai-distribution-card:hover {
            border-color: #dfc6b3;
        }

        .pegawai-distribution-name {
            color: #344054;
            font-size: 11px;
            font-weight: 750;
            line-height: 1.45;
            min-height: 32px;
        }

        .pegawai-distribution-bottom {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 10px;
        }

        .pegawai-distribution-number {
            color: #14223b;
            font-size: 23px;
            font-weight: 800;
            line-height: 1;
        }

        .pegawai-distribution-label {
            margin-top: 3px;
            color: #98a2b3;
            font-size: 9px;
        }

        .pegawai-distribution-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff0e3;
            color: #d2742d;
            font-size: 15px;
            flex-shrink: 0;
        }

        .pegawai-distribution-empty {
            padding: 22px;
            text-align: center;
            color: #98a2b3;
            font-size: 11px;
            width: 100%;
        }

        @media(max-width: 800px) {
            .pegawai-distribution-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        /* =========================================================
           TABLE
        ========================================================= */

        .pegawai-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .pegawai-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }

        .pegawai-table th {
            padding: 13px 18px;
            background: #fafbfc;
            color: #929aa6;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
            text-align: left;
            white-space: nowrap;
            border-bottom: 1px solid #edf0f4;
        }

        .pegawai-table td {
            padding: 14px 18px;
            border-bottom: 1px solid #f0f2f5;
            color: #475467;
            font-size: 12px;
            vertical-align: middle;
        }

        .pegawai-table tr:last-child td {
            border-bottom: 0;
        }

        .pegawai-table tbody tr:hover {
            background: #fcfcfd;
        }

        .pegawai-nama {
            color: #273449;
            font-size: 13px;
            font-weight: 750;
        }

        .pegawai-nip {
            color: #8b95a3;
            font-size: 10px;
            margin-top: 3px;
            font-family: monospace;
        }

        .pegawai-master {
            color: #475467;
            font-size: 12px;
            font-weight: 600;
        }

        .pegawai-master-small {
            color: #98a2b3;
            font-size: 10px;
            margin-top: 3px;
        }

        .pegawai-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .pegawai-status.active {
            color: #247a4b;
            background: #eaf8f0;
        }

        .pegawai-status.inactive {
            color: #7b8491;
            background: #f0f2f5;
        }

        .pegawai-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /* =========================================================
           ACTION
        ========================================================= */

        .pegawai-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .pegawai-action-button {
            width: 33px;
            height: 33px;
            border: 1px solid #e2e6eb;
            border-radius: 8px;
            background: #fff;
            color: #737d8c;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .15s ease;
        }

        .pegawai-action-button:hover {
            border-color: #cdd3db;
            background: #f8f9fb;
            color: #273449;
        }

        .pegawai-action-button.warning:hover {
            background: #fff8e8;
            color: #b87800;
            border-color: #efd99d;
        }

        .pegawai-action-button.danger:hover {
            background: #fff1f1;
            color: #d33;
            border-color: #efcaca;
        }

        /* =========================================================
           EMPTY
        ========================================================= */

        .pegawai-empty {
            padding: 55px 20px;
            text-align: center;
        }

        .pegawai-empty-icon {
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

        .pegawai-empty-title {
            color: #4b5565;
            font-size: 14px;
            font-weight: 700;
        }

        .pegawai-empty-text {
            margin-top: 4px;
            color: #9aa2ad;
            font-size: 11px;
        }

        /* =========================================================
           PAGINATION
        ========================================================= */

        .pegawai-pagination {
            padding: 15px 18px;
            border-top: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .pegawai-pagination-info {
            color: #8993a3;
            font-size: 11px;
            white-space: nowrap;
        }

        .pegawai-pagination-nav {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .pegawai-pagination-nav a,
        .pegawai-pagination-nav span {
            min-width: 34px;
            height: 34px;
            padding: 0 11px;
            border: 1px solid #dfe4eb;
            border-radius: 8px;
            background: #fff;
            color: #667085;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 11px;
        }

        .pegawai-pagination-nav a:hover {
            background: #fff8f2;
            border-color: #df8339;
            color: #c86520;
        }

        .pegawai-pagination-nav .active {
            border-color: #df8339;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-weight: 700;
        }

        .pegawai-pagination-nav .disabled {
            color: #c2c8d0;
            background: #f8f9fb;
        }

        /* =========================================================
           MODAL
        ========================================================= */

        .pegawai-modal .modal-content {
            border: 0;
            border-radius: 17px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, .20);
        }

        .pegawai-modal .modal-header {
            padding: 18px 21px;
            border: 0;
            background: linear-gradient(135deg, #14223b, #1d3558);
            color: #fff;
        }

        .pegawai-modal .modal-title {
            font-size: 16px;
            font-weight: 800;
        }

        .pegawai-modal .modal-subtitle {
            margin-top: 3px;
            color: rgba(255, 255, 255, .65);
            font-size: 11px;
        }

        .pegawai-modal .btn-close {
            filter: brightness(0) invert(1);
            opacity: .7;
        }

        .pegawai-modal .modal-body {
            padding: 21px;
            max-height: calc(100vh - 210px);
            overflow-y: auto;
        }

        .pegawai-modal .modal-footer {
            padding: 14px 21px;
            border-top: 1px solid #edf0f4;
        }

        /* =========================================================
           FORM
        ========================================================= */

        .pegawai-form-label {
            display: block;
            margin-bottom: 7px;
            color: #475467;
            font-size: 11px;
            font-weight: 700;
        }

        .pegawai-form-label .required {
            color: #d33;
        }

        .pegawai-form-control {
            width: 100%;
            min-height: 42px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 9px 12px;
            color: #344054;
            background-color: #fff;
            font-size: 12px;
            outline: none;
        }

        /*
        |--------------------------------------------------------------------------
        | FIX COMBO BOX
        |--------------------------------------------------------------------------
        */

        select.pegawai-form-control {
            height: 42px;
            min-height: 42px;
            padding: 0 36px 0 12px;
            cursor: pointer;
            appearance: auto;
            -webkit-appearance: auto;
            -moz-appearance: auto;
            background-color: #fff;
            color: #344054;
        }

        select.pegawai-form-control option {
            color: #344054;
            background: #fff;
        }

        .pegawai-form-control:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        textarea.pegawai-form-control {
            min-height: 80px;
            resize: vertical;
            padding-top: 10px;
        }

        .pegawai-form-group {
            margin-bottom: 16px;
        }

        .pegawai-form-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .pegawai-form-section {
            margin: 5px 0 17px;
            padding-bottom: 8px;
            border-bottom: 1px solid #edf0f4;
            color: #14223b;
            font-size: 12px;
            font-weight: 800;
        }

        .pegawai-submit-button {
            border: 0;
            border-radius: 8px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        .pegawai-cancel-button {
            border: 1px solid #dfe4eb;
            border-radius: 8px;
            padding: 10px 16px;
            background: #fff;
            color: #667085;
            font-size: 12px;
            font-weight: 600;
        }

        .pegawai-alert {
            border: 0;
            border-radius: 10px;
            font-size: 12px;
        }

        .pegawai-field-error {
            margin-top: 5px;
            color: #d33;
            font-size: 10px;
        }

        @media(max-width: 1000px) {
            .pegawai-stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media(max-width: 800px) {
            .pegawai-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .pegawai-header-actions {
                width: 100%;
            }

            .pegawai-add-button,
            .pegawai-import-button {
                flex: 1;
            }

            .pegawai-panel-header {
                align-items: stretch;
                flex-direction: column;
            }

            .pegawai-filter {
                flex-direction: column;
            }

            .pegawai-search,
            .pegawai-filter-select {
                width: 100%;
            }

            .pegawai-pagination {
                align-items: flex-start;
                flex-direction: column;
            }

            .pegawai-pagination-nav {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media(max-width: 550px) {
            .pegawai-stat-grid {
                grid-template-columns: 1fr;
            }

            .pegawai-header-actions {
                flex-direction: column;
            }

            .pegawai-add-button,
            .pegawai-import-button {
                width: 100%;
            }

            .pegawai-form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

@endsection


@section('content')

    <div class="pegawai-page">

        {{-- =========================================================
         ALERT
    ========================================================= --}}

        @if (session('success'))
            <div class="alert alert-success pegawai-alert mb-3">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger pegawai-alert mb-3">
                <i class="bi bi-exclamation-circle me-1"></i>

                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif


        {{-- =========================================================
         HEADER
    ========================================================= --}}

        <div class="pegawai-header">

            <div class="pegawai-header-left">

                <h1 class="pegawai-header-title">
                    Data Pegawai
                </h1>

                <p class="pegawai-header-description">
                    Kelola data pegawai SAMPERIN.
                </p>

            </div>


            <div class="pegawai-header-actions">

                <button type="button" class="pegawai-add-button" data-bs-toggle="modal"
                    data-bs-target="#pegawaiCreateModal">

                    <i class="bi bi-person-plus"></i>

                    Tambah Pegawai

                </button>


                <a href="{{ route('kepeg.pegawai.import') }}" class="pegawai-import-button">

                    <i class="bi bi-upload"></i>

                    Import

                </a>

            </div>

        </div>


        {{-- =========================================================
         STATISTIK
    ========================================================= --}}

        <div class="pegawai-stat-grid">

            <div class="pegawai-stat">

                <div class="pegawai-stat-icon">
                    <i class="bi bi-people"></i>
                </div>

                <div>

                    <div class="pegawai-stat-label">
                        Total Pegawai
                    </div>

                    <div class="pegawai-stat-number">
                        {{ $totalPegawai ?? $pegawais->total() }}
                    </div>

                </div>

            </div>


            <div class="pegawai-stat">

                <div class="pegawai-stat-icon">
                    <i class="bi bi-person-check"></i>
                </div>

                <div>

                    <div class="pegawai-stat-label">
                        Pegawai Aktif
                    </div>

                    <div class="pegawai-stat-number">
                        {{ $pegawaiAktif ?? 0 }}
                    </div>

                </div>

            </div>


            <div class="pegawai-stat">

                <div class="pegawai-stat-icon">
                    <i class="bi bi-person-x"></i>
                </div>

                <div>

                    <div class="pegawai-stat-label">
                        Pegawai Nonaktif
                    </div>

                    <div class="pegawai-stat-number">
                        {{ $pegawaiNonaktif ?? 0 }}
                    </div>

                </div>

            </div>


            <div class="pegawai-stat">

                <div class="pegawai-stat-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>

                <div>

                    <div class="pegawai-stat-label">
                        Dengan NIP
                    </div>

                    <div class="pegawai-stat-number">
                        {{ $pegawaiDenganNip ?? $pegawais->whereNotNull('user_nip')->count() }}
                    </div>

                </div>

            </div>

        </div>




        {{-- =========================================================
         STATISTIK JENIS KERJA / BIDANG
    ========================================================= --}}
        <div class="pegawai-distribution-panel">

            <div class="pegawai-distribution-header">
                <div>
                    <div class="pegawai-distribution-title">
                        Distribusi Pegawai Aktif
                    </div>

                    <div class="pegawai-distribution-description">
                        Jumlah pegawai aktif berdasarkan jenis kerja dan bidang.
                    </div>
                </div>

                <div class="pegawai-distribution-tabs">

                    <button type="button"
                        class="pegawai-distribution-tab {{ request('tab', 'jenis-kerja') === 'jenis-kerja' ? 'active' : '' }}"
                        data-target="jenis-kerja">
                        Jenis Kerja
                    </button>

                    <button type="button"
                        class="pegawai-distribution-tab {{ request('tab') === 'bidang' ? 'active' : '' }}"
                        data-target="bidang">
                        Bidang
                    </button>

                    <button type="button"
                        class="pegawai-distribution-tab {{ request('tab') === 'lokasi' ? 'active' : '' }}"
                        data-target="lokasi">
                        Lokasi Kerja
                    </button>

                </div>
            </div>


            {{-- ================================================================
            | JENIS KERJA
            ================================================================= --}}

            <div id="pegawai-distribution-jenis-kerja"
                class="pegawai-distribution-content {{ request('tab', 'jenis-kerja') === 'jenis-kerja' ? 'active' : '' }}">

                <div class="pegawai-distribution-list">

                    @forelse ($statJenisKerja ?? [] as $stat)

                        <a href="{{ route('kepeg.pegawai.index', [
                            'status' => 1,
                            'jenis_kerja' => $stat->jenis_kerja_id,
                            'tab' => 'jenis-kerja',
                        ]) }}"
                            class="pegawai-distribution-card"
                            style="text-decoration: none; color: inherit;">

                            <div class="pegawai-distribution-name">
                                {{ $stat->jenis_kerja_nama }}
                            </div>

                            <div class="pegawai-distribution-bottom">

                                <div>
                                    <div class="pegawai-distribution-number">
                                        {{ $stat->jumlah }}
                                    </div>

                                    <div class="pegawai-distribution-label">
                                        pegawai aktif
                                    </div>
                                </div>

                                <div class="pegawai-distribution-icon">
                                    <i class="bi bi-briefcase"></i>
                                </div>

                            </div>

                        </a>

                    @empty

                        <div class="pegawai-distribution-empty">
                            Belum ada data jenis kerja aktif.
                        </div>

                    @endforelse

                </div>

            </div>


            {{-- ================================================================
            | BIDANG
            ================================================================= --}}

            <div id="pegawai-distribution-bidang"
                class="pegawai-distribution-content {{ request('tab') === 'bidang' ? 'active' : '' }}">

                <div class="pegawai-distribution-list">

                    @forelse ($statBidang ?? [] as $stat)

                        <a href="{{ route('kepeg.pegawai.index', [
                            'status' => 1,
                            'bidang' => $stat->bidang_id,
                            'tab' => 'bidang',
                        ]) }}"
                            class="pegawai-distribution-card"
                            style="text-decoration: none; color: inherit;">

                            <div class="pegawai-distribution-name">
                                {{ $stat->bidang_nama }}
                            </div>

                            <div class="pegawai-distribution-bottom">

                                <div>
                                    <div class="pegawai-distribution-number">
                                        {{ $stat->jumlah }}
                                    </div>

                                    <div class="pegawai-distribution-label">
                                        pegawai aktif
                                    </div>
                                </div>

                                <div class="pegawai-distribution-icon">
                                    <i class="bi bi-building"></i>
                                </div>

                            </div>

                        </a>

                    @empty

                        <div class="pegawai-distribution-empty">
                            Belum ada data bidang aktif.
                        </div>

                    @endforelse

                </div>

            </div>


            {{-- ================================================================
            | LOKASI KERJA
            ================================================================= --}}

            <div id="pegawai-distribution-lokasi"
                class="pegawai-distribution-content {{ request('tab') === 'lokasi' ? 'active' : '' }}">

                {{-- KANTOR --}}
                <div class="pegawai-distribution-section-title">
                    Kantor
                </div>

                <div class="pegawai-distribution-list">

                    @foreach (($statLokasi ?? []) as $stat)

                        @if (
                            !str_starts_with(strtolower(trim($stat->user_lokasikerja)), 'kabupaten ')
                            && !str_starts_with(strtolower(trim($stat->user_lokasikerja)), 'kota ')
                        )

                            <a href="{{ route('kepeg.pegawai.index', [
                                'status' => 1,
                                'lokasi_kerja' => $stat->user_lokasikerja,
                                'tab' => 'lokasi',
                            ]) }}"
                                class="pegawai-distribution-card"
                                style="text-decoration:none;color:inherit;">

                                <div class="pegawai-distribution-name">
                                    {{ $stat->user_lokasikerja }}
                                </div>

                                <div class="pegawai-distribution-bottom">

                                    <div>
                                        <div class="pegawai-distribution-number">
                                            {{ $stat->jumlah }}
                                        </div>

                                        <div class="pegawai-distribution-label">
                                            pegawai aktif
                                        </div>
                                    </div>

                                    <div class="pegawai-distribution-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>

                                </div>

                            </a>

                        @endif

                    @endforeach

                </div>


                {{-- KABUPATEN --}}
                <div class="pegawai-distribution-section-title" style="margin-top: 24px;">
                    Kabupaten
                </div>

                <div class="pegawai-distribution-list">

                    @foreach (($statLokasi ?? []) as $stat)

                        @if (
                            str_starts_with(strtolower(trim($stat->user_lokasikerja)), 'kabupaten ')
                            || str_starts_with(strtolower(trim($stat->user_lokasikerja)), 'kota ')
                        )

                            <a href="{{ route('kepeg.pegawai.index', [
                                'status' => 1,
                                'lokasi_kerja' => $stat->user_lokasikerja,
                                'tab' => 'lokasi',
                            ]) }}"
                                class="pegawai-distribution-card"
                                style="text-decoration:none;color:inherit;">

                                <div class="pegawai-distribution-name">
                                    {{ $stat->user_lokasikerja }}
                                </div>

                                <div class="pegawai-distribution-bottom">

                                    <div>
                                        <div class="pegawai-distribution-number">
                                            {{ $stat->jumlah }}
                                        </div>

                                        <div class="pegawai-distribution-label">
                                            pegawai aktif
                                        </div>
                                    </div>

                                    <div class="pegawai-distribution-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>

                                </div>

                            </a>

                        @endif

                    @endforeach

                </div>

            </div>

        </div>


        {{-- =========================================================
         TABLE PANEL
    ========================================================= --}}

        <div class="pegawai-panel">


            {{-- FILTER --}}

            <div class="pegawai-panel-header">

                <form method="GET" action="{{ route('kepeg.pegawai.index') }}" class="pegawai-filter">

                    <div class="pegawai-search">

                        <i class="bi bi-search"></i>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari NIP, NIK, nama, email atau nomor telepon...">

                    </div>


                    <select name="status" class="pegawai-filter-select" onchange="this.form.submit()">

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

            <div class="pegawai-table-wrapper">

                <table class="pegawai-table">

                    <thead>

                        <tr>

                            <th>
                                PEGAWAI
                            </th>

                            <th>
                                JABATAN
                            </th>

                            <th>
                                BIDANG
                            </th>

                            <th>
                                GOLONGAN
                            </th>

                            <th>
                                JENIS KERJA
                            </th>

                            <th>
                                STATUS
                            </th>

                            <th style="text-align:right;">
                                AKSI
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($pegawais as $pegawai)
                            <tr>

                                <td>

                                    <div class="pegawai-nama">

                                        {{ $pegawai->user_gelardepan ? $pegawai->user_gelardepan . ' ' : '' }}

                                        {{ $pegawai->user_nama }}

                                        {{ $pegawai->user_gelarbelakang ? ', ' . $pegawai->user_gelarbelakang : '' }}

                                    </div>

                                    <div class="pegawai-nip">

                                        {{ $pegawai->user_nip ?: 'NIP belum diisi' }}

                                    </div>

                                </td>


                                <td>

                                    @if ($pegawai->jabatan)
                                        <div class="pegawai-master">
                                            {{ $pegawai->jabatan->jabatan_nama }}
                                        </div>

                                        <div class="pegawai-master-small">
                                            {{ $pegawai->jabatan->jabatan_kategori }}
                                        </div>
                                    @else
                                        -
                                    @endif

                                </td>


                                <td>

                                    @if ($pegawai->bidang)
                                        <div class="pegawai-master">
                                            {{ $pegawai->bidang->bidang_nama }}
                                        </div>
                                    @else
                                        -
                                    @endif

                                </td>


                                <td>

                                    @if ($pegawai->golongan)
                                        <div class="pegawai-master">
                                            {{ $pegawai->golongan->golongan_pangkat }}
                                        </div>

                                        <div class="pegawai-master-small">
                                            {{ $pegawai->golongan->golongan_nama }}
                                        </div>
                                    @else
                                        -
                                    @endif

                                </td>


                                <td>

                                    @if ($pegawai->jenisKerja)
                                        <div class="pegawai-master">
                                            {{ $pegawai->jenisKerja->jenis_kerja_nama }}
                                        </div>
                                    @else
                                        -
                                    @endif

                                </td>


                                <td>

                                    @if ((int) $pegawai->user_status === 1)
                                        <span class="pegawai-status active">

                                            <span class="pegawai-status-dot"></span>

                                            Aktif

                                        </span>
                                    @else
                                        <span class="pegawai-status inactive">

                                            <span class="pegawai-status-dot"></span>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                <td>

                                    <div class="pegawai-actions">

                                        {{-- EDIT --}}

                                        <button type="button" class="pegawai-action-button" data-bs-toggle="modal"
                                            data-bs-target="#pegawaiEditModal{{ $pegawai->user_uid }}" title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        {{-- STATUS --}}

                                        <form method="POST"
                                            action="{{ route('kepeg.pegawai.status', $pegawai->user_uid) }}">

                                            @csrf

                                            @method('PATCH')

                                            <button type="submit" class="pegawai-action-button warning"
                                                title="{{ $pegawai->user_status == 1 ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                onclick="return confirm('{{ $pegawai->user_status == 1 ? 'Nonaktifkan pegawai ini?' : 'Aktifkan pegawai ini?' }}')">

                                                <i class="bi bi-pause-circle"></i>

                                            </button>

                                        </form>


                                        {{-- DELETE --}}

                                        <form method="POST"
                                            action="{{ route('kepeg.pegawai.destroy', $pegawai->user_uid) }}">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit" class="pegawai-action-button danger" title="Hapus"
                                                onclick="return confirm('Hapus data pegawai ini?')">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7">

                                    <div class="pegawai-empty">

                                        <div class="pegawai-empty-icon">
                                            <i class="bi bi-people"></i>
                                        </div>

                                        <div class="pegawai-empty-title">
                                            Data pegawai belum tersedia
                                        </div>

                                        <div class="pegawai-empty-text">
                                            Belum ada data pegawai yang dapat ditampilkan.
                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            @if ($pegawais->hasPages())

                <div class="pegawai-pagination">

                    <div class="pegawai-pagination-info">

                        Menampilkan

                        <strong>
                            {{ $pegawais->firstItem() }}
                        </strong>

                        -

                        <strong>
                            {{ $pegawais->lastItem() }}
                        </strong>

                        dari

                        <strong>
                            {{ $pegawais->total() }}
                        </strong>

                        data

                    </div>


                    <div class="pegawai-pagination-nav">

                        @if ($pegawais->onFirstPage())
                            <span class="disabled">
                                <i class="bi bi-chevron-left me-1"></i>
                                Sebelumnya
                            </span>
                        @else
                            <a href="{{ $pegawais->previousPageUrl() }}">
                                <i class="bi bi-chevron-left me-1"></i>
                                Sebelumnya
                            </a>
                        @endif


                        @foreach ($pegawais->getUrlRange(max(1, $pegawais->currentPage() - 2), min($pegawais->lastPage(), $pegawais->currentPage() + 2)) as $page => $url)
                            @if ($page == $pegawais->currentPage())
                                <span class="active">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach


                        @if ($pegawais->hasMorePages())
                            <a href="{{ $pegawais->nextPageUrl() }}">
                                Selanjutnya
                                <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        @else
                            <span class="disabled">
                                Selanjutnya
                                <i class="bi bi-chevron-right ms-1"></i>
                            </span>
                        @endif

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================
     CREATE MODAL
============================================================ --}}

    <div class="modal fade pegawai-modal" id="pegawaiCreateModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-centered">

            <div class="modal-content">

                <form method="POST" action="{{ route('kepeg.pegawai.store') }}">

                    @csrf


                    <div class="modal-header">

                        <div>

                            <div class="modal-title">
                                Tambah Data Pegawai
                            </div>

                            <div class="modal-subtitle">
                                Masukkan data pegawai baru.
                            </div>

                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        {{-- IDENTITAS --}}

                        <div class="pegawai-form-section">
                            Identitas Pegawai
                        </div>


                        <div class="pegawai-form-row">

                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    NIP
                                </label>

                                <input type="text" name="user_nip" class="pegawai-form-control"
                                    value="{{ old('user_nip') }}" placeholder="Masukkan NIP">

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    NIK
                                </label>

                                <input type="text" name="user_nik" class="pegawai-form-control"
                                    value="{{ old('user_nik') }}" placeholder="Masukkan NIK">

                            </div>

                        </div>


                        <div class="pegawai-form-group">

                            <label class="pegawai-form-label">
                                Nama Lengkap <span class="required">*</span>
                            </label>

                            <input type="text" name="user_nama" class="pegawai-form-control"
                                value="{{ old('user_nama') }}" placeholder="Masukkan nama lengkap" required>

                        </div>


                        <div class="pegawai-form-row">

                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Gelar Depan
                                </label>

                                <input type="text" name="user_gelardepan" class="pegawai-form-control"
                                    value="{{ old('user_gelardepan') }}" placeholder="Contoh: Dr.">

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Gelar Belakang
                                </label>

                                <input type="text" name="user_gelarbelakang" class="pegawai-form-control"
                                    value="{{ old('user_gelarbelakang') }}" placeholder="Contoh: S.Kom.">

                            </div>

                        </div>


                        <div class="pegawai-form-row">

                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Tempat Lahir
                                </label>

                                <input type="text" name="user_tempatlahir" class="pegawai-form-control"
                                    value="{{ old('user_tempatlahir') }}">

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Tanggal Lahir
                                </label>

                                <input type="date" name="user_tgllahir" class="pegawai-form-control"
                                    value="{{ old('user_tgllahir') }}">

                            </div>

                        </div>


                        <div class="pegawai-form-group">

                            <label class="pegawai-form-label">
                                Jenis Kelamin
                            </label>

                            <select name="user_jk" class="pegawai-form-control">

                                <option value="">
                                    Pilih Jenis Kelamin
                                </option>

                                <option value="L" {{ old('user_jk') === 'L' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>

                                <option value="P" {{ old('user_jk') === 'P' ? 'selected' : '' }}>
                                    Perempuan
                                </option>

                            </select>

                        </div>


                        {{-- MASTER --}}

                        <div class="pegawai-form-section">
                            Data Kepegawaian
                        </div>


                        <div class="pegawai-form-row">

                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Jabatan
                                </label>

                                <select name="user_jabatan_id" class="pegawai-form-control">

                                    <option value="">
                                        Pilih Jabatan
                                    </option>

                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->jabatan_id }}"
                                            {{ old('user_jabatan_id') == $jabatan->jabatan_id ? 'selected' : '' }}>

                                            {{ $jabatan->jabatan_nama }}

                                            @if ($jabatan->jabatan_kategori)
                                                - {{ $jabatan->jabatan_kategori }}
                                            @endif

                                        </option>
                                    @endforeach

                                </select>

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Bidang
                                </label>

                                <select name="user_bidang_id" class="pegawai-form-control">

                                    <option value="">
                                        Pilih Bidang
                                    </option>

                                    @foreach ($bidangs as $bidang)
                                        <option value="{{ $bidang->bidang_id }}"
                                            {{ old('user_bidang_id') == $bidang->bidang_id ? 'selected' : '' }}>

                                            {{ $bidang->bidang_nama }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>


                        <div class="pegawai-form-row">

                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Golongan
                                </label>

                                <select name="user_golongan_id" class="pegawai-form-control">

                                    <option value="">
                                        Pilih Golongan
                                    </option>

                                    @foreach ($golongans as $golongan)
                                        <option value="{{ $golongan->golongan_id }}"
                                            {{ old('user_golongan_id') == $golongan->golongan_id ? 'selected' : '' }}>

                                            {{ $golongan->golongan_ }}
                                            - {{ $golongan->golongan_nama }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Eselon
                                </label>

                                <select name="user_eselon_id" class="pegawai-form-control">

                                    <option value="">
                                        Pilih Eselon
                                    </option>

                                    @foreach ($eselons as $eselon)
                                        <option value="{{ $eselon->eselon_id }}"
                                            {{ old('user_eselon_id') == $eselon->eselon_id ? 'selected' : '' }}>

                                            {{ $eselon->eselon_kode }}
                                            - {{ $eselon->eselon_nama }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>


                        <div class="pegawai-form-row">

                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Pendidikan
                                </label>

                                <select name="user_pendidikan_id" class="pegawai-form-control">

                                    <option value="">
                                        Pilih Pendidikan
                                    </option>

                                    @foreach ($pendidikans as $pendidikan)
                                        <option value="{{ $pendidikan->pendidikan_id }}"
                                            {{ old('user_pendidikan_id') == $pendidikan->pendidikan_id ? 'selected' : '' }}>

                                            {{ $pendidikan->pendidikan_jenjang }}
                                            -
                                            {{ $pendidikan->pendidikan_jurusan }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Jenis Kerja
                                </label>

                                <select name="user_jenis_kerja_id" class="pegawai-form-control">

                                    <option value="">
                                        Pilih Jenis Kerja
                                    </option>

                                    @foreach ($jenisKerjas as $jenisKerja)
                                        <option value="{{ $jenisKerja->jenis_kerja_id }}"
                                            {{ old('user_jenis_kerja_id') == $jenisKerja->jenis_kerja_id ? 'selected' : '' }}>

                                            {{ $jenisKerja->jenis_kerja_nama }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>


                        <div class="pegawai-form-row">

                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    TMT
                                </label>

                                <input type="date" name="user_tmt" class="pegawai-form-control"
                                    value="{{ old('user_tmt') }}">

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    SPMT
                                </label>

                                <input type="date" name="user_spmt" class="pegawai-form-control"
                                    value="{{ old('user_spmt') }}">

                            </div>

                        </div>


                        <div class="pegawai-form-row">

                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Kelas Jabatan
                                </label>

                                <input type="text" name="user_kelasjabatan" class="pegawai-form-control"
                                    value="{{ old('user_kelasjabatan') }}">

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Jumlah Tanggungan
                                </label>

                                <input type="number" name="user_jmltanggungan" class="pegawai-form-control"
                                    min="0" value="{{ old('user_jmltanggungan', 0) }}">

                            </div>

                        </div>


                        {{-- DATA FINANSIAL --}}

                        <div class="pegawai-form-section">
                            Data Administrasi
                        </div>


                        <div class="pegawai-form-row">

                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    NPWP
                                </label>

                                <input type="text" name="user_npwp" class="pegawai-form-control"
                                    value="{{ old('user_npwp') }}">

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    BPJS
                                </label>

                                <input type="text" name="user_bpjs" class="pegawai-form-control"
                                    value="{{ old('user_bpjs') }}">

                            </div>

                        </div>


                        <div class="pegawai-form-group">

                            <label class="pegawai-form-label">
                                Nomor Rekening BPD
                            </label>

                            <input type="text" name="user_norek_bpd" class="pegawai-form-control"
                                value="{{ old('user_norek_bpd') }}">

                        </div>


                        {{-- KONTAK --}}

                        <div class="pegawai-form-section">
                            Kontak & Lokasi
                        </div>


                        <div class="pegawai-form-row">

                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Email
                                </label>

                                <input type="email" name="user_email" class="pegawai-form-control"
                                    value="{{ old('user_email') }}">

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    No. Telepon
                                </label>

                                <input type="text" name="user_notelp" class="pegawai-form-control"
                                    value="{{ old('user_notelp') }}">

                            </div>

                        </div>


                        <div class="pegawai-form-group">

                            <label class="pegawai-form-label">
                                Alamat
                            </label>

                            <textarea name="user_alamat" class="pegawai-form-control" placeholder="Alamat lengkap pegawai">{{ old('user_alamat') }}</textarea>

                        </div>


                        <div class="pegawai-form-group">

                            <label class="pegawai-form-label">
                                Lokasi Kerja
                            </label>

                            <input type="text" name="user_lokasikerja" class="pegawai-form-control"
                                value="{{ old('user_lokasikerja') }}"
                                placeholder="Contoh: Dinas Kebudayaan Provinsi Bali">

                        </div>


                        <div class="pegawai-form-group">

                            <label class="pegawai-form-label">
                                Keterangan
                            </label>

                            <textarea name="user_keterangan" class="pegawai-form-control" placeholder="Keterangan tambahan">{{ old('user_keterangan') }}</textarea>

                        </div>


                        {{-- LOGIN --}}

                        <div class="pegawai-form-section">
                            Akun Login
                        </div>


                        <div class="pegawai-form-row">

                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Password
                                </label>

                                <input type="password" name="user_password" class="pegawai-form-control"
                                    placeholder="Minimal 6 karakter">

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Status
                                </label>

                                <select name="user_status" class="pegawai-form-control">

                                    <option value="1" {{ old('user_status', '1') == '1' ? 'selected' : '' }}>
                                        Aktif
                                    </option>

                                    <option value="0" {{ old('user_status') == '0' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="pegawai-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>


                        <button type="submit" class="pegawai-submit-button">

                            <i class="bi bi-check-lg me-1"></i>

                            Simpan Pegawai

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- ============================================================
     EDIT MODALS
     DILETAKKAN DI LUAR TABLE
============================================================ --}}

    @foreach ($pegawais as $pegawai)
        <div class="modal fade pegawai-modal" id="pegawaiEditModal{{ $pegawai->user_uid }}" tabindex="-1"
            aria-hidden="true">

            <div class="modal-dialog modal-xl modal-dialog-centered">

                <div class="modal-content">

                    <form method="POST" action="{{ route('kepeg.pegawai.update', $pegawai->user_uid) }}">

                        @csrf

                        @method('PUT')


                        <div class="modal-header">

                            <div>

                                <div class="modal-title">
                                    Edit Data Pegawai
                                </div>

                                <div class="modal-subtitle">
                                    {{ $pegawai->user_nama }}
                                </div>

                            </div>

                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>

                        </div>


                        <div class="modal-body">

                            {{-- IDENTITAS --}}

                            <div class="pegawai-form-section">
                                Identitas Pegawai
                            </div>


                            <div class="pegawai-form-row">

                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        NIP
                                    </label>

                                    <input type="text" name="user_nip" class="pegawai-form-control"
                                        value="{{ $pegawai->user_nip }}">

                                </div>


                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        NIK
                                    </label>

                                    <input type="text" name="user_nik" class="pegawai-form-control"
                                        value="{{ $pegawai->user_nik }}">

                                </div>

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Nama Lengkap <span class="required">*</span>
                                </label>

                                <input type="text" name="user_nama" class="pegawai-form-control"
                                    value="{{ $pegawai->user_nama }}" required>

                            </div>


                            <div class="pegawai-form-row">

                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Gelar Depan
                                    </label>

                                    <input type="text" name="user_gelardepan" class="pegawai-form-control"
                                        value="{{ $pegawai->user_gelardepan }}">

                                </div>


                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Gelar Belakang
                                    </label>

                                    <input type="text" name="user_gelarbelakang" class="pegawai-form-control"
                                        value="{{ $pegawai->user_gelarbelakang }}">

                                </div>

                            </div>


                            <div class="pegawai-form-row">

                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Tempat Lahir
                                    </label>

                                    <input type="text" name="user_tempatlahir" class="pegawai-form-control"
                                        value="{{ $pegawai->user_tempatlahir }}">

                                </div>


                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Tanggal Lahir
                                    </label>

                                    <input type="date" name="user_tgllahir" class="pegawai-form-control"
                                        value="{{ optional($pegawai->user_tgllahir)->format('Y-m-d') }}">

                                </div>

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Jenis Kelamin
                                </label>

                                <select name="user_jk" class="pegawai-form-control">

                                    <option value="">
                                        Pilih Jenis Kelamin
                                    </option>

                                    <option value="L" {{ $pegawai->user_jk === 'L' ? 'selected' : '' }}>
                                        Laki-laki
                                    </option>

                                    <option value="P" {{ $pegawai->user_jk === 'P' ? 'selected' : '' }}>
                                        Perempuan
                                    </option>

                                </select>

                            </div>


                            {{-- MASTER --}}

                            <div class="pegawai-form-section">
                                Data Kepegawaian
                            </div>


                            <div class="pegawai-form-row">

                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Jabatan
                                    </label>

                                    <select name="user_jabatan_id" class="pegawai-form-control">

                                        <option value="">
                                            Pilih Jabatan
                                        </option>

                                        @foreach ($jabatans as $jabatan)
                                            <option value="{{ $jabatan->jabatan_id }}"
                                                {{ $pegawai->user_jabatan_id == $jabatan->jabatan_id ? 'selected' : '' }}>

                                                {{ $jabatan->jabatan_nama }}

                                                @if ($jabatan->jabatan_kategori)
                                                    - {{ $jabatan->jabatan_kategori }}
                                                @endif

                                            </option>
                                        @endforeach

                                    </select>

                                </div>


                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Bidang
                                    </label>

                                    <select name="user_bidang_id" class="pegawai-form-control">

                                        <option value="">
                                            Pilih Bidang
                                        </option>

                                        @foreach ($bidangs as $bidang)
                                            <option value="{{ $bidang->bidang_id }}"
                                                {{ $pegawai->user_bidang_id == $bidang->bidang_id ? 'selected' : '' }}>

                                                {{ $bidang->bidang_nama }}

                                            </option>
                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            <div class="pegawai-form-row">

                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Golongan
                                    </label>

                                    <select name="user_golongan_id" class="pegawai-form-control">

                                        <option value="">
                                            Pilih Golongan
                                        </option>

                                        @foreach ($golongans as $golongan)
                                            <option value="{{ $golongan->golongan_id }}"
                                                {{ $pegawai->user_golongan_id == $golongan->golongan_id ? 'selected' : '' }}>

                                                {{ $golongan->golongan_pangkat }}
                                                - {{ $golongan->golongan_nama }}

                                            </option>
                                        @endforeach

                                    </select>

                                </div>


                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Eselon
                                    </label>

                                    <select name="user_eselon_id" class="pegawai-form-control">

                                        <option value="">
                                            Pilih Eselon
                                        </option>

                                        @foreach ($eselons as $eselon)
                                            <option value="{{ $eselon->eselon_id }}"
                                                {{ $pegawai->user_eselon_id == $eselon->eselon_id ? 'selected' : '' }}>

                                                {{ $eselon->eselon_nama }}

                                            </option>
                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            <div class="pegawai-form-row">

                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Pendidikan
                                    </label>

                                    <select name="user_pendidikan_id" class="pegawai-form-control">

                                        <option value="">
                                            Pilih Pendidikan
                                        </option>

                                        @foreach ($pendidikans as $pendidikan)
                                            <option value="{{ $pendidikan->pendidikan_id }}"
                                                {{ $pegawai->user_pendidikan_id == $pendidikan->pendidikan_id ? 'selected' : '' }}>

                                                {{ $pendidikan->pendidikan_jenjang }}
                                                -
                                                {{ $pendidikan->pendidikan_jurusan }}

                                            </option>
                                        @endforeach

                                    </select>

                                </div>


                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Jenis Kerja
                                    </label>

                                    <select name="user_jenis_kerja_id" class="pegawai-form-control">

                                        <option value="">
                                            Pilih Jenis Kerja
                                        </option>

                                        @foreach ($jenisKerjas as $jenisKerja)
                                            <option value="{{ $jenisKerja->jenis_kerja_id }}"
                                                {{ $pegawai->user_jenis_kerja_id == $jenisKerja->jenis_kerja_id ? 'selected' : '' }}>

                                                {{ $jenisKerja->jenis_kerja_nama }}

                                            </option>
                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            <div class="pegawai-form-row">

                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        TMT
                                    </label>

                                    <input type="date" name="user_tmt" class="pegawai-form-control"
                                        value="{{ optional($pegawai->user_tmt)->format('Y-m-d') }}">

                                </div>


                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        SPMT
                                    </label>

                                    <input type="date" name="user_spmt" class="pegawai-form-control"
                                        value="{{ optional($pegawai->user_spmt)->format('Y-m-d') }}">

                                </div>

                            </div>


                            <div class="pegawai-form-row">

                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Kelas Jabatan
                                    </label>

                                    <input type="text" name="user_kelasjabatan" class="pegawai-form-control"
                                        value="{{ $pegawai->user_kelasjabatan }}">

                                </div>


                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Jumlah Tanggungan
                                    </label>

                                    <input type="number" name="user_jmltanggungan" class="pegawai-form-control"
                                        min="0" value="{{ $pegawai->user_jmltanggungan }}">

                                </div>

                            </div>


                            {{-- ADMINISTRASI --}}

                            <div class="pegawai-form-section">
                                Data Administrasi
                            </div>


                            <div class="pegawai-form-row">

                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        NPWP
                                    </label>

                                    <input type="text" name="user_npwp" class="pegawai-form-control"
                                        value="{{ $pegawai->user_npwp }}">

                                </div>


                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        BPJS
                                    </label>

                                    <input type="text" name="user_bpjs" class="pegawai-form-control"
                                        value="{{ $pegawai->user_bpjs }}">

                                </div>

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Nomor Rekening BPD
                                </label>

                                <input type="text" name="user_norek_bpd" class="pegawai-form-control"
                                    value="{{ $pegawai->user_norek_bpd }}">

                            </div>


                            {{-- KONTAK --}}

                            <div class="pegawai-form-section">
                                Kontak & Lokasi
                            </div>


                            <div class="pegawai-form-row">

                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Email
                                    </label>

                                    <input type="email" name="user_email" class="pegawai-form-control"
                                        value="{{ $pegawai->user_email }}">

                                </div>


                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        No. Telepon
                                    </label>

                                    <input type="text" name="user_notelp" class="pegawai-form-control"
                                        value="{{ $pegawai->user_notelp }}">

                                </div>

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Alamat
                                </label>

                                <textarea name="user_alamat" class="pegawai-form-control">{{ $pegawai->user_alamat }}</textarea>

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Lokasi Kerja
                                </label>

                                <input type="text" name="user_lokasikerja" class="pegawai-form-control"
                                    value="{{ $pegawai->user_lokasikerja }}">

                            </div>


                            <div class="pegawai-form-group">

                                <label class="pegawai-form-label">
                                    Keterangan
                                </label>

                                <textarea name="user_keterangan" class="pegawai-form-control">{{ $pegawai->user_keterangan }}</textarea>

                            </div>


                            {{-- LOGIN --}}

                            <div class="pegawai-form-section">
                                Akun Login
                            </div>


                            <div class="pegawai-form-row">

                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Password Baru
                                    </label>

                                    <input type="password" name="user_password" class="pegawai-form-control"
                                        placeholder="Kosongkan jika tidak diubah">

                                </div>


                                <div class="pegawai-form-group">

                                    <label class="pegawai-form-label">
                                        Status
                                    </label>

                                    <select name="user_status" class="pegawai-form-control">

                                        <option value="1" {{ $pegawai->user_status == 1 ? 'selected' : '' }}>
                                            Aktif
                                        </option>

                                        <option value="0" {{ $pegawai->user_status == 0 ? 'selected' : '' }}>
                                            Nonaktif
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>


                        <div class="modal-footer">

                            <button type="button" class="pegawai-cancel-button" data-bs-dismiss="modal">

                                Batal

                            </button>


                            <button type="submit" class="pegawai-submit-button">

                                <i class="bi bi-check-lg me-1"></i>

                                Simpan Perubahan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    @endforeach




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.pegawai-distribution-tab');
            const contents = document.querySelectorAll('.pegawai-distribution-content');

            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    const target = this.dataset.target;

                    tabs.forEach(function(item) {
                        item.classList.remove('active');
                    });

                    contents.forEach(function(content) {
                        content.classList.remove('active');
                    });

                    this.classList.add('active');

                    const content = document.getElementById('pegawai-distribution-' + target);

                    if (content) {
                        content.classList.add('active');
                    }
                });
            });
        });
    </script>

@endsection
