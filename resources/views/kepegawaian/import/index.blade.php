@extends('dashboard-kepeg.layouts.app')

@section('title', 'Import Data Pegawai')

@section('page-title', 'Import Data Pegawai')

@push('styles')
    <style>
        .import-page {
            max-width: 1180px;
            margin: 0 auto;
        }

        .page-eyebrow {
            color: #ea580c;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.3px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .page-heading {
            color: #172033;
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -.6px;
            margin: 0;
        }

        .page-description {
            color: #64748b;
            font-size: 13px;
            margin-top: 7px;
            margin-bottom: 0;
        }


        /* ==========================================================
           CARD
        ========================================================== */

        .import-card {
            background: #fff;
            border: 1px solid #e7eaf0;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, .035);
            margin-top: 22px;
        }

        .import-card-header {
            padding: 20px 22px 14px;
        }

        .import-card-title {
            color: #172033;
            font-size: 15px;
            font-weight: 700;
            margin: 0;
        }

        .import-card-description {
            color: #94a3b8;
            font-size: 11px;
            margin-top: 4px;
            margin-bottom: 0;
        }

        .import-card-body {
            padding: 0 22px 22px;
        }


        /* ==========================================================
           FILE UPLOAD
        ========================================================== */

        .upload-box {
            position: relative;
            border: 1.5px dashed #cbd5e1;
            border-radius: 14px;
            background: #fafbfc;
            min-height: 175px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            transition: all .2s ease;
        }

        .upload-box:hover {
            border-color: #f97316;
            background: #fffaf5;
        }

        .upload-box.has-file {
            border-color: #f97316;
            background: #fff7ed;
        }

        .upload-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #fff7ed;
            color: #ea580c;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 21px;
        }

        .upload-title {
            color: #334155;
            font-size: 13px;
            font-weight: 600;
        }

        .upload-subtitle {
            color: #94a3b8;
            font-size: 11px;
            margin-top: 4px;
        }

        .upload-file-name {
            color: #ea580c;
            font-size: 11px;
            font-weight: 600;
            margin-top: 8px;
            word-break: break-all;
        }


        /* ==========================================================
           MASTER
        ========================================================== */

        .master-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 16px;
        }

        .master-select-all {
            border: 1px solid #fed7aa;
            background: #fff7ed;
            color: #c2410c;
            border-radius: 9px;
            padding: 7px 12px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        .master-select-all:hover {
            background: #ffedd5;
        }


        /* ==========================================================
           MASTER ITEM
        ========================================================== */

        .master-item {
            position: relative;
            height: 100%;
            border: 1px solid #e7eaf0;
            border-radius: 14px;
            background: #fff;
            padding: 17px;
            cursor: pointer;
            transition: all .2s ease;
        }

        .master-item:hover {
            border-color: #fdba74;
            box-shadow: 0 6px 18px rgba(249, 115, 22, .07);
            transform: translateY(-1px);
        }

        .master-item.selected {
            border-color: #fb923c;
            background: #fffaf5;
        }

        .master-check {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 17px;
            height: 17px;
            cursor: pointer;
            accent-color: #ea580c;
        }

        .master-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            background: #f8fafc;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            margin-bottom: 14px;
            transition: .2s;
        }

        .master-item:hover .master-icon,
        .master-item.selected .master-icon {
            background: #fff7ed;
            color: #ea580c;
        }

        .master-name {
            color: #172033;
            font-size: 13px;
            font-weight: 700;
        }

        .master-table {
            color: #94a3b8;
            font-size: 10px;
            margin-top: 4px;
        }

        .master-uid {
            display: inline-block;
            margin-top: 9px;
            padding: 3px 7px;
            border-radius: 6px;
            background: #f8fafc;
            color: #64748b;
            font-size: 9px;
            font-weight: 600;
        }


        /* ==========================================================
           INFO
        ========================================================== */

        .import-info {
            margin-top: 22px;
            padding: 17px 19px;
            border: 1px solid #fed7aa;
            background: #fffaf5;
            border-radius: 14px;
        }

        .import-info-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #9a3412;
            font-size: 12px;
            font-weight: 700;
        }

        .import-info-title i {
            color: #ea580c;
        }

        .import-info-list {
            margin: 9px 0 0 25px;
            padding: 0;
            color: #7c2d12;
            font-size: 11px;
            line-height: 1.8;
        }


        /* ==========================================================
           ALERT
        ========================================================== */

        .import-alert {
            border-radius: 14px;
            padding: 15px 17px;
            margin-top: 20px;
            border: 1px solid;
        }

        .import-alert-error {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .import-alert-success {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }

        .import-alert-title {
            font-size: 12px;
            font-weight: 700;
        }

        .import-alert-message {
            font-size: 11px;
            margin-top: 3px;
        }


        /* ==========================================================
           FOOTER BUTTON
        ========================================================== */

        .import-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 22px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 15px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #64748b;
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
        }

        .btn-back:hover {
            background: #f8fafc;
            color: #334155;
        }

        .btn-import {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 19px;
            border: 0;
            border-radius: 10px;
            background: #ea580c;
            color: white;
            font-size: 11px;
            font-weight: 700;
            box-shadow: 0 5px 12px rgba(234, 88, 12, .18);
            transition: .2s;
        }

        .btn-import:hover {
            background: #c2410c;
            color: white;
            transform: translateY(-1px);
        }

        .btn-import:disabled {
            opacity: .55;
            cursor: not-allowed;
            transform: none;
        }


        /* ==========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 767.98px) {

            .page-heading {
                font-size: 23px;
            }

            .import-card-header {
                padding: 17px;
            }

            .import-card-body {
                padding: 0 17px 17px;
            }

            .master-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .import-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .btn-back,
            .btn-import {
                justify-content: center;
            }

        }
    </style>
@endpush


@section('content')

    <div class="import-page">


        {{-- ==========================================================
       PAGE HEADER
    =========================================================== --}}

        <div>

            <div class="page-eyebrow">
                Kepegawaian
            </div>

            <h1 class="page-heading">
                Import Data Pegawai
            </h1>

            <p class="page-description">
                Migrasikan data master dari SADARIN ke SAMPERIN.
            </p>

        </div>


        {{-- ==========================================================
       ALERT ERROR
    =========================================================== --}}

        @if ($errors->any())

            <div class="import-alert import-alert-error">

                <div class="d-flex gap-2">

                    <i class="bi bi-exclamation-triangle-fill"></i>

                    <div>

                        <div class="import-alert-title">
                            Import Gagal
                        </div>

                        @foreach ($errors->all() as $error)
                            <div class="import-alert-message">
                                {{ $error }}
                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

        @endif


        {{-- ==========================================================
       SUCCESS
    =========================================================== --}}

        @if (session('import_success'))
            <div class="import-alert import-alert-success">

                <div class="d-flex gap-2">

                    <i class="bi bi-check-circle-fill"></i>

                    <div>

                        <div class="import-alert-title">
                            Import Berhasil
                        </div>

                        <div class="import-alert-message">
                            {{ session('import_success') }}
                        </div>

                    </div>

                </div>

            </div>
        @endif


        <form action="{{ route('kepeg.pegawai.import.process') }}" method="POST" enctype="multipart/form-data">

            @csrf


            {{-- ======================================================
           FILE SQL
        ======================================================= --}}

            <div class="import-card">

                <div class="import-card-header">

                    <h2 class="import-card-title">
                        File SQL
                    </h2>

                    <p class="import-card-description">
                        Pilih file export SQL dari database SADARIN.
                    </p>

                </div>


                <div class="import-card-body">

                    <label for="sql-file" class="upload-box" id="upload-box">

                        <input type="file" id="sql-file" name="file" accept=".sql,application/sql,text/sql"
                            class="d-none" required>


                        <div>

                            <div class="upload-icon">

                                <i class="bi bi-database-fill"></i>

                            </div>


                            <div class="upload-title">

                                Klik untuk memilih file SQL

                            </div>


                            <div class="upload-subtitle">

                                Format yang didukung: .sql

                            </div>


                            <div id="file-name" class="upload-file-name d-none"></div>

                        </div>

                    </label>

                </div>

            </div>


            {{-- ======================================================
           MASTER
        ======================================================= --}}

            <div class="import-card">

                <div class="import-card-header">

                    <div class="master-header mb-0">

                        <div>

                            <h2 class="import-card-title">
                                Pilih Data yang Diimport
                            </h2>

                            <p class="import-card-description">
                                ID lama SADARIN dipertahankan.
                                UID SAMPERIN dibuat otomatis.
                            </p>

                        </div>


                        <button type="button" id="select-all" class="master-select-all">

                            <i class="bi bi-check2-square me-1"></i>

                            Pilih Semua

                        </button>

                    </div>

                </div>


                <div class="import-card-body">

                    @php

                        $masters = [
                            'jabatan' => [
                                'label' => 'Jabatan',
                                'table' => 'sadarin_jabatan',
                                'icon' => 'bi-briefcase-fill',
                            ],

                            'bidang' => [
                                'label' => 'Bidang',
                                'table' => 'sadarin_bidang',
                                'icon' => 'bi-diagram-3-fill',
                            ],

                            'golongan' => [
                                'label' => 'Golongan',
                                'table' => 'sadarin_golongan',
                                'icon' => 'bi-award-fill',
                            ],

                            'eselon' => [
                                'label' => 'Eselon',
                                'table' => 'sadarin_eselon',
                                'icon' => 'bi-layers-fill',
                            ],

                            'pendidikan' => [
                                'label' => 'Pendidikan',
                                'table' => 'sadarin_pendidikan',
                                'icon' => 'bi-mortarboard-fill',
                            ],

                            'jenis_kerja' => [
                                'label' => 'Jenis Kerja',
                                'table' => 'sadarin_jenis_kerja',
                                'icon' => 'bi-person-workspace',
                            ],
                        ];

                    @endphp


                    <div class="row g-3">

                        @foreach ($masters as $key => $master)
                            <div class="col-12 col-sm-6 col-lg-4">

                                <label class="master-item d-block" data-master-item>

                                    <input type="checkbox" name="tables[]" value="{{ $key }}" class="master-check">


                                    <div class="master-icon">

                                        <i class="bi {{ $master['icon'] }}"></i>

                                    </div>


                                    <div class="master-name">

                                        {{ $master['label'] }}

                                    </div>


                                    <div class="master-table">

                                        {{ $master['table'] }}

                                    </div>


                                    <div class="master-uid">

                                        ID → UID

                                    </div>

                                </label>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>


            {{-- ======================================================
           INFO
        ======================================================= --}}

            <div class="import-info">

                <div class="import-info-title">

                    <i class="bi bi-info-circle-fill"></i>

                    Ketentuan Import

                </div>


                <ul class="import-info-list">

                    <li>
                        ID lama dari SADARIN tetap dipertahankan.
                    </li>

                    <li>
                        UID SAMPERIN dibuat untuk setiap data master.
                    </li>

                    <li>
                        UID yang sudah ada tidak dibuat ulang ketika import ulang.
                    </li>

                    <li>
                        Hanya tabel yang dicentang yang akan diproses.
                    </li>

                    <li>
                        Data pegawai tidak otomatis mencampur tabel master lainnya.
                    </li>

                </ul>

            </div>


            {{-- ======================================================
           ACTION
        ======================================================= --}}

            <div class="import-actions">

                <a href="{{ route('kepeg.dashboard') }}" class="btn-back">

                    <i class="bi bi-arrow-left"></i>

                    Kembali

                </a>


                <button type="submit" class="btn-import" id="btn-import">

                    <i class="bi bi-database-up"></i>

                    Import Data

                </button>

            </div>


        </form>

    </div>


@endsection


@push('scripts')
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {


                const fileInput =
                    document.getElementById(
                        'sql-file'
                    );


                const fileName =
                    document.getElementById(
                        'file-name'
                    );


                const uploadBox =
                    document.getElementById(
                        'upload-box'
                    );


                const checkboxes =
                    document.querySelectorAll(
                        '.master-check'
                    );


                const selectAll =
                    document.getElementById(
                        'select-all'
                    );


                const importButton =
                    document.getElementById(
                        'btn-import'
                    );


                /*
                |--------------------------------------------------------------------------
                | FILE
                |--------------------------------------------------------------------------
                */

                fileInput.addEventListener(
                    'change',
                    function() {

                        if (
                            this.files &&
                            this.files.length
                        ) {

                            const file =
                                this.files[0];


                            fileName.textContent =
                                file.name;


                            fileName.classList.remove(
                                'd-none'
                            );


                            uploadBox.classList.add(
                                'has-file'
                            );

                        } else {

                            fileName.textContent = '';

                            fileName.classList.add(
                                'd-none'
                            );

                            uploadBox.classList.remove(
                                'has-file'
                            );

                        }

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | MASTER CHECKBOX
                |--------------------------------------------------------------------------
                */

                function updateMasterCards() {

                    checkboxes.forEach(
                        function(checkbox) {

                            const card =
                                checkbox.closest(
                                    '[data-master-item]'
                                );


                            if (!card) {
                                return;
                            }


                            if (checkbox.checked) {

                                card.classList.add(
                                    'selected'
                                );

                            } else {

                                card.classList.remove(
                                    'selected'
                                );

                            }

                        }
                    );

                }


                checkboxes.forEach(
                    function(checkbox) {

                        checkbox.addEventListener(
                            'change',
                            updateMasterCards
                        );

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | SELECT ALL
                |--------------------------------------------------------------------------
                */

                selectAll.addEventListener(
                    'click',
                    function() {

                        const allChecked =
                            Array.from(
                                checkboxes
                            ).every(
                                checkbox =>
                                checkbox.checked
                            );


                        checkboxes.forEach(
                            function(checkbox) {

                                checkbox.checked = !allChecked;

                            }
                        );


                        updateMasterCards();


                        this.innerHTML =
                            allChecked

                            ?
                            '<i class="bi bi-check2-square me-1"></i> Pilih Semua'

                            :
                            '<i class="bi bi-x-square me-1"></i> Batalkan Semua';

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | SUBMIT
                |--------------------------------------------------------------------------
                */

                document
                    .querySelector('form')
                    .addEventListener(
                        'submit',
                        function(event) {

                            const selected =
                                Array.from(
                                    checkboxes
                                ).filter(
                                    checkbox =>
                                    checkbox.checked
                                );


                            if (
                                selected.length === 0
                            ) {

                                event.preventDefault();


                                alert(
                                    'Pilih minimal satu data yang akan diimport.'
                                );


                                return;

                            }


                            if (
                                !fileInput.files ||
                                !fileInput.files.length
                            ) {

                                event.preventDefault();


                                alert(
                                    'Pilih file SQL terlebih dahulu.'
                                );


                                return;

                            }


                            importButton.disabled =
                                true;


                            importButton.innerHTML =
                                '<span class="spinner-border spinner-border-sm"></span> Memproses...';

                        }
                    );


            }
        );
    </script>
@endpush
