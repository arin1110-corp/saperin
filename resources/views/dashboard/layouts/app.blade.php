<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'SAMPERIN')
    </title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            background: #f5f7fa;
            color: #172238;
            font-family: "Plus Jakarta Sans", Arial, sans-serif;
            font-size: 15px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button,
        input,
        select,
        textarea {
            font-family: inherit;
        }

        .admin-main {
            margin-left: 255px;
            min-height: 100vh;
        }

        .admin-content {
            padding: 30px 32px 42px;
        }

        .admin-alert {
            margin: 20px 32px 0;
            padding: 14px 17px;
            border-radius: 10px;
            font-size: 12px;
        }

        .admin-alert-success {
            background: #edf9f1;
            border: 1px solid #cfeeda;
            color: #287448;
        }

        .admin-alert-error {
            background: #fff1f1;
            border: 1px solid #f1d0d0;
            color: #a74747;
        }

        @media (max-width: 850px) {

            .admin-main {
                margin-left: 72px;
            }

            .admin-content {
                padding: 24px 20px 35px;
            }

            .admin-alert {
                margin-left: 20px;
                margin-right: 20px;
            }

        }
    </style>

    @yield('page-style')

</head>

<body>

    @include('dashboard.partials.sidebar')

    <main class="admin-main">

        @include('dashboard.partials.header')

        @if (session('success'))
            <div class="admin-alert admin-alert-success">

                <i class="bi bi-check-circle-fill"></i>

                &nbsp;

                {{ session('success') }}

            </div>
        @endif

        @if (session('error'))
            <div class="admin-alert admin-alert-error">

                <i class="bi bi-exclamation-circle-fill"></i>

                &nbsp;

                {{ session('error') }}

            </div>
        @endif

        @yield('content')

    </main>

    @yield('page-script')

</body>

</html>
