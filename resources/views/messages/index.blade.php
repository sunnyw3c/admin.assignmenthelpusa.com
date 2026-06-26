@extends('layouts.app')
@section('title', 'Messages')
@section('heading', 'Messages')

@section('content')
{{-- Filters --}}
<div class="bg-white rounded-2xl border border-gray-200 px-5 py-3.5 mb-4 flex flex-wrap items-center gap-3">
    <form method="GET" action="{{ route('messages.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                   placeholder="Search messages..."
                   class="pl-8 pr-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 w-52 transition">
        </div>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="unread" value="1" {{ !empty($filters['unread']) ? 'checked' : '' }}
                   class="w-4 h-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
            <span class="text-sm text-gray-600 font-medium">Unread only</span>
        </label>
        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">Apply</button>
        @if(array_filter($filters))
        <a href="{{ route('messages.index') }}" class="text-sm text-gray-400 hover:text-gray-600 transition">Clear</a>
        @endif
    </form>
</div>

{{-- Thread list --}}
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <div class="divide-y divide-gray-50">
        @forelse($threads['data'] ?? [] as $thread)
        <a href="{{ route('messages.show', $thread['id'] ?? 0) }}"
           class="flex items-start gap-4 px-5 py-4 hover:bg-gray-50/60 transition-colors group">

            {{-- Avatar --}}
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 mt-0.5">
                {{ strtoupper(substr($thread['user']['name'] ?? 'U', 0, 1)) }}
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-baseline justify-between gap-2 mb-0.5">
                    <span class="text-sm font-semibold text-gray-900 truncate">{{ $thread['user']['name'] ?? '—' }}</span>
                    <span class="text-xs text-gray-400 flex-shrink-0">
                        {{ isset($thread['last_message']['created_at']) ? \Carbon\Carbon::parse($thread['last_message']['created_at'])->diffForHumans() : '' }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 mb-1">
                    <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-[10px]">{{ $thread['order_number'] ?? '#'.($thread['id'] ?? '') }}</span>
                    {{ $thread['title'] ?? '' }}
                </p>
                <p class="text-sm text-gray-500 truncate">{{ $thread['last_message']['body'] ?? 'No messages yet' }}</p>
            </div>

            {{-- Unread badge --}}
            @if(($thread['unread_count'] ?? 0) > 0)
            <div class="flex-shrink-0 mt-1">
                <span class="w-5 h-5 bg-red-500 text-white rounded-full text-[10px] font-bold flex items-center justify-center">
                    {{ $thread['unread_count'] }}
                </span>
            </div>
            @endif
        </a>
        @empty
        <div class="py-20 text-center">
            <div class="text-5xl mb-3">💬</div>
            <p class="text-sm text-gray-400">No message threads yet</p>
        </div>
        @endforelse
    </div>

    @if(isset($threads['last_page']) && $threads['last_page'] > 1)
    <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between">
        <span class="text-xs text-gray-400">Page {{ $threads['current_page'] }} of {{ $threads['last_page'] }}</span>
        <div class="flex items-center gap-1">
            @if($threads['current_page'] > 1)
            <a href="{{ route('messages.index', array_merge($filters, ['page' => $threads['current_page'] - 1])) }}"
               class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">← Prev</a>
            @endif
            @if($threads['current_page'] < $threads['last_page'])
            <a href="{{ route('messages.index', array_merge($filters, ['page' => $threads['current_page'] + 1])) }}"
               class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">Next →</a>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
