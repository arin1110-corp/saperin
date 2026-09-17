@extends('dashboard.layouts.app')

@section('title', 'Detail Batch KGB')
@section('header-title', 'Detail Batch KGB')
@section('breadcrumb', 'Detail KGB')

@section('page-style')
    <style>
        .kgb-detail-page {
            padding-bottom: 30px;
        }

        .kgb-card {
            background: #fff;
            border: 1px solid #e9edf3;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(20, 34, 59, .06);
            overflow: hidden;
        }

        .kgb-card-header {
            padding: 20px 24px;
            background: #fafbfc;
            border-bottom: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .kgb-card-header h5 {
            margin: 0;
            color: #14223b;
            font-weight: 700;
        }

        .kgb-card-header p {
            margin: 5px 0 0;
            color: #7a8495;
            font-size: 13px;
        }

        .kgb-header-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .kgb-card-body {
            padding: 25px;
        }

        .kgb-section {
            margin-bottom: 30px;
        }

        .kgb-section:last-child {
            margin-bottom: 0;
        }

        .kgb-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #edf0f4;
            color: #14223b;
            font-size: 15px;
            font-weight: 700;
        }

        .kgb-section-title i {
            color: #df8339;
        }

        .kgb-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .kgb-info-item {
            padding: 14px 16px;
            border: 1px solid #edf0f4;
            border-radius: 10px;
            background: #fff;
        }

        .kgb-info-label {
            margin-bottom: 5px;
            color: #8a93a1;
            font-size: 12px;
        }

        .kgb-info-value {
            color: #14223b;
            font-size: 14px;
            font-weight: 600;
            word-break: break-word;
        }

        .kgb-salary {
            font-size: 18px;
            font-weight: 700;
            color: #14223b;
        }

        .kgb-status {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .kgb-status-active {
            background: #eaf8ef;
            color: #198754;
        }

        .kgb-status-inactive {
            background: #f1f3f5;
            color: #6c757d;
        }

        .kgb-badge-info {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 20px;
            background: #eef4ff;
            color: #3b6fd8;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .kgb-badge-danger {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 20px;
            background: #fff1f2;
            color: #be123c;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .kgb-btn {
            min-height: 40px;
            padding: 8px 15px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: .2s;
        }

        .kgb-btn-edit {
            color: #fff;
            background: linear-gradient(135deg, #df8339, #c35e1d);
        }

        .kgb-btn-edit:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(195, 94, 29, .2);
        }

        .kgb-btn-back {
            color: #14223b;
            background: #eef1f5;
        }

        .kgb-btn-back:hover {
            color: #14223b;
            background: #e1e5ea;
        }

        .kgb-table-wrapper {
            overflow-x: auto;
            border: 1px solid #edf0f4;
            border-radius: 10px;
        }

        .kgb-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
            min-width: 1100px;
        }

        .kgb-table th {
            padding: 12px 14px;
            background: #f8f9fb;
            color: #6f7886;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            border-bottom: 1px solid #edf0f4;
        }

        .kgb-table td {
            padding: 13px 14px;
            color: #14223b;
            font-size: 13px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .kgb-table tbody tr:last-child td {
            border-bottom: none;
        }

        .kgb-table tbody tr:hover {
            background: #fafbfc;
        }

        .kgb-employee-name {
            font-weight: 700;
            color: #14223b;
        }

        .kgb-employee-nip {
            margin-top: 2px;
            color: #8a93a1;
            font-size: 11px;
        }

        .kgb-employee-tmt {
            color: #6f7886;
            font-size: 12px;
        }

        .kgb-empty {
            padding: 35px 20px;
            text-align: center;
            color: #8a93a1;
        }

        .kgb-footer {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #edf0f4;
        }

        .kgb-alert {
            margin-bottom: 20px;
            padding: 12px 15px;
            border-radius: 10px;
            background: #eaf8ef;
            border: 1px solid #b7e4c7;
            color: #198754;
            font-size: 14px;
        }

        .kgb-masa-kerja-note {
            margin-top: 12px;
            padding: 11px 14px;
            border-radius: 9px;
            background: #f5f7fa;
            border: 1px solid #e5e9ef;
            color: #7a8495;
            font-size: 12px;
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            .kgb-card-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .kgb-info-grid {
                grid-template-columns: 1fr;
            }

            .kgb-footer {
                flex-direction: column;
            }

            .kgb-footer .kgb-btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')

    <div class="kgb-detail-page">

        {{-- ================================================= --}}
        {{-- SUCCESS --}}
        {{-- ================================================= --}}

        @if (session('success'))
            <div class="kgb-alert">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif


        {{-- ================================================= --}}
        {{-- CARD --}}
        {{-- ================================================= --}}

        <div class="kgb-card">

            {{-- ================================================= --}}
            {{-- HEADER --}}
            {{-- ================================================= --}}

            <div class="kgb-card-header">

                <div>
                    <h5>
                        <i class="bi bi-file-earmark-text me-2"></i>
                        {{ $batch->kgb_batch_nama }}
                    </h5>

                    <p>
                        Detail batch Kenaikan Gaji Berkala
                    </p>
                </div>

                <div class="kgb-header-actions">

                    <a href="{{ route('samperin.admin.kgb.edit', $batch->kgb_batch_id) }}" class="kgb-btn kgb-btn-edit">
                        <i class="bi bi-pencil-square"></i>
                        Edit Batch
                    </a>

                    <a href="{{ route('samperin.admin.kgb.pdf-all', $batch->kgb_batch_id) }}" target="_blank"
                        class="btn btn-primary-custom">

                        <i class="bi bi-file-earmark-pdf me-1"></i>

                        Cetak Semua KGB

                    </a>

                </div>

            </div>


            <div class="kgb-card-body">


                {{-- ================================================= --}}
                {{-- INFORMASI BATCH --}}
                {{-- ================================================= --}}

                <div class="kgb-section">

                    <div class="kgb-section-title">
                        <i class="bi bi-collection"></i>
                        Informasi Batch
                    </div>


                    <div class="kgb-info-grid">


                        {{-- NAMA --}}
                        <div class="kgb-info-item">

                            <div class="kgb-info-label">
                                Nama Batch
                            </div>

                            <div class="kgb-info-value">
                                {{ $batch->kgb_batch_nama }}
                            </div>

                        </div>


                        {{-- PERATURAN --}}
                        <div class="kgb-info-item">

                            <div class="kgb-info-label">
                                Peraturan Gaji
                            </div>

                            <div class="kgb-info-value">

                                {{ $batch->peraturanGaji->peraturan_gaji_nama ?? '-' }}

                                @if ($batch->peraturanGaji)
                                    <div class="text-muted small mt-1">

                                        {{ $batch->peraturanGaji->peraturan_gaji_nomor ?? '' }}

                                    </div>
                                @endif

                            </div>

                        </div>


                        {{-- PEJABAT --}}
                        <div class="kgb-info-item">

                            <div class="kgb-info-label">
                                Pejabat
                            </div>

                            <div class="kgb-info-value">

                                {{ $batch->pejabat->user_nama ?? '-' }}

                                @if ($batch->pejabat && $batch->pejabat->user_nip)
                                    <div class="text-muted small mt-1">

                                        {{ $batch->pejabat->user_nip }}

                                    </div>
                                @endif

                            </div>

                        </div>


                        {{-- TANGGAL SURAT --}}
                        <div class="kgb-info-item">

                            <div class="kgb-info-label">
                                Tanggal Surat
                            </div>

                            <div class="kgb-info-value">

                                @if ($batch->kgb_batch_tanggal)
                                    {{ \Carbon\Carbon::parse($batch->kgb_batch_tanggal)->format('d-m-Y') }}
                                @else
                                    -
                                @endif

                            </div>

                        </div>


                        {{-- MULAI BERLAKU --}}
                        <div class="kgb-info-item">

                            <div class="kgb-info-label">
                                Mulai Berlaku
                            </div>

                            <div class="kgb-info-value">

                                @if ($batch->kgb_batch_mulai_berlaku)
                                    {{ \Carbon\Carbon::parse($batch->kgb_batch_mulai_berlaku)->format('d-m-Y') }}
                                @else
                                    -
                                @endif

                            </div>

                        </div>


                        {{-- FORMAT NOMOR --}}
                        <div class="kgb-info-item">

                            <div class="kgb-info-label">
                                Format Nomor Surat
                            </div>

                            <div class="kgb-info-value">
                                {{ $batch->kgb_batch_nomor_format }}
                            </div>

                        </div>


                        {{-- NOMOR AWAL --}}
                        <div class="kgb-info-item">

                            <div class="kgb-info-label">
                                Nomor Awal
                            </div>

                            <div class="kgb-info-value">

                                {{ str_pad($batch->kgb_batch_nomor_awal, 3, '0', STR_PAD_LEFT) }}

                            </div>

                        </div>


                        {{-- NOMOR AKHIR --}}
                        <div class="kgb-info-item">

                            <div class="kgb-info-label">
                                Nomor Akhir
                            </div>

                            <div class="kgb-info-value">

                                @if ($batch->kgb_batch_nomor_akhir)
                                    {{ str_pad($batch->kgb_batch_nomor_akhir, 3, '0', STR_PAD_LEFT) }}
                                @else
                                    -
                                @endif

                            </div>

                        </div>


                        {{-- TOTAL --}}
                        <div class="kgb-info-item">

                            <div class="kgb-info-label">
                                Total Pegawai
                            </div>

                            <div class="kgb-info-value">

                                {{ $batch->kgb->count() }}
                                Pegawai

                            </div>

                        </div>


                        {{-- STATUS --}}
                        <div class="kgb-info-item">

                            <div class="kgb-info-label">
                                Status Batch
                            </div>

                            <div class="kgb-info-value">

                                @if ($batch->kgb_batch_status)
                                    <span class="kgb-status kgb-status-active">

                                        <i class="bi bi-check-circle me-1"></i>

                                        Aktif

                                    </span>
                                @else
                                    <span class="kgb-status kgb-status-inactive">

                                        <i class="bi bi-x-circle me-1"></i>

                                        Nonaktif

                                    </span>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- DAFTAR PEGAWAI --}}
                {{-- ================================================= --}}

                <div class="kgb-section">

                    <div class="kgb-section-title">

                        <i class="bi bi-people"></i>

                        Daftar Pegawai

                    </div>


                    <div class="kgb-masa-kerja-note">

                        <i class="bi bi-info-circle me-1"></i>

                        <strong>Masa kerja</strong> dihitung berdasarkan
                        TMT masing-masing pegawai sampai dengan
                        tanggal <strong>Mulai Berlaku KGB</strong>.

                    </div>


                    <div class="kgb-table-wrapper mt-3">

                        <table class="kgb-table">

                            <thead>

                                <tr>

                                    <th width="50">
                                        No
                                    </th>

                                    <th>
                                        Pegawai
                                    </th>

                                    <th>
                                        Golongan
                                    </th>

                                    <th>
                                        Nomor Surat
                                    </th>

                                    <th>
                                        Gaji Lama
                                    </th>

                                    <th>
                                        Gaji Baru
                                    </th>

                                    <th>
                                        TMT
                                    </th>

                                    <th>
                                        Masa Kerja
                                    </th>

                                    <th>
                                        Nomor SK
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th width="50">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse ($batch->kgb as $index => $kgb)
                                    @php

                                        /*
                                        |--------------------------------------------------------------------------
                                        | TMT PEGAWAI
                                        |--------------------------------------------------------------------------
                                        */

                                        $tmt = null;

                                        if ($kgb->user && $kgb->user->user_tmt) {
                                            $tmt = \Carbon\Carbon::parse($kgb->user->user_tmt);
                                        }

                                        /*
                                        |--------------------------------------------------------------------------
                                        | TANGGAL MULAI BERLAKU
                                        |--------------------------------------------------------------------------
                                        */

                                        $mulaiBerlaku = null;

                                        if ($batch->kgb_batch_mulai_berlaku) {
                                            $mulaiBerlaku = \Carbon\Carbon::parse($batch->kgb_batch_mulai_berlaku);
                                        }

                                        /*
                                        |--------------------------------------------------------------------------
                                        | MASA KERJA
                                        |--------------------------------------------------------------------------
                                        */

                                        $masaKerjaTahun = 0;
                                        $masaKerjaBulan = 0;

                                        if ($tmt && $mulaiBerlaku && $tmt->lessThanOrEqualTo($mulaiBerlaku)) {
                                            $diff = $tmt->diff($mulaiBerlaku);

                                            $masaKerjaTahun = $diff->y;
                                            $masaKerjaBulan = $diff->m;
                                        }

                                    @endphp


                                    <tr>

                                        {{-- NO --}}
                                        <td>
                                            {{ $index + 1 }}
                                        </td>


                                        {{-- PEGAWAI --}}
                                        <td>

                                            <div class="kgb-employee-name">

                                                {{ $kgb->user->user_nama ?? '-' }}

                                            </div>

                                            <div class="kgb-employee-nip">

                                                {{ $kgb->user->user_nip ?? '-' }}

                                            </div>

                                        </td>


                                        {{-- GOLONGAN --}}
                                        <td>

                                            {{ $kgb->golongan->golongan_nama ?? '-' }}

                                        </td>


                                        {{-- NOMOR SURAT --}}
                                        <td>

                                            {{ $kgb->kgb_nomor_surat ?? '-' }}

                                        </td>


                                        {{-- GAJI LAMA --}}
                                        <td>

                                            Rp
                                            {{ number_format((int) $kgb->kgb_gaji_lama, 0, ',', '.') }}

                                        </td>


                                        {{-- GAJI BARU --}}
                                        <td>

                                            <strong>

                                                Rp
                                                {{ number_format((int) $kgb->kgb_gaji_baru, 0, ',', '.') }}

                                            </strong>

                                        </td>


                                        {{-- TMT --}}
                                        <td>

                                            @if ($tmt)
                                                <span class="kgb-employee-tmt">

                                                    {{ $tmt->format('d-m-Y') }}

                                                </span>
                                            @else
                                                <span class="kgb-badge-danger">

                                                    <i class="bi bi-exclamation-circle me-1"></i>

                                                    Belum ada TMT

                                                </span>
                                            @endif

                                        </td>


                                        {{-- MASA KERJA --}}
                                        <td>

                                            @if ($tmt && $mulaiBerlaku && $tmt->lessThanOrEqualTo($mulaiBerlaku))
                                                <span class="kgb-badge-info">

                                                    {{ $masaKerjaTahun }}
                                                    Tahun

                                                    {{ $masaKerjaBulan }}
                                                    Bulan

                                                </span>
                                            @elseif ($tmt && $mulaiBerlaku)
                                                <span class="kgb-badge-danger">

                                                    TMT setelah tanggal berlaku

                                                </span>
                                            @else
                                                <span class="kgb-badge-danger">

                                                    Tidak dapat dihitung

                                                </span>
                                            @endif

                                        </td>


                                        {{-- NOMOR SK --}}
                                        <td>

                                            @if ($kgb->kgb_nomor_sk)
                                                <span class="text-success fw-semibold">

                                                    {{ $kgb->kgb_nomor_sk }}

                                                </span>
                                            @else
                                                <span class="text-muted">

                                                    Belum diisi

                                                </span>
                                            @endif

                                        </td>


                                        {{-- STATUS --}}
                                        <td>

                                            @if ($kgb->kgb_status)
                                                <span class="kgb-status kgb-status-active">

                                                    <i class="bi bi-check-circle me-1"></i>

                                                    Aktif

                                                </span>
                                            @else
                                                <span class="kgb-status kgb-status-inactive">

                                                    <i class="bi bi-x-circle me-1"></i>

                                                    Nonaktif

                                                </span>
                                            @endif

                                        </td>

                                        <td>
                                            <a href="{{ route('samperin.admin.kgb.pdf', [
                                                'id' => $batch->kgb_batch_id,
                                                'kgbId' => $kgb->kgb_id,
                                            ]) }}"
                                                target="_blank" class="btn btn-sm btn-outline-danger" title="Cetak KGB">

                                                <i class="bi bi-file-earmark-pdf"></i>

                                            </a>
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="10" class="kgb-empty">

                                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>

                                            Belum ada data KGB dalam batch ini.

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- FOOTER --}}
                {{-- ================================================= --}}

                <div class="kgb-footer">

                    <a href="{{ route('samperin.admin.kgb.index') }}" class="kgb-btn kgb-btn-back">

                        <i class="bi bi-arrow-left"></i>

                        Kembali

                    </a>


                    <a href="{{ route('samperin.admin.kgb.edit', $batch->kgb_batch_id) }}" class="kgb-btn kgb-btn-edit">

                        <i class="bi bi-pencil-square"></i>

                        Edit Batch

                    </a>

                </div>

            </div>

        </div>

    </div>

@endsection
