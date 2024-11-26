<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite('resources/js/cdn-scripts/jquery-3.6.0.js')
        @vite('resources/js/cdn-scripts/jquery-steps.js')
        @vite('resources/js/cdn-scripts/jquery-validate.js')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @vite('resources/js/stepper_conf.js')
        @vite('resources/js/calc_actifs.js')
        @vite('resources/js/calc_passifs.js')
        @vite('resources/js/etat_resultat.js')
        @vite('resources/js/financialStatements.js')

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex">
            <!-- Sidebar -->
            @hasSection('sidebar')
                @yield('sidebar')
            @endif
    
            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col">
                <!-- Navigation Bar -->
                @include('layouts.navigation')
    
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif
    
                <!-- Page Content -->
                <main class="container mx-auto py-8">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
