<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Kenaikan Gaji Berkala</title>
</head>

<body
    style="
        margin: 0;
        padding: 0;
        background-color: #f3f6fa;
        font-family: Arial, Helvetica, sans-serif;
        color: #2d3748;
    ">

    <table width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color: #f3f6fa; padding: 30px 15px;">

        <tr>
            <td align="center">

                <!-- CONTAINER -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="
                        max-width: 680px;
                        background-color: #ffffff;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
                    ">

                    <!-- HEADER -->
                    <tr>
                        <td
                            style="
                                background: linear-gradient(135deg, #0f172a, #1e3a8a);
                                padding: 28px 35px;
                                color: #ffffff;
                            ">

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>

                                    <td valign="middle">

                                        <div
                                            style="
                                                font-size: 12px;
                                                letter-spacing: 1px;
                                                text-transform: uppercase;
                                                opacity: 0.85;
                                                margin-bottom: 7px;
                                            ">
                                            SAMPERIN
                                        </div>

                                        <div
                                            style="
                                                font-size: 24px;
                                                font-weight: bold;
                                                line-height: 1.3;
                                            ">
                                            Kenaikan Gaji Berkala
                                        </div>

                                        <div
                                            style="
                                                font-size: 13px;
                                                margin-top: 7px;
                                                opacity: 0.85;
                                            ">
                                            Dinas Kebudayaan Provinsi Bali
                                        </div>

                                    </td>

                                    <td width="70" align="right" valign="middle">

                                        <div
                                            style="
                                                width: 54px;
                                                height: 54px;
                                                background-color: rgba(255,255,255,0.15);
                                                border-radius: 50%;
                                                text-align: center;
                                                line-height: 54px;
                                                font-size: 26px;
                                            ">
                                            $
                                        </div>

                                    </td>

                                </tr>
                            </table>

                        </td>
                    </tr>


                    <!-- CONTENT -->
                    <tr>
                        <td style="padding: 35px;">

                            <!-- GREETING -->
                            <div
                                style="
                                    font-size: 15px;
                                    line-height: 1.7;
                                    margin-bottom: 20px;
                                ">

                                <p style="margin: 0 0 12px 0;">
                                    Yth. Bapak/Ibu
                                    <strong style="color: #1e3a8a;">
                                        {{ $user->user_nama ?? '-' }}
                                    </strong>
                                </p>

                                <p style="margin: 0;">
                                    Dengan hormat, kami menyampaikan pemberitahuan
                                    terkait <strong>Kenaikan Gaji Berkala (KGB)</strong>
                                    dengan rincian data sebagai berikut:
                                </p>

                            </div>


                            <!-- DATA CARD -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="
                                    border: 1px solid #e2e8f0;
                                    border-radius: 8px;
                                    overflow: hidden;
                                    margin-bottom: 25px;
                                ">

                                <!-- TITLE -->
                                <tr>
                                    <td colspan="2"
                                        style="
                                            background-color: #f8fafc;
                                            padding: 14px 18px;
                                            border-bottom: 1px solid #e2e8f0;
                                            font-weight: bold;
                                            color: #1e3a8a;
                                            font-size: 14px;
                                        ">
                                        Informasi KGB
                                    </td>
                                </tr>


                                <!-- NAMA -->
                                <tr>
                                    <td width="40%"
                                        style="
                                            padding: 11px 18px;
                                            color: #64748b;
                                            font-size: 13px;
                                            border-bottom: 1px solid #f1f5f9;
                                        ">
                                        Nama Pegawai
                                    </td>

                                    <td
                                        style="
                                            padding: 11px 18px;
                                            font-size: 13px;
                                            font-weight: bold;
                                            border-bottom: 1px solid #f1f5f9;
                                        ">
                                        {{ $user->user_nama ?? '-' }}
                                    </td>
                                </tr>


                                <!-- NIP -->
                                <tr>
                                    <td
                                        style="
                                            padding: 11px 18px;
                                            color: #64748b;
                                            font-size: 13px;
                                            border-bottom: 1px solid #f1f5f9;
                                        ">
                                        NIP
                                    </td>

                                    <td
                                        style="
                                            padding: 11px 18px;
                                            font-size: 13px;
                                            border-bottom: 1px solid #f1f5f9;
                                        ">
                                        {{ $user->user_nip ?? '-' }}
                                    </td>
                                </tr>


                                <!-- GOLONGAN -->
                                <tr>
                                    <td
                                        style="
                                            padding: 11px 18px;
                                            color: #64748b;
                                            font-size: 13px;
                                            border-bottom: 1px solid #f1f5f9;
                                        ">
                                        Golongan
                                    </td>

                                    <td
                                        style="
                                            padding: 11px 18px;
                                            font-size: 13px;
                                            border-bottom: 1px solid #f1f5f9;
                                        ">
                                        {{ $kgb->golongan?->golongan_nama ?? '-' }}
                                    </td>
                                </tr>


                                <!-- NOMOR SURAT -->
                                <tr>
                                    <td
                                        style="
                                            padding: 11px 18px;
                                            color: #64748b;
                                            font-size: 13px;
                                            border-bottom: 1px solid #f1f5f9;
                                        ">
                                        Nomor Surat KGB
                                    </td>

                                    <td
                                        style="
                                            padding: 11px 18px;
                                            font-size: 13px;
                                            border-bottom: 1px solid #f1f5f9;
                                        ">
                                        {{ $kgb->kgb_nomor_surat ?? '-' }}
                                    </td>
                                </tr>


                                <!-- TANGGAL SURAT -->
                                <tr>
                                    <td
                                        style="
                                            padding: 11px 18px;
                                            color: #64748b;
                                            font-size: 13px;
                                            border-bottom: 1px solid #f1f5f9;
                                        ">
                                        Tanggal Surat
                                    </td>

                                    <td
                                        style="
                                            padding: 11px 18px;
                                            font-size: 13px;
                                            border-bottom: 1px solid #f1f5f9;
                                        ">
                                        {{ $kgb->kgb_tanggal_surat ?? '-' }}
                                    </td>
                                </tr>


                                <!-- MULAI BERLAKU -->
                                <tr>
                                    <td
                                        style="
                                            padding: 11px 18px;
                                            color: #64748b;
                                            font-size: 13px;
                                            border-bottom: 1px solid #f1f5f9;
                                        ">
                                        Mulai Berlaku
                                    </td>

                                    <td
                                        style="
                                            padding: 11px 18px;
                                            font-size: 13px;
                                            border-bottom: 1px solid #f1f5f9;
                                        ">
                                        {{ $kgb->kgb_mulai_berlaku ?? '-' }}
                                    </td>
                                </tr>


                                <!-- NOMOR SK -->
                                <tr>
                                    <td
                                        style="
                                            padding: 11px 18px;
                                            color: #64748b;
                                            font-size: 13px;
                                        ">
                                        Nomor SK
                                    </td>

                                    <td style="padding: 11px 18px; font-size: 13px;">

                                        @if (empty($kgb->kgb_nomor_sk))
                                            <span
                                                style="
                                                    display: inline-block;
                                                    background-color: #fff7ed;
                                                    color: #c2410c;
                                                    border: 1px solid #fed7aa;
                                                    padding: 6px 10px;
                                                    border-radius: 5px;
                                                    font-weight: bold;
                                                ">
                                                ⚠ Belum diisi
                                            </span>
                                        @else
                                            <strong>
                                                {{ $kgb->kgb_nomor_sk }}
                                            </strong>
                                        @endif

                                    </td>
                                </tr>

                            </table>


                            <!-- ACTION / WARNING -->
                            @if (empty($kgb->kgb_nomor_sk))
                                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                    style="
                                        background-color: #fff7ed;
                                        border: 1px solid #fed7aa;
                                        border-left: 4px solid #f97316;
                                        border-radius: 7px;
                                        margin-bottom: 25px;
                                    ">

                                    <tr>
                                        <td style="padding: 15px 17px;">

                                            <div
                                                style="
                                                    color: #9a3412;
                                                    font-weight: bold;
                                                    font-size: 14px;
                                                    margin-bottom: 5px;
                                                ">
                                                ⚠ Nomor SK Belum Diisi
                                            </div>

                                            <div
                                                style="
                                                    color: #7c2d12;
                                                    font-size: 13px;
                                                    line-height: 1.6;
                                                ">
                                                Nomor SK Anda belum diisi pada sistem
                                                SAMPERIN. Mohon segera melengkapi
                                                Nomor SK sesuai dengan dokumen yang
                                                dimiliki.
                                            </div>

                                        </td>
                                    </tr>

                                </table>
                            @endif


                            <!-- CATATAN -->
                            @if (!empty($catatan))

                                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                    style="
                                        background-color: #fff7ed;
                                        border: 1px solid #fed7aa;
                                        border-left: 4px solid #f97316;
                                        border-radius: 7px;
                                        margin-bottom: 25px;
                                    ">

                                    <tr>
                                        <td style="padding: 15px 17px;">

                                            <div
                                                style="
                                                    color: #9a3412;
                                                    font-weight: bold;
                                                    font-size: 14px;
                                                    margin-bottom: 7px;
                                                ">
                                                ⚠ Catatan
                                            </div>

                                            <div
                                                style="
                                                    color: #7c2d12;
                                                    font-size: 13px;
                                                    line-height: 1.6;
                                                    font-weight: 600;
                                                ">
                                                {!! nl2br(e($catatan)) !!}
                                            </div>

                                        </td>
                                    </tr>

                                </table>

                            @endif


                            <!-- CLOSING -->
                            <div
                                style="
                                    font-size: 13px;
                                    line-height: 1.7;
                                    color: #475569;
                                ">

                                <p style="margin: 0 0 12px 0;">
                                    Demikian pemberitahuan ini disampaikan.
                                    Mohon untuk segera menindaklanjuti sesuai
                                    dengan ketentuan yang berlaku.
                                </p>

                                <p style="margin: 0;">
                                    Terima kasih atas perhatian dan kerja samanya.
                                </p>

                            </div>

                        </td>
                    </tr>


                    <!-- FOOTER -->
                    <tr>
                        <td
                            style="
                                background-color: #f8fafc;
                                border-top: 1px solid #e2e8f0;
                                padding: 22px 35px;
                            ">

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>

                                    <td>

                                        <div
                                            style="
                                                font-size: 15px;
                                                font-weight: bold;
                                                color: #1e3a8a;
                                                margin-bottom: 4px;
                                            ">
                                            SAMPERIN
                                        </div>

                                        <div
                                            style="
                                                font-size: 12px;
                                                color: #64748b;
                                            ">
                                            Sistem Administrasi Manajemen Pegawai
                                            Lingkup Internal
                                        </div>

                                        <div
                                            style="
                                                font-size: 12px;
                                                color: #64748b;
                                                margin-top: 3px;
                                            ">
                                            Dinas Kebudayaan Provinsi Bali
                                        </div>

                                    </td>

                                    <td align="right" valign="middle">

                                        <div
                                            style="
                                                font-size: 11px;
                                                color: #94a3b8;
                                            ">
                                            Email Otomatis
                                        </div>

                                        <div
                                            style="
                                                font-size: 11px;
                                                color: #94a3b8;
                                                margin-top: 3px;
                                            ">
                                            Mohon tidak membalas email ini
                                        </div>

                                    </td>

                                </tr>
                            </table>

                        </td>
                    </tr>

                </table>

                <!-- COPYRIGHT -->
                <div
                    style="
                        max-width: 680px;
                        padding-top: 15px;
                        font-size: 11px;
                        color: #94a3b8;
                        text-align: center;
                    ">
                    SAMPERIN &copy; {{ date('Y') }} · Dinas Kebudayaan Provinsi Bali
                </div>

            </td>
        </tr>

    </table>

</body>

</html>
