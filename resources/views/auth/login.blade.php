@extends('auth.layouts.app')

@section('title', 'SAMPERIN')

@section('content')

    @php

        $leftTitle = 'Dinas Kebudayaan<br>Provinsi Bali';

        $leftDescription =
            'Sistem terintegrasi untuk mengelola data pegawai, administrasi, dan berkas internal Dinas Kebudayaan Provinsi Bali secara terstruktur dan mudah.';

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


        {{-- =========================================================
        LEFT
    ========================================================== --}}

        @include('auth.partials.left-panel')


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
                        text-[#182238]
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


                {{-- ALERT ERROR --}}

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


                {{-- ALERT SUCCESS --}}

                @if (session('success'))
                    <div
                        class="
                        mt-6
                        rounded-xl
                        border
                        border-green-200
                        bg-green-50
                        px-4
                        py-3
                        text-sm
                        font-medium
                        text-green-700
                    ">
                        {{ session('success') }}
                    </div>
                @endif


                {{-- =================================================
                LOGIN FORM
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

                                    <path stroke-linecap="round" stroke-width="1.8" d="M5 20a7 7 0 0114 0" />
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
                                    <rect x="5" y="10" width="14" height="11" rx="2" stroke-width="1.8" />

                                    <path stroke-linecap="round" stroke-width="1.8" d="
                                            M8 10V7
                                            a4 4 0 018 0v3
                                        " />
                                </svg>

                            </div>


                            <input id="password" type="password" name="password" autocomplete="current-password" required
                                placeholder="Masukkan password"
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


                                <svg id="eyeClosed" class="hidden h-6 w-6" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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


                    {{-- FORGOT PASSWORD --}}

                    <div class="mt-2 text-right">

                        <a href="{{ route('password.forgot') }}"
                            class="
                            text-sm
                            font-medium
                            text-slate-600
                            hover:text-[#a84e15]
                        ">
                            Lupa Password?
                        </a>

                    </div>


                    {{-- BUTTON --}}

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

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m13 6 6 6-6 6" />
                        </svg>

                    </button>

                </form>


                {{-- SECURITY --}}

                @include('auth.partials.security')

            </div>


            {{-- FOOTER --}}

            @include('auth.partials.footer')

        </section>

    </div>

@endsection


@push('scripts')
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                const password =
                    document.getElementById('password');

                const toggle =
                    document.getElementById('togglePassword');

                const eyeOpen =
                    document.getElementById('eyeOpen');

                const eyeClosed =
                    document.getElementById('eyeClosed');


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
@endpush
