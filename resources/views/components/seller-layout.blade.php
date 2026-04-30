<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Seller' }} | GPT Backed Ecommerce</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            :root{--ink:#14242b;--muted:#61727b;--line:rgba(20,36,43,.12);--brand:#2f806d;--brand-deep:#1d5e50;--red:#9a3527;--paper:rgba(255,255,255,.9);--shadow:0 18px 48px rgba(20,36,43,.1)}
            *{box-sizing:border-box} body{margin:0;font-family:'Outfit',sans-serif;color:var(--ink);background:linear-gradient(135deg,#edf5f2 0%,#f8f4ee 52%,#f6f8fb 100%)} a{color:inherit}.shell{width:min(1240px,calc(100% - 32px));margin:0 auto;padding:24px 0 44px}.topbar{display:flex;align-items:center;justify-content:space-between;gap:18px;margin-bottom:24px}.brand,.nav a,.button{text-decoration:none}.brand{font-weight:700}.nav{display:flex;align-items:center;gap:12px;color:var(--muted);flex-wrap:wrap}.nav a{padding:8px 10px;border-radius:8px}.nav a:hover,.nav a.active{background:rgba(255,255,255,.75);color:var(--ink)}.logout{border:0;background:transparent;color:var(--brand-deep);font:inherit;font-weight:700;cursor:pointer}.heading{display:flex;align-items:flex-end;justify-content:space-between;gap:18px;margin-bottom:18px}h1{margin:0 0 6px;font-size:clamp(2rem,5vw,3.3rem);line-height:1}.copy{margin:0;color:var(--muted);line-height:1.7}.panel,.card,.table-wrap,.alert{border:1px solid rgba(255,255,255,.9);border-radius:8px;background:var(--paper);box-shadow:var(--shadow)}.panel{padding:20px}.alert{margin-bottom:16px;padding:13px 15px}.alert.success{color:#1d5e50;border-color:rgba(47,128,109,.22);background:rgba(47,128,109,.12)}.alert.error{color:#8f311a;border-color:rgba(217,93,57,.24);background:rgba(217,93,57,.12)}.button{display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:8px;padding:10px 13px;background:var(--brand);color:#fff;font:inherit;font-weight:700;cursor:pointer}.button:hover{background:var(--brand-deep)}.button.secondary{background:#fff;color:var(--ink);border:1px solid var(--line)}.button.danger{background:#fff;color:var(--red);border:1px solid rgba(154,53,39,.22)}.grid{display:grid;gap:16px}.stats{grid-template-columns:repeat(3,minmax(0,1fr))}.card{padding:18px}.card strong{display:block;font-size:1.8rem}.card span{color:var(--muted)}.table-wrap{overflow:auto}table{width:100%;border-collapse:collapse}th,td{padding:14px 16px;text-align:left;border-bottom:1px solid var(--line);vertical-align:top}th{color:var(--muted);font-size:.86rem;text-transform:uppercase;letter-spacing:.04em}tr:last-child td{border-bottom:0}.actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap}.field{display:grid;gap:7px}.field label{font-weight:700}.field input,.field textarea,.field select{width:100%;border:1px solid var(--line);border-radius:8px;background:#fff;padding:11px 12px;color:var(--ink);font:inherit}.form-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}.form-grid .wide{grid-column:span 2}.pill{display:inline-flex;align-items:center;min-height:28px;padding:5px 10px;border-radius:999px;font-size:.84rem;font-weight:700;border:1px solid var(--line);background:#fff}.pill.bad{color:var(--red);border-color:rgba(154,53,39,.22);background:rgba(154,53,39,.1)}.pages{margin-top:18px;color:var(--muted)}@media(max-width:900px){.topbar,.heading{align-items:flex-start;flex-direction:column}.stats,.form-grid{grid-template-columns:1fr}.form-grid .wide{grid-column:auto}}
        </style>
    </head>
    <body>
        <main class="shell">
            <header class="topbar">
                <a class="brand" href="{{ route('seller.dashboard') }}">Seller Panel</a>
                <nav class="nav">
                    <a class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}" href="{{ route('seller.dashboard') }}">Dashboard</a>
                    <a class="{{ request()->routeIs('seller.products') ? 'active' : '' }}" href="{{ route('seller.products') }}">Products</a>
                    <a class="{{ request()->routeIs('seller.orders') ? 'active' : '' }}" href="{{ route('seller.orders') }}">Orders</a>
                    <a href="{{ route('home') }}">Storefront</a>
                </nav>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout" type="submit">Logout</button>
                </form>
            </header>
            @if (session('success')) <div class="alert success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert error">{{ session('error') }}</div> @endif
            @if ($errors->any()) <div class="alert error">{{ $errors->first() }}</div> @endif
            {{ $slot }}
        </main>
    </body>
</html>
