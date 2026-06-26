@extends('layouts.app')
@section('title', 'Orders')
@section('heading', 'Orders')

@section('content')

{{-- Filters bar --}}
<div class="bg-white rounded-2xl border border-gray-200 px-5 py-3.5 mb-4 flex flex-wrap items-center gap-3">
    <form method="GET" action="{{ route('orders.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">

        {{-- Search --}}
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                   placeholder="Search orders..."
                   class="pl-8 pr-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-52 transition">
        </div>

        {{-- Status --}}
        <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-600">
            <option value="">All statuses</option>
            @foreach(['pending','in_progress','submitted','revision','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ ($filters['status'] ?? '') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>

        {{-- Payment --}}
        <select name="payment_status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-600">
            <option value="">All payments</option>
            @foreach(['unpaid','partial','paid'] as $p)
                <option value="{{ $p }}" {{ ($filters['payment_status'] ?? '') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
            @endforeach
        </select>

        <button type="submit"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
            Apply
        </button>
        @if(array_filter($filters))
        <a href="{{ route('orders.index') }}" class="text-sm text-gray-400 hover:text-gray-600 transition">Clear</a>
        @endif
    </form>

    {{-- Total --}}
    @if(isset($orders['total']))
    <span class="text-xs text-gray-400 font-medium ml-auto">{{ $orders['total'] }} orders</span>
    @endif
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Order</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Student</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Subject</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Deadline</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Payment</th>
                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Budget</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($orders['data'] ?? [] as $order)
            <tr class="hover:bg-gray-50/60 transition-colors group">
                <td class="px-5 py-3.5">
                    <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $order['order_number'] ?? '#'.($order['id'] ?? '—') }}</span>
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                            {{ strtoupper(substr($order['user']['name'] ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 leading-tight">{{ $order['user']['name'] ?? '—' }}</p>
                            <p class="text-xs text-gray-400 leading-tight">{{ $order['user']['email'] ?? '' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3.5 text-sm text-gray-600 max-w-[180px] truncate">{{ $order['subject'] ?? '—' }}</td>
                <td class="px-5 py-3.5 text-sm text-gray-500 whitespace-nowrap">
                    {{ ($order['deadline'] ?? null) ? \Carbon\Carbon::parse($order['deadline'])->format('M j, Y') : '—' }}
                </td>
                <td class="px-5 py-3.5"><x-status-badge :status="$order['status'] ?? 'pending'" type="order"/></td>
                <td class="px-5 py-3.5"><x-status-badge :status="$order['payment_status'] ?? 'unpaid'" type="payment"/></td>
                <td class="px-5 py-3.5 text-right text-sm font-semibold text-gray-700">${{ number_format($order['budget'] ?? 0, 2) }}</td>
                <td class="px-5 py-3.5">
                    <a href="{{ route('orders.show', $order['id'] ?? 0) }}"
                       class="text-xs font-medium text-indigo-600 hover:text-indigo-800 opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                        Open →
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-16 text-center">
                    <div class="text-gray-300 text-4xl mb-3">📋</div>
                    <p class="text-sm text-gray-400">No orders found</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if(isset($orders['last_page']) && $orders['last_page'] > 1)
    <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between">
        <span class="text-xs text-gray-400">Page {{ $orders['current_page'] }} of {{ $orders['last_page'] }}</span>
        <div class="flex items-center gap-1">
            @if($orders['current_page'] > 1)
            <a href="{{ route('orders.index', array_merge($filters, ['page' => $orders['current_page'] - 1])) }}"
               class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">← Prev</a>
            @endif
            @if($orders['current_page'] < $orders['last_page'])
            <a href="{{ route('orders.index', array_merge($filters, ['page' => $orders['current_page'] + 1])) }}"
               class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">Next →</a>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
