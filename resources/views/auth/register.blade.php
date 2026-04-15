<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register | GPT Backed Ecommerce</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            :root {
                color-scheme: light;
                --ink: #132430;
                --muted: #65717b;
                --line: rgba(19, 36, 48, 0.12);
                --brand: #1b7f6b;
                --brand-deep: #125a4b;
                --accent: #d95d39;
                --panel: rgba(255, 255, 255, 0.9);
                --shadow: 0 32px 92px rgba(19, 36, 48, 0.16);
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
                    radial-gradient(circle at top right, rgba(27, 127, 107, 0.18), transparent 30%),
                    radial-gradient(circle at bottom left, rgba(217, 93, 57, 0.18), transparent 34%),
                    linear-gradient(145deg, #eef6f3 0%, #f8efe9 52%, #f7fafc 100%);
            }

            .page {
                min-height: 100vh;
                display: grid;
                grid-template-columns: 0.95fr 1.05fr;
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
                width: min(100%, 540px);
            }

            .tag {
                display: inline-block;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.58);
                border: 1px solid rgba(255, 255, 255, 0.84);
                color: var(--muted);
                font-size: 14px;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .hero h1 {
                margin: 20px 0 14px;
                font-size: clamp(2.4rem, 7vw, 4.6rem);
                line-height: 0.95;
                letter-spacing: -0.04em;
            }

            .hero p {
                margin: 0;
                max-width: 34rem;
                color: var(--muted);
                line-height: 1.7;
                font-size: 1.03rem;
            }

            .steps {
                margin-top: 28px;
                display: grid;
                gap: 14px;
            }

            .step {
                padding: 16px 18px;
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.54);
                border: 1px solid rgba(255, 255, 255, 0.82);
                backdrop-filter: blur(10px);
            }

            .step strong {
                display: block;
                margin-bottom: 6px;
            }

            .step span {
                color: var(--muted);
                font-size: 0.95rem;
            }

            .panel {
                padding: 34px;
                border-radius: 28px;
                background: var(--panel);
                border: 1px solid rgba(255, 255, 255, 0.9);
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

            .error-box {
                margin-bottom: 18px;
                padding: 14px 16px;
                border-radius: 16px;
                background: rgba(217, 93, 57, 0.12);
                color: #8f311a;
                border: 1px solid rgba(217, 93, 57, 0.22);
                font-size: 0.95rem;
            }

            .grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }

            .field {
                margin-bottom: 18px;
            }

            .field.full {
                grid-column: 1 / -1;
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
                background: rgba(255, 255, 255, 0.94);
                font: inherit;
                color: var(--ink);
                outline: none;
                transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
            }

            .field input:focus {
                border-color: rgba(27, 127, 107, 0.66);
                box-shadow: 0 0 0 4px rgba(27, 127, 107, 0.14);
                transform: translateY(-1px);
            }

            .error {
                margin-top: 8px;
                color: #8f311a;
                font-size: 0.88rem;
            }

            .submit {
                width: 100%;
                border: 0;
                border-radius: 18px;
                padding: 16px 18px;
                background: linear-gradient(135deg, var(--brand) 0%, var(--brand-deep) 100%);
                color: #f5fffc;
                font: inherit;
                font-weight: 700;
                box-shadow: 0 18px 38px rgba(18, 90, 75, 0.22);
                transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
                cursor: pointer;
            }

            .submit:hover {
                transform: translateY(-2px);
                box-shadow: 0 22px 46px rgba(18, 90, 75, 0.28);
                filter: saturate(1.05);
            }

            .links {
                margin-top: 18px;
                display: flex;
                gap: 16px;
                flex-wrap: wrap;
            }

            .links a {
                text-decoration: none;
            }

            .primary-link {
                color: var(--brand-deep);
                font-weight: 600;
            }

            .subtle-link {
                color: var(--muted);
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

                .grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        <main class="page">
            <section class="hero">
                <div class="hero-card">
                    <span class="tag">Create account</span>
                    <h1>Start building your ecommerce workspace.</h1>
                    <p>Register a web account to manage your catalog, customer carts, and order flow from one place with a simple Laravel session login.</p>

                    <div class="steps">
                        <div class="step">
                            <strong>Create your profile</strong>
                            <span>Add your name, email, and optional phone number for account setup.</span>
                        </div>
                        <div class="step">
                            <strong>Set your password</strong>
                            <span>Use a strong password with confirmation so your first login is ready immediately.</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="panel-wrap">
                <div class="panel">
                    <h2>Register</h2>
                    <p class="panel-copy">Create your account and we’ll sign you in right away.</p>

                    @if ($errors->any())
                        <div class="error-box">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf

                        <div class="grid">
                            <div class="field">
                                <label for="name">Full name</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required autofocus>
                                @error('name')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="phone">Phone number</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" autocomplete="tel">
                                @error('phone')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field full">
                                <label for="email">Email address</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
                                @error('email')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="password">Password</label>
                                <input id="password" name="password" type="password" autocomplete="new-password" required>
                                @error('password')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="password_confirmation">Confirm password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                            </div>
                        </div>

                        <button class="submit" type="submit">Create Account</button>
                    </form>

                    <div class="links">
                        <a class="primary-link" href="{{ route('login') }}">Already have an account? Sign in</a>
                        <a class="subtle-link" href="{{ url('/') }}">Back to home</a>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
