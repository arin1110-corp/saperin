@extends('dashboard.layouts.app')

@section('title', 'Manajemen Pengguna')

@section('breadcrumb', 'Manajemen Pengguna')

@section('header-title', 'Manajemen Pengguna')

@section('page-style')

    <style>
        .user-page {
            width: 100%;
        }

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .user-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
        }

        .user-header-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
        }

        .user-header-description {
            margin: 6px 0 0;
            color: #8993a3;
            font-size: 13px;
        }

        .user-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .user-button {
            border: 0;
            padding: 11px 17px;
            border-radius: 10px;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: .15s ease;
            text-decoration: none;
        }

        .user-button:hover {
            transform: translateY(-1px);
        }

        .user-button.import {
            background: #f4f6f8;
            border: 1px solid #dfe4eb;
            color: #536071;
        }

        .user-button.import:hover {
            background: #edf0f3;
            color: #536071;
        }

        .user-button.default {
            background: #14223b;
            box-shadow: 0 7px 18px rgba(20, 34, 59, .15);
        }

        .user-button.default:hover {
            color: #fff;
        }

        .user-button.primary {
            background: linear-gradient(135deg,
                    #df8339,
                    #c35e1d);
            box-shadow: 0 7px 18px rgba(195, 94, 29, .18);
        }

        /*
        |--------------------------------------------------------------------------
        | STAT
        |--------------------------------------------------------------------------
        */

        .user-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .user-stat {
            background: #fff;
            border: 1px solid #e7eaf0;
            border-radius: 14px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 5px 18px rgba(20, 35, 60, .035);
        }

        .user-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff0e3;
            color: #d2742d;
            font-size: 19px;
            flex-shrink: 0;
        }

        .user-stat-label {
            color: #929ba9;
            font-size: 11px;
            margin-bottom: 4px;
        }

        .user-stat-number {
            color: #273449;
            font-size: 21px;
            font-weight: 800;
        }

        /*
        |--------------------------------------------------------------------------
        | PANEL
        |--------------------------------------------------------------------------
        */

        .user-panel {
            background: #fff;
            border: 1px solid #e6eaf0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(20, 35, 60, .04);
        }

        .user-panel-header {
            padding: 17px 19px;
            border-bottom: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        .user-filter {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .user-search {
            position: relative;
            flex: 1;
        }

        .user-search i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa3b0;
            font-size: 15px;
        }

        .user-search input,
        .user-filter-select {
            height: 40px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            outline: none;
            color: #344054;
            background: #fff;
            font-size: 12px;
        }

        .user-search input {
            width: 100%;
            padding: 0 12px 0 37px;
        }

        .user-filter-select {
            min-width: 145px;
            padding: 0 11px;
        }

        .user-search input:focus,
        .user-filter-select:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        /*
        |--------------------------------------------------------------------------
        | BULK INFO
        |--------------------------------------------------------------------------
        */

        .user-bulk-info {
            margin: 16px 18px 0;
            padding: 12px 14px;
            border: 1px solid #e8e0d6;
            border-radius: 10px;
            background: #fffaf5;
            color: #756653;
            font-size: 11px;
            line-height: 1.6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .user-bulk-info strong {
            color: #b65d1d;
        }

        .user-bulk-button {
            flex-shrink: 0;
            border: 0;
            border-radius: 8px;
            padding: 9px 13px;
            background: #14223b;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
        }

        .user-bulk-button:hover {
            background: #1d3558;
        }

        /*
        |--------------------------------------------------------------------------
        | TABLE
        |--------------------------------------------------------------------------
        */

        .user-table-wrapper {
            width: 100%;
            overflow-x: auto;
            margin-top: 16px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th {
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

        .user-table td {
            padding: 15px 18px;
            border-bottom: 1px solid #f0f2f5;
            color: #475467;
            font-size: 13px;
            vertical-align: middle;
        }

        .user-table tr:last-child td {
            border-bottom: 0;
        }

        .user-table tbody tr:hover {
            background: #fcfcfd;
        }

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        .user-info {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg,
                    #14223b,
                    #28466e);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .user-name {
            color: #273449;
            font-size: 13px;
            font-weight: 750;
        }

        .user-email {
            margin-top: 3px;
            color: #929aa6;
            font-size: 11px;
        }

        .user-nik {
            margin-top: 3px;
            color: #929aa6;
            font-size: 10px;
            font-family: monospace;
        }

        .user-nip {
            color: #596579;
            font-size: 12px;
            font-family: monospace;
        }

        .user-uid {
            margin-top: 3px;
            color: #a0a8b4;
            font-size: 9px;
            font-family: monospace;
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        .user-role-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .user-role {
            display: inline-flex;
            padding: 5px 8px;
            border-radius: 7px;
            background: #f3f5f8;
            color: #626d7d;
            font-size: 10px;
            font-weight: 700;
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        .user-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .user-status.active {
            color: #247a4b;
            background: #eaf8f0;
        }

        .user-status.inactive {
            color: #7b8491;
            background: #f0f2f5;
        }

        .user-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /*
        |--------------------------------------------------------------------------
        | ACTION
        |--------------------------------------------------------------------------
        */

        .user-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
        }

        .user-action-button {
            width: 32px;
            height: 32px;
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

        .user-action-button:hover {
            border-color: #cdd3db;
            background: #f8f9fb;
            color: #273449;
        }

        .user-action-button.warning:hover {
            background: #fff8e8;
            color: #b87800;
            border-color: #efd99d;
        }

        .user-action-button.danger:hover {
            background: #fff1f1;
            color: #d33;
            border-color: #efcaca;
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        .user-pagination {
            padding: 14px 18px;
            border-top: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-pagination-info {
            color: #929aa6;
            font-size: 11px;
        }

        /*
        |--------------------------------------------------------------------------
        | EMPTY
        |--------------------------------------------------------------------------
        */

        .user-empty {
            padding: 60px 20px;
            text-align: center;
        }

        .user-empty-icon {
            width: 58px;
            height: 58px;
            margin: 0 auto 13px;
            border-radius: 16px;
            background: #f4f5f7;
            color: #9aa2ad;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .user-empty-title {
            color: #4b5565;
            font-size: 14px;
            font-weight: 700;
        }

        .user-empty-text {
            margin-top: 5px;
            color: #9aa2ad;
            font-size: 11px;
        }

        /*
        |--------------------------------------------------------------------------
        | MODAL
        |--------------------------------------------------------------------------
        */

        .user-modal .modal-content {
            border: 0;
            border-radius: 17px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, .20);
        }

        .user-modal .modal-header {
            padding: 18px 21px;
            border: 0;
            background: linear-gradient(135deg,
                    #14223b,
                    #1d3558);
            color: #fff;
        }

        .user-modal .modal-title {
            font-size: 16px;
            font-weight: 800;
        }

        .user-modal .modal-subtitle {
            margin-top: 4px;
            color: rgba(255, 255, 255, .55);
            font-size: 10px;
        }

        .user-modal .btn-close {
            filter: brightness(0) invert(1);
            opacity: .7;
        }

        .user-modal .modal-body {
            padding: 21px;
        }

        .user-modal .modal-footer {
            padding: 14px 21px;
            border-top: 1px solid #edf0f4;
        }

        /*
        |--------------------------------------------------------------------------
        | FORM
        |--------------------------------------------------------------------------
        */

        .user-form-group {
            margin-bottom: 16px;
        }

        .user-form-label {
            display: block;
            margin-bottom: 7px;
            color: #475467;
            font-size: 11px;
            font-weight: 700;
        }

        .user-form-control {
            width: 100%;
            min-height: 41px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 9px 11px;
            color: #344054;
            font-size: 12px;
            outline: none;
            background: #fff;
        }

        .user-form-control:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .user-form-help {
            margin-top: 5px;
            color: #98a2b3;
            font-size: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI SELECT
        |--------------------------------------------------------------------------
        */

        .pegawai-select-wrapper {
            position: relative;
        }

        .pegawai-select {
            width: 100%;
            min-height: 45px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 8px 11px;
            color: #344054;
            background: #fff;
            font-size: 12px;
            outline: none;
        }

        .pegawai-select:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, .08);
        }

        .pegawai-preview {
            margin-top: 9px;
            padding: 11px 12px;
            border-radius: 10px;
            background: #f7f9fb;
            border: 1px solid #edf0f4;
            display: none;
        }

        .pegawai-preview.show {
            display: block;
        }

        .pegawai-preview-name {
            color: #273449;
            font-size: 12px;
            font-weight: 800;
        }

        .pegawai-preview-detail {
            margin-top: 3px;
            color: #8993a3;
            font-size: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE CHECKBOX
        |--------------------------------------------------------------------------
        */

        .user-role-options {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 7px;
        }

        .user-role-option {
            position: relative;
        }

        .user-role-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .user-role-option label {
            min-height: 43px;
            padding: 8px 11px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            background: #fff;
            color: #667085;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: .15s ease;
        }

        .user-role-option label strong {
            font-size: 11px;
        }

        .user-role-option label span {
            margin-top: 2px;
            color: #98a2b3;
            font-size: 9px;
        }

        .user-role-option input:checked+label {
            border-color: #df8339;
            background: #fff8f2;
            color: #c86520;
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS OPTION
        |--------------------------------------------------------------------------
        */

        .user-status-options {
            display: flex;
            gap: 8px;
        }

        .user-status-option {
            flex: 1;
            position: relative;
        }

        .user-status-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .user-status-option label {
            height: 41px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            color: #737d8c;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-status-option input:checked+label {
            border-color: #df8339;
            background: #fff8f2;
            color: #c86520;
        }

        /*
        |--------------------------------------------------------------------------
        | BUTTON
        |--------------------------------------------------------------------------
        */

        .user-submit-button {
            border: 0;
            border-radius: 8px;
            padding: 10px 16px;
            background: linear-gradient(135deg,
                    #df8339,
                    #c35e1d);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        .user-cancel-button {
            border: 1px solid #dfe4eb;
            border-radius: 8px;
            padding: 10px 16px;
            background: #fff;
            color: #667085;
            font-size: 12px;
            font-weight: 600;
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORT
        |--------------------------------------------------------------------------
        */

        .user-import-info {
            padding: 12px 14px;
            border-radius: 10px;
            background: #f7f9fb;
            color: #667085;
            font-size: 11px;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .user-import-box {
            border: 1px dashed #d6dce4;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            background: #fafbfc;
        }

        .user-import-box-title {
            color: #344054;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .user-import-box-text {
            color: #98a2b3;
            font-size: 10px;
            margin-bottom: 10px;
        }

        .user-import-file {
            width: 100%;
            font-size: 11px;
        }

        .user-import-button {
            margin-top: 10px;
            border: 0;
            border-radius: 8px;
            padding: 9px 14px;
            background: #14223b;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
        }

        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        .user-delete-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 14px;
            border-radius: 15px;
            background: #fff0f0;
            color: #d33;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 23px;
        }

        .user-delete-title {
            color: #273449;
            font-size: 16px;
            font-weight: 800;
        }

        .user-delete-text {
            margin-top: 7px;
            color: #7b8491;
            font-size: 11px;
            line-height: 1.6;
        }

        /*
        |--------------------------------------------------------------------------
        | ALERT
        |--------------------------------------------------------------------------
        */

        .user-alert {
            border: 0;
            border-radius: 10px;
            font-size: 12px;
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media(max-width:1100px) {
            .user-stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media(max-width:800px) {
            .user-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .user-header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .user-header-actions .user-button {
                flex: 1;
            }

            .user-stat-grid {
                grid-template-columns: 1fr;
            }

            .user-filter {
                flex-wrap: wrap;
            }

            .user-search {
                min-width: 100%;
            }

            .user-filter-select {
                flex: 1;
            }

            .user-bulk-info {
                align-items: flex-start;
                flex-direction: column;
            }

            .user-bulk-button {
                width: 100%;
                justify-content: center;
            }
        }

        @media(max-width:550px) {
            .user-header-actions {
                flex-direction: column;
            }

            .user-header-actions .user-button {
                width: 100%;
            }

            .user-role-options {
                grid-template-columns: 1fr;
            }

            .user-pagination {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>

@endsection


@section('content')

    <div class="user-page">

        {{-- =========================================================
    ALERT SUCCESS
    ========================================================= --}}

        @if (session('success'))
            <div class="alert alert-success user-alert mb-3">
                <i class="bi bi-check-circle-fill me-1"></i>

                {{ session('success') }}
            </div>
        @endif


        {{-- =========================================================
    ALERT ERROR
    ========================================================= --}}

        @if ($errors->any())
            <div class="alert alert-danger user-alert mb-3">
                <i class="bi bi-exclamation-circle-fill me-1"></i>

                {{ $errors->first() }}
            </div>
        @endif


        {{-- =========================================================
    HEADER
    ========================================================= --}}

        <div class="user-header">

            <div>

                <h1 class="user-header-title">
                    Manajemen Pengguna
                </h1>

                <p class="user-header-description">
                    Kelola akses role pengguna SAMPERIN berdasarkan data pegawai.
                </p>

            </div>


            <div class="user-header-actions">

                {{-- IMPORT --}}

                <button type="button" class="user-button import" data-bs-toggle="modal" data-bs-target="#userImportModal">

                    <i class="bi bi-file-earmark-arrow-up"></i>

                    Import

                </button>


                {{-- TAMBAHKAN SEMUA --}}

                @if ($rolePegawai)
                    <form method="POST" action="{{ route('samperin.admin.users.assign-default-pegawai') }}"
                        onsubmit="return confirm('Tambahkan role Pegawai kepada seluruh pegawai aktif?');">

                        @csrf

                        <button type="submit" class="user-button default">

                            <i class="bi bi-people-fill"></i>

                            Tambahkan Semua Pegawai

                        </button>

                    </form>
                @endif


                {{-- TAMBAH SATU --}}

                <button type="button" class="user-button primary" data-bs-toggle="modal" data-bs-target="#userCreateModal">

                    <i class="bi bi-plus-lg"></i>

                    Tambah Pengguna

                </button>

            </div>

        </div>


        {{-- =========================================================
    STATISTIK
    ========================================================= --}}

        <div class="user-stat-grid">

            {{-- TOTAL PEGAWAI --}}

            <div class="user-stat">

                <div class="user-stat-icon">

                    <i class="bi bi-people-fill"></i>

                </div>

                <div>

                    <div class="user-stat-label">
                        Total Pegawai
                    </div>

                    <div class="user-stat-number">
                        {{ number_format($totalPegawai) }}
                    </div>

                </div>

            </div>


            {{-- TOTAL PENGGUNA --}}

            <div class="user-stat">

                <div class="user-stat-icon">

                    <i class="bi bi-person-badge-fill"></i>

                </div>

                <div>

                    <div class="user-stat-label">
                        Total Pengguna
                    </div>

                    <div class="user-stat-number">
                        {{ number_format($totalPengguna) }}
                    </div>

                </div>

            </div>


            {{-- PENGGUNA AKTIF --}}

            <div class="user-stat">

                <div class="user-stat-icon">

                    <i class="bi bi-person-check-fill"></i>

                </div>

                <div>

                    <div class="user-stat-label">
                        Pengguna Aktif
                    </div>

                    <div class="user-stat-number">
                        {{ number_format($penggunaAktif) }}
                    </div>

                </div>

            </div>


            {{-- BELUM MENJADI PENGGUNA --}}

            <div class="user-stat">

                <div class="user-stat-icon">

                    <i class="bi bi-person-plus-fill"></i>

                </div>

                <div>

                    <div class="user-stat-label">
                        Belum Memiliki Role
                    </div>

                    <div class="user-stat-number">
                        {{ number_format($belumPengguna) }}
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
    PANEL
    ========================================================= --}}

        <div class="user-panel">


            {{-- =====================================================
        FILTER
        ===================================================== --}}

            <div class="user-panel-header">

                <form method="GET" action="{{ route('samperin.admin.users.index') }}" class="user-filter">

                    <div class="user-search">

                        <i class="bi bi-search"></i>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari NIP, NIK, nama atau email...">

                    </div>


                    <select name="role" class="user-filter-select" onchange="this.form.submit()">

                        <option value="">
                            Semua Role
                        </option>

                        @foreach ($roles as $role)
                            <option value="{{ $role->role_uid }}"
                                {{ request('role') === $role->role_uid ? 'selected' : '' }}>
                                {{ $role->role_nama }}
                            </option>
                        @endforeach

                    </select>


                    <select name="status" class="user-filter-select" onchange="this.form.submit()">

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


            {{-- =====================================================
        INFO BULK
        ===================================================== --}}

            @if ($rolePegawai && $belumPengguna > 0)
                <div class="user-bulk-info">

                    <div>

                        <i class="bi bi-info-circle-fill me-1"></i>

                        Masih ada
                        <strong>
                            {{ number_format($belumPengguna) }} pegawai
                        </strong>
                        yang belum memiliki role.

                    </div>

                    <form method="POST" action="{{ route('samperin.admin.users.assign-default-pegawai') }}"
                        onsubmit="return confirm('Tambahkan role Pegawai kepada seluruh pegawai aktif yang belum memiliki role Pegawai?');">

                        @csrf

                        <button type="submit" class="user-bulk-button">

                            <i class="bi bi-person-check-fill"></i>

                            Tambahkan Role Pegawai

                        </button>

                    </form>

                </div>
            @endif


            {{-- =====================================================
        TABLE
        ===================================================== --}}

            <div class="user-table-wrapper">

                <table class="user-table">

                    <thead>

                        <tr>

                            <th>
                                Pegawai
                            </th>

                            <th>
                                NIP
                            </th>

                            <th>
                                Role
                            </th>

                            <th>
                                Status
                            </th>

                            <th style="text-align:right;">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($users as $user)

                            <tr>

                                {{-- =================================================
                            PEGAWAI
                            ================================================= --}}

                                <td>

                                    <div class="user-info">

                                        <div class="user-avatar">

                                            {{ strtoupper(substr($user->user_nama ?? 'U', 0, 1)) }}

                                        </div>


                                        <div>

                                            <div class="user-name">

                                                {{ $user->user_nama }}

                                            </div>


                                            <div class="user-email">

                                                {{ $user->user_email ?: 'Tidak ada email' }}

                                            </div>


                                            @if ($user->user_nik)
                                                <div class="user-nik">

                                                    NIK:
                                                    {{ $user->user_nik }}

                                                </div>
                                            @endif


                                            <div class="user-uid">

                                                {{ $user->user_uid }}

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- =================================================
                            NIP
                            ================================================= --}}

                                <td>

                                    <span class="user-nip">

                                        {{ $user->user_nip ?: '-' }}

                                    </span>

                                </td>


                                {{-- =================================================
                            ROLE
                            ================================================= --}}

                                <td>

                                    @if ($user->roles->count())
                                        <div class="user-role-list">

                                            @foreach ($user->roles as $role)
                                                <span class="user-role">

                                                    {{ $role->role_nama }}

                                                </span>
                                            @endforeach

                                        </div>
                                    @else
                                        <span class="user-no-role">
                                            Belum ada role
                                        </span>
                                    @endif

                                </td>


                                {{-- =================================================
                            STATUS
                            ================================================= --}}

                                <td>

                                    @if ((int) $user->user_status === 1)
                                        <span class="user-status active">

                                            <span class="user-status-dot"></span>

                                            Aktif

                                        </span>
                                    @else
                                        <span class="user-status inactive">

                                            <span class="user-status-dot"></span>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                {{-- =================================================
                            ACTION
                            ================================================= --}}

                                <td>

                                    <div class="user-actions">

                                        {{-- EDIT ROLE --}}

                                        <button type="button" class="user-action-button" title="Kelola Role"
                                            data-bs-toggle="modal" data-bs-target="#userEditModal"
                                            data-user-uid="{{ $user->user_uid }}" data-user-name="{{ $user->user_nama }}"
                                            data-user-nip="{{ $user->user_nip }}"
                                            data-user-email="{{ $user->user_email }}"
                                            data-user-roles="{{ $user->roles->pluck('role_uid')->implode(',') }}">

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        {{-- STATUS --}}

                                        <button type="button" class="user-action-button warning"
                                            title="{{ (int) $user->user_status === 1 ? 'Nonaktifkan' : 'Aktifkan' }}"
                                            data-bs-toggle="modal" data-bs-target="#userStatusModal"
                                            data-user-uid="{{ $user->user_uid }}"
                                            data-user-name="{{ $user->user_nama }}"
                                            data-user-status="{{ $user->user_status }}">

                                            @if ((int) $user->user_status === 1)
                                                <i class="bi bi-pause-circle"></i>
                                            @else
                                                <i class="bi bi-play-circle"></i>
                                            @endif

                                        </button>


                                        {{-- DELETE ROLE --}}

                                        <button type="button" class="user-action-button danger"
                                            title="Hapus dari Pengguna" data-bs-toggle="modal"
                                            data-bs-target="#userDeleteModal" data-user-uid="{{ $user->user_uid }}"
                                            data-user-name="{{ $user->user_nama }}">

                                            <i class="bi bi-trash3"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5">

                                    <div class="user-empty">

                                        <div class="user-empty-icon">

                                            <i class="bi bi-people"></i>

                                        </div>

                                        <div class="user-empty-title">

                                            Belum ada pengguna

                                        </div>

                                        <div class="user-empty-text">

                                            Belum ada pegawai yang memiliki role SAMPERIN.

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =====================================================
        PAGINATION
        ===================================================== --}}

            @if ($users->hasPages())
                <div class="user-pagination">

                    <div class="user-pagination-info">

                        Menampilkan

                        {{ $users->firstItem() }}

                        -

                        {{ $users->lastItem() }}

                        dari

                        {{ $users->total() }}

                        pengguna

                    </div>


                    <div>

                        {{ $users->links() }}

                    </div>

                </div>
            @endif

        </div>

    </div>


    {{-- =============================================================
MODAL TAMBAH PENGGUNA
============================================================= --}}

    <div class="modal fade user-modal" id="userCreateModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <div class="modal-title">
                            Tambah Pengguna
                        </div>

                        <div class="modal-subtitle">
                            Tambahkan role kepada pegawai yang sudah terdaftar.
                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <form method="POST" action="{{ route('samperin.admin.users.store') }}">

                    @csrf

                    <div class="modal-body">


                        {{-- PEGAWAI --}}

                        <div class="user-form-group">

                            <label class="user-form-label">

                                Pegawai

                            </label>


                            @if ($pegawai->count())

                                <select name="user_uid" id="create-user-select" class="pegawai-select" required>

                                    <option value="">
                                        Pilih pegawai...
                                    </option>

                                    @foreach ($pegawai as $item)
                                        <option value="{{ $item->user_uid }}" data-nip="{{ $item->user_nip }}"
                                            data-nik="{{ $item->user_nik }}" data-name="{{ $item->user_nama }}"
                                            data-email="{{ $item->user_email }}">

                                            {{ $item->user_nama }}

                                            —
                                            {{ $item->user_nip ?: ($item->user_nik ?: 'Tanpa identitas') }}

                                        </option>
                                    @endforeach

                                </select>


                                <div class="pegawai-preview" id="pegawaiPreview">

                                    <div class="pegawai-preview-name" id="pegawaiPreviewName"></div>

                                    <div class="pegawai-preview-detail" id="pegawaiPreviewDetail"></div>

                                </div>

                                <div class="user-form-help">

                                    Hanya pegawai aktif yang belum memiliki role
                                    yang ditampilkan.

                                </div>
                            @else
                                <div class="alert alert-warning user-alert">

                                    <i class="bi bi-info-circle me-1"></i>

                                    Semua pegawai aktif saat ini sudah memiliki
                                    role.

                                </div>

                            @endif

                        </div>


                        {{-- ROLE --}}

                        <div class="user-form-group mb-0">

                            <label class="user-form-label">

                                Role

                            </label>


                            <div class="user-role-options">

                                @foreach ($roles as $role)
                                    <div class="user-role-option">

                                        <input type="checkbox" id="create-role-{{ $role->role_uid }}" name="roles[]"
                                            value="{{ $role->role_uid }}"
                                            {{ $rolePegawai && $role->role_uid === $rolePegawai->role_uid ? 'checked' : '' }}>

                                        <label for="create-role-{{ $role->role_uid }}">

                                            <strong>

                                                {{ $role->role_nama }}

                                            </strong>

                                            <span>

                                                {{ $role->role_slug }}

                                            </span>

                                        </label>

                                    </div>
                                @endforeach

                            </div>


                            <div class="user-form-help">

                                Role <strong>Pegawai</strong> otomatis dipilih
                                sebagai role default.

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="user-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>


                        <button type="submit" class="user-submit-button"
                            {{ $pegawai->count() === 0 ? 'disabled' : '' }}>

                            <i class="bi bi-person-plus me-1"></i>

                            Tambahkan Pengguna

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =============================================================
MODAL EDIT ROLE
============================================================= --}}

    <div class="modal fade user-modal" id="userEditModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <div class="modal-title">
                            Kelola Role Pengguna
                        </div>

                        <div class="modal-subtitle">
                            Atur role yang dimiliki pegawai.
                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <form method="POST" id="userEditForm">

                    @csrf

                    @method('PUT')


                    <div class="modal-body">


                        {{-- IDENTITAS --}}

                        <div class="pegawai-preview show" style="margin-bottom:16px;">

                            <div class="pegawai-preview-name" id="edit-user-name"></div>

                            <div class="pegawai-preview-detail" id="edit-user-detail"></div>

                        </div>


                        {{-- ROLE --}}

                        <div class="user-form-group mb-0">

                            <label class="user-form-label">

                                Role

                            </label>


                            <div class="user-role-options">

                                @foreach ($roles as $role)
                                    <div class="user-role-option">

                                        <input type="checkbox" class="edit-role-checkbox"
                                            id="edit-role-{{ $role->role_uid }}" name="roles[]"
                                            value="{{ $role->role_uid }}">

                                        <label for="edit-role-{{ $role->role_uid }}">

                                            <strong>

                                                {{ $role->role_nama }}

                                            </strong>

                                            <span>

                                                {{ $role->role_slug }}

                                            </span>

                                        </label>

                                    </div>
                                @endforeach

                            </div>


                            <div class="user-form-help">

                                Minimal satu role harus dipilih.

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="user-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>


                        <button type="submit" class="user-submit-button">

                            <i class="bi bi-check-lg me-1"></i>

                            Simpan Role

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =============================================================
MODAL STATUS
============================================================= --}}

    <div class="modal fade user-modal" id="userStatusModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-sm">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <div class="modal-title">
                            Ubah Status
                        </div>

                        <div class="modal-subtitle">
                            Konfirmasi perubahan status pengguna.
                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <form method="POST" id="userStatusForm">

                    @csrf

                    @method('PATCH')


                    <div class="modal-body text-center">

                        <div class="user-delete-icon"
                            style="
                            background:#fff8e8;
                            color:#b87800;
                        ">

                            <i class="bi bi-arrow-repeat"></i>

                        </div>


                        <div class="user-delete-title" id="userStatusTitle">

                            Ubah status pengguna?

                        </div>


                        <div class="user-delete-text" id="userStatusText"></div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="user-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>


                        <button type="submit" class="user-submit-button">

                            Ya, Ubah

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =============================================================
MODAL DELETE
============================================================= --}}

    <div class="modal fade user-modal" id="userDeleteModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-sm">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <div class="modal-title">
                            Hapus Pengguna
                        </div>

                        <div class="modal-subtitle">
                            Data pegawai tidak akan dihapus.
                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <form method="POST" id="userDeleteForm">

                    @csrf

                    @method('DELETE')


                    <div class="modal-body text-center">

                        <div class="user-delete-icon">

                            <i class="bi bi-trash3"></i>

                        </div>


                        <div class="user-delete-title">

                            Hapus pengguna ini?

                        </div>


                        <div class="user-delete-text">

                            Role pengguna

                            <strong id="userDeleteName"></strong>

                            akan dihapus.

                            <br>

                            Data pegawai tetap tersimpan.

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="user-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>


                        <button type="submit" class="user-submit-button" style="background:#d33;">

                            Ya, Hapus Role

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =============================================================
MODAL IMPORT
============================================================= --}}

    <div class="modal fade user-modal" id="userImportModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <div class="modal-title">
                            Import Pegawai
                        </div>

                        <div class="modal-subtitle">
                            Import data pegawai ke SAMPERIN.
                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>


                <div class="modal-body">


                    <div class="user-import-info">

                        <i class="bi bi-info-circle-fill me-1"></i>

                        Import digunakan untuk memasukkan atau memperbarui
                        data pegawai pada tabel

                        <strong>samperin_user</strong>.

                        Password tidak dibuat atau diubah melalui import.

                    </div>


                    {{-- SQL --}}

                    <div class="user-import-box">

                        <div class="user-import-box-title">

                            <i class="bi bi-database me-1"></i>

                            Import SQL

                        </div>

                        <div class="user-import-box-text">

                            Import data dari dump SQL yang memiliki tabel
                            <strong>samperin_user</strong>.

                        </div>


                        <form method="POST" action="{{ route('samperin.admin.users.import.sql') }}"
                            enctype="multipart/form-data">

                            @csrf

                            <input type="file" name="file" class="form-control user-import-file" accept=".sql"
                                required>


                            <button type="submit" class="user-import-button">

                                <i class="bi bi-upload me-1"></i>

                                Import SQL

                            </button>

                        </form>

                    </div>


                    {{-- EXCEL --}}

                    <div class="user-import-box">

                        <div class="user-import-box-title">

                            <i class="bi bi-file-earmark-excel me-1"></i>

                            Import Excel

                        </div>

                        <div class="user-import-box-text">

                            Format:
                            XLSX, XLS atau CSV.

                        </div>


                        <form method="POST" action="{{ route('samperin.admin.users.import.excel') }}"
                            enctype="multipart/form-data">

                            @csrf

                            <input type="file" name="file" class="form-control user-import-file"
                                accept=".xlsx,.xls,.csv" required>


                            <button type="submit" class="user-import-button">

                                <i class="bi bi-upload me-1"></i>

                                Import Excel

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | BASE URL
            |--------------------------------------------------------------------------
            */

            const userBaseUrl = @json(url('/admin/users'));


            /*
            |--------------------------------------------------------------------------
            | CREATE - PEGAWAI PREVIEW
            |--------------------------------------------------------------------------
            */

            const createUserSelect =
                document.getElementById('create-user-select');

            const pegawaiPreview =
                document.getElementById('pegawaiPreview');

            const pegawaiPreviewName =
                document.getElementById('pegawaiPreviewName');

            const pegawaiPreviewDetail =
                document.getElementById('pegawaiPreviewDetail');


            if (createUserSelect) {

                createUserSelect.addEventListener('change', function() {

                    const option =
                        this.options[this.selectedIndex];


                    if (!this.value) {

                        pegawaiPreview.classList.remove('show');

                        pegawaiPreviewName.textContent = '';

                        pegawaiPreviewDetail.textContent = '';

                        return;

                    }


                    const name =
                        option.dataset.name || '';

                    const nip =
                        option.dataset.nip || '';

                    const nik =
                        option.dataset.nik || '';

                    const email =
                        option.dataset.email || '';


                    pegawaiPreviewName.textContent =
                        name;


                    const details = [];


                    if (nip) {

                        details.push(
                            'NIP: ' + nip
                        );

                    }


                    if (nik) {

                        details.push(
                            'NIK: ' + nik
                        );

                    }


                    if (email) {

                        details.push(
                            email
                        );

                    }


                    pegawaiPreviewDetail.textContent =
                        details.join(' • ');


                    pegawaiPreview.classList.add('show');

                });

            }


            /*
            |--------------------------------------------------------------------------
            | EDIT ROLE
            |--------------------------------------------------------------------------
            */

            const editModal =
                document.getElementById('userEditModal');


            if (editModal) {

                editModal.addEventListener(
                    'show.bs.modal',
                    function(event) {

                        const button =
                            event.relatedTarget;


                        if (!button) {
                            return;
                        }


                        const uid =
                            button.dataset.userUid || '';


                        const nip =
                            button.dataset.userNip || '';


                        const name =
                            button.dataset.userName || '';


                        const email =
                            button.dataset.userEmail || '';


                        const roles =
                            (
                                button.dataset.userRoles || ''
                            )
                            .split(',')
                            .filter(Boolean);


                        /*
                        |--------------------------------------------------------------------------
                        | FORM ACTION
                        |--------------------------------------------------------------------------
                        */

                        document.getElementById(
                                'userEditForm'
                            ).action =
                            userBaseUrl +
                            '/' +
                            encodeURIComponent(uid);


                        /*
                        |--------------------------------------------------------------------------
                        | IDENTITAS
                        |--------------------------------------------------------------------------
                        */

                        document.getElementById(
                                'edit-user-name'
                            ).textContent =
                            name;


                        const details = [];


                        if (nip) {

                            details.push(
                                'NIP: ' + nip
                            );

                        }


                        if (email) {

                            details.push(
                                email
                            );

                        }


                        document.getElementById(
                                'edit-user-detail'
                            ).textContent =
                            details.join(' • ');


                        /*
                        |--------------------------------------------------------------------------
                        | RESET ROLE
                        |--------------------------------------------------------------------------
                        */

                        document
                            .querySelectorAll(
                                '.edit-role-checkbox'
                            )
                            .forEach(
                                function(checkbox) {

                                    checkbox.checked =
                                        roles.includes(
                                            checkbox.value
                                        );

                                }
                            );

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            const statusModal =
                document.getElementById(
                    'userStatusModal'
                );


            if (statusModal) {

                statusModal.addEventListener(
                    'show.bs.modal',
                    function(event) {

                        const button =
                            event.relatedTarget;


                        if (!button) {
                            return;
                        }


                        const uid =
                            button.dataset.userUid || '';


                        const name =
                            button.dataset.userName || '';


                        const status =
                            button.dataset.userStatus || '0';


                        /*
                        |--------------------------------------------------------------------------
                        | FORM ACTION
                        |--------------------------------------------------------------------------
                        */

                        document.getElementById(
                                'userStatusForm'
                            ).action =
                            userBaseUrl +
                            '/' +
                            encodeURIComponent(uid) +
                            '/status';


                        const title =
                            document.getElementById(
                                'userStatusTitle'
                            );


                        const text =
                            document.getElementById(
                                'userStatusText'
                            );


                        if (status === '1') {

                            title.innerText =
                                'Nonaktifkan pengguna?';


                            text.innerHTML =
                                'Pengguna <strong>' +
                                escapeHtml(name) +
                                '</strong> akan dinonaktifkan.';

                        } else {

                            title.innerText =
                                'Aktifkan pengguna?';


                            text.innerHTML =
                                'Pengguna <strong>' +
                                escapeHtml(name) +
                                '</strong> akan diaktifkan.';

                        }

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            const deleteModal =
                document.getElementById(
                    'userDeleteModal'
                );


            if (deleteModal) {

                deleteModal.addEventListener(
                    'show.bs.modal',
                    function(event) {

                        const button =
                            event.relatedTarget;


                        if (!button) {
                            return;
                        }


                        const uid =
                            button.dataset.userUid || '';


                        const name =
                            button.dataset.userName || '';


                        /*
                        |--------------------------------------------------------------------------
                        | FORM ACTION
                        |--------------------------------------------------------------------------
                        */

                        document.getElementById(
                                'userDeleteForm'
                            ).action =
                            userBaseUrl +
                            '/' +
                            encodeURIComponent(uid);


                        /*
                        |--------------------------------------------------------------------------
                        | NAME
                        |--------------------------------------------------------------------------
                        */

                        document.getElementById(
                                'userDeleteName'
                            ).innerText =
                            name;

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | RESET CREATE MODAL
            |--------------------------------------------------------------------------
            */

            const createModal =
                document.getElementById(
                    'userCreateModal'
                );


            if (createModal) {

                createModal.addEventListener(
                    'hidden.bs.modal',
                    function() {

                        if (createUserSelect) {

                            createUserSelect.value =
                                '';

                        }


                        if (pegawaiPreview) {

                            pegawaiPreview.classList.remove(
                                'show'
                            );

                        }


                        if (pegawaiPreviewName) {

                            pegawaiPreviewName.textContent =
                                '';

                        }


                        if (pegawaiPreviewDetail) {

                            pegawaiPreviewDetail.textContent =
                                '';

                        }

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | ESCAPE HTML
            |--------------------------------------------------------------------------
            */

            function escapeHtml(value) {

                const div =
                    document.createElement('div');


                div.textContent =
                    value || '';


                return div.innerHTML;

            }

        });
    </script>

@endsection
