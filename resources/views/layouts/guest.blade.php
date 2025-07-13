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
    </head>
    <body class="font-sans text-volume-dark antialiased bg-gradient-to-br from-volume-light via-white to-blue-50">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-6">
            <!-- Logo Section -->
            <div class="mb-6 animate-fade-in">
                <a href="/" class="group">
                    <x-application-logo class="w-auto h-12 transition-transform duration-300 group-hover:scale-105" />
                </a>
            </div>

            <!-- Main Card -->
            <div class="w-full max-w-md animate-slide-up">
                <div class="bg-white/80 backdrop-blur-sm shadow-2xl border border-white/20 rounded-3xl p-8 relative overflow-hidden">
                    <!-- Decorative gradient -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-volume-primary via-volume-secondary to-volume-primary"></div>
                    
                    {{ $slot }}
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-center animate-fade-in">
                <p class="text-sm text-volume-gray/80">
                    &copy; {{ date('Y') }} Volume Up Agency. All rights reserved.
                </p>
                <div class="mt-2 flex items-center justify-center space-x-4 text-xs text-volume-gray/60">
                    <a href="#" class="hover:text-volume-primary transition-colors duration-200">Privacy Policy</a>
                    <span>•</span>
                    <a href="#" class="hover:text-volume-primary transition-colors duration-200">Terms of Service</a>
                    <span>•</span>
                    <a href="#" class="hover:text-volume-primary transition-colors duration-200">Support</a>
                </div>
            </div>
        </div>

        <!-- Background decoration -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-volume-primary/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-volume-secondary/5 rounded-full blur-3xl"></div>
        </div>
    </body>
</html>
