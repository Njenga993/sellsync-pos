<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- SEO Meta Tags --}}
        <title>SellSync-POS — Smart Point of Sale for African Retailers</title>
        <meta name="description" content="SellSync-POS is a powerful point of sale system for retail shops, supermarkets, pharmacies, and restaurants. Manage sales, inventory, customers, and reports all in one place.">
        <meta name="keywords" content="POS system, point of sale, retail POS, inventory management, sales tracking, Kenya POS, African POS, supermarket POS, pharmacy POS">
        <meta name="author" content="SellSync-POS">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="https://sellsync-pos-production.up.railway.app">
        
        {{-- Open Graph --}}
        <meta property="og:title" content="SellSync-POS — Smart Point of Sale for African Retailers">
        <meta property="og:description" content="Manage sales, inventory, customers, and reports all in one powerful POS system.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://sellsync-pos-production.up.railway.app">
        <meta property="og:image" content="{{ asset('images/sellsync-og.png') }}">
        
        {{-- Twitter Card --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="SellSync-POS — Smart Point of Sale">
        <meta name="twitter:description" content="Manage sales, inventory, customers, and reports all in one powerful POS system.">

        {{-- Favicon --}}
        <link rel="icon" type="image/png" href="{{ asset('images/sellsyncLogo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400;500;600;700;800&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400;500;600;700&display=swap" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            :root {
                --g: #03A737; --gl: #e6f7eb; --gd: #028a2e;
                --b: #3D7BE7; --bl: #edf3fd;
                --k: #02182F; --kl: #f0f2f5;
                --w: #FFFEFE; --br: #e4e7ef;
                --t1: #02182F; --t2: #4b5563; --t3: #6b7280; --t4: #9ca3af;
            }

            * { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                font-family: 'Outfit', sans-serif;
                background: var(--w);
                color: var(--t1);
                line-height: 1.6;
                overflow-x: hidden;
            }

            .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

            /* ── Navigation ── */
            .nav { 
                position: sticky; top: 0; z-index: 100;
                background: rgba(255,254,254,0.92); backdrop-filter: blur(12px);
                border-bottom: 1px solid var(--br);
            }
            .nav-inner { 
                display: flex; align-items: center; justify-content: space-between;
                max-width: 1200px; margin: 0 auto; padding: 14px 24px;
                gap: 16px; flex-wrap: wrap;
            }
            .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
            .nav-logo img { width: 40px; height: 40px; border-radius: 10px; object-fit: contain; }
            .nav-logo-text { font-size: 17px; font-weight: 800; color: var(--k); letter-spacing: -0.01em; }
            .nav-logo-sub { 
                font-family: 'JetBrains Mono', monospace; font-size: 10px; 
                color: var(--g); font-weight: 700; letter-spacing: 0.1em;
            }
            .nav-links { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
            .nav-link { 
                font-size: 13px; font-weight: 500; color: var(--t2); text-decoration: none;
                padding: 6px 12px; border-radius: 8px; transition: all .15s;
            }
            .nav-link:hover { color: var(--g); background: var(--gl); }

            .btn { 
                padding: 10px 22px; border-radius: 10px; font-size: 13px; font-weight: 600;
                text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
                transition: all .15s; cursor: pointer; border: none;
                font-family: 'Outfit', sans-serif; white-space: nowrap;
            }
            .btn-primary { background: var(--g); color: var(--w); box-shadow: 0 2px 8px rgba(3,167,55,.25); }
            .btn-primary:hover { background: var(--gd); box-shadow: 0 4px 14px rgba(3,167,55,.35); transform: translateY(-1px); }
            .btn-outline { background: transparent; color: var(--g); border: 1.5px solid var(--g); }
            .btn-outline:hover { background: var(--gl); }
            .btn-lg { padding: 14px 32px; font-size: 15px; border-radius: 12px; }

            /* ── Sections ── */
            .section { padding: 80px 0; }
            @media(max-width:768px){ .section { padding: 50px 0; } }
            .section-badge { 
                display: inline-flex; align-items: center; gap: 8px;
                background: var(--gl); color: var(--g);
                padding: 5px 14px; border-radius: 99px;
                font-size: 11px; font-weight: 700; letter-spacing: .05em;
                margin-bottom: 16px;
            }
            .section-badge-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--g); }
            .section-title { 
                font-size: clamp(28px, 4vw, 40px); font-weight: 800; 
                color: var(--k); line-height: 1.15; margin-bottom: 16px;
                letter-spacing: -0.02em;
            }
            .section-subtitle { 
                font-size: clamp(15px, 2vw, 18px); color: var(--t3); 
                max-width: 640px; line-height: 1.6;
            }

            /* ── Hero ── */
            .hero { 
                text-align: center; padding: 80px 0 60px;
                background: linear-gradient(160deg, var(--w) 0%, var(--gl) 50%, var(--bl) 100%);
            }
            @media(max-width:768px){ .hero { padding: 50px 0 40px; } }
            .hero-title { 
                font-size: clamp(32px, 5vw, 52px); font-weight: 800;
                color: var(--k); line-height: 1.1; margin-bottom: 20px;
                letter-spacing: -0.02em;
            }
            .hero-title span { color: var(--g); }
            .hero-subtitle {
                font-size: clamp(15px, 2vw, 18px); color: var(--t3);
                max-width: 600px; margin: 0 auto 32px; line-height: 1.7;
            }
            .hero-buttons { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
            .hero-stats { 
                display: flex; gap: 40px; justify-content: center; margin-top: 48px;
                flex-wrap: wrap;
            }
            .hero-stat { text-align: center; }
            .hero-stat-val { font-family: 'JetBrains Mono', monospace; font-size: 28px; font-weight: 700; color: var(--k); }
            .hero-stat-lbl { font-size: 12px; color: var(--t4); margin-top: 4px; }

            /* ── Features ── */
            .features-grid { 
                display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; 
            }
            @media(max-width:900px){ .features-grid { grid-template-columns: repeat(2, 1fr); } }
            @media(max-width:600px){ .features-grid { grid-template-columns: 1fr; } }
            .feature-card {
                background: var(--w); border: 1px solid var(--br);
                border-radius: 16px; padding: 28px 24px;
                transition: all .2s; position: relative; overflow: hidden;
            }
            .feature-card:hover { 
                border-color: var(--g); 
                box-shadow: 0 8px 30px rgba(3,167,55,.08); 
                transform: translateY(-2px);
            }
            .feature-card::before {
                content: ''; position: absolute; top: 0; left: 0; right: 0;
                height: 3px; background: var(--g); opacity: 0; transition: opacity .2s;
            }
            .feature-card:hover::before { opacity: 1; }
            .feature-icon { 
                width: 48px; height: 48px; border-radius: 14px;
                display: flex; align-items: center; justify-content: center;
                margin-bottom: 18px;
            }
            .feature-icon svg { width: 24px; height: 24px; }
            .fi-g { background: var(--gl); } .fi-g svg { color: var(--g); }
            .fi-b { background: var(--bl); } .fi-b svg { color: var(--b); }
            .fi-k { background: var(--kl); } .fi-k svg { color: var(--k); }
            .feature-title { font-size: 15px; font-weight: 700; color: var(--k); margin-bottom: 8px; }
            .feature-desc { font-size: 13px; color: var(--t4); line-height: 1.6; }

            /* ── Screenshot ── */
            .screenshot-wrap {
                text-align: center; margin: 0 auto;
                border-radius: 20px; overflow: hidden;
                box-shadow: 0 20px 60px rgba(2,24,47,.12);
                border: 1px solid var(--br);
            }
            .screenshot-wrap img { width: 100%; display: block; }

            /* ── Business Types ── */
            .biz-types-grid { 
                display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); 
                gap: 14px; 
            }
            .biz-type-card {
                background: var(--w); border: 1.5px solid var(--br);
                border-radius: 16px; padding: 24px 16px;
                text-align: center; transition: all .2s;
            }
            .biz-type-card:hover { 
                border-color: var(--g); 
                box-shadow: 0 6px 20px rgba(3,167,55,.06);
                transform: translateY(-2px);
            }
            .biz-type-icon { 
                font-size: 32px; margin-bottom: 12px;
                width: 56px; height: 56px; border-radius: 16px;
                display: flex; align-items: center; justify-content: center;
                margin: 0 auto 12px;
            }
            .biz-type-name { font-size: 14px; font-weight: 700; color: var(--k); margin-bottom: 4px; }
            .biz-type-desc { font-size: 11px; color: var(--t4); line-height: 1.4; }

            /* ── Pricing ── */
            .pricing-grid { 
                display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
                max-width: 1100px; margin: 0 auto;
            }
            @media(max-width:1100px){ .pricing-grid { grid-template-columns: repeat(2, 1fr); } }
            @media(max-width:600px){ .pricing-grid { grid-template-columns: 1fr; max-width: 400px; } }
            .pricing-card {
                background: var(--w); border: 2px solid var(--br);
                border-radius: 20px; padding: 32px 24px;
                text-align: center; transition: all .2s; position: relative;
                display: flex; flex-direction: column;
            }
            .pricing-card:hover { border-color: var(--g); box-shadow: 0 12px 40px rgba(3,167,55,.08); }
            .pricing-card.popular { border-color: var(--g); box-shadow: 0 0 0 4px rgba(3,167,55,.08); }
            .popular-badge { 
                position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
                background: var(--g); color: var(--w); font-size: 11px; font-weight: 700;
                padding: 4px 16px; border-radius: 99px; letter-spacing: .04em;
            }
            .pricing-name { font-size: 13px; font-weight: 700; color: var(--t4); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 8px; }
            .pricing-price { font-family: 'JetBrains Mono', monospace; font-size: 32px; font-weight: 800; color: var(--k); margin-bottom: 4px; }
            .pricing-period { font-size: 12px; color: var(--t4); margin-bottom: 20px; }
            .pricing-features { text-align: left; margin-bottom: 24px; flex: 1; }
            .pricing-feature { 
                font-size: 12px; color: var(--t2); padding: 7px 0;
                display: flex; align-items: center; gap: 8px;
            }
            .pricing-feature svg { color: var(--g); flex-shrink: 0; }
            .pricing-card .btn { margin-top: auto; }

            /* ── Setup card accent ── */
            .pricing-card.setup-card { 
                border-color: var(--b); 
                background: linear-gradient(180deg, var(--bl) 0%, var(--w) 30%);
            }
            .setup-badge {
                position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
                background: var(--b); color: var(--w); font-size: 11px; font-weight: 700;
                padding: 4px 16px; border-radius: 99px; letter-spacing: .04em;
            }

            /* ── Testimonials ── */
            .testimonial-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
            @media(max-width:768px){ .testimonial-grid { grid-template-columns: 1fr; } }
            .testimonial-card {
                background: var(--w); border: 1px solid var(--br);
                border-radius: 16px; padding: 24px;
            }
            .testimonial-text { font-size: 14px; color: var(--t2); line-height: 1.7; margin-bottom: 16px; font-style: italic; }
            .testimonial-author { display: flex; align-items: center; gap: 10px; }
            .testimonial-avatar {
                width: 40px; height: 40px; border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
                font-weight: 700; font-size: 14px; color: var(--w);
            }
            .testimonial-name { font-size: 13px; font-weight: 700; color: var(--k); }
            .testimonial-role { font-size: 11px; color: var(--t4); }

            /* ── CTA ── */
            .cta { 
                background: linear-gradient(135deg, var(--gl) 0%, var(--bl) 100%);
                border-radius: 24px; padding: 64px 40px; text-align: center;
                border: 1px solid #b8e6c4;
            }
            @media(max-width:600px){ .cta { padding: 40px 20px; } }

            /* ── Footer ── */
            .footer { 
                border-top: 1px solid var(--br); padding: 32px 0;
                text-align: center; font-size: 13px; color: var(--t4);
            }
            .footer-links { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin-bottom: 12px; }
            .footer-links a { color: var(--t3); text-decoration: none; }
            .footer-links a:hover { color: var(--g); }
        </style>
    </head>
    <body>

        {{-- ══ NAVIGATION ══ --}}
        <nav class="nav">
            <div class="nav-inner">
                <a href="/" class="nav-logo">
                    <img src="{{ asset('images/sellsyncLogo.png') }}" alt="SellSync">
                    <div>
                        <div class="nav-logo-text">SellSync</div>
                        <div class="nav-logo-sub">POS</div>
                    </div>
                </a>
                <div class="nav-links">
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#businesses" class="nav-link">Who It's For</a>
                    <a href="#pricing" class="nav-link">Pricing</a>
                    <a href="#testimonials" class="nav-link">Testimonials</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline">Sign In</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">Start Free</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        {{-- ══ HERO ══ --}}
        <section class="hero">
            <div class="container">
                <div class="section-badge">
                    <span class="section-badge-dot"></span>
                    SMART POS SYSTEM
                </div>
                <h1 class="hero-title">
                    Point of Sale.<br><span>Built for Africa.</span>
                </h1>
                <p class="hero-subtitle">
                    The all-in-one POS that grows with your business — inventory, sales, multi-branch management, and real-time reports in one powerful dashboard.
                </p>
                <div class="hero-buttons">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Start Free Trial →</a>
                    @endif
                    <a href="#features" class="btn btn-outline btn-lg">See Features</a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-val">500+</div>
                        <div class="hero-stat-lbl">Active Businesses</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-val">50K+</div>
                        <div class="hero-stat-lbl">Transactions Daily</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-val">3</div>
                        <div class="hero-stat-lbl">Countries</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══ DASHBOARD SCREENSHOT ══ --}}
        <section class="section" style="padding-top:0">
            <div class="container">
                <div class="screenshot-wrap">
                    <img src="{{ asset('images/sellsync-dashboard.png') }}" 
                         alt="SellSync-POS Dashboard" 
                         onerror="this.style.display='none'">
                </div>
            </div>
        </section>

        {{-- ══ FEATURES ══ --}}
        <section class="section" id="features">
            <div class="container">
                <div style="text-align:center;margin-bottom:48px">
                    <div class="section-badge">
                        <span class="section-badge-dot"></span>
                        WHY SELLSYNC
                    </div>
                    <h2 class="section-title" style="margin:0 auto 12px">Everything you need to run<br>your retail business</h2>
                    <p class="section-subtitle" style="margin:0 auto">Powerful features designed for African retailers — from single shops to multi-branch chains.</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon fi-g">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="feature-title">POS Terminal</div>
                        <p class="feature-desc">Lightning-fast checkout with barcode scanning, receipt printing, and multiple payment methods including M-Pesa, cash, and card.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon fi-b">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div class="feature-title">Inventory Management</div>
                        <p class="feature-desc">Track stock across multiple branches, get low-stock alerts, manage suppliers, and import products via CSV in seconds.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon fi-k">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div class="feature-title">Multi-Branch</div>
                        <p class="feature-desc">Manage all your locations from one account. Switch branches instantly, compare performance, and control stock per location.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon fi-g">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="feature-title">Customer Profiles</div>
                        <p class="feature-desc">Build loyalty with customer profiles, purchase history tracking, credit management, and loyalty points — all built in.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon fi-b">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div class="feature-title">Smart Reports</div>
                        <p class="feature-desc">Real-time P&L statements, sales analytics, cashier performance, stock valuation, and Z-reports — all exportable to PDF.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon fi-k">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div class="feature-title">Two-Factor Security</div>
                        <p class="feature-desc">OTP-based authentication on every login keeps your business data safe. Role-based access for staff, managers, and owners.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══ WHO IT'S FOR — BUSINESS TYPES ══ --}}
        <section class="section" id="businesses" style="background:var(--kl)">
            <div class="container">
                <div style="text-align:center;margin-bottom:48px">
                    <div class="section-badge">
                        <span class="section-badge-dot"></span>
                        WHO IT'S FOR
                    </div>
                    <h2 class="section-title" style="margin:0 auto 12px">Built for every type<br>of retail business</h2>
                    <p class="section-subtitle" style="margin:0 auto">From corner shops to supermarket chains — SellSync adapts to your business.</p>
                </div>
                <div class="biz-types-grid">
                    <div class="biz-type-card">
                        <div class="biz-type-icon fi-g">
                            <svg width="26" height="26" fill="none" stroke="var(--g)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        </div>
                        <div class="biz-type-name">Retail Shops</div>
                        <div class="biz-type-desc">General stores, minimarts, kiosks, and convenience shops</div>
                    </div>
                    <div class="biz-type-card">
                        <div class="biz-type-icon fi-b">
                            <svg width="26" height="26" fill="none" stroke="var(--b)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div class="biz-type-name">Supermarkets</div>
                        <div class="biz-type-desc">Multi-aisle stores with thousands of SKUs and multiple branches</div>
                    </div>
                    <div class="biz-type-card">
                        <div class="biz-type-icon fi-k">
                            <svg width="26" height="26" fill="none" stroke="var(--k)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                        </div>
                        <div class="biz-type-name">Pharmacies</div>
                        <div class="biz-type-desc">Chemists and drugstores with expiry tracking and batch management</div>
                    </div>
                    <div class="biz-type-card">
                        <div class="biz-type-icon fi-g">
                            <svg width="26" height="26" fill="none" stroke="var(--g)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div class="biz-type-name">Bookshops</div>
                        <div class="biz-type-desc">Stationery stores, bookshops, and school supply retailers</div>
                    </div>
                    <div class="biz-type-card">
                        <div class="biz-type-icon fi-b">
                            <svg width="26" height="26" fill="none" stroke="var(--b)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        </div>
                        <div class="biz-type-name">Boutiques</div>
                        <div class="biz-type-desc">Fashion, clothing, accessories, and specialty retail stores</div>
                    </div>
                    <div class="biz-type-card">
                        <div class="biz-type-icon fi-k">
                            <svg width="26" height="26" fill="none" stroke="var(--k)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                        </div>
                        <div class="biz-type-name">Hardware Shops</div>
                        <div class="biz-type-desc">Building materials, tools, and hardware supply stores</div>
                    </div>
                    <div class="biz-type-card">
                        <div class="biz-type-icon fi-g">
                            <svg width="26" height="26" fill="none" stroke="var(--g)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513m-3-4.871v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.379a48.474 48.474 0 00-6-.371c-2.032 0-4.034.126-6 .371m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.169c0 .621-.504 1.125-1.125 1.125H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 016 13.12M12 16.5h.008v.008H12v-.008z"/></svg>
                        </div>
                        <div class="biz-type-name">Restaurants</div>
                        <div class="biz-type-desc">Cafés, eateries, and quick-service restaurants with menu management</div>
                    </div>
                    <div class="biz-type-card">
                        <div class="biz-type-icon fi-b">
                            <svg width="26" height="26" fill="none" stroke="var(--b)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125v-3.75"/></svg>
                        </div>
                        <div class="biz-type-name">Wholesalers</div>
                        <div class="biz-type-desc">Bulk distributors, importers, and B2B supply chain businesses</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══ PRICING ══ --}}
        <section class="section" id="pricing" style="background:var(--kl)">
            <div class="container">
                <div style="text-align:center;margin-bottom:48px">
                    <div class="section-badge">
                        <span class="section-badge-dot"></span>
                        PRICING
                    </div>
                    <h2 class="section-title" style="margin:0 auto 12px">Simple, transparent pricing</h2>
                    <p class="section-subtitle" style="margin:0 auto">Start free. Upgrade as you grow. No hidden fees.</p>
                </div>
                <div class="pricing-grid">
                    {{-- Starter --}}
                    <div class="pricing-card">
                        <div class="pricing-name">Starter</div>
                        <div class="pricing-price">KES 1,200</div>
                        <div class="pricing-period">per month</div>
                        <div class="pricing-features">
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Single branch</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Up to 500 products</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> POS Terminal</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Basic reports</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Email support</div>
                        </div>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline" style="width:100%;justify-content:center">Get Started</a>
                        @endif
                    </div>

                    {{-- Pro — Popular --}}
                    <div class="pricing-card popular">
                        <div class="popular-badge">MOST POPULAR</div>
                        <div class="pricing-name">Pro</div>
                        <div class="pricing-price">KES 2,200</div>
                        <div class="pricing-period">per month</div>
                        <div class="pricing-features">
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Up to 5 branches</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Unlimited products</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Advanced reports</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Z-Reports & P&L</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Staff management</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Priority support</div>
                        </div>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary" style="width:100%;justify-content:center">Start Free Trial</a>
                        @endif
                    </div>

                    {{-- Enterprise --}}
                    <div class="pricing-card">
                        <div class="pricing-name">Enterprise</div>
                        <div class="pricing-price">Custom</div>
                        <div class="pricing-period">Contact us</div>
                        <div class="pricing-features">
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Unlimited branches</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Custom integrations</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Dedicated support</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> SLA guarantee</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> On-site training</div>
                        </div>
                        <a href="mailto:info@sellsync.co.ke" class="btn btn-outline" style="width:100%;justify-content:center">Contact Sales</a>
                    </div>

                    {{-- One-Time Local Setup --}}
                    <div class="pricing-card setup-card">
                        <div class="setup-badge">ONE-TIME</div>
                        <div class="pricing-name">Local Setup</div>
                        <div class="pricing-price">KES 30,000</div>
                        <div class="pricing-period">one-time fee</div>
                        <div class="pricing-features">
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Installation & setup</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Staff training</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Product import (CSV)</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> 3 months free Pro</div>
                            <div class="pricing-feature"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> On-site visit</div>
                        </div>
                        <a href="mailto:info@sellsync.co.ke" class="btn btn-outline" style="width:100%;justify-content:center;border-color:var(--b);color:var(--b)">Book Setup</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══ TESTIMONIALS ══ --}}
        <section class="section" id="testimonials">
            <div class="container">
                <div style="text-align:center;margin-bottom:48px">
                    <div class="section-badge">
                        <span class="section-badge-dot"></span>
                        TESTIMONIALS
                    </div>
                    <h2 class="section-title" style="margin:0 auto 12px">Trusted by retailers<br>across Africa</h2>
                </div>
                <div class="testimonial-grid">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"SellSync transformed how we manage our three branches. I can see sales in real-time from my phone. The multi-branch feature is a game changer."</p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar" style="background:var(--g)">NK</div>
                            <div>
                                <div class="testimonial-name">Nancy K.</div>
                                <div class="testimonial-role">Owner, Nyakazi Organics</div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <p class="testimonial-text">"Stock management used to be a nightmare. Now I get alerts before running out, and the CSV import saved me days of manual entry."</p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar" style="background:var(--b)">JM</div>
                            <div>
                                <div class="testimonial-name">James M.</div>
                                <div class="testimonial-role">Manager, Techlungs Pharmacy</div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <p class="testimonial-text">"The Z-Report feature alone is worth it. End-of-day reconciliation that used to take an hour now takes 2 minutes. Plus OTP security gives me peace of mind."</p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar" style="background:var(--k)">SW</div>
                            <div>
                                <div class="testimonial-name">Sarah W.</div>
                                <div class="testimonial-role">Founder, Nail Hub</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ══ CTA ══ --}}
        <section class="section">
            <div class="container">
                <div class="cta">
                    <h2 class="section-title" style="margin-bottom:12px">Ready to streamline your<br>retail business?</h2>
                    <p class="section-subtitle" style="margin:0 auto 24px;max-width:500px">
                        Join thousands of retailers using SellSync-POS. Start your 14-day free trial — no credit card required.
                    </p>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create Free Account →</a>
                    @endif
                    <p style="font-size:12px;color:var(--t4);margin-top:12px">No credit card required · 14-day free trial · Cancel anytime</p>
                </div>
            </div>
        </section>

        {{-- ══ FOOTER ══ --}}
        <footer class="footer">
            <div class="container">
                <div class="footer-links">
                    <a href="#features">Features</a>
                    <a href="#businesses">Who It's For</a>
                    <a href="#pricing">Pricing</a>
                    <a href="#testimonials">Testimonials</a>
                    <a href="{{ route('login') }}">Sign In</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                </div>
                <p>© {{ date('Y') }} SellSync-POS. All rights reserved. Built for Africa.</p>
            </div>
        </footer>

        <script>
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
        </script>
    </body>
</html>