<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f8f4">
    <meta name="description" content="@yield('meta_description', 'Belajar energi bersih dan terjangkau bersama SDG7Learn.')">
    <title>@yield('title', 'SDG7Learn') — Energi Bersih untuk Semua</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="site-shell">
        @include('layouts.header')

        <main id="app" tabindex="-1">
            <div class="page-wrap" style="padding-bottom: 40px;">
                @include('components.alert')
                @yield('content')
            </div>
        </main>

        @include('layouts.footer')
    </div>

    @stack('scripts')
</body>
</html>
