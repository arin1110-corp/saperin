@extends('layouts.app')


@section('title', 'Administrator')


@section('page-title', 'Administrator')


@section('content')


    <div class="mb-6">

        <h2 class="
            text-2xl
            font-bold
        ">

            Administrator

        </h2>


        <p class="
            text-slate-500
            mt-1
        ">

            Pengaturan sistem SAMPERIN.

        </p>

    </div>


    <div class="
        grid
        grid-cols-1
        md:grid-cols-3
        gap-6
    ">


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

                Total Role

            </div>


            <div class="
                text-3xl
                font-bold
                mt-2
            ">

                {{ \App\Models\SamperinRole::count() }}

            </div>

        </div>


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

                Status Sistem

            </div>


            <div
                class="
                text-xl
                font-bold
                mt-2
                text-green-600
            ">

                Aktif

            </div>

        </div>


    </div>


@endsection
