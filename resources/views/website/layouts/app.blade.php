<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $title ?? 'TradeFlow') — {{ config('tradeflow.name', 'TradeFlow') }}</title>
    <meta name="description" content="@yield('meta_description', 'Order management for buying houses — buyers, factories, shipments, and trade finance in one platform.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;0,8..60,700;1,8..60,400&display=swap" rel="stylesheet">
    @stack('head')
    <style>
        :root {
            --tf-teal: #0d9488;
            --tf-teal-dark: #0f766e;
            --tf-slate-900: #0f172a;
            --tf-slate-700: #334155;
            --tf-slate-500: #64748b;
            --tf-slate-100: #f1f5f9;
            --tf-white: #ffffff;
            --font-serif: 'Source Serif 4', Georgia, serif;
            --font-sans: 'DM Sans', system-ui, sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font-sans);
            color: var(--tf-slate-700);
            background: var(--tf-white);
            line-height: 1.6;
        }
        a { color: var(--tf-teal-dark); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .tf-wrap { max-width: 1120px; margin: 0 auto; padding: 0 1.5rem; }
        .tf-header {
            border-bottom: 1px solid #e2e8f0;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(8px);
            position: sticky; top: 0; z-index: 50;
        }
        .tf-header-inner { display: flex; align-items: center; justify-content: space-between; padding: 1rem 0; gap: 1rem; flex-wrap: wrap; }
        .tf-logo { font-family: var(--font-serif); font-weight: 700; font-size: 1.35rem; color: var(--tf-slate-900); letter-spacing: -0.02em; }
        .tf-nav { display: flex; gap: 1.25rem; flex-wrap: wrap; font-size: 0.92rem; }
        .tf-nav a { color: var(--tf-slate-700); text-decoration: none; }
        .tf-nav a:hover { color: var(--tf-teal-dark); }
        .tf-btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.65rem 1.25rem; border-radius: 999px; font-weight: 600; font-size: 0.92rem;
            border: none; cursor: pointer; text-decoration: none !important;
        }
        .tf-btn-primary { background: var(--tf-teal); color: #fff; }
        .tf-btn-primary:hover { background: var(--tf-teal-dark); }
        .tf-btn-ghost { background: transparent; color: var(--tf-slate-900); border: 1px solid #cbd5e1; }
        .tf-btn-ghost:hover { border-color: var(--tf-teal); color: var(--tf-teal-dark); }
        .tf-main { min-height: 60vh; padding: 2.5rem 0 4rem; }
        h1, h2, h3 { font-family: var(--font-serif); color: var(--tf-slate-900); line-height: 1.2; }
        h1 { font-size: clamp(2rem, 4vw, 3.25rem); margin-bottom: 1rem; }
        h2 { font-size: 1.75rem; margin: 2rem 0 1rem; }
        p { margin-bottom: 1rem; max-width: 65ch; }
        .tf-footer { border-top: 1px solid #e2e8f0; padding: 2.5rem 0; background: var(--tf-slate-100); font-size: 0.9rem; }
        .tf-footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1.5rem; }
        .tf-hero-pattern {
            background:
                radial-gradient(ellipse 80% 60% at 70% 0%, rgba(13,148,136,.18), transparent 55%),
                radial-gradient(circle at 20% 80%, rgba(15,23,42,.06), transparent 40%),
                linear-gradient(180deg, #f8fafc 0%, #ffffff 70%);
        }
        .tf-card {
            border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem;
            background: #fff;
        }
        .tf-grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; }
    </style>
</head>
<body>
<header class="tf-header">
    <div class="tf-wrap tf-header-inner">
        <a href="{{ url('/') }}" class="tf-logo">TradeFlow</a>
        <nav class="tf-nav" aria-label="Primary">
            <a href="{{ url('/features') }}">Features</a>
            <a href="{{ url('/modules') }}">Modules</a>
            <a href="{{ url('/pricing') }}">Pricing</a>
            <a href="{{ url('/documentation') }}">Docs</a>
            @if(config('tradeflow.demo.enabled'))
                <a href="{{ url('/demo-access') }}">Demo</a>
            @endif
        </nav>
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
            <a href="{{ url('/app/login') }}" class="tf-btn tf-btn-ghost">Log in</a>
            <a href="{{ url('/request-demo') }}" class="tf-btn tf-btn-primary">Request demo</a>
        </div>
    </div>
</header>

<main class="tf-main">
    @yield('content')
</main>

<footer class="tf-footer">
    <div class="tf-wrap tf-footer-grid">
        <div>
            <div class="tf-logo" style="margin-bottom:.5rem;">TradeFlow</div>
            <p style="font-size:.85rem;color:var(--tf-slate-500);">Order management for buying houses.</p>
        </div>
        <div>
            <strong>Product</strong>
            <p><a href="{{ url('/platform') }}">Platform</a></p>
            <p><a href="{{ url('/security') }}">Security</a></p>
            <p><a href="{{ url('/integrations') }}">Integrations</a></p>
        </div>
        <div>
            <strong>Company</strong>
            <p><a href="{{ url('/about') }}">About</a></p>
            <p><a href="{{ url('/contact') }}">Contact</a></p>
            <p><a href="{{ url('/faq') }}">FAQ</a></p>
        </div>
    </div>
</footer>
</body>
</html>
