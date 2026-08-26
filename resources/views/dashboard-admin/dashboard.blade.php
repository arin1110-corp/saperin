@extends('dashboard-admin.layouts.app')

@section('title', 'Dashboard Admin - SAMPERIN')


@section('content')


    <div class="mx-auto max-w-[1600px]">


        {{-- =========================================================
        WELCOME
    ========================================================== --}}

        <section
            class="
            relative
            overflow-hidden

            rounded-2xl

            border
            border-orange-100

            bg-gradient-to-r
            from-orange-50
            via-amber-50
            to-white

            px-6
            py-6

            md:px-8
            md:py-7
        ">

            <div class="relative z-10">

                <div
                    class="
                    text-[12px]
                    font-black
                    uppercase
                    tracking-[.12em]
                    text-[#a84e15]
                ">
                    Administrator
                </div>


                <h1
                    class="
                    mt-2

                    text-2xl
                    font-black
                    tracking-tight

                    text-slate-900

                    md:text-3xl
                ">

                    Halo,
                    {{ $user->user_nama ?? 'Administrator' }}

                </h1>


                <p
                    class="
                    mt-2

                    max-w-[650px]

                    text-sm
                    leading-6

                    text-slate-500

                    md:text-[15px]
                ">

                    Kelola data pegawai, berkas internal,
                    dan administrasi SAMPERIN melalui halaman
                    administrator.

                </p>

            </div>


            <div
                class="
                pointer-events-none

                absolute
                -right-10
                -top-16

                h-52
                w-52

                rounded-full

                bg-orange-100/60
            ">
            </div>


            <div
                class="
                pointer-events-none

                absolute
                -bottom-20
                right-24

                h-40
                w-40

                rounded-full

                bg-amber-100/50
            ">
            </div>

        </section>



        {{-- =========================================================
        STAT
    ========================================================== --}}

        <section
            class="
            mt-6

            grid

            grid-cols-1

            gap-4

            sm:grid-cols-2

            xl:grid-cols-4
        ">


            {{-- TOTAL --}}

            <div
                class="
                rounded-2xl

                border
                border-slate-200

                bg-white

                p-5

                shadow-sm
            ">

                <div
                    class="
                    flex
                    items-center
                    justify-between
                ">

                    <div>

                        <div
                            class="
                            text-xs
                            font-bold
                            text-slate-400
                        ">
                            Total Pegawai
                        </div>


                        <div
                            class="
                            mt-2
                            text-3xl
                            font-black
                            text-slate-900
                        ">
                            {{ number_format($totalPegawai) }}
                        </div>

                    </div>


                    <div
                        class="
                        flex
                        h-11
                        w-11

                        items-center
                        justify-center

                        rounded-xl

                        bg-blue-50

                        text-blue-600
                    ">

                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <circle cx="9" cy="8" r="3" stroke-width="1.8" />

                            <path stroke-linecap="round" stroke-width="1.8" d="
                                    M3 20
                                    c0-3.5 2.5-5.5 6-5.5
                                    s6 2 6 5.5
                                " />

                            <circle cx="17" cy="9" r="2.5" stroke-width="1.8" />

                        </svg>

                    </div>

                </div>

            </div>


            {{-- AKTIF --}}

            <div
                class="
                rounded-2xl

                border
                border-slate-200

                bg-white

                p-5

                shadow-sm
            ">

                <div
                    class="
                    flex
                    items-center
                    justify-between
                ">

                    <div>

                        <div
                            class="
                            text-xs
                            font-bold
                            text-slate-400
                        ">
                            Pegawai Aktif
                        </div>


                        <div
                            class="
                            mt-2
                            text-3xl
                            font-black
                            text-slate-900
                        ">
                            {{ number_format($pegawaiAktif) }}
                        </div>

                    </div>


                    <div
                        class="
                        flex
                        h-11
                        w-11

                        items-center
                        justify-center

                        rounded-xl

                        bg-emerald-50

                        text-emerald-600
                    ">

                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="
                                    M12 3
                                    l7 3v5
                                    c0 4.5-3 7.5-7 10
                                    c-4-2.5-7-5.5-7-10V6l7-3z
                                " />

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9 12 2 2 4-4" />

                        </svg>

                    </div>

                </div>

            </div>


            {{-- NON AKTIF --}}

            <div
                class="
                rounded-2xl

                border
                border-slate-200

                bg-white

                p-5

                shadow-sm
            ">

                <div
                    class="
                    flex
                    items-center
                    justify-between
                ">

                    <div>

                        <div
                            class="
                            text-xs
                            font-bold
                            text-slate-400
                        ">
                            Pegawai Nonaktif
                        </div>


                        <div
                            class="
                            mt-2
                            text-3xl
                            font-black
                            text-slate-900
                        ">
                            {{ number_format($pegawaiNonaktif) }}
                        </div>

                    </div>


                    <div
                        class="
                        flex
                        h-11
                        w-11

                        items-center
                        justify-center

                        rounded-xl

                        bg-slate-100

                        text-slate-500
                    ">

                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <circle cx="12" cy="12" r="9" stroke-width="1.8" />

                            <path stroke-linecap="round" stroke-width="1.8" d="M8 12h8" />

                        </svg>

                    </div>

                </div>

            </div>


            {{-- EMAIL --}}

            <div
                class="
                rounded-2xl

                border
                border-slate-200

                bg-white

                p-5

                shadow-sm
            ">

                <div
                    class="
                    flex
                    items-center
                    justify-between
                ">

                    <div>

                        <div
                            class="
                            text-xs
                            font-bold
                            text-slate-400
                        ">
                            Memiliki Email
                        </div>


                        <div
                            class="
                            mt-2
                            text-3xl
                            font-black
                            text-slate-900
                        ">
                            {{ number_format($pegawaiDenganEmail) }}
                        </div>

                    </div>


                    <div
                        class="
                        flex
                        h-11
                        w-11

                        items-center
                        justify-center

                        rounded-xl

                        bg-violet-50

                        text-violet-600
                    ">

                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.8" />

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="
                                    m4 7 8 6 8-6
                                " />

                        </svg>

                    </div>

                </div>

            </div>

        </section>



        {{-- =========================================================
        QUICK ACTION
    ========================================================== --}}

        <section class="mt-6">

            <div
                class="
                mb-4

                text-lg
                font-black
                text-slate-900
            ">
                Menu Administrasi
            </div>


            <div
                class="
                grid

                grid-cols-1

                gap-4

                md:grid-cols-2

                xl:grid-cols-4
            ">


                {{-- DATA PEGAWAI --}}

                <a href="#"
                    class="
                    group

                    rounded-2xl

                    border
                    border-slate-200

                    bg-white

                    p-5

                    no-underline

                    shadow-sm

                    transition

                    hover:-translate-y-1
                    hover:border-blue-200
                    hover:shadow-lg
                ">

                    <div
                        class="
                        flex
                        h-11
                        w-11

                        items-center
                        justify-center

                        rounded-xl

                        bg-blue-50

                        text-blue-600

                        transition

                        group-hover:bg-blue-600
                        group-hover:text-white
                    ">

                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <circle cx="9" cy="8" r="3" stroke-width="1.8" />

                            <path stroke-linecap="round" stroke-width="1.8" d="
                                    M3 20
                                    c0-3.5 2.5-5.5 6-5.5
                                    s6 2 6 5.5
                                " />

                            <circle cx="17" cy="9" r="2.5" stroke-width="1.8" />

                        </svg>

                    </div>


                    <div
                        class="
                        mt-4
                        text-[15px]
                        font-black
                        text-slate-900
                    ">
                        Data Pegawai
                    </div>


                    <div
                        class="
                        mt-1
                        text-xs
                        leading-5
                        text-slate-400
                    ">
                        Lihat dan kelola data pegawai SAMPERIN.
                    </div>

                </a>


                {{-- IMPORT --}}

                <a href="#"
                    class="
                    group

                    rounded-2xl

                    border
                    border-slate-200

                    bg-white

                    p-5

                    no-underline

                    shadow-sm

                    transition

                    hover:-translate-y-1
                    hover:border-orange-200
                    hover:shadow-lg
                ">

                    <div
                        class="
                        flex
                        h-11
                        w-11

                        items-center
                        justify-center

                        rounded-xl

                        bg-orange-50

                        text-[#a84e15]

                        transition

                        group-hover:bg-[#a84e15]
                        group-hover:text-white
                    ">

                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="
                                    M12 16V4
                                    M7 9l5-5 5 5

                                    M5 12v6
                                    a2 2 0 002 2h10
                                    a2 2 0 002-2v-6
                                " />

                        </svg>

                    </div>


                    <div
                        class="
                        mt-4
                        text-[15px]
                        font-black
                        text-slate-900
                    ">
                        Import Data
                    </div>


                    <div
                        class="
                        mt-1
                        text-xs
                        leading-5
                        text-slate-400
                    ">
                        Migrasikan data pegawai dari sumber lain.
                    </div>

                </a>


                {{-- BERKAS --}}

                <a href="#"
                    class="
                    group

                    rounded-2xl

                    border
                    border-slate-200

                    bg-white

                    p-5

                    no-underline

                    shadow-sm

                    transition

                    hover:-translate-y-1
                    hover:border-emerald-200
                    hover:shadow-lg
                ">

                    <div
                        class="
                        flex
                        h-11
                        w-11

                        items-center
                        justify-center

                        rounded-xl

                        bg-emerald-50

                        text-emerald-600

                        transition

                        group-hover:bg-emerald-600
                        group-hover:text-white
                    ">

                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="
                                    M4 6
                                    a2 2 0 012-2
                                    h5
                                    l2 2
                                    h5
                                    a2 2 0 012 2
                                    v10
                                    a2 2 0 01-2 2H6
                                    a2 2 0 01-2-2V6z
                                " />

                        </svg>

                    </div>


                    <div
                        class="
                        mt-4
                        text-[15px]
                        font-black
                        text-slate-900
                    ">
                        Berkas Pegawai
                    </div>


                    <div
                        class="
                        mt-1
                        text-xs
                        leading-5
                        text-slate-400
                    ">
                        Kelola berkas internal pegawai.
                    </div>

                </a>


                {{-- APLIKASI --}}

                <a href="#"
                    class="
                    group

                    rounded-2xl

                    border
                    border-slate-200

                    bg-white

                    p-5

                    no-underline

                    shadow-sm

                    transition

                    hover:-translate-y-1
                    hover:border-violet-200
                    hover:shadow-lg
                ">

                    <div
                        class="
                        flex
                        h-11
                        w-11

                        items-center
                        justify-center

                        rounded-xl

                        bg-violet-50

                        text-violet-600

                        transition

                        group-hover:bg-violet-600
                        group-hover:text-white
                    ">

                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <rect x="4" y="4" width="6" height="6" rx="1" stroke-width="1.8" />

                            <rect x="14" y="4" width="6" height="6" rx="1" stroke-width="1.8" />

                            <rect x="4" y="14" width="6" height="6" rx="1" stroke-width="1.8" />

                            <rect x="14" y="14" width="6" height="6" rx="1" stroke-width="1.8" />

                        </svg>

                    </div>


                    <div
                        class="
                        mt-4
                        text-[15px]
                        font-black
                        text-slate-900
                    ">
                        Aplikasi
                    </div>


                    <div
                        class="
                        mt-1
                        text-xs
                        leading-5
                        text-slate-400
                    ">
                        Kelola aplikasi yang terhubung.
                    </div>

                </a>

            </div>

        </section>



        {{-- =========================================================
        DATA TERBARU
    ========================================================== --}}

        <section class="mt-6">

            <div
                class="
                overflow-hidden

                rounded-2xl

                border
                border-slate-200

                bg-white

                shadow-sm
            ">

                <div
                    class="
                    flex
                    flex-col
                    gap-3

                    border-b
                    border-slate-100

                    px-6
                    py-5

                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                ">

                    <div>

                        <h2
                            class="
                            text-base
                            font-black
                            text-slate-900
                        ">
                            Pegawai Terbaru
                        </h2>


                        <p
                            class="
                            mt-1
                            text-xs
                            text-slate-400
                        ">
                            Data pegawai yang terakhir masuk ke sistem.
                        </p>

                    </div>


                    <a href="#"
                        class="
                        text-xs
                        font-bold

                        text-[#a84e15]

                        no-underline

                        hover:underline
                    ">
                        Lihat Semua
                    </a>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[750px]">

                        <thead>

                            <tr
                                class="
                                border-b
                                border-slate-100
                                bg-slate-50
                            ">

                                <th
                                    class="
                                    px-6
                                    py-3

                                    text-left

                                    text-[10px]
                                    font-black
                                    uppercase
                                    tracking-wider

                                    text-slate-400
                                ">
                                    Pegawai
                                </th>


                                <th
                                    class="
                                    px-6
                                    py-3

                                    text-left

                                    text-[10px]
                                    font-black
                                    uppercase
                                    tracking-wider

                                    text-slate-400
                                ">
                                    NIP
                                </th>


                                <th
                                    class="
                                    px-6
                                    py-3

                                    text-left

                                    text-[10px]
                                    font-black
                                    uppercase
                                    tracking-wider

                                    text-slate-400
                                ">
                                    Jabatan
                                </th>


                                <th
                                    class="
                                    px-6
                                    py-3

                                    text-left

                                    text-[10px]
                                    font-black
                                    uppercase
                                    tracking-wider

                                    text-slate-400
                                ">
                                    Bidang
                                </th>


                                <th
                                    class="
                                    px-6
                                    py-3

                                    text-left

                                    text-[10px]
                                    font-black
                                    uppercase
                                    tracking-wider

                                    text-slate-400
                                ">
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($pegawaiTerbaru
                                as $pegawai)
                                <tr
                                    class="
                                    border-b
                                    border-slate-100

                                    last:border-0

                                    hover:bg-slate-50
                                ">

                                    <td
                                        class="
                                        px-6
                                        py-4
                                    ">

                                        <div
                                            class="
                                            flex
                                            items-center
                                            gap-3
                                        ">

                                            <div
                                                class="
                                                flex
                                                h-9
                                                w-9

                                                shrink-0

                                                items-center
                                                justify-center

                                                rounded-full

                                                bg-orange-50

                                                text-xs
                                                font-black

                                                text-[#a84e15]
                                            ">

                                                {{ strtoupper(substr($pegawai->user_nama ?? 'P', 0, 1)) }}

                                            </div>


                                            <div>

                                                <div
                                                    class="
                                                    text-[13px]
                                                    font-bold
                                                    text-slate-800
                                                ">
                                                    {{ $pegawai->user_nama ?? '-' }}
                                                </div>


                                                <div
                                                    class="
                                                    mt-0.5
                                                    text-[10px]
                                                    text-slate-400
                                                ">
                                                    {{ $pegawai->user_email ?? 'Email belum tersedia' }}
                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    <td
                                        class="
                                        px-6
                                        py-4

                                        text-[12px]
                                        font-medium

                                        text-slate-600
                                    ">

                                        {{ $pegawai->user_nip ?? '-' }}

                                    </td>


                                    <td
                                        class="
                                        px-6
                                        py-4

                                        text-[12px]

                                        text-slate-600
                                    ">

                                        {{ $pegawai->user_jabatan ?? '-' }}

                                    </td>


                                    <td
                                        class="
                                        px-6
                                        py-4

                                        text-[12px]

                                        text-slate-600
                                    ">

                                        {{ $pegawai->user_bidang ?? '-' }}

                                    </td>


                                    <td
                                        class="
                                        px-6
                                        py-4
                                    ">

                                        @if ((int) $pegawai->user_status === 1)
                                            <span
                                                class="
                                                inline-flex
                                                items-center
                                                gap-1.5

                                                rounded-full

                                                bg-emerald-50

                                                px-2.5
                                                py-1

                                                text-[10px]
                                                font-black

                                                text-emerald-600
                                            ">

                                                <span
                                                    class="
                                                    h-1.5
                                                    w-1.5
                                                    rounded-full
                                                    bg-emerald-500
                                                "></span>

                                                Aktif

                                            </span>
                                        @else
                                            <span
                                                class="
                                                inline-flex
                                                items-center
                                                gap-1.5

                                                rounded-full

                                                bg-slate-100

                                                px-2.5
                                                py-1

                                                text-[10px]
                                                font-black

                                                text-slate-500
                                            ">

                                                <span
                                                    class="
                                                    h-1.5
                                                    w-1.5
                                                    rounded-full
                                                    bg-slate-400
                                                "></span>

                                                Nonaktif

                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5"
                                        class="
                                        px-6
                                        py-14

                                        text-center
                                    ">

                                        <div
                                            class="
                                            text-sm
                                            font-bold
                                            text-slate-500
                                        ">
                                            Belum ada data pegawai.
                                        </div>


                                        <div
                                            class="
                                            mt-1
                                            text-xs
                                            text-slate-400
                                        ">
                                            Data pegawai akan muncul setelah proses import.
                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </section>


    </div>


@endsection
