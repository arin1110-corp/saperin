@extends('dashboard.layouts.app')

@section('title', 'Manajemen Golongan')

@section('header-title', 'Manajemen Golongan')

@section('breadcrumb', 'Manajemen Golongan')

@section('page-style')

    <style>
        .golongan-page {
            width: 100%;
        }

        .golongan-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .golongan-header-left {
            min-width: 0;
        }

        .golongan-header-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
        }

        .golongan-header-description {
            margin: 6px 0 0;
            color: #8993a3;
            font-size: 13px;
        }

        .golongan-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .golongan-add-button {
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

        .golongan-add-button:hover {
            transform: translateY(-1px);
            color: #fff;
        }

        .golongan-import-button {
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

        .golongan-import-button:hover {
            background: #f8f9fb;
            color: #273449;
            border-color: #cdd3db;
        }

        .golongan-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .golongan-stat {
            background: #fff;
            border: 1px solid #e7eaf0;
            border-radius: 14px;
            padding: 17px;
            display: flex;
            align-items: center;
            gap: 13px;
            box-shadow: 0 5px 18px rgba(20, 35, 60, .035);
        }

        .golongan-stat-icon {
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

        .golongan-stat-label {
            color: #929ba9;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .golongan-stat-number {
            color: #273449;
            font-size: 21px;
            font-weight: 800;
        }

        .golongan-panel {
            background: #fff;
            border: 1px solid #e6eaf0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(20, 35, 60, .04);
        }

        .golongan-panel-header {
            padding: 17px 19px;
            border-bottom: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .golongan-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            max-width: 600px;
        }

        .golongan-search {
            position: relative;
            flex: 1;
        }

        .golongan-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa3b0;
            font-size: 15px;
        }

        .golongan-search input {
            width: 100%;
            height: 40px;
            padding: 0 12px 0 35px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            outline: none;
            color: #344054;
            font-size: 12px;
        }

        .golongan-search input:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .golongan-filter-select {
            height: 40px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 0 11px;
            color: #4b5565;
            background: #fff;
            font-size: 12px;
            outline: none;
        }

        .golongan-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .golongan-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 820px;
        }

        .golongan-table th {
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

        .golongan-table td {
            padding: 15px 18px;
            border-bottom: 1px solid #f0f2f5;
            color: #475467;
            font-size: 13px;
            vertical-align: middle;
        }

        .golongan-table tr:last-child td {
            border-bottom: 0;
        }

        .golongan-table tbody tr:hover {
            background: #fcfcfd;
        }

        .golongan-id {
            color: #a0a8b4;
            font-size: 10px;
            font-family: monospace;
        }

        .golongan-code {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 7px;
            background: #f3f5f8;
            color: #596579;
            font-size: 11px;
            font-weight: 700;
            font-family: monospace;
        }

        .golongan-name {
            color: #273449;
            font-size: 14px;
            font-weight: 750;
        }

        .golongan-pangkat {
            color: #667085;
            font-size: 12px;
        }

        .golongan-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .golongan-status.active {
            color: #247a4b;
            background: #eaf8f0;
        }

        .golongan-status.inactive {
            color: #7b8491;
            background: #f0f2f5;
        }

        .golongan-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .golongan-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .golongan-action-button {
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

        .golongan-action-button:hover {
            border-color: #cdd3db;
            background: #f8f9fb;
            color: #273449;
        }

        .golongan-action-button.warning:hover {
            background: #fff8e8;
            color: #b87800;
            border-color: #efd99d;
        }

        .golongan-action-button.danger:hover {
            background: #fff1f1;
            color: #d33;
            border-color: #efcaca;
        }

        .golongan-empty {
            padding: 55px 20px;
            text-align: center;
        }

        .golongan-empty-icon {
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

        .golongan-empty-title {
            color: #4b5565;
            font-size: 14px;
            font-weight: 700;
        }

        .golongan-empty-text {
            margin-top: 4px;
            color: #9aa2ad;
            font-size: 11px;
        }

        .golongan-pagination {
            padding: 15px 18px;
            border-top: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .golongan-pagination-info {
            color: #8993a3;
            font-size: 11px;
            white-space: nowrap;
        }

        .golongan-pagination-nav {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .golongan-pagination-nav a,
        .golongan-pagination-nav span {
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

        .golongan-pagination-nav a:hover {
            background: #fff8f2;
            border-color: #df8339;
            color: #c86520;
        }

        .golongan-pagination-nav .active {
            border-color: #df8339;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-weight: 700;
        }

        .golongan-pagination-nav .disabled {
            color: #c2c8d0;
            background: #f8f9fb;
        }

        .golongan-modal .modal-content {
            border: 0;
            border-radius: 17px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, .20);
        }

        .golongan-modal .modal-header {
            padding: 18px 21px;
            border: 0;
            background: linear-gradient(135deg, #14223b, #1d3558);
            color: #fff;
        }

        .golongan-modal .modal-title {
            font-size: 16px;
            font-weight: 800;
        }

        .golongan-modal .btn-close {
            filter: brightness(0) invert(1);
            opacity: .7;
        }

        .golongan-modal .modal-body {
            padding: 21px;
        }

        .golongan-modal .modal-footer {
            padding: 14px 21px;
            border-top: 1px solid #edf0f4;
        }

        .golongan-form-label {
            display: block;
            margin-bottom: 7px;
            color: #475467;
            font-size: 11px;
            font-weight: 700;
        }

        .golongan-form-control {
            width: 100%;
            height: 42px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 0 12px;
            color: #344054;
            font-size: 12px;
            outline: none;
        }

        .golongan-form-control:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .golongan-form-group {
            margin-bottom: 16px;
        }

        .golongan-status-options {
            display: flex;
            gap: 8px;
        }

        .golongan-status-option {
            flex: 1;
            position: relative;
        }

        .golongan-status-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .golongan-status-option label {
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

        .golongan-status-option input:checked+label {
            border-color: #df8339;
            background: #fff8f2;
            color: #c86520;
        }

        .golongan-submit-button {
            border: 0;
            border-radius: 8px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        .golongan-cancel-button {
            border: 1px solid #dfe4eb;
            border-radius: 8px;
            padding: 10px 16px;
            background: #fff;
            color: #667085;
            font-size: 12px;
            font-weight: 600;
        }

        .golongan-alert {
            border: 0;
            border-radius: 10px;
            font-size: 12px;
        }

        @media(max-width: 800px) {

            .golongan-stat-grid {
                grid-template-columns: 1fr;
            }

            .golongan-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .golongan-header-actions {
                width: 100%;
            }

            .golongan-add-button,
            .golongan-import-button {
                justify-content: center;
                flex: 1;
            }

            .golongan-panel-header {
                align-items: stretch;
                flex-direction: column;
            }

            .golongan-filter {
                max-width: none;
            }

            .golongan-pagination {
                align-items: flex-start;
                flex-direction: column;
            }

            .golongan-pagination-nav {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media(max-width: 550px) {

            .golongan-filter {
                flex-direction: column;
            }

            .golongan-search,
            .golongan-filter-select {
                width: 100%;
            }

            .golongan-header-actions {
                flex-direction: column;
            }

            .golongan-add-button,
            .golongan-import-button {
                width: 100%;
            }
        }
    </style>

@endsection


@section('content')

    <div class="golongan-page">

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success golongan-alert mb-3">

                <i class="bi bi-check-circle me-1"></i>

                {{ session('success') }}

            </div>
        @endif


        {{-- ALERT ERROR --}}
        @if ($errors->any())

            <div class="alert alert-danger golongan-alert mb-3">

                <i class="bi bi-exclamation-circle me-1"></i>

                @foreach ($errors->all() as $error)
                    <div>
                        {{ $error }}
                    </div>
                @endforeach

            </div>

        @endif


        {{-- HEADER --}}
        <div class="golongan-header">

            <div class="golongan-header-left">

                <h1 class="golongan-header-title">
                    Manajemen Golongan
                </h1>

                <p class="golongan-header-description">
                    Kelola data golongan pada SAMPERIN.
                </p>

            </div>


            <div class="golongan-header-actions">

                <button type="button" class="golongan-add-button" data-bs-toggle="modal"
                    data-bs-target="#golonganCreateModal">

                    <i class="bi bi-plus-lg"></i>

                    Tambah Golongan

                </button>


                <a href="{{ route('master.golongan.import') }}" class="golongan-import-button">

                    <i class="bi bi-upload"></i>

                    Import

                </a>

            </div>

        </div>


        {{-- STAT --}}
        <div class="golongan-stat-grid">

            <div class="golongan-stat">

                <div class="golongan-stat-icon">
                    <i class="bi bi-award"></i>
                </div>

                <div>

                    <div class="golongan-stat-label">
                        Total Golongan
                    </div>

                    <div class="golongan-stat-number">
                        {{ $totalGolongan }}
                    </div>

                </div>

            </div>


            <div class="golongan-stat">

                <div class="golongan-stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>

                    <div class="golongan-stat-label">
                        Golongan Aktif
                    </div>

                    <div class="golongan-stat-number">
                        {{ $golonganAktif }}
                    </div>

                </div>

            </div>


            <div class="golongan-stat">

                <div class="golongan-stat-icon">
                    <i class="bi bi-dash-circle"></i>
                </div>

                <div>

                    <div class="golongan-stat-label">
                        Golongan Nonaktif
                    </div>

                    <div class="golongan-stat-number">
                        {{ $golonganNonaktif }}
                    </div>

                </div>

            </div>

        </div>


        {{-- PANEL --}}
        <div class="golongan-panel">

            {{-- FILTER --}}
            <div class="golongan-panel-header">

                <form method="GET" action="{{ route('master.golongan.index') }}" class="golongan-filter">

                    <div class="golongan-search">

                        <i class="bi bi-search"></i>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode, nama, atau pangkat...">

                    </div>


                    <select name="status" class="golongan-filter-select" onchange="this.form.submit()">

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
            <div class="golongan-table-wrapper">

                <table class="golongan-table">

                    <thead>

                        <tr>

                            <th style="width:60px;">
                                ID
                            </th>

                            <th>
                                KODE
                            </th>

                            <th>
                                GOLONGAN
                            </th>

                            <th>
                                PANGKAT
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

                        @forelse($golongans as $golongan)
                            <tr>

                                <td>

                                    <span class="golongan-id">
                                        #{{ $golongan->golongan_id }}
                                    </span>

                                </td>


                                <td>

                                    <span class="golongan-code">
                                        {{ $golongan->golongan_kode }}
                                    </span>

                                </td>


                                <td>

                                    <div class="golongan-name">
                                        {{ $golongan->golongan_nama }}
                                    </div>

                                </td>


                                <td>

                                    <div class="golongan-pangkat">
                                        {{ $golongan->golongan_pangkat ?: '-' }}
                                    </div>

                                </td>


                                <td>

                                    @if ($golongan->golongan_status == 1)
                                        <span class="golongan-status active">

                                            <span class="golongan-status-dot"></span>

                                            Aktif

                                        </span>
                                    @else
                                        <span class="golongan-status inactive">

                                            <span class="golongan-status-dot"></span>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                <td>

                                    <div class="golongan-actions">

                                        {{-- EDIT --}}
                                        <button type="button" class="golongan-action-button" data-bs-toggle="modal"
                                            data-bs-target="#golonganEditModal{{ $golongan->golongan_id }}" title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        {{-- STATUS --}}
                                        <form method="POST"
                                            action="{{ route('master.golongan.status', $golongan->golongan_id) }}">

                                            @csrf

                                            @method('PATCH')

                                            <button type="submit" class="golongan-action-button warning"
                                                title="{{ $golongan->golongan_status == 1 ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                onclick="return confirm('{{ $golongan->golongan_status == 1 ? 'Nonaktifkan golongan ini?' : 'Aktifkan golongan ini?' }}')">

                                                <i class="bi bi-pause-circle"></i>

                                            </button>

                                        </form>


                                        {{-- DELETE --}}
                                        <form method="POST"
                                            action="{{ route('master.golongan.destroy', $golongan->golongan_id) }}">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit" class="golongan-action-button danger" title="Hapus"
                                                onclick="return confirm('Hapus golongan ini? Data yang masih digunakan pegawai tidak dapat dihapus.')">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            {{-- EDIT MODAL --}}
                            <div class="modal fade golongan-modal" id="golonganEditModal{{ $golongan->golongan_id }}"
                                tabindex="-1">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form method="POST"
                                            action="{{ route('master.golongan.update', $golongan->golongan_id) }}">

                                            @csrf

                                            @method('PUT')


                                            <div class="modal-header">

                                                <div class="modal-title">
                                                    Edit Golongan
                                                </div>

                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>

                                            </div>


                                            <div class="modal-body">

                                                {{-- KODE --}}
                                                <div class="golongan-form-group">

                                                    <label class="golongan-form-label">
                                                        Kode Golongan
                                                    </label>

                                                    <input type="text" class="golongan-form-control"
                                                        value="{{ $golongan->golongan_kode }}" readonly>

                                                </div>


                                                {{-- NAMA --}}
                                                <div class="golongan-form-group">

                                                    <label class="golongan-form-label">
                                                        Nama Golongan
                                                    </label>

                                                    <input type="text" name="golongan_nama"
                                                        class="golongan-form-control"
                                                        value="{{ $golongan->golongan_nama }}" required>

                                                </div>


                                                {{-- PANGKAT --}}
                                                <div class="golongan-form-group">

                                                    <label class="golongan-form-label">
                                                        Pangkat
                                                    </label>

                                                    <input type="text" name="golongan_pangkat"
                                                        class="golongan-form-control"
                                                        value="{{ $golongan->golongan_pangkat }}"
                                                        placeholder="Contoh: Penata Muda">

                                                </div>


                                                {{-- STATUS --}}
                                                <div class="golongan-form-group">

                                                    <label class="golongan-form-label">
                                                        Status
                                                    </label>

                                                    <div class="golongan-status-options">

                                                        <div class="golongan-status-option">

                                                            <input type="radio"
                                                                id="editGolonganActive{{ $golongan->golongan_id }}"
                                                                name="golongan_status" value="1"
                                                                {{ $golongan->golongan_status == 1 ? 'checked' : '' }}>

                                                            <label for="editGolonganActive{{ $golongan->golongan_id }}">

                                                                Aktif

                                                            </label>

                                                        </div>


                                                        <div class="golongan-status-option">

                                                            <input type="radio"
                                                                id="editGolonganInactive{{ $golongan->golongan_id }}"
                                                                name="golongan_status" value="0"
                                                                {{ $golongan->golongan_status == 0 ? 'checked' : '' }}>

                                                            <label for="editGolonganInactive{{ $golongan->golongan_id }}">

                                                                Nonaktif

                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button type="button" class="golongan-cancel-button"
                                                    data-bs-dismiss="modal">

                                                    Batal

                                                </button>

                                                <button type="submit" class="golongan-submit-button">

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

                                    <div class="golongan-empty">

                                        <div class="golongan-empty-icon">

                                            <i class="bi bi-award"></i>

                                        </div>

                                        <div class="golongan-empty-title">

                                            Data golongan belum tersedia

                                        </div>

                                        <div class="golongan-empty-text">

                                            Belum ada data golongan yang dapat ditampilkan.

                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            @if ($golongans->hasPages())

                <div class="golongan-pagination">

                    <div class="golongan-pagination-info">

                        Menampilkan

                        <strong>
                            {{ $golongans->firstItem() }}
                        </strong>

                        -

                        <strong>
                            {{ $golongans->lastItem() }}
                        </strong>

                        dari

                        <strong>
                            {{ $golongans->total() }}
                        </strong>

                        data

                    </div>


                    <div class="golongan-pagination-nav">

                        @if ($golongans->onFirstPage())
                            <span class="disabled">

                                <i class="bi bi-chevron-left me-1"></i>

                                Sebelumnya

                            </span>
                        @else
                            <a href="{{ $golongans->previousPageUrl() }}">

                                <i class="bi bi-chevron-left me-1"></i>

                                Sebelumnya

                            </a>
                        @endif


                        @foreach ($golongans->getUrlRange(max(1, $golongans->currentPage() - 2), min($golongans->lastPage(), $golongans->currentPage() + 2)) as $page => $url)
                            @if ($page == $golongans->currentPage())
                                <span class="active">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach


                        @if ($golongans->hasMorePages())
                            <a href="{{ $golongans->nextPageUrl() }}">

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
    <div class="modal fade golongan-modal" id="golonganCreateModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form method="POST" action="{{ route('master.golongan.store') }}">

                    @csrf


                    <div class="modal-header">

                        <div class="modal-title">
                            Tambah Golongan
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        {{-- KODE OTOMATIS --}}
                        <div class="golongan-form-group">

                            <label class="golongan-form-label">
                                Kode
                            </label>

                            <input type="text" class="golongan-form-control" value="Otomatis oleh sistem" readonly>

                        </div>


                        {{-- NAMA --}}
                        <div class="golongan-form-group">

                            <label class="golongan-form-label">
                                Nama Golongan
                            </label>

                            <input type="text" name="golongan_nama" class="golongan-form-control"
                                placeholder="Contoh: Penata Muda" required>

                        </div>


                        {{-- PANGKAT --}}
                        <div class="golongan-form-group">

                            <label class="golongan-form-label">
                                Pangkat
                            </label>

                            <input type="text" name="golongan_pangkat" class="golongan-form-control"
                                placeholder="Contoh: III/a">
                        </div>


                        {{-- STATUS --}}
                        <div class="golongan-form-group">

                            <label class="golongan-form-label">
                                Status
                            </label>

                            <div class="golongan-status-options">

                                <div class="golongan-status-option">

                                    <input type="radio" id="createGolonganActive" name="golongan_status"
                                        value="1" checked>

                                    <label for="createGolonganActive">

                                        Aktif

                                    </label>

                                </div>


                                <div class="golongan-status-option">

                                    <input type="radio" id="createGolonganInactive" name="golongan_status"
                                        value="0">

                                    <label for="createGolonganInactive">

                                        Nonaktif

                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="golongan-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="golongan-submit-button">

                            Simpan Golongan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
