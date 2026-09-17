@extends('dashboard.layouts.app')

@section('title', 'Atur Tarif Gaji')

@section('header-title', 'Atur Tarif Gaji')

@section('breadcrumb', 'Peraturan Gaji / Atur Tarif')

@section('page-style')

    <style>
        .tarif-page {
            width: 100%;
        }

        /* =========================================================
               HEADER
            ========================================================= */

        .tarif-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .tarif-title {
            margin: 0;
            color: #14223b;
            font-size: 24px;
            font-weight: 800;
        }

        .tarif-description {
            margin: 6px 0 0;
            color: #8993a3;
            font-size: 13px;
        }

        .tarif-back-button {
            height: 40px;
            padding: 0 14px;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            border: 1px solid #dfe4eb;
            border-radius: 9px;

            background: #fff;
            color: #596579;

            text-decoration: none;

            font-size: 12px;
            font-weight: 600;

            transition: .15s ease;
        }

        .tarif-back-button:hover {
            background: #f8f9fb;
            color: #273449;
        }


        /* =========================================================
               ALERT
            ========================================================= */

        .tarif-alert {
            border: 0;
            border-radius: 10px;
            padding: 11px 14px;
            margin-bottom: 18px;
            font-size: 12px;
        }

        .tarif-alert ul {
            margin: 5px 0 0;
            padding-left: 18px;
        }


        /* =========================================================
               INFO PERATURAN
            ========================================================= */

        .tarif-info {
            background: #fff;

            border: 1px solid #e6eaf0;
            border-radius: 15px;

            padding: 20px;

            margin-bottom: 18px;

            box-shadow:
                0 7px 22px rgba(20, 35, 60, .035);
        }

        .tarif-info-grid {
            display: grid;

            grid-template-columns:
                2fr 1.2fr .7fr .8fr;

            gap: 20px;
        }

        .tarif-info-label {
            color: #9aa2ad;
            font-size: 10px;

            text-transform: uppercase;
            letter-spacing: .6px;

            margin-bottom: 5px;
        }

        .tarif-info-value {
            color: #273449;

            font-size: 13px;
            font-weight: 700;
        }

        .tarif-info-value.mono {
            font-family: monospace;
            font-weight: 600;
        }


        /* =========================================================
               PANEL
            ========================================================= */

        .tarif-panel {
            background: #fff;

            border: 1px solid #e6eaf0;

            border-radius: 16px;

            overflow: hidden;

            box-shadow:
                0 8px 25px rgba(20, 35, 60, .04);
        }

        .tarif-panel-header {
            padding: 17px 19px;

            border-bottom:
                1px solid #edf0f4;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 15px;
        }

        .tarif-panel-title {
            color: #273449;

            font-size: 14px;
            font-weight: 800;
        }

        .tarif-panel-subtitle {
            margin-top: 3px;

            color: #9aa2ad;

            font-size: 11px;
        }

        .tarif-count {
            padding: 6px 10px;

            border-radius: 7px;

            background: #fff0e3;
            color: #c86520;

            font-size: 11px;
            font-weight: 700;
        }


        /* =========================================================
               TABLE
            ========================================================= */

        .tarif-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .tarif-table {
            width: 100%;
            min-width: 900px;

            border-collapse: collapse;
        }

        .tarif-table th {
            padding: 13px 18px;

            background: #fafbfc;

            border-bottom:
                1px solid #edf0f4;

            color: #929aa6;

            font-size: 10px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .6px;

            white-space: nowrap;
        }

        .tarif-table td {
            padding: 13px 18px;

            border-bottom:
                1px solid #f0f2f5;

            color: #475467;

            font-size: 12px;

            vertical-align: middle;
        }

        .tarif-table tbody tr:hover {
            background: #fcfcfd;
        }

        .tarif-table tbody tr:last-child td {
            border-bottom: 0;
        }


        /* =========================================================
               GOLONGAN
            ========================================================= */

        .golongan-name {
            color: #273449;

            font-size: 13px;
            font-weight: 750;
        }

        .golongan-id {
            margin-top: 3px;

            color: #a0a8b4;

            font-family: monospace;

            font-size: 9px;
        }


        /* =========================================================
               INPUT
            ========================================================= */

        .tarif-input {
            width: 100%;
            height: 38px;

            border:
                1px solid #dfe4eb;

            border-radius: 8px;

            padding: 0 10px;

            color: #344054;

            background: #fff;

            font-size: 12px;

            outline: none;

            transition: .15s ease;
        }

        .tarif-input:focus {
            border-color: #df8339;

            box-shadow:
                0 0 0 3px rgba(223, 131, 57, .08);
        }


        /* =========================================================
               NOMINAL
            ========================================================= */

        .tarif-money {
            position: relative;
        }

        .tarif-money-prefix {
            position: absolute;

            left: 10px;
            top: 50%;

            transform: translateY(-50%);

            color: #9aa2ad;

            font-size: 11px;

            pointer-events: none;

            z-index: 2;
        }

        .tarif-money input {
            padding-left: 30px;

            text-align: right;

            font-family: monospace;

            font-size: 13px;
            font-weight: 600;
        }


        /* =========================================================
               STATUS
            ========================================================= */

        .tarif-status {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 5px 8px;

            border-radius: 20px;

            font-size: 9px;
            font-weight: 700;
        }

        .tarif-status.active {
            color: #247a4b;
            background: #eaf8f0;
        }

        .tarif-status.inactive {
            color: #7b8491;
            background: #f0f2f5;
        }

        .tarif-status-dot {
            width: 5px;
            height: 5px;

            border-radius: 50%;

            background: currentColor;
        }


        /* =========================================================
               ACTION
            ========================================================= */

        .tarif-save-button {
            height: 36px;

            padding: 0 12px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 6px;

            border: 0;

            border-radius: 8px;

            background:
                linear-gradient(135deg,
                    #df8339,
                    #c35e1d);

            color: #fff;

            font-size: 11px;
            font-weight: 700;

            cursor: pointer;

            transition: .15s ease;
        }

        .tarif-save-button:hover {
            color: #fff;
            opacity: .92;
            transform: translateY(-1px);
        }


        /* =========================================================
               BELUM DIATUR
            ========================================================= */

        .tarif-empty-badge {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 5px 8px;

            border-radius: 20px;

            background: #fff7e6;
            color: #a66a00;

            font-size: 9px;
            font-weight: 700;
        }


        /* =========================================================
               RESPONSIVE
            ========================================================= */

        @media (max-width: 850px) {

            .tarif-info-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

        }

        @media (max-width: 600px) {

            .tarif-header {
                flex-direction: column;
            }

            .tarif-back-button {
                width: 100%;
            }

            .tarif-info-grid {
                grid-template-columns: 1fr;
            }

        }
    </style>

@endsection


@section('content')

    <div class="tarif-page">


        {{-- =========================================================
             SUCCESS
        ========================================================== --}}

        @if (session('success'))
            <div class="alert alert-success tarif-alert">

                <i class="bi bi-check-circle me-1"></i>

                {{ session('success') }}

            </div>
        @endif


        {{-- =========================================================
             ERROR
        ========================================================== --}}

        @if ($errors->any())

            <div class="alert alert-danger tarif-alert">

                <div>

                    <i class="bi bi-exclamation-circle me-1"></i>

                    Terdapat kesalahan.

                </div>

                <ul>

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================================
             HEADER
        ========================================================== --}}

        <div class="tarif-header">

            <div>

                <h1 class="tarif-title">
                    Atur Tarif Gaji
                </h1>

                <p class="tarif-description">
                    Atur gaji lama dan gaji baru untuk setiap golongan.
                </p>

            </div>


            <a href="{{ route('samperin.admin.peraturan-gaji.index') }}" class="tarif-back-button">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

        </div>


        {{-- =========================================================
             INFO PERATURAN
        ========================================================== --}}

        <div class="tarif-info">

            <div class="tarif-info-grid">


                {{-- PERATURAN --}}

                <div>

                    <div class="tarif-info-label">
                        Peraturan
                    </div>

                    <div class="tarif-info-value">
                        {{ $peraturan->peraturan_gaji_nama }}
                    </div>

                </div>


                {{-- NOMOR --}}

                <div>

                    <div class="tarif-info-label">
                        Nomor
                    </div>

                    <div class="tarif-info-value mono">
                        {{ $peraturan->peraturan_gaji_nomor ?: '-' }}
                    </div>

                </div>


                {{-- TAHUN --}}

                <div>

                    <div class="tarif-info-label">
                        Tahun
                    </div>

                    <div class="tarif-info-value">
                        {{ $peraturan->peraturan_gaji_tahun }}
                    </div>

                </div>


                {{-- JUMLAH TARIF --}}

                <div>

                    <div class="tarif-info-label">
                        Tarif Diatur
                    </div>

                    <div class="tarif-info-value">

                        {{ $peraturan->golongan->count() }}

                        /

                        {{ $golongan->count() }}

                        Golongan

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             PANEL
        ========================================================== --}}

        <div class="tarif-panel">


            {{-- HEADER PANEL --}}

            <div class="tarif-panel-header">

                <div>

                    <div class="tarif-panel-title">
                        Tarif Gaji per Golongan
                    </div>

                    <div class="tarif-panel-subtitle">
                        Isi gaji lama dan gaji baru sesuai peraturan yang berlaku.
                    </div>

                </div>


                <div class="tarif-count">

                    {{ $peraturan->golongan->count() }}

                    / {{ $golongan->count() }}

                    Diatur

                </div>

            </div>


            {{-- =====================================================
                 TABLE
            ====================================================== --}}

            <div class="tarif-table-wrapper">

                <table class="tarif-table">

                    <thead>

                        <tr>

                            <th style="width: 22%;">
                                Golongan
                            </th>

                            <th style="width: 24%;">
                                Gaji Lama
                            </th>

                            <th style="width: 24%;">
                                Gaji Baru
                            </th>

                            <th style="width: 14%;">
                                Status
                            </th>

                            <th style="width: 16%;" class="text-end">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($golongan as $item)
                            @php

                                $tarif = $peraturan->golongan->firstWhere('golongan_id', $item->golongan_id);

                                $statusTarif = $tarif ? (int) $tarif->peraturan_gaji_golongan_status : 0;

                            @endphp


                            <tr>


                                {{-- =================================================
                                     GOLONGAN
                                ================================================== --}}

                                <td>

                                    <div class="golongan-name">

                                        {{ $item->golongan_nama }}

                                    </div>

                                    <div class="golongan-id">

                                        ID:
                                        {{ $item->golongan_id }}

                                    </div>

                                </td>


                                {{-- =================================================
                                     FORM
                                ================================================== --}}

                                <td colspan="4">

                                    <form method="POST"
                                        action="{{ $tarif
                                            ? route('samperin.admin.peraturan-gaji.update-golongan', [
                                                'id' => $peraturan->peraturan_gaji_id,
                                                'golonganId' => $item->golongan_id,
                                            ])
                                            : route('samperin.admin.peraturan-gaji.save-golongan', $peraturan->peraturan_gaji_id) }}"
                                        class="row g-2 align-items-center">

                                        @csrf

                                        @if ($tarif)
                                            @method('PUT')
                                        @endif


                                        <input type="hidden" name="golongan_id" value="{{ $item->golongan_id }}">


                                        {{-- GAJI LAMA --}}

                                        <div class="col">

                                            <div class="tarif-money">

                                                <span class="tarif-money-prefix">
                                                    Rp
                                                </span>

                                                <input type="text" name="peraturan_gaji_gaji_lama"
                                                    class="tarif-input nominal-input" inputmode="numeric" autocomplete="off"
                                                    value="{{ old('peraturan_gaji_gaji_lama', $tarif ? number_format($tarif->peraturan_gaji_gaji_lama, 0, ',', '.') : '') }}"
                                                    placeholder="0" required>

                                            </div>

                                        </div>


                                        {{-- GAJI BARU --}}

                                        <div class="col">

                                            <div class="tarif-money">

                                                <span class="tarif-money-prefix">
                                                    Rp
                                                </span>

                                                <input type="text" name="peraturan_gaji_gaji_baru"
                                                    class="tarif-input nominal-input" inputmode="numeric" autocomplete="off"
                                                    value="{{ old('peraturan_gaji_gaji_baru', $tarif ? number_format($tarif->peraturan_gaji_gaji_baru, 0, ',', '.') : '') }}"
                                                    placeholder="0" required>

                                            </div>

                                        </div>


                                        {{-- STATUS --}}

                                        <div class="col">

                                            <select name="peraturan_gaji_golongan_status" class="tarif-input" required>

                                                <option value="1" {{ $statusTarif === 1 ? 'selected' : '' }}>
                                                    Aktif
                                                </option>

                                                <option value="0" {{ $statusTarif === 0 ? 'selected' : '' }}>
                                                    Nonaktif
                                                </option>

                                            </select>

                                        </div>


                                        {{-- STATUS INFO --}}

                                        <div class="col-auto">

                                            @if ($tarif)
                                                @if ($statusTarif === 1)
                                                    <span class="tarif-status active">

                                                        <span class="tarif-status-dot"></span>

                                                        Aktif

                                                    </span>
                                                @else
                                                    <span class="tarif-status inactive">

                                                        <span class="tarif-status-dot"></span>

                                                        Nonaktif

                                                    </span>
                                                @endif
                                            @else
                                                <span class="tarif-empty-badge">

                                                    <i class="bi bi-exclamation-circle"></i>

                                                    Belum Diatur

                                                </span>
                                            @endif

                                        </div>


                                        {{-- SIMPAN --}}

                                        <div class="col-auto">

                                            <button type="submit" class="tarif-save-button">

                                                <i class="bi bi-check-lg"></i>

                                                {{ $tarif ? 'Simpan' : 'Tambah' }}

                                            </button>

                                        </div>


                                    </form>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection


@section('page-script')

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | Format nominal Indonesia
            |--------------------------------------------------------------------------
            |
            | 1000000
            | menjadi
            | 1.000.000
            |
            */

            document.querySelectorAll('.nominal-input').forEach(function(input) {

                function formatRupiah(value) {

                    let angka = value
                        .replace(/\D/g, '');

                    if (!angka) {
                        return '';
                    }

                    return angka.replace(
                        /\B(?=(\d{3})+(?!\d))/g,
                        '.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Format saat mengetik
                |--------------------------------------------------------------------------
                */

                input.addEventListener('input', function() {

                    this.value = formatRupiah(this.value);

                });


                /*
                |--------------------------------------------------------------------------
                | Format saat halaman dibuka
                |--------------------------------------------------------------------------
                */

                input.value = formatRupiah(input.value);

            });


            /*
            |--------------------------------------------------------------------------
            | Sebelum submit
            |--------------------------------------------------------------------------
            |
            | 1.000.000
            | diubah menjadi
            | 1000000
            |
            | sehingga Laravel menerima numeric normal.
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll('.tarif-row-form').forEach(function(form) {

                form.addEventListener('submit', function() {

                    form.querySelectorAll('.nominal-input').forEach(function(input) {

                        input.value = input.value
                            .replace(/\./g, '')
                            .replace(/\D/g, '');

                    });

                });

            });

        });
    </script>

@endsection
