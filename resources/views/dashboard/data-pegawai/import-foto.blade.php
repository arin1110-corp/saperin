@extends('dashboard.layouts.app')

@section('title', 'Import Foto Pegawai')

@section('header-title', 'Import Foto Pegawai')

@section('breadcrumb', 'Data Pegawai / Import Foto')

@section('content')

    <style>
        .import-card {
            background: #fff;
            border: 1px solid #e8ebef;
            border-radius: 16px;
            padding: 26px;
            box-shadow: 0 4px 16px rgba(24, 34, 56, .04);
        }

        .import-icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            background: rgba(242, 140, 40, .12);
            color: #f28c28;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            margin-bottom: 18px;
        }

        .import-title {
            font-size: 19px;
            font-weight: 800;
            color: #182238;
            margin-bottom: 6px;
        }

        .import-desc {
            font-size: 13px;
            color: #7d8694;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 700;
            color: #182238;
            margin-bottom: 7px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #dfe3e8;
            min-height: 44px;
            font-size: 13px;
        }

        .form-control:focus {
            border-color: #f28c28;
            box-shadow: 0 0 0 3px rgba(242, 140, 40, .10);
        }

        .btn-samperin {
            background: #f28c28;
            border-color: #f28c28;
            color: #fff;
            border-radius: 10px;
            min-height: 44px;
            padding: 0 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .btn-samperin:hover {
            background: #df7917;
            border-color: #df7917;
            color: #fff;
        }

        .info-box {
            background: #f8f9fb;
            border: 1px solid #e9edf1;
            border-radius: 12px;
            padding: 16px;
            margin-top: 20px;
        }

        .info-box-title {
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .info-box ul {
            margin: 0;
            padding-left: 18px;
            font-size: 12px;
            color: #697281;
            line-height: 1.8;
        }

        .alert {
            border-radius: 12px;
            font-size: 13px;
        }

        .error-list {
            max-height: 300px;
            overflow-y: auto;
            font-size: 12px;
            margin: 0;
            padding-left: 20px;
        }

        .progress-box {
            display: none;
            margin-top: 20px;
        }

        .progress {
            height: 8px;
            border-radius: 20px;
            background: #edf0f3;
        }

        .progress-bar {
            background: #f28c28;
        }
    </style>

    @if (session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if (session('import_errors') && count(session('import_errors')) > 0)
        <div class="alert alert-warning">
            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Beberapa data tidak berhasil diproses
            </div>

            <ol class="error-list">
                @foreach (session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ol>
        </div>
    @endif

    <div class="import-card">

        <div class="import-icon">
            <i class="bi bi-images"></i>
        </div>

        <div class="import-title">
            Import Foto Pegawai
        </div>

        <div class="import-desc">
            Duplikasi foto pegawai dari SADARIN ke ArinDrive.
            Data pegawai SAMPERIN tidak diubah.
            File asli di SADARIN juga tidak dihapus.
        </div>

        <form action="{{ route('kepeg.pegawai.import-foto.process') }}" method="POST" enctype="multipart/form-data"
            id="importFotoForm">
            @csrf

            <div class="mb-3">
                <label class="form-label">
                    File SQL SADARIN
                </label>

                <input type="file" name="file_sql" class="form-control" accept=".sql,.txt" required>

                <div class="form-text">
                    Pilih file dump <strong>sadarin_user.sql</strong>.
                </div>
            </div>

            <div class="info-box">

                <div class="info-box-title">
                    <i class="bi bi-info-circle me-1"></i>
                    Proses import
                </div>

                <ul>
                    <li>
                        Pegawai dicocokkan berdasarkan
                        <strong>user_id</strong>.
                    </li>

                    <li>
                        Foto diambil dari
                        <strong>sadarin.saplarin.site</strong>.
                    </li>

                    <li>
                        Foto diduplikasi ke ArinDrive.
                    </li>

                    <li>
                        File asli SADARIN tetap dipertahankan.
                    </li>

                    <li>
                        Folder Drive mengikuti
                        <strong>FOTO_PEGAWAI</strong>
                        berdasarkan jenis kerja pegawai.
                    </li>

                    <li>
                        Hasil file ArinDrive disimpan ke
                        <strong>samperin_user_foto</strong>.
                    </li>

                    <li>
                        Foto yang sudah berhasil dimigrasikan akan
                        otomatis di-skip.
                    </li>
                </ul>

            </div>

            <div class="progress-box" id="progressBox">

                <div class="d-flex justify-content-between mb-2">
                    <span style="font-size:12px;font-weight:700;">
                        Sedang memproses...
                    </span>

                    <span style="font-size:12px;color:#7d8694;">
                        Jangan tutup halaman
                    </span>
                </div>

                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%;"></div>
                </div>

            </div>

            <div class="d-flex justify-content-end mt-4">

                <button type="submit" class="btn btn-samperin" id="btnImport">
                    <i class="bi bi-cloud-arrow-up me-2"></i>
                    Mulai Import Foto
                </button>

            </div>

        </form>

    </div>

@endsection

@section('page-script')

    <script>
        document.getElementById('importFotoForm').addEventListener('submit', function() {

            const button = document.getElementById('btnImport');
            const progressBox = document.getElementById('progressBox');

            button.disabled = true;

            button.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>' +
                'Sedang Memproses...';

            progressBox.style.display = 'block';
        });
    </script>

@endsection
