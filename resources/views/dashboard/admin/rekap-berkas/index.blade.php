@extends('dashboard.layouts.app')

@section('title', 'Rekap Berkas')

@section('content')

    <div class="rekap-page">

        {{-- =========================================================
         HEADER
        ========================================================== --}}
        <div class="page-header">

            <div class="page-header-left">

                <div class="page-icon">
                    <i class="bi bi-file-earmark-check-fill"></i>
                </div>

                <div>
                    <h4>Rekap Pengumpulan Berkas</h4>

                    <p>
                        Pantau pengumpulan
                        <strong>
                            {{ $permintaan->permintaan_judul }}
                        </strong>
                        berdasarkan jenis kerja pegawai.
                    </p>
                </div>

            </div>

            <a href="{{ route('admin.permintaan.berkas.index') }}" class="btn-kembali">

                <i class="bi bi-arrow-left"></i>

                <span>
                    Kembali
                </span>

            </a>

        </div>


        {{-- =========================================================
         ALERT
        ========================================================== --}}
        @if (session('success'))
            <div class="alert alert-success border-0 rounded-3 mb-4">

                <i class="bi bi-check-circle-fill me-2"></i>

                {{ session('success') }}

            </div>
        @endif


        @if (session('error'))
            <div class="alert alert-danger border-0 rounded-3 mb-4">

                <i class="bi bi-exclamation-circle-fill me-2"></i>

                {{ session('error') }}

            </div>
        @endif


        {{-- =========================================================
         REQUEST HERO
        ========================================================== --}}
        <div class="request-hero">

            <div class="request-hero-content">

                <div class="request-category">

                    {{ $permintaan->jenisBerkas?->kategori?->kategori_nama ?? 'Berkas' }}

                </div>

                <div class="request-title">

                    {{ $permintaan->permintaan_judul }}

                </div>

                <div class="request-meta">

                    <span>
                        <i class="bi bi-folder2-open"></i>
                        {{ $permintaan->jenisBerkas?->jenis_berkas_nama ?? '-' }}
                    </span>

                    @if ($permintaan->permintaan_tahun)
                        <span>
                            <i class="bi bi-calendar3"></i>
                            {{ $permintaan->permintaan_tahun }}
                        </span>
                    @endif

                    @if ($permintaan->permintaan_periode)
                        <span>
                            <i class="bi bi-clock"></i>
                            {{ $permintaan->permintaan_periode }}
                        </span>
                    @endif

                    @if ($permintaan->permintaan_expired)
                        <span>
                            <i class="bi bi-calendar-event"></i>
                            Deadline
                            {{ $permintaan->permintaan_expired->format('d/m/Y H:i') }}
                        </span>
                    @endif

                </div>

                @if ($permintaan->permintaan_keterangan)
                    <div class="request-description">

                        {{ $permintaan->permintaan_keterangan }}

                    </div>
                @endif

            </div>


            <div class="request-progress">

                <div class="progress-label">
                    Tingkat Pengumpulan
                </div>

                <div class="progress-percent">
                    {{ number_format($persentase, 1) }}%
                </div>

                <div class="hero-progress">

                    <div class="hero-progress-bar" style="width: {{ min(100, max(0, $persentase)) }}%;">
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
         SUMMARY
        ========================================================== --}}
        <div class="summary-grid">

            {{-- TOTAL --}}
            <div class="summary-card">

                <div class="summary-label">
                    Total Target
                </div>

                <div class="summary-value">
                    {{ number_format($totalPegawai) }}
                </div>

            </div>


            {{-- SUDAH --}}
            <div class="summary-card">

                <div class="summary-label">
                    Sudah Mengumpulkan
                </div>

                <div class="summary-value green">
                    {{ number_format($totalSudah) }}
                </div>

            </div>


            {{-- BELUM --}}
            <div class="summary-card">

                <div class="summary-label">
                    Belum Mengumpulkan
                </div>

                <div class="summary-value gray">
                    {{ number_format($totalBelum) }}
                </div>

            </div>

        </div>


        {{-- =========================================================
         REKAP PER JENIS KERJA
        ========================================================== --}}
        <div class="rekap-jenis-card">

            <div class="rekap-jenis-header">

                <div>

                    <h5>
                        Rekap Berdasarkan Jenis Kerja
                    </h5>

                    <p>
                        Progress pengumpulan berkas berdasarkan jenis kerja pegawai.
                    </p>

                </div>

            </div>


            <div class="rekap-jenis-grid">

                @forelse ($perJenisKerja as $item)
                    <div class="rekap-jenis-item">

                        <div class="rekap-jenis-top">

                            <div class="rekap-jenis-name">

                                <div class="rekap-jenis-icon">
                                    <i class="bi bi-people-fill"></i>
                                </div>

                                <div>

                                    <div class="rekap-jenis-title">
                                        {{ $item['nama'] }}
                                    </div>

                                    <div class="rekap-jenis-total">
                                        {{ number_format($item['total']) }} pegawai
                                    </div>

                                </div>

                            </div>


                            <div class="rekap-jenis-percent">

                                {{ number_format($item['persentase'], 1) }}%

                            </div>

                        </div>


                        {{-- PROGRESS --}}
                        <div class="rekap-progress">

                            <div class="rekap-progress-bar" style="width: {{ min(100, max(0, $item['persentase'])) }}%;">
                            </div>

                        </div>


                        {{-- DETAIL --}}
                        <div class="rekap-jenis-detail">

                            <div class="rekap-detail-sudah">

                                <span class="rekap-detail-dot"></span>

                                <span>
                                    Sudah
                                </span>

                                <strong>
                                    {{ number_format($item['sudah']) }}
                                </strong>

                            </div>


                            <div class="rekap-detail-belum">

                                <span class="rekap-detail-dot"></span>

                                <span>
                                    Belum
                                </span>

                                <strong>
                                    {{ number_format($item['belum']) }}
                                </strong>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="rekap-empty">

                        <i class="bi bi-people"></i>

                        <div>
                            Belum ada data rekap berdasarkan jenis kerja.
                        </div>

                    </div>
                @endforelse

            </div>

        </div>


        {{-- =========================================================
         FILTER
        ========================================================== --}}
        <div class="filter-card">

            <form method="GET" action="{{ route('admin.rekap.berkas.index') }}" class="filter-form">

                <input type="hidden" name="permintaanUid" value="{{ $permintaanUid }}">


                {{-- JENIS KERJA --}}
                <div class="filter-group">

                    <label>
                        Jenis Kerja
                    </label>

                    <select name="jenis_kerja" class="form-select">

                        <option value="">
                            Semua Jenis Kerja
                        </option>

                        @foreach ($jenisKerjaList as $jenis)
                            <option value="{{ $jenis->jenis_kerja_id }}" @selected((string) request('jenis_kerja') === (string) $jenis->jenis_kerja_id)>

                                {{ $jenis->jenis_kerja_nama . ' (' . $jenis->jenis_kerja_kode . ')' }}

                            </option>
                        @endforeach

                    </select>

                </div>


                {{-- STATUS --}}
                <div class="filter-group">

                    <label>
                        Status
                    </label>

                    <select name="status" class="form-select">

                        <option value="all" @selected(request('status', 'all') === 'all')>
                            Semua
                        </option>

                        <option value="sudah" @selected(request('status') === 'sudah')>
                            Sudah Mengumpulkan
                        </option>

                        <option value="belum" @selected(request('status') === 'belum')>
                            Belum Mengumpulkan
                        </option>

                    </select>

                </div>


                {{-- SEARCH --}}
                <div class="filter-search">

                    <label>
                        Cari Pegawai
                    </label>

                    <div class="search-input">

                        <i class="bi bi-search"></i>

                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari nama, NIP, atau NIK...">

                    </div>

                </div>


                {{-- BUTTON --}}
                <div class="filter-actions">

                    <button type="submit" class="btn-filter">

                        <i class="bi bi-funnel-fill"></i>

                        Filter

                    </button>


                    <a href="{{ route('admin.rekap.berkas.index', [
                        'permintaanUid' => $permintaanUid,
                    ]) }}"
                        class="btn-reset">

                        <i class="bi bi-arrow-counterclockwise"></i>

                        Reset

                    </a>

                </div>

            </form>

        </div>


        {{-- =========================================================
         TABLE CARD
        ========================================================== --}}
        <div class="pegawai-card">

            <div class="pegawai-card-header">

                <div>

                    <h5>
                        Daftar Pegawai
                    </h5>

                    <p>
                        Daftar target pengumpulan berkas untuk permintaan ini.
                    </p>

                </div>


                <div class="pegawai-count">

                    {{ number_format($rekap->total()) }}
                    pegawai

                </div>

            </div>


            {{-- TABLE --}}
            @if ($rekap->isEmpty())

                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="bi bi-person-x"></i>

                    </div>

                    <h5>
                        Data tidak ditemukan
                    </h5>

                    <p>
                        Tidak ada pegawai yang sesuai dengan filter yang dipilih.
                    </p>

                    <a href="{{ route('admin.rekap.berkas.index', [
                        'permintaanUid' => $permintaanUid,
                    ]) }}"
                        class="btn-reset">

                        <i class="bi bi-arrow-counterclockwise"></i>

                        Reset Filter

                    </a>

                </div>
            @else
                <div class="table-responsive">

                    <table class="table pegawai-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th width="55">
                                    #
                                </th>

                                <th>
                                    Pegawai
                                </th>

                                <th>
                                    Jenis Kerja
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Berkas
                                </th>

                                <th width="150">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($rekap as $index => $item)
                                @php

                                    $pegawai = $item['user'];
                                    $berkas = $item['berkas'];

                                    $nama = trim(
                                        ($pegawai->user_gelardepan ?? '') .
                                            ' ' .
                                            ($pegawai->user_nama ?? '') .
                                            ' ' .
                                            ($pegawai->user_gelarbelakang ?? ''),
                                    );
                                @endphp


                                <tr>

                                    {{-- NO --}}
                                    <td>

                                        <span class="row-number">

                                            {{ ($rekap->firstItem() ?? 1) + $index }}

                                        </span>

                                    </td>


                                    {{-- PEGAWAI --}}
                                    <td>

                                        <div class="pegawai-info">

                                            <div class="pegawai-avatar">
                                                @if ($pegawai->foto?->thumbnail_url)
                                                    <img src="{{ $pegawai->foto->thumbnail_url }}"
                                                        alt="{{ $pegawai->user_nama }}" class="pegawai-avatar-img">
                                                @else
                                                    {{ strtoupper(substr($pegawai->user_nama ?? 'P', 0, 1)) }}
                                                @endif
                                            </div>

                                            <div>

                                                <div class="pegawai-name">

                                                    {{ $nama }}

                                                </div>

                                                <div class="pegawai-identitas">

                                                    @if ($pegawai->user_nip)
                                                        NIP:
                                                        {{ $pegawai->user_nip }}
                                                    @else
                                                        NIK:
                                                        {{ $pegawai->user_nik ?? '-' }}
                                                    @endif

                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- JENIS KERJA --}}
                                    <td>

                                        <span class="jenis-badge">

                                            {{ $pegawai->jenisKerja?->jenis_kerja_nama ?? '-' }}

                                        </span>

                                    </td>


                                    {{-- STATUS --}}
                                    <td>

                                        @if ($item['sudah'])
                                            <span class="status-badge sudah">

                                                <i class="bi bi-check-circle-fill"></i>

                                                Sudah Mengumpulkan

                                            </span>
                                        @else
                                            <span class="status-badge belum">

                                                <i class="bi bi-clock"></i>

                                                Belum Mengumpulkan

                                            </span>
                                        @endif

                                    </td>


                                    {{-- BERKAS --}}
                                    <td>

                                        @if ($berkas)
                                            <div class="berkas-info">

                                                <div class="berkas-icon">

                                                    <i class="bi bi-file-earmark-check-fill"></i>

                                                </div>

                                                <div>

                                                    <div class="berkas-name">

                                                        {{ $berkas->pengumpulan_berkas_nama ?? 'Berkas' }}

                                                    </div>

                                                    <div class="berkas-date">

                                                        {{ $berkas->pengumpulan_berkas_tanggal ? $berkas->pengumpulan_berkas_tanggal->format('d/m/Y H:i') : '-' }}

                                                    </div>

                                                </div>

                                            </div>
                                        @else
                                            <span class="text-muted">

                                                Belum ada berkas

                                            </span>
                                        @endif

                                    </td>


                                    {{-- AKSI --}}
                                    <td>

                                        <div class="action-group">

                                            @if ($berkas)
                                                @if ($berkas->pengumpulan_berkas_file)
                                                    <a href="{{ $berkas->pengumpulan_berkas_file }}" target="_blank"
                                                        class="btn-action view" title="Lihat Berkas">

                                                        <i class="bi bi-eye-fill"></i>

                                                    </a>
                                                @endif


                                                <button type="button" class="btn-action edit" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditBerkas"
                                                    data-uid="{{ $berkas->pengumpulan_berkas_uid }}"
                                                    data-nama="{{ $pegawai->user_nama }}">

                                                    <i class="bi bi-pencil-fill"></i>

                                                </button>
                                            @else
                                                <button type="button" class="btn-action upload" data-bs-toggle="modal"
                                                    data-bs-target="#modalUploadBerkas"
                                                    data-user-uid="{{ $pegawai->user_uid }}"
                                                    data-nama="{{ $pegawai->user_nama }}">

                                                    <i class="bi bi-cloud-arrow-up-fill"></i>

                                                </button>
                                            @endif

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
                @if ($rekap->hasPages())
                    <div class="pagination-wrapper">

                        <div class="pagination-info">

                            Menampilkan

                            <strong>
                                {{ $rekap->firstItem() }}
                            </strong>

                            –

                            <strong>
                                {{ $rekap->lastItem() }}
                            </strong>

                            dari

                            <strong>
                                {{ $rekap->total() }}
                            </strong>

                            pegawai

                        </div>


                        <div class="pagination-container">

                            {{ $rekap->onEachSide(1)->links('pagination::bootstrap-5') }}

                        </div>

                    </div>
                @endif

            @endif

        </div>

    </div>


    {{-- =========================================================
     MODAL UPLOAD
    ========================================================== --}}
    <div class="modal fade" id="modalUploadBerkas" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content modal-modern">

                <div class="modal-header">

                    <div>

                        <h5 class="modal-title">
                            Upload Berkas
                        </h5>

                        <p class="modal-subtitle">
                            Upload berkas untuk pegawai.
                        </p>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <form method="POST" id="formUploadBerkas" enctype="multipart/form-data">

                    @csrf

                    <div class="modal-body">

                        <div class="pegawai-selected">

                            <div class="pegawai-selected-icon">
                                <i class="bi bi-person-fill"></i>
                            </div>

                            <div>

                                <div class="selected-label">
                                    Pegawai
                                </div>

                                <div class="selected-name" id="uploadNamaPegawai">
                                    -
                                </div>

                            </div>

                        </div>


                        <div class="upload-box">

                            <i class="bi bi-cloud-arrow-up"></i>

                            <div class="upload-title">
                                Pilih berkas
                            </div>

                            <div class="upload-description">
                                Maksimal ukuran file 50 MB.
                            </div>

                            <input type="file" name="file" class="form-control mt-3" required>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="btn-modal-submit">

                            <i class="bi bi-cloud-arrow-up-fill"></i>

                            Upload Berkas

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =========================================================
     MODAL EDIT
    ========================================================== --}}
    <div class="modal fade" id="modalEditBerkas" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content modal-modern">

                <div class="modal-header">

                    <div>

                        <h5 class="modal-title">
                            Ganti Berkas
                        </h5>

                        <p class="modal-subtitle">
                            Upload berkas pengganti.
                        </p>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <form method="POST" id="formEditBerkas" enctype="multipart/form-data">

                    @csrf

                    <div class="modal-body">

                        <div class="pegawai-selected">

                            <div class="pegawai-selected-icon">
                                <i class="bi bi-person-fill"></i>
                            </div>

                            <div>

                                <div class="selected-label">
                                    Pegawai
                                </div>

                                <div class="selected-name" id="editNamaPegawai">
                                    -
                                </div>

                            </div>

                        </div>


                        <div class="upload-box">

                            <i class="bi bi-file-earmark-arrow-up"></i>

                            <div class="upload-title">
                                Pilih berkas baru
                            </div>

                            <div class="upload-description">
                                Berkas lama akan diganti dengan berkas baru.
                            </div>

                            <input type="file" name="file" class="form-control mt-3" required>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="btn-modal-submit">

                            <i class="bi bi-arrow-repeat"></i>

                            Ganti Berkas

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =========================================================
     JAVASCRIPT
    ========================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | MODAL UPLOAD
            |--------------------------------------------------------------------------
            */

            const uploadModal =
                document.getElementById('modalUploadBerkas');

            if (uploadModal) {

                uploadModal.addEventListener(
                    'show.bs.modal',
                    function(event) {

                        const button = event.relatedTarget;

                        if (!button) {
                            return;
                        }

                        const userUid =
                            button.getAttribute('data-user-uid');

                        const nama =
                            button.getAttribute('data-nama');

                        const namaElement =
                            document.getElementById('uploadNamaPegawai');

                        const form =
                            document.getElementById('formUploadBerkas');

                        namaElement.textContent =
                            nama || '-';

                        form.action =
                            "{{ url('/admin/rekap-berkas') }}" +
                            "/{{ $permintaanUid }}/upload/" +
                            userUid;

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | MODAL EDIT
            |--------------------------------------------------------------------------
            */

            const editModal =
                document.getElementById('modalEditBerkas');

            if (editModal) {

                editModal.addEventListener(
                    'show.bs.modal',
                    function(event) {

                        const button = event.relatedTarget;

                        if (!button) {
                            return;
                        }

                        const uid =
                            button.getAttribute('data-uid');

                        const nama =
                            button.getAttribute('data-nama');

                        const namaElement =
                            document.getElementById('editNamaPegawai');

                        const form =
                            document.getElementById('formEditBerkas');

                        namaElement.textContent =
                            nama || '-';

                        form.action =
                            "{{ url('/admin/rekap-berkas/edit') }}" +
                            "/" +
                            uid;

                    }
                );

            }

        });
    </script>


    <style>
        /* =========================================================
                           PAGE
                        ========================================================== */

        .rekap-page {
            max-width: 1500px;
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
            margin-bottom: 22px;
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

        .page-header p strong {
            color: #374151;
        }


        /* =========================================================
                           BUTTON KEMBALI
                        ========================================================== */

        .btn-kembali {
            min-height: 40px;
            padding: 0 14px;
            border-radius: 9px;
            border: 1px solid #e2e6ec;
            background: #fff;
            color: #374151;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: .2s ease;
        }

        .btn-kembali:hover {
            color: #f28c28;
            border-color: #f28c28;
            background: rgba(242, 140, 40, .04);
        }


        /* =========================================================
                           HERO
                        ========================================================== */

        .request-hero {
            background: #fff;
            border: 1px solid #e9edf3;
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(24, 34, 56, .04);
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 20px;
        }

        .request-hero-content {
            min-width: 0;
        }

        .request-category {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .request-title {
            color: #102342;
            font-size: 23px;
            font-weight: 700;
            line-height: 1.3;
        }

        .request-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 18px;
            margin-top: 9px;
            color: #718096;
            font-size: 13px;
        }

        .request-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .request-meta i {
            color: #f28c28;
        }

        .request-description {
            margin-top: 13px;
            color: #718096;
            font-size: 13px;
            line-height: 1.5;
        }

        .request-progress {
            width: 190px;
            flex-shrink: 0;
            text-align: right;
        }

        .progress-label {
            color: #718096;
            font-size: 12px;
        }

        .progress-percent {
            color: #f28c28;
            font-size: 38px;
            line-height: 1;
            font-weight: 700;
            margin-top: 7px;
        }

        .hero-progress {
            height: 8px;
            background: #edf1f5;
            border-radius: 99px;
            overflow: hidden;
            margin-top: 12px;
        }

        .hero-progress-bar {
            height: 100%;
            background: #f28c28;
            border-radius: 99px;
        }


        /* =========================================================
                           SUMMARY
                        ========================================================== */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: #fff;
            border: 1px solid #e9edf3;
            border-radius: 18px;
            padding: 27px 30px;
            box-shadow: 0 4px 18px rgba(24, 34, 56, .04);
        }

        .summary-label {
            color: #718096;
            font-size: 14px;
        }

        .summary-value {
            color: #102342;
            font-size: 38px;
            line-height: 1;
            font-weight: 700;
            margin-top: 13px;
        }

        .summary-value.green {
            color: #16a34a;
        }

        .summary-value.gray {
            color: #6b7280;
        }


        /* =========================================================
                           REKAP JENIS KERJA
                        ========================================================== */

        .rekap-jenis-card {
            background: #fff;
            border: 1px solid #e9edf3;
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(24, 34, 56, .04);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .rekap-jenis-header {
            padding: 22px 30px;
            border-bottom: 1px solid #edf0f4;
        }

        .rekap-jenis-header h5 {
            margin: 0;
            color: #102342;
            font-size: 17px;
            font-weight: 700;
        }

        .rekap-jenis-header p {
            margin: 5px 0 0;
            color: #718096;
            font-size: 13px;
        }

        .rekap-jenis-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            padding: 22px 30px 28px;
        }

        .rekap-jenis-item {
            border: 1px solid #e9edf3;
            border-radius: 14px;
            padding: 18px;
            background: #fff;
            transition: .2s ease;
        }

        .rekap-jenis-item:hover {
            border-color: #f3c28f;
            box-shadow: 0 4px 14px rgba(24, 34, 56, .05);
        }

        .rekap-jenis-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .rekap-jenis-name {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
        }

        .rekap-jenis-icon {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            background: rgba(242, 140, 40, .10);
            color: #f28c28;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rekap-jenis-title {
            color: #182238;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.4;
        }

        .rekap-jenis-total {
            color: #8a93a2;
            font-size: 11px;
            margin-top: 3px;
        }

        .rekap-jenis-percent {
            color: #f28c28;
            font-size: 20px;
            font-weight: 700;
            white-space: nowrap;
        }

        .rekap-progress {
            width: 100%;
            height: 8px;
            background: #edf1f5;
            border-radius: 99px;
            overflow: hidden;
            margin-top: 17px;
        }

        .rekap-progress-bar {
            height: 100%;
            background: #f28c28;
            border-radius: 99px;
        }

        .rekap-jenis-detail {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 13px;
        }

        .rekap-detail-sudah,
        .rekap-detail-belum {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #7b8494;
            font-size: 11px;
        }

        .rekap-detail-sudah strong {
            color: #16a34a;
            font-size: 12px;
        }

        .rekap-detail-belum strong {
            color: #6b7280;
            font-size: 12px;
        }

        .rekap-detail-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #16a34a;
        }

        .rekap-detail-belum .rekap-detail-dot {
            background: #9ca3af;
        }

        .rekap-empty {
            grid-column: 1 / -1;
            padding: 35px 20px;
            text-align: center;
            color: #8a93a2;
            font-size: 13px;
        }

        .rekap-empty i {
            display: block;
            font-size: 28px;
            margin-bottom: 8px;
        }


        /* =========================================================
                           FILTER
                        ========================================================== */

        .filter-card {
            background: #fff;
            border: 1px solid #e9edf3;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(24, 34, 56, .04);
            padding: 18px 20px;
            margin-bottom: 20px;
        }

        .filter-form {
            display: flex;
            align-items: flex-end;
            gap: 12px;
        }

        .filter-group {
            width: 220px;
            flex-shrink: 0;
        }

        .filter-search {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label,
        .filter-search label {
            display: block;
            color: #6b7280;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .filter-form .form-select,
        .filter-form .form-control {
            min-height: 40px;
            border-color: #e2e6ec;
            border-radius: 9px;
            color: #374151;
            font-size: 12px;
            box-shadow: none;
        }

        .filter-form .form-select:focus,
        .filter-form .form-control:focus {
            border-color: #f28c28;
            box-shadow: 0 0 0 3px rgba(242, 140, 40, .08);
        }

        .search-input {
            position: relative;
        }

        .search-input i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 13px;
            z-index: 2;
        }

        .search-input .form-control {
            padding-left: 35px;
        }

        .filter-actions {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .btn-filter,
        .btn-reset {
            min-height: 40px;
            padding: 0 13px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-filter {
            background: #f28c28;
            color: #fff;
            border: 1px solid #f28c28;
        }

        .btn-filter:hover {
            background: #dc7818;
            border-color: #dc7818;
            color: #fff;
        }

        .btn-reset {
            background: #fff;
            color: #6b7280;
            border: 1px solid #e2e6ec;
        }

        .btn-reset:hover {
            color: #f28c28;
            border-color: #f28c28;
        }


        /* =========================================================
                           TABLE CARD
                        ========================================================== */

        .pegawai-card {
            background: #fff;
            border: 1px solid #e9edf3;
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(24, 34, 56, .04);
            overflow: hidden;
        }

        .pegawai-card-header {
            padding: 21px 24px;
            border-bottom: 1px solid #edf0f4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .pegawai-card-header h5 {
            margin: 0;
            color: #182238;
            font-size: 16px;
            font-weight: 700;
        }

        .pegawai-card-header p {
            margin: 4px 0 0;
            color: #7b8494;
            font-size: 12px;
        }

        .pegawai-count {
            color: #6b7280;
            background: #f5f7fa;
            border: 1px solid #e8ecf1;
            border-radius: 8px;
            padding: 7px 10px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }


        /* =========================================================
                           TABLE
                        ========================================================== */

        .pegawai-table {
            min-width: 1000px;
        }

        .pegawai-table thead th {
            background: #f8fafc;
            color: #6b7280;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 1px solid #e9edf3;
            padding: 13px 16px;
            white-space: nowrap;
        }

        .pegawai-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #eef1f5;
            vertical-align: middle;
        }

        .pegawai-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .pegawai-table tbody tr:hover {
            background: #fafbfc;
        }


        /* =========================================================
                           NUMBER
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
            font-size: 11px;
            font-weight: 600;
        }


        /* =========================================================
                           PEGAWAI
                        ========================================================== */

        .pegawai-info {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 220px;
        }

        .pegawai-avatar {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 10px;
            overflow: hidden;
            background: #f3f5f8;
            color: #5b6472;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
            position: relative;
        }

        .pegawai-avatar-img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center;
        }

        .pegawai-avatar-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5b6472;
            font-size: 13px;
            font-weight: 700;
        }

        .pegawai-name {
            color: #182238;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.4;
        }

        .pegawai-identitas {
            color: #8a93a2;
            font-size: 10px;
            margin-top: 2px;
        }


        /* =========================================================
                           JENIS KERJA
                        ========================================================== */

        .jenis-badge {
            display: inline-block;
            color: #5b6472;
            background: #f5f7fa;
            border: 1px solid #e7ebf0;
            border-radius: 7px;
            padding: 5px 8px;
            font-size: 10px;
            line-height: 1.3;
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
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-badge.sudah {
            color: #15803d;
            background: #f0fdf4;
            border: 1px solid #dcfce7;
        }

        .status-badge.belum {
            color: #6b7280;
            background: #f5f7fa;
            border: 1px solid #e5e7eb;
        }


        /* =========================================================
                           BERKAS
                        ========================================================== */

        .berkas-info {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 180px;
        }

        .berkas-icon {
            width: 31px;
            height: 31px;
            border-radius: 8px;
            background: #f0fdf4;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .berkas-name {
            color: #374151;
            font-size: 11px;
            font-weight: 600;
            max-width: 190px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .berkas-date {
            color: #9ca3af;
            font-size: 9px;
            margin-top: 2px;
        }


        /* =========================================================
                           ACTION
                        ========================================================== */

        .action-group {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-action {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 12px;
            transition: .2s ease;
        }

        .btn-action.view {
            background: #f5f7fa;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .btn-action.view:hover {
            color: #f28c28;
            border-color: #f28c28;
            background: rgba(242, 140, 40, .05);
        }

        .btn-action.edit {
            background: #fff7ed;
            color: #ea7d16;
            border: 1px solid #fed7aa;
        }

        .btn-action.edit:hover {
            background: #f28c28;
            color: #fff;
            border-color: #f28c28;
        }

        .btn-action.upload {
            background: #f28c28;
            color: #fff;
            border: 1px solid #f28c28;
        }

        .btn-action.upload:hover {
            background: #dc7818;
            border-color: #dc7818;
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
            font-size: 11px;
        }

        .pagination-info strong {
            color: #374151;
        }

        .pagination-container .pagination {
            margin: 0;
            gap: 4px;
        }

        .pagination-container .page-link {
            color: #374151;
            border: 1px solid #e5e7eb;
            background: #fff;
            font-size: 11px;
            min-width: 33px;
            height: 33px;
            border-radius: 8px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
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
        }


        /* =========================================================
                           EMPTY
                        ========================================================== */

        .empty-state {
            padding: 65px 20px;
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
            font-size: 27px;
            margin-bottom: 15px;
        }

        .empty-state h5 {
            color: #374151;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .empty-state p {
            color: #8a93a2;
            font-size: 12px;
            margin-bottom: 18px;
        }


        /* =========================================================
                           MODAL
                        ========================================================== */

        .modal-modern {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
        }

        .modal-modern .modal-header {
            padding: 20px 22px;
            border-bottom: 1px solid #edf0f4;
        }

        .modal-modern .modal-title {
            color: #182238;
            font-size: 16px;
            font-weight: 700;
        }

        .modal-subtitle {
            color: #8a93a2;
            font-size: 11px;
            margin: 4px 0 0;
        }

        .modal-modern .modal-body {
            padding: 22px;
        }

        .pegawai-selected {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #edf0f4;
            border-radius: 11px;
            padding: 12px;
            margin-bottom: 16px;
        }

        .pegawai-selected-icon {
            width: 35px;
            height: 35px;
            border-radius: 9px;
            background: rgba(242, 140, 40, .1);
            color: #f28c28;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .selected-label {
            color: #9ca3af;
            font-size: 9px;
        }

        .selected-name {
            color: #374151;
            font-size: 12px;
            font-weight: 700;
            margin-top: 2px;
        }

        .upload-box {
            border: 1px dashed #d8dee7;
            border-radius: 12px;
            padding: 25px 18px;
            text-align: center;
        }

        .upload-box>i {
            color: #f28c28;
            font-size: 30px;
        }

        .upload-title {
            color: #374151;
            font-size: 13px;
            font-weight: 700;
            margin-top: 8px;
        }

        .upload-description {
            color: #8a93a2;
            font-size: 10px;
            margin-top: 3px;
        }

        .upload-box .form-control {
            font-size: 11px;
            border-color: #e1e5eb;
            box-shadow: none;
        }

        .modal-modern .modal-footer {
            padding: 14px 22px;
            border-top: 1px solid #edf0f4;
        }

        .btn-modal-cancel,
        .btn-modal-submit {
            min-height: 38px;
            padding: 0 13px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
        }

        .btn-modal-cancel {
            color: #6b7280;
            background: #fff;
            border: 1px solid #e2e6ec;
        }

        .btn-modal-submit {
            color: #fff;
            background: #f28c28;
            border: 1px solid #f28c28;
        }

        .btn-modal-submit:hover {
            background: #dc7818;
            border-color: #dc7818;
        }


        /* =========================================================
                           RESPONSIVE
                        ========================================================== */

        @media (max-width: 1100px) {

            .filter-form {
                flex-wrap: wrap;
            }

            .filter-group {
                width: calc(50% - 6px);
            }

            .filter-search {
                width: 100%;
                flex: auto;
            }

        }


        @media (max-width: 991.98px) {

            .summary-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .request-hero {
                align-items: flex-start;
                flex-direction: column;
            }

            .request-progress {
                width: 100%;
                text-align: left;
            }

            .hero-progress {
                max-width: 400px;
            }

            .rekap-jenis-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 767.98px) {

            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .btn-kembali {
                width: 100%;
            }

            .request-hero {
                padding: 22px;
            }

            .request-title {
                font-size: 19px;
            }

            .progress-percent {
                font-size: 32px;
            }

            .summary-card {
                padding: 21px;
            }

            .summary-value {
                font-size: 32px;
            }

            .rekap-jenis-header {
                padding: 19px;
            }

            .rekap-jenis-grid {
                padding: 16px;
            }

            .filter-card {
                padding: 15px;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group,
            .filter-search {
                width: 100%;
                min-width: 0;
            }

            .filter-actions {
                width: 100%;
            }

            .btn-filter,
            .btn-reset {
                flex: 1;
            }

            .pegawai-card-header {
                padding: 18px;
                align-items: flex-start;
                flex-direction: column;
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

        }
    </style>

@endsection
