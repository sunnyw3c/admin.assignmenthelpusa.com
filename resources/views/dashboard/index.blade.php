@extends('layouts.app')
@section('title', 'Home')
@section('heading', 'Dashboard')

@section('content')
@php
    $role  = auth()->user()->role;
    $hour  = (int) date('H');
    $greet = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $first = explode(' ', auth()->user()->name)[0] ?: 'there';
@endphp

{{-- ══════════════ HERO ══════════════
     A single wide panel carries the greeting and the date, so the eye lands
     somewhere deliberate before it reaches the grid of numbers. --}}
<div class="relative mb-6 overflow-hidden rounded-3xl p-6 sm:p-8"
     style="background-image: linear-gradient(120deg, var(--color-brand-from), var(--color-brand-to));">

    {{-- Two blooms and a faint grid give the panel some depth rather than
         leaving it as one flat wash of colour. --}}
    <span aria-hidden="true" class="pointer-events-none absolute -right-16 -top-24 size-72 rounded-full bg-white/15 blur-3xl"></span>
    <span aria-hidden="true" class="pointer-events-none absolute -bottom-24 left-1/3 size-64 rounded-full bg-black/10 blur-3xl"></span>
    <span aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.07]"
          style="background-image: linear-gradient(rgba(255,255,255,.9) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.9) 1px, transparent 1px); background-size: 32px 32px;"></span>

    <div class="relative flex flex-wrap items-end justify-between gap-6">
        <div class="min-w-0">
            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-white/70">{{ now()->format('l, j F Y') }}</p>
            <h2 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">{{ $greet }}, {{ $first }}</h2>
            <p class="mt-1.5 max-w-md text-sm text-white/80">
                Here is where things stand across orders, writers and messages right now.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('orders.index') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-white/95 px-4 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm transition hover:bg-white active:translate-y-px">
                <x-icon name="clipboard" class="size-4" />
                View orders
            </a>
            <a href="{{ route('messages.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/15 active:translate-y-px">
                <x-icon name="chat" class="size-4" />
                Messages
            </a>
        </div>
    </div>
</div>

<p class="eyebrow mb-3">Overview</p>

{{-- Stats grid --}}
<div class="rise-stagger mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

@if($role === 'admin')
    <x-stat-card label="Total Orders"    :value="$stats['total_orders'] ?? 0"                          color="indigo" icon="📋"/>
    <x-stat-card label="Revenue"         :value="'$'.number_format($stats['total_revenue'] ?? 0, 2)"   color="green"  icon="💰"/>
    <x-stat-card label="Open Tickets"    :value="$stats['open_tickets'] ?? 0"                          color="red"    icon="🎫"/>
    <x-stat-card label="Writers"         :value="$stats['total_writers'] ?? 0"                         color="blue"   icon="✍️"/>
    <x-stat-card label="Pending"         :value="$stats['pending_orders'] ?? 0"                        color="yellow" icon="⏳"/>
    <x-stat-card label="Unassigned"      :value="$stats['unassigned_orders'] ?? 0"                     color="orange" icon="❗"/>
    <x-stat-card label="Completed"       :value="$stats['completed_orders'] ?? 0"                      color="green"  icon="✅"/>
    <x-stat-card label="Students"        :value="$stats['total_users'] ?? 0"                           color="purple" icon="👤"/>

@elseif($role === 'manager')
    <x-stat-card label="Pending"         :value="$stats['pending_orders'] ?? 0"     color="yellow" icon="⏳"/>
    <x-stat-card label="Unassigned"      :value="$stats['unassigned_orders'] ?? 0"  color="red"    icon="❗"/>
    <x-stat-card label="In Progress"     :value="$stats['in_progress'] ?? 0"        color="blue"   icon="🔄"/>
    <x-stat-card label="Completed Today" :value="$stats['completed_today'] ?? 0"    color="green"  icon="✅"/>
    <x-stat-card label="Active Writers"  :value="$stats['active_writers'] ?? 0"     color="indigo" icon="✍️"/>
    <x-stat-card label="Overdue"         :value="$stats['overdue_orders'] ?? 0"     color="red"    icon="🚨"/>

@elseif($role === 'writer')
    <x-stat-card label="Assigned"        :value="$stats['assigned'] ?? 0"   color="blue"   icon="📋"/>
    <x-stat-card label="Completed"       :value="$stats['completed'] ?? 0"  color="green"  icon="✅"/>
    <x-stat-card label="Due in 48h"      :value="$stats['due_soon'] ?? 0"   color="yellow" icon="⏰"/>
    <x-stat-card label="Overdue"         :value="$stats['overdue'] ?? 0"    color="red"    icon="🚨"/>

@elseif($role === 'executive')
    <x-stat-card label="Revenue"          :value="'$'.number_format($stats['total_revenue'] ?? 0, 2)" color="green"  icon="💰"/>
    <x-stat-card label="Total Orders"     :value="$stats['total_orders'] ?? 0"                        color="indigo" icon="📋"/>
    <x-stat-card label="Completion Rate"  :value="($stats['completion_rate'] ?? 0).'%'"               color="blue"   icon="📊"/>
    <x-stat-card label="Pending Payment"  :value="$stats['pending_payment'] ?? 0"                     color="red"    icon="💳"/>

@elseif($role === 'support')
    <x-stat-card label="Open Tickets"    :value="$stats['open_tickets'] ?? 0"   color="red"    icon="🎫"/>
    <x-stat-card label="Total Messages"  :value="$stats['total_messages'] ?? 0" color="blue"   icon="💬"/>
    <x-stat-card label="Total Orders"    :value="$stats['total_orders'] ?? 0"   color="indigo" icon="📋"/>
@endif

</div>

{{-- ══════════════ QUICK ACTIONS ══════════════ --}}
<p class="eyebrow mb-3">Jump to</p>
<div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">

    @php
        // label, emoji, href, hue — one row per shortcut so the markup below
        // stays a single loop rather than five near-identical anchors.
        $shortcuts = [
            ['All orders',  '📋', route('orders.index'),                              '#78716c'],
            ['Pending',     '⏳', route('orders.index', ['status' => 'pending']),     '#d97706'],
            ['Unread',      '💬', route('messages.index', ['unread' => 1]),           '#e11d48'],
        ];

        if (in_array($role, ['admin', 'manager'])) {
            $shortcuts[] = ['In progress', '🔄', route('orders.index', ['status' => 'in_progress']), '#0284c7'];
            $shortcuts[] = ['Writers',     '✍️', route('writers.index'),                             '#7c3aed'];
        }
    @endphp

    @foreach ($shortcuts as [$label, $emoji, $href, $hue])
        <a href="{{ $href }}" class="card card-lift group flex items-center gap-3 p-4">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl text-lg transition-transform duration-200 group-hover:scale-110"
                  style="background-color: {{ $hue }}1a; box-shadow: inset 0 0 0 1px {{ $hue }}26;">{{ $emoji }}</span>
            <span class="min-w-0 flex-1 truncate text-sm font-semibold text-zinc-700 dark:text-zinc-200">{{ $label }}</span>
            <span class="text-zinc-300 transition-transform duration-200 group-hover:translate-x-0.5 dark:text-zinc-600">
                <x-icon name="arrow-left" class="size-4 rotate-180" />
            </span>
        </a>
    @endforeach
</div>
@endsection
