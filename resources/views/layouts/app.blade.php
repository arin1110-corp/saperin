<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'SAMPERIN')
    </title>


    {{-- TAILWIND CDN --}}

    <script src="https://cdn.tailwindcss.com"></script>


    {{-- ICON --}}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>


<body class="
        bg-slate-100
        text-slate-800
    ">


    <div class="
        min-h-screen
        flex
    ">


        {{-- SIDEBAR --}}

        <aside
            class="
            hidden
            md:flex
            w-64
            bg-slate-900
            text-white
            flex-col
        ">


            {{-- LOGO --}}

            <div
                class="
                px-6
                py-6
                border-b
                border-slate-800
            ">

                <div class="
                    text-2xl
                    font-bold
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
                    Pegawai Internal

                </div>

            </div>


            {{-- MENU --}}

            <nav
                class="
                flex-1
                px-4
                py-6
                space-y-1
            ">


                <a href="{{ route('pegawai.dashboard') }}"
                    class="
                    flex
                    items-center
                    gap-3
                    px-4
                    py-3
                    rounded-lg
                    hover:bg-slate-800
                ">

                    <i class="bi bi-grid"></i>

                    Dashboard

                </a>


                @php

                    $user = request()->attributes->get('samperin_user');

                @endphp


                {{-- KEPEGAWAIAN --}}

                @if ($user && $user->hasAnyRole(['admin', 'kepeg']))
                    <a href="{{ route('kepeg.dashboard') }}"
                        class="
                        flex
                        items-center
                        gap-3
                        px-4
                        py-3
                        rounded-lg
                        hover:bg-slate-800
                    ">

                        <i class="bi bi-people"></i>

                        Kepegawaian

                    </a>
                @endif


                {{-- ADMIN --}}

                @if ($user && $user->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}"
                        class="
                        flex
                        items-center
                        gap-3
                        px-4
                        py-3
                        rounded-lg
                        hover:bg-slate-800
                    ">

                        <i class="bi bi-shield-lock"></i>

                        Administrator

                    </a>
                @endif

            </nav>


            {{-- USER INFO --}}

            <div class="
                p-4
                border-t
                border-slate-800
            ">

                @if ($user)

                    <div
                        class="
                        text-sm
                        font-medium
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
                            text-[10px]
                            px-2
                            py-1
                            rounded
                            bg-slate-800
                        ">

                            Pegawai

                        </span>


                        {{-- ROLE TAMBAHAN --}}

                        @foreach ($user->roles as $role)
                            <span
                                class="
                                text-[10px]
                                px-2
                                py-1
                                rounded
                                bg-blue-900
                            ">

                                {{ $role->role_nama }}

                            </span>
                        @endforeach

                    </div>

                @endif

            </div>


        </aside>


        {{-- MAIN --}}

        <main class="
            flex-1
            min-w-0
        ">


            <header
                class="
                bg-white
                border-b
                border-slate-200
                px-6
                py-4
            ">

                <h1 class="
                    font-semibold
                    text-lg
                ">

                    @yield('page-title')

                </h1>

            </header>


            <section class="
                p-6
            ">

                @yield('content')

            </section>


        </main>


    </div>


</body>

</html>
