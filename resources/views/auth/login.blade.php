<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Masuk - SAMPERIN</title>


    <script src="https://cdn.tailwindcss.com"></script>


    <script>
        tailwind.config = {

            theme: {

                extend: {

                    fontFamily: {

                        sans: [
                            'Inter',
                            'ui-sans-serif',
                            'system-ui',
                            'sans-serif'
                        ]

                    }

                }

            }

        };
    </script>


    <style>
        * {
            box-sizing: border-box;
        }


        html,
        body {

            margin: 0;

            min-height: 100%;

        }


        body {

            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            background: #f3f1ef;

        }


        /*
        |--------------------------------------------------------------------------
        | BUILDING
        |--------------------------------------------------------------------------
        */

        .building-panel {

            position: relative;

            background-image:

                linear-gradient(180deg,

                    rgba(5, 3, 2, .84) 0%,

                    rgba(17, 9, 4, .82) 25%,

                    rgba(28, 14, 6, .84) 50%,

                    rgba(12, 6, 3, .94) 75%,

                    rgba(3, 2, 1, .98) 100%),

                linear-gradient(90deg,

                    rgba(0, 0, 0, .35) 0%,

                    rgba(0, 0, 0, .08) 55%,

                    rgba(0, 0, 0, .45) 100%),

                url('{{ asset('assets/images/gedung-disbud.jpg') }}');

            background-size: cover;

            background-position: center;

            background-repeat: no-repeat;

        }


        .building-panel::after {

            content: '';

            position: absolute;

            inset: 0;

            pointer-events: none;

            background:

                linear-gradient(180deg,
                    transparent 0%,
                    rgba(0, 0, 0, .12) 50%,
                    rgba(0, 0, 0, .35) 100%);

        }


        /*
        |--------------------------------------------------------------------------
        | PATTERN
        |--------------------------------------------------------------------------
        */

        .bali-pattern {

            position: absolute;

            top: 0;

            right: 0;

            width: 300px;

            height: 470px;

            opacity: .10;

            pointer-events: none;

            background-image:

                radial-gradient(circle,
                    rgba(244, 191, 98, .9) 0,
                    rgba(244, 191, 98, .9) 1px,
                    transparent 1.5px);

            background-size: 14px 14px;

            mask-image:
                linear-gradient(to bottom,
                    black,
                    transparent);

            -webkit-mask-image:
                linear-gradient(to bottom,
                    black,
                    transparent);

        }


        /*
        |--------------------------------------------------------------------------
        | CONTENT
        |--------------------------------------------------------------------------
        */

        .left-content {

            position: relative;

            z-index: 5;

        }


        /*
        |--------------------------------------------------------------------------
        | BRAND
        |--------------------------------------------------------------------------
        */

        .samperin-brand {

            font-size:
                clamp(40px,
                    4vw,
                    58px);

            font-weight: 900;

            line-height: .95;

            letter-spacing: -.075em;

        }


        .samperin-normal {

            color: #fff;

        }


        .samperin-in {

            color: #b85b17;

        }


        /*
        |--------------------------------------------------------------------------
        | GOLD LINE
        |--------------------------------------------------------------------------
        */

        .gold-line {

            width: 64px;

            height: 3px;

            margin-top: 24px;

            border-radius: 999px;

            background: #f4bd5d;

        }


        /*
        |--------------------------------------------------------------------------
        | FEATURE
        |--------------------------------------------------------------------------
        */

        .feature-box {

            background:
                rgba(8,
                    5,
                    3,
                    .72);

            border:
                1px solid rgba(255,
                    255,
                    255,
                    .24);

            backdrop-filter:
                blur(6px);

            -webkit-backdrop-filter:
                blur(6px);

        }


        .feature-item {

            border-bottom:
                1px solid rgba(255,
                    255,
                    255,
                    .17);

        }


        .feature-item:last-child {

            border-bottom: none;

        }


        /*
        |--------------------------------------------------------------------------
        | INPUT
        |--------------------------------------------------------------------------
        */

        .login-input {

            transition:
                border-color .2s ease,
                box-shadow .2s ease,
                background-color .2s ease;

        }


        .login-input:focus {

            outline: none;

            border-color: #a85514;

            background: #fff;

            box-shadow:
                0 0 0 4px rgba(168,
                    85,
                    20,
                    .09);

        }


        /*
        |--------------------------------------------------------------------------
        | BUTTON
        |--------------------------------------------------------------------------
        */

        .login-button {

            transition:
                background-color .2s ease,
                transform .15s ease,
                box-shadow .2s ease;

        }


        .login-button:hover {

            background: #98480d;

            box-shadow:
                0 12px 28px rgba(152,
                    72,
                    13,
                    .24);

        }


        .login-button:active {

            transform:
                translateY(1px);

        }


        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        */

        .security-box {

            border:
                1px solid #ece8e3;

            background: #faf9f7;

        }


        /*
        |--------------------------------------------------------------------------
        | DESKTOP
        |--------------------------------------------------------------------------
        */

        @media (min-width: 1024px) {
            body {
                min-height: 100vh;
                overflow-y: auto;
                overflow-x: hidden;
            }

            .login-wrapper {
                min-height: calc(100vh - 24px);
                max-height: none;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 1023px) {

            body {

                overflow-y: auto;

            }


            .login-wrapper {

                min-height: 100vh;

            }


            .building-panel {

                min-height: 620px;

            }

        }


        @media (max-width: 640px) {

            .building-panel {

                min-height: 650px;

            }


            .samperin-brand {

                font-size: 42px;

            }

        }
    </style>

</head>


<body>


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


        {{-- =========================================================
        LEFT
    ========================================================== --}}

        <section
            class="
            building-panel

            relative

            flex
            w-full

            flex-col

            overflow-hidden

            p-8

            text-white

            sm:p-10

            lg:w-[52%]
            lg:p-12

            xl:p-14
        ">

            <div class="bali-pattern"></div>


            <div
                class="
                left-content

                flex
                h-full

                flex-col
            ">


                {{-- BRAND --}}

                <div>

                    <div class="samperin-brand">

                        <span class="samperin-normal">
                            SAMPER
                        </span>

                        <span class="samperin-in">
                            IN
                        </span>

                    </div>


                    <div
                        class="
                        mt-3

                        max-w-[500px]

                        text-sm

                        font-medium

                        leading-6

                        tracking-wide

                        text-white/90

                        sm:text-[15px]
                    ">

                        Sistem Administrasi Manajemen Pegawai
                        dan Berkas Internal

                    </div>

                </div>


                <div class="gold-line"></div>


                {{-- TITLE --}}

                <div class="mt-7">

                    <h1
                        class="
                        max-w-[560px]

                        text-[30px]

                        font-black

                        leading-[1.15]

                        tracking-tight

                        text-white

                        sm:text-[36px]

                        lg:text-[40px]
                    ">

                        Dinas Kebudayaan

                        <br>

                        Provinsi Bali

                    </h1>


                    <p
                        class="
                        mt-5

                        max-w-[550px]

                        text-[15px]

                        leading-7

                        text-white/90

                        sm:text-[16px]
                    ">

                        Sistem terintegrasi untuk mengelola
                        data pegawai, administrasi, dan berkas
                        internal Dinas Kebudayaan Provinsi Bali
                        secara terstruktur dan mudah.

                    </p>

                </div>


                {{-- FEATURES --}}

                <div
                    class="
                    feature-box

                    mt-8

                    w-full

                    max-w-[430px]

                    rounded-2xl

                    px-5

                    py-2

                    sm:px-6

                    lg:mt-auto
                ">


                    {{-- FEATURE 1 --}}

                    <div
                        class="
                        feature-item

                        flex
                        items-center

                        gap-4

                        py-4
                    ">

                        <div
                            class="
                            flex

                            h-11
                            w-11

                            shrink-0

                            items-center
                            justify-center

                            text-[#ffbd50]
                        ">

                            <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <circle cx="9" cy="8" r="3" stroke-width="1.7" />

                                <circle cx="17" cy="10" r="2.5" stroke-width="1.7" />

                                <path stroke-linecap="round" stroke-width="1.7" d="
                                    M3 20
                                    c0-3.2 2.7-5 6-5
                                    s6 1.8 6 5

                                    M15 15
                                    c3 0 5 1.6 5 4
                                " />

                            </svg>

                        </div>


                        <div>

                            <div
                                class="
                                text-[16px]
                                font-bold
                                text-white
                            ">
                                Manajemen Pegawai
                            </div>


                            <div
                                class="
                                mt-1
                                text-[13px]
                                leading-5
                                text-white/70
                            ">
                                Kelola data dan informasi
                                pegawai secara terstruktur
                            </div>

                        </div>

                    </div>


                    {{-- FEATURE 2 --}}

                    <div
                        class="
                        feature-item

                        flex
                        items-center

                        gap-4

                        py-4
                    ">

                        <div
                            class="
                            flex

                            h-11
                            w-11

                            shrink-0

                            items-center
                            justify-center

                            text-[#ffbd50]
                        ">

                            <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="
                                    M4 6
                                    a2 2 0 012-2
                                    h5
                                    l2 2
                                    h5
                                    a2 2 0 012 2
                                    v10
                                    a2 2 0 01-2 2
                                    H6
                                    a2 2 0 01-2-2V6z
                                " />

                                <path stroke-linecap="round" stroke-width="1.7" d="
                                    M8 11h8
                                    M8 15h5
                                " />

                            </svg>

                        </div>


                        <div>

                            <div
                                class="
                                text-[16px]
                                font-bold
                                text-white
                            ">
                                Berkas Internal
                            </div>


                            <div
                                class="
                                mt-1
                                text-[13px]
                                leading-5
                                text-white/70
                            ">
                                Kelola dan akses berkas
                                pegawai secara terpusat
                            </div>

                        </div>

                    </div>


                    {{-- FEATURE 3 --}}

                    <div
                        class="
                        feature-item

                        flex
                        items-center

                        gap-4

                        py-4
                    ">

                        <div
                            class="
                            flex

                            h-11
                            w-11

                            shrink-0

                            items-center
                            justify-center

                            text-[#ffbd50]
                        ">

                            <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <rect x="6" y="4" width="12" height="17" rx="2" stroke-width="1.7" />

                                <path stroke-linecap="round" stroke-width="1.7" d="
                                    M9 8h6
                                    M9 12h6
                                    M9 16h4
                                " />

                            </svg>

                        </div>


                        <div>

                            <div
                                class="
                                text-[16px]
                                font-bold
                                text-white
                            ">
                                Administrasi
                            </div>


                            <div
                                class="
                                mt-1
                                text-[13px]
                                leading-5
                                text-white/70
                            ">
                                Mendukung pengelolaan
                                administrasi internal
                            </div>

                        </div>

                    </div>


                    {{-- FEATURE 4 --}}

                    <div
                        class="
                        feature-item

                        flex
                        items-center

                        gap-4

                        py-4
                    ">

                        <div
                            class="
                            flex

                            h-11
                            w-11

                            shrink-0

                            items-center
                            justify-center

                            text-[#ffbd50]
                        ">

                            <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <ellipse cx="12" cy="6" rx="7" ry="3"
                                    stroke-width="1.7" />

                                <path stroke-linecap="round" stroke-width="1.7" d="
                                    M5 6v6
                                    c0 1.7 3.1 3 7 3
                                    s7-1.3 7-3V6

                                    M5 12v6
                                    c0 1.7 3.1 3 7 3
                                    s7-1.3 7-3v-6
                                " />

                            </svg>

                        </div>


                        <div>

                            <div
                                class="
                                text-[16px]
                                font-bold
                                text-white
                            ">
                                Terstruktur
                            </div>


                            <div
                                class="
                                mt-1
                                text-[13px]
                                leading-5
                                text-white/70
                            ">
                                Data dan berkas tersimpan
                                dengan rapi dan mudah diakses
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>



        {{-- =========================================================
        RIGHT
    ========================================================== --}}

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


                {{-- LOGO PEMPROV --}}

                <div
                    class="
                    mb-5

                    flex
                    justify-center
                ">

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

                        <img src="{{ asset('assets/images/lambang-pemprov.png') }}"
                            alt="Lambang Pemerintah Provinsi Bali"
                            class="
                            h-full
                            w-full
                            object-contain
                        ">

                    </div>

                </div>


                {{-- TITLE --}}

                <div class="text-center">

                    <h2
                        class="
                        text-[30px]

                        font-black

                        tracking-tight

                        text-[#182238]

                        sm:text-[36px]
                    ">

                        <span>

                            <span class="text-[#182238]">
                                SAMPER
                            </span>

                            <span class="text-[#a84e15]">
                                IN
                            </span>

                        </span>

                    </h2>


                    <p
                        class="
                        mx-auto

                        mt-2

                        max-w-[560px]

                        text-[15px]

                        leading-6

                        text-[#766b64]

                        sm:text-[17px]
                    ">

                        Sistem Administrasi Manajemen Pegawai
                        dan Berkas Internal

                    </p>

                </div>


                {{-- =================================================
                FORM
            ================================================== --}}

                <form method="POST" action="{{ route('samperin.login.process') }}" class="mt-8">

                    @csrf


                    {{-- LOGIN --}}

                    <div>

                        <label for="login"
                            class="
                            mb-2
                            block

                            text-[14px]

                            font-bold

                            text-[#182238]
                        ">
                            NIP, NIK, atau Email
                        </label>


                        <div class="relative">

                            <div
                                class="
                                pointer-events-none

                                absolute
                                inset-y-0
                                left-0

                                flex

                                w-14

                                items-center
                                justify-center

                                text-slate-400
                            ">

                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <circle cx="12" cy="8" r="3" stroke-width="1.8" />

                                    <path stroke-linecap="round" stroke-width="1.8" d="
                                        M5 20
                                        a7 7 0 0114 0
                                    " />

                                </svg>

                            </div>


                            <input id="login" type="text" name="login" value="{{ old('login') }}"
                                autocomplete="username" required placeholder="Masukkan NIP, NIK, atau email"
                                class="
                                login-input

                                h-[58px]

                                w-full

                                rounded-xl

                                border
                                border-slate-200

                                bg-slate-50

                                pl-14
                                pr-4

                                text-[15px]

                                text-slate-800

                                placeholder:text-slate-400
                            ">

                        </div>


                        @error('login')
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

                    </div>


                    {{-- PASSWORD --}}

                    <div class="mt-5">

                        <label for="password"
                            class="
                            mb-2
                            block

                            text-[14px]

                            font-bold

                            text-[#182238]
                        ">
                            Password
                        </label>


                        <div class="relative">

                            <div
                                class="
                                pointer-events-none

                                absolute
                                inset-y-0
                                left-0

                                flex

                                w-14

                                items-center
                                justify-center

                                text-slate-400
                            ">

                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <rect x="5" y="10" width="14" height="11" rx="2"
                                        stroke-width="1.8" />

                                    <path stroke-linecap="round" stroke-width="1.8" d="
                                        M8 10V7
                                        a4 4 0 018 0v3
                                    " />

                                </svg>

                            </div>


                            <input id="password" type="password" name="password" autocomplete="current-password"
                                required placeholder="Masukkan password"
                                class="
                                login-input

                                h-[58px]

                                w-full

                                rounded-xl

                                border
                                border-slate-200

                                bg-slate-50

                                pl-14
                                pr-14

                                text-[15px]

                                text-slate-800

                                placeholder:text-slate-400
                            ">


                            <button type="button" id="togglePassword"
                                class="
                                absolute

                                inset-y-0
                                right-0

                                flex

                                w-14

                                items-center
                                justify-center

                                text-slate-400

                                hover:text-slate-600
                            ">

                                <svg id="eyeOpen" class="h-6 w-6" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="
                                        M2.5 12
                                        s3.5-6 9.5-6
                                        s9.5 6 9.5 6
                                        s-3.5 6-9.5 6
                                        s-9.5-6-9.5-6z
                                    " />

                                    <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />

                                </svg>


                                <svg id="eyeClosed"
                                    class="
                                    hidden
                                    h-6
                                    w-6
                                "
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="
                                        M3 3l18 18

                                        M10.6 10.6
                                        a2 2 0 002.8 2.8

                                        M9.9 5.3
                                        A10.8 10.8 0 0112 5

                                        c6 0 9.5 7 9.5 7

                                        a17.8 17.8 0 01-3.2 3.9

                                        M6.2 6.2
                                        C3.8 8.1 2.5 12 2.5 12

                                        s3.5 7 9.5 7

                                        c1.3 0 2.5-.3 3.5-.8
                                    " />

                                </svg>

                            </button>

                        </div>


                        @error('password')
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

                    </div>


                    {{-- REMEMBER --}}

                    <div
                        class="
                        mt-5

                        flex

                        items-center
                        justify-between

                        gap-4
                    ">

                        <label
                            class="
                            flex

                            cursor-pointer

                            items-center

                            gap-2

                            text-[14px]

                            text-slate-500
                        ">

                            <input type="checkbox" name="remember"
                                class="
                                h-5
                                w-5

                                rounded

                                border-slate-300

                                text-[#a85f27]

                                focus:ring-[#a85f27]
                            ">

                            Ingat saya

                        </label>


                        <span
                            class="
                            text-[14px]

                            font-semibold

                            text-slate-400
                        ">
                            Lupa password?
                        </span>

                    </div>


                    {{-- LOGIN BUTTON --}}

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

                        Masuk


                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-width="1.8" d="M5 12h13" />

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="m13 6 6 6-6 6" />

                        </svg>

                    </button>

                </form>


                {{-- SECURITY --}}

                <div
                    class="
                    security-box

                    mt-7

                    flex

                    items-center

                    gap-4

                    rounded-2xl

                    px-5
                    py-4
                ">

                    <div
                        class="
                        flex

                        h-11
                        w-11

                        shrink-0

                        items-center
                        justify-center

                        rounded-full

                        bg-white

                        text-[#9b673d]

                        shadow-sm
                    ">

                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="
                                M12 3
                                l7 3v5

                                c0 4.5-3 7.5-7 10

                                c-4-2.5-7-5.5-7-10

                                V6l7-3z
                            " />

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                d="m9 12 2 2 4-4" />

                        </svg>

                    </div>


                    <div
                        class="
                        text-xs

                        leading-5

                        text-slate-500

                        sm:text-sm
                    ">

                        Data dan berkas dalam SAMPERIN
                        bersifat internal dan hanya dapat
                        diakses oleh pengguna yang memiliki hak akses.

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}

            <div
                class="
                border-t

                border-slate-100

                px-6
                py-4

                text-center

                text-xs

                text-slate-500
            ">

                © {{ date('Y') }}
                SAMPERIN -
                Dinas Kebudayaan Provinsi Bali

            </div>

        </section>

    </div>


    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                const password =
                    document.getElementById(
                        'password'
                    );

                const toggle =
                    document.getElementById(
                        'togglePassword'
                    );

                const eyeOpen =
                    document.getElementById(
                        'eyeOpen'
                    );

                const eyeClosed =
                    document.getElementById(
                        'eyeClosed'
                    );


                if (
                    !password ||
                    !toggle
                ) {

                    return;

                }


                toggle.addEventListener(
                    'click',
                    function() {

                        const isPassword =
                            password.type === 'password';


                        password.type =
                            isPassword ?
                            'text' :
                            'password';


                        eyeOpen.classList.toggle(
                            'hidden',
                            !isPassword
                        );


                        eyeClosed.classList.toggle(
                            'hidden',
                            isPassword
                        );

                    }
                );

            }
        );
    </script>


</body>

</html>
