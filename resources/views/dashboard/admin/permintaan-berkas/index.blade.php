@extends('dashboard.layouts.app')

@section('title', 'Permintaan Berkas')

@section('content')

    <div class="permintaan-index-page">

        {{-- =========================================================
         HEADER
    ========================================================== --}}
        <div class="page-header">

            <div class="page-header-left">

                <div class="page-icon">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>

                <div>
                    <h4>Permintaan Berkas</h4>

                    <p>
                        Daftar permintaan pengumpulan berkas pegawai
                    </p>
                </div>

            </div>

            <a href="{{ route('admin.permintaan.berkas.create') }}" class="btn-permintaan-baru">

                <i class="bi bi-plus-lg"></i>

                <span>
                    Permintaan Berkas Baru
                </span>

            </a>

        </div>


        {{-- =========================================================
         SUCCESS
    ========================================================== --}}
        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 mb-4">

                <i class="bi bi-check-circle-fill me-2"></i>

                {{ session('success') }}

            </div>
        @endif


        {{-- =========================================================
         ERROR
    ========================================================== --}}
        @if (session('error'))
            <div class="alert alert-danger border-0 rounded-3 mb-4">

                <i class="bi bi-exclamation-circle-fill me-2"></i>

                {{ session('error') }}

            </div>
        @endif


        {{-- =========================================================
         SUMMARY
    ========================================================== --}}
        <div class="summary-grid">

            {{-- TOTAL --}}
            <div class="summary-card">

                <div class="summary-icon orange">

                    <i class="bi bi-file-earmark-text-fill"></i>

                </div>

                <div>

                    <div class="summary-value">

                        {{ $permintaan->total() }}

                    </div>

                    <div class="summary-label">

                        Total Permintaan

                    </div>

                </div>

            </div>


            {{-- AKTIF --}}
            <div class="summary-card">

                <div class="summary-icon green">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <div>

                    <div class="summary-value">

                        {{ $permintaan->filter(function ($item) {
                                return !$item->permintaan_expired || !$item->permintaan_expired->isPast();
                            })->count() }}

                    </div>

                    <div class="summary-label">

                        Aktif · Halaman Ini

                    </div>

                </div>

            </div>


            {{-- KEDALUWARSA --}}
            <div class="summary-card">

                <div class="summary-icon red">

                    <i class="bi bi-clock-history"></i>

                </div>

                <div>

                    <div class="summary-value">

                        {{ $permintaan->filter(function ($item) {
                                return $item->permintaan_expired && $item->permintaan_expired->isPast();
                            })->count() }}

                    </div>

                    <div class="summary-label">

                        Kedaluwarsa · Halaman Ini

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
         LIST
    ========================================================== --}}
        <div class="permintaan-card">

            {{-- CARD HEADER --}}
            <div class="permintaan-card-header">

                <div>

                    <h5>
                        Daftar Permintaan
                    </h5>

                    <p>
                        Semua permintaan berkas yang telah dibuat
                    </p>

                </div>

            </div>


            {{-- CARD BODY --}}
            <div class="permintaan-card-body p-0">

                @if ($permintaan->isEmpty())

                    {{-- =================================================
                     EMPTY
                ================================================== --}}
                    <div class="empty-state">

                        <div class="empty-icon">

                            <i class="bi bi-file-earmark-x"></i>

                        </div>

                        <h5>
                            Belum ada permintaan
                        </h5>

                        <p>
                            Belum ada permintaan berkas yang dibuat.
                        </p>

                        <a href="{{ route('admin.permintaan.berkas.create') }}" class="btn-permintaan-baru">

                            <i class="bi bi-plus-lg"></i>

                            Permintaan Berkas Baru

                        </a>

                    </div>
                @else
                    {{-- =================================================
                     TABLE
                ================================================== --}}
                    <div class="table-responsive">

                        <table class="table permintaan-table align-middle mb-0">

                            <thead>

                                <tr>

                                    <th width="60">
                                        #
                                    </th>

                                    <th>
                                        Permintaan
                                    </th>

                                    <th>
                                        Jenis Berkas
                                    </th>

                                    <th>
                                        Periode
                                    </th>

                                    <th>
                                        Target
                                    </th>

                                    <th>
                                        Deadline
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th width="130">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($permintaan as $index => $item)
                                    @php

                                        /*
                                        |--------------------------------------------------------------------------
                                        | STATUS
                                        |--------------------------------------------------------------------------
                                        */

                                        $expired = $item->permintaan_expired && $item->permintaan_expired->isPast();

                                        /*
                                        |--------------------------------------------------------------------------
                                        | TARGET
                                        |--------------------------------------------------------------------------
                                        */

                                        $activeTargets = $item->target->where('target_status', true);

                                        $targetCount = $activeTargets->count();

                                    @endphp


                                    <tr>

                                        {{-- =================================================
                                         NOMOR
                                    ================================================== --}}
                                        <td>

                                            <span class="row-number">

                                                {{ ($permintaan->firstItem() ?? 1) + $index }}

                                            </span>

                                        </td>


                                        {{-- =================================================
                                         PERMINTAAN
                                    ================================================== --}}
                                        <td>

                                            <div class="request-title">

                                                {{ $item->permintaan_judul }}

                                            </div>


                                            @if ($item->permintaan_keterangan)
                                                <div class="request-description">

                                                    {{ $item->permintaan_keterangan }}

                                                </div>
                                            @endif

                                        </td>


                                        {{-- =================================================
                                         JENIS BERKAS
                                    ================================================== --}}
                                        <td>

                                            <div class="jenis-wrapper">

                                                <span class="jenis-name">

                                                    {{ $item->jenisBerkas?->jenis_berkas_nama ?? '-' }}

                                                </span>


                                                @if ($item->jenisBerkas?->kategori)
                                                    <span class="kategori-name">

                                                        {{ $item->jenisBerkas->kategori->kategori_nama }}

                                                    </span>
                                                @endif

                                            </div>

                                        </td>


                                        {{-- =================================================
                                         PERIODE
                                    ================================================== --}}
                                        <td>

                                            @if ($item->permintaan_tahun)
                                                <div class="periode-tahun">

                                                    {{ $item->permintaan_tahun }}

                                                </div>
                                            @endif


                                            @if ($item->permintaan_periode)
                                                <div class="periode-name">

                                                    {{ $item->permintaan_periode }}

                                                </div>
                                            @endif


                                            @if (!$item->permintaan_tahun && !$item->permintaan_periode)
                                                <span class="text-muted">

                                                    -

                                                </span>
                                            @endif

                                        </td>


                                        {{-- =================================================
                                         TARGET
                                    ================================================== --}}
                                        <td>

                                            <div class="target-count">

                                                <i class="bi bi-people-fill"></i>

                                                {{ $targetCount }}

                                                <span>
                                                    jenis kerja
                                                </span>

                                            </div>


                                            @if ($targetCount > 0)
                                                <div class="target-list">

                                                    @foreach ($activeTargets->take(3) as $target)
                                                        <span class="target-badge">

                                                            {{ $target->jenisKerja?->jenis_kerja_nama ?? '-' }}

                                                        </span>
                                                    @endforeach


                                                    @if ($targetCount > 3)
                                                        <span class="target-more">

                                                            +{{ $targetCount - 3 }}

                                                        </span>
                                                    @endif

                                                </div>
                                            @endif

                                        </td>


                                        {{-- =================================================
                                         DEADLINE
                                    ================================================== --}}
                                        <td>

                                            @if ($item->permintaan_expired)
                                                <div class="deadline">

                                                    <i class="bi bi-calendar-event"></i>

                                                    {{ $item->permintaan_expired->format('d/m/Y') }}

                                                </div>


                                                <div class="deadline-time">

                                                    {{ $item->permintaan_expired->format('H:i') }}

                                                </div>
                                            @else
                                                <span class="text-muted">

                                                    Tidak ditentukan

                                                </span>
                                            @endif

                                        </td>


                                        {{-- =================================================
                                         STATUS
                                    ================================================== --}}
                                        <td>

                                            @if ($expired)
                                                <span class="status-badge expired">

                                                    <i class="bi bi-clock-history"></i>

                                                    Kedaluwarsa

                                                </span>
                                            @else
                                                <span class="status-badge active">

                                                    <i class="bi bi-check-circle-fill"></i>

                                                    Aktif

                                                </span>
                                            @endif

                                        </td>


                                        {{-- =================================================
                                         AKSI
                                    ================================================== --}}
                                        <td>

                                            <a href="{{ route('admin.rekap.berkas.index', [
                                                'permintaanUid' => $item->permintaan_uid,
                                            ]) }}"
                                                class="btn-lihat">

                                                <i class="bi bi-eye-fill"></i>

                                                Lihat Rekap

                                            </a>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- =================================================
                     PAGINATION
                ================================================== --}}
                    @if ($permintaan->hasPages())
                        <div class="pagination-wrapper">

                            <div class="pagination-info">

                                Menampilkan

                                <strong>
                                    {{ $permintaan->firstItem() }}
                                </strong>

                                –

                                <strong>
                                    {{ $permintaan->lastItem() }}
                                </strong>

                                dari

                                <strong>
                                    {{ $permintaan->total() }}
                                </strong>

                                permintaan

                            </div>


                            <div class="pagination-container">

                                {{ $permintaan->onEachSide(1)->links('pagination::bootstrap-5') }}

                            </div>

                        </div>
                    @endif

                @endif

            </div>

        </div>

    </div>


    <style>
        /* =========================================================
               PAGE
            ========================================================== */

        .permintaan-index-page {
            max-width: 1400px;
            margin: 0 auto;
        }


        /* =========================================================
               HEADER
            ========================================================== */

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }


        .page-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }


        .page-icon {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            background: rgba(242, 140, 40, .12);
            color: #f28c28;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            flex-shrink: 0;
        }


        .page-header h4 {
            margin: 0;
            color: #182238;
            font-weight: 700;
        }


        .page-header p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 14px;
        }


        /* =========================================================
               BUTTON
            ========================================================== */

        .btn-permintaan-baru {
            min-height: 44px;
            padding: 0 18px;
            border-radius: 10px;
            background: #f28c28;
            border: 1px solid #f28c28;
            color: #fff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: .2s ease;
            white-space: nowrap;
        }


        .btn-permintaan-baru:hover {
            background: #dc7818;
            border-color: #dc7818;
            color: #fff;
            transform: translateY(-1px);
        }


        /* =========================================================
               SUMMARY
            ========================================================== */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }


        .summary-card {
            background: #fff;
            border: 1px solid #e9edf3;
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 4px 18px rgba(24, 34, 56, .04);
        }


        .summary-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }


        .summary-icon.orange {
            background: rgba(242, 140, 40, .12);
            color: #f28c28;
        }


        .summary-icon.green {
            background: rgba(34, 197, 94, .12);
            color: #16a34a;
        }


        .summary-icon.red {
            background: rgba(239, 68, 68, .12);
            color: #dc2626;
        }


        .summary-value {
            color: #182238;
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
        }


        .summary-label {
            color: #7b8494;
            font-size: 12px;
            margin-top: 5px;
        }


        /* =========================================================
               CARD
            ========================================================== */

        .permintaan-card {
            background: #fff;
            border: 1px solid #e9edf3;
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(24, 34, 56, .04);
            overflow: hidden;
        }


        .permintaan-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #edf0f4;
        }


        .permintaan-card-header h5 {
            margin: 0;
            color: #182238;
            font-size: 16px;
            font-weight: 700;
        }


        .permintaan-card-header p {
            margin: 4px 0 0;
            color: #7b8494;
            font-size: 13px;
        }


        /* =========================================================
               TABLE
            ========================================================== */

        .permintaan-table {
            min-width: 1100px;
        }


        .permintaan-table thead th {
            background: #f8fafc;
            color: #6b7280;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 1px solid #e9edf3;
            padding: 13px 16px;
            white-space: nowrap;
        }


        .permintaan-table tbody td {
            padding: 16px;
            border-bottom: 1px solid #eef1f5;
            vertical-align: middle;
        }


        .permintaan-table tbody tr:last-child td {
            border-bottom: 0;
        }


        .permintaan-table tbody tr:hover {
            background: #fafbfc;
        }


        /* =========================================================
               ROW NUMBER
            ========================================================== */

        .row-number {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: #f3f5f8;
            color: #6b7280;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }


        /* =========================================================
               REQUEST
            ========================================================== */

        .request-title {
            color: #182238;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.4;
        }


        .request-description {
            color: #8a93a2;
            font-size: 12px;
            margin-top: 4px;
            max-width: 280px;
            line-height: 1.4;
        }


        /* =========================================================
               JENIS
            ========================================================== */

        .jenis-wrapper {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }


        .jenis-name {
            color: #374151;
            font-size: 13px;
            font-weight: 600;
        }


        .kategori-name {
            color: #8a93a2;
            font-size: 11px;
        }


        /* =========================================================
               PERIODE
            ========================================================== */

        .periode-tahun {
            color: #374151;
            font-size: 13px;
            font-weight: 700;
        }


        .periode-name {
            color: #8a93a2;
            font-size: 12px;
            margin-top: 2px;
        }


        /* =========================================================
               TARGET
            ========================================================== */

        .target-count {
            color: #374151;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }


        .target-count i {
            color: #f28c28;
            margin-right: 4px;
        }


        .target-count span {
            color: #8a93a2;
            font-weight: 400;
        }


        .target-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: 6px;
            max-width: 190px;
        }


        .target-badge,
        .target-more {
            background: #f5f7fa;
            border: 1px solid #e8ecf1;
            color: #6b7280;
            border-radius: 6px;
            padding: 3px 6px;
            font-size: 10px;
            line-height: 1.2;
        }


        .target-more {
            color: #f28c28;
            background: rgba(242, 140, 40, .08);
            border-color: rgba(242, 140, 40, .15);
        }


        /* =========================================================
               DEADLINE
            ========================================================== */

        .deadline {
            color: #374151;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }


        .deadline i {
            color: #f28c28;
            margin-right: 4px;
        }


        .deadline-time {
            color: #8a93a2;
            font-size: 11px;
            margin-top: 3px;
        }


        /* =========================================================
               STATUS
            ========================================================== */

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 9px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }


        .status-badge.active {
            color: #15803d;
            background: #f0fdf4;
            border: 1px solid #dcfce7;
        }


        .status-badge.expired {
            color: #b91c1c;
            background: #fef2f2;
            border: 1px solid #fee2e2;
        }


        /* =========================================================
               ACTION
            ========================================================== */

        .btn-lihat {
            min-height: 36px;
            padding: 0 11px;
            border-radius: 8px;
            border: 1px solid #e3e7ed;
            background: #fff;
            color: #374151;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
            transition: .2s ease;
        }


        .btn-lihat:hover {
            color: #f28c28;
            border-color: #f28c28;
            background: rgba(242, 140, 40, .05);
        }


        /* =========================================================
               PAGINATION
            ========================================================== */

        .pagination-wrapper {
            padding: 16px 20px;
            border-top: 1px solid #eef1f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }


        .pagination-info {
            color: #7b8494;
            font-size: 12px;
        }


        .pagination-info strong {
            color: #374151;
        }


        .pagination-container {
            display: flex;
            align-items: center;
        }


        .pagination-container .pagination {
            margin: 0;
            gap: 4px;
        }


        .pagination-container .page-item {
            margin: 0;
        }


        .pagination-container .page-link {
            color: #374151;
            border: 1px solid #e5e7eb;
            background: #fff;
            font-size: 12px;
            min-width: 34px;
            height: 34px;
            border-radius: 8px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 9px;
            box-shadow: none;
        }


        .pagination-container .page-link:hover {
            color: #f28c28;
            border-color: #f28c28;
            background: rgba(242, 140, 40, .05);
        }


        .pagination-container .page-item.active .page-link {
            background: #f28c28;
            border-color: #f28c28;
            color: #fff;
        }


        .pagination-container .page-item.disabled .page-link {
            color: #b0b6c0;
            background: #f8fafc;
            border-color: #e5e7eb;
        }


        /* =========================================================
               EMPTY
            ========================================================== */

        .empty-state {
            padding: 70px 20px;
            text-align: center;
        }


        .empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: #f5f7fa;
            color: #9ca3af;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 16px;
        }


        .empty-state h5 {
            color: #374151;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 6px;
        }


        .empty-state p {
            color: #8a93a2;
            font-size: 13px;
            margin-bottom: 20px;
        }


        /* =========================================================
               RESPONSIVE
            ========================================================== */

        @media (max-width: 991.98px) {

            .summary-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 767.98px) {

            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }


            .page-header-left {
                align-items: flex-start;
            }


            .btn-permintaan-baru {
                width: 100%;
            }


            .summary-grid {
                gap: 10px;
            }


            .summary-card {
                padding: 15px;
            }


            .permintaan-card-header {
                padding: 18px;
            }


            .pagination-wrapper {
                align-items: flex-start;
                flex-direction: column;
            }


            .pagination-container {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 2px;
            }


            .pagination-container .pagination {
                flex-wrap: nowrap;
            }

        }
    </style>

@endsection
