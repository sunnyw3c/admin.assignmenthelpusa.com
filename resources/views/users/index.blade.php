@extends('layouts.app')
@section('title', 'Users')
@section('heading', 'Users')

@section('content')
{{-- Filters --}}
<div class="bg-white rounded-2xl border border-gray-200 px-5 py-3.5 mb-4 flex flex-wrap items-center gap-3">
    <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                   placeholder="Search users..."
                   class="pl-8 pr-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 w-52 transition">
        </div>
        <select name="role" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-600">
            <option value="">All roles</option>
            @foreach(['student','writer','support','manager','admin','executive'] as $r)
            <option value="{{ $r }}" {{ ($filters['role'] ?? '') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">Apply</button>
        @if(array_filter($filters))
        <a href="{{ route('users.index') }}" class="text-sm text-gray-400 hover:text-gray-600 transition">Clear</a>
        @endif
    </form>
    @if(isset($users['total']))
    <span class="text-xs text-gray-400 font-medium ml-auto">{{ $users['total'] }} users</span>
    @endif
</div>

<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">User</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Role</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Orders</th>
                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wide">Joined</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users['data'] ?? [] as $user)
            <tr class="hover:bg-gray-50/60 transition-colors group">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $user['name'] ?? '—' }}</p>
                            <p class="text-xs text-gray-400">{{ $user['email'] ?? '' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3.5">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 capitalize ring-1 ring-gray-200">
                        {{ $user['role'] ?? 'student' }}
                    </span>
                </td>
                <td class="px-5 py-3.5 text-sm text-gray-500">{{ $user['order_count'] ?? 0 }}</td>
                <td class="px-5 py-3.5 text-xs text-gray-400">
                    {{ isset($user['created_at']) ? \Carbon\Carbon::parse($user['created_at'])->format('M j, Y') : '—' }}
                </td>
                <td class="px-5 py-3.5">
                    <a href="{{ route('users.show', $user['id'] ?? 0) }}"
                       class="text-xs font-medium text-indigo-600 hover:text-indigo-800 opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                        Open →
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-16 text-center">
                    <div class="text-gray-300 text-4xl mb-3">👤</div>
                    <p class="text-sm text-gray-400">No users found</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if(isset($users['last_page']) && $users['last_page'] > 1)
    <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between">
        <span class="text-xs text-gray-400">Page {{ $users['current_page'] }} of {{ $users['last_page'] }}</span>
        <div class="flex items-center gap-1">
            @if($users['current_page'] > 1)
            <a href="{{ route('users.index', array_merge($filters, ['page' => $users['current_page'] - 1])) }}"
               class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">← Prev</a>
            @endif
            @if($users['current_page'] < $users['last_page'])
            <a href="{{ route('users.index', array_merge($filters, ['page' => $users['current_page'] + 1])) }}"
               class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">Next →</a>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
