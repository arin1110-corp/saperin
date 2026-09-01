@extends('dashboard.layouts.app')

@section('content')

    <div class="container-fluid">

        <div class="d-flex align-items-center justify-content-between mb-4">

            <div>

                <h4 class="mb-1">
                    Import Data Bidang
                </h4>

                <div class="text-muted small">
                    Import data bidang menggunakan file SQL atau Excel.
                </div>

            </div>

            <a href="{{ route('master.bidang.index') }}" class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Kembali

            </a>

        </div>


        {{-- =====================================================
         ERROR
    ====================================================== --}}

        @if ($errors->any())
            <div class="alert alert-danger">

                <div class="fw-semibold mb-2">

                    <i class="bi bi-exclamation-triangle-fill me-1"></i>

                    Import gagal

                </div>

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </div>
        @endif


        {{-- =====================================================
         FORM
    ====================================================== --}}

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <form method="POST" action="{{ route('master.bidang.import.process') }}" enctype="multipart/form-data">

                    @csrf


                    <div class="mb-4">

                        <label class="form-label fw-semibold">

                            File Import

                        </label>

                        <input type="file" name="file" class="form-control" accept=".sql,.xls,.xlsx" required>

                        <div class="form-text">

                            Format yang didukung:
                            <strong>SQL, XLS, XLSX</strong>.
                            Maksimal 10 MB.

                        </div>

                    </div>


                    {{-- =================================================
                     FORMAT SQL
                ================================================== --}}

                    <div class="border rounded p-3 mb-3">

                        <div class="fw-semibold mb-2">

                            <i class="bi bi-filetype-sql me-1"></i>

                            Format SQL

                        </div>

                        <div class="small text-muted">

                            File SQL hasil SQLyog seperti:

                            <code>
                                INSERT INTO sadarin_bidang
                            </code>

                            dapat langsung digunakan.

                        </div>

                        <div class="small text-muted mt-2">

                            Kolom yang dibaca:

                            <code>bidang_id</code>,
                            <code>bidang_nama</code>,
                            <code>bidang_link</code>,
                            <code>bidang_status</code>

                        </div>

                    </div>


                    {{-- =================================================
                     FORMAT EXCEL
                ================================================== --}}

                    <div class="border rounded p-3 mb-4">

                        <div class="fw-semibold mb-2">

                            <i class="bi bi-file-earmark-excel me-1"></i>

                            Format Excel

                        </div>

                        <div class="small text-muted">

                            Baris pertama harus berisi nama kolom.

                        </div>

                        <div class="mt-2">

                            <code>bidang_id</code> &nbsp;
                            <code>bidang_nama</code> &nbsp;
                            <code>bidang_kode</code> &nbsp;
                            <code>bidang_status</code>

                        </div>

                        <div class="small text-muted mt-2">

                            <strong>bidang_id wajib diisi.</strong>

                            UID tidak perlu diisi karena sistem
                            akan membuat UID otomatis.

                        </div>

                    </div>


                    {{-- =================================================
                     WARNING ID
                ================================================== --}}

                    <div class="alert alert-warning">

                        <div class="fw-semibold mb-1">

                            <i class="bi bi-info-circle me-1"></i>

                            Perhatian ID

                        </div>

                        <div class="small">

                            <strong>bidang_id akan mengikuti ID dari file import.</strong>

                            Jika ID tersebut sudah ada di database,
                            data bidang dengan ID tersebut akan diperbarui.

                            Ini dilakukan agar ID tetap konsisten dengan
                            relasi data pegawai.

                        </div>

                    </div>


                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('master.bidang.index') }}" class="btn btn-light">

                            Batal

                        </a>

                        <button type="submit" class="btn btn-primary">

                            <i class="bi bi-upload me-1"></i>

                            Import Data

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
