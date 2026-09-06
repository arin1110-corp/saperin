@extends('dashboard.layouts.app')

@section('title', 'Permintaan Berkas')

@section('content')

    @php
        /*
        |--------------------------------------------------------------------------
        | DATA UNTUK MODAL / JAVASCRIPT
        |--------------------------------------------------------------------------
        */

        $folderData = ($folders ?? collect())
            ->map(function ($folder) {
                return [
                    'id' => $folder->folder_id,
                    'nama' => $folder->folder_nama,
                    'jenis_kerja_id' => $folder->folder_jenis_kerja_id,
                ];
            })
            ->values()
            ->all();

        $jenisKerjaData = ($jenisKerja ?? collect())
            ->map(function ($jenis) {
                return [
                    'id' => $jenis->jenis_kerja_id,
                    'nama' => $jenis->jenis_kerja_nama,
                ];
            })
            ->values()
            ->all();
    @endphp


    <div class="permintaan-index-page">

        {{-- =========================================================
             HEADER
        ========================================================== --}}

        <div class="page-header">

            <div class="page-header-left">

                <div class="page-icon">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>

                <div>

                    <h4>
                        Permintaan Berkas
                    </h4>

                    <p>
                        Daftar permintaan pengumpulan berkas pegawai
                    </p>

                </div>

            </div>


            <a href="{{ route('admin.permintaan.berkas.create') }}" class="btn-permintaan-baru">

                <i class="bi bi-plus-lg"></i>

                <span>
                    Permintaan Berkas Baru
                </span>

            </a>

        </div>


        {{-- =========================================================
             SUCCESS
        ========================================================== --}}

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 mb-4">

                <i class="bi bi-check-circle-fill me-2"></i>

                {{ session('success') }}

            </div>
        @endif


        {{-- =========================================================
             ERROR
        ========================================================== --}}

        @if (session('error'))
            <div class="alert alert-danger border-0 rounded-3 mb-4">

                <i class="bi bi-exclamation-circle-fill me-2"></i>

                {{ session('error') }}

            </div>
        @endif


        {{-- =========================================================
             VALIDATION ERROR
        ========================================================== --}}

        @if ($errors->any())

            <div class="alert alert-danger border-0 rounded-3 mb-4">

                <div class="fw-semibold mb-2">

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    Terdapat kesalahan pada data.

                </div>

                <ul class="mb-0 ps-4">

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================================
             SUMMARY
        ========================================================== --}}

        <div class="summary-grid">

            {{-- TOTAL --}}

            <div class="summary-card">

                <div class="summary-icon orange">

                    <i class="bi bi-file-earmark-text-fill"></i>

                </div>

                <div>

                    <div class="summary-value">
                        {{ $permintaan->total() }}
                    </div>

                    <div class="summary-label">
                        Total Permintaan
                    </div>

                </div>

            </div>


            {{-- AKTIF --}}

            <div class="summary-card">

                <div class="summary-icon green">

                    <i class="bi bi-check-circle-fill"></i>

                </div>

                <div>

                    <div class="summary-value">

                        {{ $permintaan->filter(function ($item) {
                                return !$item->permintaan_expired || !$item->permintaan_expired->isPast();
                            })->count() }}

                    </div>

                    <div class="summary-label">
                        Aktif · Halaman Ini
                    </div>

                </div>

            </div>


            {{-- KEDALUWARSA --}}

            <div class="summary-card">

                <div class="summary-icon red">

                    <i class="bi bi-clock-history"></i>

                </div>

                <div>

                    <div class="summary-value">

                        {{ $permintaan->filter(function ($item) {
                                return $item->permintaan_expired && $item->permintaan_expired->isPast();
                            })->count() }}

                    </div>

                    <div class="summary-label">
                        Kedaluwarsa · Halaman Ini
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             LIST
        ========================================================== --}}

        <div class="permintaan-card">

            {{-- CARD HEADER --}}

            <div class="permintaan-card-header">

                <div>

                    <h5>
                        Daftar Permintaan
                    </h5>

                    <p>
                        Semua permintaan berkas yang telah dibuat
                    </p>

                </div>

            </div>


            {{-- CARD BODY --}}

            <div class="permintaan-card-body p-0">

                @if ($permintaan->isEmpty())

                    {{-- =================================================
                         EMPTY
                    ================================================== --}}

                    <div class="empty-state">

                        <div class="empty-icon">

                            <i class="bi bi-file-earmark-x"></i>

                        </div>

                        <h5>
                            Belum ada permintaan
                        </h5>

                        <p>
                            Belum ada permintaan berkas yang dibuat.
                        </p>

                        <a href="{{ route('admin.permintaan.berkas.create') }}" class="btn-permintaan-baru">

                            <i class="bi bi-plus-lg"></i>

                            Permintaan Berkas Baru

                        </a>

                    </div>
                @else
                    {{-- =================================================
                         TABLE
                    ================================================== --}}

                    <div class="table-responsive">

                        <table class="table permintaan-table align-middle mb-0">

                            <thead>

                                <tr>

                                    <th width="60">
                                        #
                                    </th>

                                    <th>
                                        Permintaan
                                    </th>

                                    <th>
                                        Jenis Berkas
                                    </th>

                                    <th>
                                        Periode
                                    </th>

                                    <th>
                                        Target
                                    </th>

                                    <th>
                                        Deadline
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th width="170">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($permintaan as $index => $item)
                                    @php

                                        /*
                                        |--------------------------------------------------------------------------
                                        | STATUS
                                        |--------------------------------------------------------------------------
                                        */

                                        $expired = $item->permintaan_expired && $item->permintaan_expired->isPast();

                                        /*
                                        |--------------------------------------------------------------------------
                                        | TARGET
                                        |--------------------------------------------------------------------------
                                        */

                                        $activeTargets = $item->target->where('target_status', true);

                                        $targetCount = $activeTargets->count();

                                    @endphp


                                    <tr>

                                        {{-- NOMOR --}}

                                        <td>

                                            <span class="row-number">

                                                {{ ($permintaan->firstItem() ?? 1) + $index }}

                                            </span>

                                        </td>


                                        {{-- PERMINTAAN --}}

                                        <td>

                                            <div class="request-title">

                                                {{ $item->permintaan_judul }}

                                            </div>


                                            @if ($item->permintaan_keterangan)
                                                <div class="request-description">

                                                    {{ $item->permintaan_keterangan }}

                                                </div>
                                            @endif

                                        </td>


                                        {{-- JENIS BERKAS --}}

                                        <td>

                                            <div class="jenis-wrapper">

                                                <span class="jenis-name">

                                                    {{ $item->jenisBerkas?->jenis_berkas_nama ?? '-' }}

                                                </span>


                                                @if ($item->jenisBerkas?->kategori)
                                                    <span class="kategori-name">

                                                        {{ $item->jenisBerkas->kategori->kategori_nama }}

                                                    </span>
                                                @endif

                                            </div>

                                        </td>


                                        {{-- PERIODE --}}

                                        <td>

                                            @if ($item->permintaan_tahun)
                                                <div class="periode-tahun">

                                                    {{ $item->permintaan_tahun }}

                                                </div>
                                            @endif


                                            @if ($item->permintaan_periode)
                                                <div class="periode-name">

                                                    {{ $item->permintaan_periode }}

                                                </div>
                                            @endif


                                            @if (!$item->permintaan_tahun && !$item->permintaan_periode)
                                                <span class="text-muted">
                                                    -
                                                </span>
                                            @endif

                                        </td>


                                        {{-- TARGET --}}

                                        <td>

                                            <div class="target-count">

                                                <i class="bi bi-people-fill"></i>

                                                {{ $targetCount }}

                                                <span>
                                                    jenis kerja
                                                </span>

                                            </div>


                                            @if ($targetCount > 0)
                                                <div class="target-list">

                                                    @foreach ($activeTargets->take(3) as $target)
                                                        <span class="target-badge">

                                                            {{ $target->jenisKerja?->jenis_kerja_nama ?? '-' }}

                                                        </span>
                                                    @endforeach


                                                    @if ($targetCount > 3)
                                                        <span class="target-more">

                                                            +{{ $targetCount - 3 }}

                                                        </span>
                                                    @endif

                                                </div>
                                            @endif

                                        </td>


                                        {{-- DEADLINE --}}

                                        <td>

                                            @if ($item->permintaan_expired)
                                                <div class="deadline">

                                                    <i class="bi bi-calendar-event"></i>

                                                    {{ $item->permintaan_expired->format('d/m/Y') }}

                                                </div>


                                                <div class="deadline-time">

                                                    {{ $item->permintaan_expired->format('H:i') }}

                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    Tidak ditentukan
                                                </span>
                                            @endif

                                        </td>


                                        {{-- STATUS --}}

                                        <td>

                                            @if ($expired)
                                                <span class="status-badge expired">

                                                    <i class="bi bi-clock-history"></i>

                                                    Kedaluwarsa

                                                </span>
                                            @else
                                                <span class="status-badge active">

                                                    <i class="bi bi-check-circle-fill"></i>

                                                    Aktif

                                                </span>
                                            @endif

                                        </td>


                                        {{-- AKSI --}}

                                        <td>

                                            <div class="action-group">

                                                {{-- EDIT --}}

                                                <button type="button" class="btn-action btn-edit" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditPermintaan{{ $item->permintaan_id }}"
                                                    title="Edit Permintaan">

                                                    <i class="bi bi-pencil-square"></i>

                                                </button>


                                                {{-- REKAP --}}

                                                <a href="{{ route('admin.rekap.berkas.index', [
                                                    'permintaanUid' => $item->permintaan_uid,
                                                ]) }}"
                                                    class="btn-lihat">

                                                    <i class="bi bi-bar-chart-fill"></i>

                                                    Lihat Rekap

                                                </a>

                                            </div>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- =================================================
                         PAGINATION
                    ================================================== --}}

                    @if ($permintaan->hasPages())
                        <div class="pagination-wrapper">

                            <div class="pagination-info">

                                Menampilkan

                                <strong>
                                    {{ $permintaan->firstItem() }}
                                </strong>

                                –

                                <strong>
                                    {{ $permintaan->lastItem() }}
                                </strong>

                                dari

                                <strong>
                                    {{ $permintaan->total() }}
                                </strong>

                                permintaan

                            </div>


                            <div class="pagination-container">

                                {{ $permintaan->onEachSide(1)->links('pagination::bootstrap-5') }}

                            </div>

                        </div>
                    @endif

                @endif

            </div>

        </div>

    </div>


    {{-- =============================================================
         MODAL EDIT
    ============================================================= --}}

    @foreach ($permintaan as $item)
        <div class="modal fade" id="modalEditPermintaan{{ $item->permintaan_id }}" tabindex="-1"
            aria-labelledby="modalEditPermintaanLabel{{ $item->permintaan_id }}" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-xl">

                <div class="modal-content modal-edit-permintaan">

                    {{-- MODAL HEADER --}}

                    <div class="modal-header">

                        <div class="modal-title-wrapper">

                            <div class="modal-edit-icon">

                                <i class="bi bi-pencil-square"></i>

                            </div>

                            <div>

                                <h5 class="modal-title" id="modalEditPermintaanLabel{{ $item->permintaan_id }}">

                                    Edit Permintaan Berkas

                                </h5>

                                <p>

                                    Perbarui informasi dan target permintaan berkas

                                </p>

                            </div>

                        </div>


                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>


                    {{-- FORM --}}

                    <form method="POST"
                        action="{{ route('admin.permintaan.berkas.update', [
                            'permintaanUid' => $item->permintaan_uid,
                        ]) }}"
                        class="form-edit-permintaan">

                        @csrf

                        @method('PUT')


                        <div class="modal-body">

                            {{-- =================================================
                                 INFORMASI PERMINTAAN
                            ================================================== --}}

                            <div class="edit-section">

                                <div class="edit-section-title">

                                    <i class="bi bi-file-earmark-text"></i>

                                    Informasi Permintaan

                                </div>


                                <div class="row g-3">

                                    {{-- JENIS BERKAS --}}

                                    <div class="col-md-6">

                                        <label class="edit-label">

                                            Jenis Berkas

                                            <span>*</span>

                                        </label>


                                        <select name="permintaan_jenis_berkas_id" class="form-select edit-input" required>

                                            <option value="">
                                                -- Pilih Jenis Berkas --
                                            </option>


                                            @foreach ($jenisBerkas ?? collect() as $jenis)
                                                <option value="{{ $jenis->jenis_berkas_id }}"
                                                    @selected((int) $item->permintaan_jenis_berkas_id === (int) $jenis->jenis_berkas_id)>

                                                    {{ $jenis->jenis_berkas_nama }}

                                                </option>
                                            @endforeach

                                        </select>

                                    </div>


                                    {{-- TAHUN --}}

                                    <div class="col-md-3">

                                        <label class="edit-label">

                                            Tahun

                                            <span>*</span>

                                        </label>


                                        <input type="number" name="permintaan_tahun" class="form-control edit-input"
                                            value="{{ $item->permintaan_tahun }}" min="2000" max="2100"
                                            required>

                                    </div>


                                    {{-- PERIODE --}}

                                    <div class="col-md-3">

                                        <label class="edit-label">

                                            Periode

                                        </label>


                                        <input type="text" name="permintaan_periode" class="form-control edit-input"
                                            value="{{ $item->permintaan_periode }}" maxlength="100"
                                            placeholder="Contoh: TW I">

                                    </div>


                                    {{-- JUDUL --}}

                                    <div class="col-md-8">

                                        <label class="edit-label">

                                            Judul Permintaan

                                            <span>*</span>

                                        </label>


                                        <input type="text" name="permintaan_judul" class="form-control edit-input"
                                            value="{{ $item->permintaan_judul }}" maxlength="255" required>

                                    </div>


                                    {{-- TOMBOL --}}

                                    <div class="col-md-4">

                                        <label class="edit-label">

                                            Nama Tombol

                                            <span>*</span>

                                        </label>


                                        <input type="text" name="permintaan_tombol" class="form-control edit-input"
                                            value="{{ $item->permintaan_tombol }}" maxlength="100" required>

                                    </div>


                                    {{-- MULAI --}}

                                    <div class="col-md-6">

                                        <label class="edit-label">

                                            Mulai Pengumpulan

                                            <span>*</span>

                                        </label>


                                        <input type="datetime-local" name="permintaan_mulai"
                                            class="form-control edit-input"
                                            value="{{ $item->permintaan_mulai?->format('Y-m-d\TH:i') }}" required>

                                    </div>


                                    {{-- DEADLINE --}}

                                    <div class="col-md-6">

                                        <label class="edit-label">

                                            Deadline

                                            <span>*</span>

                                        </label>


                                        <input type="datetime-local" name="permintaan_expired"
                                            class="form-control edit-input"
                                            value="{{ $item->permintaan_expired?->format('Y-m-d\TH:i') }}" required>

                                    </div>


                                    {{-- KETERANGAN --}}

                                    <div class="col-12">

                                        <label class="edit-label">

                                            Keterangan

                                        </label>


                                        <textarea name="permintaan_keterangan" class="form-control edit-input" rows="3"
                                            placeholder="Keterangan tambahan...">{{ $item->permintaan_keterangan }}</textarea>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 TARGET JENIS KERJA
                            ================================================== --}}

                            <div class="edit-section">

                                <div class="target-header">

                                    <div>

                                        <div class="edit-section-title mb-1">

                                            <i class="bi bi-people-fill"></i>

                                            Target Jenis Kerja

                                        </div>

                                        <div class="edit-section-description">

                                            Tentukan jenis kerja dan folder Drive
                                            untuk masing-masing target.

                                        </div>

                                    </div>


                                    <button type="button" class="btn-tambah-target"
                                        onclick="tambahTargetEdit({{ $item->permintaan_id }})">

                                        <i class="bi bi-plus-lg"></i>

                                        Tambah Target

                                    </button>

                                </div>


                                <div class="target-edit-list" id="targetEditList{{ $item->permintaan_id }}">

                                    @forelse ($item->target->where('target_status', true)
                                            as $target)
                                        <div class="target-edit-row">

                                            {{-- JENIS KERJA --}}

                                            <div class="target-edit-field">

                                                <label class="edit-label">

                                                    Jenis Kerja

                                                    <span>*</span>

                                                </label>


                                                <select name="jenis_kerja[]"
                                                    class="form-select edit-input target-jenis-kerja" required>

                                                    <option value="">
                                                        -- Pilih Jenis Kerja --
                                                    </option>


                                                    @foreach ($jenisKerja ?? collect() as $jenis)
                                                        <option value="{{ $jenis->jenis_kerja_id }}"
                                                            @selected((int) $target->target_jenis_kerja_id === (int) $jenis->jenis_kerja_id)>

                                                            {{ $jenis->jenis_kerja_nama }}

                                                        </option>
                                                    @endforeach

                                                </select>

                                            </div>


                                            {{-- FOLDER --}}

                                            <div class="target-edit-field">

                                                <label class="edit-label">

                                                    Folder Drive

                                                    <span>*</span>

                                                </label>


                                                <select name="folder_id[]" class="form-select edit-input target-folder"
                                                    required>

                                                    <option value="">
                                                        -- Pilih Folder Drive --
                                                    </option>


                                                    @foreach ($folders ?? collect() as $folder)
                                                        <option value="{{ $folder->folder_id }}"
                                                            @selected((int) $target->target_folder_id === (int) $folder->folder_id)>

                                                            {{ $folder->folder_nama }}

                                                        </option>
                                                    @endforeach

                                                </select>

                                            </div>


                                            {{-- HAPUS --}}

                                            <button type="button" class="btn-hapus-target"
                                                onclick="hapusTargetEdit(this)" title="Hapus Target">

                                                <i class="bi bi-trash3"></i>

                                            </button>

                                        </div>

                                    @empty

                                        <div class="target-empty">

                                            <i class="bi bi-people"></i>

                                            <span>
                                                Belum ada target jenis kerja.
                                            </span>

                                        </div>
                                    @endforelse

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             FOOTER
                        ================================================== --}}

                        <div class="modal-footer">

                            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">

                                Batal

                            </button>


                            <button type="submit" class="btn-modal-save">

                                <i class="bi bi-check-lg"></i>

                                Simpan Perubahan

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    @endforeach


    {{-- =============================================================
         JAVASCRIPT
    ============================================================= --}}

    <script>
        /*
            |--------------------------------------------------------------------------
            | DATA DARI LARAVEL
            |--------------------------------------------------------------------------
            */

        const samperinJenisKerja = @json($jenisKerjaData);

        const samperinFolders = @json($folderData);


        /*
        |--------------------------------------------------------------------------
        | TAMBAH TARGET EDIT
        |--------------------------------------------------------------------------
        */

        function tambahTargetEdit(permintaanId) {

            const container = document.getElementById(
                'targetEditList' + permintaanId
            );

            if (!container) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | HAPUS EMPTY STATE
            |--------------------------------------------------------------------------
            */

            const emptyState =
                container.querySelector('.target-empty');

            if (emptyState) {
                emptyState.remove();
            }


            /*
            |--------------------------------------------------------------------------
            | BUAT ROW
            |--------------------------------------------------------------------------
            */

            const row = document.createElement('div');

            row.className = 'target-edit-row';


            /*
            |--------------------------------------------------------------------------
            | OPTIONS JENIS KERJA
            |--------------------------------------------------------------------------
            */

            let jenisKerjaOptions = `
                <option value="">
                    -- Pilih Jenis Kerja --
                </option>
            `;


            samperinJenisKerja.forEach(function(jenis) {

                jenisKerjaOptions += `
                    <option value="${jenis.id}">
                        ${escapeHtml(jenis.nama)}
                    </option>
                `;

            });


            /*
            |--------------------------------------------------------------------------
            | OPTIONS FOLDER
            |--------------------------------------------------------------------------
            */

            let folderOptions = `
                <option value="">
                    -- Pilih Folder Drive --
                </option>
            `;


            samperinFolders.forEach(function(folder) {

                folderOptions += `
                    <option value="${folder.id}">
                        ${escapeHtml(folder.nama)}
                    </option>
                `;

            });


            /*
            |--------------------------------------------------------------------------
            | HTML ROW
            |--------------------------------------------------------------------------
            */

            row.innerHTML = `

                <div class="target-edit-field">

                    <label class="edit-label">

                        Jenis Kerja

                        <span>*</span>

                    </label>

                    <select
                        name="jenis_kerja[]"
                        class="form-select edit-input target-jenis-kerja"
                        required>

                        ${jenisKerjaOptions}

                    </select>

                </div>


                <div class="target-edit-field">

                    <label class="edit-label">

                        Folder Drive

                        <span>*</span>

                    </label>

                    <select
                        name="folder_id[]"
                        class="form-select edit-input target-folder"
                        required>

                        ${folderOptions}

                    </select>

                </div>


                <button
                    type="button"
                    class="btn-hapus-target"
                    onclick="hapusTargetEdit(this)"
                    title="Hapus Target">

                    <i class="bi bi-trash3"></i>

                </button>

            `;


            container.appendChild(row);


            updateNomorTargetEdit(container);

            updateDuplicateJenisKerja(container);

        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS TARGET
        |--------------------------------------------------------------------------
        */

        function hapusTargetEdit(button) {

            const row =
                button.closest('.target-edit-row');

            if (!row) {
                return;
            }


            const container =
                row.parentElement;


            const rows =
                container.querySelectorAll(
                    '.target-edit-row'
                );


            /*
            |--------------------------------------------------------------------------
            | MINIMAL SATU TARGET
            |--------------------------------------------------------------------------
            */

            if (rows.length <= 1) {

                alert(
                    'Minimal harus ada satu jenis kerja sebagai target.'
                );

                return;
            }


            row.remove();


            updateNomorTargetEdit(container);

            updateDuplicateJenisKerja(container);

        }


        /*
        |--------------------------------------------------------------------------
        | NOMOR TARGET
        |--------------------------------------------------------------------------
        */

        function updateNomorTargetEdit(container) {

            const rows =
                container.querySelectorAll(
                    '.target-edit-row'
                );


            rows.forEach(function(row, index) {

                let number =
                    row.querySelector('.target-row-number');


                /*
                | Tidak digunakan pada desain grid sekarang.
                | Tetap disediakan jika nanti ditambahkan nomor.
                */

                if (number) {
                    number.textContent = index + 1;
                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | CEGAH JENIS KERJA DUPLIKAT
        |--------------------------------------------------------------------------
        */

        function updateDuplicateJenisKerja(container) {

            const selects =
                container.querySelectorAll(
                    '.target-jenis-kerja'
                );


            const selectedValues = [];


            selects.forEach(function(select) {

                if (select.value) {

                    selectedValues.push(
                        String(select.value)
                    );

                }

            });


            selects.forEach(function(select) {

                const currentValue =
                    String(select.value || '');


                Array.from(select.options)
                    .forEach(function(option) {

                        if (!option.value) {
                            return;
                        }


                        const value =
                            String(option.value);


                        option.disabled =
                            selectedValues.includes(value) &&
                            value !== currentValue;

                    });

            });

        }


        /*
        |--------------------------------------------------------------------------
        | EVENT JENIS KERJA
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'change',
            function(event) {

                if (
                    !event.target.classList.contains(
                        'target-jenis-kerja'
                    )
                ) {
                    return;
                }


                const container =
                    event.target.closest(
                        '.target-edit-list'
                    );


                if (!container) {
                    return;
                }


                const selectedValue =
                    event.target.value;


                if (!selectedValue) {
                    updateDuplicateJenisKerja(container);
                    return;
                }


                const allSelects =
                    container.querySelectorAll(
                        '.target-jenis-kerja'
                    );


                let duplicate = false;


                allSelects.forEach(function(select) {

                    if (
                        select !== event.target &&
                        select.value === selectedValue
                    ) {

                        duplicate = true;

                    }

                });


                if (duplicate) {

                    alert(
                        'Jenis kerja tersebut sudah dipilih pada target lain.'
                    );


                    event.target.value = '';

                }


                updateDuplicateJenisKerja(container);

            }
        );


        /*
        |--------------------------------------------------------------------------
        | ESCAPE HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value) {

            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');

        }


        /*
        |--------------------------------------------------------------------------
        | INIT SEMUA MODAL
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'DOMContentLoaded',
            function() {

                document
                    .querySelectorAll('.target-edit-list')
                    .forEach(function(container) {

                        updateNomorTargetEdit(container);

                        updateDuplicateJenisKerja(
                            container
                        );

                    });


                /*
                |--------------------------------------------------------------------------
                | SUBMIT BUTTON
                |--------------------------------------------------------------------------
                */

                document
                    .querySelectorAll('.form-edit-permintaan')
                    .forEach(function(form) {

                        form.addEventListener(
                            'submit',
                            function() {

                                const button =
                                    form.querySelector(
                                        '.btn-modal-save'
                                    );


                                if (!button) {
                                    return;
                                }


                                button.disabled = true;


                                button.innerHTML = `
                                    <span
                                        class="spinner-border spinner-border-sm"
                                        role="status"
                                        aria-hidden="true">
                                    </span>

                                    Menyimpan...
                                `;

                            }
                        );

                    });

            }
        );
    </script>


    {{-- =============================================================
         STYLE
    ============================================================= --}}

    <style>
        /* =========================================================
               PAGE
            ========================================================== */

        .permintaan-index-page {
            max-width: 1400px;
            margin: 0 auto;
        }


        /* =========================================================
               HEADER
            ========================================================== */

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }


        .page-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }


        .page-icon {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            background: rgba(242, 140, 40, .12);
            color: #f28c28;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            flex-shrink: 0;
        }


        .page-header h4 {
            margin: 0;
            color: #182238;
            font-weight: 700;
        }


        .page-header p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 14px;
        }


        /* =========================================================
               BUTTON
            ========================================================== */

        .btn-permintaan-baru {
            min-height: 44px;
            padding: 0 18px;
            border-radius: 10px;
            background: #f28c28;
            border: 1px solid #f28c28;
            color: #fff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: .2s ease;
            white-space: nowrap;
        }


        .btn-permintaan-baru:hover {
            background: #dc7818;
            border-color: #dc7818;
            color: #fff;
            transform: translateY(-1px);
        }


        /* =========================================================
               SUMMARY
            ========================================================== */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }


        .summary-card {
            background: #fff;
            border: 1px solid #e9edf3;
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 4px 18px rgba(24, 34, 56, .04);
        }


        .summary-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }


        .summary-icon.orange {
            background: rgba(242, 140, 40, .12);
            color: #f28c28;
        }


        .summary-icon.green {
            background: rgba(34, 197, 94, .12);
            color: #16a34a;
        }


        .summary-icon.red {
            background: rgba(239, 68, 68, .12);
            color: #dc2626;
        }


        .summary-value {
            color: #182238;
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
        }


        .summary-label {
            color: #7b8494;
            font-size: 12px;
            margin-top: 5px;
        }


        /* =========================================================
               CARD
            ========================================================== */

        .permintaan-card {
            background: #fff;
            border: 1px solid #e9edf3;
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(24, 34, 56, .04);
            overflow: hidden;
        }


        .permintaan-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #edf0f4;
        }


        .permintaan-card-header h5 {
            margin: 0;
            color: #182238;
            font-size: 16px;
            font-weight: 700;
        }


        .permintaan-card-header p {
            margin: 4px 0 0;
            color: #7b8494;
            font-size: 13px;
        }


        /* =========================================================
               TABLE
            ========================================================== */

        .permintaan-table {
            min-width: 1150px;
        }


        .permintaan-table thead th {
            background: #f8fafc;
            color: #6b7280;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 1px solid #e9edf3;
            padding: 13px 16px;
            white-space: nowrap;
        }


        .permintaan-table tbody td {
            padding: 16px;
            border-bottom: 1px solid #eef1f5;
            vertical-align: middle;
        }


        .permintaan-table tbody tr:last-child td {
            border-bottom: 0;
        }


        .permintaan-table tbody tr:hover {
            background: #fafbfc;
        }


        /* =========================================================
               ROW NUMBER
            ========================================================== */

        .row-number {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: #f3f5f8;
            color: #6b7280;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }


        /* =========================================================
               REQUEST
            ========================================================== */

        .request-title {
            color: #182238;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.4;
        }


        .request-description {
            color: #8a93a2;
            font-size: 12px;
            margin-top: 4px;
            max-width: 280px;
            line-height: 1.4;
        }


        /* =========================================================
               JENIS
            ========================================================== */

        .jenis-wrapper {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }


        .jenis-name {
            color: #374151;
            font-size: 13px;
            font-weight: 600;
        }


        .kategori-name {
            color: #8a93a2;
            font-size: 11px;
        }


        /* =========================================================
               PERIODE
            ========================================================== */

        .periode-tahun {
            color: #374151;
            font-size: 13px;
            font-weight: 700;
        }


        .periode-name {
            color: #8a93a2;
            font-size: 12px;
            margin-top: 2px;
        }


        /* =========================================================
               TARGET
            ========================================================== */

        .target-count {
            color: #374151;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }


        .target-count i {
            color: #f28c28;
            margin-right: 4px;
        }


        .target-count span {
            color: #8a93a2;
            font-weight: 400;
        }


        .target-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: 6px;
            max-width: 190px;
        }


        .target-badge,
        .target-more {
            background: #f5f7fa;
            border: 1px solid #e8ecf1;
            color: #6b7280;
            border-radius: 6px;
            padding: 3px 6px;
            font-size: 10px;
            line-height: 1.2;
        }


        .target-more {
            color: #f28c28;
            background: rgba(242, 140, 40, .08);
            border-color: rgba(242, 140, 40, .15);
        }


        /* =========================================================
               DEADLINE
            ========================================================== */

        .deadline {
            color: #374151;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }


        .deadline i {
            color: #f28c28;
            margin-right: 4px;
        }


        .deadline-time {
            color: #8a93a2;
            font-size: 11px;
            margin-top: 3px;
        }


        /* =========================================================
               STATUS
            ========================================================== */

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 9px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }


        .status-badge.active {
            color: #15803d;
            background: #f0fdf4;
            border: 1px solid #dcfce7;
        }


        .status-badge.expired {
            color: #b91c1c;
            background: #fef2f2;
            border: 1px solid #fee2e2;
        }


        /* =========================================================
               ACTION
            ========================================================== */

        .action-group {
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }


        .btn-action {
            width: 36px;
            height: 36px;
            padding: 0;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border: 1px solid #e3e7ed;
            background: #fff;
            cursor: pointer;
            transition: .2s ease;
            flex-shrink: 0;
        }


        .btn-edit {
            color: #182238;
        }


        .btn-edit:hover {
            color: #f28c28;
            border-color: #f28c28;
            background: rgba(242, 140, 40, .06);
        }


        .btn-lihat {
            min-height: 36px;
            padding: 0 11px;
            border-radius: 8px;
            border: 1px solid #e3e7ed;
            background: #fff;
            color: #374151;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
            transition: .2s ease;
        }


        .btn-lihat:hover {
            color: #f28c28;
            border-color: #f28c28;
            background: rgba(242, 140, 40, .05);
        }


        /* =========================================================
               PAGINATION
            ========================================================== */

        .pagination-wrapper {
            padding: 16px 20px;
            border-top: 1px solid #eef1f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }


        .pagination-info {
            color: #7b8494;
            font-size: 12px;
        }


        .pagination-info strong {
            color: #374151;
        }


        .pagination-container {
            display: flex;
            align-items: center;
        }


        .pagination-container .pagination {
            margin: 0;
            gap: 4px;
        }


        .pagination-container .page-item {
            margin: 0;
        }


        .pagination-container .page-link {
            color: #374151;
            border: 1px solid #e5e7eb;
            background: #fff;
            font-size: 12px;
            min-width: 34px;
            height: 34px;
            border-radius: 8px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 9px;
            box-shadow: none;
        }


        .pagination-container .page-link:hover {
            color: #f28c28;
            border-color: #f28c28;
            background: rgba(242, 140, 40, .05);
        }


        .pagination-container .page-item.active .page-link {
            background: #f28c28;
            border-color: #f28c28;
            color: #fff;
        }


        .pagination-container .page-item.disabled .page-link {
            color: #b0b6c0;
            background: #f8fafc;
            border-color: #e5e7eb;
        }


        /* =========================================================
               EMPTY
            ========================================================== */

        .empty-state {
            padding: 70px 20px;
            text-align: center;
        }


        .empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: #f5f7fa;
            color: #9ca3af;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 16px;
        }


        .empty-state h5 {
            color: #374151;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 6px;
        }


        .empty-state p {
            color: #8a93a2;
            font-size: 13px;
            margin-bottom: 20px;
        }


        /* =========================================================
               EDIT MODAL
            ========================================================== */

        .modal-edit-permintaan {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(24, 34, 56, .18);
        }


        .modal-edit-permintaan .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #edf0f4;
            background: #fff;
        }


        .modal-title-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }


        .modal-edit-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            background: rgba(242, 140, 40, .12);
            color: #f28c28;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }


        .modal-title {
            color: #182238;
            font-size: 17px;
            font-weight: 700;
            margin: 0;
        }


        .modal-title-wrapper p {
            margin: 3px 0 0;
            color: #8a93a2;
            font-size: 12px;
        }


        .modal-edit-permintaan .modal-body {
            padding: 24px;
            max-height: 70vh;
            overflow-y: auto;
        }


        .edit-section {
            padding: 18px;
            border: 1px solid #e9edf3;
            border-radius: 14px;
            background: #fff;
            margin-bottom: 18px;
        }


        .edit-section:last-child {
            margin-bottom: 0;
        }


        .edit-section-title {
            color: #182238;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 16px;
        }


        .edit-section-title i {
            color: #f28c28;
        }


        .edit-section-description {
            color: #8a93a2;
            font-size: 12px;
        }


        .edit-label {
            display: block;
            color: #4b5563;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
        }


        .edit-label span {
            color: #dc2626;
        }


        .edit-input {
            border-color: #e1e6ed;
            border-radius: 9px;
            font-size: 13px;
            min-height: 40px;
            box-shadow: none !important;
        }


        .edit-input:focus {
            border-color: #f28c28;
            box-shadow: 0 0 0 .2rem rgba(242, 140, 40, .08) !important;
        }


        textarea.edit-input {
            min-height: auto;
        }


        /* =========================================================
               TARGET HEADER
            ========================================================== */

        .target-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 16px;
        }


        .btn-tambah-target {
            min-height: 36px;
            padding: 0 12px;
            border-radius: 8px;
            border: 1px solid #f28c28;
            background: rgba(242, 140, 40, .08);
            color: #f28c28;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            white-space: nowrap;
        }


        .btn-tambah-target:hover {
            background: #f28c28;
            color: #fff;
        }


        /* =========================================================
               TARGET ROW
            ========================================================== */

        .target-edit-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }


        .target-edit-row {
            display: grid;
            grid-template-columns:
                minmax(200px, .8fr) minmax(300px, 1.5fr) 38px;

            align-items: end;
            gap: 10px;

            padding: 12px;

            background: #f8fafc;
            border: 1px solid #e9edf3;
            border-radius: 10px;
        }


        .target-edit-field {
            min-width: 0;
        }


        .target-edit-field .form-select {
            width: 100%;
        }


        .target-edit-row select {
            min-height: 40px;
        }


        .btn-hapus-target {
            width: 38px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #fee2e2;
            background: #fff5f5;
            color: #dc2626;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }


        .btn-hapus-target:hover {
            background: #dc2626;
            border-color: #dc2626;
            color: #fff;
        }


        .target-empty {
            min-height: 80px;
            border: 1px dashed #dce1e8;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #8a93a2;
            font-size: 12px;
        }


        /* =========================================================
               MODAL FOOTER
            ========================================================== */

        .modal-edit-permintaan .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #edf0f4;
            background: #fafbfc;
        }


        .btn-modal-cancel {
            min-height: 40px;
            padding: 0 15px;
            border-radius: 9px;
            border: 1px solid #e1e5eb;
            background: #fff;
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
        }


        .btn-modal-cancel:hover {
            background: #f8fafc;
        }


        .btn-modal-save {
            min-height: 40px;
            padding: 0 16px;
            border-radius: 9px;
            border: 1px solid #f28c28;
            background: #f28c28;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }


        .btn-modal-save:hover {
            background: #dc7818;
            border-color: #dc7818;
            color: #fff;
        }


        .btn-modal-save:disabled {
            opacity: .7;
            cursor: not-allowed;
        }


        /* =========================================================
               RESPONSIVE
            ========================================================== */

        @media (max-width: 991.98px) {

            .summary-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 767.98px) {

            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }


            .page-header-left {
                align-items: flex-start;
            }


            .btn-permintaan-baru {
                width: 100%;
            }


            .summary-grid {
                gap: 10px;
            }


            .summary-card {
                padding: 15px;
            }


            .permintaan-card-header {
                padding: 18px;
            }


            .pagination-wrapper {
                align-items: flex-start;
                flex-direction: column;
            }


            .pagination-container {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 2px;
            }


            .pagination-container .pagination {
                flex-wrap: nowrap;
            }


            .modal-edit-permintaan .modal-body {
                padding: 16px;
            }


            .target-header {
                align-items: flex-start;
                flex-direction: column;
            }


            .btn-tambah-target {
                width: 100%;
                justify-content: center;
            }


            .target-edit-row {
                grid-template-columns: 1fr;
            }


            .btn-hapus-target {
                width: 100%;
            }

        }
    </style>

@endsection
