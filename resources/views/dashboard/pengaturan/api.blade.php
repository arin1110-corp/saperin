@extends('dashboard.layouts.app')

@section('title', 'Setting API')

@section('header-title', 'Setting API')

@section('breadcrumb', 'Pengaturan / Setting API')

@section('page-style')

    <style>
        .setting-page-header {
            margin-bottom: 24px;
        }

        .setting-page-title {
            font-size: 20px;
            font-weight: 800;
            color: #182238;
            margin-bottom: 5px;
        }

        .setting-page-description {
            font-size: 13px;
            color: #8c95a3;
            margin: 0;
        }

        .btn-samperin {
            background: #f28c28;
            border: 1px solid #f28c28;
            color: #fff;
            font-weight: 700;
            border-radius: 10px;
            padding: 9px 16px;
        }

        .btn-samperin:hover,
        .btn-samperin:focus {
            background: #dc7415;
            border-color: #dc7415;
            color: #fff;
        }

        .setting-card {
            background: #fff;
            border: 1px solid #e8ebef;
            border-radius: 16px;
            overflow: hidden;
        }

        .setting-table {
            margin: 0;
        }

        .setting-table thead th {
            background: #fafbfc;
            color: #6f7887;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .3px;
            border-bottom: 1px solid #e8ebef;
            padding: 15px 14px;
            white-space: nowrap;
        }

        .setting-table tbody td {
            padding: 15px 14px;
            border-color: #edf0f3;
            color: #30394a;
            font-size: 13px;
            vertical-align: middle;
        }

        .setting-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .setting-table tbody tr:hover {
            background: #fafbfc;
        }

        .table-no {
            width: 60px;
            color: #9aa2ae !important;
        }

        .api-code {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 7px;
            background: #f0f2f5;
            color: #182238;
            font-size: 11px;
            font-weight: 800;
        }

        .api-url {
            display: block;
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #6f7887;
            font-size: 12px;
        }

        .badge-aktif {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #e9f7ef;
            color: #21834f;
            border-radius: 20px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-nonaktif {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f0f1f3;
            color: #737b87;
            border-radius: 20px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 700;
        }

        .action-group {
            display: flex;
            justify-content: flex-end;
            gap: 6px;
        }

        .btn-action {
            width: 34px;
            height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #fff;
        }

        .btn-edit {
            border: 1px solid #dce2ea;
            color: #182238;
        }

        .btn-edit:hover {
            background: #182238;
            border-color: #182238;
            color: #fff;
        }

        .btn-delete {
            border: 1px solid #f0d5d5;
            color: #c74b4b;
        }

        .btn-delete:hover {
            background: #c74b4b;
            border-color: #c74b4b;
            color: #fff;
        }

        .empty-state {
            text-align: center;
            padding: 70px 20px;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: #f0f2f5;
            color: #182238;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 16px;
        }

        .empty-title {
            font-size: 15px;
            font-weight: 800;
            color: #182238;
            margin-bottom: 5px;
        }

        .empty-text {
            font-size: 12px;
            color: #969eaa;
            margin-bottom: 20px;
        }

        .modal-content {
            border-radius: 16px !important;
        }

        .modal-header {
            border-bottom: 1px solid #edf0f3;
            padding: 18px 22px;
        }

        .modal-title {
            color: #182238;
            font-size: 16px;
        }

        .modal-body {
            padding: 22px;
        }

        .modal-footer {
            border-top: 1px solid #edf0f3;
            padding: 15px 22px;
        }

        .form-label {
            color: #30394a;
            font-size: 12px;
            margin-bottom: 7px;
        }

        .form-control {
            border-color: #dfe3e8;
            border-radius: 9px;
            font-size: 13px;
            min-height: 40px;
        }

        .form-control:focus {
            border-color: #f28c28;
            box-shadow: 0 0 0 .2rem rgba(242, 140, 40, .12);
        }

        textarea.form-control {
            min-height: auto;
        }

        .form-text {
            font-size: 10px;
            color: #9aa2ae;
        }

        .form-check-input:checked {
            background-color: #f28c28;
            border-color: #f28c28;
        }

        .btn-light {
            border: 1px solid #dfe3e8;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 600;
            color: #596273;
        }

        .alert {
            border: 0;
            border-radius: 12px;
            font-size: 12px;
        }

        @media(max-width: 850px) {

            .setting-page-title {
                font-size: 18px;
            }

            .setting-page-description {
                font-size: 12px;
            }

            .setting-table {
                min-width: 850px;
            }

        }
    </style>

@endsection


@section('content')

    <div class="setting-page-header">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

            <div>
                <div class="setting-page-title">
                    Setting API
                </div>

                <p class="setting-page-description">
                    Kelola konfigurasi API yang digunakan oleh SAMPERIN.
                </p>
            </div>

            <button type="button" class="btn btn-samperin" data-bs-toggle="modal" data-bs-target="#modalTambahApi">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah API
            </button>

        </div>

    </div>


    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>
    @endif


    @if ($errors->any())

        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">

            <strong>Terjadi kesalahan:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    @endif


    <div class="setting-card">

        @if ($apis->count())

            <div class="table-responsive">

                <table class="table setting-table align-middle">

                    <thead>

                        <tr>

                            <th class="ps-4 table-no">
                                No
                            </th>

                            <th>
                                Kode
                            </th>

                            <th>
                                Nama API
                            </th>

                            <th>
                                URL
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end pe-4">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($apis as $api)
                            <tr>

                                <td class="ps-4 table-no">
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <span class="api-code">
                                        {{ $api->api_kode }}
                                    </span>

                                </td>

                                <td>
                                    <strong>
                                        {{ $api->api_nama }}
                                    </strong>
                                </td>

                                <td>

                                    @if ($api->api_url)
                                        <span class="api-url" title="{{ $api->api_url }}">
                                            {{ $api->api_url }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            -
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    @if ($api->api_status)
                                        <span class="badge-aktif">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge-nonaktif">
                                            <i class="bi bi-dash-circle-fill"></i>
                                            Nonaktif
                                        </span>
                                    @endif

                                </td>

                                <td class="text-end pe-4">

                                    <div class="action-group">

                                        <button type="button" class="btn btn-action btn-edit" data-bs-toggle="modal"
                                            data-bs-target="#modalEditApi{{ $api->api_uid }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <form action="{{ route('pengaturan.api.delete', $api->api_uid) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus API ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-action btn-delete" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            {{-- MODAL EDIT API --}}

                            <div class="modal fade" id="modalEditApi{{ $api->api_uid }}" tabindex="-1" aria-hidden="true">

                                <div class="modal-dialog modal-dialog-centered modal-lg">

                                    <div class="modal-content border-0 shadow">

                                        <form action="{{ route('pengaturan.api.update', $api->api_uid) }}" method="POST">

                                            @csrf
                                            @method('PUT')

                                            <div class="modal-header">

                                                <h5 class="modal-title fw-bold">
                                                    Edit API
                                                </h5>

                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                            </div>

                                            <div class="modal-body">

                                                <div class="row g-3">

                                                    <div class="col-md-6">

                                                        <label class="form-label fw-semibold">
                                                            Kode API
                                                        </label>

                                                        <input type="text" name="api_kode" class="form-control"
                                                            value="{{ $api->api_kode }}" required>

                                                    </div>

                                                    <div class="col-md-6">

                                                        <label class="form-label fw-semibold">
                                                            Nama API
                                                        </label>

                                                        <input type="text" name="api_nama" class="form-control"
                                                            value="{{ $api->api_nama }}" required>

                                                    </div>

                                                    <div class="col-12">

                                                        <label class="form-label fw-semibold">
                                                            API URL
                                                        </label>

                                                        <input type="text" name="api_url" class="form-control"
                                                            value="{{ $api->api_url }}" placeholder="https://...">

                                                    </div>

                                                    <div class="col-12">

                                                        <label class="form-label fw-semibold">
                                                            API Token
                                                        </label>

                                                        <div class="input-group">

                                                            <input type="password" name="api_token" class="form-control"
                                                                value="{{ $api->api_token }}"
                                                                id="tokenEdit{{ $api->api_uid }}">

                                                            <button type="button" class="btn btn-outline-secondary"
                                                                onclick="toggleToken('tokenEdit{{ $api->api_uid }}', this)">
                                                                <i class="bi bi-eye"></i>
                                                            </button>

                                                        </div>

                                                    </div>

                                                    <div class="col-12">

                                                        <label class="form-label fw-semibold">
                                                            Keterangan
                                                        </label>

                                                        <textarea name="api_keterangan" class="form-control" rows="3">{{ $api->api_keterangan }}</textarea>

                                                    </div>

                                                    <div class="col-12">

                                                        <div class="form-check form-switch">

                                                            <input class="form-check-input" type="checkbox"
                                                                name="api_status" value="1"
                                                                id="statusEdit{{ $api->api_uid }}"
                                                                {{ $api->api_status ? 'checked' : '' }}>

                                                            <label class="form-check-label"
                                                                for="statusEdit{{ $api->api_uid }}">
                                                                API Aktif
                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    Batal
                                                </button>

                                                <button type="submit" class="btn btn-samperin">
                                                    <i class="bi bi-save me-1"></i>
                                                    Simpan Perubahan
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </tbody>

                </table>

            </div>
        @else
            <div class="empty-state">

                <div class="empty-icon">
                    <i class="bi bi-plug"></i>
                </div>

                <div class="empty-title">
                    Belum ada konfigurasi API
                </div>

                <div class="empty-text">
                    Tambahkan API yang akan digunakan oleh SAMPERIN.
                </div>

                <button type="button" class="btn btn-samperin" data-bs-toggle="modal" data-bs-target="#modalTambahApi">
                    <i class="bi bi-plus-lg me-1"></i>
                    Tambah API
                </button>

            </div>

        @endif

    </div>


    {{-- MODAL TAMBAH API --}}

    <div class="modal fade" id="modalTambahApi" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content border-0 shadow">

                <form action="{{ route('pengaturan.api.store') }}" method="POST">

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title fw-bold">
                            Tambah API
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Kode API
                                </label>

                                <input type="text" name="api_kode" class="form-control" placeholder="ARINDRIVE"
                                    required>

                                <div class="form-text">
                                    Kode unik untuk membedakan API.
                                </div>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Nama API
                                </label>

                                <input type="text" name="api_nama" class="form-control" placeholder="ArinDrive API"
                                    required>

                            </div>

                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    API URL
                                </label>

                                <input type="text" name="api_url" class="form-control"
                                    placeholder="https://arindrive.example.com">

                            </div>

                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    API Token
                                </label>

                                <div class="input-group">

                                    <input type="password" name="api_token" class="form-control" id="tokenTambah"
                                        placeholder="Masukkan API token">

                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="toggleToken('tokenTambah', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                </div>

                            </div>

                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Keterangan
                                </label>

                                <textarea name="api_keterangan" class="form-control" rows="3" placeholder="Keterangan API..."></textarea>

                            </div>

                            <div class="col-12">

                                <div class="form-check form-switch">

                                    <input class="form-check-input" type="checkbox" name="api_status" value="1"
                                        id="statusTambah" checked>

                                    <label class="form-check-label" for="statusTambah">
                                        API Aktif
                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-samperin">
                            <i class="bi bi-save me-1"></i>
                            Simpan API
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


@section('page-script')

    <script>
        function toggleToken(inputId, button) {

            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');

            if (input.type === 'password') {

                input.type = 'text';

                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');

            } else {

                input.type = 'password';

                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');

            }

        }
    </script>

@endsection

@endsection
