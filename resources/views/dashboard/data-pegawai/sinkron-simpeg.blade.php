@extends('dashboard.layouts.app')

@section('title', 'Sinkron SIMPEG')

@section('header-title', 'Sinkron SIMPEG')

@section('breadcrumb', 'Sinkron SIMPEG')

@section('page-style')

    <style>
        .pegawai-import-page {
            width: 100%;
        }


        /* =========================================================
           HEADER
        ========================================================= */

        .pegawai-import-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .pegawai-import-header-left {
            min-width: 0;
        }

        .pegawai-import-header-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
        }

        .pegawai-import-header-description {
            margin: 6px 0 0;
            color: #8993a3;
            font-size: 13px;
        }

        .pegawai-import-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }


        /* =========================================================
           BACK
        ========================================================= */

        .pegawai-import-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            border: 1px solid #dfe4eb;
            border-radius: 10px;

            padding: 11px 16px;

            background: #fff;
            color: #667085;

            font-size: 13px;
            font-weight: 700;

            text-decoration: none;

            transition: .15s ease;
        }

        .pegawai-import-back:hover {
            background: #f8f9fb;
            color: #273449;
        }


        /* =========================================================
           PANEL
        ========================================================= */

        .pegawai-import-panel {
            background: #fff;

            border: 1px solid #e7eaf0;
            border-radius: 14px;

            overflow: hidden;

            box-shadow:
                0 5px 18px rgba(20, 35, 60, .035);
        }

        .pegawai-import-panel-header {
            padding: 18px 20px;

            border-bottom: 1px solid #edf0f4;
        }

        .pegawai-import-panel-title {
            color: #273449;

            font-size: 15px;
            font-weight: 800;
        }

        .pegawai-import-panel-description {
            margin-top: 4px;

            color: #929ba9;

            font-size: 11px;
        }

        .pegawai-import-panel-body {
            padding: 20px;
        }


        /* =========================================================
           ALERT
        ========================================================= */

        .pegawai-import-alert {
            border-radius: 10px;

            font-size: 12px;
        }


        /* =========================================================
           INFO
        ========================================================= */

        .pegawai-import-info {
            display: flex;
            align-items: flex-start;

            gap: 13px;

            padding: 15px;

            margin-bottom: 18px;

            border: 1px solid #e7eaf0;
            border-radius: 12px;

            background: #fafbfc;
        }

        .pegawai-import-info-icon {
            width: 42px;
            height: 42px;

            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 11px;

            background: #e8f1ff;
            color: #3677d5;

            font-size: 18px;
        }

        .pegawai-import-info-title {
            margin-bottom: 4px;

            color: #344054;

            font-size: 12px;
            font-weight: 800;
        }

        .pegawai-import-info-text {
            color: #8993a3;

            font-size: 11px;

            line-height: 1.7;
        }

        .pegawai-import-info-text strong {
            color: #475467;
        }


        /* =========================================================
           UPLOAD
        ========================================================= */

        .pegawai-import-upload {
            border: 1.5px dashed #d5dae2;

            border-radius: 13px;

            padding: 35px 20px;

            text-align: center;

            background: #fcfcfd;

            transition: .15s ease;
        }

        .pegawai-import-upload:hover {
            border-color: #3677d5;

            background: #f8fbff;
        }

        .pegawai-import-upload-icon {
            width: 58px;
            height: 58px;

            margin: 0 auto 14px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 15px;

            background: #e8f1ff;
            color: #3677d5;

            font-size: 24px;
        }

        .pegawai-import-upload-title {
            color: #344054;

            font-size: 14px;
            font-weight: 800;
        }

        .pegawai-import-upload-description {
            margin-top: 5px;

            color: #98a2b3;

            font-size: 11px;
        }

        .pegawai-import-file-wrapper {
            margin: 18px auto 0;

            max-width: 500px;
        }

        .pegawai-import-file {
            width: 100%;

            border: 1px solid #dfe4eb;

            border-radius: 9px;

            padding: 10px;

            background: #fff;

            color: #475467;

            font-size: 12px;

            cursor: pointer;
        }

        .pegawai-import-file:focus {
            outline: none;

            border-color: #3677d5;

            box-shadow:
                0 0 0 3px rgba(54, 119, 213, .08);
        }

        .pegawai-import-format {
            margin-top: 13px;

            color: #98a2b3;

            font-size: 10px;
        }

        .pegawai-import-format strong {
            color: #667085;
        }


        /* =========================================================
           MAPPING
        ========================================================= */

        .pegawai-import-mapping {
            margin-top: 18px;

            border: 1px solid #e7eaf0;

            border-radius: 12px;

            overflow: hidden;
        }

        .pegawai-import-mapping-header {
            padding: 13px 15px;

            background: #fafbfc;

            border-bottom: 1px solid #edf0f4;

            color: #344054;

            font-size: 12px;
            font-weight: 800;
        }

        .pegawai-import-mapping-body {
            padding: 14px 15px;
        }

        .pegawai-import-mapping-grid {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 8px 25px;
        }

        .pegawai-import-mapping-item {
            display: flex;
            align-items: center;

            gap: 8px;

            color: #667085;

            font-size: 10px;

            line-height: 1.5;
        }

        .pegawai-import-mapping-item i {
            color: #3677d5;

            font-size: 10px;
        }

        .pegawai-import-mapping-item strong {
            color: #475467;
        }


        /* =========================================================
           WARNING
        ========================================================= */

        .pegawai-import-warning {
            display: flex;
            align-items: flex-start;

            gap: 10px;

            margin-top: 18px;

            padding: 14px 15px;

            border: 1px solid #f1dfb1;

            border-radius: 10px;

            background: #fff8e8;

            color: #8a6500;

            font-size: 11px;

            line-height: 1.6;
        }

        .pegawai-import-warning-icon {
            flex-shrink: 0;

            margin-top: 1px;

            font-size: 15px;
        }

        .pegawai-import-warning-title {
            margin-bottom: 3px;

            font-weight: 800;
        }


        /* =========================================================
           ACTION
        ========================================================= */

        .pegawai-import-actions {
            display: flex;

            align-items: center;

            justify-content: flex-end;

            gap: 8px;

            margin-top: 20px;

            padding-top: 18px;

            border-top: 1px solid #edf0f4;
        }

        .pegawai-import-cancel,
        .pegawai-import-submit {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            border-radius: 9px;

            padding: 10px 16px;

            font-size: 12px;

            font-weight: 700;

            text-decoration: none;

            transition: .15s ease;
        }

        .pegawai-import-cancel {

            border: 1px solid #dfe4eb;

            background: #fff;

            color: #667085;
        }

        .pegawai-import-cancel:hover {

            background: #f8f9fb;

            color: #273449;
        }

        .pegawai-import-submit {

            border: 0;

            background:
                linear-gradient(135deg,
                    #3677d5,
                    #2457a6);

            color: #fff;

            cursor: pointer;

            box-shadow:
                0 7px 18px rgba(36, 87, 166, .18);
        }

        .pegawai-import-submit:hover {

            color: #fff;

            transform: translateY(-1px);
        }

        .pegawai-import-submit.loading {

            pointer-events: none;

            opacity: .7;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 768px) {

            .pegawai-import-header {

                align-items: flex-start;

                flex-direction: column;
            }

            .pegawai-import-header-actions {

                width: 100%;
            }

            .pegawai-import-back {

                width: 100%;
            }

            .pegawai-import-panel-body {

                padding: 15px;
            }

            .pegawai-import-mapping-grid {

                grid-template-columns: 1fr;
            }

            .pegawai-import-actions {

                flex-direction: column-reverse;
            }

            .pegawai-import-cancel,
            .pegawai-import-submit {

                width: 100%;
            }

        }
    </style>

@endsection


@section('content')

    <div class="pegawai-import-page">


        {{-- =========================================================
         ERROR
    ========================================================= --}}

        @if (session('error'))
            <div class="alert alert-danger pegawai-import-alert mb-3">

                <i class="bi bi-exclamation-circle me-1"></i>

                {{ session('error') }}

            </div>
        @endif


        {{-- VALIDATION ERROR --}}

        @if ($errors->any())

            <div class="alert alert-danger pegawai-import-alert mb-3">

                <i class="bi bi-exclamation-circle me-1"></i>

                @foreach ($errors->all() as $error)
                    <div>
                        {{ $error }}
                    </div>
                @endforeach

            </div>

        @endif


        {{-- =========================================================
         HEADER
    ========================================================= --}}

        <div class="pegawai-import-header">

            <div class="pegawai-import-header-left">

                <h1 class="pegawai-import-header-title">

                    Sinkron SIMPEG

                </h1>

                <p class="pegawai-import-header-description">

                    Sinkronisasi data pegawai dari Excel SIMPEG ke SAMPERIN.

                </p>

            </div>


            <div class="pegawai-import-header-actions">

                <a href="{{ route('kepeg.pegawai.index') }}" class="pegawai-import-back">

                    <i class="bi bi-arrow-left"></i>

                    Kembali

                </a>

            </div>

        </div>


        {{-- =========================================================
         PANEL
    ========================================================= --}}

        <div class="pegawai-import-panel">


            {{-- PANEL HEADER --}}

            <div class="pegawai-import-panel-header">

                <div class="pegawai-import-panel-title">

                    Sinkronisasi Data SIMPEG

                </div>

                <div class="pegawai-import-panel-description">

                    Upload file Excel terbaru dari SIMPEG untuk memperbarui
                    data pegawai SAMPERIN.

                </div>

            </div>


            {{-- PANEL BODY --}}

            <div class="pegawai-import-panel-body">


                {{-- =================================================
                 INFO
            ================================================= --}}

                <div class="pegawai-import-info">

                    <div class="pegawai-import-info-icon">

                        <i class="bi bi-arrow-repeat"></i>

                    </div>


                    <div>

                        <div class="pegawai-import-info-title">

                            Cara Kerja Sinkronisasi

                        </div>


                        <div class="pegawai-import-info-text">

                            Sistem akan membaca data pegawai dari file
                            <strong>Excel SIMPEG</strong> kemudian
                            mencocokkannya dengan data
                            <strong>samperin_user</strong>.

                            <br>

                            Jika <strong>NIP tersedia</strong>, NIP digunakan
                            sebagai identitas utama.

                            Jika NIP kosong, sistem menggunakan
                            <strong>NIK</strong>.

                            <br>

                            Data yang sudah ditemukan akan
                            <strong>diperbarui</strong>, sedangkan data pegawai
                            yang belum ada akan <strong>ditambahkan</strong>.

                        </div>

                    </div>

                </div>


                {{-- =================================================
                 FORM
            ================================================= --}}

                <form method="POST" action="{{ route('kepeg.pegawai.sinkron-simpeg.process') }}"
                    enctype="multipart/form-data" id="pegawaiImportForm">

                    @csrf


                    {{-- =================================================
                     UPLOAD
                ================================================= --}}

                    <div class="pegawai-import-upload">

                        <div class="pegawai-import-upload-icon">

                            <i class="bi bi-file-earmark-spreadsheet"></i>

                        </div>


                        <div class="pegawai-import-upload-title">

                            Pilih File Excel SIMPEG

                        </div>


                        <div class="pegawai-import-upload-description">

                            Upload file Excel yang berisi seluruh data pegawai
                            terbaru dari SIMPEG.

                        </div>


                        <div class="pegawai-import-file-wrapper">

                            <input type="file" name="file_simpeg" id="pegawaiImportFile" class="pegawai-import-file"
                                accept=".xlsx,.xls" required>

                        </div>


                        <div class="pegawai-import-format">

                            Format:

                            <strong>.XLSX</strong>
                            /
                            <strong>.XLS</strong>

                            · Maksimal:

                            <strong>50 MB</strong>

                        </div>

                    </div>


                    {{-- =================================================
                     MAPPING
                ================================================= --}}

                    <div class="pegawai-import-mapping">

                        <div class="pegawai-import-mapping-header">

                            <i class="bi bi-arrow-left-right me-1"></i>

                            Data yang akan disinkronkan

                        </div>


                        <div class="pegawai-import-mapping-body">

                            <div class="pegawai-import-mapping-grid">


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>NIP</strong>
                                        → NIP Pegawai

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>NIK</strong>
                                        → NIK Pegawai

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>Nama</strong>
                                        → Nama Pegawai

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>GELAR DEPAN</strong>
                                        → Gelar Depan

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>GELAR BELAKANG</strong>
                                        → Gelar Belakang

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>ALAMAT</strong>
                                        → Alamat

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>JENIS KELAMIN</strong>
                                        → Jenis Kelamin

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>TELEPON</strong>
                                        → Nomor Telepon

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>TEMPAT LAHIR</strong>
                                        → Tempat Lahir

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>TANGGAL LAHIR</strong>
                                        → Tanggal Lahir

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>JABATAN</strong>
                                        → Jabatan

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>GOLONGAN</strong>
                                        → Golongan

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>ESELON</strong>
                                        → Eselon

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>PENDIDIKAN TERAKHIR</strong>
                                        → Pendidikan

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>STATUS PEGAWAI</strong>
                                        → Jenis Kerja

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>KELAS JABATAN</strong>
                                        → Kelas Jabatan

                                    </span>

                                </div>


                                <div class="pegawai-import-mapping-item">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <span>

                                        <strong>UNIT PERANGKAT DAERAH</strong>
                                        → Lokasi Kerja

                                    </span>

                                </div>


                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                     WARNING
                ================================================= --}}

                    <div class="pegawai-import-warning">

                        <div class="pegawai-import-warning-icon">

                            <i class="bi bi-exclamation-triangle"></i>

                        </div>


                        <div>

                            <div class="pegawai-import-warning-title">

                                Perhatian

                            </div>


                            <div>

                                Pastikan file Excel yang digunakan merupakan
                                <strong>data lengkap seluruh pegawai SIMPEG</strong>.

                                <br>

                                Pegawai yang tidak ditemukan pada file SIMPEG
                                akan otomatis menjadi
                                <strong>tidak aktif (`user_status = 0`)</strong>.

                                <br>

                                Data lama akan diperbarui berdasarkan
                                <strong>NIP</strong>, atau
                                <strong>NIK</strong> apabila NIP kosong.

                                <br>

                                Jika NIP ditemukan tetapi NIK berbeda,
                                data tersebut akan
                                <strong>dilewati untuk mencegah salah data pegawai</strong>.

                                <br>

                                Data seperti
                                <strong>user_uid</strong>,
                                password,
                                foto, dan data manual lainnya
                                tidak ditimpa oleh sinkronisasi SIMPEG.

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                     ACTION
                ================================================= --}}

                    <div class="pegawai-import-actions">


                        <a href="{{ route('kepeg.pegawai.index') }}" class="pegawai-import-cancel">

                            <i class="bi bi-x-lg"></i>

                            Batal

                        </a>


                        <button type="submit" class="pegawai-import-submit" id="pegawaiImportSubmit">

                            <i class="bi bi-arrow-repeat"></i>

                            Sinkronkan Data

                        </button>


                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =============================================================
     SCRIPT
============================================================= --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form =
                document.getElementById('pegawaiImportForm');

            const fileInput =
                document.getElementById('pegawaiImportFile');

            const submitButton =
                document.getElementById('pegawaiImportSubmit');


            if (!form || !fileInput || !submitButton) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDASI FILE SAAT DIPILIH
            |--------------------------------------------------------------------------
            */

            fileInput.addEventListener('change', function() {

                if (!fileInput.files.length) {
                    return;
                }


                const file =
                    fileInput.files[0];


                const extension =
                    file.name
                    .split('.')
                    .pop()
                    .toLowerCase();


                if (
                    extension !== 'xlsx' &&
                    extension !== 'xls'
                ) {

                    alert(
                        'File harus berformat XLS atau XLSX.'
                    );

                    fileInput.value = '';

                    return;
                }


                if (
                    file.size >
                    50 * 1024 * 1024
                ) {

                    alert(
                        'Ukuran file maksimal 50 MB.'
                    );

                    fileInput.value = '';

                    return;
                }

            });


            /*
            |--------------------------------------------------------------------------
            | SUBMIT
            |--------------------------------------------------------------------------
            */

            form.addEventListener('submit', function(event) {

                if (!fileInput.files.length) {

                    event.preventDefault();

                    alert(
                        'Silakan pilih file Excel SIMPEG terlebih dahulu.'
                    );

                    return;
                }


                const file =
                    fileInput.files[0];


                const extension =
                    file.name
                    .split('.')
                    .pop()
                    .toLowerCase();


                if (
                    extension !== 'xlsx' &&
                    extension !== 'xls'
                ) {

                    event.preventDefault();

                    alert(
                        'File harus berformat XLS atau XLSX.'
                    );

                    fileInput.value = '';

                    return;
                }


                if (
                    file.size >
                    50 * 1024 * 1024
                ) {

                    event.preventDefault();

                    alert(
                        'Ukuran file maksimal 50 MB.'
                    );

                    fileInput.value = '';

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | LOADING
                |--------------------------------------------------------------------------
                */

                submitButton.classList.add('loading');

                submitButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span>' +
                    ' Memproses Sinkronisasi...';

            });

        });
    </script>

@endsection
