<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Admin</title>
    <link rel="icon" href="{{ asset('images/logo-mark.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.png') }}">

    {{-- Resolve the stored theme before first paint, so a dark-mode user never
         sees a white flash while the stylesheet loads. --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                if (t === 'dark' || (!t && matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
                if (localStorage.getItem('sidebar') === 'collapsed') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-zinc-100 font-sans antialiased dark:bg-zinc-950">

@php $role = auth()->user()->role; @endphp

{{-- ══════════════ MOBILE TOP BAR ══════════════ --}}
<div class="sticky top-0 z-30 flex items-center justify-between border-b border-zinc-200 bg-white px-4 py-3 lg:hidden dark:border-zinc-800 dark:bg-zinc-900">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        <img src="{{ asset('images/logo-mark.svg') }}" alt="" class="size-7">
        <span class="font-bold text-zinc-900 dark:text-zinc-100">Admin</span>
    </a>
    <div class="flex items-center gap-1">
        <button type="button" data-theme-toggle aria-label="Toggle dark mode"
            class="inline-flex size-10 items-center justify-center rounded-lg text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
            <x-icon name="sun" class="hidden size-5 dark:block" />
            <x-icon name="moon" class="size-5 dark:hidden" />
        </button>
        <button id="mobile-menu-toggle" type="button" aria-label="Open navigation" aria-controls="sidebar" aria-expanded="false"
            class="inline-flex size-10 items-center justify-center rounded-lg text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
            <x-icon name="bars" class="size-5" />
        </button>
    </div>
</div>

<div class="flex min-h-screen lg:min-h-0">

    {{-- Backdrop for the mobile drawer. --}}
    <div id="sidebar-backdrop" hidden class="fixed inset-0 z-40 bg-zinc-950/40 lg:hidden"></div>

    {{-- ══════════════ SIDEBAR ══════════════ --}}
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-56 flex-col border-r border-zinc-200 bg-white
               -translate-x-full transition-[transform,width] duration-200
               lg:static lg:min-h-screen lg:translate-x-0
               dark:border-zinc-800 dark:bg-zinc-900">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-5 py-5">
            <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3">
                <img src="{{ asset('images/logo-mark.svg') }}" alt="Assignment Help USA" class="size-8 shrink-0">
                <span class="sidebar-brand-text min-w-0">
                    <span class="block truncate text-sm font-bold leading-tight text-zinc-900 dark:text-zinc-100">Admin Panel</span>
                    <span class="block truncate text-[11px] leading-tight text-zinc-500">Assignment Help USA</span>
                </span>
            </a>

            <button id="sidebar-toggle" type="button" aria-controls="sidebar" aria-expanded="true" aria-label="Collapse sidebar"
                class="ml-auto hidden size-7 shrink-0 items-center justify-center rounded-lg text-zinc-400 hover:bg-zinc-100 hover:text-zinc-900 lg:inline-flex dark:text-zinc-500 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                <x-icon name="chevron-left" class="size-4 transition-transform duration-200" />
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 pb-3">
            <x-nav-item route="dashboard" icon="home" label="Home" :active="request()->routeIs('dashboard')" />
            <x-nav-item route="orders.index" icon="clipboard" label="Orders" :active="request()->routeIs('orders.*')" />
            <x-nav-item route="messages.index" icon="chat" label="Messages" :active="request()->routeIs('messages.*')" />

            @if (in_array($role, ['admin', 'manager']))
                <p class="sidebar-section-label px-3 pb-1 pt-4 text-[10px] font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-600">Team</p>
                <x-nav-item route="users.index" icon="users" label="Users" :active="request()->routeIs('users.*')" />
                <x-nav-item route="writers.index" icon="pencil" label="Writers" :active="request()->routeIs('writers.*')" />
                <x-nav-item route="mail.index" icon="envelope" label="Mail" :active="request()->routeIs('mail.*')" />
            @endif

            @if ($role === 'admin')
                <p class="sidebar-section-label px-3 pb-1 pt-4 text-[10px] font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-600">Website</p>
                <x-nav-item route="cms.index" icon="document" label="Pages" :active="request()->routeIs('cms.*')" />
                <x-nav-item route="services-editor.index" icon="squares" label="Services" :active="request()->routeIs('services-editor.*')" />
            @endif
        </nav>

        {{-- Signed-in user --}}
        <div class="border-t border-zinc-200 p-3 dark:border-zinc-800">
            <div class="flex items-center gap-3 rounded-lg px-2 py-2">
                <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-accent text-xs font-bold text-accent-foreground">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-label min-w-0 flex-1">
                    <p class="truncate text-xs font-medium text-zinc-900 dark:text-zinc-100">{{ auth()->user()->name }}</p>
                    <p class="truncate text-[10px] capitalize text-zinc-500">{{ auth()->user()->role }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="sidebar-label">
                    @csrf
                    <button type="submit" title="Sign out" aria-label="Sign out"
                        class="text-zinc-400 transition-colors hover:text-zinc-900 dark:hover:text-zinc-100">
                        <x-icon name="logout" class="size-4" />
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ══════════════ MAIN ══════════════ --}}
    <div class="flex min-w-0 flex-1 flex-col">

        <header class="hidden h-16 shrink-0 items-center gap-4 border-b border-zinc-200 bg-white px-6 lg:flex dark:border-zinc-800 dark:bg-zinc-900">
            <h1 class="truncate text-base font-semibold text-zinc-900 dark:text-zinc-100">@yield('heading', 'Dashboard')</h1>

            <div class="ml-auto flex items-center gap-1">
                <button type="button" data-theme-toggle aria-label="Toggle dark mode"
                    class="inline-flex size-9 items-center justify-center rounded-lg text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                    <x-icon name="sun" class="hidden size-5 dark:block" />
                    <x-icon name="moon" class="size-5 dark:hidden" />
                </button>
                <button type="button" aria-label="Notifications"
                    class="inline-flex size-9 items-center justify-center rounded-lg text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100">
                    <x-icon name="bell" class="size-5" />
                </button>
            </div>
        </header>

        <main class="min-w-0 px-3 py-4 sm:px-4 md:p-6">

            @if (session('success'))
                <div class="mb-4 flex items-start gap-3 rounded-xl border border-accent/30 bg-accent/10 p-3.5">
                    <x-icon name="check-circle" class="mt-0.5 size-4 shrink-0 text-accent-content" />
                    <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 flex items-start gap-3 rounded-xl border border-red-200 dark:border-red-500/30 bg-red-50 dark:bg-red-500/10 p-3.5 dark:border-red-900/50 dark:bg-red-950/30">
                    <x-icon name="x-circle" class="mt-0.5 size-4 shrink-0 text-red-500" />
                    <p class="text-sm text-red-700 dark:text-red-400 dark:text-red-300">{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 flex items-start gap-3 rounded-xl border border-red-200 dark:border-red-500/30 bg-red-50 dark:bg-red-500/10 p-3.5 dark:border-red-900/50 dark:bg-red-950/30">
                    <x-icon name="x-circle" class="mt-0.5 size-4 shrink-0 text-red-500" />
                    <div class="space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-700 dark:text-red-400 dark:text-red-300">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    (function () {
        var root = document.documentElement;

        document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var dark = root.classList.toggle('dark');
                try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {}
            });
        });

        // Desktop collapse. The class is set on <html> in the head script too,
        // so the collapsed width is correct on first paint.
        var toggle = document.getElementById('sidebar-toggle');

        if (toggle && root.classList.contains('sidebar-collapsed')) {
            toggle.setAttribute('aria-expanded', 'false');
            toggle.setAttribute('aria-label', 'Expand sidebar');
        }

        if (toggle) {
            toggle.addEventListener('click', function () {
                var collapsed = root.classList.toggle('sidebar-collapsed');
                toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                toggle.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
                try { localStorage.setItem('sidebar', collapsed ? 'collapsed' : 'expanded'); } catch (e) {}
            });
        }

        // Mobile drawer.
        var sidebar = document.getElementById('sidebar');
        var backdrop = document.getElementById('sidebar-backdrop');
        var menuBtn = document.getElementById('mobile-menu-toggle');

        function setDrawer(open) {
            if (!sidebar) return;
            sidebar.classList.toggle('-translate-x-full', !open);
            if (backdrop) backdrop.hidden = !open;
            if (menuBtn) menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        }

        if (menuBtn) {
            menuBtn.addEventListener('click', function () {
                setDrawer(sidebar.classList.contains('-translate-x-full'));
            });
        }
        if (backdrop) backdrop.addEventListener('click', function () { setDrawer(false); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setDrawer(false); });
    })();
</script>
</body>
</html>
