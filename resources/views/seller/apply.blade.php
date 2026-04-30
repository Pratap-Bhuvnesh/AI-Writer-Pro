<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Become a Seller | GPT Backed Ecommerce</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            :root{--ink:#14242b;--muted:#61727b;--line:rgba(20,36,43,.12);--brand:#d95d39;--brand-deep:#a84025;--paper:rgba(255,255,255,.9);--shadow:0 18px 48px rgba(20,36,43,.1)}
            *{box-sizing:border-box}body{margin:0;font-family:'Outfit',sans-serif;color:var(--ink);background:linear-gradient(135deg,#f8f4ee 0%,#edf5f2 52%,#f6f8fb 100%)}a{color:inherit}.page{width:min(1080px,calc(100% - 32px));margin:0 auto;padding:28px 0 46px}.topbar{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:28px}.brand,.nav a,.button{text-decoration:none}.brand{font-weight:700}.nav{display:flex;gap:14px;color:var(--muted)}.layout{display:grid;grid-template-columns:.9fr 1.1fr;gap:22px}.panel,.info,.alert{border:1px solid rgba(255,255,255,.9);border-radius:8px;background:var(--paper);box-shadow:var(--shadow)}.info,.panel{padding:24px}h1{margin:0 0 12px;font-size:clamp(2.2rem,5vw,4rem);line-height:1}.copy{margin:0;color:var(--muted);line-height:1.75}.benefits{display:grid;gap:12px;margin-top:24px}.benefit{padding:14px;border:1px solid var(--line);border-radius:8px;background:#fff}.fields{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.field.full{grid-column:1/-1}.field label{display:block;margin-bottom:7px;font-weight:700}.field input,.field textarea,.field select{width:100%;border:1px solid var(--line);border-radius:8px;background:#fff;padding:12px 13px;font:inherit;color:var(--ink)}.button{border:0;border-radius:8px;padding:13px 16px;background:var(--brand);color:#fffaf7;font:inherit;font-weight:700;cursor:pointer}.button:hover{background:var(--brand-deep)}.alert{margin-bottom:16px;padding:13px 15px}.alert.success{color:#1d5e50;border-color:rgba(47,128,109,.22);background:rgba(47,128,109,.12)}.alert.error{color:#8f311a;border-color:rgba(217,93,57,.24);background:rgba(217,93,57,.12)}@media(max-width:840px){.topbar,.layout{grid-template-columns:1fr;align-items:flex-start;flex-direction:column}.fields{grid-template-columns:1fr}.field.full{grid-column:auto}}
        </style>
    </head>
    <body>
        <main class="page">
            <header class="topbar">
                <a class="brand" href="{{ route('home') }}">GPT Backed Ecommerce</a>
                <nav class="nav"><a href="{{ route('home') }}">Home</a><a href="{{ route('products.list') }}">Products</a></nav>
            </header>

            @if (session('success')) <div class="alert success">{{ session('success') }}</div> @endif
            @if ($errors->any()) <div class="alert error">{{ $errors->first() }}</div> @endif

            <section class="layout">
                <aside class="info">
                    <h1>Sell on our marketplace.</h1>
                    <p class="copy">Apply as a seller, wait for admin approval, then use your seller backend to add products, manage stock, and track sales from your products.</p>
                    <div class="benefits">
                        <div class="benefit"><strong>Seller backend</strong><br><span class="copy">Add and manage your products independently.</span></div>
                        <div class="benefit"><strong>Marketplace checkout</strong><br><span class="copy">Customers buy from all sellers using one cart.</span></div>
                        <div class="benefit"><strong>Admin approval</strong><br><span class="copy">Only approved sellers can publish products.</span></div>
                    </div>
                </aside>

                <form class="panel" method="POST" action="{{ route('seller.apply.store') }}">
                    @csrf
                    <div class="fields">
                        @guest
                            <div class="field"><label>Name</label><input name="name" value="{{ old('name') }}" required></div>
                            <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required></div>
                            <div class="field"><label>Password</label><input type="password" name="password" required></div>
                            <div class="field"><label>Confirm Password</label><input type="password" name="password_confirmation" required></div>
                        @endguest
                        <div class="field"><label>Store Name</label><input name="store_name" value="{{ old('store_name', auth()->user()?->sellerProfile?->store_name) }}" required></div>
                        <div class="field">
                            <label>Business Type</label>
                            <select name="business_type" required>
                                @foreach (['individual', 'business', 'brand', 'wholesaler'] as $type)
                                    <option value="{{ $type }}" @selected(old('business_type', auth()->user()?->sellerProfile?->business_type) === $type)>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field"><label>Phone</label><input name="contact_phone" value="{{ old('contact_phone', auth()->user()?->sellerProfile?->contact_phone ?? auth()->user()?->phone) }}" required></div>
                        <div class="field"><label>City</label><input name="city" value="{{ old('city', auth()->user()?->sellerProfile?->city) }}" required></div>
                        <div class="field"><label>State</label><input name="state" value="{{ old('state', auth()->user()?->sellerProfile?->state) }}" required></div>
                        <div class="field full"><label>Business Address</label><textarea name="address" rows="2" required>{{ old('address', auth()->user()?->sellerProfile?->address) }}</textarea></div>
                        <div class="field full"><label>What do you sell?</label><textarea name="description" rows="3">{{ old('description', auth()->user()?->sellerProfile?->description) }}</textarea></div>
                    </div>
                    <button class="button" style="margin-top:16px;" type="submit">Submit Seller Application</button>
                </form>
            </section>
        </main>
    </body>
</html>
