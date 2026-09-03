<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'SAMPERIN')
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="favicon" href="{{ asset('images/logo-samperin.png') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f6f8;
            color: #182238;
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .dashboard-main {
            margin-left: 255px;
            min-height: 100vh;
        }

        .dashboard-header {
            height: 76px;
            background: #fff;
            border-bottom: 1px solid #e8ebef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .dashboard-header-title {
            font-size: 17px;
            font-weight: 800;
        }

        .dashboard-header-breadcrumb {
            margin-top: 3px;
            font-size: 10px;
            color: #9aa2ae;
        }

        .dashboard-content {
            padding: 28px 30px 40px;
        }

        @media(max-width:850px) {

            .dashboard-main {
                margin-left: 72px;
            }

            .dashboard-header {
                padding: 0 20px;
            }

            .dashboard-content {
                padding: 20px;
            }

        }
    </style>

    @yield('page-style')

</head>

<body>

    @include('dashboard.partials.sidebar')

    <main class="dashboard-main">

        <header class="dashboard-header">

            <div>

                <div class="dashboard-header-title">

                    @yield('header-title', 'SAMPERIN')

                </div>

                <div class="dashboard-header-breadcrumb">

                    @yield('breadcrumb', 'Dashboard')

                </div>

            </div>

        </header>


        <div class="dashboard-content">

            @yield('content')

        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @yield('page-script')

</body>

</html>
