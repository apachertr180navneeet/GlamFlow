<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GlamFlow') }} - Salon & Spa Management System</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/admin/img/favicon/favicon.ico') }}" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/admin/img/favicon/favicon-32x32.png') }}" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Boxicons -->
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/fonts/boxicons.css') }}">

    <style>
        :root {
            --bg-dark: #0a0e17;
            --bg-card: rgba(18, 25, 40, 0.75);
            --border-color: rgba(255, 255, 255, 0.08);
            --primary-accent: #6366f1;
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #ec4899 100%);
            --gold-accent: #d4af37;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Ambient background glow */
        .ambient-glow-1 {
            position: absolute;
            top: -15%;
            left: 20%;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .ambient-glow-2 {
            position: absolute;
            top: 40%;
            right: -10%;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.12) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .ambient-glow-3 {
            position: absolute;
            bottom: -10%;
            left: -5%;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.08) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Header Navigation */
        header {
            position: relative;
            z-index: 10;
            border-bottom: 1px solid var(--border-color);
            backdrop-filter: blur(12px);
            background: rgba(10, 14, 23, 0.7);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.4rem;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35);
        }

        .brand-text {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .brand-tag {
            color: var(--gold-accent);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-left: 6px;
        }

        .btn-launch {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.65rem 1.4rem;
            border-radius: 10px;
            background: var(--primary-gradient);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.92rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-launch:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(99, 102, 241, 0.45);
        }

        /* Hero Section */
        .hero {
            position: relative;
            z-index: 1;
            padding: 5rem 2rem 3rem;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 50px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.25);
            color: #a5b4fc;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.8rem;
        }

        .badge-pill i {
            color: var(--gold-accent);
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.18;
            letter-spacing: -1px;
            margin-bottom: 1.4rem;
            background: linear-gradient(180deg, #ffffff 40%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.15rem;
            color: var(--text-muted);
            max-width: 680px;
            margin: 0 auto 2.5rem;
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn-primary-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.85rem 2rem;
            border-radius: 12px;
            background: var(--primary-gradient);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.25s ease;
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35);
        }

        .btn-primary-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(99, 102, 241, 0.5);
        }

        .btn-secondary-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.85rem 1.8rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.25s ease;
        }

        .btn-secondary-action:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* Features Section */
        .features {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 2rem auto 4rem;
            padding: 0 2rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .section-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }

        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            padding: 2rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
        }

        .feature-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            background: rgba(99, 102, 241, 0.12);
            color: #818cf8;
            border: 1px solid rgba(99, 102, 241, 0.2);
        }

        .feature-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.22rem;
            font-weight: 600;
            color: #ffffff;
        }

        .feature-text {
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.55;
        }

        /* Status Banner */
        .system-status {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto 4rem;
            padding: 0 2rem;
            width: 100%;
        }

        .status-box {
            background: rgba(18, 25, 40, 0.5);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .status-items {
            display: flex;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.88rem;
            color: #34d399;
            font-weight: 600;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #34d399;
            box-shadow: 0 0 10px #34d399;
        }

        .system-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-muted);
            font-size: 0.82rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Footer */
        footer {
            margin-top: auto;
            position: relative;
            z-index: 1;
            border-top: 1px solid var(--border-color);
            padding: 1.8rem 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.88rem;
            background: rgba(10, 14, 23, 0.5);
        }

        footer a {
            color: #a5b4fc;
            text-decoration: none;
            font-weight: 500;
        }

        footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.3rem;
            }
            .hero-subtitle {
                font-size: 1rem;
            }
            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Glowing Orbs -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>
    <div class="ambient-glow-3"></div>

    <!-- Header Navigation -->
    <header>
        <div class="nav-container">
            <a href="{{ url('/') }}" class="brand-logo">
                <div class="brand-icon">
                    <i class="bx bx-spa"></i>
                </div>
                <div class="brand-text">
                    GlamFlow <span class="brand-tag">ERP</span>
                </div>
            </a>

            <div>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn-launch">
                        <i class="bx bx-layout"></i> Open Dashboard
                    </a>
                @else
                    <a href="{{ route('admin.login') }}" class="btn-launch">
                        <i class="bx bx-lock-open-alt"></i> Sign In
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main>
        <section class="hero">
            <div class="badge-pill">
                <i class="bx bxs-badge-check"></i> Enterprise Salon & Spa Management Platform
            </div>

            <h1 class="hero-title">
                Effortless Operations for Modern Salons & Spas
            </h1>

            <p class="hero-subtitle">
                Welcome to {{ config('app.name', 'GlamFlow') }}. Simplify appointments, manage staff and payroll, process billing, track inventory, and grow customer relationships with complete ease.
            </p>

            <div class="hero-actions">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn-primary-action">
                        <i class="bx bx-tachometer"></i> Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('admin.login') }}" class="btn-primary-action">
                        <i class="bx bx-user-check"></i> Operator Portal Login
                    </a>
                @endauth
            </div>
        </section>

        <!-- Features Showcase -->
        <section class="features">
            <div class="section-header">
                <h2 class="section-title">Core Management Capabilities</h2>
                <p class="section-desc">Everything needed to run high-performing multi-branch salons and luxury spas</p>
            </div>

            <div class="features-grid">
                <!-- Feature 1 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="bx bx-calendar-check"></i>
                    </div>
                    <h3 class="feature-title">Smart Appointment Scheduling</h3>
                    <p class="feature-text">Real-time slot allocation, stylist assignment, check-in tracking, and automated service reminders for maximum chair utilization.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper" style="color: #38bdf8; background: rgba(56, 189, 248, 0.12); border-color: rgba(56, 189, 248, 0.2);">
                        <i class="bx bx-id-card"></i>
                    </div>
                    <h3 class="feature-title">Staff & Payroll Operations</h3>
                    <p class="feature-text">Track daily attendance, working shifts, automated commission calculations, salary advances, and monthly payrolls.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper" style="color: #34d399; background: rgba(52, 211, 153, 0.12); border-color: rgba(52, 211, 153, 0.2);">
                        <i class="bx bx-credit-card"></i>
                    </div>
                    <h3 class="feature-title">POS & Split Billing</h3>
                    <p class="feature-text">Fast billing with tax support, split payment modes (Cash, Card, UPI), discount vouchers, and integrated package redemptions.</p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper" style="color: #fbbf24; background: rgba(251, 191, 36, 0.12); border-color: rgba(251, 191, 36, 0.2);">
                        <i class="bx bx-gift"></i>
                    </div>
                    <h3 class="feature-title">Packages & Client CRM</h3>
                    <p class="feature-text">Curated service bundles, prepaid customer balances, comprehensive client histories, and birthday greeting automations.</p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper" style="color: #f472b6; background: rgba(244, 114, 182, 0.12); border-color: rgba(244, 114, 182, 0.2);">
                        <i class="bx bx-closet"></i>
                    </div>
                    <h3 class="feature-title">Laundry & Inventory Cycle</h3>
                    <p class="feature-text">Track salon laundry orders, send items for processing, monitor receive statuses, and record vendor payouts with full accountability.</p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper" style="color: #a78bfa; background: rgba(167, 139, 250, 0.12); border-color: rgba(167, 139, 250, 0.2);">
                        <i class="bx bx-buildings"></i>
                    </div>
                    <h3 class="feature-title">Multi-Branch Architecture</h3>
                    <p class="feature-text">Manage multiple stores and branches with granular role permissions, central expense ledgers, and exportable financial reports.</p>
                </div>
            </div>
        </section>

        <!-- System Status Bar -->
        <section class="system-status">
            <div class="status-box">
                <div class="status-items">
                    <div class="status-indicator">
                        <span class="status-dot"></span> System Operational
                    </div>
                    <span class="system-pill"><i class="bx bx-server"></i> Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
                    <span class="system-pill"><i class="bx bx-code-curly"></i> PHP v{{ PHP_VERSION }}</span>
                </div>
                <div>
                    <a href="{{ route('admin.login') }}" style="color: #818cf8; font-size: 0.88rem; font-weight: 500; text-decoration: none;">
                        Staff Access &rarr;
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'GlamFlow') }}. All rights reserved. &bull; <a href="{{ route('admin.login') }}">Admin Login</a></p>
    </footer>

</body>
</html>
