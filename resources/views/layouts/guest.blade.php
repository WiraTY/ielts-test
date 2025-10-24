<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#4f46e5">
        <meta name="application-name" content="Trial Class App">
        <meta name="apple-mobile-web-app-title" content="TrialClass">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="description" content="Educational platform for trial classes with lessons and quizzes">

        <!-- PWA Icons -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/icons/icon-192x192.png') }}">
        <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('images/icons/icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/icons/icon-512x512.png') }}">
        <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('images/icons/icon-512x512.png') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- PWA Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js')
                        .then(function(registration) {
                            console.log('ServiceWorker registration successful with scope: ', registration.scope);
                        })
                        .catch(function(error) {
                            console.log('ServiceWorker registration failed: ', error);
                        });
                });
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/" wire:navigate>
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
