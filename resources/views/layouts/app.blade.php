<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Volume Up Agency') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --volume-up-primary: #00B9F1;
                --volume-up-secondary: #003F66;
                --volume-up-light: #F8FEFF;
                --volume-up-gray: #6B7280;
                --volume-up-dark: #1F2937;
            }
            
            .bg-volume-primary { background-color: var(--volume-up-primary); }
            .bg-volume-secondary { background-color: var(--volume-up-secondary); }
            .bg-volume-light { background-color: var(--volume-up-light); }
            .text-volume-primary { color: var(--volume-up-primary); }
            .text-volume-secondary { color: var(--volume-up-secondary); }
            .text-volume-dark { color: var(--volume-up-dark); }
            .border-volume-primary { border-color: var(--volume-up-primary); }
            .hover\:bg-volume-primary:hover { background-color: var(--volume-up-primary); }
            .hover\:text-volume-primary:hover { color: var(--volume-up-primary); }
            .focus\:ring-volume-primary:focus { --tw-ring-color: var(--volume-up-primary); }
        </style>
    </head>
    <body class="font-inter antialiased bg-volume-light">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm border-b border-gray-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="pb-12">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
