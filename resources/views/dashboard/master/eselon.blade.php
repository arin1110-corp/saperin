@extends('dashboard.layouts.app')

@section('title', 'Manajemen Eselon')

@section('header-title', 'Manajemen Eselon')

@section('breadcrumb', 'Manajemen Eselon')

@section('page-style')

    <style>
        .eselon-page {
            width: 100%;
        }

        .eselon-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .eselon-header-left {
            min-width: 0;
        }

        .eselon-header-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
        }

        .eselon-header-description {
            margin: 6px 0 0;
            color: #8993a3;
            font-size: 13px;
        }

        .eselon-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .eselon-add-button {
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

        .eselon-add-button:hover {
            transform: translateY(-1px);
            color: #fff;
        }

        .eselon-import-button {
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

        .eselon-import-button:hover {
            background: #f8f9fb;
            color: #273449;
            border-color: #cdd3db;
        }

        .eselon-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .eselon-stat {
            background: #fff;
            border: 1px solid #e7eaf0;
            border-radius: 14px;
            padding: 17px;
            display: flex;
            align-items: center;
            gap: 13px;
            box-shadow: 0 5px 18px rgba(20, 35, 60, .035);
        }

        .eselon-stat-icon {
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

        .eselon-stat-label {
            color: #929ba9;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .eselon-stat-number {
            color: #273449;
            font-size: 21px;
            font-weight: 800;
        }

        .eselon-panel {
            background: #fff;
            border: 1px solid #e6eaf0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(20, 35, 60, .04);
        }

        .eselon-panel-header {
            padding: 17px 19px;
            border-bottom: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .eselon-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            max-width: 560px;
        }

        .eselon-search {
            position: relative;
            flex: 1;
        }

        .eselon-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa3b0;
            font-size: 15px;
        }

        .eselon-search input {
            width: 100%;
            height: 40px;
            padding: 0 12px 0 35px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            outline: none;
            color: #344054;
            font-size: 12px;
        }

        .eselon-search input:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .eselon-filter-select {
            height: 40px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 0 11px;
            color: #4b5565;
            background: #fff;
            font-size: 12px;
            outline: none;
        }

        .eselon-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .eselon-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }

        .eselon-table th {
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

        .eselon-table td {
            padding: 15px 18px;
            border-bottom: 1px solid #f0f2f5;
            color: #475467;
            font-size: 13px;
            vertical-align: middle;
        }

        .eselon-table tr:last-child td {
            border-bottom: 0;
        }

        .eselon-table tbody tr:hover {
            background: #fcfcfd;
        }

        .eselon-id {
            color: #a0a8b4;
            font-size: 10px;
            font-family: monospace;
        }

        .eselon-code {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 7px;
            background: #f3f5f8;
            color: #6d7683;
            font-size: 10px;
            font-weight: 700;
            font-family: monospace;
        }

        .eselon-name {
            color: #273449;
            font-size: 14px;
            font-weight: 750;
        }

        .eselon-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .eselon-status.active {
            color: #247a4b;
            background: #eaf8f0;
        }

        .eselon-status.inactive {
            color: #7b8491;
            background: #f0f2f5;
        }

        .eselon-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .eselon-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .eselon-action-button {
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

        .eselon-action-button:hover {
            border-color: #cdd3db;
            background: #f8f9fb;
            color: #273449;
        }

        .eselon-action-button.warning:hover {
            background: #fff8e8;
            color: #b87800;
            border-color: #efd99d;
        }

        .eselon-action-button.danger:hover {
            background: #fff1f1;
            color: #d33;
            border-color: #efcaca;
        }

        .eselon-empty {
            padding: 55px 20px;
            text-align: center;
        }

        .eselon-empty-icon {
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

        .eselon-empty-title {
            color: #4b5565;
            font-size: 14px;
            font-weight: 700;
        }

        .eselon-empty-text {
            margin-top: 4px;
            color: #9aa2ad;
            font-size: 11px;
        }

        .eselon-pagination {
            padding: 15px 18px;
            border-top: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .eselon-pagination-info {
            color: #8993a3;
            font-size: 11px;
            white-space: nowrap;
        }

        .eselon-pagination-nav {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .eselon-pagination-nav a,
        .eselon-pagination-nav span {
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

        .eselon-pagination-nav a:hover {
            background: #fff8f2;
            border-color: #df8339;
            color: #c86520;
        }

        .eselon-pagination-nav .active {
            border-color: #df8339;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-weight: 700;
        }

        .eselon-pagination-nav .disabled {
            color: #c2c8d0;
            background: #f8f9fb;
        }

        .eselon-modal .modal-content {
            border: 0;
            border-radius: 17px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, .20);
        }

        .eselon-modal .modal-header {
            padding: 18px 21px;
            border: 0;
            background: linear-gradient(135deg, #14223b, #1d3558);
            color: #fff;
        }

        .eselon-modal .modal-title {
            font-size: 16px;
            font-weight: 800;
        }

        .eselon-modal .btn-close {
            filter: brightness(0) invert(1);
            opacity: .7;
        }

        .eselon-modal .modal-body {
            padding: 21px;
        }

        .eselon-modal .modal-footer {
            padding: 14px 21px;
            border-top: 1px solid #edf0f4;
        }

        .eselon-form-label {
            display: block;
            margin-bottom: 7px;
            color: #475467;
            font-size: 11px;
            font-weight: 700;
        }

        .eselon-form-control {
            width: 100%;
            height: 42px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 0 12px;
            color: #344054;
            font-size: 12px;
            outline: none;
        }

        .eselon-form-control:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .eselon-form-group {
            margin-bottom: 16px;
        }

        .eselon-status-options {
            display: flex;
            gap: 8px;
        }

        .eselon-status-option {
            flex: 1;
            position: relative;
        }

        .eselon-status-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .eselon-status-option label {
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

        .eselon-status-option input:checked+label {
            border-color: #df8339;
            background: #fff8f2;
            color: #c86520;
        }

        .eselon-submit-button {
            border: 0;
            border-radius: 8px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        .eselon-cancel-button {
            border: 1px solid #dfe4eb;
            border-radius: 8px;
            padding: 10px 16px;
            background: #fff;
            color: #667085;
            font-size: 12px;
            font-weight: 600;
        }

        .eselon-alert {
            border: 0;
            border-radius: 10px;
            font-size: 12px;
        }

        @media(max-width: 800px) {

            .eselon-stat-grid {
                grid-template-columns: 1fr;
            }

            .eselon-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .eselon-header-actions {
                width: 100%;
            }

            .eselon-add-button,
            .eselon-import-button {
                justify-content: center;
                flex: 1;
            }

            .eselon-panel-header {
                align-items: stretch;
                flex-direction: column;
            }

            .eselon-filter {
                max-width: none;
            }

            .eselon-pagination {
                align-items: flex-start;
                flex-direction: column;
            }

            .eselon-pagination-nav {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media(max-width: 550px) {

            .eselon-filter {
                flex-direction: column;
            }

            .eselon-search,
            .eselon-filter-select {
                width: 100%;
            }

            .eselon-header-actions {
                flex-direction: column;
            }

            .eselon-add-button,
            .eselon-import-button {
                width: 100%;
            }
        }
    </style>

@endsection


@section('content')

    <div class="eselon-page">

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success eselon-alert mb-3">

                <i class="bi bi-check-circle me-1"></i>

                {{ session('success') }}

            </div>
        @endif


        {{-- ERROR --}}
        @if ($errors->any())

            <div class="alert alert-danger eselon-alert mb-3">

                <i class="bi bi-exclamation-circle me-1"></i>

                @foreach ($errors->all() as $error)
                    <div>
                        {{ $error }}
                    </div>
                @endforeach

            </div>

        @endif


        {{-- HEADER --}}

        <div class="eselon-header">

            <div class="eselon-header-left">

                <h1 class="eselon-header-title">
                    Manajemen Eselon
                </h1>

                <p class="eselon-header-description">
                    Kelola data eselon pada SAMPERIN.
                </p>

            </div>


            <div class="eselon-header-actions">

                <button type="button" class="eselon-add-button" data-bs-toggle="modal" data-bs-target="#eselonCreateModal">

                    <i class="bi bi-plus-lg"></i>

                    Tambah Eselon

                </button>


                <a href="{{ route('master.eselon.import') }}" class="eselon-import-button">

                    <i class="bi bi-upload"></i>

                    Import

                </a>

            </div>

        </div>


        {{-- STATISTIK --}}

        <div class="eselon-stat-grid">

            <div class="eselon-stat">

                <div class="eselon-stat-icon">

                    <i class="bi bi-diagram-3"></i>

                </div>

                <div>

                    <div class="eselon-stat-label">
                        Total Eselon
                    </div>

                    <div class="eselon-stat-number">
                        {{ $totalEselon }}
                    </div>

                </div>

            </div>


            <div class="eselon-stat">

                <div class="eselon-stat-icon">

                    <i class="bi bi-check-circle"></i>

                </div>

                <div>

                    <div class="eselon-stat-label">
                        Eselon Aktif
                    </div>

                    <div class="eselon-stat-number">
                        {{ $eselonAktif }}
                    </div>

                </div>

            </div>


            <div class="eselon-stat">

                <div class="eselon-stat-icon">

                    <i class="bi bi-dash-circle"></i>

                </div>

                <div>

                    <div class="eselon-stat-label">
                        Eselon Nonaktif
                    </div>

                    <div class="eselon-stat-number">
                        {{ $eselonNonaktif }}
                    </div>

                </div>

            </div>

        </div>


        {{-- PANEL --}}

        <div class="eselon-panel">

            {{-- FILTER --}}

            <div class="eselon-panel-header">

                <form method="GET" action="{{ route('master.eselon.index') }}" class="eselon-filter">

                    <div class="eselon-search">

                        <i class="bi bi-search"></i>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode atau nama eselon...">

                    </div>


                    <select name="status" class="eselon-filter-select" onchange="this.form.submit()">

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

            <div class="eselon-table-wrapper">

                <table class="eselon-table">

                    <thead>

                        <tr>

                            <th style="width: 60px;">
                                ID
                            </th>

                            <th>
                                KODE
                            </th>

                            <th>
                                ESELON
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

                        @forelse($eselons as $eselon)
                            <tr>

                                <td>

                                    <span class="eselon-id">
                                        #{{ $eselon->eselon_id }}
                                    </span>

                                </td>


                                <td>

                                    <span class="eselon-code">
                                        {{ $eselon->eselon_kode }}
                                    </span>

                                </td>


                                <td>

                                    <div class="eselon-name">
                                        {{ $eselon->eselon_nama }}
                                    </div>

                                </td>


                                <td>

                                    @if ($eselon->eselon_status == 1)
                                        <span class="eselon-status active">

                                            <span class="eselon-status-dot"></span>

                                            Aktif

                                        </span>
                                    @else
                                        <span class="eselon-status inactive">

                                            <span class="eselon-status-dot"></span>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                <td>

                                    <div class="eselon-actions">

                                        {{-- EDIT --}}

                                        <button type="button" class="eselon-action-button" data-bs-toggle="modal"
                                            data-bs-target="#eselonEditModal{{ $eselon->eselon_id }}" title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        {{-- STATUS --}}

                                        <form method="POST"
                                            action="{{ route('master.eselon.status', $eselon->eselon_uid) }}">

                                            @csrf

                                            @method('PATCH')

                                            <button type="submit" class="eselon-action-button warning"
                                                title="{{ $eselon->eselon_status == 1 ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                onclick="return confirm('{{ $eselon->eselon_status == 1 ? 'Nonaktifkan eselon ini?' : 'Aktifkan eselon ini?' }}')">

                                                <i class="bi bi-pause-circle"></i>

                                            </button>

                                        </form>


                                        {{-- DELETE --}}

                                        <form method="POST"
                                            action="{{ route('master.eselon.destroy', $eselon->eselon_uid) }}">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit" class="eselon-action-button danger" title="Hapus"
                                                onclick="return confirm('Hapus eselon ini?')">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            {{-- EDIT MODAL --}}

                            <div class="modal fade eselon-modal" id="eselonEditModal{{ $eselon->eselon_id }}"
                                tabindex="-1">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <form method="POST"
                                            action="{{ route('master.eselon.update', $eselon->eselon_uid) }}">

                                            @csrf

                                            @method('PUT')


                                            <div class="modal-header">

                                                <div>

                                                    <div class="modal-title">
                                                        Edit Eselon
                                                    </div>

                                                </div>


                                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                </button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="eselon-form-group">

                                                    <label class="eselon-form-label">
                                                        Kode Eselon
                                                    </label>

                                                    <input type="text" class="eselon-form-control"
                                                        value="{{ $eselon->eselon_kode }}" readonly>

                                                </div>


                                                <div class="eselon-form-group">

                                                    <label class="eselon-form-label">
                                                        Nama Eselon
                                                    </label>

                                                    <input type="text" name="eselon_nama" class="eselon-form-control"
                                                        value="{{ $eselon->eselon_nama }}" required>

                                                </div>


                                                <div class="eselon-form-group">

                                                    <label class="eselon-form-label">
                                                        Status
                                                    </label>


                                                    <div class="eselon-status-options">

                                                        <div class="eselon-status-option">

                                                            <input type="radio" id="editActive{{ $eselon->eselon_id }}"
                                                                name="eselon_status" value="1"
                                                                {{ $eselon->eselon_status == 1 ? 'checked' : '' }}>

                                                            <label for="editActive{{ $eselon->eselon_id }}">
                                                                Aktif
                                                            </label>

                                                        </div>


                                                        <div class="eselon-status-option">

                                                            <input type="radio"
                                                                id="editInactive{{ $eselon->eselon_id }}"
                                                                name="eselon_status" value="0"
                                                                {{ $eselon->eselon_status == 0 ? 'checked' : '' }}>

                                                            <label for="editInactive{{ $eselon->eselon_id }}">
                                                                Nonaktif
                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button type="button" class="eselon-cancel-button"
                                                    data-bs-dismiss="modal">

                                                    Batal

                                                </button>


                                                <button type="submit" class="eselon-submit-button">

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

                                    <div class="eselon-empty">

                                        <div class="eselon-empty-icon">

                                            <i class="bi bi-diagram-3"></i>

                                        </div>


                                        <div class="eselon-empty-title">
                                            Data eselon belum tersedia
                                        </div>


                                        <div class="eselon-empty-text">
                                            Belum ada data eselon yang dapat ditampilkan.
                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            @if ($eselons->hasPages())

                <div class="eselon-pagination">

                    <div class="eselon-pagination-info">

                        Menampilkan

                        <strong>
                            {{ $eselons->firstItem() }}
                        </strong>

                        -

                        <strong>
                            {{ $eselons->lastItem() }}
                        </strong>

                        dari

                        <strong>
                            {{ $eselons->total() }}
                        </strong>

                        data

                    </div>


                    <div class="eselon-pagination-nav">

                        @if ($eselons->onFirstPage())
                            <span class="disabled">

                                <i class="bi bi-chevron-left me-1"></i>

                                Sebelumnya

                            </span>
                        @else
                            <a href="{{ $eselons->previousPageUrl() }}">

                                <i class="bi bi-chevron-left me-1"></i>

                                Sebelumnya

                            </a>
                        @endif


                        @foreach ($eselons->getUrlRange(max(1, $eselons->currentPage() - 2), min($eselons->lastPage(), $eselons->currentPage() + 2)) as $page => $url)
                            @if ($page == $eselons->currentPage())
                                <span class="active">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach


                        @if ($eselons->hasMorePages())
                            <a href="{{ $eselons->nextPageUrl() }}">

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

    <div class="modal fade eselon-modal" id="eselonCreateModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form method="POST" action="{{ route('master.eselon.store') }}">

                    @csrf


                    <div class="modal-header">

                        <div>

                            <div class="modal-title">
                                Tambah Eselon
                            </div>

                        </div>


                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="eselon-form-group">

                            <label class="eselon-form-label">
                                Kode Eselon
                            </label>

                            <input type="text" class="eselon-form-control" value="Otomatis" readonly>

                        </div>


                        <div class="eselon-form-group">

                            <label class="eselon-form-label">
                                Nama Eselon
                            </label>

                            <input type="text" name="eselon_nama" class="eselon-form-control"
                                placeholder="Contoh: Eselon II" required>

                        </div>


                        <div class="eselon-form-group">

                            <label class="eselon-form-label">
                                Status
                            </label>


                            <div class="eselon-status-options">

                                <div class="eselon-status-option">

                                    <input type="radio" id="createActive" name="eselon_status" value="1" checked>

                                    <label for="createActive">
                                        Aktif
                                    </label>

                                </div>


                                <div class="eselon-status-option">

                                    <input type="radio" id="createInactive" name="eselon_status" value="0">

                                    <label for="createInactive">
                                        Nonaktif
                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="eselon-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>


                        <button type="submit" class="eselon-submit-button">

                            Simpan Eselon

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
