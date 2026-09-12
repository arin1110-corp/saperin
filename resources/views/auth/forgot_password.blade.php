@extends('auth.layouts.app')

@section('title', 'Lupa Password - SAMPERIN')

@section('content')

    @php

        $leftTitle = 'Pemulihan<br>Akun SAMPERIN';

        $leftDescription = 'Gunakan NIP, NIK, atau email yang terdaftar untuk memulihkan akses ke akun SAMPERIN.';

        $leftFeatures = [
            [
                'title' => 'Identitas Pegawai',
                'description' => 'Gunakan NIP, NIK, atau email yang telah terdaftar',
                'icon' => 'user',
            ],

            [
                'title' => 'Verifikasi',
                'description' => 'Sistem akan memeriksa data pegawai secara otomatis',
                'icon' => 'document',
            ],

            [
                'title' => 'Link Aman',
                'description' => 'Link reset dikirim ke email yang tersimpan di sistem',
                'icon' => 'database',
            ],
        ];

    @endphp


    <div
        class="
        login-wrapper
        mx-auto
        flex
        w-full
        max-w-[1536px]
        flex-col
        bg-white
        lg:my-3
        lg:flex-row
        lg:rounded-[20px]
        lg:shadow-[0_15px_45px_rgba(45,30,20,.15)]
    ">

        {{-- LEFT --}}

        @include('auth.partials.left-panel')


        {{-- RIGHT --}}

        <section
            class="
            flex
            w-full
            flex-col
            bg-white
            lg:w-[48%]
        ">

            <div
                class="
                flex
                flex-1
                flex-col
                justify-center
                px-6
                py-10
                sm:px-10
                md:px-14
                lg:px-14
                xl:px-16
            ">

                {{-- LOGO --}}

                <div class="mb-5 flex justify-center">

                    <div
                        class="
                        flex
                        h-[110px]
                        w-[110px]
                        items-center
                        justify-center
                        overflow-hidden
                        rounded-full
                        bg-[#faf8f5]
                        p-3
                    ">

                        <img src="{{ asset('assets/images/logo-samperin.png') }}" alt="Logo SAMPERIN"
                            class="h-full w-full object-contain">

                    </div>

                </div>


                {{-- TITLE --}}

                <div class="text-center">

                    <h2
                        class="
                        text-[30px]
                        font-black
                        tracking-tight
                        sm:text-[36px]
                    ">

                        <span class="text-[#182238]">
                            SAMPER
                        </span>

                        <span class="text-[#a84e15]">
                            IN
                        </span>

                    </h2>

                    <p
                        class="
                        mt-2
                        text-[15px]
                        leading-6
                        text-[#766b64]
                        sm:text-[17px]
                    ">
                        Lupa Password
                    </p>

                </div>


                {{-- ALERT --}}

                @if (session('error'))
                    <div
                        class="
                        mt-6
                        rounded-xl
                        border
                        border-red-200
                        bg-red-50
                        px-4
                        py-3
                        text-sm
                        font-medium
                        text-red-700
                    ">
                        {{ session('error') }}
                    </div>
                @endif


                {{-- =================================================
                INPUT IDENTIFIER
            ================================================== --}}

                @if (!isset($showConfirmation) || !$showConfirmation)
                    <form method="POST" action="{{ route('password.forgot.check') }}" class="mt-8">

                        @csrf

                        <label for="identifier"
                            class="
                            mb-2
                            block
                            text-[14px]
                            font-bold
                            text-[#182238]
                        ">
                            NIP, NIK, atau Email
                        </label>


                        <input id="identifier" type="text" name="identifier" value="{{ old('identifier') }}" required
                            autofocus placeholder="Masukkan NIP, NIK, atau email"
                            class="
                            login-input
                            h-[58px]
                            w-full
                            rounded-xl
                            border
                            border-slate-200
                            bg-slate-50
                            px-4
                            text-[15px]
                            text-slate-800
                            placeholder:text-slate-400
                        ">


                        @error('identifier')
                            <div
                                class="
                                mt-2
                                text-xs
                                font-semibold
                                text-red-600
                            ">
                                {{ $message }}
                            </div>
                        @enderror


                        <button type="submit"
                            class="
                            login-button
                            mt-6
                            flex
                            h-[60px]
                            w-full
                            items-center
                            justify-center
                            gap-4
                            rounded-xl
                            bg-[#a84e15]
                            text-[17px]
                            font-bold
                            text-white
                            shadow-[0_7px_18px_rgba(168,78,21,.16)]
                        ">
                            Lanjutkan

                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-width="1.8" d="M5 12h13" />

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m13 6 6 6-6 6" />
                            </svg>

                        </button>

                    </form>


                    <div class="mt-5 text-center">

                        <a href="{{ route('samperin.login') }}"
                            class="
                            text-sm
                            font-semibold
                            text-slate-500
                            hover:text-[#a84e15]
                        ">
                            ← Kembali ke Login
                        </a>

                    </div>
                @else
                    {{-- =================================================
                    CONFIRMATION
                ================================================== --}}

                    <div class="mt-8">

                        <div
                            class="
        rounded-2xl
        border
        border-[#eadfd6]
        bg-[#faf8f5]
        p-6
    ">

                            <h3 class="
            text-[18px]
            font-black
            text-[#182238]
        ">
                                Konfirmasi Reset Password
                            </h3>

                            <p
                                class="
            mt-2
            text-sm
            leading-6
            text-slate-500
        ">
                                Data pegawai ditemukan.
                                Link reset password akan dikirim
                                ke email yang terdaftar pada akun ini.
                            </p>

                            <div
                                class="
            mt-5
            rounded-xl
            border
            border-slate-200
            bg-white
            px-4
            py-4
        ">

                                {{-- NAMA PEGAWAI --}}
                                <div
                                    class="
                text-xs
                font-semibold
                uppercase
                tracking-wide
                text-slate-400
            ">
                                    Nama Pegawai
                                </div>

                                <div
                                    class="
                mt-1
                text-[15px]
                font-bold
                text-[#182238]
            ">
                                    {{ $resetUser->user_nama }}
                                </div>


                                {{-- EMAIL --}}
                                <div
                                    class="
                mt-4
                text-xs
                font-semibold
                uppercase
                tracking-wide
                text-slate-400
            ">
                                    Email Tujuan
                                </div>

                                <div class="mt-1 flex items-center gap-2">

                                    <span id="emailDisplay"
                                        class="
                    min-w-0
                    break-all
                    text-[15px]
                    font-bold
                    text-[#a84e15]
                ">
                                        {{ $maskedEmail }}
                                    </span>

                                    <button type="button" id="toggleEmailButton" onclick="toggleEmailVisibility()"
                                        class="
                    flex
                    h-8
                    w-8
                    shrink-0
                    items-center
                    justify-center
                    rounded-lg
                    text-slate-400
                    transition
                    hover:bg-slate-100
                    hover:text-[#a84e15]
                "
                                        title="Tampilkan email" aria-label="Tampilkan email">

                                        {{-- ICON MATA --}}
                                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>

                                    </button>

                                </div>

                            </div>


                            {{-- INFORMASI --}}
                            <div
                                class="
            mt-4
            rounded-xl
            bg-amber-50
            px-4
            py-3
            text-xs
            leading-5
            text-amber-700
        ">
                                Link reset password berlaku selama
                                <strong>4 jam</strong>.
                                Setelah link dikirim, permintaan reset
                                berikutnya hanya dapat dilakukan setelah
                                periode 4 jam tersebut berakhir.
                            </div>


                            {{-- BUTTON --}}
                            <form method="POST" action="{{ route('password.forgot.send') }}" class="mt-5">

                                @csrf

                                <div class="grid grid-cols-2 gap-3">

                                    <a href="{{ route('password.forgot') }}"
                                        class="
                    flex
                    h-[54px]
                    items-center
                    justify-center
                    rounded-xl
                    border
                    border-slate-200
                    bg-white
                    text-sm
                    font-bold
                    text-slate-600
                    hover:bg-slate-50
                ">
                                        Batal
                                    </a>

                                    <button type="submit"
                                        class="
                    login-button
                    flex
                    h-[54px]
                    items-center
                    justify-center
                    rounded-xl
                    bg-[#a84e15]
                    text-sm
                    font-bold
                    text-white
                ">
                                        OKE, Kirim Link
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>
                @endif


                {{-- SECURITY --}}

                @include('auth.partials.security')

            </div>


            {{-- FOOTER --}}

            @include('auth.partials.footer')

        </section>

    </div>

@endsection



<script>
    let emailVisible = false;

    function toggleEmailVisibility() {

        const emailDisplay =
            document.getElementById('emailDisplay');

        const eyeIcon =
            document.getElementById('eyeIcon');

        const button =
            document.getElementById('toggleEmailButton');

        emailVisible = !emailVisible;

        if (emailVisible) {

            emailDisplay.textContent =
                @json($email);

            button.title = 'Sembunyikan email';
            button.setAttribute(
                'aria-label',
                'Sembunyikan email'
            );

            eyeIcon.innerHTML = `
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3.98 8.223A10.477 10.477 0 001.458 12C2.732 16.057 6.523 19 11 19c1.61 0 3.14-.39 4.47-1.08"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6.228 6.228A10.451 10.451 0 0111 5c4.477 0 8.268 2.943 9.542 7a10.51 10.51 0 01-4.132 5.411"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6.228 6.228L3 3"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3 3l18 18"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9.88 9.88a3 3 0 104.24 4.24"
                />
            `;

        } else {

            emailDisplay.textContent =
                @json($maskedEmail);

            button.title = 'Tampilkan email';
            button.setAttribute(
                'aria-label',
                'Tampilkan email'
            );

            eyeIcon.innerHTML = `
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
            `;
        }
    }
</script>
