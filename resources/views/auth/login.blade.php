{{-- The sign-in page is always dark, as on asset-management.test: the grid,
     the glow and the frosted card only read against the dark ground, so this
     page deliberately ignores the theme toggle the rest of the panel uses. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Admin</title>
    <link rel="icon" href="{{ asset('images/logo-mark.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])

    <style>
        body {
            background-color: #0f1117 !important;
            background-image:
                linear-gradient(rgba(255, 255, 255, .025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .025) 1px, transparent 1px) !important;
            background-size: 44px 44px !important;
            position: relative;
        }

        /* Glow behind the brand, so the top of the page is not flat. */
        body::before {
            content: '';
            position: fixed;
            top: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 600px;
            background: radial-gradient(ellipse at center, rgba(230, 57, 70, .10) 0%, transparent 65%);
            pointer-events: none;
            z-index: 0;
        }

        /* Mouse-tracking glow: the same grid again in lime, masked to a circle
           that follows the cursor, so only the lines near the pointer light up.
           Hover-capable pointers only — there is no cursor to follow on touch. */
        .grid-glow {
            position: fixed;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            opacity: 0;
            transition: opacity .35s;
            background-image:
                linear-gradient(rgba(242, 101, 34, .22) 1px, transparent 1px),
                linear-gradient(90deg, rgba(242, 101, 34, .22) 1px, transparent 1px);
            background-size: 44px 44px;
            mask-image: radial-gradient(350px circle at var(--gx, 50%) var(--gy, 50%), black 0%, transparent 100%);
            -webkit-mask-image: radial-gradient(350px circle at var(--gx, 50%) var(--gy, 50%), black 0%, transparent 100%);
        }

        @media (hover: hover) {
            body:hover .grid-glow { opacity: 1; }
        }

        @media (prefers-reduced-motion: reduce) {
            .grid-glow { transition: none; }
        }

        .auth-card {
            background: rgba(24, 24, 27, .85) !important;
            border: 1px solid rgba(255, 255, 255, .07) !important;
            border-radius: 1rem !important;
            backdrop-filter: blur(12px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, .5) !important;
        }

        .auth-field {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .09);
            color: #f4f4f5;
        }

        .auth-field::placeholder { color: #52525b; }

        .auth-field:focus {
            outline: none;
            border-color: #f26522;
            box-shadow: 0 0 0 3px rgba(230, 57, 70, .18);
        }

        /* Show/hide password. The two icons are both in the button and swapped
           by aria-pressed, so the pressed state and the glyph cannot disagree. */
        .pw-wrap { position: relative; }
        .pw-wrap .auth-field { padding-right: 2.75rem; }

        .pw-toggle {
            position: absolute;
            top: 0;
            right: 0;
            display: flex;
            height: 100%;
            width: 2.75rem;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: .5rem;
            background: none;
            padding: 0;
            color: #71717a;
            cursor: pointer;
            transition: color .15s;
        }

        .pw-toggle:hover { color: #a1a1aa; }

        .pw-toggle:focus-visible {
            outline: 2px solid rgba(230, 57, 70, .5);
            outline-offset: -2px;
        }

        .pw-toggle svg { width: 1rem; height: 1rem; }
        .pw-toggle .icon-hide { display: none; }
        .pw-toggle[aria-pressed="true"] .icon-hide { display: block; }
        .pw-toggle[aria-pressed="true"] .icon-show { display: none; }
    </style>
</head>
<body class="min-h-screen antialiased">

<div class="grid-glow" id="gridGlow"></div>

<div class="relative z-10 flex min-h-screen flex-col items-center justify-center p-6">

    {{-- Brand. The orange wordmark is the variant the main site ships for dark
         grounds, so it is the one that belongs here. --}}
    <div class="mb-8 flex flex-col items-center">
        <img src="{{ asset('images/logo-320w.webp') }}" alt="Assignment Help USA"
             width="320" height="60" class="h-12 w-auto" decoding="async">
        <p class="mt-2 text-[10px] font-medium uppercase tracking-[0.2em] text-zinc-600">Admin Panel</p>
    </div>

    {{-- Card --}}
    <div class="auth-card w-full max-w-sm px-8 py-8">

        <h1 class="text-lg font-bold text-zinc-100">Sign in</h1>
        <p class="mt-1 text-sm text-zinc-500">Staff access only</p>

        @if ($errors->any())
            <div class="mt-5 flex items-start gap-3 rounded-xl border border-red-500/30 bg-red-500/10 p-3.5">
                <x-icon name="x-circle" class="mt-0.5 size-4 shrink-0 text-red-400" />
                <div class="space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-300">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mt-5 flex items-start gap-3 rounded-xl border border-red-500/30 bg-red-500/10 p-3.5">
                <x-icon name="x-circle" class="mt-0.5 size-4 shrink-0 text-red-400" />
                <p class="text-sm text-red-300">{{ session('error') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-xs font-medium text-zinc-400">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       autocomplete="username" placeholder="you@company.com"
                       class="auth-field w-full rounded-lg px-3 py-2.5 text-sm transition">
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-xs font-medium text-zinc-400">Password</label>
                <div class="pw-wrap">
                    <input id="password" type="password" name="password" required
                           autocomplete="current-password" placeholder="••••••••"
                           class="auth-field w-full rounded-lg px-3 py-2.5 text-sm transition">
                    <button type="button" class="pw-toggle" data-pw-toggle="password"
                            aria-pressed="false" aria-controls="password"
                            aria-label="Show password" title="Show password">
                        <svg class="icon-show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        <svg class="icon-hide" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243"/></svg>
                    </button>
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm text-zinc-400">
                <input type="checkbox" name="remember" value="1"
                       class="size-4 rounded border-zinc-600 bg-white/5 text-orange-500 focus:ring-orange-500/40">
                Remember me
            </label>

            <button type="submit"
                    class="w-full rounded-lg py-2.5 text-sm font-semibold text-zinc-900 transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #e63946, #f26522);">
                Continue
            </button>
        </form>
    </div>

    <p class="mt-7 text-[11px] text-zinc-700">&copy; {{ date('Y') }} Assignment Help USA</p>
</div>

<script>
    (function () {
        var glow = document.getElementById('gridGlow');
        if (!glow || !matchMedia('(hover: hover)').matches) return;

        var x = 0, y = 0, queued = false;

        // Paint on the next frame rather than on every mousemove, so a fast
        // cursor cannot outrun the compositor.
        function paint() {
            queued = false;
            glow.style.setProperty('--gx', x + 'px');
            glow.style.setProperty('--gy', y + 'px');
        }

        document.addEventListener('mousemove', function (e) {
            x = e.clientX;
            y = e.clientY;
            if (!queued) {
                queued = true;
                requestAnimationFrame(paint);
            }
        });
    })();

    // Show/hide password.
    (function () {
        document.querySelectorAll('[data-pw-toggle]').forEach(function (btn) {
            var input = document.getElementById(btn.dataset.pwToggle);
            if (!input) return;

            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.setAttribute('aria-pressed', show ? 'true' : 'false');
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                btn.title = show ? 'Hide password' : 'Show password';
                input.focus({ preventScroll: true });
            });
        });
    })();
</script>
</body>
</html>
