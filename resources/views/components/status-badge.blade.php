@props(['status', 'type' => 'order'])

@php
$orderMap = [
    'pending'     => ['bg-amber-50 text-amber-700 ring-amber-200',   '●'],
    'in_progress' => ['bg-blue-50 text-blue-700 ring-blue-200',      '●'],
    'submitted'   => ['bg-indigo-50 text-indigo-700 ring-indigo-200','●'],
    'revision'    => ['bg-orange-50 text-orange-700 ring-orange-200','●'],
    'completed'   => ['bg-emerald-50 text-emerald-700 ring-emerald-200','●'],
    'cancelled'   => ['bg-gray-100 text-gray-500 ring-gray-200',     '●'],
];
$paymentMap = [
    'unpaid'  => ['bg-red-50 text-red-600 ring-red-200',          '●'],
    'partial' => ['bg-amber-50 text-amber-600 ring-amber-200',    '●'],
    'paid'    => ['bg-emerald-50 text-emerald-700 ring-emerald-200','●'],
];
$map = $type === 'payment' ? $paymentMap : $orderMap;
[$cls, $dot] = $map[$status] ?? ['bg-gray-100 text-gray-500 ring-gray-200', '●'];
@endphp

<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium ring-1 {{ $cls }}">
    <span class="text-[8px] leading-none">{{ $dot }}</span>
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
