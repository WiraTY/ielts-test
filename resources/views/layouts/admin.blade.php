@php
use Illuminate\Support\Facades\Auth;
@endphp

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
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-200">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('admin.dashboard') }}">
                                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                    {{ __('Users') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')">
                                    {{ __('Courses') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.levels.index')" :active="request()->routeIs('admin.levels.*')">
                                    {{ __('Levels') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                                    {{ __('Reports') }}
                                </x-nav-link>
                                
                                <x-nav-link :href="route('admin.placement-tests.index')" :active="request()->routeIs('admin.placement-tests.*')">
                                    {{ __('Placement Tests') }}
                                </x-nav-link>
                                
                                <!-- View as Student -->
                                <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*') || request()->routeIs('lessons.*') || request()->routeIs('quizzes.*')">
                                    {{ __('View as Student') }}
                                </x-nav-link>
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="relative">
                                <div id="admin-user-menu-button">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </div>

                                <div id="admin-user-dropdown-menu" class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0 hidden">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5 bg-white py-1">
                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <x-dropdown-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button id="admin-mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path id="admin-mobile-menu-open" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path id="admin-mobile-menu-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div id="admin-mobile-menu" class="hidden sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Users') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')">
                            {{ __('Courses') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.levels.index')" :active="request()->routeIs('admin.levels.*')">
                            {{ __('Levels') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                            {{ __('Reports') }}
                        </x-responsive-nav-link>
                        
                        <x-responsive-nav-link :href="route('admin.placement-tests.index')" :active="request()->routeIs('admin.placement-tests.*')">
                            {{ __('Placement Tests') }}
                        </x-responsive-nav-link>
                        
                        <!-- View as Student -->
                        <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*') || request()->routeIs('lessons.*') || request()->routeIs('quizzes.*')">
                            {{ __('View as Student') }}
                        </x-responsive-nav-link>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-responsive-nav-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Mobile menu toggle
                const mobileMenuButton = document.getElementById('admin-mobile-menu-button');
                const mobileMenu = document.getElementById('admin-mobile-menu');
                const openIcon = document.getElementById('admin-mobile-menu-open');
                const closeIcon = document.getElementById('admin-mobile-menu-close');
                
                if (mobileMenuButton && mobileMenu) {
                    mobileMenuButton.addEventListener('click', function() {
                        // Toggle menu visibility
                        mobileMenu.classList.toggle('hidden');
                        mobileMenu.classList.toggle('block');
                        
                        // Toggle icon
                        openIcon.classList.toggle('hidden');
                        openIcon.classList.toggle('inline-flex');
                        closeIcon.classList.toggle('hidden');
                        closeIcon.classList.toggle('inline-flex');
                    });
                }
                
                // Admin user dropdown menu
                const userMenuButton = document.getElementById('admin-user-menu-button');
                const userDropdownMenu = document.getElementById('admin-user-dropdown-menu');
                
                if (userMenuButton && userDropdownMenu) {
                    userMenuButton.addEventListener('click', function(e) {
                        e.stopPropagation();
                        userDropdownMenu.classList.toggle('hidden');
                    });
                    
                    // Close dropdown when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!userMenuButton.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                            userDropdownMenu.classList.add('hidden');
                        }
                    });
                }
            });
        </script>
    </body>
</html>