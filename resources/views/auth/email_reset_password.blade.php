<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password SAMPERIN</title>

</head>

<body
    style="
        margin:0;
        padding:0;
        background:#f3f1ef;
        font-family:Arial, Helvetica, sans-serif;
        color:#182238;
    ">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding:40px 15px;">

        <tr>

            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="
                    max-width:600px;
                    background:#ffffff;
                    border-radius:18px;
                    overflow:hidden;
                ">

                    {{-- HEADER --}}

                    <tr>

                        <td
                            style="
                            background:#a84e15;
                            padding:30px;
                            text-align:center;
                        ">

                            <img src="{{ asset('assets/images/logo-samperin.png') }}" alt="SAMPERIN" width="80"
                                height="80"
                                style="
                                display:block;
                                margin:0 auto 15px;
                                object-fit:contain;
                            ">

                            <div
                                style="
                                font-size:28px;
                                font-weight:900;
                                letter-spacing:-1px;
                            ">

                                <span style="color:#ffffff;">
                                    SAMPER
                                </span>

                                <span style="color:#f4bd5d;">
                                    IN
                                </span>

                            </div>

                        </td>

                    </tr>


                    {{-- CONTENT --}}

                    <tr>

                        <td style="
                            padding:40px 35px;
                        ">

                            <h1
                                style="
                                margin:0;
                                font-size:24px;
                                color:#182238;
                            ">
                                Reset Password
                            </h1>


                            <p
                                style="
                                margin:20px 0 0;
                                font-size:15px;
                                line-height:1.7;
                                color:#5f6368;
                            ">
                                Halo
                                <strong>
                                    {{ $user->user_nama }}
                                </strong>,
                            </p>


                            <p
                                style="
                                margin:12px 0 0;
                                font-size:15px;
                                line-height:1.7;
                                color:#5f6368;
                            ">
                                Kami menerima permintaan untuk
                                melakukan reset password akun
                                SAMPERIN Anda.
                            </p>


                            <p
                                style="
                                margin:12px 0 0;
                                font-size:15px;
                                line-height:1.7;
                                color:#5f6368;
                            ">
                                Klik tombol di bawah untuk membuat
                                password baru.
                            </p>


                            {{-- BUTTON --}}

                            <table cellpadding="0" cellspacing="0" border="0" style="margin:30px auto;">

                                <tr>

                                    <td align="center"
                                        style="
                                        background:#a84e15;
                                        border-radius:10px;
                                    ">

                                        <a href="{{ $link }}"
                                            style="
                                            display:inline-block;
                                            padding:15px 28px;
                                            color:#ffffff;
                                            text-decoration:none;
                                            font-size:15px;
                                            font-weight:bold;
                                        ">
                                            Reset Password
                                        </a>

                                    </td>

                                </tr>

                            </table>


                            {{-- EXPIRED --}}

                            <div
                                style="
                                background:#fff8eb;
                                border:1px solid #f3dfb0;
                                border-radius:10px;
                                padding:15px;
                                font-size:13px;
                                line-height:1.6;
                                color:#805b16;
                            ">

                                Link reset password ini berlaku selama
                                <strong>4 jam</strong> sejak email ini
                                dikirim.

                            </div>


                            <p
                                style="
                                margin:25px 0 0;
                                font-size:13px;
                                line-height:1.6;
                                color:#777777;
                            ">
                                Jika Anda tidak meminta reset password,
                                abaikan email ini. Jangan berikan link
                                reset password kepada orang lain.
                            </p>

                        </td>

                    </tr>


                    {{-- FOOTER --}}

                    <tr>

                        <td
                            style="
                            border-top:1px solid #eeeeee;
                            padding:20px;
                            text-align:center;
                            font-size:12px;
                            color:#777777;
                        ">

                            © {{ date('Y') }}
                            SAMPERIN -
                            Dinas Kebudayaan Provinsi Bali

                        </td>

                    </tr>

                </table>

            </td>

        </tr>

    </table>

</body>

</html>
