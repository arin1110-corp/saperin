<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
    @yield('title', 'SAMPERIN')
</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="icon" href="{{ asset('assets/images/logo-samperin.png') }}">

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
