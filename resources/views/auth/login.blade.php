<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login | GPT Backed Ecommerce</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            :root {
                color-scheme: light;
                --ink: #10212b;
                --muted: #62717a;
                --mist: #eef3f1;
                --line: rgba(16, 33, 43, 0.12);
                --brand: #d95d39;
                --brand-deep: #a84025;
                --panel: rgba(255, 255, 255, 0.88);
                --shadow: 0 30px 90px rgba(16, 33, 43, 0.16);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                font-family: 'Outfit', sans-serif;
                color: var(--ink);
                background:
                    radial-gradient(circle at top left, rgba(217, 93, 57, 0.16), transparent 32%),
                    radial-gradient(circle at bottom right, rgba(51, 138, 117, 0.2), transparent 30%),
                    linear-gradient(135deg, #f7efe8 0%, #edf4ef 50%, #f4f7fb 100%);
            }

            a {
                color: inherit;
            }

            .page {
                min-height: 100vh;
                display: grid;
                grid-template-columns: 1.1fr 0.9fr;
            }

            .hero,
            .panel-wrap {
                padding: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .hero-card,
            .panel {
                width: min(100%, 520px);
            }

            .eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.56);
                border: 1px solid rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                color: var(--muted);
                font-size: 14px;
                letter-spacing: 0.04em;
                text-transform: uppercase;
            }

            .hero h1 {
                margin: 22px 0 16px;
                font-size: clamp(2.5rem, 7vw, 4.5rem);
                line-height: 0.95;
                letter-spacing: -0.04em;
            }

            .hero p {
                margin: 0;
                max-width: 34rem;
                font-size: 1.05rem;
                line-height: 1.7;
                color: var(--muted);
            }

            .highlights {
                margin-top: 28px;
                display: grid;
                gap: 14px;
            }

            .highlight {
                padding: 16px 18px;
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.52);
                border: 1px solid rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(10px);
            }

            .highlight strong {
                display: block;
                margin-bottom: 6px;
                font-size: 0.95rem;
            }

            .highlight span {
                color: var(--muted);
                font-size: 0.95rem;
            }

            .panel {
                padding: 34px;
                border-radius: 28px;
                background: var(--panel);
                border: 1px solid rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(18px);
                box-shadow: var(--shadow);
            }

            .panel h2 {
                margin: 0 0 8px;
                font-size: 2rem;
                letter-spacing: -0.03em;
            }

            .panel-copy {
                margin: 0 0 26px;
                color: var(--muted);
                line-height: 1.65;
            }

            .status,
            .error-box {
                margin-bottom: 18px;
                padding: 14px 16px;
                border-radius: 16px;
                font-size: 0.95rem;
            }

            .status {
                background: rgba(51, 138, 117, 0.14);
                color: #1d5e50;
                border: 1px solid rgba(51, 138, 117, 0.22);
            }

            .error-box {
                background: rgba(217, 93, 57, 0.12);
                color: #8f311a;
                border: 1px solid rgba(217, 93, 57, 0.22);
            }

            .field {
                margin-bottom: 18px;
            }

            .field label {
                display: block;
                margin-bottom: 8px;
                font-size: 0.95rem;
                font-weight: 600;
            }

            .field input {
                width: 100%;
                padding: 15px 16px;
                border-radius: 16px;
                border: 1px solid var(--line);
                background: rgba(255, 255, 255, 0.92);
                font: inherit;
                color: var(--ink);
                outline: none;
                transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
            }

            .field input:focus {
                border-color: rgba(217, 93, 57, 0.7);
                box-shadow: 0 0 0 4px rgba(217, 93, 57, 0.14);
                transform: translateY(-1px);
            }

            .error {
                margin-top: 8px;
                color: #8f311a;
                font-size: 0.88rem;
            }

            .row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                margin-bottom: 22px;
            }

            .remember {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                color: var(--muted);
                font-size: 0.95rem;
            }

            .remember input {
                width: 18px;
                height: 18px;
                accent-color: var(--brand);
            }

            .submit {
                width: 100%;
                border: 0;
                border-radius: 18px;
                padding: 16px 18px;
                background: linear-gradient(135deg, var(--brand) 0%, var(--brand-deep) 100%);
                color: #fff8f5;
                font: inherit;
                font-weight: 700;
                letter-spacing: 0.01em;
                box-shadow: 0 18px 38px rgba(169, 64, 37, 0.22);
                transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
                cursor: pointer;
            }

            .submit:hover {
                transform: translateY(-2px);
                box-shadow: 0 22px 45px rgba(169, 64, 37, 0.28);
                filter: saturate(1.05);
            }

            .submit:focus-visible {
                outline: 3px solid rgba(217, 93, 57, 0.22);
                outline-offset: 3px;
            }

            .home-link {
                display: inline-block;
                margin-top: 18px;
                color: var(--muted);
                text-decoration: none;
            }

            .home-link:hover {
                color: var(--ink);
            }

            @media (max-width: 900px) {
                .page {
                    grid-template-columns: 1fr;
                }

                .hero,
                .panel-wrap {
                    padding: 28px 20px;
                }

                .hero {
                    padding-bottom: 8px;
                }

                .panel {
                    padding: 26px 22px;
                    border-radius: 24px;
                }
            }
        </style>
    </head>
    <body>
        <main class="page">
            <section class="hero">
                <div class="hero-card">
                    <span class="eyebrow">GPT Backed Ecommerce</span>
                    <h1>Welcome back to your storefront control room.</h1>
                    <p>Sign in to manage products, carts, orders, and the everyday flow of your ecommerce workspace with a clean, focused session-based login.</p>

                    <div class="highlights">
                        <div class="highlight">
                            <strong>Fast access</strong>
                            <span>Use your existing user email and password to enter the web app.</span>
                        </div>
                        <div class="highlight">
                            <strong>Secure sessions</strong>
                            <span>Your login uses Laravel's session authentication with CSRF protection and session regeneration.</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="panel-wrap">
                <div class="panel">
                    <h2>Login</h2>
                    <p class="panel-copy">Enter your account credentials to continue.</p>

                    @if (session('status'))
                        <div class="status">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="error-box">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf

                        <div class="field">
                            <label for="email">Email address</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                required
                                autofocus
                            >
                            @error('email')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="password">Password</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                            >
                            @error('password')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <label class="remember" for="remember">
                                <input id="remember" name="remember" type="checkbox" value="1" {{ old('remember') ? 'checked' : '' }}>
                                <span>Keep me signed in</span>
                            </label>
                        </div>

                        <button class="submit" type="submit">Sign In</button>
                    </form>

                    <p style="margin: 18px 0 0; color: var(--muted);">
                        New here?
                        <a href="{{ route('register') }}" style="color: var(--brand-deep); font-weight: 600; text-decoration: none;">Create an account</a>
                    </p>

                    <a class="home-link" href="{{ url('/') }}">Back to home</a>
                </div>
            </section>
        </main>
    </body>
</html>
