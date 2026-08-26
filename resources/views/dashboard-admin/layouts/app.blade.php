<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin SAMPERIN')
    </title>


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

            background: #f5f7fa;

            color: #0f172a;

        }


        /*
        |--------------------------------------------------------------------------
        | SCROLLBAR
        |--------------------------------------------------------------------------
        */

        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }


        /*
        |--------------------------------------------------------------------------
        | SIDEBAR
        |--------------------------------------------------------------------------
        */

        .admin-sidebar {

            width: 250px;

            flex-shrink: 0;

            background: #ffffff;

            border-right: 1px solid #e5e7eb;

            transition:
                width .2s ease,
                transform .2s ease;

        }


        /*
        |--------------------------------------------------------------------------
        | MAIN
        |--------------------------------------------------------------------------
        */

        .admin-main {

            min-width: 0;

            flex: 1;

        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .admin-header {

            height: 64px;

            background: #ffffff;

            border-bottom: 1px solid #e5e7eb;

        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE SIDEBAR
        |--------------------------------------------------------------------------
        */

        @media (max-width: 900px) {

            .admin-sidebar {

                position: fixed;

                top: 0;

                bottom: 0;

                left: 0;

                z-index: 50;

                transform:
                    translateX(-100%);

                box-shadow:
                    10px 0 35px rgba(15, 23, 42, .10);

            }


            .admin-sidebar.open {

                transform:
                    translateX(0);

            }


            .admin-overlay {

                position: fixed;

                inset: 0;

                z-index: 40;

                background:
                    rgba(15, 23, 42, .35);

                display: none;

            }


            .admin-overlay.show {

                display: block;

            }

        }
    </style>


    @stack('styles')

</head>


<body>


    <div class="
        flex
        min-h-screen
        w-full
    ">


        {{-- =========================================================
        SIDEBAR
    ========================================================== --}}

        <aside id="adminSidebar" class="admin-sidebar">

            @include('dashboard-admin.partials.sidebar')

        </aside>


        {{-- =========================================================
        MOBILE OVERLAY
    ========================================================== --}}

        <div id="adminOverlay" class="admin-overlay"></div>


        {{-- =========================================================
        MAIN
    ========================================================== --}}

        <main class="admin-main">


            {{-- HEADER --}}

            @include('dashboard-admin.partials.header')


            {{-- CONTENT --}}

            <div
                class="
                min-h-[calc(100vh-64px)]
                p-5

                md:p-7

                lg:p-8
            ">

                @yield('content')

            </div>

        </main>

    </div>


    <script>
        const sidebar =
            document.getElementById(
                'adminSidebar'
            );

        const overlay =
            document.getElementById(
                'adminOverlay'
            );

        const menuButton =
            document.getElementById(
                'adminMenuButton'
            );


        function openSidebar() {
            if (!sidebar) {
                return;
            }

            sidebar.classList.add(
                'open'
            );

            if (overlay) {
                overlay.classList.add(
                    'show'
                );
            }
        }


        function closeSidebar() {
            if (!sidebar) {
                return;
            }

            sidebar.classList.remove(
                'open'
            );

            if (overlay) {
                overlay.classList.remove(
                    'show'
                );
            }
        }


        if (menuButton) {

            menuButton.addEventListener(
                'click',
                openSidebar
            );

        }


        if (overlay) {

            overlay.addEventListener(
                'click',
                closeSidebar
            );

        }
    </script>


    @stack('scripts')

</body>

</html>
