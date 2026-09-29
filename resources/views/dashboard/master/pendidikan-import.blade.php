@extends('dashboard.layouts.app')

@section('title', 'Import Pendidikan')

@section('header-title', 'Import Pendidikan')

@section('breadcrumb', 'Import Pendidikan')

@section('page-style')

    <style>
        .pendidikan-import-page {
            max-width: 850px;
        }

        .pendidikan-import-card {
            background: #fff;
            border: 1px solid #e6eaf0;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(20, 35, 60, .04);
        }

        .pendidikan-import-title {
            color: #14223b;
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .pendidikan-import-description {
            color: #8993a3;
            font-size: 12px;
            margin-bottom: 22px;
        }

        /*
            |--------------------------------------------------------------------------
            | TAB
            |--------------------------------------------------------------------------
            */

        .pendidikan-import-tabs {
            display: flex;
            gap: 5px;
            padding: 5px;
            background: #f5f7fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .pendidikan-import-tabs .nav-link {
            flex: 1;
            border: 0;
            border-radius: 7px;
            color: #667085;
            font-size: 12px;
            font-weight: 700;
            padding: 10px 15px;
            background: transparent;
        }

        .pendidikan-import-tabs .nav-link.active {
            background: #fff;
            color: #df8339;
            box-shadow: 0 2px 8px rgba(20, 35, 60, .06);
        }

        /*
            |--------------------------------------------------------------------------
            | FILE
            |--------------------------------------------------------------------------
            */

        .pendidikan-file {
            border: 2px dashed #dfe4eb;
            border-radius: 13px;
            padding: 35px 20px;
            text-align: center;
            background: #fafbfc;
        }

        .pendidikan-file-icon {
            font-size: 32px;
            color: #df8339;
            margin-bottom: 10px;
        }

        .pendidikan-file-text {
            color: #475467;
            font-size: 13px;
            font-weight: 700;
        }

        .pendidikan-file-info {
            margin-top: 5px;
            color: #9aa3b0;
            font-size: 11px;
        }

        .pendidikan-file input {
            margin-top: 15px;
            max-width: 450px;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }

        /*
            |--------------------------------------------------------------------------
            | ACTION
            |--------------------------------------------------------------------------
            */

        .pendidikan-import-actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .pendidikan-back {
            border: 1px solid #dfe4eb;
            border-radius: 8px;
            padding: 10px 16px;
            background: #fff;
            color: #667085;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .pendidikan-submit {
            border: 0;
            border-radius: 8px;
            padding: 10px 17px;
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }

        .pendidikan-alert {
            border: 0;
            border-radius: 10px;
            font-size: 12px;
        }
    </style>

@endsection


@section('content')

    <div class="pendidikan-import-page">

        {{-- ERROR --}}

        @if ($errors->any())

            <div class="alert alert-danger pendidikan-alert mb-3">

                <i class="bi bi-exclamation-circle me-1"></i>

                @foreach ($errors->all() as $error)
                    <div>
                        {{ $error }}
                    </div>
                @endforeach

            </div>

        @endif


        {{-- SUCCESS --}}

        @if (session('success'))
            <div class="alert alert-success pendidikan-alert mb-3">

                <i class="bi bi-check-circle me-1"></i>

                {{ session('success') }}

            </div>
        @endif


        <div class="pendidikan-import-card">

            <div class="pendidikan-import-title">
                Import Data Pendidikan
            </div>

            <div class="pendidikan-import-description">
                Pilih metode import data pendidikan yang ingin digunakan.
            </div>


            {{-- TAB --}}

            <ul class="nav pendidikan-import-tabs" role="tablist">

                <li class="nav-item" role="presentation">

                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#import-sql" type="button"
                        role="tab">
                        <i class="bi bi-database me-1"></i>
                        Import SQL
                    </button>

                </li>

                <li class="nav-item" role="presentation">

                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#import-excel" type="button"
                        role="tab">
                        <i class="bi bi-file-earmark-excel me-1"></i>
                        Import Excel SIMPEG
                    </button>

                </li>

            </ul>


            {{-- TAB CONTENT --}}

            <div class="tab-content">

                {{-- =========================================================
                     IMPORT SQL
                ========================================================== --}}

                <div class="tab-pane fade show active" id="import-sql" role="tabpanel">

                    <div class="pendidikan-import-description">
                        Import data pendidikan menggunakan file SQL.
                    </div>


                    <form method="POST" action="{{ route('master.pendidikan.import.process') }}"
                        enctype="multipart/form-data">

                        @csrf


                        <div class="pendidikan-file">

                            <div class="pendidikan-file-icon">
                                <i class="bi bi-database"></i>
                            </div>

                            <div class="pendidikan-file-text">
                                Pilih file SQL untuk diimport
                            </div>

                            <div class="pendidikan-file-info">
                                Format yang didukung: SQL — maksimal 10 MB
                            </div>

                            <input type="file" name="file" class="form-control" accept=".sql" required>

                        </div>


                        <div class="pendidikan-import-actions">

                            <a href="{{ route('master.pendidikan.index') }}" class="pendidikan-back">
                                <i class="bi bi-arrow-left me-1"></i>
                                Kembali
                            </a>


                            <button type="submit" class="pendidikan-submit">
                                <i class="bi bi-upload me-1"></i>
                                Import SQL
                            </button>

                        </div>

                    </form>

                </div>


                {{-- =========================================================
                     IMPORT EXCEL SIMPEG
                ========================================================== --}}

                <div class="tab-pane fade" id="import-excel" role="tabpanel">

                    <div class="pendidikan-import-description">

                        Import master pendidikan dari Excel SIMPEG.
                        Sistem mengambil kolom
                        <strong>PENDIDIKAN TERAKHIR</strong>
                        dan
                        <strong>JURUSAN PENDIDIKAN</strong>.

                    </div>


                    <form method="POST" action="{{ route('master.pendidikan.import-simpeg') }}"
                        enctype="multipart/form-data">

                        @csrf


                        <div class="pendidikan-file">

                            <div class="pendidikan-file-icon">
                                <i class="bi bi-file-earmark-excel"></i>
                            </div>

                            <div class="pendidikan-file-text">
                                Pilih file Excel SIMPEG
                            </div>

                            <div class="pendidikan-file-info">
                                Format yang didukung: XLS, XLSX — maksimal 10 MB
                            </div>

                            <input type="file" name="file" class="form-control" accept=".xls,.xlsx" required>

                        </div>


                        <div class="pendidikan-import-actions">

                            <a href="{{ route('master.pendidikan.index') }}" class="pendidikan-back">
                                <i class="bi bi-arrow-left me-1"></i>
                                Kembali
                            </a>


                            <button type="submit" class="pendidikan-submit">
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Import Excel
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection
