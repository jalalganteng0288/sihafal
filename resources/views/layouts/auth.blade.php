<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SiHafal')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">
        @yield('content')

        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} SiHafal — Pondok Pesantren Attaupiqillah
        </p>
    </div>

    @stack('scripts')
</body>
</html>
