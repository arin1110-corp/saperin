@extends('dashboard.layouts.app')

@section('title', 'Manajemen Jabatan')

@section('header-title', 'Manajemen Jabatan')

@section('breadcrumb', 'Manajemen Jabatan')

@section('page-style')

    <style>
        .jabatan-page {
            width: 100%;
        }

        .jabatan-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .jabatan-header-left {
            min-width: 0;
        }

        .jabatan-header-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
        }

        .jabatan-header-description {
            margin: 6px 0 0;
            color: #8993a3;
            font-size: 13px;
        }

        .jabatan-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .jabatan-add-button {
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
            transition: .15s ease;
        }

        .jabatan-add-button:hover {
            transform: translateY(-1px);
            color: #fff;
        }

        .jabatan-import-button {
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
            transition: .15s ease;
        }

        .jabatan-import-button:hover {
            background: #f8f9fb;
            color: #273449;
            border-color: #cdd3db;
        }

        .jabatan-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .jabatan-stat {
            background: #fff;
            border: 1px solid #e7eaf0;
            border-radius: 14px;
            padding: 17px;
            display: flex;
            align-items: center;
            gap: 13px;
            box-shadow: 0 5px 18px rgba(20, 35, 60, .035);
        }

        .jabatan-stat-icon {
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

        .jabatan-stat-label {
            color: #929ba9;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .jabatan-stat-number {
            color: #273449;
            font-size: 21px;
            font-weight: 800;
        }

        .jabatan-panel {
            background: #fff;
            border: 1px solid #e6eaf0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(20, 35, 60, .04);
        }

        .jabatan-panel-header {
            padding: 17px 19px;
            border-bottom: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .jabatan-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            max-width: 560px;
        }

        .jabatan-search {
            position: relative;
            flex: 1;
        }

        .jabatan-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa3b0;
            font-size: 15px;
        }

        .jabatan-search input {
            width: 100%;
            height: 40px;
            padding: 0 12px 0 35px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            outline: none;
            color: #344054;
            font-size: 12px;
        }

        .jabatan-search input:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .jabatan-filter-select {
            height: 40px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 0 11px;
            color: #4b5565;
            background: #fff;
            font-size: 12px;
            outline: none;
        }

        .jabatan-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .jabatan-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }

        .jabatan-table th {
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

        .jabatan-table td {
            padding: 15px 18px;
            border-bottom: 1px solid #f0f2f5;
            color: #475467;
            font-size: 13px;
            vertical-align: middle;
        }

        .jabatan-table tr:last-child td {
            border-bottom: 0;
        }

        .jabatan-table tbody tr:hover {
            background: #fcfcfd;
        }

        .jabatan-id {
            color: #a0a8b4;
            font-size: 10px;
            font-family: monospace;
        }

        .jabatan-name {
            color: #273449;
            font-size: 14px;
            font-weight: 750;
        }

        .jabatan-category {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 7px;
            background: #f3f5f8;
            color: #6d7683;
            font-size: 10px;
            font-weight: 600;
        }

        .jabatan-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .jabatan-status.active {
            color: #247a4b;
            background: #eaf8f0;
        }

        .jabatan-status.inactive {
            color: #7b8491;
            background: #f0f2f5;
        }

        .jabatan-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .jabatan-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .jabatan-action-button {
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

        .jabatan-action-button:hover {
            border-color: #cdd3db;
            background: #f8f9fb;
            color: #273449;
        }

        .jabatan-action-button.warning:hover {
            background: #fff8e8;
            color: #b87800;
            border-color: #efd99d;
        }

        .jabatan-action-button.danger:hover {
            background: #fff1f1;
            color: #d33;
            border-color: #efcaca;
        }

        .jabatan-empty {
            padding: 55px 20px;
            text-align: center;
        }

        .jabatan-empty-icon {
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

        .jabatan-empty-title {
            color: #4b5565;
            font-size: 14px;
            font-weight: 700;
        }

        .jabatan-empty-text {
            margin-top: 4px;
            color: #9aa2ad;
            font-size: 11px;
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        .jabatan-pagination {
            padding: 15px 18px;
            border-top: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .jabatan-pagination-info {
            color: #8993a3;
            font-size: 11px;
            white-space: nowrap;
        }

        .jabatan-pagination-nav {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .jabatan-pagination-nav a,
        .jabatan-pagination-nav span {
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
            transition: .15s ease;
        }

        .jabatan-pagination-nav a:hover {
            background: #fff8f2;
            border-color: #df8339;
            color: #c86520;
        }

        .jabatan-pagination-nav .active {
            border-color: #df8339;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-weight: 700;
        }

        .jabatan-pagination-nav .disabled {
            color: #c2c8d0;
            background: #f8f9fb;
        }

        .jabatan-modal .modal-content {
            border: 0;
            border-radius: 17px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, .20);
        }

        .jabatan-modal .modal-header {
            padding: 18px 21px;
            border: 0;
            background: linear-gradient(135deg, #14223b, #1d3558);
            color: #fff;
        }

        .jabatan-modal .modal-title {
            font-size: 16px;
            font-weight: 800;
        }

        .jabatan-modal .btn-close {
            filter: brightness(0) invert(1);
            opacity: .7;
        }

        .jabatan-modal .modal-body {
            padding: 21px;
        }

        .jabatan-modal .modal-footer {
            padding: 14px 21px;
            border-top: 1px solid #edf0f4;
        }

        .jabatan-form-label {
            display: block;
            margin-bottom: 7px;
            color: #475467;
            font-size: 11px;
            font-weight: 700;
        }

        .jabatan-form-control {
            width: 100%;
            height: 42px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 0 12px;
            color: #344054;
            font-size: 12px;
            outline: none;
        }

        .jabatan-form-control:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .jabatan-form-group {
            margin-bottom: 16px;
        }

        .jabatan-status-options {
            display: flex;
            gap: 8px;
        }

        .jabatan-status-option {
            flex: 1;
            position: relative;
        }

        .jabatan-status-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .jabatan-status-option label {
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

        .jabatan-status-option input:checked+label {
            border-color: #df8339;
            background: #fff8f2;
            color: #c86520;
        }

        .jabatan-submit-button {
            border: 0;
            border-radius: 8px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        .jabatan-cancel-button {
            border: 1px solid #dfe4eb;
            border-radius: 8px;
            padding: 10px 16px;
            background: #fff;
            color: #667085;
            font-size: 12px;
            font-weight: 600;
        }

        .jabatan-alert {
            border: 0;
            border-radius: 10px;
            font-size: 12px;
        }

        @media(max-width: 800px) {

            .jabatan-stat-grid {
                grid-template-columns: 1fr;
            }

            .jabatan-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .jabatan-header-actions {
                width: 100%;
            }

            .jabatan-add-button,
            .jabatan-import-button {
                justify-content: center;
                flex: 1;
            }

            .jabatan-panel-header {
                align-items: stretch;
                flex-direction: column;
            }

            .jabatan-filter {
                max-width: none;
            }

            .jabatan-pagination {
                align-items: flex-start;
                flex-direction: column;
            }

            .jabatan-pagination-nav {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media(max-width: 550px) {

            .jabatan-filter {
                flex-direction: column;
            }

            .jabatan-search,
            .jabatan-filter-select {
                width: 100%;
            }

            .jabatan-header-actions {
                flex-direction: column;
            }

            .jabatan-add-button,
            .jabatan-import-button {
                width: 100%;
            }
        }
    </style>

@endsection


@section('content')

    <div class="jabatan-page">

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success jabatan-alert mb-3">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- ALERT ERROR --}}
        @if ($errors->any())
            <div class="alert alert-danger jabatan-alert mb-3">
                <i class="bi bi-exclamation-circle me-1"></i>

                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- HEADER --}}
        <div class="jabatan-header">

            <div class="jabatan-header-left">

                <h1 class="jabatan-header-title">
                    Manajemen Jabatan
                </h1>

                <p class="jabatan-header-description">
                    Kelola data jabatan pada SAMPERIN.
                </p>

            </div>

            <div class="jabatan-header-actions">

                <button type="button" class="jabatan-add-button" data-bs-toggle="modal"
                    data-bs-target="#jabatanCreateModal">

                    <i class="bi bi-plus-lg"></i>

                    Tambah Jabatan

                </button>

                <a href="{{ route('master.jabatan.import') }}" class="jabatan-import-button">

                    <i class="bi bi-upload"></i>

                    Import

                </a>

            </div>

        </div>


        {{-- STAT --}}
        <div class="jabatan-stat-grid">

            <div class="jabatan-stat">

                <div class="jabatan-stat-icon">
                    <i class="bi bi-briefcase"></i>
                </div>

                <div>

                    <div class="jabatan-stat-label">
                        Total Jabatan
                    </div>

                    <div class="jabatan-stat-number">
                        {{ $totalJabatan }}
                    </div>

                </div>

            </div>


            <div class="jabatan-stat">

                <div class="jabatan-stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>

                    <div class="jabatan-stat-label">
                        Jabatan Aktif
                    </div>

                    <div class="jabatan-stat-number">
                        {{ $jabatanAktif }}
                    </div>

                </div>

            </div>


            <div class="jabatan-stat">

                <div class="jabatan-stat-icon">
                    <i class="bi bi-dash-circle"></i>
                </div>

                <div>

                    <div class="jabatan-stat-label">
                        Jabatan Nonaktif
                    </div>

                    <div class="jabatan-stat-number">
                        {{ $jabatanNonaktif }}
                    </div>

                </div>

            </div>

        </div>


        {{-- PANEL --}}
        <div class="jabatan-panel">

            {{-- FILTER --}}
            <div class="jabatan-panel-header">

                <form method="GET" action="{{ route('master.jabatan.index') }}" class="jabatan-filter">

                    <div class="jabatan-search">

                        <i class="bi bi-search"></i>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau kategori jabatan...">

                    </div>

                    <select name="status" class="jabatan-filter-select" onchange="this.form.submit()">

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
            <div class="jabatan-table-wrapper">

                <table class="jabatan-table">

                    <thead>

                        <tr>

                            <th style="width: 60px;">
                                ID
                            </th>

                            <th>
                                JABATAN
                            </th>

                            <th>
                                KATEGORI
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

                        @forelse($jabatans as $jabatan)
                            <tr>

                                <td>
                                    <span class="jabatan-id">
                                        #{{ $jabatan->jabatan_id }}
                                    </span>
                                </td>

                                <td>

                                    <div class="jabatan-name">
                                        {{ $jabatan->jabatan_nama }}
                                    </div>

                                </td>

                                <td>

                                    <span class="jabatan-category">
                                        {{ $jabatan->jabatan_kategori ?: '-' }}
                                    </span>

                                </td>

                                <td>

                                    @if ($jabatan->jabatan_status == 1)
                                        <span class="jabatan-status active">

                                            <span class="jabatan-status-dot"></span>

                                            Aktif

                                        </span>
                                    @else
                                        <span class="jabatan-status inactive">

                                            <span class="jabatan-status-dot"></span>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <div class="jabatan-actions">

                                        {{-- EDIT --}}
                                        <button type="button" class="jabatan-action-button" data-bs-toggle="modal"
                                            data-bs-target="#jabatanEditModal{{ $jabatan->jabatan_id }}" title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        {{-- STATUS --}}
                                        <form method="POST"
                                            action="{{ route('master.jabatan.status', $jabatan->jabatan_id) }}">

                                            @csrf

                                            @method('PATCH')

                                            <button type="submit" class="jabatan-action-button warning"
                                                title="{{ $jabatan->jabatan_status == 1 ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                onclick="return confirm('{{ $jabatan->jabatan_status == 1 ? 'Nonaktifkan jabatan ini?' : 'Aktifkan jabatan ini?' }}')">

                                                <i class="bi bi-pause-circle"></i>

                                            </button>

                                        </form>


                                        {{-- DELETE --}}
                                        <form method="POST"
                                            action="{{ route('master.jabatan.destroy', $jabatan->jabatan_id) }}">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit" class="jabatan-action-button danger" title="Hapus"
                                                onclick="return confirm('Hapus jabatan ini? Data yang masih digunakan pegawai tidak dapat dihapus.')">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            {{-- EDIT MODAL --}}
                            <div class="modal fade jabatan-modal" id="jabatanEditModal{{ $jabatan->jabatan_id }}"
                                tabindex="-1">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form method="POST"
                                            action="{{ route('master.jabatan.update', $jabatan->jabatan_id) }}">

                                            @csrf

                                            @method('PUT')

                                            <div class="modal-header">

                                                <div>

                                                    <div class="modal-title">
                                                        Edit Jabatan
                                                    </div>

                                                </div>

                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="jabatan-form-group">

                                                    <label class="jabatan-form-label">
                                                        Nama Jabatan
                                                    </label>

                                                    <input type="text" name="jabatan_nama" class="jabatan-form-control"
                                                        value="{{ $jabatan->jabatan_nama }}" required>

                                                </div>


                                                <div class="jabatan-form-group">

                                                    <label class="jabatan-form-label">
                                                        Kategori Jabatan
                                                    </label>

                                                    <input type="text" name="jabatan_kategori"
                                                        class="jabatan-form-control"
                                                        value="{{ $jabatan->jabatan_kategori }}" required>

                                                </div>


                                                <div class="jabatan-form-group">

                                                    <label class="jabatan-form-label">
                                                        Status
                                                    </label>

                                                    <div class="jabatan-status-options">

                                                        <div class="jabatan-status-option">

                                                            <input type="radio"
                                                                id="editActive{{ $jabatan->jabatan_id }}"
                                                                name="jabatan_status" value="1"
                                                                {{ $jabatan->jabatan_status == 1 ? 'checked' : '' }}>

                                                            <label for="editActive{{ $jabatan->jabatan_id }}">
                                                                Aktif
                                                            </label>

                                                        </div>

                                                        <div class="jabatan-status-option">

                                                            <input type="radio"
                                                                id="editInactive{{ $jabatan->jabatan_id }}"
                                                                name="jabatan_status" value="0"
                                                                {{ $jabatan->jabatan_status == 0 ? 'checked' : '' }}>

                                                            <label for="editInactive{{ $jabatan->jabatan_id }}">
                                                                Nonaktif
                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button type="button" class="jabatan-cancel-button"
                                                    data-bs-dismiss="modal">

                                                    Batal

                                                </button>

                                                <button type="submit" class="jabatan-submit-button">

                                                    Simpan Perubahan

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <tr>

                                <td colspan="5">

                                    <div class="jabatan-empty">

                                        <div class="jabatan-empty-icon">
                                            <i class="bi bi-briefcase"></i>
                                        </div>

                                        <div class="jabatan-empty-title">
                                            Data jabatan belum tersedia
                                        </div>

                                        <div class="jabatan-empty-text">
                                            Belum ada data jabatan yang dapat ditampilkan.
                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            @if ($jabatans->hasPages())

                <div class="jabatan-pagination">

                    <div class="jabatan-pagination-info">

                        Menampilkan
                        <strong>
                            {{ $jabatans->firstItem() }}
                        </strong>
                        -
                        <strong>
                            {{ $jabatans->lastItem() }}
                        </strong>
                        dari
                        <strong>
                            {{ $jabatans->total() }}
                        </strong>
                        data

                    </div>


                    <div class="jabatan-pagination-nav">

                        @if ($jabatans->onFirstPage())
                            <span class="disabled">
                                <i class="bi bi-chevron-left me-1"></i>
                                Sebelumnya
                            </span>
                        @else
                            <a href="{{ $jabatans->previousPageUrl() }}">
                                <i class="bi bi-chevron-left me-1"></i>
                                Sebelumnya
                            </a>
                        @endif


                        @foreach ($jabatans->getUrlRange(max(1, $jabatans->currentPage() - 2), min($jabatans->lastPage(), $jabatans->currentPage() + 2)) as $page => $url)
                            @if ($page == $jabatans->currentPage())
                                <span class="active">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach


                        @if ($jabatans->hasMorePages())
                            <a href="{{ $jabatans->nextPageUrl() }}">
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
    <div class="modal fade jabatan-modal" id="jabatanCreateModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form method="POST" action="{{ route('master.jabatan.store') }}">

                    @csrf

                    <div class="modal-header">

                        <div>

                            <div class="modal-title">
                                Tambah Jabatan
                            </div>

                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="jabatan-form-group">

                            <label class="jabatan-form-label">
                                Nama Jabatan
                            </label>

                            <input type="text" name="jabatan_nama" class="jabatan-form-control"
                                placeholder="Contoh: Analis Budaya" required>

                        </div>


                        <div class="jabatan-form-group">

                            <label class="jabatan-form-label">
                                Kategori Jabatan
                            </label>

                            <input type="text" name="jabatan_kategori" class="jabatan-form-control"
                                placeholder="Contoh: Struktural" required>

                        </div>


                        <div class="jabatan-form-group">

                            <label class="jabatan-form-label">
                                Status
                            </label>

                            <div class="jabatan-status-options">

                                <div class="jabatan-status-option">

                                    <input type="radio" id="createActive" name="jabatan_status" value="1"
                                        checked>

                                    <label for="createActive">
                                        Aktif
                                    </label>

                                </div>

                                <div class="jabatan-status-option">

                                    <input type="radio" id="createInactive" name="jabatan_status" value="0">

                                    <label for="createInactive">
                                        Nonaktif
                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="jabatan-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="jabatan-submit-button">

                            Simpan Jabatan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
