@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid px-0">

        {{-- HEADER --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">

            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <a href="{{ url()->previous() }}" class="text-decoration-none" style="color:#6b7280;">
                        <i class="bi bi-arrow-left"></i>
                    </a>

                    <span class="text-muted small">
                        Administrator
                    </span>
                </div>

                <h4 class="fw-bold mb-1" style="color:#182238;">
                    Import Pemberkasan SADARIN
                </h4>

                <p class="text-muted mb-0">
                    Migrasi data pemberkasan lama ke struktur SAMPERIN tanpa memindahkan file.
                </p>
            </div>

        </div>


        {{-- ALERT ERROR --}}
        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>

                    <div>
                        <div class="fw-semibold">
                            Proses gagal
                        </div>

                        <div class="small mt-1">
                            {{ session('error') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif


        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-check-circle-fill fs-5"></i>

                    <div>
                        <div class="fw-semibold">
                            Berhasil
                        </div>

                        <div class="small mt-1">
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif


        {{-- INFO --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-body p-4">

                <div class="d-flex align-items-start gap-3">

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                        style="
                        width:46px;
                        height:46px;
                        background:#fff3e6;
                        color:#f28c28;
                        flex-shrink:0;
                     ">
                        <i class="bi bi-database fs-5"></i>
                    </div>

                    <div>

                        <h6 class="fw-bold mb-1" style="color:#182238;">
                            Sumber Data SADARIN
                        </h6>

                        <p class="text-muted small mb-0">
                            Sistem akan membaca data dari database SADARIN,
                            kemudian memasukkan hanya pemberkasan yang benar-benar
                            memiliki file ke database SAMPERIN.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- PREVIEW --}}
        @if (session('preview'))
            @php
                $preview = session('preview');
            @endphp

            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-header bg-white border-0 p-4 pb-2">
                    <div class="fw-bold" style="color:#182238;">
                        Hasil Analisis
                    </div>

                    <div class="text-muted small">
                        Data belum diubah. Silakan periksa hasil analisis sebelum memulai import.
                    </div>
                </div>


                <div class="card-body p-4">

                    <div class="row g-3">

                        {{-- TOTAL --}}
                        <div class="col-6 col-lg-3">
                            <div class="rounded-4 p-3 h-100" style="background:#f5f6f8;">
                                <div class="small text-muted mb-1">
                                    Total Data Lama
                                </div>

                                <div class="fs-4 fw-bold" style="color:#182238;">
                                    {{ number_format($preview['total']) }}
                                </div>
                            </div>
                        </div>


                        {{-- FILE --}}
                        <div class="col-6 col-lg-3">
                            <div class="rounded-4 p-3 h-100" style="background:#eefaf2;">

                                <div class="small text-muted mb-1">
                                    Memiliki File
                                </div>

                                <div class="fs-4 fw-bold text-success">
                                    {{ number_format($preview['dengan_file']) }}
                                </div>

                            </div>
                        </div>


                        {{-- KOSONG --}}
                        <div class="col-6 col-lg-3">
                            <div class="rounded-4 p-3 h-100" style="background:#f3f4f6;">

                                <div class="small text-muted mb-1">
                                    Data Kosong
                                </div>

                                <div class="fs-4 fw-bold text-secondary">
                                    {{ number_format($preview['kosong']) }}
                                </div>

                            </div>
                        </div>


                        {{-- PEGAWAI --}}
                        <div class="col-6 col-lg-3">
                            <div class="rounded-4 p-3 h-100" style="background:#eef6ff;">

                                <div class="small text-muted mb-1">
                                    Pegawai Cocok
                                </div>

                                <div class="fs-4 fw-bold text-primary">
                                    {{ number_format($preview['pegawai_cocok']) }}
                                </div>

                            </div>
                        </div>


                        {{-- PEGAWAI TIDAK COCOK --}}
                        <div class="col-6 col-lg-3">
                            <div class="rounded-4 p-3 h-100" style="background:#fff4f4;">

                                <div class="small text-muted mb-1">
                                    Pegawai Tidak Cocok
                                </div>

                                <div class="fs-4 fw-bold text-danger">
                                    {{ number_format($preview['pegawai_tidak_cocok']) }}
                                </div>

                            </div>
                        </div>


                        {{-- JENIS COCOK --}}
                        <div class="col-6 col-lg-3">
                            <div class="rounded-4 p-3 h-100" style="background:#eefaf2;">

                                <div class="small text-muted mb-1">
                                    Jenis Berkas Cocok
                                </div>

                                <div class="fs-4 fw-bold text-success">
                                    {{ number_format($preview['jenis_cocok']) }}
                                </div>

                            </div>
                        </div>


                        {{-- JENIS TIDAK COCOK --}}
                        <div class="col-6 col-lg-3">
                            <div class="rounded-4 p-3 h-100" style="background:#fff4f4;">

                                <div class="small text-muted mb-1">
                                    Jenis Tidak Cocok
                                </div>

                                <div class="fs-4 fw-bold text-danger">
                                    {{ number_format($preview['jenis_tidak_cocok']) }}
                                </div>

                            </div>
                        </div>

                    </div>


                    <hr class="my-4">


                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                        <div class="small text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Data kosong tidak akan dibuat menjadi record pemberkasan.
                        </div>

                        <form method="POST" action="{{ route('admin.import.berkas.process') }}"
                            onsubmit="return confirmImport(this)">

                            @csrf

                            <button type="submit" class="btn text-white px-4 rounded-3" style="background:#f28c28;">

                                <i class="bi bi-cloud-arrow-down me-2"></i>
                                Mulai Import

                            </button>

                        </form>

                    </div>

                </div>

            </div>
        @endif


        {{-- HASIL IMPORT --}}
        @if (session('import_stats'))
            @php
                $stats = session('import_stats');
            @endphp

            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-header bg-white border-0 p-4 pb-2">

                    <div class="fw-bold" style="color:#182238;">
                        Hasil Import
                    </div>

                    <div class="text-muted small">
                        Ringkasan proses migrasi data pemberkasan.
                    </div>

                </div>


                <div class="card-body p-4">

                    <div class="row g-3">

                        <div class="col-6 col-lg-3">
                            <div class="border rounded-4 p-3">
                                <div class="small text-muted">
                                    Data Dibaca
                                </div>

                                <div class="fs-4 fw-bold">
                                    {{ number_format($stats['dibaca']) }}
                                </div>
                            </div>
                        </div>


                        <div class="col-6 col-lg-3">
                            <div class="border border-success rounded-4 p-3">
                                <div class="small text-muted">
                                    Berhasil Diimport
                                </div>

                                <div class="fs-4 fw-bold text-success">
                                    {{ number_format($stats['diimport']) }}
                                </div>
                            </div>
                        </div>


                        <div class="col-6 col-lg-3">
                            <div class="border rounded-4 p-3">
                                <div class="small text-muted">
                                    Data Kosong
                                </div>

                                <div class="fs-4 fw-bold text-secondary">
                                    {{ number_format($stats['dilewati_kosong']) }}
                                </div>
                            </div>
                        </div>


                        <div class="col-6 col-lg-3">
                            <div class="border rounded-4 p-3">
                                <div class="small text-muted">
                                    Duplikat
                                </div>

                                <div class="fs-4 fw-bold text-warning">
                                    {{ number_format($stats['duplikat']) }}
                                </div>
                            </div>
                        </div>


                        <div class="col-6 col-lg-3">
                            <div class="border rounded-4 p-3">
                                <div class="small text-muted">
                                    Pegawai Tidak Ditemukan
                                </div>

                                <div class="fs-4 fw-bold text-danger">
                                    {{ number_format($stats['pegawai_tidak_ditemukan']) }}
                                </div>
                            </div>
                        </div>


                        <div class="col-6 col-lg-3">
                            <div class="border rounded-4 p-3">
                                <div class="small text-muted">
                                    Jenis Tidak Ditemukan
                                </div>

                                <div class="fs-4 fw-bold text-danger">
                                    {{ number_format($stats['jenis_tidak_ditemukan']) }}
                                </div>
                            </div>
                        </div>


                        <div class="col-6 col-lg-3">
                            <div class="border rounded-4 p-3">
                                <div class="small text-muted">
                                    Gagal
                                </div>

                                <div class="fs-4 fw-bold text-danger">
                                    {{ number_format($stats['gagal']) }}
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        @endif


        {{-- TOMBOL ANALISIS --}}
        @if (!session('preview'))
            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <div class="text-center py-4">

                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                            style="
                            width:70px;
                            height:70px;
                            background:#fff3e6;
                            color:#f28c28;
                         ">

                            <i class="bi bi-search fs-3"></i>

                        </div>

                        <h5 class="fw-bold" style="color:#182238;">
                            Siap Menganalisis Data
                        </h5>

                        <p class="text-muted small mx-auto" style="max-width:600px;">

                            Sistem akan membaca data pemberkasan SADARIN terlebih dahulu.
                            Tidak ada data SAMPERIN yang akan diubah pada tahap ini.

                        </p>


                        <form method="POST" action="{{ route('admin.import.berkas.preview') }}">

                            @csrf

                            <button type="submit" class="btn text-white px-4 rounded-3" style="background:#182238;">

                                <i class="bi bi-search me-2"></i>
                                Analisis Data

                            </button>

                        </form>

                    </div>

                </div>

            </div>
        @endif

    </div>


    <script>
        function confirmImport(form) {
            return confirm(
                'Import pemberkasan SADARIN sekarang?\n\n' +
                'Hanya data yang memiliki file valid yang akan dimasukkan ke SAMPERIN. ' +
                'File Google Drive tidak akan dipindahkan.'
            );
        }
    </script>
@endsection
