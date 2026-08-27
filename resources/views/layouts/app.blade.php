<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'SAMPERIN')
    </title>


    {{-- =========================================================
        TAILWIND CDN
    ========================================================== --}}

    <script src="https://cdn.tailwindcss.com"></script>


    {{-- =========================================================
        BOOTSTRAP ICONS
    ========================================================== --}}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    {{-- =========================================================
        TAILWIND CONFIG
    ========================================================== --}}

    <script>
        tailwind.config = {

            theme: {

                extend: {

                    colors: {

                        samperin: {

                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a'

                        }

                    }

                }

            }

        }
    </script>


</head>


<body class="
        bg-slate-100
        text-slate-800
        antialiased
    ">


    @php

        /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    |
    | User diberikan oleh middleware samperin.user
    |
    */

        $user = request()->attributes->get('samperin_user');

        /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    |
    | Pegawai bukan role.
    |
    | Role tambahan berada di:
    |
    | samperin_user_role
    |
    */

        $roleNames = collect();

        if ($user) {
            $roleNames = $user->roles->pluck('role_nama')->map(function ($role) {
                return strtolower(trim($role));
            });
        }

        /*
    |--------------------------------------------------------------------------
    | CEK ROLE
    |--------------------------------------------------------------------------
    */

        $isAdmin = $roleNames->contains('admin');

        $isKepeg = $roleNames->contains('kepeg');

        /*
    |--------------------------------------------------------------------------
    | AKSES KEPEGAWAIAN
    |--------------------------------------------------------------------------
    */

        $hasKepegAccess = $isAdmin || $isKepeg;

    @endphp



    {{-- =============================================================
    WRAPPER
============================================================== --}}

    <div class="
        min-h-screen
        flex
    ">


        {{-- =========================================================
        SIDEBAR DESKTOP
    ========================================================== --}}

        <aside
            class="
            hidden
            md:flex
            fixed
            inset-y-0
            left-0
            z-40
            w-64
            bg-slate-900
            text-white
            flex-col
        ">


            {{-- =====================================================
            LOGO
        ====================================================== --}}

            <div
                class="
                px-6
                py-6
                border-b
                border-slate-800
            ">

                <div
                    class="
                    text-2xl
                    font-bold
                    tracking-tight
                ">

                    SAMPERIN

                </div>


                <div
                    class="
                    text-xs
                    text-slate-400
                    mt-1
                    leading-relaxed
                ">

                    Sistem Administrasi
                    <br>
                    Pegawai Internal

                </div>

            </div>



            {{-- =====================================================
            NAVIGATION
        ====================================================== --}}

            <nav
                class="
                flex-1
                px-4
                py-6
                space-y-1
                overflow-y-auto
            ">


                {{-- =================================================
                DASHBOARD PEGAWAI
            ================================================== --}}

                <a href="#"
                    class="
                    flex
                    items-center
                    gap-3
                    px-4
                    py-3
                    rounded-xl
                    text-sm
                    transition
                    {{ request()->routeIs('pegawai.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}
                ">

                    <i
                        class="
                        bi
                        bi-grid
                        text-lg
                    "></i>


                    <span>

                        Dashboard

                    </span>

                </a>



                {{-- =================================================
                KEPEGAWAIAN
            ================================================== --}}

                @if ($hasKepegAccess)
                    <div
                        class="
                        pt-6
                        pb-2
                        px-4
                    ">

                        <span
                            class="
                            text-[10px]
                            uppercase
                            tracking-widest
                            font-semibold
                            text-slate-500
                        ">

                            Modul

                        </span>

                    </div>


                    <a href="{{ route('kepeg.dashboard') }}"
                        class="
                        flex
                        items-center
                        gap-3
                        px-4
                        py-3
                        rounded-xl
                        text-sm
                        transition
                        {{ request()->routeIs('kepeg.*')
                            ? 'bg-slate-800 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}
                    ">

                        <i
                            class="
                            bi
                            bi-people
                            text-lg
                        "></i>


                        <span>

                            Kepegawaian

                        </span>

                    </a>
                @endif



                {{-- =================================================
                ADMINISTRATOR
            ================================================== --}}

                @if ($isAdmin)
                    <a href="{{ route('admin.dashboard') }}"
                        class="
                        flex
                        items-center
                        gap-3
                        px-4
                        py-3
                        rounded-xl
                        text-sm
                        transition
                        {{ request()->routeIs('admin.*')
                            ? 'bg-slate-800 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}
                    ">

                        <i
                            class="
                            bi
                            bi-shield-lock
                            text-lg
                        "></i>


                        <span>

                            Administrator

                        </span>

                    </a>
                @endif

            </nav>



            {{-- =====================================================
            USER AREA
        ====================================================== --}}

            <div class="
                border-t
                border-slate-800
                p-4
            ">

                @if ($user)


                    {{-- USER PROFILE --}}

                    <div
                        class="
                        flex
                        items-center
                        gap-3
                    ">


                        {{-- AVATAR --}}

                        <div
                            class="
                            w-10
                            h-10
                            flex-shrink-0
                            rounded-xl
                            bg-slate-800
                            flex
                            items-center
                            justify-center
                        ">

                            <i
                                class="
                                bi
                                bi-person
                                text-lg
                                text-slate-300
                            "></i>

                        </div>


                        {{-- USER DATA --}}

                        <div
                            class="
                            min-w-0
                            flex-1
                        ">

                            <div
                                class="
                                text-sm
                                font-medium
                                text-white
                                truncate
                            ">

                                {{ $user->user_nama }}

                            </div>


                            <div
                                class="
                                text-xs
                                text-slate-400
                                truncate
                            ">

                                {{ $user->user_nip }}

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                    BADGES
                ================================================== --}}

                    <div
                        class="
                        flex
                        flex-wrap
                        gap-1
                        mt-3
                    ">


                        {{-- PEGAWAI BAWAAN --}}

                        <span
                            class="
                            inline-flex
                            items-center
                            px-2
                            py-1
                            rounded-md
                            bg-slate-800
                            text-slate-300
                            text-[10px]
                            font-medium
                        ">

                            Pegawai

                        </span>


                        {{-- ROLE TAMBAHAN --}}

                        @foreach ($user->roles as $role)
                            <span
                                class="
                                inline-flex
                                items-center
                                px-2
                                py-1
                                rounded-md
                                bg-blue-900
                                text-blue-100
                                text-[10px]
                                font-medium
                            ">

                                {{ $role->role_nama }}

                            </span>
                        @endforeach

                    </div>



                    {{-- =================================================
                    LOGOUT
                ================================================== --}}

                    <form action="{{ route('samperin.logout') }}" method="POST" class="mt-4">

                        @csrf

                        <button type="submit"
                            class="
                            w-full
                            flex
                            items-center
                            justify-center
                            gap-2
                            px-4
                            py-2.5
                            rounded-xl
                            border
                            border-slate-800
                            text-slate-300
                            text-sm
                            hover:bg-red-950
                            hover:text-red-300
                            hover:border-red-900
                            transition
                        ">

                            <i
                                class="
                                bi
                                bi-box-arrow-right
                            "></i>


                            Keluar

                        </button>

                    </form>


                @endif

            </div>


        </aside>



        {{-- =========================================================
        MOBILE SIDEBAR
    ========================================================== --}}

        <div id="mobile-menu"
            class="
            fixed
            inset-0
            z-50
            hidden
        ">


            {{-- OVERLAY --}}

            <div id="mobile-overlay"
                class="
                absolute
                inset-0
                bg-black/50
            ">
            </div>



            {{-- MOBILE SIDEBAR --}}

            <aside
                class="
                relative
                w-72
                max-w-[85%]
                h-full
                bg-slate-900
                text-white
                flex
                flex-col
                shadow-2xl
            ">


                {{-- HEADER --}}

                <div
                    class="
                    px-6
                    py-5
                    border-b
                    border-slate-800
                    flex
                    items-center
                    justify-between
                ">

                    <div>

                        <div
                            class="
                            text-xl
                            font-bold
                        ">

                            SAMPERIN

                        </div>


                        <div
                            class="
                            text-[10px]
                            text-slate-400
                            mt-1
                        ">

                            Sistem Administrasi
                            Pegawai Internal

                        </div>

                    </div>


                    <button type="button" id="mobile-close"
                        class="
                        w-9
                        h-9
                        rounded-lg
                        flex
                        items-center
                        justify-center
                        text-slate-300
                        hover:bg-slate-800
                        hover:text-white
                    ">

                        <i class="bi bi-x-lg"></i>

                    </button>

                </div>



                {{-- MOBILE NAV --}}

                <nav
                    class="
                    flex-1
                    px-4
                    py-6
                    space-y-1
                    overflow-y-auto
                ">


                    {{-- DASHBOARD --}}

                    <a href="{{ route('pegawai.dashboard') }}"
                        class="
                        flex
                        items-center
                        gap-3
                        px-4
                        py-3
                        rounded-xl
                        text-sm
                        text-slate-300
                        hover:bg-slate-800
                        hover:text-white
                        transition
                    ">

                        <i
                            class="
                            bi
                            bi-grid
                        "></i>


                        Dashboard

                    </a>



                    {{-- KEPEGAWAIAN --}}

                    @if ($hasKepegAccess)
                        <div
                            class="
                            pt-6
                            pb-2
                            px-4
                        ">

                            <span
                                class="
                                text-[10px]
                                uppercase
                                tracking-widest
                                font-semibold
                                text-slate-500
                            ">

                                Modul

                            </span>

                        </div>


                        <a href="{{ route('kepeg.dashboard') }}"
                            class="
                            flex
                            items-center
                            gap-3
                            px-4
                            py-3
                            rounded-xl
                            text-sm
                            text-slate-300
                            hover:bg-slate-800
                            hover:text-white
                            transition
                        ">

                            <i
                                class="
                                bi
                                bi-people
                            "></i>


                            Kepegawaian

                        </a>
                    @endif



                    {{-- ADMIN --}}

                    @if ($isAdmin)
                        <a href="{{ route('admin.dashboard') }}"
                            class="
                            flex
                            items-center
                            gap-3
                            px-4
                            py-3
                            rounded-xl
                            text-sm
                            text-slate-300
                            hover:bg-slate-800
                            hover:text-white
                            transition
                        ">

                            <i
                                class="
                                bi
                                bi-shield-lock
                            "></i>


                            Administrator

                        </a>
                    @endif

                </nav>



                {{-- MOBILE USER --}}

                @if ($user)

                    <div
                        class="
                        p-4
                        border-t
                        border-slate-800
                    ">


                        {{-- NAME --}}

                        <div
                            class="
                            text-sm
                            font-medium
                            text-white
                            truncate
                        ">

                            {{ $user->user_nama }}

                        </div>


                        {{-- NIP --}}

                        <div
                            class="
                            text-xs
                            text-slate-400
                            mt-0.5
                        ">

                            {{ $user->user_nip }}

                        </div>


                        {{-- ROLE --}}

                        <div
                            class="
                            flex
                            flex-wrap
                            gap-1
                            mt-3
                        ">

                            {{-- PEGAWAI --}}

                            <span
                                class="
                                px-2
                                py-1
                                rounded-md
                                bg-slate-800
                                text-slate-300
                                text-[10px]
                            ">

                                Pegawai

                            </span>


                            {{-- ROLE TAMBAHAN --}}

                            @foreach ($user->roles as $role)
                                <span
                                    class="
                                    px-2
                                    py-1
                                    rounded-md
                                    bg-blue-900
                                    text-blue-100
                                    text-[10px]
                                ">

                                    {{ $role->role_nama }}

                                </span>
                            @endforeach

                        </div>


                        {{-- LOGOUT --}}

                        <form action="{{ route('logout') }}" method="POST" class="mt-4">

                            @csrf

                            <button type="submit"
                                class="
                                w-full
                                flex
                                items-center
                                justify-center
                                gap-2
                                px-4
                                py-2.5
                                rounded-xl
                                border
                                border-slate-800
                                text-slate-300
                                text-sm
                                hover:bg-red-950
                                hover:text-red-300
                                transition
                            ">

                                <i
                                    class="
                                    bi
                                    bi-box-arrow-right
                                "></i>


                                Keluar

                            </button>

                        </form>

                    </div>

                @endif

            </aside>

        </div>



        {{-- =========================================================
        MAIN
    ========================================================== --}}

        <main class="
            flex-1
            min-w-0
            md:ml-64
        ">


            {{-- =====================================================
            HEADER
        ====================================================== --}}

            <header
                class="
                sticky
                top-0
                z-30
                bg-white
                border-b
                border-slate-200
                px-4
                md:px-6
                py-4
            ">

                <div
                    class="
                    flex
                    items-center
                    gap-4
                ">


                    {{-- MOBILE BUTTON --}}

                    <button type="button" id="mobile-open"
                        class="
                        md:hidden
                        w-10
                        h-10
                        rounded-xl
                        bg-slate-900
                        text-white
                        flex
                        items-center
                        justify-center
                        flex-shrink-0
                    ">

                        <i
                            class="
                            bi
                            bi-list
                            text-xl
                        "></i>

                    </button>



                    {{-- PAGE TITLE --}}

                    <div class="
                        flex-1
                        min-w-0
                    ">

                        <h1
                            class="
                            font-semibold
                            text-lg
                            text-slate-900
                            truncate
                        ">

                            @yield('page-title', 'SAMPERIN')

                        </h1>


                        <p
                            class="
                            text-xs
                            text-slate-400
                            mt-0.5
                            hidden
                            sm:block
                        ">

                            Sistem Administrasi Pegawai Internal

                        </p>

                    </div>



                    {{-- HEADER USER --}}

                    @if ($user)
                        <div
                            class="
                            hidden
                            sm:flex
                            items-center
                            gap-3
                        ">

                            <div
                                class="
                                text-right
                                max-w-48
                            ">

                                <div
                                    class="
                                    text-sm
                                    font-medium
                                    text-slate-700
                                    truncate
                                ">

                                    {{ $user->user_nama }}

                                </div>


                                <div
                                    class="
                                    text-xs
                                    text-slate-400
                                ">

                                    {{ $user->user_nip }}

                                </div>

                            </div>


                            <div
                                class="
                                w-10
                                h-10
                                rounded-xl
                                bg-slate-900
                                text-white
                                flex
                                items-center
                                justify-center
                                flex-shrink-0
                            ">

                                <i
                                    class="
                                    bi
                                    bi-person
                                "></i>

                            </div>

                        </div>
                    @endif

                </div>

            </header>



            {{-- =====================================================
            CONTENT
        ====================================================== --}}

            <section class="
                p-4
                md:p-6
                lg:p-8
            ">

                @yield('content')

            </section>


        </main>


    </div>



    {{-- =============================================================
    MOBILE MENU JAVASCRIPT
============================================================== --}}

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {


                const mobileMenu =
                    document.getElementById(
                        'mobile-menu'
                    );


                const mobileOpen =
                    document.getElementById(
                        'mobile-open'
                    );


                const mobileClose =
                    document.getElementById(
                        'mobile-close'
                    );


                const mobileOverlay =
                    document.getElementById(
                        'mobile-overlay'
                    );


                /*
                |--------------------------------------------------------------------------
                | OPEN
                |--------------------------------------------------------------------------
                */

                function openMobileMenu() {

                    if (!mobileMenu) {
                        return;
                    }


                    mobileMenu.classList.remove(
                        'hidden'
                    );


                    document.body.classList.add(
                        'overflow-hidden'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | CLOSE
                |--------------------------------------------------------------------------
                */

                function closeMobileMenu() {

                    if (!mobileMenu) {
                        return;
                    }


                    mobileMenu.classList.add(
                        'hidden'
                    );


                    document.body.classList.remove(
                        'overflow-hidden'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | BUTTON OPEN
                |--------------------------------------------------------------------------
                */

                if (mobileOpen) {

                    mobileOpen.addEventListener(
                        'click',
                        openMobileMenu
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | BUTTON CLOSE
                |--------------------------------------------------------------------------
                */

                if (mobileClose) {

                    mobileClose.addEventListener(
                        'click',
                        closeMobileMenu
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | OVERLAY
                |--------------------------------------------------------------------------
                */

                if (mobileOverlay) {

                    mobileOverlay.addEventListener(
                        'click',
                        closeMobileMenu
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | ESC
                |--------------------------------------------------------------------------
                */

                document.addEventListener(
                    'keydown',
                    function(event) {

                        if (
                            event.key === 'Escape'
                        ) {

                            closeMobileMenu();

                        }

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | CLOSE AFTER CLICK LINK
                |--------------------------------------------------------------------------
                */

                if (mobileMenu) {

                    const links =
                        mobileMenu.querySelectorAll(
                            'a'
                        );


                    links.forEach(
                        function(link) {

                            link.addEventListener(
                                'click',
                                closeMobileMenu
                            );

                        }
                    );

                }

            }
        );
    </script>


</body>

</html>
