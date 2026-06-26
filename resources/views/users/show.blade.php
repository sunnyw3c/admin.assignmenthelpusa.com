@extends('layouts.app')
@section('title', $user['name'] ?? 'User')
@section('heading')
    <div class="flex items-center gap-3">
        <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <span>{{ $user['name'] ?? 'User' }}</span>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 capitalize ring-1 ring-gray-200">{{ $user['role'] ?? 'student' }}</span>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Left --}}
    <div class="xl:col-span-2 space-y-4">

        {{-- Profile card --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xl font-bold">
                    {{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $user['name'] ?? '—' }}</h2>
                    <p class="text-sm text-gray-400">{{ $user['email'] ?? '' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Joined {{ isset($user['created_at']) ? \Carbon\Carbon::parse($user['created_at'])->format('M j, Y') : '—' }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <p class="text-xl font-bold text-gray-900">{{ $user['order_count'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Orders</p>
                </div>
                <div class="text-center p-3 bg-emerald-50 rounded-xl">
                    <p class="text-xl font-bold text-emerald-700">${{ number_format($user['total_spent'] ?? 0, 2) }}</p>
                    <p class="text-xs text-emerald-400 mt-0.5">Total spent</p>
                </div>
                <div class="text-center p-3 bg-indigo-50 rounded-xl">
                    <p class="text-xl font-bold text-indigo-700 capitalize">{{ $user['role'] ?? 'student' }}</p>
                    <p class="text-xs text-indigo-400 mt-0.5">Role</p>
                </div>
            </div>
        </div>

        {{-- Recent orders --}}
        @if(!empty($user['orders']))
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-900">Recent Orders</h2>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-50">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Order</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Title</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Budget</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($user['orders'] as $order)
                    <tr class="hover:bg-gray-50/60 group transition-colors">
                        <td class="px-5 py-3"><span class="font-mono text-xs text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">{{ $order['order_number'] ?? '#'.($order['id'] ?? '') }}</span></td>
                        <td class="px-5 py-3 text-sm text-gray-700 max-w-[200px] truncate">{{ $order['title'] ?? '—' }}</td>
                        <td class="px-5 py-3"><x-status-badge :status="$order['status'] ?? 'pending'" type="order"/></td>
                        <td class="px-5 py-3 text-right text-sm font-semibold text-gray-700">${{ number_format($order['budget'] ?? 0, 2) }}</td>
                        <td class="px-5 py-3">
                            <a href="{{ route('orders.show', $order['id'] ?? 0) }}"
                               class="text-xs text-indigo-600 hover:text-indigo-800 font-medium opacity-0 group-hover:opacity-100 transition">View →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Right --}}
    <div class="space-y-4">

        {{-- Edit --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">Edit User</h2>
            <form method="POST" action="{{ route('users.update', $user['id'] ?? 0) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Full name</label>
                    <input type="text" name="name" value="{{ $user['name'] ?? '' }}" required
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ $user['email'] ?? '' }}" required
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2.5 rounded-xl transition">
                    Save Changes
                </button>
            </form>
        </div>

        {{-- Change role (admin only) --}}
        @if(session('admin_user.role') === 'admin')
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">Change Role</h2>
            <form method="POST" action="{{ route('users.role', $user['id'] ?? 0) }}" class="space-y-3">
                @csrf
                <select name="role"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    @foreach(['student','writer','support','manager','executive','admin'] as $r)
                    <option value="{{ $r }}" {{ ($user['role'] ?? '') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
                <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium py-2.5 rounded-xl transition">
                    Update Role
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
