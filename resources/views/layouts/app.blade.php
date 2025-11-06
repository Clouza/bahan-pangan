<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>

<body class="relative flex h-auto min-h-screen w-full flex-col bg-white group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="px-4 md:px-10 flex flex-1 justify-center py-5">
            <div class="layout-content-container flex flex-col w-full flex-1">
                @if (isset($header))
                    <div class="flex flex-wrap justify-between gap-3 p-4">
                        @yield('header')
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>

</html>

