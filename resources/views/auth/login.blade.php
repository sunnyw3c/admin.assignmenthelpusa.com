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
            background: radial-gradient(ellipse at center, rgba(163, 230, 53, .07) 0%, transparent 65%);
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
                linear-gradient(rgba(163, 230, 53, .18) 1px, transparent 1px),
                linear-gradient(90deg, rgba(163, 230, 53, .18) 1px, transparent 1px);
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
            border-color: #a3e635;
            box-shadow: 0 0 0 3px rgba(163, 230, 53, .15);
        }
    </style>
</head>
<body class="min-h-screen antialiased">

<div class="grid-glow" id="gridGlow"></div>

<div class="relative z-10 flex min-h-screen flex-col items-center justify-center p-6">

    {{-- Brand. The orange wordmark is the variant the main site ships for dark
         grounds, so it is the one that belongs here. --}}
    <div class="mb-8 flex flex-col items-center">
        <img src="{{ asset('images/logo-dark.svg') }}" alt="Assignment Help USA" class="h-11 w-auto">
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
                <input id="password" type="password" name="password" required
                       autocomplete="current-password" placeholder="••••••••"
                       class="auth-field w-full rounded-lg px-3 py-2.5 text-sm transition">
            </div>

            <label class="flex items-center gap-2 text-sm text-zinc-400">
                <input type="checkbox" name="remember" value="1"
                       class="size-4 rounded border-zinc-600 bg-white/5 text-lime-400 focus:ring-lime-400/40">
                Remember me
            </label>

            <button type="submit"
                    class="w-full rounded-lg py-2.5 text-sm font-semibold text-zinc-900 transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #84cc16, #a3e635);">
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
</script>
</body>
</html>
