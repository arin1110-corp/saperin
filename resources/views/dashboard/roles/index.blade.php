@extends('dashboard.layouts.app')

@section('title', 'Manajemen Role')

@section('breadcrumb', 'Manajemen Role')

@section('header-title', 'Manajemen Role')

@section('page-style')

    <style>
        .role-page {
            width: 100%;
        }

        /*
                |--------------------------------------------------------------------------
                | HEADER
                |--------------------------------------------------------------------------
                */

        .role-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .role-header-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
        }

        .role-header-description {
            margin: 6px 0 0;
            color: #8993a3;
            font-size: 13px;
        }

        .role-add-button {
            border: 0;
            padding: 12px 18px;
            border-radius: 10px;

            background:
                linear-gradient(135deg,
                    #df8339,
                    #c35e1d);

            color: #fff;
            font-size: 13px;
            font-weight: 700;

            display: inline-flex;
            align-items: center;
            gap: 8px;

            cursor: pointer;

            box-shadow:
                0 7px 18px rgba(195, 94, 29, .18);

            transition: .15s ease;
        }

        .role-add-button:hover {
            transform: translateY(-1px);

            box-shadow:
                0 9px 22px rgba(195, 94, 29, .25);
        }


        /*
                |--------------------------------------------------------------------------
                | STAT
                |--------------------------------------------------------------------------
                */

        .role-stat-grid {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 14px;

            margin-bottom: 18px;
        }

        .role-stat {
            background: #fff;

            border:
                1px solid #e7eaf0;

            border-radius: 14px;

            padding: 17px;

            display: flex;

            align-items: center;

            gap: 13px;

            box-shadow:
                0 5px 18px rgba(20, 35, 60, .035);
        }

        .role-stat-icon {
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

        .role-stat-label {
            color: #929ba9;

            font-size: 11px;

            margin-bottom: 3px;
        }

        .role-stat-number {
            color: #273449;

            font-size: 21px;

            font-weight: 800;
        }


        /*
                |--------------------------------------------------------------------------
                | PANEL
                |--------------------------------------------------------------------------
                */

        .role-panel {
            background: #fff;

            border:
                1px solid #e6eaf0;

            border-radius: 16px;

            overflow: hidden;

            box-shadow:
                0 8px 25px rgba(20, 35, 60, .04);
        }

        .role-panel-header {
            padding: 17px 19px;

            border-bottom:
                1px solid #edf0f4;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;
        }


        /*
                |--------------------------------------------------------------------------
                | SEARCH
                |--------------------------------------------------------------------------
                */

        .role-filter {
            display: flex;

            align-items: center;

            gap: 8px;

            flex: 1;

            max-width: 520px;
        }

        .role-search {
            position: relative;

            flex: 1;
        }

        .role-search i {
            position: absolute;

            left: 12px;
            top: 50%;

            transform:
                translateY(-50%);

            color: #9aa3b0;

            font-size: 15px;
        }

        .role-search input {
            width: 100%;

            height: 40px;

            padding:
                0 12px 0 35px;

            border:
                1px solid #dfe4eb;

            border-radius: 9px;

            outline: none;

            color: #344054;

            font-size: 12px;

            transition: .15s ease;
        }

        .role-search input:focus {
            border-color: #df8339;

            box-shadow:
                0 0 0 3px rgba(223, 131, 57, .08);
        }

        .role-filter-select {
            height: 40px;

            border:
                1px solid #dfe4eb;

            border-radius: 9px;

            padding: 0 11px;

            color: #4b5565;

            background: #fff;

            font-size: 12px;

            outline: none;
        }


        /*
                |--------------------------------------------------------------------------
                | TABLE
                |--------------------------------------------------------------------------
                */

        .role-table-wrapper {
            width: 100%;

            overflow-x: auto;
        }

        .role-table {
            width: 100%;

            border-collapse: collapse;
        }

        .role-table th {
            padding:
                13px 18px;

            background: #fafbfc;

            color: #929aa6;

            font-size: 10px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .7px;

            text-align: left;

            white-space: nowrap;

            border-bottom:
                1px solid #edf0f4;
        }

        .role-table td {
            padding:
                15px 18px;

            border-bottom:
                1px solid #f0f2f5;

            color: #475467;

            font-size: 13px;

            vertical-align: middle;
        }

        .role-table tr:last-child td {
            border-bottom: 0;
        }

        .role-table tbody tr:hover {
            background: #fcfcfd;
        }


        /*
                |--------------------------------------------------------------------------
                | ROLE NAME
                |--------------------------------------------------------------------------
                */

        .role-name {
            color: #273449;

            font-size: 14px;

            font-weight: 750;
        }

        .role-uid {
            margin-top: 4px;

            color: #a0a8b4;

            font-size: 10px;

            font-family: monospace;
        }

        .role-slug {
            display: inline-block;

            padding:
                5px 8px;

            border-radius: 6px;

            background: #f3f5f8;

            color: #6d7683;

            font-family: monospace;

            font-size: 10px;
        }


        /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

        .role-status {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding:
                6px 9px;

            border-radius: 20px;

            font-size: 10px;

            font-weight: 700;
        }

        .role-status.active {
            color: #247a4b;

            background: #eaf8f0;
        }

        .role-status.inactive {
            color: #7b8491;

            background: #f0f2f5;
        }

        .role-status-dot {
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

        .role-actions {
            display: flex;

            align-items: center;

            justify-content: flex-end;

            gap: 6px;
        }

        .role-action-button {
            width: 33px;
            height: 33px;

            border:
                1px solid #e2e6eb;

            border-radius: 8px;

            background: #fff;

            color: #737d8c;

            display: flex;

            align-items: center;

            justify-content: center;

            cursor: pointer;

            transition: .15s ease;
        }

        .role-action-button:hover {
            border-color: #cdd3db;

            background: #f8f9fb;

            color: #273449;
        }

        .role-action-button.warning:hover {
            background: #fff8e8;

            color: #b87800;

            border-color: #efd99d;
        }

        .role-action-button.danger:hover {
            background: #fff1f1;

            color: #d33;

            border-color: #efcaca;
        }


        /*
                |--------------------------------------------------------------------------
                | EMPTY
                |--------------------------------------------------------------------------
                */

        .role-empty {
            padding: 55px 20px;

            text-align: center;
        }

        .role-empty-icon {
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

        .role-empty-title {
            color: #4b5565;

            font-size: 14px;

            font-weight: 700;
        }

        .role-empty-text {
            margin-top: 4px;

            color: #9aa2ad;

            font-size: 11px;
        }


        /*
                |--------------------------------------------------------------------------
                | MODAL
                |--------------------------------------------------------------------------
                */

        .role-modal .modal-content {
            border: 0;

            border-radius: 17px;

            overflow: hidden;

            box-shadow:
                0 25px 70px rgba(0, 0, 0, .20);
        }

        .role-modal .modal-header {
            padding:
                18px 21px;

            border: 0;

            background:
                linear-gradient(135deg,
                    #14223b,
                    #1d3558);

            color: #fff;
        }

        .role-modal .modal-title {
            font-size: 16px;

            font-weight: 800;
        }

        .role-modal .modal-subtitle {
            margin-top: 4px;

            color:
                rgba(255, 255, 255, .52);

            font-size: 10px;
        }

        .role-modal .btn-close {
            filter:
                brightness(0) invert(1);

            opacity: .7;
        }

        .role-modal .modal-body {
            padding: 21px;
        }

        .role-modal .modal-footer {
            padding:
                14px 21px;

            border-top:
                1px solid #edf0f4;
        }

        .role-form-label {
            display: block;

            margin-bottom: 7px;

            color: #475467;

            font-size: 11px;

            font-weight: 700;
        }

        .role-form-control {
            width: 100%;

            height: 42px;

            border:
                1px solid #dfe4eb;

            border-radius: 9px;

            padding:
                0 12px;

            color: #344054;

            font-size: 12px;

            outline: none;
        }

        .role-form-control:focus {
            border-color: #df8339;

            box-shadow:
                0 0 0 3px rgba(223, 131, 57, .08);
        }

        .role-form-group {
            margin-bottom: 16px;
        }

        .role-form-help {
            margin-top: 6px;

            color: #98a2b3;

            font-size: 10px;
        }

        .role-status-options {
            display: flex;

            gap: 8px;
        }

        .role-status-option {
            flex: 1;

            position: relative;
        }

        .role-status-option input {
            position: absolute;

            opacity: 0;

            pointer-events: none;
        }

        .role-status-option label {
            display: flex;

            align-items: center;

            justify-content: center;

            height: 42px;

            border:
                1px solid #dfe4eb;

            border-radius: 9px;

            color: #737d8c;

            font-size: 12px;

            font-weight: 600;

            cursor: pointer;

            transition: .15s ease;
        }

        .role-status-option input:checked+label {
            border-color: #df8339;

            background: #fff8f2;

            color: #c86520;
        }

        .role-submit-button {
            border: 0;

            border-radius: 8px;

            padding:
                10px 16px;

            background:
                linear-gradient(135deg,
                    #df8339,
                    #c35e1d);

            color: #fff;

            font-size: 12px;

            font-weight: 700;
        }

        .role-cancel-button {
            border:
                1px solid #dfe4eb;

            border-radius: 8px;

            padding:
                10px 16px;

            background: #fff;

            color: #667085;

            font-size: 12px;

            font-weight: 600;
        }


        /*
                |--------------------------------------------------------------------------
                | DELETE MODAL
                |--------------------------------------------------------------------------
                */

        .role-delete-icon {
            width: 55px;
            height: 55px;

            margin: 0 auto 13px;

            border-radius: 15px;

            background: #fff0f0;

            color: #d33;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 23px;
        }

        .role-delete-title {
            color: #273449;

            font-size: 16px;

            font-weight: 800;
        }

        .role-delete-text {
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

        .role-alert {
            border: 0;

            border-radius: 10px;

            font-size: 12px;
        }


        /*
                |--------------------------------------------------------------------------
                | RESPONSIVE
                |--------------------------------------------------------------------------
                */

        @media(max-width:800px) {

            .role-stat-grid {
                grid-template-columns:
                    1fr;
            }

            .role-header {
                align-items: flex-start;

                flex-direction: column;
            }

            .role-add-button {
                width: 100%;

                justify-content: center;
            }

            .role-panel-header {
                align-items: stretch;

                flex-direction: column;
            }

            .role-filter {
                max-width: none;

                width: 100%;
            }

        }


        @media(max-width:550px) {

            .role-filter {
                flex-direction: column;
            }

            .role-search,
            .role-filter-select {
                width: 100%;
            }

            .role-table th,
            .role-table td {
                padding-left: 13px;
                padding-right: 13px;
            }

            .role-name {
                font-size: 13px;
            }

            .role-uid {
                font-size: 9px;
            }

            .role-table td {
                font-size: 12px;
            }

        }
    </style>

@endsection


@section('content')

    <div class="role-page">


        {{-- =====================================================
             ALERT SUCCESS
        ====================================================== --}}

        @if (session('success'))
            <div class="alert alert-success role-alert mb-3">

                <i class="bi bi-check-circle-fill me-1"></i>

                {{ session('success') }}

            </div>
        @endif


        {{-- =====================================================
             ALERT ERROR
        ====================================================== --}}

        @if ($errors->any())
            <div class="alert alert-danger role-alert mb-3">

                <i class="bi bi-exclamation-circle-fill me-1"></i>

                {{ $errors->first() }}

            </div>
        @endif


        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="role-header">

            <div>

                <h1 class="role-header-title">
                    Manajemen Role
                </h1>

                <p class="role-header-description">
                    Kelola role dan hak akses pengguna SAMPERIN.
                </p>

            </div>


            <button type="button" class="role-add-button" data-bs-toggle="modal" data-bs-target="#roleCreateModal">

                <i class="bi bi-plus-lg"></i>

                Tambah Role

            </button>

        </div>


        {{-- =====================================================
             STATISTIK
        ====================================================== --}}

        <div class="role-stat-grid">


            <div class="role-stat">

                <div class="role-stat-icon">

                    <i class="bi bi-person-badge-fill"></i>

                </div>

                <div>

                    <div class="role-stat-label">
                        Total Role
                    </div>

                    <div class="role-stat-number">
                        {{ $totalRole }}
                    </div>

                </div>

            </div>


            <div class="role-stat">

                <div class="role-stat-icon">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <div>

                    <div class="role-stat-label">
                        Role Aktif
                    </div>

                    <div class="role-stat-number">
                        {{ $roleAktif }}
                    </div>

                </div>

            </div>


            <div class="role-stat">

                <div class="role-stat-icon">

                    <i class="bi bi-slash-circle-fill"></i>

                </div>

                <div>

                    <div class="role-stat-label">
                        Role Nonaktif
                    </div>

                    <div class="role-stat-number">
                        {{ $roleNonaktif }}
                    </div>

                </div>

            </div>


        </div>


        {{-- =====================================================
             PANEL
        ====================================================== --}}

        <div class="role-panel">


            {{-- FILTER --}}

            <div class="role-panel-header">

                <form method="GET" action="{{ route('samperin.admin.roles.index') }}" class="role-filter">

                    <div class="role-search">

                        <i class="bi bi-search"></i>

                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau slug role...">

                    </div>


                    <select name="status" class="role-filter-select" onchange="this.form.submit()">

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


            {{-- =================================================
                 TABLE
            ================================================== --}}

            <div class="role-table-wrapper">

                <table class="role-table">

                    <thead>

                        <tr>

                            <th>
                                Role
                            </th>

                            <th>
                                Slug
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

                        @forelse($roles as $role)
                            <tr>


                                {{-- ROLE --}}

                                <td>

                                    <div class="role-name">

                                        {{ $role->role_nama }}

                                    </div>

                                    <div class="role-uid">

                                        {{ $role->role_uid }}

                                    </div>

                                </td>


                                {{-- SLUG --}}

                                <td>

                                    <span class="role-slug">

                                        {{ $role->role_slug }}

                                    </span>

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if ((int) $role->role_status === 1)
                                        <span class="role-status active">

                                            <span class="role-status-dot"></span>

                                            Aktif

                                        </span>
                                    @else
                                        <span class="role-status inactive">

                                            <span class="role-status-dot"></span>

                                            Nonaktif

                                        </span>
                                    @endif

                                </td>


                                {{-- ACTION --}}

                                <td>

                                    <div class="role-actions">


                                        {{-- EDIT --}}

                                        <button type="button" class="role-action-button" title="Edit"
                                            data-bs-toggle="modal" data-bs-target="#roleEditModal"
                                            data-role-uid="{{ $role->role_uid }}" data-role-name="{{ $role->role_nama }}"
                                            data-role-slug="{{ $role->role_slug }}"
                                            data-role-status="{{ $role->role_status }}">

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        {{-- STATUS --}}

                                        <button type="button" class="role-action-button warning"
                                            title="{{ (int) $role->role_status === 1 ? 'Nonaktifkan' : 'Aktifkan' }}"
                                            data-bs-toggle="modal" data-bs-target="#roleStatusModal"
                                            data-role-uid="{{ $role->role_uid }}" data-role-name="{{ $role->role_nama }}"
                                            data-role-status="{{ $role->role_status }}">

                                            @if ((int) $role->role_status === 1)
                                                <i class="bi bi-pause-circle"></i>
                                            @else
                                                <i class="bi bi-play-circle"></i>
                                            @endif

                                        </button>


                                        {{-- DELETE --}}

                                        <button type="button" class="role-action-button danger" title="Hapus"
                                            data-bs-toggle="modal" data-bs-target="#roleDeleteModal"
                                            data-role-uid="{{ $role->role_uid }}" data-role-name="{{ $role->role_nama }}">

                                            <i class="bi bi-trash3"></i>

                                        </button>


                                    </div>

                                </td>


                            </tr>

                        @empty

                            <tr>

                                <td colspan="4">

                                    <div class="role-empty">

                                        <div class="role-empty-icon">

                                            <i class="bi bi-person-badge"></i>

                                        </div>

                                        <div class="role-empty-title">

                                            Belum ada role

                                        </div>

                                        <div class="role-empty-text">

                                            Silakan tambahkan role baru.

                                        </div>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>



    {{-- =========================================================
         MODAL TAMBAH ROLE
    ========================================================== --}}

    <div class="modal fade role-modal" id="roleCreateModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">


                <div class="modal-header">

                    <div>

                        <div class="modal-title">
                            Tambah Role
                        </div>

                        <div class="modal-subtitle">
                            Tambahkan role baru ke SAMPERIN.
                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <form method="POST" action="{{ route('samperin.admin.roles.store') }}">

                    @csrf


                    <div class="modal-body">


                        {{-- NAMA --}}

                        <div class="role-form-group">

                            <label class="role-form-label">
                                Nama Role
                            </label>

                            <input type="text" name="role_nama" class="role-form-control"
                                placeholder="Contoh: Administrator" required>

                        </div>


                        {{-- SLUG --}}

                        <div class="role-form-group">

                            <label class="role-form-label">
                                Slug
                            </label>

                            <input type="text" name="role_slug" class="role-form-control"
                                placeholder="Contoh: admin">

                            <div class="role-form-help">

                                Kosongkan untuk dibuat otomatis dari nama role.

                            </div>

                        </div>


                        {{-- STATUS --}}

                        <div class="role-form-group mb-0">

                            <label class="role-form-label">
                                Status
                            </label>


                            <div class="role-status-options">


                                <div class="role-status-option">

                                    <input type="radio" id="create-status-active" name="role_status" value="1"
                                        checked>

                                    <label for="create-status-active">
                                        Aktif
                                    </label>

                                </div>


                                <div class="role-status-option">

                                    <input type="radio" id="create-status-inactive" name="role_status" value="0">

                                    <label for="create-status-inactive">
                                        Nonaktif
                                    </label>

                                </div>


                            </div>

                        </div>


                    </div>


                    <div class="modal-footer">

                        <button type="button" class="role-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="role-submit-button">

                            Simpan Role

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- =========================================================
         MODAL EDIT ROLE
    ========================================================== --}}

    <div class="modal fade role-modal" id="roleEditModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">


                <div class="modal-header">

                    <div>

                        <div class="modal-title">
                            Edit Role
                        </div>

                        <div class="modal-subtitle">
                            Perbarui informasi role.
                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <form method="POST" id="roleEditForm">

                    @csrf

                    @method('PUT')


                    <div class="modal-body">


                        <div class="role-form-group">

                            <label class="role-form-label">
                                Nama Role
                            </label>

                            <input type="text" name="role_nama" id="edit-role-name" class="role-form-control"
                                required>

                        </div>


                        <div class="role-form-group">

                            <label class="role-form-label">
                                Slug
                            </label>

                            <input type="text" name="role_slug" id="edit-role-slug" class="role-form-control">

                        </div>


                        <div class="role-form-group mb-0">

                            <label class="role-form-label">
                                Status
                            </label>


                            <div class="role-status-options">


                                <div class="role-status-option">

                                    <input type="radio" id="edit-status-active" name="role_status" value="1">

                                    <label for="edit-status-active">
                                        Aktif
                                    </label>

                                </div>


                                <div class="role-status-option">

                                    <input type="radio" id="edit-status-inactive" name="role_status" value="0">

                                    <label for="edit-status-inactive">
                                        Nonaktif
                                    </label>

                                </div>


                            </div>

                        </div>


                    </div>


                    <div class="modal-footer">

                        <button type="button" class="role-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="role-submit-button">

                            Simpan Perubahan

                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>



    {{-- =========================================================
         MODAL STATUS
    ========================================================== --}}

    <div class="modal fade role-modal" id="roleStatusModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-sm">

            <div class="modal-content">


                <div class="modal-header">

                    <div>

                        <div class="modal-title">
                            Ubah Status
                        </div>

                        <div class="modal-subtitle">
                            Konfirmasi perubahan status role.
                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <form method="POST" id="roleStatusForm">

                    @csrf

                    @method('PATCH')


                    <div class="modal-body text-center">

                        <div class="role-delete-icon" style="background:#fff8e8;color:#b87800;">

                            <i class="bi bi-arrow-repeat"></i>

                        </div>


                        <div class="role-delete-title" id="roleStatusTitle">

                            Ubah status role?

                        </div>


                        <div class="role-delete-text" id="roleStatusText">
                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="role-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="role-submit-button">

                            Ya, Ubah

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    {{-- =========================================================
         MODAL DELETE
    ========================================================== --}}

    <div class="modal fade role-modal" id="roleDeleteModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-sm">

            <div class="modal-content">


                <div class="modal-header">

                    <div>

                        <div class="modal-title">
                            Hapus Role
                        </div>

                        <div class="modal-subtitle">
                            Tindakan ini tidak dapat dibatalkan.
                        </div>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <form method="POST" id="roleDeleteForm">

                    @csrf

                    @method('DELETE')


                    <div class="modal-body text-center">

                        <div class="role-delete-icon">

                            <i class="bi bi-trash3"></i>

                        </div>


                        <div class="role-delete-title">

                            Hapus role ini?

                        </div>


                        <div class="role-delete-text">

                            Role
                            <strong id="roleDeleteName"></strong>
                            akan dihapus secara permanen.

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="role-cancel-button" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="role-submit-button" style="background:#d33;">

                            Ya, Hapus

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>



    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                /*
                |--------------------------------------------------------------------------
                | EDIT MODAL
                |--------------------------------------------------------------------------
                */

                const editModal =
                    document.getElementById(
                        'roleEditModal'
                    );

                if (editModal) {

                    editModal.addEventListener(
                        'show.bs.modal',
                        function(event) {

                            const button =
                                event.relatedTarget;

                            const uid =
                                button.dataset.roleUid;

                            const name =
                                button.dataset.roleName;

                            const slug =
                                button.dataset.roleSlug;

                            const status =
                                button.dataset.roleStatus;


                            const form =
                                document.getElementById(
                                    'roleEditForm'
                                );

                            form.action =
                                "{{ url('/admin/roles') }}/" +
                                uid;


                            document.getElementById(
                                'edit-role-name'
                            ).value = name || '';


                            document.getElementById(
                                'edit-role-slug'
                            ).value = slug || '';


                            if (status === '1') {

                                document.getElementById(
                                    'edit-status-active'
                                ).checked = true;

                            } else {

                                document.getElementById(
                                    'edit-status-inactive'
                                ).checked = true;

                            }

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | STATUS MODAL
                |--------------------------------------------------------------------------
                */

                const statusModal =
                    document.getElementById(
                        'roleStatusModal'
                    );

                if (statusModal) {

                    statusModal.addEventListener(
                        'show.bs.modal',
                        function(event) {

                            const button =
                                event.relatedTarget;

                            const uid =
                                button.dataset.roleUid;

                            const name =
                                button.dataset.roleName;

                            const status =
                                button.dataset.roleStatus;


                            const form =
                                document.getElementById(
                                    'roleStatusForm'
                                );

                            form.action =
                                "{{ url('/admin/roles') }}/" +
                                uid +
                                "/status";


                            const title =
                                document.getElementById(
                                    'roleStatusTitle'
                                );

                            const text =
                                document.getElementById(
                                    'roleStatusText'
                                );


                            if (status === '1') {

                                title.innerText =
                                    'Nonaktifkan role?';

                                text.innerHTML =
                                    'Role <strong>' +
                                    escapeHtml(name) +
                                    '</strong> akan dinonaktifkan.';

                            } else {

                                title.innerText =
                                    'Aktifkan role?';

                                text.innerHTML =
                                    'Role <strong>' +
                                    escapeHtml(name) +
                                    '</strong> akan diaktifkan.';

                            }

                        }
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | DELETE MODAL
                |--------------------------------------------------------------------------
                */

                const deleteModal =
                    document.getElementById(
                        'roleDeleteModal'
                    );

                if (deleteModal) {

                    deleteModal.addEventListener(
                        'show.bs.modal',
                        function(event) {

                            const button =
                                event.relatedTarget;

                            const uid =
                                button.dataset.roleUid;

                            const name =
                                button.dataset.roleName;


                            const form =
                                document.getElementById(
                                    'roleDeleteForm'
                                );

                            form.action =
                                "{{ url('/admin/roles') }}/" +
                                uid;


                            document.getElementById(
                                    'roleDeleteName'
                                ).innerText =
                                name || '';

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

            }
        );
    </script>

@endsection
