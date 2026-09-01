@extends('dashboard.layouts.app')

@section('title', 'Manajemen Pendidikan')

@section('header-title', 'Manajemen Pendidikan')

@section('breadcrumb', 'Manajemen Pendidikan')

@section('page-style')

    <style>
        .pendidikan-page {
            width: 100%;
        }

        .pendidikan-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .pendidikan-header-left {
            min-width: 0;
        }

        .pendidikan-header-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
        }

        .pendidikan-header-description {
            margin: 6px 0 0;
            color: #8993a3;
            font-size: 13px;
        }

        .pendidikan-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pendidikan-add-button {
            border: 0;
            padding: 12px 18px;
            border-radius: 10px;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 7px 18px rgba(195, 94, 29, .18);
        }

        .pendidikan-add-button:hover {
            color: #fff;
        }

        .pendidikan-import-button {
            border: 1px solid #dfe4eb;
            padding: 11px 16px;
            border-radius: 10px;
            background: #fff;
            color: #667085;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
        }

        .pendidikan-import-button:hover {
            background: #f8f9fb;
            color: #273449;
        }

        .pendidikan-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .pendidikan-stat {
            background: #fff;
            border: 1px solid #e7eaf0;
            border-radius: 14px;
            padding: 17px;
            display: flex;
            align-items: center;
            gap: 13px;
            box-shadow: 0 5px 18px rgba(20, 35, 60, .035);
        }

        .pendidikan-stat-icon {
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

        .pendidikan-stat-label {
            color: #929ba9;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .pendidikan-stat-number {
            color: #273449;
            font-size: 21px;
            font-weight: 800;
        }

        .pendidikan-panel {
            background: #fff;
            border: 1px solid #e6eaf0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(20, 35, 60, .04);
        }

        .pendidikan-panel-header {
            padding: 17px 19px;
            border-bottom: 1px solid #edf0f4;
            display: flex;
            align-items: center;
        }

        .pendidikan-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            max-width: 650px;
        }

        .pendidikan-search {
            position: relative;
            flex: 1;
        }

        .pendidikan-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa3b0;
        }

        .pendidikan-search input {
            width: 100%;
            height: 40px;
            padding: 0 12px 0 35px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            outline: none;
            color: #344054;
            font-size: 12px;
        }

        .pendidikan-search input:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .pendidikan-filter-select {
            height: 40px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 0 11px;
            color: #4b5565;
            background: #fff;
            font-size: 12px;
        }

        .pendidikan-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .pendidikan-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        .pendidikan-table th {
            padding: 13px 18px;
            background: #fafbfc;
            color: #929aa6;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
            text-align: left;
            border-bottom: 1px solid #edf0f4;
        }

        .pendidikan-table td {
            padding: 15px 18px;
            border-bottom: 1px solid #f0f2f5;
            color: #475467;
            font-size: 13px;
            vertical-align: middle;
        }

        .pendidikan-id {
            color: #a0a8b4;
            font-size: 10px;
            font-family: monospace;
        }

        .pendidikan-code {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 7px;
            background: #f3f5f8;
            color: #667085;
            font-size: 10px;
            font-weight: 700;
            font-family: monospace;
        }

        .pendidikan-name {
            color: #273449;
            font-size: 14px;
            font-weight: 750;
        }

        .pendidikan-jurusan {
            color: #667085;
            font-size: 12px;
        }

        .pendidikan-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .pendidikan-status.active {
            color: #247a4b;
            background: #eaf8f0;
        }

        .pendidikan-status.inactive {
            color: #7b8491;
            background: #f0f2f5;
        }

        .pendidikan-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .pendidikan-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .pendidikan-action-button {
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
        }

        .pendidikan-action-button:hover {
            background: #f8f9fb;
            color: #273449;
        }

        .pendidikan-action-button.warning:hover {
            background: #fff8e8;
            color: #b87800;
        }

        .pendidikan-action-button.danger:hover {
            background: #fff1f1;
            color: #d33;
        }

        .pendidikan-pagination {
            padding: 15px 18px;
            border-top: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pendidikan-pagination-info {
            color: #8993a3;
            font-size: 11px;
        }

        .pendidikan-pagination-nav {
            display: flex;
            gap: 5px;
        }

        .pendidikan-pagination-nav a,
        .pendidikan-pagination-nav span {
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

        .pendidikan-pagination-nav .active {
            border-color: #df8339;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
        }

        .pendidikan-pagination-nav .disabled {
            color: #c2c8d0;
            background: #f8f9fb;
        }

        .pendidikan-modal .modal-content {
            border: 0;
            border-radius: 17px;
            overflow: hidden;
        }

        .pendidikan-modal .modal-header {
            padding: 18px 21px;
            border: 0;
            background: linear-gradient(135deg, #14223b, #1d3558);
            color: #fff;
        }

        .pendidikan-modal .modal-title {
            font-size: 16px;
            font-weight: 800;
        }

        .pendidikan-modal .btn-close {
            filter: brightness(0) invert(1);
        }

        .pendidikan-modal .modal-body {
            padding: 21px;
        }

        .pendidikan-modal .modal-footer {
            padding: 14px 21px;
            border-top: 1px solid #edf0f4;
        }

        .pendidikan-form-group {
            margin-bottom: 16px;
        }

        .pendidikan-form-label {
            display: block;
            margin-bottom: 7px;
            color: #475467;
            font-size: 11px;
            font-weight: 700;
        }

        .pendidikan-form-control {
            width: 100%;
            height: 42px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 0 12px;
            color: #344054;
            font-size: 12px;
            outline: none;
        }

        .pendidikan-form-control:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .pendidikan-status-options {
            display: flex;
            gap: 8px;
        }

        .pendidikan-status-option {
            flex: 1;
            position: relative;
        }

        .pendidikan-status-option input {
            position: absolute;
            opacity: 0;
        }

        .pendidikan-status-option label {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 42px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            color: #737d8c;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .pendidikan-status-option input:checked+label {
            border-color: #df8339;
            background: #fff8f2;
            color: #c86520;
        }

        .pendidikan-submit-button {
            border: 0;
            border-radius: 8px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        .pendidikan-cancel-button {
            border: 1px solid #dfe4eb;
            border-radius: 8px;
            padding: 10px 16px;
            background: #fff;
            color: #667085;
            font-size: 12px;
        }

        .pendidikan-alert {
            border: 0;
            border-radius: 10px;
            font-size: 12px;
        }

        @media(max-width:800px) {

            .pendidikan-stat-grid {
                grid-template-columns: 1fr;
            }

            .pendidikan-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .pendidikan-header-actions {
                width: 100%;
            }

            .pendidikan-add-button,
            .pendidikan-import-button {
                flex: 1;
                justify-content: center;
            }

            .pendidikan-panel-header {
                align-items: stretch;
            }

            .pendidikan-filter {
                max-width: none;
            }

            .pendidikan-pagination {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }

        @media(max-width:550px) {

            .pendidikan-filter {
                flex-direction: column;
            }

            .pendidikan-search,
            .pendidikan-filter-select {
                width: 100%;
            }

            .pendidikan-header-actions {
                flex-direction: column;
            }

            .pendidikan-add-button,
            .pendidikan-import-button {
                width: 100%;
            }
        }
    </style>

@endsection


@section('content')

    <div class="pendidikan-page">

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success pendidikan-alert mb-3">

                <i class="bi bi-check-circle me-1"></i>

                {{ session('success') }}

            </div>
        @endif


        {{-- ALERT ERROR --}}
        @if ($errors->any())

            <div class="alert alert-danger pendidikan-alert mb-3">

                <i class="bi bi-exclamation-circle me-1"></i>

                @foreach ($errors->all() as $error)
                    <div>
                        {{ $error }}
                    </div>
                @endforeach

            </div>

        @endif


        {{-- HEADER --}}
        <div class="pendidikan-header">

            <div class="pendidikan-header-left">

                <h1 class="pendidikan-header-title">
                    Manajemen Pendidikan
                </h1>

                <p class="pendidikan-header-description">
                    Kelola data pendidikan pada SAMPERIN.
                </p>

            </div>


            <div class="pendidikan-header-actions">

                <button type="button" class="pendidikan-add-button" data-bs-toggle="modal"
                    data-bs-target="#pendidikanCreateModal">

                    <i class="bi bi-plus-lg"></i>

                    Tambah Pendidikan

                </button>


                <a href="{{ route('master.pendidikan.import') }}" class="pendidikan-import-button">

                    <i class="bi bi-upload"></i>

                    Import

                </a>

            </div>

        </div>


        {{-- STAT --}}
        <div class="pendidikan-stat-grid">

            <div class="pendidikan-stat">

                <div class="pendidikan-stat-icon">
                    <i class="bi bi-mortarboard"></i>
                </div>

                <div>

                    <div class="pendidikan-stat-label">
                        Total Pendidikan
                    </div>

                    <div class="pendidikan-stat-number">
                        {{ $totalPendidikan }}
                    </div>

                </div>

            </div>


            <div class="pendidikan-stat">

                <div class="pendidikan-stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>

                    <div class="pendidikan-stat-label">
                        Pendidikan Aktif
                    </div>

                    <div class="pendidikan-stat-number">
                        {{ $pendidikanAktif }}
                    </div>

                </div>

            </div>


            <div class="pendidikan-stat">

                <div class="pendidikan-stat-icon">
                    <i class="bi bi-dash-circle"></i>
                </div>

                <div>

                    <div class="pendidikan-stat-label">
                        Pendidikan Nonaktif
                    </div>

                    <div class="pendidikan-stat-number">
                        {{ $pendidikanNonaktif }}
                    </div>

                </div>

            </div>

        </div>


        {{-- PANEL --}}
        <div class="pendidikan-panel">

            <div class="pendidikan-panel-header">

                <form method="GET" action="{{ route('master.pendidikan.index') }}" class="pendidikan-filter">

                    <div class="pendidikan-search">

                        <i class="bi bi-search"></i>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode, jenjang atau jurusan...">

                    </div>


                    <select name="status" class="pendidikan-filter-select" onchange="this.form.submit()">

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
            <div class="pendidikan-table-wrapper">

                <table class="pendidikan-table">

                    <thead>

                        <tr>

                            <th style="width:60px;">
                                ID
                            </th>

                            <th>
                                KODE
                            </th>

                            <th>
                                JENJANG
                            </th>

                            <th>
                                JURUSAN
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

                        @forelse($pendidikans as $pendidikan)
                            <tr>

                                <td>

                                    <span class="pendidikan-id">
                                        #{{ $pendidikan->pendidikan_id }}
                                    </span>

                                </td>


                                <td>

                                    <span class="pendidikan-code">
                                        {{ $pendidikan->pendidikan_kode }}
                                    </span>

                                </td>


                                <td>

                                    <div class="pendidikan-name">
                                        {{ $pendidikan->pendidikan_jenjang }}
                                    </div>

                                </td>


                                <td>

                                    <div class="pendidikan-jurusan">
                                        {{ $pendidikan->pendidikan_jurusan }}
                                    </div>

                                </td>


                                <td>

                                    @if ($pendidikan->pendidikan_status == 1)
                                        <span class="pendidikan-status active">

                                            <span class="pendidikan-status-dot"></span>

                                            Aktif

                                        </span>
                                    @else
                                        <span class="pendidikan-status inactive">

                                            <span class="pendidikan-status-dot"></span>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                <td>

                                    <div class="pendidikan-actions">

                                        {{-- EDIT --}}

                                        <button type="button" class="pendidikan-action-button" data-bs-toggle="modal"
                                            data-bs-target="#pendidikanEditModal{{ $pendidikan->pendidikan_id }}"
                                            title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        {{-- STATUS --}}

                                        <form method="POST"
                                            action="{{ route('master.pendidikan.status', $pendidikan->pendidikan_uid) }}">

                                            @csrf

                                            @method('PATCH')

                                            <button type="submit" class="pendidikan-action-button warning"
                                                title="{{ $pendidikan->pendidikan_status == 1 ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                onclick="return confirm('{{ $pendidikan->pendidikan_status == 1 ? 'Nonaktifkan pendidikan ini?' : 'Aktifkan pendidikan ini?' }}')">

                                                <i class="bi bi-pause-circle"></i>

                                            </button>

                                        </form>


                                        {{-- DELETE --}}

                                        <form method="POST"
                                            action="{{ route('master.pendidikan.destroy', $pendidikan->pendidikan_uid) }}">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit" class="pendidikan-action-button danger" title="Hapus"
                                                onclick="return confirm('Hapus pendidikan ini? Data yang masih digunakan pegawai tidak dapat dihapus.')">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            {{-- EDIT MODAL --}}

                            <div class="modal fade pendidikan-modal"
                                id="pendidikanEditModal{{ $pendidikan->pendidikan_id }}" tabindex="-1">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form method="POST"
                                            action="{{ route('master.pendidikan.update', $pendidikan->pendidikan_uid) }}">

                                            @csrf

                                            @method('PUT')


                                            <div class="modal-header">

                                                <div class="modal-title">
                                                    Edit Pendidikan
                                                </div>

                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="pendidikan-form-group">

                                                    <label class="pendidikan-form-label">
                                                        Kode Pendidikan
                                                    </label>

                                                    <input type="text" class="pendidikan-form-control"
                                                        value="{{ $pendidikan->pendidikan_kode }}" readonly>

                                                </div>


                                                <div class="pendidikan-form-group">

                                                    <label class="pendidikan-form-label">
                                                        Jenjang Pendidikan
                                                    </label>

                                                    <input type="text" name="pendidikan_jenjang"
                                                        class="pendidikan-form-control"
                                                        value="{{ $pendidikan->pendidikan_jenjang }}" required>

                                                </div>


                                                <div class="pendidikan-form-group">

                                                    <label class="pendidikan-form-label">
                                                        Jurusan
                                                    </label>

                                                    <input type="text" name="pendidikan_jurusan"
                                                        class="pendidikan-form-control"
                                                        value="{{ $pendidikan->pendidikan_jurusan }}" required>

                                                </div>


                                                <div class="pendidikan-form-group">

                                                    <label class="pendidikan-form-label">
                                                        Status
                                                    </label>

                                                    <div class="pendidikan-status-options">

                                                        <div class="pendidikan-status-option">

                                                            <input type="radio"
                                                                id="editActive{{ $pendidikan->pendidikan_id }}"
                                                                name="pendidikan_status" value="1"
                                                                {{ $pendidikan->pendidikan_status == 1 ? 'checked' : '' }}>

                                                            <label for="editActive{{ $pendidikan->pendidikan_id }}">
                                                                Aktif
                                                            </label>

                                                        </div>


                                                        <div class="pendidikan-status-option">

                                                            <input type="radio"
                                                                id="editInactive{{ $pendidikan->pendidikan_id }}"
                                                                name="pendidikan_status" value="0"
                                                                {{ $pendidikan->pendidikan_status == 0 ? 'checked' : '' }}>

                                                            <label for="editInactive{{ $pendidikan->pendidikan_id }}">
                                                                Nonaktif
                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button type="button" class="pendidikan-cancel-button"
                                                    data-bs-dismiss="modal">
                                                    Batal
                                                </button>

                                                <button type="submit" class="pendidikan-submit-button">
                                                    Simpan Perubahan
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <tr>

                                <td colspan="6">

                                    <div style="padding:55px 20px;text-align:center;">

                                        <div style="font-size:23px;color:#9aa2ad;margin-bottom:10px;">
                                            <i class="bi bi-mortarboard"></i>
                                        </div>

                                        <div style="color:#4b5565;font-size:14px;font-weight:700;">
                                            Data pendidikan belum tersedia
                                        </div>

                                        <div style="margin-top:4px;color:#9aa2ad;font-size:11px;">
                                            Belum ada data pendidikan yang dapat ditampilkan.
                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            @if ($pendidikans->hasPages())

                <div class="pendidikan-pagination">

                    <div class="pendidikan-pagination-info">

                        Menampilkan

                        <strong>
                            {{ $pendidikans->firstItem() }}
                        </strong>

                        -

                        <strong>
                            {{ $pendidikans->lastItem() }}
                        </strong>

                        dari

                        <strong>
                            {{ $pendidikans->total() }}
                        </strong>

                        data

                    </div>


                    <div class="pendidikan-pagination-nav">

                        @if ($pendidikans->onFirstPage())
                            <span class="disabled">
                                <i class="bi bi-chevron-left me-1"></i>
                                Sebelumnya
                            </span>
                        @else
                            <a href="{{ $pendidikans->previousPageUrl() }}">
                                <i class="bi bi-chevron-left me-1"></i>
                                Sebelumnya
                            </a>
                        @endif


                        @foreach ($pendidikans->getUrlRange(max(1, $pendidikans->currentPage() - 2), min($pendidikans->lastPage(), $pendidikans->currentPage() + 2)) as $page => $url)
                            @if ($page == $pendidikans->currentPage())
                                <span class="active">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach


                        @if ($pendidikans->hasMorePages())
                            <a href="{{ $pendidikans->nextPageUrl() }}">
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


    {{-- CREATE MODAL --}}

    <div class="modal fade pendidikan-modal" id="pendidikanCreateModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form method="POST" action="{{ route('master.pendidikan.store') }}">

                    @csrf


                    <div class="modal-header">

                        <div class="modal-title">
                            Tambah Pendidikan
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                    </div>


                    <div class="modal-body">

                        <div class="pendidikan-form-group">

                            <label class="pendidikan-form-label">
                                Jenjang Pendidikan
                            </label>

                            <input type="text" name="pendidikan_jenjang" class="pendidikan-form-control"
                                placeholder="Contoh: S1" required>

                        </div>


                        <div class="pendidikan-form-group">

                            <label class="pendidikan-form-label">
                                Jurusan
                            </label>

                            <input type="text" name="pendidikan_jurusan" class="pendidikan-form-control"
                                placeholder="Contoh: Teknik Informatika" required>

                        </div>


                        <div class="pendidikan-form-group">

                            <label class="pendidikan-form-label">
                                Status
                            </label>

                            <div class="pendidikan-status-options">

                                <div class="pendidikan-status-option">

                                    <input type="radio" id="createPendidikanActive" name="pendidikan_status"
                                        value="1" checked>

                                    <label for="createPendidikanActive">
                                        Aktif
                                    </label>

                                </div>


                                <div class="pendidikan-status-option">

                                    <input type="radio" id="createPendidikanInactive" name="pendidikan_status"
                                        value="0">

                                    <label for="createPendidikanInactive">
                                        Nonaktif
                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="pendidikan-cancel-button" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="pendidikan-submit-button">
                            Simpan Pendidikan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
