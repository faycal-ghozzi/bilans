<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BTL-Synthèse') }}</title>
        <link rel="icon" type="image/png" sizes="32x32" href={{ asset('images/favicon.png') }}>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite('resources/js/cdn-scripts/jquery-3.6.0.js')
        @vite('resources/js/cdn-scripts/jquery-steps.js')
        @vite('resources/js/cdn-scripts/jquery-validate.js')
        @vite('resources/js/cdn-scripts/chart.js')
        @vite('resources/js/chart-extension.js')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @vite('resources/js/stepper_conf.js')
        @vite('resources/js/calc_actifs.js')
        @vite('resources/js/calc_passifs.js')
        @vite('resources/js/etat_resultat.js')
        @vite('resources/js/financialStatements.js')

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex flex-col">
            <div class="sticky top-0 z-50 bg-[rgb(23,48,35)] shadow">
                @include('layouts.navigation')
            </div>
    
            <div class="flex flex-1">
                @if(View::hasSection('sidebar'))
                    @yield('sidebar')
                @endif
    
                <div class="flex-1 @hasSection('sidebar') ml-64 @endif">
                    @if (isset($header))
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif
    
                    <main class="container mx-auto py-8">
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
