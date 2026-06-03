<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SellSync-POS — Smart Point of Sale</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
                /* [Keeping your existing Tailwind base styles here - they remain the same] */
            </style>
        @endif

        <style>
            /* SellSync-POS Brand Styles */
            :root {
                --brand-blue: #1a56db;
                --brand-blue-light: #eff4ff;
                --brand-blue-dark: #1a3fad;
                --brand-teal: #0d9488;
                --brand-teal-light: #f0fdfa;
                --brand-indigo: #7c3aed;
                --brand-indigo-light: #f5f3ff;
                --brand-green: #16a34a;
                --brand-green-light: #f0fdf4;
                --brand-red: #dc2626;
                --brand-red-light: #fef2f2;
            }

            * { box-sizing: border-box; }

            body {
                font-family: 'Outfit', sans-serif;
                background: linear-gradient(135deg, #fafbff 0%, #eff4ff 100%);
                margin: 0;
                min-height: 100vh;
            }

            .welcome-container {
                width: 100%;
                max-width: 1100px;
                margin: 0 auto;
                padding: 20px 16px;
            }
            @media(min-width: 640px) { .welcome-container { padding: 28px 24px; } }
            @media(min-width: 1024px) { .welcome-container { padding: 32px; } }

            /* ── Header ── */
            .welcome-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 16px;
                margin-bottom: 28px;
            }
            @media(min-width: 640px) { .welcome-header { margin-bottom: 36px; } }
            @media(min-width: 1024px) { .welcome-header { margin-bottom: 48px; } }

            .logo-wrap {
                display: flex;
                align-items: center;
                gap: 12px;
            }
            .logo-icon {
                width: 38px;
                height: 38px;
                background: #eff4ff;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            @media(min-width: 640px) { .logo-icon { width: 42px; height: 42px; border-radius: 12px; } }
            .logo-icon svg {
                width: 20px;
                height: 20px;
                color: #1a56db;
            }
            @media(min-width: 640px) { .logo-icon svg { width: 22px; height: 22px; } }

            .logo-text {
                font-family: 'Outfit', sans-serif;
                font-size: 18px;
                font-weight: 700;
                color: #111827;
                line-height: 1;
            }
            @media(min-width: 640px) { .logo-text { font-size: 20px; } }

            .logo-sub {
                font-family: 'JetBrains Mono', monospace;
                font-size: 10px;
                color: #6b7280;
                font-weight: 500;
                letter-spacing: 0.05em;
            }
            @media(min-width: 640px) { .logo-sub { font-size: 11px; } }

            .auth-nav {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }
            @media(min-width: 640px) { .auth-nav { gap: 12px; } }

            /* ── Buttons ── */
            .btn-primary {
                background: #1a56db;
                color: white;
                padding: 9px 18px;
                border-radius: 10px;
                font-size: 12px;
                font-weight: 600;
                text-decoration: none;
                font-family: 'Outfit', sans-serif;
                letter-spacing: 0.01em;
                box-shadow: 0 2px 8px rgba(26, 86, 219, 0.25);
                transition: all 0.15s;
                display: inline-block;
                white-space: nowrap;
            }
            @media(min-width: 640px) { .btn-primary { padding: 10px 22px; font-size: 13px; } }
            .btn-primary:hover {
                background: #1e40af;
                box-shadow: 0 4px 12px rgba(26, 86, 219, 0.35);
                transform: translateY(-1px);
            }

            .btn-secondary {
                background: transparent;
                color: #1a56db;
                padding: 9px 18px;
                border-radius: 10px;
                font-size: 12px;
                font-weight: 600;
                text-decoration: none;
                font-family: 'Outfit', sans-serif;
                letter-spacing: 0.01em;
                border: 1.5px solid #1a56db;
                transition: all 0.15s;
                display: inline-block;
                white-space: nowrap;
            }
            @media(min-width: 640px) { .btn-secondary { padding: 10px 22px; font-size: 13px; } }
            .btn-secondary:hover {
                background: #eff4ff;
            }

            .btn-hero {
                font-size: 13px;
                padding: 11px 24px;
            }
            @media(min-width: 640px) { .btn-hero { font-size: 14px; padding: 12px 28px; } }

            /* ── Hero ── */
            .hero-section {
                text-align: center;
                margin-bottom: 32px;
            }
            @media(min-width: 640px) { .hero-section { margin-bottom: 40px; } }
            @media(min-width: 1024px) { .hero-section { margin-bottom: 48px; } }

            .hero-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #eff4ff;
                padding: 5px 14px;
                border-radius: 99px;
                margin-bottom: 16px;
            }
            @media(min-width: 640px) { .hero-badge { padding: 6px 16px; margin-bottom: 20px; } }
            .hero-badge-dot {
                width: 7px;
                height: 7px;
                border-radius: 50%;
                background: #1a56db;
                display: inline-block;
            }
            @media(min-width: 640px) { .hero-badge-dot { width: 8px; height: 8px; } }
            .hero-badge-text {
                font-size: 10px;
                font-weight: 600;
                color: #1a56db;
                font-family: 'Outfit', sans-serif;
                letter-spacing: 0.02em;
            }
            @media(min-width: 640px) { .hero-badge-text { font-size: 12px; } }

            .hero-title {
                font-family: 'Outfit', sans-serif;
                font-size: 28px;
                font-weight: 700;
                color: #111827;
                line-height: 1.15;
                margin-bottom: 12px;
            }
            @media(min-width: 480px) { .hero-title { font-size: 32px; } }
            @media(min-width: 640px) { .hero-title { font-size: 38px; margin-bottom: 16px; } }
            @media(min-width: 1024px) { .hero-title { font-size: 42px; } }

            .hero-subtitle {
                font-family: 'Outfit', sans-serif;
                font-size: 14px;
                color: #6b7280;
                line-height: 1.6;
                max-width: 560px;
                margin: 0 auto 20px;
                padding: 0 10px;
            }
            @media(min-width: 640px) { .hero-subtitle { font-size: 16px; margin-bottom: 28px; } }

            .hero-buttons {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                flex-wrap: wrap;
            }
            @media(min-width: 640px) { .hero-buttons { gap: 12px; } }

            /* ── Feature Cards ── */
            .features-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 12px;
                margin-bottom: 16px;
            }
            @media(min-width: 480px) { .features-grid { grid-template-columns: repeat(2, 1fr); } }
            @media(min-width: 768px) { .features-grid { grid-template-columns: repeat(4, 1fr); gap: 16px; } }

            .brand-card {
                background: #ffffff;
                border: 1px solid #e4e7ef;
                border-radius: 14px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
                transition: all 0.15s ease;
                padding: 20px 16px;
                text-align: center;
            }
            @media(min-width: 768px) { .brand-card { padding: 24px 20px; } }
            .brand-card:hover {
                border-color: #1a56db;
                box-shadow: 0 4px 12px rgba(26, 86, 219, 0.1);
            }

            .feature-icon {
                width: 42px;
                height: 42px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 14px;
            }
            @media(min-width: 768px) { .feature-icon { width: 48px; height: 48px; border-radius: 12px; margin-bottom: 16px; } }
            .feature-icon svg {
                width: 20px;
                height: 20px;
            }
            @media(min-width: 768px) { .feature-icon svg { width: 24px; height: 24px; } }

            .icon-blue { background: #eff4ff; }
            .icon-blue svg { color: #1a56db; }
            .icon-teal { background: #f0fdfa; }
            .icon-teal svg { color: #0d9488; }
            .icon-indigo { background: #f5f3ff; }
            .icon-indigo svg { color: #7c3aed; }
            .icon-green { background: #f0fdf4; }
            .icon-green svg { color: #16a34a; }

            .feature-title {
                font-family: 'Outfit', sans-serif;
                font-size: 13px;
                font-weight: 700;
                color: #111827;
                margin-bottom: 4px;
            }
            @media(min-width: 768px) { .feature-title { font-size: 14px; margin-bottom: 6px; } }

            .feature-desc {
                font-family: 'Outfit', sans-serif;
                font-size: 11px;
                color: #9ca3af;
                line-height: 1.5;
            }
            @media(min-width: 768px) { .feature-desc { font-size: 12px; } }

            /* ── Bottom CTA ── */
            .cta-card {
                background: #ffffff;
                border: 1px solid #c7d7fb;
                border-radius: 14px;
                padding: 22px 20px;
                text-align: center;
                background: linear-gradient(135deg, #eff4ff 0%, #f0fdfa 100%);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            }
            @media(min-width: 640px) { .cta-card { padding: 28px 32px; } }

            .cta-title {
                font-family: 'Outfit', sans-serif;
                font-size: 15px;
                font-weight: 700;
                color: #111827;
                margin-bottom: 6px;
            }
            @media(min-width: 640px) { .cta-title { font-size: 16px; margin-bottom: 8px; } }

            .cta-desc {
                font-family: 'Outfit', sans-serif;
                font-size: 12px;
                color: #6b7280;
                margin-bottom: 14px;
            }
            @media(min-width: 640px) { .cta-desc { font-size: 13px; margin-bottom: 16px; } }

            .cta-note {
                font-family: 'Outfit', sans-serif;
                font-size: 10px;
                color: #9ca3af;
                margin-top: 8px;
            }
            @media(min-width: 640px) { .cta-note { font-size: 11px; margin-top: 10px; } }

            /* ── Footer ── */
            .welcome-footer {
                text-align: center;
                padding: 20px 0 0;
            }
            @media(min-width: 640px) { .welcome-footer { padding: 24px 0 0; } }
            .welcome-footer p {
                font-family: 'Outfit', sans-serif;
                font-size: 11px;
                color: #9ca3af;
            }
            @media(min-width: 640px) { .welcome-footer p { font-size: 12px; } }
        </style>
    </head>
    <body>
        <div class="welcome-container">

            {{-- Header / Navigation --}}
            <header class="welcome-header">
                {{-- Logo --}}
                <div class="logo-wrap">
                    <div class="logo-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="logo-text">SellSync</div>
                        <div class="logo-sub">POS</div>
                    </div>
                </div>

                {{-- Auth Buttons --}}
                @if (Route::has('login'))
                    <nav class="auth-nav">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-secondary">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-primary">Start Free Trial</a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>

            {{-- Hero Section --}}
            <main>
                <div class="hero-section">
                    <div class="hero-badge">
                        <span class="hero-badge-dot"></span>
                        <span class="hero-badge-text">SMART POS SYSTEM</span>
                    </div>
                    <h1 class="hero-title">
                        Point of Sale.<br>Perfected.
                    </h1>
                    <p class="hero-subtitle">
                        SellSync-POS gives you everything you need to run your retail business — 
                        inventory, sales, customers, and reports — all in one beautiful dashboard.
                    </p>
                    <div class="hero-buttons">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary btn-hero">Get Started Free →</a>
                        @endif
                        <a href="{{ route('login') }}" class="btn-secondary btn-hero">Sign In</a>
                    </div>
                </div>

                {{-- Feature Cards --}}
                <div class="features-grid">
                    {{-- POS Terminal --}}
                    <div class="brand-card">
                        <div class="feature-icon icon-blue">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="feature-title">POS Terminal</div>
                        <p class="feature-desc">Fast, intuitive checkout with barcode scanning & receipt printing.</p>
                    </div>

                    {{-- Inventory --}}
                    <div class="brand-card">
                        <div class="feature-icon icon-teal">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div class="feature-title">Inventory Management</div>
                        <p class="feature-desc">Track stock levels, manage suppliers, and get low-stock alerts instantly.</p>
                    </div>

                    {{-- Customers --}}
                    <div class="brand-card">
                        <div class="feature-icon icon-indigo">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="feature-title">Customer Profiles</div>
                        <p class="feature-desc">Build customer loyalty with profiles, purchase history, and credit management.</p>
                    </div>

                    {{-- Reports --}}
                    <div class="brand-card">
                        <div class="feature-icon icon-green">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="feature-title">Smart Reports</div>
                        <p class="feature-desc">Real-time P&L, sales analytics, and cashier performance at your fingertips.</p>
                    </div>
                </div>

                {{-- Bottom CTA --}}
                <div class="cta-card">
                    <div class="cta-title">Ready to streamline your retail business?</div>
                    <p class="cta-desc">
                        Join thousands of retailers using SellSync-POS to manage sales, inventory, and customers.
                    </p>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary btn-hero">Create Free Account</a>
                    @endif
                    <div class="cta-note">No credit card required · 14-day free trial</div>
                </div>
            </main>

            {{-- Footer --}}
            <footer class="welcome-footer">
                <p>© {{ date('Y') }} SellSync-POS. All rights reserved.</p>
            </footer>
        </div>
    </body>
</html>