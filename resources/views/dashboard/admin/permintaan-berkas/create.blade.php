@extends('dashboard.layouts.app')

@section('title', 'Permintaan Berkas')

@section('content')

    @php
        $folderData = $folders
            ->map(function ($folder) {
                return [
                    'id' => $folder->folder_id,
                    'nama' => $folder->folder_nama,
                    'jenis_kerja_id' => $folder->folder_jenis_kerja_id,
                ];
            })
            ->values()
            ->all();
    @endphp


    <style>
        .permintaan-page {
            max-width: 1200px;
            margin: 0 auto;
        }

        .permintaan-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }

        .permintaan-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .permintaan-back {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #fff;
            color: #182238;
            border: 1px solid #e5e7eb;
            text-decoration: none;
            transition: .2s ease;
        }

        .permintaan-back:hover {
            background: #f8fafc;
            color: #f28c28;
            border-color: #f28c28;
        }

        .permintaan-title h4 {
            margin: 0;
            color: #182238;
            font-weight: 700;
        }

        .permintaan-title p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .permintaan-card {
            background: #fff;
            border: 1px solid #e9edf3;
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(24, 34, 56, .04);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .permintaan-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .permintaan-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            background: rgba(242, 140, 40, .12);
            color: #f28c28;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            flex-shrink: 0;
        }

        .permintaan-card-header h5 {
            margin: 0;
            color: #182238;
            font-size: 16px;
            font-weight: 700;
        }

        .permintaan-card-header p {
            margin: 3px 0 0;
            color: #7b8494;
            font-size: 13px;
        }

        .permintaan-card-body {
            padding: 24px;
        }

        .form-label {
            color: #374151;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            min-height: 44px;
            border-radius: 10px;
            border-color: #dfe4eb;
            font-size: 14px;
            color: #182238;
            box-shadow: none !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #f28c28;
            box-shadow: 0 0 0 .2rem rgba(242, 140, 40, .10) !important;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .required {
            color: #dc3545;
        }

        .target-info {
            padding: 12px 14px;
            border-radius: 10px;
            background: #f8fafc;
            color: #667085;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .target-info i {
            color: #f28c28;
            margin-right: 5px;
        }

        .target-row {
            border: 1px solid #e5e9ef;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 12px;
            background: #fff;
        }

        .target-row:last-child {
            margin-bottom: 0;
        }

        .target-row-number {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: #182238;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-add-target {
            border: 1px dashed #cfd6df;
            background: #f8fafc;
            color: #182238;
            border-radius: 10px;
            min-height: 42px;
            font-size: 13px;
            font-weight: 600;
            width: 100%;
            transition: .2s ease;
            margin-top: 16px;
        }

        .btn-add-target:hover {
            border-color: #f28c28;
            color: #f28c28;
            background: #fffaf5;
        }

        .btn-remove-target {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid #f0d0d0;
            background: #fff;
            color: #dc3545;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: .2s ease;
        }

        .btn-remove-target:hover {
            background: #fff5f5;
            border-color: #dc3545;
        }

        .form-error {
            font-size: 12px;
            color: #dc3545;
            margin-top: 5px;
        }

        .permintaan-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding: 20px 24px;
            border-top: 1px solid #edf0f4;
        }

        .btn-simpan {
            background: #f28c28;
            border: 1px solid #f28c28;
            color: #fff;
            min-height: 44px;
            padding: 0 22px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-simpan:hover {
            background: #dc7818;
            border-color: #dc7818;
            color: #fff;
        }

        .btn-batal {
            min-height: 44px;
            padding: 0 20px;
            border-radius: 10px;
            border: 1px solid #dfe4eb;
            background: #fff;
            color: #4b5563;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-batal:hover {
            background: #f8fafc;
            color: #182238;
        }

        @media (max-width: 767.98px) {
            .permintaan-header {
                align-items: flex-start;
            }

            .permintaan-header-left {
                align-items: flex-start;
            }

            .permintaan-title h4 {
                font-size: 18px;
            }

            .permintaan-card-body,
            .permintaan-card-header,
            .permintaan-footer {
                padding: 18px;
            }

            .target-row {
                padding: 14px;
            }

            .permintaan-footer {
                flex-direction: column-reverse;
            }

            .permintaan-footer a,
            .permintaan-footer button {
                width: 100%;
            }
        }
    </style>

    <div class="permintaan-page">

        {{-- HEADER --}}
        <div class="permintaan-header">

            <div class="permintaan-header-left">

                <a href="{{ route('admin.permintaan.berkas.index') }}" class="permintaan-back" title="Kembali">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <div class="permintaan-title">
                    <h4>Permintaan Berkas</h4>
                    <p>Buat permintaan pengumpulan berkas pegawai</p>
                </div>

            </div>

        </div>


        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif


        {{-- ERROR --}}
        @if ($errors->any())

            <div class="alert alert-danger border-0 rounded-3 mb-4">

                <div class="fw-semibold mb-1">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Terdapat kesalahan pada data.
                </div>

                <ul class="mb-0 ps-4 small">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <form action="{{ route('admin.permintaan.berkas.store') }}" method="POST" id="formPermintaanBerkas">

            @csrf


            {{-- ========================================================= --}}
            {{-- INFORMASI PERMINTAAN --}}
            {{-- ========================================================= --}}

            <div class="permintaan-card">

                <div class="permintaan-card-header">

                    <div class="permintaan-card-icon">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>

                    <div>

                        <h5>Informasi Permintaan</h5>

                        <p>
                            Lengkapi informasi dasar permintaan berkas
                        </p>

                    </div>

                </div>


                <div class="permintaan-card-body">

                    <div class="row g-3">


                        {{-- JENIS BERKAS --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Jenis Berkas
                                <span class="required">*</span>
                            </label>

                            <select name="permintaan_jenis_berkas_id"
                                class="form-select @error('permintaan_jenis_berkas_id') is-invalid @enderror" required>

                                <option value="">
                                    -- Pilih Jenis Berkas --
                                </option>

                                @foreach ($jenisBerkas as $jenis)
                                    <option value="{{ $jenis->jenis_berkas_id }}"
                                        {{ old('permintaan_jenis_berkas_id') == $jenis->jenis_berkas_id ? 'selected' : '' }}>

                                        {{ $jenis->jenis_berkas_nama }}

                                    </option>
                                @endforeach

                            </select>

                            @error('permintaan_jenis_berkas_id')
                                <div class="form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- TAHUN --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Tahun
                                <span class="required">*</span>
                            </label>

                            <input type="number" name="permintaan_tahun"
                                class="form-control @error('permintaan_tahun') is-invalid @enderror"
                                value="{{ old('permintaan_tahun', date('Y')) }}" min="2000" max="2100" required>

                            @error('permintaan_tahun')
                                <div class="form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- PERIODE --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Periode
                            </label>

                            <input type="text" name="permintaan_periode" class="form-control"
                                value="{{ old('permintaan_periode') }}" placeholder="Contoh: TW I">

                        </div>


                        {{-- JUDUL --}}
                        <div class="col-md-8">

                            <label class="form-label">
                                Judul Permintaan
                                <span class="required">*</span>
                            </label>

                            <input type="text" name="permintaan_judul"
                                class="form-control @error('permintaan_judul') is-invalid @enderror"
                                value="{{ old('permintaan_judul') }}" placeholder="Contoh: Pengumpulan EVKIN TW I"
                                required>

                            @error('permintaan_judul')
                                <div class="form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- TOMBOL --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Teks Tombol
                                <span class="required">*</span>
                            </label>

                            <input type="text" name="permintaan_tombol"
                                class="form-control @error('permintaan_tombol') is-invalid @enderror"
                                value="{{ old('permintaan_tombol', 'Upload Berkas') }}" placeholder="Contoh: Upload Berkas"
                                required>

                            @error('permintaan_tombol')
                                <div class="form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- MULAI --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Mulai Permintaan
                                <span class="required">*</span>
                            </label>

                            <input type="datetime-local" name="permintaan_mulai"
                                class="form-control @error('permintaan_mulai') is-invalid @enderror"
                                value="{{ old('permintaan_mulai') }}" required>

                            @error('permintaan_mulai')
                                <div class="form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- EXPIRED --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Batas Waktu
                                <span class="required">*</span>
                            </label>

                            <input type="datetime-local" name="permintaan_expired"
                                class="form-control @error('permintaan_expired') is-invalid @enderror"
                                value="{{ old('permintaan_expired') }}" required>

                            @error('permintaan_expired')
                                <div class="form-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- KETERANGAN --}}
                        <div class="col-12">

                            <label class="form-label">
                                Keterangan
                            </label>

                            <textarea name="permintaan_keterangan" class="form-control"
                                placeholder="Tambahkan keterangan atau instruksi pengumpulan berkas...">{{ old('permintaan_keterangan') }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- TARGET JENIS KERJA --}}
            {{-- ========================================================= --}}

            <div class="permintaan-card">

                <div class="permintaan-card-header">

                    <div class="permintaan-card-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div>

                        <h5>Target Jenis Kerja</h5>

                        <p>
                            Tentukan jenis kerja dan folder Drive tujuan masing-masing
                        </p>

                    </div>

                </div>


                <div class="permintaan-card-body">

                    <div class="target-info">

                        <i class="bi bi-info-circle-fill"></i>

                        Setiap jenis kerja harus memiliki satu folder Drive berkas.
                        Folder dipilih dari folder yang sudah terdaftar di SAMPERIN.

                    </div>


                    <div id="targetContainer"></div>


                    <button type="button" class="btn-add-target" id="btnTambahTarget">

                        <i class="bi bi-plus-circle me-1"></i>

                        Tambah Jenis Kerja

                    </button>

                </div>


                <div class="permintaan-footer">

                    <a href="{{ route('admin.permintaan.berkas.index') }}" class="btn btn-batal">

                        Batal

                    </a>


                    <button type="submit" class="btn btn-simpan" id="btnSimpan">

                        <i class="bi bi-check2-circle me-1"></i>

                        Simpan Permintaan

                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- ============================================================= --}}
    {{-- TEMPLATE TARGET --}}
    {{-- ============================================================= --}}

    <template id="targetTemplate">

        <div class="target-row">

            <div class="d-flex align-items-center justify-content-between gap-3 mb-3">

                <div class="target-row-number">
                    1
                </div>


                <button type="button" class="btn-remove-target" title="Hapus" onclick="hapusTarget(this)">

                    <i class="bi bi-trash3"></i>

                </button>

            </div>


            <div class="row g-3">


                {{-- JENIS KERJA --}}
                <div class="col-md-6">

                    <label class="form-label">

                        Jenis Kerja

                        <span class="required">*</span>

                    </label>


                    <select name="jenis_kerja[]" class="form-select target-jenis-kerja" required>

                        <option value="">
                            -- Pilih Jenis Kerja --
                        </option>

                        @foreach ($jenisKerja as $jenis)
                            <option value="{{ $jenis->jenis_kerja_id }}">

                                {{ $jenis->jenis_kerja_nama }}

                            </option>
                        @endforeach

                    </select>

                </div>


                {{-- FOLDER --}}
                <div class="col-md-6">

                    <label class="form-label">

                        Folder Drive

                        <span class="required">*</span>

                    </label>


                    <select name="folder_id[]" class="form-select target-folder" required>

                        <option value="">
                            -- Pilih Folder Drive --
                        </option>

                    </select>

                </div>

            </div>

        </div>

    </template>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const container = document.getElementById('targetContainer');
            const template = document.getElementById('targetTemplate');
            const btnTambah = document.getElementById('btnTambahTarget');
            const form = document.getElementById('formPermintaanBerkas');
            const btnSimpan = document.getElementById('btnSimpan');

            const folders = @json($folderData);


            // =========================================================
            // TAMBAH TARGET
            // =========================================================

            function tambahTarget(oldJenis = '', oldFolder = '') {

                const clone = template.content.cloneNode(true);

                container.appendChild(clone);

                const row = container.lastElementChild;

                const jenisSelect = row.querySelector('.target-jenis-kerja');

                isiFolder(row, oldFolder);

                if (oldJenis !== '') {
                    jenisSelect.value = oldJenis;
                }

                jenisSelect.addEventListener('change', function() {
                    updateJenisKerjaOptions();
                });

                updateNomor();
                updateJenisKerjaOptions();
            }


            // =========================================================
            // ISI SEMUA FOLDER
            // =========================================================

            function isiFolder(row, selectedFolder = '') {

                const folderSelect = row.querySelector('.target-folder');

                folderSelect.innerHTML = '';

                // Tidak ada folder
                if (!folders || folders.length === 0) {

                    folderSelect.disabled = true;

                    folderSelect.innerHTML = `
                <option value="">
                    Belum ada folder Drive terdaftar
                </option>
            `;

                    return;
                }

                // Ada folder
                folderSelect.disabled = false;

                folderSelect.innerHTML = `
            <option value="">
                -- Pilih Folder Drive --
            </option>
        `;

                // Tampilkan SEMUA folder
                folders.forEach(function(folder) {

                    const option = document.createElement('option');

                    option.value = folder.id;

                    option.textContent = folder.nama;

                    if (String(selectedFolder) === String(folder.id)) {
                        option.selected = true;
                    }

                    folderSelect.appendChild(option);
                });
            }


            // =========================================================
            // NOMOR BARIS
            // =========================================================

            function updateNomor() {

                const rows = container.querySelectorAll('.target-row');

                rows.forEach(function(row, index) {

                    const nomor = row.querySelector('.target-row-number');

                    if (nomor) {
                        nomor.textContent = index + 1;
                    }
                });
            }


            // =========================================================
            // CEGAH JENIS KERJA DUPLIKAT
            // =========================================================

            function updateJenisKerjaOptions() {

                const rows = container.querySelectorAll('.target-row');

                const selectedValues = [];

                rows.forEach(function(row) {

                    const select = row.querySelector('.target-jenis-kerja');

                    if (!select) {
                        return;
                    }

                    if (select.value) {
                        selectedValues.push(String(select.value));
                    }
                });


                rows.forEach(function(row) {

                    const select = row.querySelector('.target-jenis-kerja');

                    if (!select) {
                        return;
                    }

                    const currentValue = String(select.value || '');

                    Array.from(select.options).forEach(function(option) {

                        if (!option.value) {
                            return;
                        }

                        const value = String(option.value);

                        option.disabled =
                            selectedValues.includes(value) &&
                            value !== currentValue;
                    });
                });
            }


            // =========================================================
            // HAPUS TARGET
            // =========================================================

            window.hapusTarget = function(button) {

                const row = button.closest('.target-row');

                if (!row) {
                    return;
                }

                row.remove();

                updateNomor();
                updateJenisKerjaOptions();
            };


            // =========================================================
            // TAMBAH BARIS
            // =========================================================

            btnTambah.addEventListener('click', function() {

                tambahTarget();

            });


            // =========================================================
            // SUBMIT
            // =========================================================

            form.addEventListener('submit', function(event) {

                const rows = container.querySelectorAll('.target-row');

                if (rows.length === 0) {

                    event.preventDefault();

                    alert('Minimal pilih satu jenis kerja.');

                    return;
                }

                btnSimpan.disabled = true;

                btnSimpan.innerHTML = `
            <span
                class="spinner-border spinner-border-sm me-1"
                role="status"
                aria-hidden="true">
            </span>
            Menyimpan...
        `;
            });


            // =========================================================
            // RESTORE OLD INPUT
            // =========================================================

            const oldJenisKerja = @json(old('jenis_kerja', []));
            const oldFolder = @json(old('folder_id', []));


            if (oldJenisKerja.length > 0) {

                oldJenisKerja.forEach(function(jenisId, index) {

                    tambahTarget(
                        jenisId,
                        oldFolder[index] ?? ''
                    );

                });

            } else {

                tambahTarget();

            }

        });
    </script>

@endsection
