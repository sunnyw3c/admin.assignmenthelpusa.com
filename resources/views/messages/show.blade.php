@extends('layouts.app')
@section('title', 'Thread')
@section('heading')
    <div class="flex items-center gap-3">
        <a href="{{ route('messages.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <span>{{ $thread['assignment']['user']['name'] ?? 'Thread' }}</span>
        @php $assignment = $thread['assignment'] ?? []; @endphp
        <span class="font-mono text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded">{{ $assignment['order_number'] ?? '#'.($assignment['id'] ?? '') }}</span>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 h-[calc(100vh-130px)]">

    {{-- Chat column --}}
    <div class="xl:col-span-2 flex flex-col bg-white rounded-2xl border border-gray-200 overflow-hidden">

        {{-- Messages scroll area --}}
        <div class="flex-1 overflow-y-auto p-5 space-y-4" id="msg-scroll">
            @forelse($thread['messages'] ?? [] as $msg)
            @php
                $isMe = ($msg['sender']['name'] ?? '') === session('admin_user.name');
            @endphp
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} gap-2.5">
                @if(!$isMe)
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-auto">
                    {{ strtoupper(substr($msg['sender']['name'] ?? 'U', 0, 1)) }}
                </div>
                @endif
                <div class="max-w-sm">
                    <div class="flex items-center gap-2 mb-1 {{ $isMe ? 'justify-end' : '' }}">
                        <span class="text-xs font-medium text-gray-500">{{ $msg['sender']['name'] ?? 'Unknown' }}</span>
                        <span class="text-[10px] text-gray-300">{{ isset($msg['created_at']) ? \Carbon\Carbon::parse($msg['created_at'])->format('M j, H:i') : '' }}</span>
                    </div>
                    <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                        {{ $isMe
                            ? 'bg-indigo-600 text-white rounded-tr-sm'
                            : 'bg-gray-100 text-gray-800 rounded-tl-sm' }}">
                        {{ $msg['body'] }}
                    </div>
                </div>
            </div>
            @empty
            <div class="flex-1 flex flex-col items-center justify-center py-16 text-gray-300">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <p class="text-sm">No messages yet</p>
            </div>
            @endforelse
        </div>

        {{-- Reply box --}}
        <div class="border-t border-gray-100 p-4">
            <form method="POST" action="{{ route('messages.reply', $assignment['id'] ?? 0) }}" x-data="{ body: '' }">
                @csrf
                <div class="flex gap-3 items-end">
                    <div class="flex-1">
                        <textarea name="body" x-model="body" rows="2" required
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none transition placeholder-gray-400"
                                  placeholder="Write a reply..."></textarea>
                    </div>
                    <button type="submit"
                            :disabled="!body.trim()"
                            class="flex-shrink-0 w-10 h-10 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed text-white rounded-xl flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Info sidebar --}}
    <div class="space-y-4 overflow-y-auto">

        {{-- Order info --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Order</h2>
            <dl class="space-y-2.5">
                <div>
                    <dt class="text-[10px] text-gray-400 uppercase tracking-wide">Order #</dt>
                    <dd class="text-sm font-mono font-medium text-gray-700">{{ $assignment['order_number'] ?? '#'.($assignment['id'] ?? '—') }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] text-gray-400 uppercase tracking-wide">Title</dt>
                    <dd class="text-sm text-gray-700 font-medium">{{ $assignment['title'] ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] text-gray-400 uppercase tracking-wide">Status</dt>
                    <dd class="mt-0.5"><x-status-badge :status="$assignment['status'] ?? 'pending'" type="order"/></dd>
                </div>
                <div>
                    <dt class="text-[10px] text-gray-400 uppercase tracking-wide">Deadline</dt>
                    <dd class="text-sm text-gray-700">{{ ($assignment['deadline'] ?? null) ? \Carbon\Carbon::parse($assignment['deadline'])->format('M j, Y') : '—' }}</dd>
                </div>
            </dl>
            @if(!empty($assignment['id']))
            <a href="{{ route('orders.show', $assignment['id']) }}"
               class="mt-4 flex items-center gap-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-800 transition">
                Open full order
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endif
        </div>

        {{-- Student info --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Student</h2>
            @php $user = $assignment['user'] ?? []; @endphp
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $user['name'] ?? '—' }}</p>
                    <p class="text-xs text-gray-400">{{ $user['email'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const el = document.getElementById('msg-scroll');
    if (el) el.scrollTop = el.scrollHeight;
</script>
@endsection
