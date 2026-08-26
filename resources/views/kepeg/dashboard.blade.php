@extends('layouts.app')


@section('title', 'Kepegawaian')


@section('page-title', 'Kepegawaian')


@section('content')


    <div class="mb-6">

        <h2 class="
            text-2xl
            font-bold
        ">

            Kepegawaian

        </h2>


        <p class="
            text-slate-500
            mt-1
        ">

            Administrasi pegawai
            SAMPERIN.

        </p>

    </div>


    <div class="
        grid
        grid-cols-1
        md:grid-cols-3
        gap-6
    ">


        {{-- TOTAL --}}

        <div class="
            bg-white
            rounded-xl
            border
            p-6
        ">

            <div class="
                text-sm
                text-slate-500
            ">

                Total Pegawai

            </div>


            <div class="
                text-3xl
                font-bold
                mt-2
            ">

                {{ \App\Models\SamperinUser::count() }}

            </div>

        </div>


        {{-- AKTIF --}}

        <div class="
            bg-white
            rounded-xl
            border
            p-6
        ">

            <div class="
                text-sm
                text-slate-500
            ">

                Pegawai Aktif

            </div>


            <div class="
                text-3xl
                font-bold
                mt-2
            ">

                {{ \App\Models\SamperinUser::where('user_status', 1)->count() }}

            </div>

        </div>


        {{-- BERKAS --}}

        <div class="
            bg-white
            rounded-xl
            border
            p-6
        ">

            <div class="
                text-sm
                text-slate-500
            ">

                Total Berkas

            </div>


            <div class="
                text-3xl
                font-bold
                mt-2
            ">

                {{ \App\Models\SamperinPengumpulanBerkas::count() }}

            </div>

        </div>


    </div>


@endsection
