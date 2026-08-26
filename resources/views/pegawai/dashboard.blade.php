@extends('layouts.app')


@section('title', 'Dashboard Pegawai')


@section('page-title', 'Dashboard Pegawai')


@section('content')


    @php

        $user = request()->attributes->get('samperin_user');

    @endphp


    <div class="mb-6">

        <h2 class="
            text-2xl
            font-bold
        ">

            Selamat Datang,

            {{ $user->user_nama }}

        </h2>


        <p class="
            text-slate-500
            mt-1
        ">

            SAMPERIN —
            Sistem Administrasi Pegawai Internal

        </p>

    </div>


    <div class="
        grid
        grid-cols-1
        md:grid-cols-3
        gap-6
    ">


        {{-- NAMA --}}

        <div
            class="
            bg-white
            rounded-xl
            border
            border-slate-200
            p-6
        ">

            <div class="
                text-sm
                text-slate-500
            ">

                Nama

            </div>


            <div class="
                font-semibold
                mt-2
            ">

                {{ $user->user_nama }}

            </div>

        </div>


        {{-- NIP --}}

        <div
            class="
            bg-white
            rounded-xl
            border
            border-slate-200
            p-6
        ">

            <div class="
                text-sm
                text-slate-500
            ">

                NIP

            </div>


            <div class="
                font-semibold
                mt-2
            ">

                {{ $user->user_nip }}

            </div>

        </div>


        {{-- STATUS --}}

        <div
            class="
            bg-white
            rounded-xl
            border
            border-slate-200
            p-6
        ">

            <div class="
                text-sm
                text-slate-500
            ">

                Status

            </div>


            <span
                class="
                inline-flex
                mt-2
                px-3
                py-1
                rounded-full
                bg-green-100
                text-green-700
            ">

                Aktif

            </span>

        </div>


    </div>


    {{-- ROLE --}}

    <div class="
        bg-white
        rounded-xl
        border
        border-slate-200
        p-6
        mt-6
    ">

        <h3 class="
            font-semibold
            mb-4
        ">

            Hak Akses

        </h3>


        <div class="
            flex
            flex-wrap
            gap-2
        ">


            {{-- BAWAAN --}}

            <span
                class="
                px-3
                py-2
                rounded-lg
                bg-slate-100
            ">

                Pegawai

            </span>


            {{-- ROLE TAMBAHAN --}}

            @forelse($user->roles
                as $role)
                <span
                    class="
                    px-3
                    py-2
                    rounded-lg
                    bg-blue-100
                    text-blue-700
                ">

                    {{ $role->role_nama }}

                </span>

            @empty
            @endforelse


        </div>

    </div>


@endsection
