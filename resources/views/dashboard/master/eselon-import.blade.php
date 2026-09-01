@extends('dashboard.layouts.app')

@section('title', 'Import Eselon')

@section('header-title', 'Import Eselon')

@section('breadcrumb', 'Import Eselon')

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

            <div class="card-body p-4">

                <h5 class="fw-bold mb-2">
                    Import Data Eselon
                </h5>

                <p class="text-muted small mb-4">
                    Import data Eselon menggunakan file SQL, XLS, atau XLSX.
                </p>


                <form method="POST" action="{{ route('master.eselon.import.process') }}" enctype="multipart/form-data">

                    @csrf


                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            File Import
                        </label>

                        <input type="file" name="file" class="form-control" accept=".sql,.xls,.xlsx" required>

                        <div class="form-text">
                            Format yang didukung: SQL, XLS, XLSX. Maksimal 10 MB.
                        </div>

                    </div>


                    <div class="d-flex gap-2">

                        <a href="{{ route('master.eselon.index') }}" class="btn btn-light border">

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
