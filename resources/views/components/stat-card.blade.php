@props(['label', 'value', 'color' => 'accent', 'icon' => '', 'trend' => null])

@php
    // Tints stay muted so the accent stays the loudest thing on the page.
    $iconBg = [
        'accent' => 'bg-accent/15 text-accent-content',
        'indigo' => 'bg-accent/15 text-accent-content',
        'green'  => 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400',
        'blue'   => 'bg-blue-500/15 text-blue-600 dark:text-blue-400',
        'red'    => 'bg-red-500/15 text-red-600 dark:text-red-400',
        'yellow' => 'bg-amber-500/15 text-amber-600 dark:text-amber-400',
        'orange' => 'bg-orange-500/15 text-orange-600 dark:text-orange-400',
        'purple' => 'bg-violet-500/15 text-violet-600 dark:text-violet-400',
    ][$color] ?? 'bg-accent/15 text-accent-content';
@endphp

<div class="card p-5 transition-shadow hover:shadow-sm">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="truncate text-xs font-medium uppercase tracking-wide text-zinc-500">{{ $label }}</p>
            <p class="mt-1.5 text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $value }}</p>
            @if ($trend)
                <p class="mt-1 text-xs text-zinc-500">{{ $trend }}</p>
            @endif
        </div>
        @if ($icon)
            <div class="flex size-10 shrink-0 items-center justify-center rounded-xl text-lg {{ $iconBg }}">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
