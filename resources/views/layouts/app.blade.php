<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SellSync-POS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
            .min-h-screen.bg-gray-100 {
                background: #f8f9fc !important;
            }
            .main-content-wrapper {
                margin-left: 260px;
                transition: margin-left 0.3s ease;
                min-height: 100vh;
            }
            @media (max-width: 768px) {
                .main-content-wrapper {
                    margin-left: 0;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="main-content-wrapper">
                <!-- Page Heading -->
                @isset($header)
                    <header style="background:#ffffff;border-bottom:1px solid #e4e7ef">
                        <div style="padding:16px 24px">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main style="padding:20px 24px">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>