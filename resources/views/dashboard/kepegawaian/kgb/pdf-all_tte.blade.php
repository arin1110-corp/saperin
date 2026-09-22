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
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12pt;
            line-height: 1;
            color: #000;
            white-space: nowrap;
        }

        /* =========================================================
           TANGGAL SURAT
        ========================================================= */

        .tanggal-surat {
            left: 141mm;
            top: 231.6mm;
        }

        /* =========================================================
           NOMOR SURAT
        ========================================================= */

        .nomor-surat {
            left: 60.5mm;
            top: 55.4mm;
        }

        .lampiran {
            left: 60.5mm;
            top: 59.2mm;
        }

        /* =========================================================
           DATA PEGAWAI
        ========================================================= */

        .nama {
            left: 117.5mm;
            top: 119.1mm;
            width: 85mm;
            white-space: nowrap;
        }

        .nama-atas {
            left: 117.5mm;
            top: 115.1mm;
            width: 85mm;
            white-space: nowrap;
        }

        .nama-bawah {
            left: 117.5mm;
            top: 119.1mm;
            width: 85mm;
            white-space: nowrap;
        }

        .tempat-tgl-lahir {
            left: 117.5mm;
            top: 123.8mm;
        }

        .nip {
            left: 117.5mm;
            top: 128.4mm;
        }

        .jabatan {
            left: 117.5mm;
            top: 132.8mm;
            white-space: normal;
            width: 70mm;
            line-height: 1.05;
        }

        .tempat-kerja {
            left: 117.5mm;
            top: 136.8mm;
            white-space: normal;
            width: 90mm;
            line-height: 1.05;
        }

        /* =========================================================
           GAJI LAMA
        ========================================================= */

        .gaji-lama {
            left: 125mm;
            top: 141.5mm;
        }

        /* =========================================================
           DASAR KEPUTUSAN
        ========================================================= */

        .oleh-pejabat {
            left: 117.5mm;
            top: 150.5mm;
            white-space: normal;
            width: 70mm;
            line-height: 1.05;
        }

        .nomor-sk {
            left: 117.5mm;
            top: 154.8mm;
        }

        .tanggal-berlaku {
            left: 117.5mm;
            top: 159.5mm;
        }

        /* =========================================================
           MASA KERJA GOLONGAN
        ========================================================= */

        .masa-kerja-tahun {
            left: 117.5mm;
            top: 164.3mm;
        }

        .masa-kerja-bulan {
            left: 150mm;
            top: 164.3mm;
        }

        /* =========================================================
           GAJI BARU
        ========================================================= */

        .gaji-baru {
            left: 125mm;
            top: 177.5mm;
        }

        /* =========================================================
           TERBILANG
        ========================================================= */

        .terbilang {
            left: 117.5mm;
            top: 181.8mm;
            white-space: normal;
            width: 90mm;
            line-height: 1.05;
        }

        /* =========================================================
           BERDASARKAN MASA KERJA
        ========================================================= */

        .berdasarkan-tahun {
            left: 117.5mm;
            top: 191.5mm;
        }

        .berdasarkan-bulan {
            left: 150mm;
            top: 191.5mm;
        }

        /* =========================================================
           GOLONGAN
        ========================================================= */

        .golongan {
            left: 117.5mm;
            top: 196mm;
        }

        /* =========================================================
           MULAI TANGGAL
        ========================================================= */

        .mulai-tanggal {
            left: 117.5mm;
            top: 200.6mm;
        }

        /* =========================================================
           BERKEDUDUKAN
        ========================================================= */

        .berkedudukan {
            left: 117.5mm;
            top: 204.7mm;
        }
    </style>
</head>

<body>

    {{-- =========================================================
         LOOP SEMUA KGB DALAM BATCH
         SATU KGB = SATU HALAMAN PDF
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
            | FORMAT NAMA
            |--------------------------------------------------------------------------
            */

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

                    /*
                    |--------------------------------------------------------------------------
                    | Jika mengandung titik:
                    | S.Pd
                    | M.Si
                    | Dr.
                    |--------------------------------------------------------------------------
                    */

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
            | PEMENGGALAN NAMA
            |--------------------------------------------------------------------------
            */

            $namaAtas = '';
            $namaBawah = '';
            $namaDuaBaris = false;

            /*
            |--------------------------------------------------------------------------
            | LEBAR MAKSIMAL
            |--------------------------------------------------------------------------
            */

            $maxNamaWidth = 75;

            /*
            |--------------------------------------------------------------------------
            | HITUNG PERKIRAAN LEBAR NAMA
            |--------------------------------------------------------------------------
            */

            $hitungLebarNama = function ($text) {
                $lebar = 0;

                $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);

                foreach ($chars as $char) {
                    /*
                    |--------------------------------------------------------------------------
                    | SPASI
                    |--------------------------------------------------------------------------
                    */

                    if ($char === ' ') {
                        $lebar += 1.05;
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | KARAKTER SEMPIT
                    |--------------------------------------------------------------------------
                    */

                    if (in_array($char, ['i', 'I', 'l', 'j', 't', 'f', 'r', 'J', '.', ',', "'", ':'], true)) {
                        $lebar += 1.0;
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | KARAKTER LEBAR
                    |--------------------------------------------------------------------------
                    */

                    if (in_array($char, ['W', 'M', 'O', 'Q', 'G', 'D', 'B', 'C', 'H', 'N', 'U'], true)) {
                        $lebar += 3.2;
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | HURUF BESAR
                    |--------------------------------------------------------------------------
                    */

                    if (ctype_upper($char)) {
                        $lebar += 2.5;
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | ANGKA
                    |--------------------------------------------------------------------------
                    */

                    if (ctype_digit($char)) {
                        $lebar += 2.1;
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | HURUF NORMAL
                    |--------------------------------------------------------------------------
                    */

                    $lebar += 2.2;
                }

                return $lebar;
            };

            /*
            |--------------------------------------------------------------------------
            | PEMENGGALAN NAMA
            |--------------------------------------------------------------------------
            */

            if ($nama !== '') {
                $lebarNama = $hitungLebarNama($nama);

                /*
                |--------------------------------------------------------------------------
                | NAMA MASIH MUAT
                |--------------------------------------------------------------------------
                */

                if ($lebarNama <= $maxNamaWidth) {
                    $namaDuaBaris = false;

                    $namaAtas = '';

                    $namaBawah = $nama;
                } else {
                    /*
                    |--------------------------------------------------------------------------
                    | NAMA 2 BARIS
                    |--------------------------------------------------------------------------
                    */

                    $namaDuaBaris = true;

                    $kataNama = preg_split('/\s+/', $nama, -1, PREG_SPLIT_NO_EMPTY);

                    $barisAtas = [];

                    $barisBawah = [];

                    $lebarBarisAtas = 0;

                    foreach ($kataNama as $index => $kata) {
                        $kataDenganSpasi = empty($barisAtas) ? $kata : ' ' . $kata;

                        $lebarKata = $hitungLebarNama($kataDenganSpasi);

                        $sisaKata = count($kataNama) - $index - 1;

                        /*
                        |--------------------------------------------------------------------------
                        | Masih muat baris atas
                        |--------------------------------------------------------------------------
                        */

                        if ($lebarBarisAtas + $lebarKata <= $maxNamaWidth && $sisaKata >= 1) {
                            $barisAtas[] = $kata;

                            $lebarBarisAtas += $lebarKata;
                        } else {
                            /*
                            |--------------------------------------------------------------------------
                            | Kata masuk baris bawah
                            |--------------------------------------------------------------------------
                            */

                            $barisBawah[] = $kata;

                            for ($i = $index + 1; $i < count($kataNama); $i++) {
                                $barisBawah[] = $kataNama[$i];
                            }

                            break;
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Jika baris atas kosong
                    |--------------------------------------------------------------------------
                    */

                    if (empty($barisAtas)) {
                        $barisAtas[] = array_shift($kataNama);

                        $barisBawah = $kataNama;
                    }

                    $namaAtas = implode(' ', $barisAtas);

                    $namaBawah = implode(' ', $barisBawah);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | BULAN INDONESIA
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

            /*
            |--------------------------------------------------------------------------
            | FORMAT TANGGAL
            |--------------------------------------------------------------------------
            */

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
            | TMT BERKALA
            |--------------------------------------------------------------------------
            */

            $tmtBerkala = $pegawai?->user_tmt_berkala ?? null;

            $tanggalMulaiBerlaku_Berkala = $formatTanggal($tmtBerkala);

            /*
            |--------------------------------------------------------------------------
            | MASA KERJA GOLONGAN
            |
            | TMT BERKALA - TMT AWAL
            |--------------------------------------------------------------------------
            */

            $masaKerjaTahun_Berkala = 0;

            $masaKerjaBulan_Berkala = 0;

            $tmtAwal_Berkala = $pegawai?->user_tmt_awal ?? null;

            if ($tmtAwal_Berkala && $tmtBerkala) {
                try {
                    $awal = \Carbon\Carbon::parse($tmtAwal_Berkala);

                    $berkala = \Carbon\Carbon::parse($tmtBerkala);

                    if ($awal->lessThanOrEqualTo($berkala)) {
                        $diff = $awal->diff($berkala);

                        $masaKerjaTahun_Berkala = $diff->y;

                        $masaKerjaBulan_Berkala = $diff->m;
                    }
                } catch (\Throwable $e) {
                    //
                }
            }

            /*
            |--------------------------------------------------------------------------
            | TANGGAL MULAI BERLAKU KGB
            |--------------------------------------------------------------------------
            */

            $tanggalMulaiBerlaku = $formatTanggal($kgb->kgb_mulai_berlaku);

            /*
            |--------------------------------------------------------------------------
            | MASA KERJA KGB
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


        {{-- =========================================================
             SATU HALAMAN UNTUK SATU PEGAWAI
        ========================================================== --}}

        <div class="page">


            {{-- =====================================================
                 TEMPLATE
            ====================================================== --}}

            <img src="{{ public_path('assets/images/template-surat-tte.png') }}" class="template" alt="">


            {{-- =====================================================
                 TANGGAL SURAT
            ====================================================== --}}

            @if ($tanggalSurat !== '')
                <div class="field tanggal-surat">
                    {{ $tanggalSurat }}
                </div>
            @endif


            {{-- =====================================================
                 NOMOR SURAT
            ====================================================== --}}

            @if ($nomorSurat !== '')
                <div class="field nomor-surat">
                    {{ $nomorSurat }}
                </div>
            @endif


            {{-- =====================================================
                 LAMPIRAN
            ====================================================== --}}

            <div class="field lampiran">
                -
            </div>


            {{-- =====================================================
                 1. NAMA
            ====================================================== --}}

            @if ($nama !== '')
                @if ($namaDuaBaris)
                    <div class="field nama-atas">
                        {{ $namaAtas }}
                    </div>

                    <div class="field nama-bawah">
                        {{ $namaBawah }}
                    </div>
                @else
                    <div class="field nama">
                        {{ $nama }}
                    </div>
                @endif
            @endif


            {{-- =====================================================
                 2. TEMPAT / TANGGAL LAHIR
            ====================================================== --}}

            @if ($tempatTanggalLahir !== '')
                <div class="field tempat-tgl-lahir">
                    {{ $tempatTanggalLahir }}
                </div>
            @endif


            {{-- =====================================================
                 3. NIP
            ====================================================== --}}

            @if ($pegawai?->user_nip)
                <div class="field nip">
                    {{ substr($pegawai->user_nip, 0, 8) }}
                    {{ substr($pegawai->user_nip, 8, 6) }}
                    {{ substr($pegawai->user_nip, 14, 1) }}
                    {{ substr($pegawai->user_nip, 15, 3) }}
                </div>
            @endif


            {{-- =====================================================
                 4. JABATAN
            ====================================================== --}}

            @if ($jabatan !== '')
                <div class="field jabatan">
                    {{ $jabatan }}
                </div>
            @endif


            {{-- =====================================================
                 5. KANTOR / TEMPAT BEKERJA
            ====================================================== --}}

            @if ($lokasiKerja !== '')
                <div class="field tempat-kerja">
                    {{ $lokasiKerja }}
                </div>
            @endif


            {{-- =====================================================
                 6. GAJI POKOK LAMA
            ====================================================== --}}

            @if ($nilaiGajiLama > 0)
                <div class="field gaji-lama">
                    {{ $gajiLama }},-
                </div>
            @endif


            {{-- =====================================================
                 a. OLEH PEJABAT
            ====================================================== --}}

            @if (!empty($batch->kgb_batch_oleh_pejabat))
                <div class="field oleh-pejabat">
                    {{ $batch->kgb_batch_oleh_pejabat }}
                </div>
            @endif


            {{-- =====================================================
                 b. NOMOR SK
            ====================================================== --}}

            @if ($nomorSk !== '')
                <div class="field nomor-sk">
                    {{ $nomorSk }}
                </div>
            @endif


            {{-- =====================================================
                 c. TANGGAL MULAI BERLAKU
                 
                 MENGGUNAKAN TMT BERKALA
            ====================================================== --}}

            @if ($tanggalMulaiBerlaku_Berkala !== '')
                <div class="field tanggal-berlaku">
                    {{ $tanggalMulaiBerlaku_Berkala }}
                </div>
            @endif


            {{-- =====================================================
                 d. MASA KERJA GOLONGAN
                 
                 TMT BERKALA - TMT AWAL
            ====================================================== --}}

            <div class="field masa-kerja-tahun">
                {{ str_pad($masaKerjaTahun_Berkala, 2, '0', STR_PAD_LEFT) }}
            </div>

            <div class="field masa-kerja-bulan">
                {{ str_pad($masaKerjaBulan_Berkala, 2, '0', STR_PAD_LEFT) }}
            </div>


            {{-- =====================================================
                 7. GAJI POKOK BARU
            ====================================================== --}}

            @if ($nilaiGajiBaru > 0)
                <div class="field gaji-baru">
                    {{ $gajiBaru }},-
                </div>
            @endif


            {{-- =====================================================
                 TERBILANG
            ====================================================== --}}

            @if ($nilaiGajiBaru > 0)
                <div class="field terbilang">
                    ({{ $terbilangGaji }})
                </div>
            @endif


            {{-- =====================================================
                 8. BERDASARKAN MASA KERJA
            ====================================================== --}}

            <div class="field berdasarkan-tahun">
                {{ str_pad($masaKerjaBerdasarkanTmtTahun, 2, '0', STR_PAD_LEFT) }}
            </div>

            <div class="field berdasarkan-bulan">
                {{ str_pad($masaKerjaBerdasarkanTmtBulan, 2, '0', STR_PAD_LEFT) }}
            </div>


            {{-- =====================================================
                 9. DALAM GOLONGAN
            ====================================================== --}}

            @if ($golongan !== '')
                <div class="field golongan">
                    {{ $golongan }}
                </div>
            @endif


            {{-- =====================================================
                 10. MULAI TANGGAL
            ====================================================== --}}

            @if ($tanggalMulaiBerlaku !== '')
                <div class="field mulai-tanggal">
                    {{ $tanggalMulaiBerlaku }}
                </div>
            @endif


            {{-- =====================================================
                 11. BERKEDUDUKAN SEBAGAI
            ====================================================== --}}

            @if ($berkedudukan !== '')
                <div class="field berkedudukan">
                    {{ $berkedudukan }}
                </div>
            @endif

        </div>
    @endforeach

</body>

</html>
