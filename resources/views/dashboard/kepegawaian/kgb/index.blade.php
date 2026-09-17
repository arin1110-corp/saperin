@extends('dashboard.layouts.app')

@section('title', 'Kenaikan Gaji Berkala')
@section('header-title', 'Kenaikan Gaji Berkala')
@section('breadcrumb', 'Kenaikan Gaji Berkala')

@section('page-style')

    <style>
        /* =====================================================
               KGB PAGE
            ===================================================== */

        .kgb-page {
            padding-bottom: 30px;
        }

        /* =====================================================
               HEADER
            ===================================================== */

        .kgb-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 22px;
        }

        .kgb-header-title h3 {
            margin: 0;
            color: #273449;
            font-size: 22px;
            font-weight: 800;
        }

        .kgb-header-title p {
            margin: 5px 0 0;
            color: #8b94a3;
            font-size: 13px;
        }

        .kgb-header-action {
            display: flex;
            gap: 8px;
        }

        .kgb-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            min-height: 40px;
            padding: 0 17px;

            border: 0;
            border-radius: 9px;

            background: linear-gradient(135deg,
                    #df8339,
                    #c35e1d);

            color: #fff;
            text-decoration: none;

            font-size: 13px;
            font-weight: 700;

            box-shadow:
                0 7px 18px rgba(195, 94, 29, .18);

            transition: .15s ease;
        }

        .kgb-btn-primary:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow:
                0 9px 22px rgba(195, 94, 29, .25);
        }

        /* =====================================================
               STATISTIC
            ===================================================== */

        .kgb-stat-grid {
            display: grid;
            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 14px;
            margin-bottom: 20px;
        }

        .kgb-stat-card {
            position: relative;

            padding: 17px 18px;

            background: #fff;

            border:
                1px solid #edf0f3;

            border-radius: 14px;

            box-shadow:
                0 4px 16px rgba(20, 35, 55, .04);

            overflow: hidden;
        }

        .kgb-stat-card::after {
            content: '';

            position: absolute;

            width: 70px;
            height: 70px;

            right: -20px;
            bottom: -30px;

            border-radius: 50%;

            background:
                rgba(223, 131, 57, .07);
        }

        .kgb-stat-label {
            color: #929aa6;
            font-size: 11px;
            font-weight: 600;

            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .kgb-stat-value {
            margin-top: 5px;

            color: #273449;

            font-size: 25px;
            line-height: 1.1;
            font-weight: 800;
        }

        .kgb-stat-icon {
            position: absolute;

            top: 15px;
            right: 16px;

            width: 37px;
            height: 37px;

            border-radius: 10px;

            background: #fff1e6;
            color: #d6742d;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 17px;
        }

        /* =====================================================
               FILTER
            ===================================================== */

        .kgb-filter-card {
            padding: 15px;

            background: #fff;

            border:
                1px solid #edf0f3;

            border-radius: 14px;

            box-shadow:
                0 4px 16px rgba(20, 35, 55, .04);

            margin-bottom: 16px;
        }

        .kgb-filter-form {
            display: grid;

            grid-template-columns:
                minmax(0, 1fr) 170px auto;

            gap: 9px;
        }

        .kgb-filter-input,
        .kgb-filter-select {
            width: 100%;

            min-height: 40px;

            padding: 0 12px;

            border:
                1px solid #e1e5ea;

            border-radius: 8px;

            background: #fff;

            color: #3d4858;

            font-size: 13px;

            outline: none;
        }

        .kgb-filter-input:focus,
        .kgb-filter-select:focus {
            border-color: #df8339;

            box-shadow:
                0 0 0 3px rgba(223, 131, 57, .08);
        }

        .kgb-filter-button {
            min-height: 40px;

            padding: 0 16px;

            border: 0;
            border-radius: 8px;

            background: #273449;
            color: #fff;

            font-size: 13px;
            font-weight: 700;

            cursor: pointer;
        }

        .kgb-filter-button:hover {
            background: #1c293c;
        }

        /* =====================================================
               TABLE
            ===================================================== */

        .kgb-table-card {
            background: #fff;

            border:
                1px solid #edf0f3;

            border-radius: 14px;

            box-shadow:
                0 4px 16px rgba(20, 35, 55, .04);

            overflow: hidden;
        }

        .kgb-table-header {
            display: flex;

            align-items: center;
            justify-content: space-between;

            padding: 16px 18px;

            border-bottom:
                1px solid #edf0f3;
        }

        .kgb-table-title {
            color: #273449;

            font-size: 14px;
            font-weight: 800;
        }

        .kgb-table-count {
            color: #929aa6;

            font-size: 11px;
        }

        .kgb-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .kgb-table {
            width: 100%;

            min-width: 900px;

            border-collapse: collapse;
        }

        .kgb-table thead th {
            padding: 11px 14px;

            background: #f8f9fb;

            color: #7d8795;

            border-bottom:
                1px solid #edf0f3;

            font-size: 10px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .5px;

            white-space: nowrap;
        }

        .kgb-table tbody td {
            padding: 13px 14px;

            border-bottom:
                1px solid #f0f2f5;

            color: #4c5665;

            font-size: 12px;

            vertical-align: middle;
        }

        .kgb-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .kgb-table tbody tr:hover {
            background: #fcfcfd;
        }

        /* =====================================================
               BATCH
            ===================================================== */

        .kgb-batch-name {
            color: #273449;

            font-size: 13px;
            font-weight: 750;
        }

        .kgb-batch-id {
            margin-top: 3px;

            color: #a0a7b1;

            font-size: 10px;
        }

        .kgb-regulation {
            color: #4b5666;

            font-size: 12px;
            font-weight: 600;
        }

        .kgb-regulation-number {
            margin-top: 3px;

            color: #999fa8;

            font-size: 10px;
        }

        .kgb-official {
            color: #4b5666;

            font-size: 12px;
            font-weight: 600;
        }

        .kgb-date {
            color: #687383;

            font-size: 11px;
        }

        /* =====================================================
               BADGE
            ===================================================== */

        .kgb-badge {
            display: inline-flex;

            align-items: center;

            padding: 5px 9px;

            border-radius: 20px;

            font-size: 10px;
            font-weight: 700;
        }

        .kgb-badge-active {
            background: #eaf8ef;
            color: #25824b;
        }

        .kgb-badge-inactive {
            background: #f1f2f4;
            color: #7c838d;
        }

        .kgb-count {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            min-width: 30px;

            padding: 4px 7px;

            border-radius: 7px;

            background: #fff1e6;
            color: #c96822;

            font-size: 10px;
            font-weight: 700;
        }

        /* =====================================================
               ACTION
            ===================================================== */

        .kgb-actions {
            display: flex;

            align-items: center;

            gap: 5px;
        }

        .kgb-action {
            width: 31px;
            height: 31px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            border-radius: 7px;

            border: 1px solid #e4e7eb;

            background: #fff;

            color: #6e7785;

            text-decoration: none;

            font-size: 13px;

            transition: .15s ease;
        }

        .kgb-action:hover {
            color: #273449;
            border-color: #d3d8df;
            background: #f8f9fb;
        }

        .kgb-action-primary {
            color: #d16d29;

            border-color: #f1d4bd;

            background: #fff8f3;
        }

        .kgb-action-primary:hover {
            color: #c35e1d;

            border-color: #dfb18e;

            background: #fff1e6;
        }

        /* =====================================================
               EMPTY
            ===================================================== */

        .kgb-empty {
            padding: 55px 20px;

            text-align: center;
        }

        .kgb-empty-icon {
            width: 52px;
            height: 52px;

            margin: 0 auto 12px;

            border-radius: 13px;

            background: #f4f5f7;

            color: #a2a9b2;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 23px;
        }

        .kgb-empty-title {
            color: #4d5868;

            font-size: 14px;
            font-weight: 700;
        }

        .kgb-empty-text {
            margin-top: 4px;

            color: #9ba2ad;

            font-size: 11px;
        }

        /* =====================================================
               PAGINATION
            ===================================================== */

        .kgb-pagination {
            padding: 15px 18px;

            border-top:
                1px solid #edf0f3;

            display: flex;

            justify-content: flex-end;
        }

        .kgb-pagination nav {
            margin: 0;
        }

        /* =====================================================
               RESPONSIVE
            ===================================================== */

        @media (max-width: 900px) {

            .kgb-stat-grid {
                grid-template-columns:
                    repeat(3, minmax(0, 1fr));
            }

        }

        @media (max-width: 700px) {

            .kgb-header {
                align-items: flex-start;

                flex-direction: column;
            }

            .kgb-header-action {
                width: 100%;
            }

            .kgb-btn-primary {
                width: 100%;
            }

            .kgb-stat-grid {
                grid-template-columns:
                    1fr;
            }

            .kgb-filter-form {
                grid-template-columns: 1fr;
            }

            .kgb-filter-button {
                width: 100%;
            }

        }
    </style>

@endsection


@section('content')

    <div class="kgb-page">

        {{-- =====================================================
             HEADER
        ===================================================== --}}

        <div class="kgb-header">

            <div class="kgb-header-title">

                <h3>
                    Kenaikan Gaji Berkala
                </h3>

                <p>
                    Kelola penerbitan KGB pegawai secara terpusat.
                </p>

            </div>

            <div class="kgb-header-action">

                <a href="{{ route('samperin.admin.kgb.create') }}" class="kgb-btn-primary">

                    <i class="bi bi-plus-lg"></i>

                    Buat KGB

                </a>

            </div>

        </div>


        {{-- =====================================================
             STATISTIK
        ===================================================== --}}

        <div class="kgb-stat-grid">

            {{-- TOTAL BATCH --}}

            <div class="kgb-stat-card">

                <div class="kgb-stat-label">
                    Total Batch
                </div>

                <div class="kgb-stat-value">
                    {{ number_format($totalBatch) }}
                </div>

                <div class="kgb-stat-icon">
                    <i class="bi bi-collection-fill"></i>
                </div>

            </div>


            {{-- BATCH AKTIF --}}

            <div class="kgb-stat-card">

                <div class="kgb-stat-label">
                    Batch Aktif
                </div>

                <div class="kgb-stat-value">
                    {{ number_format($batchAktif) }}
                </div>

                <div class="kgb-stat-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

            </div>


            {{-- TOTAL KGB --}}

            <div class="kgb-stat-card">

                <div class="kgb-stat-label">
                    Total Pegawai KGB
                </div>

                <div class="kgb-stat-value">
                    {{ number_format($totalKgb) }}
                </div>

                <div class="kgb-stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>

            </div>

        </div>


        {{-- =====================================================
             FILTER
        ===================================================== --}}

        <div class="kgb-filter-card">

            <form method="GET" action="{{ route('samperin.admin.kgb.index') }}" class="kgb-filter-form">

                <input type="text" name="search" value="{{ request('search') }}" class="kgb-filter-input"
                    placeholder="Cari nama batch atau format nomor surat...">

                <select name="status" class="kgb-filter-select">

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

                <button type="submit" class="kgb-filter-button">

                    <i class="bi bi-search"></i>

                    Cari

                </button>

            </form>

        </div>


        {{-- =====================================================
             TABLE
        ===================================================== --}}

        <div class="kgb-table-card">

            <div class="kgb-table-header">

                <div class="kgb-table-title">
                    Daftar Batch KGB
                </div>

                <div class="kgb-table-count">

                    {{ $kgb->total() }}
                    batch

                </div>

            </div>


            @if ($kgb->count())

                <div class="kgb-table-wrapper">

                    <table class="kgb-table">

                        <thead>

                            <tr>

                                <th>
                                    Batch
                                </th>

                                <th>
                                    Peraturan Gaji
                                </th>

                                <th>
                                    Pejabat
                                </th>

                                <th>
                                    Tanggal Surat
                                </th>

                                <th>
                                    Jumlah
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($kgb as $batch)
                                <tr>

                                    {{-- BATCH --}}

                                    <td>

                                        <div class="kgb-batch-name">

                                            {{ $batch->kgb_batch_nama }}

                                        </div>

                                        <div class="kgb-batch-id">

                                            Nomor
                                            {{ $batch->kgb_batch_nomor_awal }}

                                            @if ($batch->kgb_batch_nomor_akhir)
                                                -
                                                {{ $batch->kgb_batch_nomor_akhir }}
                                            @endif

                                        </div>

                                    </td>


                                    {{-- PERATURAN --}}

                                    <td>

                                        @if ($batch->peraturanGaji)
                                            <div class="kgb-regulation">

                                                {{ $batch->peraturanGaji->peraturan_gaji_nama }}

                                            </div>

                                            <div class="kgb-regulation-number">

                                                {{ $batch->peraturanGaji->peraturan_gaji_nomor }}
                                                /
                                                {{ $batch->peraturanGaji->peraturan_gaji_tahun }}

                                            </div>
                                        @else
                                            <span class="text-muted">
                                                -
                                            </span>
                                        @endif

                                    </td>


                                    {{-- PEJABAT --}}

                                    <td>

                                        @if ($batch->pejabat)
                                            <div class="kgb-official">

                                                {{ $batch->pejabat->user_nama }}

                                            </div>

                                            @if ($batch->pejabat->user_nip)
                                                <div class="kgb-regulation-number">

                                                    {{ $batch->pejabat->user_nip }}

                                                </div>
                                            @endif
                                        @else
                                            <span class="text-muted">
                                                -
                                            </span>
                                        @endif

                                    </td>


                                    {{-- TANGGAL --}}

                                    <td>

                                        <div class="kgb-date">

                                            {{ \Carbon\Carbon::parse($batch->kgb_batch_tanggal)->translatedFormat('d F Y') }}

                                        </div>

                                    </td>


                                    {{-- JUMLAH --}}

                                    <td>

                                        <span class="kgb-count">

                                            {{ $batch->kgb_count }}

                                            Pegawai

                                        </span>

                                    </td>


                                    {{-- STATUS --}}

                                    <td>

                                        @if ($batch->kgb_batch_status)
                                            <span class="kgb-badge kgb-badge-active">

                                                Aktif

                                            </span>
                                        @else
                                            <span class="kgb-badge kgb-badge-inactive">

                                                Nonaktif

                                            </span>
                                        @endif

                                    </td>


                                    {{-- ACTION --}}

                                    <td>

                                        <div class="kgb-actions">

                                            <a href="{{ route('samperin.admin.kgb.show', $batch->kgb_batch_id) }}"
                                                class="kgb-action kgb-action-primary" title="Detail">

                                                <i class="bi bi-eye"></i>

                                            </a>


                                            <a href="{{ route('samperin.admin.kgb.edit', $batch->kgb_batch_id) }}"
                                                class="kgb-action" title="Edit">

                                                <i class="bi bi-pencil"></i>

                                            </a>


                                            <form method="POST"
                                                action="{{ route('samperin.admin.kgb.toggle-status', $batch->kgb_batch_id) }}"
                                                style="display:inline;">

                                                @csrf

                                                <button type="submit" class="kgb-action"
                                                    title="{{ $batch->kgb_batch_status ? 'Nonaktifkan' : 'Aktifkan' }}">

                                                    @if ($batch->kgb_batch_status)
                                                        <i class="bi bi-toggle-on"></i>
                                                    @else
                                                        <i class="bi bi-toggle-off"></i>
                                                    @endif

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                     PAGINATION
                ================================================== --}}

                @if ($kgb->hasPages())
                    <div class="kgb-pagination">

                        {{ $kgb->links() }}

                    </div>
                @endif
            @else
                <div class="kgb-empty">

                    <div class="kgb-empty-icon">

                        <i class="bi bi-file-earmark-text"></i>

                    </div>

                    <div class="kgb-empty-title">

                        Belum ada batch KGB

                    </div>

                    <div class="kgb-empty-text">

                        Silakan buat batch KGB untuk mulai menerbitkan
                        Kenaikan Gaji Berkala.

                    </div>

                </div>

            @endif

        </div>

    </div>

@endsection
