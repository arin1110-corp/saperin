@extends('dashboard.layouts.app')

@section('title', 'Import Jabatan')

@section('header-title', 'Import Jabatan')

@section('breadcrumb', 'Manajemen Jabatan / Import')

@section('page-style')

    <style>
        .jabatan-import-page {
            max-width: 900px;
        }

        .jabatan-import-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
        }

        .jabatan-import-description {
            margin: 6px 0 20px;
            color: #8993a3;
            font-size: 13px;
        }

        .jabatan-import-card {
            background: #fff;
            border: 1px solid #e6eaf0;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 25px rgba(20, 35, 60, .04);
        }

        .jabatan-import-label {
            display: block;
            margin-bottom: 8px;
            color: #475467;
            font-size: 12px;
            font-weight: 700;
        }

        .jabatan-import-file {
            width: 100%;
            padding: 12px;
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            background: #fff;
            color: #667085;
            font-size: 12px;
        }

        .jabatan-import-info {
            margin-top: 18px;
            padding: 15px;
            border-radius: 10px;
            background: #f8f9fb;
            color: #667085;
            font-size: 12px;
            line-height: 1.7;
        }

        .jabatan-import-info strong {
            color: #273449;
        }

        .jabatan-import-actions {
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .jabatan-import-submit {
            border: 0;
            border-radius: 9px;
            padding: 11px 17px;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        .jabatan-import-back {
            border: 1px solid #dfe4eb;
            border-radius: 9px;
            padding: 10px 16px;
            background: #fff;
            color: #667085;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .jabatan-import-alert {
            border: 0;
            border-radius: 10px;
            font-size: 12px;
        }

        @media(max-width: 600px) {
            .jabatan-import-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .jabatan-import-submit,
            .jabatan-import-back {
                width: 100%;
                text-align: center;
            }
        }
    </style>

@endsection


@section('content')

    <div class="jabatan-import-page">

        @if ($errors->any())

            <div class="alert alert-danger jabatan-import-alert mb-3">

                <i class="bi bi-exclamation-circle me-1"></i>

                <strong>Import gagal</strong>

                <div class="mt-1">

                    @foreach ($errors->all() as $error)
                        <div>
                            - {{ $error }}
                        </div>
                    @endforeach

                </div>

            </div>

        @endif


        <h1 class="jabatan-import-title">
            Import Data Jabatan
        </h1>

        <p class="jabatan-import-description">
            Import data jabatan menggunakan file SQL atau Excel.
        </p>


        <div class="jabatan-import-card">

            <form method="POST" action="{{ route('master.jabatan.import.process') }}" enctype="multipart/form-data">

                @csrf

                <label class="jabatan-import-label">
                    Pilih File
                </label>

                <input type="file" name="file" class="jabatan-import-file" accept=".sql,.xls,.xlsx" required>


                <div class="jabatan-import-info">

                    <strong>Format SQL</strong><br>

                    Kolom yang digunakan:

                    <br>

                    <code>
                        jabatan_id,
                        jabatan_nama,
                        jabatan_status,
                        jabatan_kategori
                    </code>

                    <br><br>

                    <strong>Format Excel</strong><br>

                    Baris pertama harus berisi nama kolom:

                    <br>

                    <code>
                        jabatan_id |
                        jabatan_nama |
                        jabatan_status |
                        jabatan_kategori
                    </code>

                    <br><br>

                    <strong>ID Jabatan:</strong>
                    nilai
                    <code>jabatan_id</code>
                    dari file akan dipertahankan agar relasi dengan pegawai tetap sesuai.

                </div>


                <div class="jabatan-import-actions">

                    <a href="{{ route('master.jabatan.index') }}" class="jabatan-import-back">

                        <i class="bi bi-arrow-left me-1"></i>

                        Kembali

                    </a>

                    <button type="submit" class="jabatan-import-submit">

                        <i class="bi bi-upload me-1"></i>

                        Import Data

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
