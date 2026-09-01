@extends('dashboard.layouts.app')

@section('title', 'Manajemen Jenis Kerja')

@section('header-title', 'Manajemen Jenis Kerja')

@section('breadcrumb', 'Manajemen Jenis Kerja')

@section('page-style')

    <style>
        .jenis-kerja-page {
            width: 100%;
        }

        .jenis-kerja-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .jenis-kerja-header-left {
            min-width: 0;
        }

        .jenis-kerja-header-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
        }

        .jenis-kerja-header-description {
            margin: 6px 0 0;
            color: #8993a3;
            font-size: 13px;
        }

        .jenis-kerja-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .jenis-kerja-add-button {
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

        .jenis-kerja-add-button:hover {
            transform: translateY(-1px);
            color: #fff;
        }

        .jenis-kerja-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .jenis-kerja-stat {
            background: #fff;
            border: 1px solid #e7eaf0;
            border-radius: 14px;
            padding: 17px;
            display: flex;
            align-items: center;
            gap: 13px;
            box-shadow: 0 5px 18px rgba(20, 35, 60, .035);
        }

        .jenis-kerja-stat-icon {
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

        .jenis-kerja-stat-label {
            color: #929ba9;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .jenis-kerja-stat-number {
            color: #273449;
            font-size: 21px;
            font-weight: 800;
        }

        .jenis-kerja-panel {
            background: #fff;
            border: 1px solid #e6eaf0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(20, 35, 60, .04);
        }

        .jenis-kerja-panel-header {
            padding: 17px 19px;
            border-bottom: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .jenis-kerja-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            max-width: 560px;
        }

        .jenis-kerja-search {
            position: relative;
            flex: 1;
        }

        .jenis-kerja-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa3b0;
            font-size: 15px;
        }

        .jenis-kerja-search input {
            width: 100%;
            height: 40px;
            padding: 0 12px 0 35px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            outline: none;
            color: #344054;
            font-size: 12px;
        }

        .jenis-kerja-search input:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .jenis-kerja-filter-select {
            height: 40px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 0 11px;
            color: #4b5565;
            background: #fff;
            font-size: 12px;
            outline: none;
        }

        .jenis-kerja-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .jenis-kerja-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }

        .jenis-kerja-table th {
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

        .jenis-kerja-table td {
            padding: 15px 18px;
            border-bottom: 1px solid #f0f2f5;
            color: #475467;
            font-size: 13px;
            vertical-align: middle;
        }

        .jenis-kerja-table tr:last-child td {
            border-bottom: 0;
        }

        .jenis-kerja-table tbody tr:hover {
            background: #fcfcfd;
        }

        .jenis-kerja-id {
            color: #a0a8b4;
            font-size: 10px;
            font-family: monospace;
        }

        .jenis-kerja-code {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 7px;
            background: #f3f5f8;
            color: #5d6878;
            font-size: 10px;
            font-weight: 700;
            font-family: monospace;
        }

        .jenis-kerja-name {
            color: #273449;
            font-size: 14px;
            font-weight: 750;
        }

        .jenis-kerja-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .jenis-kerja-status.active {
            color: #247a4b;
            background: #eaf8f0;
        }

        .jenis-kerja-status.inactive {
            color: #7b8491;
            background: #f0f2f5;
        }

        .jenis-kerja-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .jenis-kerja-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .jenis-kerja-action-button {
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

        .jenis-kerja-action-button:hover {
            border-color: #cdd3db;
            background: #f8f9fb;
            color: #273449;
        }

        .jenis-kerja-action-button.warning:hover {
            background: #fff8e8;
            color: #b87800;
            border-color: #efd99d;
        }

        .jenis-kerja-action-button.danger:hover {
            background: #fff1f1;
            color: #d33;
            border-color: #efcaca;
        }

        .jenis-kerja-empty {
            padding: 55px 20px;
            text-align: center;
        }

        .jenis-kerja-empty-icon {
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

        .jenis-kerja-empty-title {
            color: #4b5565;
            font-size: 14px;
            font-weight: 700;
        }

        .jenis-kerja-empty-text {
            margin-top: 4px;
            color: #9aa2ad;
            font-size: 11px;
        }

        .jenis-kerja-pagination {
            padding: 15px 18px;
            border-top: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .jenis-kerja-pagination-info {
            color: #8993a3;
            font-size: 11px;
            white-space: nowrap;
        }

        .jenis-kerja-pagination-nav {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .jenis-kerja-pagination-nav a,
        .jenis-kerja-pagination-nav span {
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

        .jenis-kerja-pagination-nav a:hover {
            background: #fff8f2;
            border-color: #df8339;
            color: #c86520;
        }

        .jenis-kerja-pagination-nav .active {
            border-color: #df8339;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-weight: 700;
        }

        .jenis-kerja-pagination-nav .disabled {
            color: #c2c8d0;
            background: #f8f9fb;
        }

        .jenis-kerja-modal .modal-content {
            border: 0;
            border-radius: 17px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, .20);
        }

        .jenis-kerja-modal .modal-header {
            padding: 18px 21px;
            border: 0;
            background: linear-gradient(135deg, #14223b, #1d3558);
            color: #fff;
        }

        .jenis-kerja-modal .modal-title {
            font-size: 16px;
            font-weight: 800;
        }

        .jenis-kerja-modal .btn-close {
            filter: brightness(0) invert(1);
            opacity: .7;
        }

        .jenis-kerja-modal .modal-body {
            padding: 21px;
        }

        .jenis-kerja-modal .modal-footer {
            padding: 14px 21px;
            border-top: 1px solid #edf0f4;
        }

        .jenis-kerja-form-label {
            display: block;
            margin-bottom: 7px;
            color: #475467;
            font-size: 11px;
            font-weight: 700;
        }

        .jenis-kerja-form-control {
            width: 100%;
            height: 42px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 0 12px;
            color: #344054;
            font-size: 12px;
            outline: none;
        }

        .jenis-kerja-form-control:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .jenis-kerja-form-group {
            margin-bottom: 16px;
        }

        .jenis-kerja-status-options {
            display: flex;
            gap: 8px;
        }

        .jenis-kerja-status-option {
            flex: 1;
            position: relative;
        }

        .jenis-kerja-status-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .jenis-kerja-status-option label {
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

        .jenis-kerja-status-option input:checked+label {
            border-color: #df8339;
            background: #fff8f2;
            color: #c86520;
        }

        .jenis-kerja-submit-button {
            border: 0;
            border-radius: 8px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        .jenis-kerja-cancel-button {
            border: 1px solid #dfe4eb;
            border-radius: 8px;
            padding: 10px 16px;
            background: #fff;
            color: #667085;
            font-size: 12px;
            font-weight: 600;
        }

        .jenis-kerja-alert {
            border: 0;
            border-radius: 10px;
            font-size: 12px;
        }

        @media(max-width: 800px) {

            .jenis-kerja-stat-grid {
                grid-template-columns: 1fr;
            }

            .jenis-kerja-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .jenis-kerja-header-actions {
                width: 100%;
            }

            .jenis-kerja-add-button {
                justify-content: center;
                flex: 1;
            }

            .jenis-kerja-panel-header {
                align-items: stretch;
                flex-direction: column;
            }

            .jenis-kerja-filter {
                max-width: none;
            }

            .jenis-kerja-pagination {
                align-items: flex-start;
                flex-direction: column;
            }

            .jenis-kerja-pagination-nav {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media(max-width: 550px) {

            .jenis-kerja-filter {
                flex-direction: column;
            }

            .jenis-kerja-search,
            .jenis-kerja-filter-select {
                width: 100%;
            }

            .jenis-kerja-header-actions {
                width: 100%;
            }

            .jenis-kerja-add-button {
                width: 100%;
            }
        }
    </style>

@endsection


@section('content')

    <div class="jenis-kerja-page">

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success jenis-kerja-alert mb-3">

                <i class="bi bi-check-circle me-1"></i>

                {{ session('success') }}

            </div>
        @endif


        {{-- ALERT ERROR --}}
        @if ($errors->any())

            <div class="alert alert-danger jenis-kerja-alert mb-3">

                <i class="bi bi-exclamation-circle me-1"></i>

                @foreach ($errors->all() as $error)
                    <div>
                        {{ $error }}
                    </div>
                @endforeach

            </div>

        @endif


        {{-- HEADER --}}
        <div class="jenis-kerja-header">

            <div class="jenis-kerja-header-left">

                <h1 class="jenis-kerja-header-title">
                    Manajemen Jenis Kerja
                </h1>

                <p class="jenis-kerja-header-description">
                    Kelola data jenis kerja pada SAMPERIN.
                </p>

            </div>


            <div class="jenis-kerja-header-actions">

                <button type="button" class="jenis-kerja-add-button" data-bs-toggle="modal"
                    data-bs-target="#jenisKerjaCreateModal">

                    <i class="bi bi-plus-lg"></i>

                    Tambah Jenis Kerja

                </button>

            </div>

        </div>


        {{-- STATISTIK --}}
        <div class="jenis-kerja-stat-grid">

            <div class="jenis-kerja-stat">

                <div class="jenis-kerja-stat-icon">

                    <i class="bi bi-person-workspace"></i>

                </div>

                <div>

                    <div class="jenis-kerja-stat-label">
                        Total Jenis Kerja
                    </div>

                    <div class="jenis-kerja-stat-number">
                        {{ $totalJenisKerja }}
                    </div>

                </div>

            </div>


            <div class="jenis-kerja-stat">

                <div class="jenis-kerja-stat-icon">

                    <i class="bi bi-check-circle"></i>

                </div>

                <div>

                    <div class="jenis-kerja-stat-label">
                        Jenis Kerja Aktif
                    </div>

                    <div class="jenis-kerja-stat-number">
                        {{ $jenisKerjaAktif }}
                    </div>

                </div>

            </div>


            <div class="jenis-kerja-stat">

                <div class="jenis-kerja-stat-icon">

                    <i class="bi bi-dash-circle"></i>

                </div>

                <div>

                    <div class="jenis-kerja-stat-label">
                        Jenis Kerja Nonaktif
                    </div>

                    <div class="jenis-kerja-stat-number">
                        {{ $jenisKerjaNonaktif }}
                    </div>

                </div>

            </div>

        </div>


        {{-- PANEL --}}
        <div class="jenis-kerja-panel">

            {{-- FILTER --}}
            <div class="jenis-kerja-panel-header">

                <form method="GET" action="{{ route('master.jenis-kerja.index') }}" class="jenis-kerja-filter">

                    <div class="jenis-kerja-search">

                        <i class="bi bi-search"></i>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode atau nama jenis kerja...">

                    </div>


                    <select name="status" class="jenis-kerja-filter-select" onchange="this.form.submit()">

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
            <div class="jenis-kerja-table-wrapper">

                <table class="jenis-kerja-table">

                    <thead>

                        <tr>

                            <th style="width:60px;">
                                ID
                            </th>

                            <th>
                                KODE
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

                        @forelse($jenisKerjas as $jenisKerja)
                            <tr>

                                <td>

                                    <span class="jenis-kerja-id">
                                        #{{ $jenisKerja->jenis_kerja_id }}
                                    </span>

                                </td>


                                <td>

                                    <span class="jenis-kerja-code">
                                        {{ $jenisKerja->jenis_kerja_kode }}
                                    </span>

                                </td>


                                <td>

                                    <div class="jenis-kerja-name">
                                        {{ $jenisKerja->jenis_kerja_nama }}
                                    </div>

                                </td>


                                <td>

                                    @if ($jenisKerja->jenis_kerja_status == 1)
                                        <span class="jenis-kerja-status active">

                                            <span class="jenis-kerja-status-dot"></span>

                                            Aktif

                                        </span>
                                    @else
                                        <span class="jenis-kerja-status inactive">

                                            <span class="jenis-kerja-status-dot"></span>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                <td>

                                    <div class="jenis-kerja-actions">

                                        {{-- EDIT --}}
                                        <button type="button" class="jenis-kerja-action-button" data-bs-toggle="modal"
                                            data-bs-target="#jenisKerjaEditModal{{ $jenisKerja->jenis_kerja_id }}"
                                            title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        {{-- STATUS --}}
                                        <form method="POST"
                                            action="{{ route('master.jenis-kerja.status', $jenisKerja->jenis_kerja_uid) }}">

                                            @csrf

                                            @method('PATCH')

                                            <button type="submit" class="jenis-kerja-action-button warning"
                                                title="{{ $jenisKerja->jenis_kerja_status == 1 ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                onclick="return confirm('{{ $jenisKerja->jenis_kerja_status == 1 ? 'Nonaktifkan jenis kerja ini?' : 'Aktifkan jenis kerja ini?' }}')">

                                                <i class="bi bi-pause-circle"></i>

                                            </button>

                                        </form>


                                        {{-- DELETE --}}
                                        <form method="POST"
                                            action="{{ route('master.jenis-kerja.destroy', $jenisKerja->jenis_kerja_uid) }}">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit" class="jenis-kerja-action-button danger" title="Hapus"
                                                onclick="return confirm('Hapus jenis kerja ini? Data yang masih digunakan pegawai tidak dapat dihapus.')">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            {{-- EDIT MODAL --}}
                            <div class="modal fade jenis-kerja-modal"
                                id="jenisKerjaEditModal{{ $jenisKerja->jenis_kerja_id }}" tabindex="-1">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form method="POST"
                                            action="{{ route('master.jenis-kerja.update', $jenisKerja->jenis_kerja_uid) }}">

                                            @csrf

                                            @method('PUT')


                                            <div class="modal-header">

                                                <div class="modal-title">
                                                    Edit Jenis Kerja
                                                </div>

                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="jenis-kerja-form-group">

                                                    <label class="jenis-kerja-form-label">
                                                        Kode Jenis Kerja
                                                    </label>

                                                    <input type="text" name="jenis_kerja_kode"
                                                        class="jenis-kerja-form-control"
                                                        value="{{ $jenisKerja->jenis_kerja_kode }}" maxlength="20"
                                                        required>

                                                </div>


                                                <div class="jenis-kerja-form-group">

                                                    <label class="jenis-kerja-form-label">
                                                        Nama Jenis Kerja
                                                    </label>

                                                    <input type="text" name="jenis_kerja_nama"
                                                        class="jenis-kerja-form-control"
                                                        value="{{ $jenisKerja->jenis_kerja_nama }}" maxlength="100"
                                                        required>

                                                </div>


                                                <div class="jenis-kerja-form-group">

                                                    <label class="jenis-kerja-form-label">
                                                        Status
                                                    </label>

                                                    <div class="jenis-kerja-status-options">

                                                        <div class="jenis-kerja-status-option">

                                                            <input type="radio"
                                                                id="editActive{{ $jenisKerja->jenis_kerja_id }}"
                                                                name="jenis_kerja_status" value="1"
                                                                {{ $jenisKerja->jenis_kerja_status == 1 ? 'checked' : '' }}>

                                                            <label for="editActive{{ $jenisKerja->jenis_kerja_id }}">
                                                                Aktif
                                                            </label>

                                                        </div>


                                                        <div class="jenis-kerja-status-option">

                                                            <input type="radio"
                                                                id="editInactive{{ $jenisKerja->jenis_kerja_id }}"
                                                                name="jenis_kerja_status" value="0"
                                                                {{ $jenisKerja->jenis_kerja_status == 0 ? 'checked' : '' }}>

                                                            <label for="editInactive{{ $jenisKerja->jenis_kerja_id }}">
                                                                Nonaktif
                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button type="button" class="jenis-kerja-cancel-button"
                                                    data-bs-dismiss="modal">

                                                    Batal

                                                </button>


                                                <button type="submit" class="jenis-kerja-submit-button">

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

                                    <div class="jenis-kerja-empty">

                                        <div class="jenis-kerja-empty-icon">

                                            <i class="bi bi-person-workspace"></i>

                                        </div>

                                        <div class="jenis-kerja-empty-title">

                                            Data jenis kerja belum tersedia

                                        </div>

                                        <div class="jenis-kerja-empty-text">

                                            Belum ada data jenis kerja yang dapat ditampilkan.

                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            @if ($jenisKerjas->hasPages())

                <div class="jenis-kerja-pagination">

                    <div class="jenis-kerja-pagination-info">

                        Menampilkan

                        <strong>
                            {{ $jenisKerjas->firstItem() }}
                        </strong>

                        -

                        <strong>
                            {{ $jenisKerjas->lastItem() }}
                        </strong>

                        dari

                        <strong>
                            {{ $jenisKerjas->total() }}
                        </strong>

                        data

                    </div>


                    <div class="jenis-kerja-pagination-nav">

                        @if ($jenisKerjas->onFirstPage())
                            <span class="disabled">

                                <i class="bi bi-chevron-left me-1"></i>

                                Sebelumnya

                            </span>
                        @else
                            <a href="{{ $jenisKerjas->previousPageUrl() }}">

                                <i class="bi bi-chevron-left me-1"></i>

                                Sebelumnya

                            </a>
                        @endif


                        @foreach ($jenisKerjas->getUrlRange(max(1, $jenisKerjas->currentPage() - 2), min($jenisKerjas->lastPage(), $jenisKerjas->currentPage() + 2)) as $page => $url)
                            @if ($page == $jenisKerjas->currentPage())
                                <span class="active">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach


                        @if ($jenisKerjas->hasMorePages())
                            <a href="{{ $jenisKerjas->nextPageUrl() }}">

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
    <div class="modal fade jenis-kerja-modal" id="jenisKerjaCreateModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form method="POST" action="{{ route('master.jenis-kerja.store') }}">

                    @csrf


                    <div class="modal-header">

                        <div class="modal-title">
                            Tambah Jenis Kerja
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="jenis-kerja-form-group">

                            <label class="jenis-kerja-form-label">
                                Kode Jenis Kerja
                            </label>

                            <input type="text" name="jenis_kerja_kode" class="jenis-kerja-form-control"
                                placeholder="Contoh: PNS" maxlength="20" required>

                        </div>


                        <div class="jenis-kerja-form-group">

                            <label class="jenis-kerja-form-label">
                                Nama Jenis Kerja
                            </label>

                            <input type="text" name="jenis_kerja_nama" class="jenis-kerja-form-control"
                                placeholder="Contoh: Pegawai Negeri Sipil" maxlength="100" required>

                        </div>


                        <div class="jenis-kerja-form-group">

                            <label class="jenis-kerja-form-label">
                                Status
                            </label>


                            <div class="jenis-kerja-status-options">

                                <div class="jenis-kerja-status-option">

                                    <input type="radio" id="createActive" name="jenis_kerja_status" value="1"
                                        checked>

                                    <label for="createActive">
                                        Aktif
                                    </label>

                                </div>


                                <div class="jenis-kerja-status-option">

                                    <input type="radio" id="createInactive" name="jenis_kerja_status" value="0">

                                    <label for="createInactive">
                                        Nonaktif
                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="jenis-kerja-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>


                        <button type="submit" class="jenis-kerja-submit-button">

                            Simpan Jenis Kerja

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
