@extends('layouts.app')
@section('title', 'Thread')
{{-- Phrasing content, not a <div>: the layout yields this into an <h1> in
     both the desktop header and the mobile heading strip. --}}
@section('heading')
    <span class="flex min-w-0 items-center gap-3">
        <a href="{{ route('messages.index') }}" aria-label="Back to messages"
           class="-ml-1 inline-flex size-8 shrink-0 items-center justify-center rounded-lg text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-600 dark:hover:bg-zinc-800 dark:hover:text-zinc-300">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <span class="truncate">{{ $thread['assignment']['user']['name'] ?? 'Thread' }}</span>
        @php $assignment = $thread['assignment'] ?? []; @endphp
        <span class="shrink-0 font-mono text-xs text-zinc-400 bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 rounded">{{ $assignment['order_number'] ?? '#'.($assignment['id'] ?? '') }}</span>
    </span>
@endsection

@section('content')
{{-- The fixed height only makes sense once the two columns sit side by side.
     Below xl they stack, and pinning the pair to one viewport height squeezed
     the conversation into a third of the screen. --}}
<div class="grid grid-cols-1 gap-5 xl:h-[calc(100vh-8rem)] xl:grid-cols-3">

    {{-- Chat column --}}
    <div class="xl:col-span-2 flex flex-col card overflow-hidden">

        {{-- Messages scroll area --}}
        <div class="min-h-[24rem] flex-1 overflow-y-auto p-5 space-y-4" id="msg-scroll">
            @forelse($thread['messages'] ?? [] as $msg)
            @php
                $isMe = ($msg['sender']['name'] ?? '') === auth()->user()->name;
            @endphp
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} gap-2.5">
                @if(!$isMe)
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-accent to-purple-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-auto">
                    {{ strtoupper(substr($msg['sender']['name'] ?? 'U', 0, 1)) }}
                </div>
                @endif
                <div class="max-w-sm">
                    <div class="flex items-center gap-2 mb-1 {{ $isMe ? 'justify-end' : '' }}">
                        <span class="text-xs font-medium text-zinc-500">{{ $msg['sender']['name'] ?? 'Unknown' }}</span>
                        <span class="text-[10px] text-zinc-300 dark:text-zinc-600">{{ isset($msg['created_at']) ? \Carbon\Carbon::parse($msg['created_at'])->format('M j, H:i') : '' }}</span>
                    </div>
                    <div class="px-4 py-2.5 rounded-xl text-sm leading-relaxed
                        {{ $isMe
                            ? 'bg-accent text-accent-foreground rounded-tr-sm'
                            : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 rounded-tl-sm' }}">
                        {{ $msg['body'] }}
                    </div>
                </div>
            </div>
            @empty
            <div class="flex-1 flex flex-col items-center justify-center py-16 text-zinc-300 dark:text-zinc-600">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <p class="text-sm">No messages yet</p>
            </div>
            @endforelse
        </div>

        {{-- Reply box --}}
        <div class="border-t border-zinc-100 dark:border-zinc-800 p-4">
            <form method="POST" action="{{ route('messages.reply', $assignment['id'] ?? 0) }}" x-data="{ body: '' }">
                @csrf
                <div class="flex gap-3 items-end">
                    <div class="flex-1">
                        <textarea name="body" x-model="body" rows="2" required
                                  class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-3 text-sm text-zinc-800 dark:text-zinc-200 bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 resize-none transition placeholder:text-zinc-400"
                                  placeholder="Write a reply..."></textarea>
                    </div>
                    <button type="submit"
                            :disabled="!body.trim()"
                            class="flex-shrink-0 w-10 h-10 bg-accent hover:opacity-90 disabled:opacity-40 disabled:cursor-not-allowed text-accent-foreground rounded-xl flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Info sidebar --}}
    <div class="space-y-4 xl:overflow-y-auto">

        {{-- Order info --}}
        <div class="card p-5">
            <h2 class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-3">Order</h2>
            <dl class="space-y-2.5">
                <div>
                    <dt class="text-[10px] text-zinc-400 uppercase tracking-wide">Order #</dt>
                    <dd class="text-sm font-mono font-medium text-zinc-700 dark:text-zinc-300">{{ $assignment['order_number'] ?? '#'.($assignment['id'] ?? '—') }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] text-zinc-400 uppercase tracking-wide">Title</dt>
                    <dd class="text-sm text-zinc-700 dark:text-zinc-300 font-medium">{{ $assignment['title'] ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] text-zinc-400 uppercase tracking-wide">Status</dt>
                    <dd class="mt-0.5"><x-status-badge :status="$assignment['status'] ?? 'pending'" type="order"/></dd>
                </div>
                <div>
                    <dt class="text-[10px] text-zinc-400 uppercase tracking-wide">Deadline</dt>
                    <dd class="text-sm text-zinc-700 dark:text-zinc-300">{{ ($assignment['deadline'] ?? null) ? \Carbon\Carbon::parse($assignment['deadline'])->format('M j, Y') : '—' }}</dd>
                </div>
            </dl>
            @if(!empty($assignment['id']))
            <a href="{{ route('orders.show', $assignment['id']) }}"
               class="mt-4 flex items-center gap-1.5 text-xs font-medium text-accent-content hover:opacity-80 transition">
                Open full order
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endif
        </div>

        {{-- Student info --}}
        <div class="card p-5">
            <h2 class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-3">Student</h2>
            @php $user = $assignment['user'] ?? []; @endphp
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-accent to-purple-500 flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ $user['name'] ?? '—' }}</p>
                    <p class="text-xs text-zinc-400">{{ $user['email'] ?? '' }}</p>
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
