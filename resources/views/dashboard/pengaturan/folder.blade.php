@extends('dashboard.layouts.app')

@section('title', 'Setting Folder')

@section('header-title', 'Setting Folder')

@section('breadcrumb', 'Pengaturan / Setting Folder')

@section('page-style')

    <style>
        .folder-card {
            background: #fff;
            border: 1px solid #e8ebef;
            border-radius: 16px;
            overflow: hidden;
        }

        .folder-card-header {
            padding: 20px 22px;
            border-bottom: 1px solid #edf0f3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .folder-card-title {
            font-size: 15px;
            font-weight: 800;
            color: #182238;
            margin: 0;
        }

        .folder-card-subtitle {
            font-size: 12px;
            color: #9aa2ae;
            margin-top: 4px;
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

        .folder-table {
            margin: 0;
        }

        .folder-table thead th {
            background: #fafbfc;
            color: #7f8794;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .03em;
            border-bottom: 1px solid #edf0f3;
            padding: 13px 16px;
            white-space: nowrap;
        }

        .folder-table tbody td {
            padding: 14px 16px;
            font-size: 12px;
            color: #384152;
            vertical-align: middle;
            border-color: #f0f2f5;
        }

        .folder-code {
            font-weight: 800;
            color: #182238;
        }

        .folder-name {
            font-weight: 700;
            color: #182238;
        }

        .folder-drive {
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-family: monospace;
            font-size: 11px;
            color: #697386;
        }

        .folder-type {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 8px;
            background: #f3f5f8;
            color: #596273;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .folder-status-active {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 8px;
            background: #eaf7ef;
            color: #23834b;
            font-size: 10px;
            font-weight: 800;
        }

        .folder-status-inactive {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 8px;
            background: #f4f4f5;
            color: #888;
            font-size: 10px;
            font-weight: 800;
        }

        .folder-action {
            display: flex;
            gap: 6px;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid #e4e7eb;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #596273;
        }

        .btn-action:hover {
            background: #f5f6f8;
            color: #182238;
        }

        .btn-action-delete:hover {
            color: #dc3545;
            border-color: #f1c5ca;
            background: #fff5f5;
        }

        .form-label {
            font-size: 12px;
            font-weight: 700;
            color: #182238;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            border-color: #e1e5ea;
            border-radius: 9px;
            min-height: 42px;
            font-size: 12px;
            box-shadow: none !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #f28c28;
        }

        textarea.form-control {
            min-height: 90px;
        }

        .modal-title {
            color: #182238;
            font-weight: 800;
            font-size: 16px;
        }

        .modal-content {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid #edf0f3;
            padding: 18px 20px;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-top: 1px solid #edf0f3;
            padding: 15px 20px;
        }

        .form-check-input:checked {
            background-color: #f28c28;
            border-color: #f28c28;
        }

        .empty-folder {
            padding: 50px 20px;
            text-align: center;
            color: #9aa2ae;
        }

        .empty-folder i {
            font-size: 35px;
            margin-bottom: 10px;
        }

        @media(max-width: 850px) {

            .folder-card-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .folder-table-wrapper {
                overflow-x: auto;
            }

            .folder-table {
                min-width: 1000px;
            }

        }
    </style>

@endsection


@section('content')

    @if (session('success'))
        <div class="alert alert-success border-0 rounded-3 small">
            {{ session('success') }}
        </div>
    @endif


    @if ($errors->any())

        <div class="alert alert-danger border-0 rounded-3 small">

            <ul class="mb-0 ps-3">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <div class="folder-card">

        <div class="folder-card-header">

            <div>

                <div class="folder-card-title">
                    Folder Berkas
                </div>

                <div class="folder-card-subtitle">
                    Pengaturan folder Google Drive berdasarkan jenis kerja.
                </div>

            </div>

            <button type="button" class="btn btn-samperin" data-bs-toggle="modal" data-bs-target="#modalTambahFolder">

                <i class="bi bi-plus-lg me-1"></i>

                Tambah Folder

            </button>

        </div>


        <div class="folder-table-wrapper">

            <table class="table folder-table align-middle">

                <thead>

                    <tr>

                        <th width="50">No</th>

                        <th>Kode</th>

                        <th>Nama Folder</th>

                        <th>Jenis</th>

                        <th>Jenis Kerja</th>

                        <th>Prefix</th>

                        <th>Google Drive Folder ID</th>

                        <th>Status</th>

                        <th width="100">Aksi</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($folders as $folder)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>

                                <div class="folder-code">
                                    {{ $folder->folder_kode }}
                                </div>

                            </td>

                            <td>

                                <div class="folder-name">
                                    {{ $folder->folder_nama }}
                                </div>

                            </td>

                            <td>

                                <span class="folder-type">
                                    {{ $folder->folder_jenis }}
                                </span>

                            </td>

                            <td>

                                @php

                                    $jenisKerjaItem = $jenisKerja->firstWhere(
                                        'jenis_kerja_id',
                                        $folder->folder_jenis_kerja_id,
                                    );

                                @endphp

                                @if ($jenisKerjaItem)
                                    {{ $jenisKerjaItem->jenis_kerja_nama }}
                                @else
                                    <span class="text-muted">
                                        -
                                    </span>
                                @endif

                            </td>

                            <td>
                                {{ $folder->folder_prefix ?: '-' }}
                            </td>

                            <td>

                                <div class="folder-drive" title="{{ $folder->folder_drive_id }}">
                                    {{ $folder->folder_drive_id }}
                                </div>

                            </td>

                            <td>

                                @if ($folder->folder_status)
                                    <span class="folder-status-active">
                                        Aktif
                                    </span>
                                @else
                                    <span class="folder-status-inactive">
                                        Tidak Aktif
                                    </span>
                                @endif

                            </td>

                            <td>

                                <div class="folder-action">

                                    <button type="button" class="btn-action" title="Edit" data-bs-toggle="modal"
                                        data-bs-target="#modalEditFolder{{ $folder->folder_uid }}">

                                        <i class="bi bi-pencil"></i>

                                    </button>


                                    <form action="{{ route('pengaturan.folder.delete', $folder->folder_uid) }}"
                                        method="POST" onsubmit="return confirm('Hapus folder ini?')">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit" class="btn-action btn-action-delete" title="Hapus">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                        {{-- MODAL EDIT --}}

                        <div class="modal fade" id="modalEditFolder{{ $folder->folder_uid }}" tabindex="-1"
                            aria-hidden="true">

                            <div class="modal-dialog modal-lg modal-dialog-centered">

                                <div class="modal-content">

                                    <form action="{{ route('pengaturan.folder.update', $folder->folder_uid) }}"
                                        method="POST">

                                        @csrf

                                        @method('PUT')


                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Edit Folder
                                            </h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                        </div>


                                        <div class="modal-body">

                                            <div class="row g-3">

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Kode Folder
                                                    </label>

                                                    <input type="text" name="folder_kode" class="form-control"
                                                        value="{{ $folder->folder_kode }}" required>

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Nama Folder
                                                    </label>

                                                    <input type="text" name="folder_nama" class="form-control"
                                                        value="{{ $folder->folder_nama }}" required>

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Jenis Folder
                                                    </label>

                                                    <select name="folder_jenis" class="form-select" required>

                                                        <option value="">
                                                            Pilih jenis folder
                                                        </option>

                                                        <option value="foto"
                                                            {{ $folder->folder_jenis === 'foto' ? 'selected' : '' }}>
                                                            Foto
                                                        </option>

                                                        <option value="berkas"
                                                            {{ $folder->folder_jenis === 'berkas' ? 'selected' : '' }}>
                                                            Berkas
                                                        </option>

                                                        <option value="dokumen"
                                                            {{ $folder->folder_jenis === 'dokumen' ? 'selected' : '' }}>
                                                            Dokumen
                                                        </option>

                                                    </select>

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Jenis Kerja
                                                    </label>

                                                    <select name="folder_jenis_kerja_id" class="form-select" required>

                                                        <option value="">
                                                            Pilih jenis kerja
                                                        </option>

                                                        @foreach ($jenisKerja as $item)
                                                            <option value="{{ $item->jenis_kerja_id }}"
                                                                {{ $folder->folder_jenis_kerja_id == $item->jenis_kerja_id ? 'selected' : '' }}>
                                                                {{ $item->jenis_kerja_nama }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Prefix
                                                    </label>

                                                    <input type="text" name="folder_prefix" class="form-control"
                                                        value="{{ $folder->folder_prefix }}">

                                                </div>


                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Google Drive Folder ID
                                                    </label>

                                                    <div class="input-group">

                                                        <input type="text" name="folder_drive_id" class="form-control"
                                                            value="{{ $folder->folder_drive_id }}"
                                                            id="driveEdit{{ $folder->folder_uid }}" required>

                                                        <button type="button" class="btn btn-outline-secondary"
                                                            onclick="copyDriveId('driveEdit{{ $folder->folder_uid }}')"
                                                            title="Copy">

                                                            <i class="bi bi-copy"></i>

                                                        </button>

                                                    </div>

                                                </div>


                                                <div class="col-12">

                                                    <label class="form-label">
                                                        Keterangan
                                                    </label>

                                                    <textarea name="folder_keterangan" class="form-control">{{ $folder->folder_keterangan }}</textarea>

                                                </div>


                                                <div class="col-12">

                                                    <div class="form-check form-switch">

                                                        <input type="checkbox" class="form-check-input"
                                                            name="folder_status" value="1"
                                                            id="statusEdit{{ $folder->folder_uid }}"
                                                            {{ $folder->folder_status ? 'checked' : '' }}>

                                                        <label class="form-check-label small"
                                                            for="statusEdit{{ $folder->folder_uid }}">
                                                            Folder Aktif
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
                                                Simpan Perubahan
                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @empty

                        <tr>

                            <td colspan="9">

                                <div class="empty-folder">

                                    <i class="bi bi-folder2-open d-block"></i>

                                    <div>
                                        Belum ada folder yang dikonfigurasi.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- MODAL TAMBAH --}}

    <div class="modal fade" id="modalTambahFolder" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <form action="{{ route('pengaturan.folder.store') }}" method="POST">

                    @csrf


                    <div class="modal-header">

                        <h5 class="modal-title">
                            Tambah Folder
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                    </div>


                    <div class="modal-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Kode Folder
                                </label>

                                <input type="text" name="folder_kode" class="form-control"
                                    placeholder="Contoh: FOTO_PEGAWAI" value="{{ old('folder_kode') }}" required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Nama Folder
                                </label>

                                <input type="text" name="folder_nama" class="form-control"
                                    placeholder="Contoh: Foto Pegawai" value="{{ old('folder_nama') }}" required>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Jenis Folder
                                </label>

                                <select name="folder_jenis" class="form-select" required>

                                    <option value="">
                                        Pilih jenis folder
                                    </option>

                                    <option value="foto" {{ old('folder_jenis') === 'foto' ? 'selected' : '' }}>
                                        Foto
                                    </option>

                                    <option value="berkas" {{ old('folder_jenis') === 'berkas' ? 'selected' : '' }}>
                                        Berkas
                                    </option>

                                    <option value="dokumen" {{ old('folder_jenis') === 'dokumen' ? 'selected' : '' }}>
                                        Dokumen
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Jenis Kerja
                                </label>

                                <select name="folder_jenis_kerja_id" class="form-select" required>

                                    <option value="">
                                        Pilih jenis kerja
                                    </option>

                                    @foreach ($jenisKerja as $item)
                                        <option value="{{ $item->jenis_kerja_id }}"
                                            {{ old('folder_jenis_kerja_id') == $item->jenis_kerja_id ? 'selected' : '' }}>
                                            {{ $item->jenis_kerja_nama }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Prefix
                                </label>

                                <input type="text" name="folder_prefix" class="form-control" placeholder="Opsional"
                                    value="{{ old('folder_prefix') }}">

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Google Drive Folder ID
                                </label>

                                <div class="input-group">

                                    <input type="text" name="folder_drive_id" class="form-control"
                                        placeholder="Masukkan Folder ID Google Drive"
                                        value="{{ old('folder_drive_id') }}" id="driveTambah" required>

                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="copyDriveId('driveTambah')" title="Copy">

                                        <i class="bi bi-copy"></i>

                                    </button>

                                </div>

                                <div class="form-text">
                                    Masukkan ID folder dari link Google Drive.
                                </div>

                            </div>


                            <div class="col-12">

                                <label class="form-label">
                                    Keterangan
                                </label>

                                <textarea name="folder_keterangan" class="form-control" placeholder="Keterangan folder (opsional)">{{ old('folder_keterangan') }}</textarea>

                            </div>


                            <div class="col-12">

                                <div class="form-check form-switch">

                                    <input type="checkbox" class="form-check-input" name="folder_status" value="1"
                                        id="statusTambah" checked>

                                    <label class="form-check-label small" for="statusTambah">
                                        Folder Aktif
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
                            <i class="bi bi-check-lg me-1"></i>
                            Simpan Folder
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection


@section('page-script')

    <script>
        function copyDriveId(id) {

            const input = document.getElementById(id);

            if (!input) {
                return;
            }

            navigator.clipboard.writeText(input.value);

        }
    </script>

@endsection
