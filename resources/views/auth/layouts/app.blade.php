<!DOCTYPE html>
<html lang="id">

<head>

    @include('auth.partials.head')

    @include('auth.partials.styles')

    @stack('styles')

</head>

<body>

    @yield('content')

    @stack('scripts')

</body>

</html>
