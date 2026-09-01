@extends('dashboard.layouts.app')

@section('title', 'Import Golongan')

@section('header-title', 'Import Golongan')

@section('breadcrumb', 'Import Golongan')

@section('content')

    <div class="container-fluid">

        @if ($errors->any())

            <div class="alert alert-danger">

                <i class="bi bi-exclamation-circle me-1"></i>

                @foreach ($errors->all() as $error)
                    <div>
                        {{ $error }}
                    </div>
                @endforeach

            </div>

        @endif


        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <h5 class="mb-1 fw-bold">
                    Import Data Golongan
                </h5>

                <small class="text-muted">
                    Import data menggunakan file SQL, XLS, atau XLSX.
                </small>

            </div>


            <div class="card-body">

                <form method="POST" action="{{ route('master.golongan.import.process') }}" enctype="multipart/form-data">

                    @csrf

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            File Import
                        </label>

                        <input type="file" name="file" class="form-control" accept=".sql,.xls,.xlsx" required>

                        <div class="form-text">
                            Format yang didukung: SQL, XLS, XLSX. Maksimal 10 MB.
                        </div>

                    </div>


                    <div class="d-flex gap-2">

                        <a href="{{ route('master.golongan.index') }}" class="btn btn-light border">

                            <i class="bi bi-arrow-left me-1"></i>

                            Kembali

                        </a>


                        <button type="submit" class="btn btn-primary">

                            <i class="bi bi-upload me-1"></i>

                            Import

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
