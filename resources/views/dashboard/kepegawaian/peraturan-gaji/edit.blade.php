@extends('dashboard.layouts.app')

@section('title', 'Edit Peraturan Gaji')
@section('header-title', 'Edit Peraturan Gaji')
@section('breadcrumb', 'Edit Peraturan Gaji')

@section('page-style')
    <style>
        .gaji-edit-page {
            padding-bottom: 30px;
        }

        .gaji-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e9edf3;
            box-shadow: 0 4px 15px rgba(20, 34, 59, 0.06);
            overflow: hidden;
        }

        .gaji-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #edf0f4;
            background: #fafbfc;
        }

        .gaji-card-header h5 {
            margin: 0;
            color: #14223b;
            font-weight: 700;
        }

        .gaji-card-header p {
            margin: 5px 0 0;
            color: #7a8495;
            font-size: 13px;
        }

        .gaji-card-body {
            padding: 25px;
        }

        .gaji-label {
            display: block;
            margin-bottom: 8px;
            color: #14223b;
            font-size: 14px;
            font-weight: 600;
        }

        .gaji-required {
            color: #dc3545;
        }

        .gaji-input,
        .gaji-select {
            width: 100%;
            min-height: 44px;
            border: 1px solid #dce1e8;
            border-radius: 9px;
            padding: 10px 13px;
            font-size: 14px;
            color: #14223b;
            background: #fff;
            outline: none;
            transition: .2s;
        }

        .gaji-input:focus,
        .gaji-select:focus {
            border-color: #df8339;
            box-shadow: 0 0 0 3px rgba(223, 131, 57, 0.12);
        }

        .gaji-help {
            margin-top: 6px;
            font-size: 12px;
            color: #8a93a1;
        }

        .gaji-error {
            margin-top: 6px;
            font-size: 12px;
            color: #dc3545;
        }

        .gaji-actions {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #edf0f4;
        }

        .gaji-btn {
            min-height: 42px;
            padding: 9px 18px;
            border-radius: 9px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: .2s;
        }

        .gaji-btn-back {
            background: #eef1f5;
            color: #14223b;
        }

        .gaji-btn-back:hover {
            background: #e1e5ea;
            color: #14223b;
        }

        .gaji-btn-save {
            background: linear-gradient(135deg, #df8339, #c35e1d);
            color: #fff;
        }

        .gaji-btn-save:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(195, 94, 29, 0.2);
        }

        .gaji-alert {
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .gaji-alert-danger {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
        }

        .gaji-info-box {
            margin-bottom: 22px;
            padding: 14px 16px;
            border-radius: 10px;
            background: #f5f7fa;
            border: 1px solid #e5e9ef;
        }

        .gaji-info-title {
            color: #14223b;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .gaji-info-text {
            color: #7a8495;
            font-size: 12px;
            margin: 0;
        }

        @media (max-width: 768px) {
            .gaji-card-body {
                padding: 18px;
            }

            .gaji-actions {
                flex-direction: column-reverse;
            }

            .gaji-btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')

    <div class="gaji-edit-page">

        {{-- ERROR VALIDASI --}}
        @if ($errors->any())
            <div class="gaji-alert gaji-alert-danger">
                <div class="fw-bold mb-1">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Terdapat kesalahan:
                </div>

                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="gaji-card">

            {{-- HEADER --}}
            <div class="gaji-card-header">
                <h5>
                    <i class="bi bi-pencil-square me-2"></i>
                    Edit Peraturan Gaji
                </h5>

                <p>
                    Perbarui informasi peraturan gaji yang digunakan dalam sistem Kenaikan Gaji Berkala.
                </p>
            </div>

            {{-- BODY --}}
            <div class="gaji-card-body">

                <div class="gaji-info-box">
                    <div class="gaji-info-title">
                        {{ $peraturan->peraturan_gaji_nama }}
                    </div>

                    <p class="gaji-info-text">
                        Nomor: {{ $peraturan->peraturan_gaji_nomor }}
                        &nbsp;|&nbsp;
                        Tahun: {{ $peraturan->peraturan_gaji_tahun }}
                    </p>
                </div>

                <form method="POST"
                    action="{{ route('samperin.admin.peraturan-gaji.update', $peraturan->peraturan_gaji_id) }}">

                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- NAMA --}}
                        <div class="col-md-8">
                            <label class="gaji-label">
                                Nama Peraturan
                                <span class="gaji-required">*</span>
                            </label>

                            <input type="text" name="peraturan_gaji_nama"
                                class="gaji-input @error('peraturan_gaji_nama') is-invalid @enderror"
                                value="{{ old('peraturan_gaji_nama', $peraturan->peraturan_gaji_nama) }}"
                                placeholder="Contoh: Peraturan Pemerintah tentang Gaji PNS" required>

                            @error('peraturan_gaji_nama')
                                <div class="gaji-error">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- NOMOR --}}
                        <div class="col-md-4">
                            <label class="gaji-label">
                                Nomor Peraturan
                                <span class="gaji-required">*</span>
                            </label>

                            <input type="text" name="peraturan_gaji_nomor"
                                class="gaji-input @error('peraturan_gaji_nomor') is-invalid @enderror"
                                value="{{ old('peraturan_gaji_nomor', $peraturan->peraturan_gaji_nomor) }}"
                                placeholder="Contoh: 5 Tahun 2024" required>

                            @error('peraturan_gaji_nomor')
                                <div class="gaji-error">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- TAHUN --}}
                        <div class="col-md-4">
                            <label class="gaji-label">
                                Tahun
                                <span class="gaji-required">*</span>
                            </label>

                            <input type="number" name="peraturan_gaji_tahun"
                                class="gaji-input @error('peraturan_gaji_tahun') is-invalid @enderror"
                                value="{{ old('peraturan_gaji_tahun', $peraturan->peraturan_gaji_tahun) }}" min="2000"
                                max="2100" required>

                            @error('peraturan_gaji_tahun')
                                <div class="gaji-error">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- TANGGAL --}}
                        <div class="col-md-4">
                            <label class="gaji-label">
                                Tanggal Peraturan
                            </label>

                            <input type="date" name="peraturan_gaji_tanggal"
                                class="gaji-input @error('peraturan_gaji_tanggal') is-invalid @enderror"
                                value="{{ old('peraturan_gaji_tanggal', $peraturan->peraturan_gaji_tanggal ? \Carbon\Carbon::parse($peraturan->peraturan_gaji_tanggal)->format('Y-m-d') : '') }}">

                            @error('peraturan_gaji_tanggal')
                                <div class="gaji-error">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="gaji-help">
                                Kosongkan jika tanggal peraturan tidak tersedia.
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-4">
                            <label class="gaji-label">
                                Status
                                <span class="gaji-required">*</span>
                            </label>

                            <select name="peraturan_gaji_status"
                                class="gaji-select @error('peraturan_gaji_status') is-invalid @enderror" required>
                                <option value="1"
                                    {{ old('peraturan_gaji_status', $peraturan->peraturan_gaji_status) == 1 ? 'selected' : '' }}>
                                    Aktif
                                </option>

                                <option value="0"
                                    {{ old('peraturan_gaji_status', $peraturan->peraturan_gaji_status) == 0 ? 'selected' : '' }}>
                                    Nonaktif
                                </option>
                            </select>

                            @error('peraturan_gaji_status')
                                <div class="gaji-error">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    {{-- ACTION --}}
                    <div class="gaji-actions">

                        <a href="{{ route('samperin.admin.peraturan-gaji.index') }}" class="gaji-btn gaji-btn-back">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </a>

                        <button type="submit" class="gaji-btn gaji-btn-save">
                            <i class="bi bi-check-lg"></i>
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection
