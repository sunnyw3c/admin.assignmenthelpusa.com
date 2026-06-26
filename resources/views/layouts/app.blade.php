<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        * { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 2px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-[#f5f5f5]" x-data="{ sidebar: true }">

<div class="flex h-full overflow-hidden">

    {{-- ═══════════════ SIDEBAR ═══════════════ --}}
    <aside class="flex-shrink-0 w-[220px] bg-[#1e1f21] flex flex-col h-full">

        {{-- Logo --}}
        <div class="h-14 flex items-center px-4 border-b border-white/5">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-md bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-white font-semibold text-sm tracking-tight">Admin Panel</span>
            </div>
        </div>

        {{-- Workspace label --}}
        <div class="px-4 pt-4 pb-1">
            <p class="text-[10px] font-semibold text-white/30 uppercase tracking-widest">Workspace</p>
        </div>

        {{-- Nav --}}
        @php $role = session('admin_user.role', 'admin'); @endphp
        <nav class="flex-1 px-2 py-2 space-y-0.5 overflow-y-auto">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="group flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all
               {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : 'text-white/50 hover:bg-white/5 hover:text-white/80' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Home
            </a>

            {{-- Orders --}}
            <a href="{{ route('orders.index') }}"
               class="group flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all
               {{ request()->routeIs('orders.*') ? 'bg-white/10 text-white' : 'text-white/50 hover:bg-white/5 hover:text-white/80' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Orders
            </a>

            {{-- Messages --}}
            <a href="{{ route('messages.index') }}"
               class="group flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all
               {{ request()->routeIs('messages.*') ? 'bg-white/10 text-white' : 'text-white/50 hover:bg-white/5 hover:text-white/80' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                Messages
            </a>

            @if(in_array($role, ['admin', 'manager']))
            {{-- Divider --}}
            <div class="my-3 border-t border-white/5"></div>
            <p class="px-3 text-[10px] font-semibold text-white/25 uppercase tracking-widest mb-1">Team</p>

            {{-- Users --}}
            <a href="{{ route('users.index') }}"
               class="group flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all
               {{ request()->routeIs('users.*') ? 'bg-white/10 text-white' : 'text-white/50 hover:bg-white/5 hover:text-white/80' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Users
            </a>

            {{-- Writers --}}
            <a href="{{ route('writers.index') }}"
               class="group flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all
               {{ request()->routeIs('writers.*') ? 'bg-white/10 text-white' : 'text-white/50 hover:bg-white/5 hover:text-white/80' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Writers
            </a>

            {{-- Mail --}}
            <a href="{{ route('mail.index') }}"
               class="group flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all
               {{ request()->routeIs('mail.*') ? 'bg-white/10 text-white' : 'text-white/50 hover:bg-white/5 hover:text-white/80' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Mail
            </a>
            @endif

            @if($role === 'admin')
            {{-- Divider --}}
            <div class="my-3 border-t border-white/5"></div>
            <p class="px-3 text-[10px] font-semibold text-white/25 uppercase tracking-widest mb-1">Website</p>

            {{-- CMS / Pages --}}
            <a href="{{ route('cms.index') }}"
               class="group flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all
               {{ request()->routeIs('cms.*') ? 'bg-white/10 text-white' : 'text-white/50 hover:bg-white/5 hover:text-white/80' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Pages
            </a>

            {{-- Assignment Services --}}
            <a href="{{ route('services-editor.index') }}"
               class="group flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] font-medium transition-all
               {{ request()->routeIs('services-editor.*') ? 'bg-white/10 text-white' : 'text-white/50 hover:bg-white/5 hover:text-white/80' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Assignment Services
            </a>
            @endif
        </nav>

        {{-- User profile --}}
        <div class="p-3 border-t border-white/5">
            <div class="flex items-center gap-2.5 px-2 py-2 rounded-lg hover:bg-white/5 transition group">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                    {{ strtoupper(substr(session('admin_user.name', 'A'), 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-white/80 font-medium truncate">{{ session('admin_user.name') }}</p>
                    <p class="text-[10px] text-white/30 capitalize">{{ session('admin_user.role') }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Sign out"
                            class="text-white/25 hover:text-white/70 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ═══════════════ MAIN ═══════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="h-14 bg-white border-b border-gray-200 flex items-center px-6 gap-4 flex-shrink-0">
            {{-- Breadcrumb / Title --}}
            <div class="flex items-center gap-2 min-w-0">
                <h1 class="text-[15px] font-semibold text-gray-800 truncate">@yield('heading', 'Dashboard')</h1>
            </div>

            <div class="ml-auto flex items-center gap-3">
                @if(session('success'))
                <div class="flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-medium px-3 py-1.5 rounded-full border border-green-200">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
                @endif

                {{-- Notifications placeholder --}}
                <button class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </button>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @if($errors->any())
            <div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl p-4">
                <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <div>
                    @foreach($errors->all() as $error)
                    <p class="text-sm text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
