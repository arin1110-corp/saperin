<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <style>
        /* =========================================================
           PAGE F4
        ========================================================= */

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
            font-size: 10pt;
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
            margin: 0;
            padding: 0;
        }


        /* =========================================================
           TEMPLATE WORD / JPG
        ========================================================= */

        .template-surat {
            position: absolute;
            left: 0;
            top: 0;

            width: 215mm;
            height: 330mm;

            display: block;
            margin: 0;
            padding: 0;
        }


        /* =========================================================
           SEMUA FIELD DINAMIS
        ========================================================= */

        .field {
            position: absolute;
            z-index: 10;

            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1;

            color: #000;

            margin: 0;
            padding: 0;
        }


        /* =========================================================
           KOORDINAT DINAMIS

           SEMUA KOORDINAT DI SINI.
           Kalau mau geser nanti cukup ubah left / top.
        ========================================================= */


        /* ---------------------------------------------------------
           TANGGAL SURAT
        --------------------------------------------------------- */

        .f-tanggal {
            left: 157mm;
            top: 57mm;
            width: 43mm;
            text-align: right;
            white-space: nowrap;
        }


        /* ---------------------------------------------------------
           NOMOR / LAMPIRAN / HAL
        --------------------------------------------------------- */

        .f-nomor {
            left: 58mm;
            top: 63mm;
            width: 120mm;
            white-space: nowrap;
        }

        .f-lampiran {
            left: 58mm;
            top: 68mm;
            width: 120mm;
            white-space: nowrap;
        }

        .f-hal {
            left: 58mm;
            top: 73mm;
            width: 100mm;

            font-weight: bold;
            text-decoration: underline;
            white-space: nowrap;
        }


        /* =========================================================
           DATA PEGAWAI
        ========================================================= */


        /* ---------------------------------------------------------
           1. NAMA
        --------------------------------------------------------- */

        .f-nama {
            left: 80mm;
            top: 132mm;
            width: 85mm;
        }


        /* ---------------------------------------------------------
           2. TEMPAT / TANGGAL LAHIR
        --------------------------------------------------------- */

        .f-tempat-lahir {
            left: 80mm;
            top: 137mm;
            width: 85mm;
        }


        /* ---------------------------------------------------------
           3. NIP
        --------------------------------------------------------- */

        .f-nip {
            left: 80mm;
            top: 142mm;
            width: 90mm;
            white-space: nowrap;
        }


        /* ---------------------------------------------------------
           4. JABATAN
        --------------------------------------------------------- */

        .f-jabatan {
            left: 80mm;
            top: 147mm;
            width: 88mm;
        }


        /* ---------------------------------------------------------
           5. KANTOR / TEMPAT BEKERJA
        --------------------------------------------------------- */

        .f-lokasi {
            left: 80mm;
            top: 157mm;
            width: 88mm;
        }


        /* ---------------------------------------------------------
           6. GAJI POKOK LAMA
        --------------------------------------------------------- */

        .f-gaji-lama {
            left: 80mm;
            top: 167mm;
            width: 80mm;
            white-space: nowrap;
        }


        /* =========================================================
           DASAR KEPUTUSAN
        ========================================================= */

        .f-oleh-pejabat {
            left: 80mm;
            top: 178mm;
            width: 90mm;
        }

        .f-nomor-sk {
            left: 80mm;
            top: 184mm;
            width: 95mm;
        }

        .f-tanggal-berlaku {
            left: 80mm;
            top: 189mm;
            width: 85mm;
        }


        /* ---------------------------------------------------------
           MASA KERJA GOLONGAN
        --------------------------------------------------------- */

        .f-masa-tahun {
            left: 80mm;
            top: 194mm;
            width: 20mm;
            text-align: left;
        }

        .f-masa-bulan {
            left: 111mm;
            top: 194mm;
            width: 20mm;
            text-align: left;
        }


        /* ---------------------------------------------------------
           GOLONGAN
        --------------------------------------------------------- */

        .f-golongan {
            left: 80mm;
            top: 204mm;
            width: 60mm;
        }


        /* =========================================================
           7. GAJI POKOK BARU
        ========================================================= */

        .f-gaji-baru {
            left: 80mm;
            top: 212mm;
            width: 80mm;
            white-space: nowrap;
        }


        /* ---------------------------------------------------------
           TERBILANG
        --------------------------------------------------------- */

        .f-terbilang {
            left: 80mm;
            top: 218mm;
            width: 105mm;

            font-size: 9pt;
            line-height: 1.05;
        }


        /* =========================================================
           8. BERDASARKAN MASA KERJA
        ========================================================= */

        .f-masa-tmt-tahun {
            left: 80mm;
            top: 230mm;
            width: 20mm;
        }

        .f-masa-tmt-bulan {
            left: 111mm;
            top: 230mm;
            width: 20mm;
        }


        /* =========================================================
           9. DALAM GOLONGAN
        ========================================================= */

        .f-golongan-2 {
            left: 80mm;
            top: 235mm;
            width: 60mm;
        }


        /* =========================================================
           10. MULAI TANGGAL
        ========================================================= */

        .f-mulai-tanggal {
            left: 80mm;
            top: 240mm;
            width: 80mm;
        }


        /* =========================================================
           11. BERKEDUDUKAN
        ========================================================= */

        .f-kedudukan {
            left: 80mm;
            top: 245mm;
            width: 90mm;
        }


        /* =========================================================
           PARAGRAF PENUTUP
        ========================================================= */

        .f-paragraf {
            position: absolute;
            z-index: 10;

            left: 30mm;
            top: 254mm;

            width: 155mm;

            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            line-height: 1.05;

            color: #000;

            text-align: justify;

            margin: 0;
            padding: 0;
        }


        /* =========================================================
           TANDA TANGAN
        ========================================================= */

        .f-ttd-nama {
            left: 145mm;
            top: 286mm;
            width: 58mm;

            font-weight: bold;
            text-decoration: underline;

            white-space: nowrap;
        }


        /* ---------------------------------------------------------
           JABATAN PEJABAT DINAMIS
        --------------------------------------------------------- */

        .f-ttd-jabatan {
            left: 145mm;
            top: 291mm;
            width: 60mm;
        }


        /* ---------------------------------------------------------
           GOLONGAN PEJABAT DINAMIS
        --------------------------------------------------------- */

        .f-ttd-golongan {
            left: 145mm;
            top: 296mm;
            width: 60mm;
        }


        /* ---------------------------------------------------------
           NIP PEJABAT
        --------------------------------------------------------- */

        .f-ttd-nip {
            left: 145mm;
            top: 301mm;
            width: 65mm;

            white-space: nowrap;
        }


        /* =========================================================
           UTIL
        ========================================================= */

        .nowrap {
            white-space: nowrap;
        }

        .wrap {
            white-space: normal;
        }

    </style>
</head>


<body>

@php

    /* =========================================================
       FORMAT NAMA
    ========================================================= */

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
             * Pertahankan inisial seperti:
             * A.A.
             * I.A.
             * I.P.
             */
            if (str_contains($part, '.')) {

                $result[] = strtoupper($part);

            } else {

                $result[] = ucwords(
                    strtolower($part)
                );
            }
        }

        return implode(' ', $result);
    };


    /* =========================================================
       PEGAWAI
    ========================================================= */

    $pegawai = $kgb->user ?? null;


    /* =========================================================
       NAMA PEGAWAI
    ========================================================= */

    $nama = $formatNama(
        $pegawai?->user_nama
    );

    $gelarBelakang = trim(
        (string) (
            $pegawai?->user_gelarbelakang ?? ''
        )
    );

    if ($gelarBelakang !== '') {

        $nama .= ', ' . $gelarBelakang;
    }

    $nama = trim($nama);

    if ($nama === '') {

        $nama = '-';
    }


    /* =========================================================
       PEJABAT PENANDATANGAN
    ========================================================= */

    $pejabat =
        $kgb->pejabat
        ?? $batch->pejabat
        ?? null;


    /* =========================================================
       NAMA PEJABAT
    ========================================================= */

    $pejabatNama = $formatNama(
        $pejabat?->user_nama
    );

    $pejabatGelar = trim(
        (string) (
            $pejabat?->user_gelarbelakang ?? ''
        )
    );

    if ($pejabatGelar !== '') {

        $pejabatNama .= ', ' . $pejabatGelar;
    }

    $pejabatNama = trim($pejabatNama);

    if ($pejabatNama === '') {

        $pejabatNama = '-';
    }


    /* =========================================================
       JABATAN PEJABAT DINAMIS
    ========================================================= */

    $pejabatJabatan =
        trim(
            (string) (
                $pejabat?->jabatan?->jabatan_nama
                ?? '-'
            )
        );

    if ($pejabatJabatan === '') {

        $pejabatJabatan = '-';
    }


    /* =========================================================
       GOLONGAN PEJABAT
    ========================================================= */

    $pejabatGolongan =
        trim(
            (string) (
                $pejabat?->golongan?->golongan_nama
                ?? '-'
            )
        );

    if ($pejabatGolongan === '') {

        $pejabatGolongan = '-';
    }


    /* =========================================================
       NIP PEJABAT
    ========================================================= */

    $pejabatNip =
        trim(
            (string) (
                $pejabat?->user_nip ?? '-'
            )
        );


    /* =========================================================
       GOLONGAN PEGAWAI
    ========================================================= */

    $golongan =
        $kgb->golongan?->golongan_nama
        ?? $pegawai?->golongan?->golongan_nama
        ?? '-';

    /*
     * Hapus kata "Golongan" kalau ada.
     *
     * Contoh:
     * Golongan IX
     * menjadi
     * IX
     */

    $golongan = preg_replace(
        '/^golongan\s*/i',
        '',
        trim($golongan)
    );


    /* =========================================================
       BULAN INDONESIA
    ========================================================= */

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


    /* =========================================================
       FORMAT TANGGAL
    ========================================================= */

    $formatTanggal = function ($tanggal)
        use ($bulanIndonesia) {

        if (!$tanggal) {
            return '-';
        }

        try {

            $tanggal =
                \Carbon\Carbon::parse($tanggal);

            return $tanggal->format('d')
                . ' '
                . $bulanIndonesia[
                    (int) $tanggal->format('m')
                ]
                . ' '
                . $tanggal->format('Y');

        } catch (\Throwable $e) {

            return '-';
        }
    };


    /* =========================================================
       TANGGAL SURAT
    ========================================================= */

    $tanggalSurat =
        $formatTanggal(
            $kgb->kgb_tanggal_surat
            ?? $batch->kgb_batch_tanggal
        );


    /* =========================================================
       TEMPAT / TANGGAL LAHIR
    ========================================================= */

    $tanggalLahir =
        $formatTanggal(
            $pegawai?->user_tgllahir
        );

    $tempatLahir =
        trim(
            (string) (
                $pegawai?->user_tempatlahir ?? ''
            )
        );

    if (
        $tempatLahir !== ''
        && $tanggalLahir !== '-'
    ) {

        $tempatTanggalLahir =
            $tempatLahir
            . ', '
            . $tanggalLahir;

    } elseif ($tempatLahir !== '') {

        $tempatTanggalLahir =
            $tempatLahir;

    } else {

        $tempatTanggalLahir =
            $tanggalLahir;
    }


    /* =========================================================
       LOKASI KERJA
    ========================================================= */

    $lokasiKerja =
        trim(
            (string) (
                $pegawai?->user_lokasikerja ?? ''
            )
        );

    /*
     * Kalau bukan UPTD:
     * Dinas Kebudayaan Provinsi Bali
     */

    if (
        $lokasiKerja === ''
        || stripos(
            $lokasiKerja,
            'UPTD'
        ) === false
    ) {

        $lokasiKerja =
            'Dinas Kebudayaan Provinsi Bali';
    }


    /* =========================================================
       GAJI
    ========================================================= */

    $nilaiGajiLama =
        (int) (
            $kgb->kgb_gaji_lama ?? 0
        );

    $nilaiGajiBaru =
        (int) (
            $kgb->kgb_gaji_baru ?? 0
        );


    $gajiLama =
        number_format(
            $nilaiGajiLama,
            0,
            ',',
            '.'
        );


    $gajiBaru =
        number_format(
            $nilaiGajiBaru,
            0,
            ',',
            '.'
        );


    /* =========================================================
       TERBILANG
    ========================================================= */

    $terbilang = function ($angka)
        use (&$terbilang) {

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

            return
                $huruf[$angka - 10]
                . ' belas';
        }


        if ($angka < 100) {

            $puluh =
                intdiv($angka, 10);

            $sisa =
                $angka % 10;

            $hasil =
                $huruf[$puluh]
                . ' puluh';

            if ($sisa > 0) {

                $hasil .=
                    ' '
                    . $huruf[$sisa];
            }

            return $hasil;
        }


        if ($angka < 200) {

            $sisa =
                $angka - 100;

            return $sisa > 0
                ? 'seratus '
                    . $terbilang($sisa)
                : 'seratus';
        }


        if ($angka < 1000) {

            $ratus =
                intdiv($angka, 100);

            $sisa =
                $angka % 100;

            $hasil =
                $huruf[$ratus]
                . ' ratus';

            if ($sisa > 0) {

                $hasil .=
                    ' '
                    . $terbilang($sisa);
            }

            return $hasil;
        }


        if ($angka < 2000) {

            $sisa =
                $angka - 1000;

            return $sisa > 0
                ? 'seribu '
                    . $terbilang($sisa)
                : 'seribu';
        }


        if ($angka < 1000000) {

            $ribu =
                intdiv($angka, 1000);

            $sisa =
                $angka % 1000;

            $hasil =
                $terbilang($ribu)
                . ' ribu';

            if ($sisa > 0) {

                $hasil .=
                    ' '
                    . $terbilang($sisa);
            }

            return $hasil;
        }


        if ($angka < 1000000000) {

            $juta =
                intdiv(
                    $angka,
                    1000000
                );

            $sisa =
                $angka % 1000000;

            $hasil =
                $terbilang($juta)
                . ' juta';

            if ($sisa > 0) {

                $hasil .=
                    ' '
                    . $terbilang($sisa);
            }

            return $hasil;
        }


        if ($angka < 1000000000000) {

            $miliar =
                intdiv(
                    $angka,
                    1000000000
                );

            $sisa =
                $angka % 1000000000;

            $hasil =
                $terbilang($miliar)
                . ' miliar';

            if ($sisa > 0) {

                $hasil .=
                    ' '
                    . $terbilang($sisa);
            }

            return $hasil;
        }


        return (string) $angka;
    };


    $terbilangGaji =
        $terbilang($nilaiGajiBaru);


    /* =========================================================
       NOMOR SK
    ========================================================= */

    $nomorSk =
        trim(
            (string) (
                $kgb->kgb_nomor_sk ?? ''
            )
        );

    if ($nomorSk === '') {

        $nomorSk = '-';
    }


    /* =========================================================
       TANGGAL MULAI BERLAKU
    ========================================================= */

    $tanggalMulaiBerlaku =
        $formatTanggal(
            $kgb->kgb_mulai_berlaku
        );


    /* =========================================================
       MASA KERJA GOLONGAN
    ========================================================= */

    $masaKerjaTahun =
        (int) (
            $kgb->kgb_masa_kerja_tahun ?? 0
        );

    $masaKerjaBulan =
        (int) (
            $kgb->kgb_masa_kerja_bulan ?? 0
        );


    /* =========================================================
       MASA KERJA BERDASARKAN TMT
    ========================================================= */

    $masaKerjaBerdasarkanTmtTahun =
        $masaKerjaTahun;

    $masaKerjaBerdasarkanTmtBulan =
        $masaKerjaBulan;


    if (
        $pegawai?->user_tmt
        && $kgb->kgb_mulai_berlaku
    ) {

        try {

            $tmt =
                \Carbon\Carbon::parse(
                    $pegawai->user_tmt
                );

            $efektif =
                \Carbon\Carbon::parse(
                    $kgb->kgb_mulai_berlaku
                );

            if (
                $tmt->lessThanOrEqualTo(
                    $efektif
                )
            ) {

                $diff =
                    $tmt->diff($efektif);

                $masaKerjaBerdasarkanTmtTahun =
                    $diff->y;

                $masaKerjaBerdasarkanTmtBulan =
                    $diff->m;
            }

        } catch (\Throwable $e) {
            //
        }
    }

@endphp


<div class="page">


    {{-- =========================================================
         BACKGROUND TEMPLATE
    ========================================================== --}}

    <img
        src="{{ public_path('assets/images/template-surat.png') }}"
        class="template-surat"
        alt=""
    >


    {{-- =========================================================
         TANGGAL
    ========================================================== --}}

    <div class="field f-tanggal">
        Bali, {{ $tanggalSurat }}
    </div>


    {{-- =========================================================
         NOMOR
    ========================================================== --}}

    <div class="field f-nomor">
        {{ $kgb->kgb_nomor_surat ?? '-' }}
    </div>


    {{-- =========================================================
         LAMPIRAN
    ========================================================== --}}

    <div class="field f-lampiran">
        -
    </div>


    {{-- =========================================================
         HAL
    ========================================================== --}}

    <div class="field f-hal">
        Kenaikan Gaji Berkala
    </div>


    {{-- =========================================================
         1. NAMA
    ========================================================== --}}

    <div class="field f-nama">
        {{ $nama }}
    </div>


    {{-- =========================================================
         2. TEMPAT / TANGGAL LAHIR
    ========================================================== --}}

    <div class="field f-tempat-lahir">
        {{ $tempatTanggalLahir }}
    </div>


    {{-- =========================================================
         3. NIP
    ========================================================== --}}

    <div class="field f-nip">
        {{ $pegawai?->user_nip ?? '-' }}
    </div>


    {{-- =========================================================
         4. JABATAN
    ========================================================== --}}

    <div class="field f-jabatan">
        {{ $pegawai?->jabatan?->jabatan_nama ?? '-' }}
    </div>


    {{-- =========================================================
         5. KANTOR / TEMPAT BEKERJA
    ========================================================== --}}

    <div class="field f-lokasi">
        {{ $lokasiKerja }}
    </div>


    {{-- =========================================================
         6. GAJI POKOK LAMA
    ========================================================== --}}

    <div class="field f-gaji-lama">
        Rp. {{ $gajiLama }},-
    </div>


    {{-- =========================================================
         a. OLEH PEJABAT
    ========================================================== --}}

    <div class="field f-oleh-pejabat">
        Kepala BKPSDM Provinsi Bali
    </div>


    {{-- =========================================================
         b. NOMOR DAN TANGGAL / NOMOR SK
    ========================================================== --}}

    <div class="field f-nomor-sk">
        {{ $nomorSk }}
    </div>


    {{-- =========================================================
         c. TANGGAL MULAI BERLAKU
    ========================================================== --}}

    <div class="field f-tanggal-berlaku">
        {{ $tanggalMulaiBerlaku }}
    </div>


    {{-- =========================================================
         d. MASA KERJA - TAHUN
    ========================================================== --}}

    <div class="field f-masa-tahun">
        {{ str_pad($masaKerjaTahun, 2, '0', STR_PAD_LEFT) }}
    </div>


    {{-- =========================================================
         d. MASA KERJA - BULAN
    ========================================================== --}}

    <div class="field f-masa-bulan">
        {{ str_pad($masaKerjaBulan, 2, '0', STR_PAD_LEFT) }}
    </div>


    {{-- =========================================================
         e. GOLONGAN
    ========================================================== --}}

    <div class="field f-golongan">
        {{ $golongan }}
    </div>


    {{-- =========================================================
         7. GAJI POKOK BARU
    ========================================================== --}}

    <div class="field f-gaji-baru">
        Rp. {{ $gajiBaru }},-
    </div>


    {{-- =========================================================
         TERBILANG
    ========================================================== --}}

    <div class="field f-terbilang">
        ({{ ucfirst($terbilangGaji) }} rupiah)
    </div>


    {{-- =========================================================
         8. MASA KERJA BERDASARKAN TMT - TAHUN
    ========================================================== --}}

    <div class="field f-masa-tmt-tahun">
        {{ str_pad($masaKerjaBerdasarkanTmtTahun, 2, '0', STR_PAD_LEFT) }}
    </div>


    {{-- =========================================================
         8. MASA KERJA BERDASARKAN TMT - BULAN
    ========================================================== --}}

    <div class="field f-masa-tmt-bulan">
        {{ str_pad($masaKerjaBerdasarkanTmtBulan, 2, '0', STR_PAD_LEFT) }}
    </div>


    {{-- =========================================================
         9. DALAM GOLONGAN
    ========================================================== --}}

    <div class="field f-golongan-2">
        {{ $golongan }}
    </div>


    {{-- =========================================================
         10. MULAI TANGGAL
    ========================================================== --}}

    <div class="field f-mulai-tanggal">
        {{ $tanggalMulaiBerlaku }}
    </div>


    {{-- =========================================================
         11. BERKEDUDUKAN SEBAGAI
    ========================================================== --}}

    <div class="field f-kedudukan">
        PPPK Provinsi Bali
    </div>


    {{-- =========================================================
         PARAGRAF PENUTUP
    ========================================================== --}}

    <div class="f-paragraf">

        Diharapkan agar sesuai dengan Peraturan Pemerintah No.98 Tahun 2020,
        sebagaimana telah diubah terakhir dengan Peraturan Pemerintah No. 11 Tahun 2024,
        kepada Pegawai tersebut dapat dibayarkan penghasilannya berdasarkan gaji pokok
        yang baru.

    </div>


    {{-- =========================================================
         NAMA PEJABAT
    ========================================================== --}}

    <div class="field f-ttd-nama">
        {{ $pejabatNama }}
    </div>


    {{-- =========================================================
         JABATAN PEJABAT DINAMIS
    ========================================================== --}}

    <div class="field f-ttd-jabatan">
        {{ $pejabatJabatan }}
    </div>


    {{-- =========================================================
         GOLONGAN PEJABAT DINAMIS
    ========================================================== --}}

    <div class="field f-ttd-golongan">
        {{ $pejabatGolongan }}
    </div>


    {{-- =========================================================
         NIP PEJABAT
    ========================================================== --}}

    <div class="field f-ttd-nip">
        NIP. {{ $pejabatNip }}
    </div>


</div>

</body>

</html>