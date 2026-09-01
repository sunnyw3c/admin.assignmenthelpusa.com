@props(['status', 'type' => 'order'])

@php
    $orderMap = [
        'pending'     => 'bg-amber-500/10 text-amber-700 ring-amber-500/30 dark:text-amber-400',
        'in_progress' => 'bg-blue-500/10 text-blue-700 ring-blue-500/30 dark:text-blue-400',
        'submitted'   => 'bg-accent/10 text-accent-content ring-accent/30',
        'revision'    => 'bg-orange-500/10 text-orange-700 ring-orange-500/30 dark:text-orange-400',
        'completed'   => 'bg-emerald-500/10 text-emerald-700 ring-emerald-500/30 dark:text-emerald-400',
        'cancelled'   => 'bg-zinc-500/10 text-zinc-500 ring-zinc-500/30',
    ];

    $paymentMap = [
        'unpaid'  => 'bg-red-500/10 text-red-600 ring-red-500/30 dark:text-red-400',
        'partial' => 'bg-amber-500/10 text-amber-700 ring-amber-500/30 dark:text-amber-400',
        'paid'    => 'bg-emerald-500/10 text-emerald-700 ring-emerald-500/30 dark:text-emerald-400',
    ];

    $map = $type === 'payment' ? $paymentMap : $orderMap;
    $cls = $map[$status] ?? 'bg-zinc-500/10 text-zinc-500 ring-zinc-500/30';
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset {{ $cls }}">
    <span class="size-1.5 rounded-full bg-current"></span>
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
