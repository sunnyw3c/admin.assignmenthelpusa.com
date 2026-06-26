@extends('layouts.app')
@section('title', 'Home')
@section('heading', 'Good ' . (date('H') < 12 ? 'morning' : (date('H') < 18 ? 'afternoon' : 'evening')) . ', ' . (session('admin_user.name') ? explode(' ', session('admin_user.name'))[0] : 'there') . ' 👋')

@section('content')
@php $role = session('admin_user.role', 'admin'); @endphp

{{-- Stats grid --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

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

{{-- Quick actions --}}
<div class="bg-white rounded-2xl border border-gray-200 p-5">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Quick Actions</p>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('orders.index') }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-700 font-medium hover:bg-gray-100 transition">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            All Orders
        </a>
        <a href="{{ route('orders.index', ['status' => 'pending']) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-sm text-amber-700 font-medium hover:bg-amber-100 transition">
            ⏳ Pending
        </a>
        <a href="{{ route('messages.index', ['unread' => 1]) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700 font-medium hover:bg-red-100 transition">
            💬 Unread
        </a>
        @if(in_array($role, ['admin', 'manager']))
        <a href="{{ route('orders.index', ['status' => 'in_progress']) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200 text-sm text-blue-700 font-medium hover:bg-blue-100 transition">
            🔄 In Progress
        </a>
        <a href="{{ route('writers.index') }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 border border-indigo-200 text-sm text-indigo-700 font-medium hover:bg-indigo-100 transition">
            ✍️ Writers
        </a>
        @endif
    </div>
</div>
@endsection
