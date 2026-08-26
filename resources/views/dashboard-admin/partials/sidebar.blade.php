<div class="flex h-full flex-col">


    {{-- =========================================================
        BRAND
    ========================================================== --}}

    <div
        class="
            flex
            h-16
            shrink-0
            items-center

            border-b
            border-slate-100

            px-6
        ">

        <a href="{{ route('samperin.admin.dashboard') }}"
            class="
                flex
                items-center
                gap-3

                no-underline
            ">

            <div
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center

                    rounded-lg

                    bg-[#a84e15]

                    text-white
                ">

                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="
                            M4 5
                            a2 2 0 012-2
                            h12
                            a2 2 0 012 2
                            v14
                            a2 2 0 01-2 2
                            H6
                            a2 2 0 01-2-2V5z
                        " />

                    <path stroke-linecap="round" stroke-width="2" d="
                            M8 8h8
                            M8 12h8
                            M8 16h5
                        " />

                </svg>

            </div>


            <div>

                <div
                    class="
                        text-[20px]
                        font-black
                        leading-none
                        tracking-tight
                    ">

                    <span class="text-slate-900">
                        SAMPER
                    </span>

                    <span class="text-[#a84e15]">
                        IN
                    </span>

                </div>


                <div
                    class="
                        mt-1
                        text-[9px]
                        font-semibold
                        uppercase
                        tracking-[.12em]
                        text-slate-400
                    ">
                    Admin Panel
                </div>

            </div>

        </a>

    </div>


    {{-- =========================================================
        NAVIGATION
    ========================================================== --}}

    <div class="
            flex-1
            overflow-y-auto

            px-4
            py-6
        ">


        {{-- MAIN --}}

        <div
            class="
                mb-2
                px-3

                text-[10px]
                font-black
                uppercase
                tracking-[.12em]

                text-slate-400
            ">
            Utama
        </div>


        <a href="{{ route('samperin.admin.dashboard') }}"
            class="
                mb-1
                flex
                items-center
                gap-3

                rounded-xl

                bg-orange-50

                px-3
                py-3

                text-[13px]
                font-bold

                text-[#a84e15]

                no-underline
            ">

            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <rect x="3" y="3" width="7" height="7" rx="1" stroke-width="1.8" />

                <rect x="14" y="3" width="7" height="7" rx="1" stroke-width="1.8" />

                <rect x="3" y="14" width="7" height="7" rx="1" stroke-width="1.8" />

                <rect x="14" y="14" width="7" height="7" rx="1" stroke-width="1.8" />

            </svg>


            Dashboard

        </a>


        {{-- PEGAWAI --}}

        <div
            class="
                mb-2
                mt-7
                px-3

                text-[10px]
                font-black
                uppercase
                tracking-[.12em]

                text-slate-400
            ">
            Kepegawaian
        </div>


        <a href="#"
            class="
                admin-nav-link

                mb-1
                flex
                items-center
                gap-3

                rounded-xl

                px-3
                py-3

                text-[13px]
                font-semibold

                text-slate-600

                no-underline

                transition

                hover:bg-slate-50
                hover:text-slate-900
            ">

            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <circle cx="9" cy="8" r="3" stroke-width="1.8" />

                <circle cx="17" cy="9" r="2.5" stroke-width="1.8" />

                <path stroke-linecap="round" stroke-width="1.8" d="
                        M3 20
                        c0-3.5 2.5-5.5 6-5.5
                        s6 2 6 5.5

                        M14 15
                        c3.5 0 5.5 1.8 5.5 5
                    " />

            </svg>


            Data Pegawai

        </a>


        <a href="#"
            class="
                mb-1
                flex
                items-center
                gap-3

                rounded-xl

                px-3
                py-3

                text-[13px]
                font-semibold

                text-slate-600

                no-underline

                transition

                hover:bg-slate-50
                hover:text-slate-900
            ">

            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="
                        M4 5
                        a2 2 0 012-2
                        h12
                        a2 2 0 012 2
                        v14
                        a2 2 0 01-2 2
                        H6
                        a2 2 0 01-2-2V5z
                    " />

                <path stroke-linecap="round" stroke-width="1.8" d="
                        M8 8h8
                        M8 12h5
                        M8 16h8
                    " />

            </svg>


            Import Data

        </a>


        <a href="#"
            class="
                mb-1
                flex
                items-center
                gap-3

                rounded-xl

                px-3
                py-3

                text-[13px]
                font-semibold

                text-slate-600

                no-underline

                transition

                hover:bg-slate-50
                hover:text-slate-900
            ">

            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="
                        M5 4
                        h10
                        l4 4
                        v12
                        H5
                        V4z
                    " />

                <path stroke-linejoin="round" stroke-width="1.8" d="
                        M14 4v5h5
                    " />

                <path stroke-linecap="round" stroke-width="1.8" d="
                        M8 13h8
                        M8 16h6
                    " />

            </svg>


            Berkas Pegawai

        </a>


        {{-- SISTEM --}}

        <div
            class="
                mb-2
                mt-7
                px-3

                text-[10px]
                font-black
                uppercase
                tracking-[.12em]

                text-slate-400
            ">
            Sistem
        </div>


        <a href="#"
            class="
                mb-1
                flex
                items-center
                gap-3

                rounded-xl

                px-3
                py-3

                text-[13px]
                font-semibold

                text-slate-600

                no-underline

                transition

                hover:bg-slate-50
                hover:text-slate-900
            ">

            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <rect x="3" y="4" width="18" height="16" rx="2" stroke-width="1.8" />

                <rect x="6" y="7" width="5" height="5" rx="1" stroke-width="1.8" />

                <path stroke-linecap="round" stroke-width="1.8" d="
                        M14 8h4
                        M14 11h4
                        M6 16h12
                    " />

            </svg>


            Aplikasi

        </a>


        <a href="#"
            class="
                mb-1
                flex
                items-center
                gap-3

                rounded-xl

                px-3
                py-3

                text-[13px]
                font-semibold

                text-slate-600

                no-underline

                transition

                hover:bg-slate-50
                hover:text-slate-900
            ">

            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="
                        M12 3
                        l7 3v5
                        c0 4.5-3 7.5-7 10
                        c-4-2.5-7-5.5-7-10V6l7-3z
                    " />

                <path stroke-linecap="round" stroke-width="1.8" d="M9 12l2 2 4-4" />

            </svg>


            Pengaturan

        </a>

    </div>


    {{-- =========================================================
        BOTTOM
    ========================================================== --}}

    <div class="
            shrink-0

            border-t
            border-slate-100

            p-4
        ">

        <a href="{{ route('samperin.dashboard') }}"
            class="
                mb-2
                flex
                items-center
                gap-3

                rounded-xl

                px-3
                py-3

                text-[13px]
                font-semibold

                text-slate-500

                no-underline

                transition

                hover:bg-slate-50
                hover:text-slate-900
            ">

            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="
                        M15 18l6-6-6-6
                        M21 12H9
                        M9 5H5
                        a2 2 0 00-2 2v10
                        a2 2 0 002 2h4
                    " />

            </svg>


            Portal SAMPERIN

        </a>


        <form method="POST" action="{{ route('samperin.logout') }}">

            @csrf

            <button type="submit"
                class="
                    flex
                    w-full
                    items-center
                    gap-3

                    rounded-xl

                    px-3
                    py-3

                    text-left

                    text-[13px]
                    font-semibold

                    text-red-500

                    transition

                    hover:bg-red-50
                ">

                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="
                            M15 18l6-6-6-6
                            M21 12H9
                            M9 5H5
                            a2 2 0 00-2 2v10
                            a2 2 0 002 2h4
                        " />

                </svg>


                Keluar

            </button>

        </form>

    </div>

</div>
