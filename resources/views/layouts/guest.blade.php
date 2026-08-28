<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="MBC Student Monitoring System - Secure Login Portal">

    <title>{{ config('app.name', 'MBC Student Monitoring System') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/MBC-logo.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-top: 52px;
            /* offset fixed header */

            /* ── Clean school background, no effects ── */
            background-color: #1e1b5e;
            background-image: linear-gradient(rgba(92, 92, 92, 0.4), rgba(92, 92, 92, 0.4)), url('/images/school_background_compressed.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
        }

        /* ── Site Header ── */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            height: 52px;
            background: #1e1b5e;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.35);
        }

        .site-header-left {
            display: flex;
            align-items: center;
        }

        .site-header-left a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .site-header-logo {
            width: 36px;
            height: 36px;
            object-fit: contain;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            padding: 2px;
        }

        .site-header-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .site-header-right {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            letter-spacing: 0.5px;
        }

        /* ── Glassmorphism Card ── */
        .auth-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        .auth-card {
            /* true glassmorphism: semi-transparent white + strong blur */
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 24px;
            padding: 20px 30px;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.5);
            animation: cardIn 0.3s ease-out both;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(15px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ── Brand header ── */
        .brand-header {
            text-align: center;
            margin-bottom: 8px;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 58px;
            height: 58px;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            border-radius: 18px;
            margin-bottom: 6px;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.45);
            position: relative;
            overflow: hidden;
        }

        .brand-logo::before {
            content: '';
            position: absolute;
            inset: -50%;
            background: conic-gradient(transparent, rgba(255, 255, 255, 0.18), transparent 180deg);
            animation: rotateSpin 4s linear infinite;
        }

        @keyframes rotateSpin {
            to {
                transform: rotate(360deg);
            }
        }

        .brand-logo svg {
            width: 36px;
            height: 36px;
            fill: white;
            position: relative;
            z-index: 1;
        }

        .brand-title {
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
            margin: 0 0 6px;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
        }

        .brand-subtitle {
            font-size: 13px;
            color: #ffffff;
            margin: 0;
            letter-spacing: 0.3px;
        }

        /* ── Divider ── */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 10px;
        }

        .auth-divider-line {
            flex: 1;
            height: 1px;
            background: rgba(40, 32, 195, 0.35);
        }

        .auth-divider-text {
            font-size: 11px;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <!-- ── Site Header ── -->
    <header class="site-header">
        <div class="site-header-left">
            <a href="{{ route('login') }}">
                <img src="/images/MBC-logo.png" alt="MBC Logo" class="site-header-logo">
                <span class="site-header-title">MBC Academic Portal</span>
            </a>
        </div>
        <div class="site-header-right">Metro Business College</div>
    </header>

    <!-- Card -->
    <div class="auth-wrapper">
        <div class="auth-card">
            <!-- Brand -->
            <div class="brand-header">
                <div class="brand-logo">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z" />
                    </svg>
                </div>
                <h1 class="brand-title">MBC Student Monitoring System</h1>
            </div>

            <div class="auth-divider">
                <div class="auth-divider-line"></div>
                <span class="auth-divider-text">Secure Access</span>
                <div class="auth-divider-line"></div>
            </div>

            {{ $slot }}
        </div>
    </div>
</body>

</html>