@props(['label', 'value', 'color' => 'accent', 'icon' => '', 'trend' => null])

@php
    // Each tile carries one hue: the icon tile, the hairline along the top and
    // the bloom in the corner all come from the same pair, so the card reads as
    // one object rather than a box with a coloured sticker on it.
    $hues = [
        'accent' => ['#e63946', '#f26522'],
        'indigo' => ['#e63946', '#f26522'],
        'red'    => ['#e11d48', '#f43f5e'],
        'orange' => ['#ea580c', '#fb923c'],
        'yellow' => ['#d97706', '#fbbf24'],
        'green'  => ['#059669', '#34d399'],
        'blue'   => ['#0284c7', '#38bdf8'],
        'purple' => ['#7c3aed', '#a78bfa'],
    ];

    [$from, $to] = $hues[$color] ?? $hues['accent'];

    // A formatted revenue figure is far wider than an order count, and at four
    // columns the tile has no room to spare — "$1,284,932.55" ran past its box
    // at text-3xl. Step the size down once the value gets long.
    $len = mb_strlen((string) $value);
    $valueSize = $len > 12 ? 'text-xl' : ($len > 8 ? 'text-2xl' : 'text-3xl');
@endphp

<div {{ $attributes->merge(['class' => 'card card-lift group relative overflow-hidden p-5']) }}>

    {{-- Hairline along the top edge, brightening on hover. --}}
    <span aria-hidden="true"
          class="absolute inset-x-0 top-0 h-0.5 opacity-70 transition-opacity duration-200 group-hover:opacity-100"
          style="background-image: linear-gradient(90deg, {{ $from }}, {{ $to }});"></span>

    {{-- Soft bloom in the corner. Scales up on hover so the tile answers the
         pointer without anything moving that the eye has to re-read. --}}
    <span aria-hidden="true"
          class="pointer-events-none absolute -right-8 -top-10 size-32 rounded-full opacity-50 blur-2xl transition-transform duration-500 group-hover:scale-125"
          style="background-image: radial-gradient(circle, {{ $to }}33, transparent 70%);"></span>

    <div class="relative flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="eyebrow truncate">{{ $label }}</p>
            <p title="{{ $value }}"
               class="mt-2 truncate {{ $valueSize }} font-bold tracking-tight tabular-nums text-zinc-900 dark:text-zinc-50">{{ $value }}</p>
            @if ($trend)
                <p class="mt-1 text-xs text-zinc-500">{{ $trend }}</p>
            @endif
        </div>

        @if ($icon)
            <span class="flex size-11 shrink-0 items-center justify-center rounded-xl text-lg shadow-sm transition-transform duration-200 group-hover:-rotate-6 group-hover:scale-110"
                  style="background-image: linear-gradient(135deg, {{ $from }}1f, {{ $to }}2e); box-shadow: inset 0 0 0 1px {{ $from }}26;">
                {{ $icon }}
            </span>
        @endif
    </div>
</div>
