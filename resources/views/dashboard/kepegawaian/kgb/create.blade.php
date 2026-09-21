@extends('dashboard.layouts.app')

@section('title', 'Buat Batch KGB')
@section('header-title', 'Buat Batch Kenaikan Gaji Berkala')
@section('breadcrumb', 'Buat Batch KGB')

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

        .badge-filter {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
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

        .error-box {
            border-radius: 12px;
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
        {{-- FILTER PEGAWAI --}}
        {{-- ========================================================= --}}
        {{--

        FORM GET TERPISAH.

        Contoh:
        /admin/kgb/create?golongan_id=9

        Database hanya mengambil pegawai golongan 9.

    --}}

        <div class="card kgb-card mb-4">

            <div class="kgb-card-header">

                <h5>
                    <i class="bi bi-funnel me-2"></i>
                    Filter Pegawai
                </h5>

                <small>
                    Filter dilakukan langsung dari database.
                </small>

            </div>

            <div class="kgb-card-body">

                <form method="GET" action="{{ route('samperin.admin.kgb.create') }}">

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

                                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                    placeholder="Cari nama atau NIP...">

                            </div>


                            {{-- BUTTON --}}
                            <div class="col-md-3 d-flex align-items-end gap-2">

                                <button type="submit" class="btn btn-primary-custom flex-grow-1">

                                    <i class="bi bi-search me-1"></i>

                                    Terapkan Filter

                                </button>

                                <a href="{{ route('samperin.admin.kgb.create') }}"
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
        {{-- FORM CREATE BATCH --}}
        {{-- ========================================================= --}}

        <form method="POST" action="{{ route('samperin.admin.kgb.store') }}" id="kgbCreateForm">

            @csrf


            {{-- ===================================================== --}}
            {{-- DATA BATCH --}}
            {{-- ===================================================== --}}

            <div class="card kgb-card mb-4">

                <div class="kgb-card-header">

                    <h5>
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Informasi Batch KGB
                    </h5>

                    <small>
                        Data ini akan digunakan untuk seluruh pegawai
                        dalam batch.
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
                                value="{{ old('kgb_batch_nama') }}" placeholder="Contoh: KGB Pegawai Tahun 2026" required>

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
                                        {{ old('kgb_batch_peraturan_gaji_id') == $item->peraturan_gaji_id ? 'selected' : '' }}>

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
                                        {{ old('kgb_batch_pejabat_id') == $item->user_id ? 'selected' : '' }}>

                                        {{ $item->user_nama }}

                                        @if ($item->user_nip)
                                            - {{ $item->user_nip }}
                                        @endif

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Oleh Pejabat
                            </label>

                            <input type="text" name="kgb_batch_oleh_pejabat" class="form-control"
                                value="{{ old('kgb_batch_oleh_pejabat') }}"
                                placeholder="Contoh: Kepala BKPSDM Provinsi Bali" required>

                            @error('kgb_batch_oleh_pejabat')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- TANGGAL SURAT --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Tanggal Surat
                                <span class="text-danger">*</span>
                            </label>

                            <input type="date" name="kgb_batch_tanggal" class="form-control"
                                value="{{ old('kgb_batch_tanggal', now()->format('Y-m-d')) }}" required>

                        </div>


                        {{-- MULAI BERLAKU --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Mulai Berlaku
                                <span class="text-danger">*</span>
                            </label>

                            <input type="date" name="kgb_batch_mulai_berlaku" class="form-control"
                                value="{{ old('kgb_batch_mulai_berlaku') }}" required>

                        </div>


                        {{-- FORMAT NOMOR --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Format Nomor Surat
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="kgb_batch_nomor_format" class="form-control"
                                value="{{ old('kgb_batch_nomor_format', '{nomor}/KGB/DISBUD/{tahun}') }}"
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
                                value="{{ old('kgb_batch_nomor_awal', 1) }}" required>

                            <div class="form-text">
                                Contoh: 1 akan menghasilkan 001.
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- PEGAWAI --}}
            {{-- ===================================================== --}}

            <div class="card kgb-card">

                <div class="kgb-card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5>
                                <i class="bi bi-people me-2"></i>
                                Pilih Pegawai
                            </h5>

                            <small>
                                Hanya pegawai hasil filter yang akan
                                ditampilkan dan diproses.
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


                {{-- ================================================= --}}
                {{-- TOOLBAR CHECKBOX --}}
                {{-- ================================================= --}}

                <div class="px-4 pt-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="selected-info">

                            <i class="bi bi-info-circle me-1"></i>

                            Centang pegawai yang akan dibuatkan KGB.

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


                {{-- ================================================= --}}
                {{-- TABLE --}}
                {{-- ================================================= --}}

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

                                        <th width="80" class="text-center">
                                            Pilih
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach ($pegawai as $item)
                                        <tr>

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

                                                <input type="checkbox" class="form-check-input pegawai-checkbox"
                                                    name="pegawai[]" value="{{ $item->user_id }}"
                                                    {{ in_array($item->user_id, old('pegawai', [])) ? 'checked' : '' }}>

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


            {{-- ===================================================== --}}
            {{-- ACTION --}}
            {{-- ===================================================== --}}

            <div class="d-flex justify-content-between align-items-center mt-4">

                <a href="{{ route('samperin.admin.kgb.index') }}" class="btn btn-outline-secondary btn-secondary-custom">

                    <i class="bi bi-arrow-left me-1"></i>

                    Kembali

                </a>


                <button type="submit" class="btn btn-primary-custom" id="btnSubmit"
                    {{ $jumlahPegawai === 0 ? 'disabled' : '' }}>

                    <i class="bi bi-check-circle me-1"></i>

                    Buat Batch KGB

                </button>

            </div>

        </form>

    </div>

@endsection


@section('page-script')

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | Checkbox pegawai
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
            | Centang semua
            |--------------------------------------------------------------------------
            |
            | HANYA checkbox yang sedang ada di halaman.
            |
            | Karena controller sudah melakukan filter database,
            | maka otomatis hanya pegawai dari golongan yang dipilih.
            |
            */

            if (btnCheckAll) {

                btnCheckAll.addEventListener(
                    'click',
                    function() {

                        checkboxes.forEach(function(checkbox) {

                            checkbox.checked = true;

                        });

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Batal semua
            |--------------------------------------------------------------------------
            */

            if (btnUncheckAll) {

                btnUncheckAll.addEventListener(
                    'click',
                    function() {

                        checkboxes.forEach(function(checkbox) {

                            checkbox.checked = false;

                        });

                    }
                );

            }

        });
    </script>

@endsection
