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
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area (shifted right for sidebar) -->
            <div class="main-content-wrapper">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white border-b border-gray-200 shadow-sm">
                        <div class="px-6 py-4">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <style>
            /* Adjust main content to work with sidebar */
            .main-content-wrapper {
                margin-left: 0px;
                transition: margin-left 0.3s ease;
                min-height: 100vh;
            }

            @media (max-width: 768px) {
                .main-content-wrapper {
                    margin-left: 0;
                }
            }
        </style>
    </body>
</html>