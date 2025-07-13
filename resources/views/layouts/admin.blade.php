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
    <body class="font-inter antialiased bg-volume-light">
        <!-- Admin Top Navigation -->
        @include('components.admin-top-nav')
        
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <div class="fixed top-12 bottom-0 left-0 z-40 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0" id="sidebar">
                <div class="flex items-center justify-center h-16 px-4 bg-volume-secondary">
                    <h1 class="font-poppins text-xl font-bold text-white">Admin Panel</h1>
                </div>
                
                <nav class="mt-8">
                    <div class="px-4 space-y-2">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-volume-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            Dashboard
                        </a>

                        <!-- Leads -->
                        <a href="{{ route('admin.leads') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.leads*') ? 'bg-volume-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Manage Leads
                        </a>

                        <!-- Commissions -->
                        <a href="{{ route('admin.commissions') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.commissions*') ? 'bg-volume-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Manage Commissions
                        </a>

                        <!-- Commission Settings -->
                        <a href="{{ route('admin.commission-settings') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.commission-settings*') ? 'bg-volume-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Commission Settings
                        </a>

                        <!-- Pipeline Analytics -->
                        <a href="{{ route('admin.pipeline-analytics') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.pipeline-analytics*') ? 'bg-volume-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Pipeline Analytics
                        </a>

                        <!-- Webhooks -->
                        <a href="{{ route('admin.webhooks') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.webhooks*') ? 'bg-volume-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                            Webhooks
                        </a>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 my-4"></div>

                        <!-- Content Management -->
                        <div class="px-4 py-2">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Content Management</h3>
                        </div>

                        <!-- Landing Page -->
                        <a href="{{ route('admin.landing-page') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.landing-page*') ? 'bg-volume-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Landing Page
                        </a>

                        <!-- Referral Page -->
                        <a href="{{ route('admin.referral-page') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.referral-page*') ? 'bg-volume-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            Referral Page
                        </a>
                    </div>
                </nav>

                <!-- User Menu at Bottom -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-volume-primary rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile sidebar overlay -->
            <div class="fixed inset-0 z-30 bg-gray-600 bg-opacity-75 transition-opacity lg:hidden hidden" id="sidebar-overlay"></div>

            <!-- Main content -->
            <div class="flex-1 lg:ml-64 pt-12">
                <!-- Mobile header -->
                <div class="lg:hidden bg-white shadow-sm border-b border-gray-200">
                    <div class="flex items-center justify-between px-4 py-3">
                        <button type="button" class="text-gray-500 hover:text-gray-600" id="mobile-menu-button">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="text-lg font-semibold text-gray-900">Admin Panel</h1>
                        <div class="w-6"></div>
                    </div>
                </div>

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
        </div>

        <script>
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            mobileMenuButton?.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            });

            sidebarOverlay?.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            });
        </script>
    </body>
</html>
