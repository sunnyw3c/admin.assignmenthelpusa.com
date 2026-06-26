@props(['label', 'value', 'color' => 'indigo', 'icon' => '', 'trend' => null])

@php
$iconBg = [
    'indigo' => 'bg-indigo-100 text-indigo-600',
    'green'  => 'bg-emerald-100 text-emerald-600',
    'blue'   => 'bg-blue-100 text-blue-600',
    'red'    => 'bg-red-100 text-red-600',
    'yellow' => 'bg-amber-100 text-amber-600',
    'orange' => 'bg-orange-100 text-orange-600',
    'purple' => 'bg-violet-100 text-violet-600',
][$color] ?? 'bg-indigo-100 text-indigo-600';
@endphp

<div class="bg-white rounded-2xl border border-gray-200 p-5 hover:shadow-sm transition-shadow">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $label }}</p>
            <p class="text-2xl font-bold text-gray-900 mt-1.5">{{ $value }}</p>
        </div>
        @if($icon)
        <div class="w-10 h-10 rounded-xl {{ $iconBg }} flex items-center justify-center text-lg flex-shrink-0">
            {{ $icon }}
        </div>
        @endif
    </div>
</div>
