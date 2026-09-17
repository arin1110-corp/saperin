<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        KGB - {{ $batch->kgb_batch_nama }}
    </title>

    <style>
        @page {
            size: A4 portrait;
            margin: 2.2cm 2.2cm 2cm 2.5cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.5;
        }

        .page {
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .kop {
            text-align: center;
            margin-bottom: 18px;
        }

        .kop-title {
            font-size: 14pt;
            font-weight: bold;
        }

        .kop-subtitle {
            font-size: 11pt;
            font-weight: bold;
        }

        .kop-address {
            font-size: 9pt;
        }

        .line {
            border-bottom: 2px solid #000;
            margin-top: 8px;
        }

        .line-thin {
            border-bottom: 1px solid #000;
            margin-top: 2px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin-top: 25px;
            margin-bottom: 20px;
        }

        .nomor {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            text-align: justify;
        }

        .paragraph {
            margin-bottom: 12px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 16px 0;
        }

        table.data td {
            padding: 3px 4px;
            vertical-align: top;
        }

        .label {
            width: 32%;
        }

        .separator {
            width: 3%;
        }

        .value {
            width: 65%;
        }

        table.salary {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        table.salary th,
        table.salary td {
            border: 1px solid #000;
            padding: 7px;
        }

        table.salary th {
            text-align: center;
            font-weight: bold;
        }

        .signature {
            width: 100%;
            margin-top: 35px;
        }

        .signature-right {
            width: 42%;
            text-align: left;
        }

        .signature-space {
            height: 70px;
        }
    </style>

</head>


<body>

    @foreach ($batch->kgb as $kgb)
        @php

            $pegawai = $kgb->user;

            $golongan = $kgb->golongan->golongan_nama ?? ($pegawai?->golongan?->golongan_nama ?? '-');

            $masaKerjaTahun = $kgb->kgb_masa_kerja_tahun ?? 0;
            $masaKerjaBulan = $kgb->kgb_masa_kerja_bulan ?? 0;

            /*
             * Hitung ulang dari TMT ke mulai berlaku
             * sebagai pengaman untuk data lama.
             */

            if ($pegawai?->user_tmt && $kgb->kgb_mulai_berlaku) {
                try {
                    $tmt = \Carbon\Carbon::parse($pegawai->user_tmt);

                    $berlaku = \Carbon\Carbon::parse($kgb->kgb_mulai_berlaku);

                    if ($tmt->lessThanOrEqualTo($berlaku)) {
                        $masaKerja = $tmt->diff($berlaku);

                        $masaKerjaTahun = $masaKerja->y;
                        $masaKerjaBulan = $masaKerja->m;
                    }
                } catch (\Throwable $e) {
                    // gunakan data database
                }
            }

        @endphp


        <div class="page">


            {{-- ================================================= --}}
            {{-- KOP --}}
            {{-- ================================================= --}}

            <div class="kop">

                <div class="kop-title">
                    PEMERINTAH PROVINSI BALI
                </div>

                <div class="kop-title">
                    DINAS KEBUDAYAAN
                </div>

                <div class="kop-address">
                    Provinsi Bali
                </div>

                <div class="line"></div>

                <div class="line-thin"></div>

            </div>


            {{-- ================================================= --}}
            {{-- JUDUL --}}
            {{-- ================================================= --}}

            <div class="judul">

                SURAT KENAIKAN GAJI BERKALA

            </div>


            <div class="nomor">

                Nomor:

                <strong>
                    {{ $kgb->kgb_nomor_surat }}
                </strong>

            </div>


            {{-- ================================================= --}}
            {{-- ISI --}}
            {{-- ================================================= --}}

            <div class="content">

                <div class="paragraph">

                    Berdasarkan ketentuan peraturan perundang-undangan
                    mengenai kenaikan gaji berkala, dengan ini diberikan
                    kenaikan gaji berkala kepada:

                </div>


                <table class="data">

                    <tr>

                        <td class="label">
                            Nama
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">

                            <strong>
                                {{ $pegawai?->user_nama ?? '-' }}
                            </strong>

                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            NIP
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">
                            {{ $pegawai?->user_nip ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            Pangkat / Golongan
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">
                            {{ $golongan }}
                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            Jabatan
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">
                            {{ $pegawai?->jabatan?->jabatan_nama ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            Unit Kerja
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">
                            {{ $pegawai?->bidang?->bidang_nama ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            TMT
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">

                            @if ($pegawai?->user_tmt)
                                {{ \Carbon\Carbon::parse($pegawai->user_tmt)->translatedFormat('d F Y') }}
                            @else
                                -
                            @endif

                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            Masa Kerja
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">

                            {{ $masaKerjaTahun }}
                            Tahun
                            {{ $masaKerjaBulan }}
                            Bulan

                        </td>

                    </tr>

                </table>


                <div class="paragraph">

                    Dengan demikian gaji pokok pegawai yang bersangkutan
                    menjadi:

                </div>


                {{-- ================================================= --}}
                {{-- GAJI --}}
                {{-- ================================================= --}}

                <table class="salary">

                    <thead>

                        <tr>

                            <th width="50%">
                                Keterangan
                            </th>

                            <th width="50%">
                                Jumlah
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td>
                                Gaji Pokok Lama
                            </td>

                            <td>

                                Rp
                                {{ number_format($kgb->kgb_gaji_lama ?? 0, 0, ',', '.') }}

                            </td>

                        </tr>


                        <tr>

                            <td>
                                Gaji Pokok Baru
                            </td>

                            <td>

                                <strong>

                                    Rp
                                    {{ number_format($kgb->kgb_gaji_baru ?? 0, 0, ',', '.') }}

                                </strong>

                            </td>

                        </tr>

                    </tbody>

                </table>


                {{-- ================================================= --}}
                {{-- BERLAKU --}}
                {{-- ================================================= --}}

                <div class="paragraph">

                    Kenaikan gaji berkala tersebut berlaku mulai tanggal

                    <strong>

                        @if ($kgb->kgb_mulai_berlaku)
                            {{ \Carbon\Carbon::parse($kgb->kgb_mulai_berlaku)->translatedFormat('d F Y') }}
                        @else
                            -
                        @endif

                    </strong>

                    dengan nomor Surat Keputusan:

                    <strong>
                        {{ $kgb->kgb_nomor_sk ?? '-' }}
                    </strong>.

                </div>


                <div class="paragraph">

                    Demikian surat kenaikan gaji berkala ini dibuat
                    untuk dapat dipergunakan sebagaimana mestinya.

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- TANDA TANGAN --}}
            {{-- ================================================= --}}

            <table class="signature">

                <tr>

                    <td width="58%">
                        &nbsp;
                    </td>

                    <td class="signature-right">

                        Bali,

                        @if ($kgb->kgb_tanggal_surat)
                            {{ \Carbon\Carbon::parse($kgb->kgb_tanggal_surat)->translatedFormat('d F Y') }}
                        @else
                            -
                        @endif

                        <br><br>

                        Pejabat Penandatangan,

                        <div class="signature-space">
                            &nbsp;
                        </div>

                        <strong>

                            {{ $kgb->pejabat?->user_nama ?? '-' }}

                        </strong>

                        @if ($kgb->pejabat?->user_nip)
                            <br>

                            NIP.
                            {{ $kgb->pejabat->user_nip }}
                        @endif

                    </td>

                </tr>

            </table>

        </div>
    @endforeach

</body>

</html>
