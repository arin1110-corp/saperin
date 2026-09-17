@extends('dashboard.layouts.app')

@section('title', 'Edit Batch KGB')
@section('header-title', 'Edit Batch Kenaikan Gaji Berkala')
@section('breadcrumb', 'Edit Batch KGB')

@section('page-style')

    <style>
        .kgb-page {
            padding-bottom: 40px;
        }

        .kgb-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .kgb-card-header {
            padding: 20px 24px;
            background: linear-gradient(135deg,
                    #0f172a,
                    #1e293b);
            color: #fff;
        }

        .kgb-card-header h5 {
            margin: 0;
            font-weight: 700;
        }

        .kgb-card-header small {
            color: rgba(255, 255, 255, 0.7);
        }

        .kgb-card-body {
            padding: 24px;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            min-height: 44px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.1);
        }

        .employee-table {
            margin-bottom: 0;
        }

        .employee-table thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .employee-table tbody td {
            padding: 13px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .employee-table tbody tr:hover {
            background: #f8fafc;
        }

        .employee-name {
            font-weight: 600;
            color: #1e293b;
        }

        .employee-nip {
            font-size: 13px;
            color: #64748b;
        }

        .employee-info {
            font-size: 13px;
            color: #64748b;
        }

        .badge-filter {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-existing {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-warning-custom {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .empty-employee {
            padding: 60px 20px;
            text-align: center;
            color: #64748b;
        }

        .empty-employee i {
            font-size: 48px;
            color: #cbd5e1;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg,
                    #2563eb,
                    #1d4ed8);
            border: 0;
            color: #fff;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .btn-primary-custom:hover {
            color: #fff;
            opacity: 0.92;
        }

        .btn-secondary-custom {
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .btn-danger-custom {
            border-radius: 8px;
            padding: 7px 10px;
            font-weight: 600;
        }

        .filter-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px;
        }

        .selected-info {
            font-size: 13px;
            color: #64748b;
        }

        .section-note {
            font-size: 13px;
            color: #64748b;
        }

        .batch-info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
        }

        .batch-info-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .batch-info-value {
            font-weight: 700;
            color: #1e293b;
        }

        .error-box {
            border-radius: 12px;
        }

        .masa-kerja {
            font-weight: 600;
            color: #1e293b;
            white-space: nowrap;
        }

        .remove-form {
            margin: 0;
        }

        .checkbox-large {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .already-row {
            background: #f8fafc;
        }

        .already-row:hover {
            background: #f1f5f9 !important;
        }

        .add-summary {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
        }

        .danger-note {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
        }

        code {
            color: #1d4ed8;
        }
    </style>

@endsection

@section('content')

    <div class="kgb-page">

        {{-- ========================================================= --}}
        {{-- ERROR --}}
        {{-- ========================================================= --}}

        @if ($errors->any())

            <div class="alert alert-danger error-box mb-4">

                <div class="fw-bold mb-2">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Terdapat kesalahan

                </div>

                <ul class="mb-0 ps-3">

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- SUCCESS --}}
        {{-- ========================================================= --}}

        @if (session('success'))
            <div class="alert alert-success error-box mb-4">

                <i class="bi bi-check-circle me-1"></i>

                {{ session('success') }}

            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- INFORMASI BATCH --}}
        {{-- ========================================================= --}}

        <div class="card kgb-card mb-4">

            <div class="kgb-card-header">

                <h5>

                    <i class="bi bi-info-circle me-2"></i>

                    Informasi Batch

                </h5>

                <small>

                    Informasi batch KGB yang sedang diedit.

                </small>

            </div>

            <div class="kgb-card-body">

                <div class="row g-3">

                    {{-- NAMA --}}
                    <div class="col-md-4">

                        <div class="batch-info-box">

                            <div class="batch-info-label">
                                Nama Batch
                            </div>

                            <div class="batch-info-value">
                                {{ $batch->kgb_batch_nama }}
                            </div>

                        </div>

                    </div>


                    {{-- JUMLAH --}}
                    <div class="col-md-2">

                        <div class="batch-info-box">

                            <div class="batch-info-label">
                                Jumlah Pegawai
                            </div>

                            <div class="batch-info-value">

                                {{ $batch->kgb->count() }}

                                <span class="fw-normal text-muted">
                                    orang
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- NOMOR AWAL --}}
                    <div class="col-md-2">

                        <div class="batch-info-box">

                            <div class="batch-info-label">
                                Nomor Awal
                            </div>

                            <div class="batch-info-value">

                                {{ str_pad($batch->kgb_batch_nomor_awal, 3, '0', STR_PAD_LEFT) }}

                            </div>

                        </div>

                    </div>


                    {{-- NOMOR AKHIR --}}
                    <div class="col-md-2">

                        <div class="batch-info-box">

                            <div class="batch-info-label">
                                Nomor Akhir
                            </div>

                            <div class="batch-info-value">

                                @if ($batch->kgb_batch_nomor_akhir)
                                    {{ str_pad($batch->kgb_batch_nomor_akhir, 3, '0', STR_PAD_LEFT) }}
                                @else
                                    -
                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-md-2">

                        <div class="batch-info-box">

                            <div class="batch-info-label">
                                Status
                            </div>

                            <div class="batch-info-value">

                                @if ($batch->kgb_batch_status)
                                    <span class="badge bg-success">
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Nonaktif
                                    </span>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FORM UPDATE BATCH --}}
        {{-- ========================================================= --}}

        <form method="POST" action="{{ route('samperin.admin.kgb.update', $batch->kgb_batch_id) }}" id="kgbEditForm">

            @csrf

            @method('PUT')


            <div class="card kgb-card mb-4">

                <div class="kgb-card-header">

                    <h5>

                        <i class="bi bi-pencil-square me-2"></i>

                        Data Batch KGB

                    </h5>

                    <small>

                        Perubahan berikut akan digunakan pada batch ini.

                    </small>

                </div>


                <div class="kgb-card-body">

                    <div class="row g-4">

                        {{-- NAMA BATCH --}}
                        <div class="col-md-6">

                            <label class="form-label">

                                Nama Batch

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text" name="kgb_batch_nama" class="form-control"
                                value="{{ old('kgb_batch_nama', $batch->kgb_batch_nama) }}"
                                placeholder="Contoh: KGB Pegawai Tahun 2026" required>

                        </div>


                        {{-- PERATURAN GAJI --}}
                        <div class="col-md-6">

                            <label class="form-label">

                                Peraturan Gaji

                                <span class="text-danger">*</span>

                            </label>

                            <select name="kgb_batch_peraturan_gaji_id" class="form-select" required>

                                <option value="">
                                    -- Pilih Peraturan Gaji --
                                </option>

                                @foreach ($peraturanGaji as $item)
                                    <option value="{{ $item->peraturan_gaji_id }}"
                                        {{ old('kgb_batch_peraturan_gaji_id', $batch->kgb_batch_peraturan_gaji_id) == $item->peraturan_gaji_id
                                            ? 'selected'
                                            : '' }}>

                                        {{ $item->peraturan_gaji_nama }}

                                        @if ($item->peraturan_gaji_nomor)
                                            - {{ $item->peraturan_gaji_nomor }}
                                        @endif

                                        @if ($item->peraturan_gaji_tahun)
                                            ({{ $item->peraturan_gaji_tahun }})
                                        @endif

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        {{-- PEJABAT --}}
                        <div class="col-md-6">

                            <label class="form-label">

                                Pejabat Penandatangan

                                <span class="text-danger">*</span>

                            </label>

                            <select name="kgb_batch_pejabat_id" class="form-select" required>

                                <option value="">
                                    -- Pilih Pejabat --
                                </option>

                                @foreach ($pejabat as $item)
                                    <option value="{{ $item->user_id }}"
                                        {{ old('kgb_batch_pejabat_id', $batch->kgb_batch_pejabat_id) == $item->user_id ? 'selected' : '' }}>

                                        {{ $item->user_nama }}

                                        @if ($item->user_nip)
                                            - {{ $item->user_nip }}
                                        @endif

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        {{-- TANGGAL SURAT --}}
                        <div class="col-md-3">

                            <label class="form-label">

                                Tanggal Surat

                                <span class="text-danger">*</span>

                            </label>

                            <input type="date" name="kgb_batch_tanggal" class="form-control"
                                value="{{ old(
                                    'kgb_batch_tanggal',
                                    optional($batch->kgb_batch_tanggal)
                                        ? \Carbon\Carbon::parse($batch->kgb_batch_tanggal)->format('Y-m-d')
                                        : now()->format('Y-m-d'),
                                ) }}"
                                required>

                        </div>


                        {{-- MULAI BERLAKU --}}
                        <div class="col-md-3">

                            <label class="form-label">

                                Mulai Berlaku

                                <span class="text-danger">*</span>

                            </label>

                            <input type="date" name="kgb_batch_mulai_berlaku" id="kgb_batch_mulai_berlaku"
                                class="form-control"
                                value="{{ old(
                                    'kgb_batch_mulai_berlaku',
                                    optional($batch->kgb_batch_mulai_berlaku)
                                        ? \Carbon\Carbon::parse($batch->kgb_batch_mulai_berlaku)->format('Y-m-d')
                                        : '',
                                ) }}"
                                required>

                        </div>


                        {{-- FORMAT NOMOR --}}
                        <div class="col-md-6">

                            <label class="form-label">

                                Format Nomor Surat

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text" name="kgb_batch_nomor_format" class="form-control"
                                value="{{ old('kgb_batch_nomor_format', $batch->kgb_batch_nomor_format) }}"
                                placeholder="{nomor}/KGB/DISBUD/{tahun}" required>

                            <div class="form-text">

                                Gunakan
                                <code>{nomor}</code>
                                untuk nomor urut dan
                                <code>{tahun}</code>
                                untuk tahun.

                            </div>

                        </div>


                        {{-- NOMOR AWAL --}}
                        <div class="col-md-3">

                            <label class="form-label">

                                Nomor Awal

                                <span class="text-danger">*</span>

                            </label>

                            <input type="number" name="kgb_batch_nomor_awal" class="form-control" min="1"
                                value="{{ old('kgb_batch_nomor_awal', $batch->kgb_batch_nomor_awal) }}"
                                required>

                            <div class="form-text">

                                Contoh:
                                1 akan menghasilkan 001.

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>


        {{-- ========================================================= --}}
        {{-- PEGAWAI YANG SUDAH DALAM BATCH --}}
        {{-- ========================================================= --}}

        <div class="card kgb-card mb-4">

            <div class="kgb-card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5>

                            <i class="bi bi-people-fill me-2"></i>

                            Pegawai Dalam Batch

                        </h5>

                        <small>

                            Pegawai yang sudah masuk dalam batch KGB ini.

                        </small>

                    </div>

                    <div class="text-end">

                        <div class="fw-bold">

                            {{ $batch->kgb->count() }}

                        </div>

                        <small>
                            Pegawai
                        </small>

                    </div>

                </div>

            </div>


            <div class="kgb-card-body p-0">

                @if ($batch->kgb->count())

                    <div class="table-responsive">

                        <table class="table employee-table">

                            <thead>

                                <tr>

                                    <th width="60" class="text-center">
                                        No
                                    </th>

                                    <th width="160">
                                        NIP
                                    </th>

                                    <th>
                                        Nama Pegawai
                                    </th>

                                    <th>
                                        Jabatan
                                    </th>

                                    <th>
                                        Bidang
                                    </th>

                                    <th>
                                        Golongan
                                    </th>

                                    <th>
                                        TMT
                                    </th>

                                    <th>
                                        Masa Kerja
                                    </th>

                                    <th width="90" class="text-center">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($batch->kgb as $kgb)
                                    @php

                                        $pegawaiItem = $kgb->user;

                                        $tmt = $pegawaiItem?->user_tmt;

                                        $mulaiBerlaku = $batch->kgb_batch_mulai_berlaku;

                                        $masaKerjaTahun = 0;
                                        $masaKerjaBulan = 0;

                                        if ($tmt && $mulaiBerlaku) {
                                            try {
                                                $tanggalTmt = \Carbon\Carbon::parse($tmt);
                                                $tanggalBerlaku = \Carbon\Carbon::parse($mulaiBerlaku);

                                                if ($tanggalTmt->lessThanOrEqualTo($tanggalBerlaku)) {
                                                    $masaKerja = $tanggalTmt->diff($tanggalBerlaku);

                                                    $masaKerjaTahun = $masaKerja->y;
                                                    $masaKerjaBulan = $masaKerja->m;
                                                }
                                            } catch (\Throwable $e) {
                                                $masaKerjaTahun = $kgb->kgb_masa_kerja_tahun ?? 0;
                                                $masaKerjaBulan = $kgb->kgb_masa_kerja_bulan ?? 0;
                                            }
                                        } else {
                                            $masaKerjaTahun = $kgb->kgb_masa_kerja_tahun ?? 0;
                                            $masaKerjaBulan = $kgb->kgb_masa_kerja_bulan ?? 0;
                                        }

                                    @endphp

                                    <tr>

                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>


                                        <td>

                                            <div class="employee-nip">
                                                {{ $pegawaiItem?->user_nip ?? '-' }}
                                            </div>

                                        </td>


                                        <td>

                                            <div class="employee-name">
                                                {{ $pegawaiItem?->user_nama ?? '-' }}
                                            </div>

                                        </td>


                                        <td>

                                            {{ optional($pegawaiItem?->jabatan)->jabatan_nama ?? '-' }}

                                        </td>


                                        <td>

                                            {{ optional($pegawaiItem?->bidang)->bidang_nama ?? '-' }}

                                        </td>


                                        <td>

                                            {{ optional($kgb->golongan)->golongan_nama ?? (optional($pegawaiItem?->golongan)->golongan_nama ?? '-') }}

                                        </td>


                                        <td>

                                            @if ($tmt)
                                                {{ \Carbon\Carbon::parse($tmt)->format('d-m-Y') }}
                                            @else
                                                <span class="text-danger">
                                                    Belum ada TMT
                                                </span>
                                            @endif

                                        </td>


                                        <td>

                                            <span class="masa-kerja">

                                                {{ $masaKerjaTahun }}
                                                Tahun
                                                {{ $masaKerjaBulan }}
                                                Bulan

                                            </span>

                                        </td>


                                        <td class="text-center">

                                            <form method="POST"
                                                action="{{ route('samperin.admin.kgb.remove-pegawai', [
                                                    'id' => $batch->kgb_batch_id,
                                                    'kgbId' => $kgb->kgb_id,
                                                ]) }}"
                                                class="remove-form"
                                                onsubmit="return confirm('Yakin ingin menghapus pegawai ini dari batch KGB? Data pegawai tidak akan dihapus dari sistem.');">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit" class="btn btn-outline-danger btn-danger-custom"
                                                    title="Hapus dari batch">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>
                @else
                    <div class="empty-employee">

                        <i class="bi bi-person-x"></i>

                        <h6 class="mt-3">
                            Belum ada pegawai
                        </h6>

                        <p class="mb-0">
                            Belum ada pegawai yang masuk dalam batch ini.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FILTER PEGAWAI UNTUK DITAMBAHKAN --}}
        {{-- ========================================================= --}}

        <div class="card kgb-card mb-4">

            <div class="kgb-card-header">

                <h5>

                    <i class="bi bi-person-plus me-2"></i>

                    Tambah Pegawai

                </h5>

                <small>

                    Cari dan pilih pegawai yang akan ditambahkan ke batch.

                </small>

            </div>


            <div class="kgb-card-body">

                <form method="GET"
                    action="{{ route('samperin.admin.kgb.edit', $batch->kgb_batch_id) }}">

                    <div class="filter-box">

                        <div class="row g-3">

                            {{-- GOLONGAN --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Golongan
                                </label>

                                <select name="golongan_id" class="form-select">

                                    <option value="">
                                        -- Semua Golongan --
                                    </option>

                                    @foreach ($golongan as $item)
                                        <option value="{{ $item->golongan_id }}"
                                            {{ request('golongan_id') == $item->golongan_id ? 'selected' : '' }}>

                                            {{ $item->golongan_nama }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>


                            {{-- SEARCH --}}
                            <div class="col-md-5">

                                <label class="form-label">
                                    Cari Pegawai
                                </label>

                                <input type="text" name="search" class="form-control"
                                    value="{{ request('search') }}" placeholder="Cari nama atau NIP...">

                            </div>


                            {{-- BUTTON --}}
                            <div class="col-md-3 d-flex align-items-end gap-2">

                                <button type="submit" class="btn btn-primary-custom flex-grow-1">

                                    <i class="bi bi-search me-1"></i>

                                    Terapkan Filter

                                </button>


                                <a href="{{ route('samperin.admin.kgb.edit', $batch->kgb_batch_id) }}"
                                    class="btn btn-outline-secondary btn-secondary-custom">

                                    <i class="bi bi-arrow-counterclockwise"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- INFO FILTER --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                @if (request('golongan_id'))
                    <span class="badge-filter">

                        <i class="bi bi-funnel me-1"></i>

                        Golongan:

                        {{ optional($golongan->firstWhere('golongan_id', request('golongan_id')))->golongan_nama ?? '-' }}

                    </span>
                @endif


                @if (request('search'))
                    <span class="badge-filter ms-1">

                        <i class="bi bi-search me-1"></i>

                        "{{ request('search') }}"

                    </span>
                @endif

            </div>


            <div class="selected-info">

                <strong>
                    {{ $jumlahPegawai }}
                </strong>

                pegawai ditampilkan

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- PEGAWAI YANG BISA DITAMBAHKAN --}}
        {{-- ========================================================= --}}

        <div class="card kgb-card mb-4">

            <div class="kgb-card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5>

                            <i class="bi bi-person-check me-2"></i>

                            Pilih Pegawai Tambahan

                        </h5>

                        <small>

                            Pegawai yang sudah ada dalam batch tidak perlu dipilih lagi.

                        </small>

                    </div>


                    <div class="text-end">

                        <div class="fw-bold">
                            {{ $jumlahPegawai }}
                        </div>

                        <small>
                            Pegawai
                        </small>

                    </div>

                </div>

            </div>


            {{-- TOOLBAR --}}
            <div class="px-4 pt-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="selected-info">

                        <i class="bi bi-info-circle me-1"></i>

                        Centang pegawai yang ingin ditambahkan.

                    </div>


                    <div class="d-flex gap-2">

                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnCheckAll">

                            <i class="bi bi-check2-square me-1"></i>

                            Centang Semua

                        </button>


                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnUncheckAll">

                            <i class="bi bi-square me-1"></i>

                            Batal Semua

                        </button>

                    </div>

                </div>

            </div>


            {{-- TABLE --}}
            <div class="kgb-card-body p-0 mt-3">

                @if ($pegawai->count())

                    <div class="table-responsive">

                        <table class="table employee-table">

                            <thead>

                                <tr>

                                    <th width="60" class="text-center">
                                        No
                                    </th>

                                    <th width="160">
                                        NIP
                                    </th>

                                    <th>
                                        Nama Pegawai
                                    </th>

                                    <th>
                                        Jabatan
                                    </th>

                                    <th>
                                        Bidang
                                    </th>

                                    <th>
                                        Golongan
                                    </th>

                                    <th width="110" class="text-center">
                                        Status
                                    </th>

                                    <th width="80" class="text-center">
                                        Pilih
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($pegawai as $item)
                                    @php

                                        $sudahDalamBatch = $pegawaiDalamBatch->contains($item->user_id);

                                    @endphp

                                    <tr class="{{ $sudahDalamBatch ? 'already-row' : '' }}">

                                        <td class="text-center">

                                            {{ $loop->iteration }}

                                        </td>


                                        <td>

                                            <div class="employee-nip">

                                                {{ $item->user_nip ?? '-' }}

                                            </div>

                                        </td>


                                        <td>

                                            <div class="employee-name">

                                                {{ $item->user_nama }}

                                            </div>

                                        </td>


                                        <td>

                                            {{ optional($item->jabatan)->jabatan_nama ?? '-' }}

                                        </td>


                                        <td>

                                            {{ optional($item->bidang)->bidang_nama ?? '-' }}

                                        </td>


                                        <td>

                                            {{ optional($item->golongan)->golongan_nama ?? '-' }}

                                        </td>


                                        <td class="text-center">

                                            @if ($sudahDalamBatch)
                                                <span class="badge-existing">

                                                    <i class="bi bi-check-circle me-1"></i>

                                                    Sudah Masuk

                                                </span>
                                            @else
                                                <span class="badge-warning-custom">

                                                    Belum Masuk

                                                </span>
                                            @endif

                                        </td>


                                        <td class="text-center">

                                            @if ($sudahDalamBatch)
                                                <input type="checkbox" class="form-check-input checkbox-large" disabled>
                                            @else
                                                <input type="checkbox"
                                                    class="form-check-input checkbox-large pegawai-checkbox"
                                                    name="pegawai[]" value="{{ $item->user_id }}" form="kgbEditForm"
                                                    {{ in_array($item->user_id, old('pegawai', [])) ? 'checked' : '' }}>
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>
                @else
                    <div class="empty-employee">

                        <i class="bi bi-person-x"></i>

                        <h6 class="mt-3">
                            Tidak ada pegawai
                        </h6>

                        <p class="mb-0">

                            Tidak ditemukan pegawai yang sesuai
                            dengan filter yang dipilih.

                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- CATATAN --}}
        {{-- ========================================================= --}}

        <div class="danger-note mb-4">

            <div class="fw-bold mb-1">

                <i class="bi bi-exclamation-triangle me-1"></i>

                Perhatian

            </div>

            <div>

                Menghapus pegawai di atas hanya menghapus pegawai
                dari <strong>batch KGB ini</strong>.
                Data pegawai pada SAMPERIN tetap aman dan tidak ikut
                terhapus.

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ACTION --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-between align-items-center mt-4">

            <a href="{{ route('samperin.admin.kgb.show', $batch->kgb_batch_id) }}"
                class="btn btn-outline-secondary btn-secondary-custom">

                <i class="bi bi-arrow-left me-1"></i>

                Kembali

            </a>


            <button type="submit" form="kgbEditForm" class="btn btn-primary-custom" id="btnSubmit">

                <i class="bi bi-check-circle me-1"></i>

                Simpan Perubahan

            </button>

        </div>

    </div>

@endsection


@section('page-script')

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | CHECKBOX PEGAWAI TAMBAHAN
            |--------------------------------------------------------------------------
            */

            const checkboxes = document.querySelectorAll(
                '.pegawai-checkbox'
            );

            const btnCheckAll = document.getElementById(
                'btnCheckAll'
            );

            const btnUncheckAll = document.getElementById(
                'btnUncheckAll'
            );


            /*
            |--------------------------------------------------------------------------
            | CENTANG SEMUA
            |--------------------------------------------------------------------------
            */

            if (btnCheckAll) {

                btnCheckAll.addEventListener(
                    'click',
                    function() {

                        checkboxes.forEach(function(checkbox) {

                            if (!checkbox.disabled) {

                                checkbox.checked = true;

                            }

                        });

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | BATAL SEMUA
            |--------------------------------------------------------------------------
            */

            if (btnUncheckAll) {

                btnUncheckAll.addEventListener(
                    'click',
                    function() {

                        checkboxes.forEach(function(checkbox) {

                            if (!checkbox.disabled) {

                                checkbox.checked = false;

                            }

                        });

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | HITUNG PEGAWAI YANG DIPILIH
            |--------------------------------------------------------------------------
            */

            function updateSelectedCount() {

                const selected = document.querySelectorAll(
                    '.pegawai-checkbox:checked'
                ).length;

                const selectedText = document.getElementById(
                    'selectedEmployeeCount'
                );

                if (selectedText) {

                    selectedText.textContent = selected;

                }

            }


            checkboxes.forEach(function(checkbox) {

                checkbox.addEventListener(
                    'change',
                    updateSelectedCount
                );

            });


            updateSelectedCount();


            /*
            |--------------------------------------------------------------------------
            | KONFIRMASI SUBMIT
            |--------------------------------------------------------------------------
            */

            const editForm = document.getElementById(
                'kgbEditForm'
            );

            if (editForm) {

                editForm.addEventListener(
                    'submit',
                    function(event) {

                        const selected = document.querySelectorAll(
                            '.pegawai-checkbox:checked'
                        ).length;

                        if (selected > 0) {

                            const konfirmasi = confirm(
                                'Tambahkan ' +
                                selected +
                                ' pegawai baru ke batch KGB ini?'
                            );

                            if (!konfirmasi) {

                                event.preventDefault();

                            }

                        }

                    }
                );

            }

        });
    </script>

@endsection
