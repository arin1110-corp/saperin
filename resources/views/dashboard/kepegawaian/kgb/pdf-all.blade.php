<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <style>
        @page {
            size: 215mm 330mm;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 215mm;
            height: 330mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
            font-size: 12pt;
            line-height: 1;
        }

        /* =========================================================
           HALAMAN
        ========================================================= */

        .page {
            position: relative;
            width: 215mm;
            height: 330mm;
            overflow: hidden;
            page-break-after: always;
            page-break-inside: avoid;
        }

        .page:last-child {
            page-break-after: auto;
        }

        /* =========================================================
           TEMPLATE WORD / JPG
        ========================================================= */

        .template {
            position: absolute;
            left: 0;
            top: 0;
            width: 215mm;
            height: 330mm;
            z-index: 1;
        }

        /* =========================================================
           FIELD DINAMIS
        ========================================================= */

        .field {
            position: absolute;
            z-index: 10;
            white-space: nowrap;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12pt;
            line-height: 1;
            color: #000;
        }

        /* =========================================================
           TANGGAL SURAT
        ========================================================= */

        .tanggal-surat {
            left: 155mm;
            top: 54.5mm;
        }

        /* =========================================================
           NOMOR SURAT
        ========================================================= */

        .nomor-surat {
            left: 46.5mm;
            top: 60.7mm;
        }

        .lampiran {
            left: 46.5mm;
            top: 65.7mm;
        }

        /* =========================================================
           DATA PEGAWAI
        ========================================================= */

        .nama {
            left: 118.5mm;
            top: 132.0mm;
        }

        .tempat-tgl-lahir {
            left: 118.5mm;
            top: 137.1mm;
        }

        .nip {
            left: 118.5mm;
            top: 142.2mm;
        }

        .jabatan {
            left: 118.5mm;
            top: 147mm;
            white-space: normal;
            width: 70mm;
            line-height: 1.05;
        }

        .tempat-kerja {
            left: 118.5mm;
            top: 152.1mm;
            white-space: normal;
            width: 90mm;
            line-height: 1.05;
        }

        /* =========================================================
           GAJI LAMA
        ========================================================= */

        .gaji-lama {
            left: 128mm;
            top: 157mm;
        }

        /* =========================================================
           DASAR KEPUTUSAN
        ========================================================= */

        .oleh-pejabat {
            left: 118.5mm;
            top: 167.0mm;
            white-space: normal;
            width: 70mm;
            line-height: 1.05;
        }

        .nomor-sk {
            left: 118.5mm;
            top: 172.5mm;
        }

        .tanggal-berlaku {
            left: 118.5mm;
            top: 177.5mm;
        }

        /* =========================================================
           MASA KERJA GOLONGAN
        ========================================================= */

        .masa-kerja-tahun {
            left: 118.5mm;
            top: 182.7mm;
        }

        .masa-kerja-bulan {
            left: 153mm;
            top: 182.7mm;
        }

        /* =========================================================
           GAJI BARU
        ========================================================= */

        .gaji-baru {
            left: 128mm;
            top: 198.0mm;
        }

        /* =========================================================
           TERBILANG
        ========================================================= */

        .terbilang {
            left: 118.5mm;
            top: 203.2mm;
            white-space: normal;
            width: 90mm;
            line-height: 1.05;
        }

        /* =========================================================
           BERDASARKAN MASA KERJA
        ========================================================= */

        .berdasarkan-tahun {
            left: 118.5mm;
            top: 213.3mm;
        }

        .berdasarkan-bulan {
            left: 153mm;
            top: 213.3mm;
        }

        /* =========================================================
           GOLONGAN
        ========================================================= */

        .golongan {
            left: 118.5mm;
            top: 218.8mm;
        }

        /* =========================================================
           MULAI TANGGAL
        ========================================================= */

        .mulai-tanggal {
            left: 118.5mm;
            top: 224mm;
        }

        /* =========================================================
           BERKEDUDUKAN
        ========================================================= */

        .berkedudukan {
            left: 118.5mm;
            top: 228.8mm;
        }

        /* =========================================================
           PEJABAT PENANDATANGAN
        ========================================================= */

        .pejabat-nama {
            left: 128mm;
            top: 286.0mm;
            font-weight: bold;
        }

        .pejabat-golongan {
            left: 128mm;
            top: 291.2mm;
        }

        .pejabat-nip {
            left: 128mm;
            top: 296.2mm;
        }

        .text-underline {
            text-decoration: underline;
        }

        .wrap {
            white-space: normal;
        }
    </style>
</head>

<body>

    {{-- =========================================================
         FUNCTION FORMAT NAMA
    ========================================================== --}}

    @php

        $formatNama = function ($value) {
            $value = trim((string) $value);

            if ($value === '') {
                return '';
            }

            $parts = preg_split('/\s+/', $value);

            $result = [];

            foreach ($parts as $part) {
                $part = trim($part);

                if ($part === '') {
                    continue;
                }

                if (str_contains($part, '.')) {
                    $result[] = strtoupper($part);
                } else {
                    $result[] = ucwords(strtolower($part));
                }
            }

            return implode(' ', $result);
        };

        /*
        |--------------------------------------------------------------------------
        | FORMAT TANGGAL
        |--------------------------------------------------------------------------
        */

        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $formatTanggal = function ($tanggal) use ($bulanIndonesia) {
            if (!$tanggal) {
                return '';
            }

            try {
                $tanggal = \Carbon\Carbon::parse($tanggal);

                return $tanggal->format('d') .
                    ' ' .
                    $bulanIndonesia[(int) $tanggal->format('m')] .
                    ' ' .
                    $tanggal->format('Y');
            } catch (\Throwable $e) {
                return '';
            }
        };

        /*
        |--------------------------------------------------------------------------
        | TERBILANG
        |--------------------------------------------------------------------------
        */

        $terbilang = function ($angka) use (&$terbilang) {
            $angka = (int) $angka;

            $huruf = [
                0 => 'nol',
                1 => 'satu',
                2 => 'dua',
                3 => 'tiga',
                4 => 'empat',
                5 => 'lima',
                6 => 'enam',
                7 => 'tujuh',
                8 => 'delapan',
                9 => 'sembilan',
                10 => 'sepuluh',
                11 => 'sebelas',
            ];

            if ($angka < 12) {
                return $huruf[$angka];
            }

            if ($angka < 20) {
                return $huruf[$angka - 10] . ' belas';
            }

            if ($angka < 100) {
                $puluh = intdiv($angka, 10);
                $sisa = $angka % 10;

                $hasil = $huruf[$puluh] . ' puluh';

                if ($sisa > 0) {
                    $hasil .= ' ' . $huruf[$sisa];
                }

                return $hasil;
            }

            if ($angka < 200) {
                $sisa = $angka - 100;

                return $sisa > 0 ? 'seratus ' . $terbilang($sisa) : 'seratus';
            }

            if ($angka < 1000) {
                $ratus = intdiv($angka, 100);
                $sisa = $angka % 100;

                $hasil = $huruf[$ratus] . ' ratus';

                if ($sisa > 0) {
                    $hasil .= ' ' . $terbilang($sisa);
                }

                return $hasil;
            }

            if ($angka < 2000) {
                $sisa = $angka - 1000;

                return $sisa > 0 ? 'seribu ' . $terbilang($sisa) : 'seribu';
            }

            if ($angka < 1000000) {
                $ribu = intdiv($angka, 1000);
                $sisa = $angka % 1000;

                $hasil = $terbilang($ribu) . ' ribu';

                if ($sisa > 0) {
                    $hasil .= ' ' . $terbilang($sisa);
                }

                return $hasil;
            }

            if ($angka < 1000000000) {
                $juta = intdiv($angka, 1000000);
                $sisa = $angka % 1000000;

                $hasil = $terbilang($juta) . ' juta';

                if ($sisa > 0) {
                    $hasil .= ' ' . $terbilang($sisa);
                }

                return $hasil;
            }

            if ($angka < 1000000000000) {
                $miliar = intdiv($angka, 1000000000);
                $sisa = $angka % 1000000000;

                $hasil = $terbilang($miliar) . ' miliar';

                if ($sisa > 0) {
                    $hasil .= ' ' . $terbilang($sisa);
                }

                return $hasil;
            }

            return (string) $angka;
        };

    @endphp


    {{-- =========================================================
         LOOP SEMUA KGB
    ========================================================== --}}

    @foreach ($batch->kgb as $kgb)
        @php

            /*
            |--------------------------------------------------------------------------
            | PEGAWAI
            |--------------------------------------------------------------------------
            */

            $pegawai = $kgb->user ?? null;

            /*
            |--------------------------------------------------------------------------
            | NAMA PEGAWAI
            |--------------------------------------------------------------------------
            */

            $nama = $formatNama($pegawai?->user_nama);

            $gelarBelakang = trim((string) ($pegawai?->user_gelarbelakang ?? ''));

            if ($gelarBelakang !== '') {
                $nama .= ', ' . $gelarBelakang;
            }

            $nama = trim($nama);

            /*
            |--------------------------------------------------------------------------
            | TEMPAT / TANGGAL LAHIR
            |--------------------------------------------------------------------------
            */

            $tanggalLahir = $formatTanggal($pegawai?->user_tgllahir);

            $tempatLahir = ucwords(strtolower(trim((string) ($pegawai?->user_tempatlahir ?? ''))));

            if ($tempatLahir !== '' && $tanggalLahir !== '') {
                $tempatTanggalLahir = $tempatLahir . ', ' . $tanggalLahir;
            } elseif ($tempatLahir !== '') {
                $tempatTanggalLahir = $tempatLahir;
            } else {
                $tempatTanggalLahir = $tanggalLahir;
            }

            /*
            |--------------------------------------------------------------------------
            | LOKASI KERJA
            |--------------------------------------------------------------------------
            */

            $lokasiKerja = trim((string) ($pegawai?->bidang?->bidang_nama ?? ''));

            if (!str_contains(strtolower($lokasiKerja), 'uptd')) {
                $lokasiKerja = 'Dinas Kebudayaan Provinsi Bali';
            }

            /*
            |--------------------------------------------------------------------------
            | JABATAN
            |--------------------------------------------------------------------------
            */

            $jabatan = trim((string) ($pegawai?->jabatan?->jabatan_nama ?? ''));

            /*
            |--------------------------------------------------------------------------
            | TANGGAL SURAT
            |--------------------------------------------------------------------------
            */

            $tanggalSurat = $formatTanggal($kgb->kgb_tanggal_surat ?? $batch->kgb_batch_tanggal);

            /*
            |--------------------------------------------------------------------------
            | NOMOR SURAT
            |--------------------------------------------------------------------------
            */

            $nomorSurat = trim((string) ($kgb->kgb_nomor_surat ?? ''));

            /*
            |--------------------------------------------------------------------------
            | TARIF GAJI
            |--------------------------------------------------------------------------
            */

            $tarifGaji = null;

            if ($batch->peraturanGaji && $batch->peraturanGaji->golongan) {
                $tarifGaji = $batch->peraturanGaji->golongan->firstWhere('golongan_id', $kgb->kgb_golongan_id);
            }

            /*
            |--------------------------------------------------------------------------
            | GAJI LAMA
            |--------------------------------------------------------------------------
            */

            $nilaiGajiLama = (int) ($tarifGaji?->peraturan_gaji_gaji_lama ?? 0);

            /*
            |--------------------------------------------------------------------------
            | GAJI BARU
            |--------------------------------------------------------------------------
            */

            $nilaiGajiBaru = (int) ($tarifGaji?->peraturan_gaji_gaji_baru ?? 0);

            /*
            |--------------------------------------------------------------------------
            | FORMAT GAJI
            |--------------------------------------------------------------------------
            */

            $gajiLama = number_format($nilaiGajiLama, 0, ',', '.');

            $gajiBaru = number_format($nilaiGajiBaru, 0, ',', '.');

            /*
            |--------------------------------------------------------------------------
            | NOMOR SK
            |--------------------------------------------------------------------------
            */

            $nomorSk = trim((string) ($kgb->kgb_nomor_sk ?? ''));

            /*
            |--------------------------------------------------------------------------
            | TANGGAL MULAI BERLAKU
            |--------------------------------------------------------------------------
            */

            $tanggalMulaiBerlaku = $formatTanggal($kgb->kgb_mulai_berlaku);

            /*
            |--------------------------------------------------------------------------
            | MASA KERJA GOLONGAN
            |--------------------------------------------------------------------------
            */

            $masaKerjaTahun = (int) ($kgb->kgb_masa_kerja_tahun ?? 0);

            $masaKerjaBulan = (int) ($kgb->kgb_masa_kerja_bulan ?? 0);

            /*
            |--------------------------------------------------------------------------
            | MASA KERJA BERDASARKAN TMT
            |--------------------------------------------------------------------------
            */

            $masaKerjaBerdasarkanTmtTahun = $masaKerjaTahun;

            $masaKerjaBerdasarkanTmtBulan = $masaKerjaBulan;

            if ($pegawai?->user_tmt && $kgb->kgb_mulai_berlaku) {
                try {
                    $tmt = \Carbon\Carbon::parse($pegawai->user_tmt);

                    $efektif = \Carbon\Carbon::parse($kgb->kgb_mulai_berlaku);

                    if ($tmt->lessThanOrEqualTo($efektif)) {
                        $diff = $tmt->diff($efektif);

                        $masaKerjaBerdasarkanTmtTahun = $diff->y;

                        $masaKerjaBerdasarkanTmtBulan = $diff->m;
                    }
                } catch (\Throwable $e) {
                    //
                }
            }

            /*
            |--------------------------------------------------------------------------
            | GOLONGAN PEGAWAI
            |--------------------------------------------------------------------------
            */

            $golongan = $kgb->golongan?->golongan_nama ?? ($pegawai?->golongan?->golongan_nama ?? '');

            $golongan = preg_replace('/^golongan\s*/i', '', trim($golongan));

            /*
            |--------------------------------------------------------------------------
            | BERKEDUDUKAN SEBAGAI
            |--------------------------------------------------------------------------
            */

            $berkedudukan = trim((string) ($pegawai?->status_pegawai ?? ''));

            if ($berkedudukan === '') {
                $berkedudukan = 'PPPK Daerah Provinsi Bali';
            }

            /*
            |--------------------------------------------------------------------------
            | TERBILANG GAJI BARU
            |--------------------------------------------------------------------------
            */

            $terbilangGaji = ucfirst($terbilang($nilaiGajiBaru)) . ' rupiah';

            /*
            |--------------------------------------------------------------------------
            | PEJABAT PENANDATANGAN
            |--------------------------------------------------------------------------
            */

            $pejabat = $kgb->pejabat ?? ($batch->pejabat ?? null);

            $pejabatNama = $formatNama($pejabat?->user_nama);

            $pejabatGelar = trim((string) ($pejabat?->user_gelarbelakang ?? ''));

            if ($pejabatGelar !== '') {
                $pejabatNama .= ', ' . $pejabatGelar;
            }

            $pejabatNama = trim($pejabatNama);

            $pejabatNip = trim((string) ($pejabat?->user_nip ?? ''));

            /*
            |--------------------------------------------------------------------------
            | GOLONGAN + PANGKAT PEJABAT
            |--------------------------------------------------------------------------
            */

            $pejabatGolongan = trim((string) ($pejabat?->golongan?->golongan_nama ?? ''));

            $pejabatPangkat = trim((string) ($pejabat?->golongan?->golongan_pangkat ?? ''));

            $pejabatGolongan = preg_replace('/^golongan\s*/i', '', $pejabatGolongan);

            if ($pejabatGolongan !== '' && $pejabatPangkat !== '') {
                $pejabatGolongan .= ' (' . $pejabatPangkat . ')';
            }
        @endphp


        {{-- =====================================================
             SATU PEGAWAI = SATU HALAMAN
        ====================================================== --}}

        <div class="page">

            {{-- TEMPLATE --}}

            <img src="{{ public_path('assets/images/template-surat.png') }}" class="template" alt="">


            {{-- =================================================
                 TANGGAL SURAT
            ================================================== --}}

            @if ($tanggalSurat !== '')
                <div class="field tanggal-surat">
                    {{ $tanggalSurat }}
                </div>
            @endif


            {{-- =================================================
                 NOMOR SURAT
            ================================================== --}}

            @if ($nomorSurat !== '')
                <div class="field nomor-surat">
                    {{ $nomorSurat }}
                </div>
            @endif


            {{-- =================================================
                 LAMPIRAN
            ================================================== --}}

            <div class="field lampiran">
                -
            </div>


            {{-- =================================================
                 NAMA
            ================================================== --}}

            @if ($nama !== '')
                <div class="field nama">
                    {{ $nama }}
                </div>
            @endif


            {{-- =================================================
                 TEMPAT / TANGGAL LAHIR
            ================================================== --}}

            @if ($tempatTanggalLahir !== '')
                <div class="field tempat-tgl-lahir">
                    {{ $tempatTanggalLahir }}
                </div>
            @endif


            {{-- =================================================
                 NIP
            ================================================== --}}

            @if ($pegawai?->user_nip)
                <div class="field nip">
                    {{ $pegawai->user_nip }}
                </div>
            @endif


            {{-- =================================================
                 JABATAN
            ================================================== --}}

            @if ($jabatan !== '')
                <div class="field jabatan">
                    {{ $jabatan }}
                </div>
            @endif


            {{-- =================================================
                 TEMPAT BEKERJA
            ================================================== --}}

            @if ($lokasiKerja !== '')
                <div class="field tempat-kerja">
                    {{ $lokasiKerja }}
                </div>
            @endif


            {{-- =================================================
                 GAJI POKOK LAMA
            ================================================== --}}

            @if ($nilaiGajiLama > 0)
                <div class="field gaji-lama">
                    {{ $gajiLama }},-
                </div>
            @endif


            {{-- =================================================
                 OLEH PEJABAT
            ================================================== --}}

            @if (!empty($batch->kgb_batch_oleh_pejabat))
                <div class="field oleh-pejabat">
                    {{ $batch->kgb_batch_oleh_pejabat }}
                </div>
            @endif


            {{-- =================================================
                 NOMOR SK
            ================================================== --}}

            @if ($nomorSk !== '')
                <div class="field nomor-sk">
                    {{ $nomorSk }}
                </div>
            @endif


            {{-- =================================================
                 TANGGAL MULAI BERLAKU
            ================================================== --}}

            @if ($tanggalMulaiBerlaku !== '')
                <div class="field tanggal-berlaku">
                    {{ $tanggalMulaiBerlaku }}
                </div>
            @endif


            {{-- =================================================
                 MASA KERJA GOLONGAN
            ================================================== --}}

            <div class="field masa-kerja-tahun">
                {{ str_pad($masaKerjaTahun, 2, '0', STR_PAD_LEFT) }}
            </div>

            <div class="field masa-kerja-bulan">
                {{ str_pad($masaKerjaBulan, 2, '0', STR_PAD_LEFT) }}
            </div>


            {{-- =================================================
                 GAJI POKOK BARU
            ================================================== --}}

            @if ($nilaiGajiBaru > 0)
                <div class="field gaji-baru">
                    {{ $gajiBaru }},-
                </div>
            @endif


            {{-- =================================================
                 TERBILANG
            ================================================== --}}

            @if ($nilaiGajiBaru > 0)
                <div class="field terbilang">
                    ({{ $terbilangGaji }})
                </div>
            @endif


            {{-- =================================================
                 BERDASARKAN MASA KERJA
            ================================================== --}}

            <div class="field berdasarkan-tahun">
                {{ str_pad($masaKerjaBerdasarkanTmtTahun, 2, '0', STR_PAD_LEFT) }}
            </div>

            <div class="field berdasarkan-bulan">
                {{ str_pad($masaKerjaBerdasarkanTmtBulan, 2, '0', STR_PAD_LEFT) }}
            </div>


            {{-- =================================================
                 DALAM GOLONGAN
            ================================================== --}}

            @if ($golongan !== '')
                <div class="field golongan">
                    {{ $golongan }}
                </div>
            @endif


            {{-- =================================================
                 MULAI TANGGAL
            ================================================== --}}

            @if ($tanggalMulaiBerlaku !== '')
                <div class="field mulai-tanggal">
                    {{ $tanggalMulaiBerlaku }}
                </div>
            @endif


            {{-- =================================================
                 BERKEDUDUKAN SEBAGAI
            ================================================== --}}

            @if ($berkedudukan !== '')
                <div class="field berkedudukan">
                    {{ $berkedudukan }}
                </div>
            @endif


            {{-- =================================================
                 PEJABAT PENANDATANGAN
            ================================================== --}}

            @if ($pejabatNama !== '')
                <div class="field pejabat-nama text-underline">
                    {{ $pejabatNama }}
                </div>
            @endif


            @if ($pejabatGolongan !== '')
                <div class="field pejabat-golongan">
                    {{ $pejabatGolongan }}
                </div>
            @endif


            @if ($pejabatNip !== '')
                <div class="field pejabat-nip">
                    NIP. {{ $pejabatNip }}
                </div>
            @endif

        </div>
    @endforeach

</body>

</html>
