@extends('auth.layouts.app')

@section('title', 'Reset Password - SAMPERIN')

@section('content')

    <div
        class="
        min-h-screen
        flex
        flex-col
        items-center
        justify-center
        px-4
        py-10
    ">

        <div
            class="
            w-full
            max-w-md
            rounded-3xl
            bg-white
            p-8
            shadow-[0_15px_45px_rgba(45,30,20,.15)]
            sm:p-10
        ">

            {{-- LOGO --}}

            <div class="flex justify-center">

                <div
                    class="
                    flex
                    h-24
                    w-24
                    items-center
                    justify-center
                    rounded-full
                    bg-[#faf8f5]
                    p-3
                ">

                    <img src="{{ asset('assets/images/logo-samperin.png') }}" alt="Logo SAMPERIN"
                        class="h-full w-full object-contain">

                </div>

            </div>


            {{-- TITLE --}}

            <div class="mt-6 text-center">

                <h1
                    class="
                    text-2xl
                    font-black
                    text-[#182238]
                ">
                    Reset Password
                </h1>

                <p
                    class="
                    mt-2
                    text-sm
                    leading-6
                    text-slate-500
                ">
                    Buat password baru untuk akun
                    SAMPERIN Anda.
                </p>

            </div>


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


            {{-- FORM --}}

            <form method="POST" action="{{ route('password.update') }}" class="mt-8">

                @csrf

                <input type="hidden" name="token" value="{{ $token }}">


                {{-- PASSWORD --}}

                <div>

                    <label for="password"
                        class="
                        mb-2
                        block
                        text-sm
                        font-bold
                        text-[#182238]
                    ">
                        Password Baru
                    </label>

                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        class="
                        login-input
                        h-14
                        w-full
                        rounded-xl
                        border
                        border-slate-200
                        bg-slate-50
                        px-4
                        text-sm
                    ">

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


                {{-- CONFIRMATION --}}

                <div class="mt-5">

                    <label for="password_confirmation"
                        class="
                        mb-2
                        block
                        text-sm
                        font-bold
                        text-[#182238]
                    ">
                        Konfirmasi Password
                    </label>

                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        autocomplete="new-password" placeholder="Ulangi password baru"
                        class="
                        login-input
                        h-14
                        w-full
                        rounded-xl
                        border
                        border-slate-200
                        bg-slate-50
                        px-4
                        text-sm
                    ">

                </div>


                <button type="submit"
                    class="
                    login-button
                    mt-6
                    flex
                    h-14
                    w-full
                    items-center
                    justify-center
                    rounded-xl
                    bg-[#a84e15]
                    text-sm
                    font-bold
                    text-white
                ">
                    Simpan Password
                </button>

            </form>


            {{-- SECURITY --}}

            @include('auth.partials.security')


            {{-- LOGIN --}}

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

        </div>


        {{-- FOOTER --}}

        <div class="mt-6 w-full max-w-md">

            @include('auth.partials.footer')

        </div>

    </div>

@endsection
