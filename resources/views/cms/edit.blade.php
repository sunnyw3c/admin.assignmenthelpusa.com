@extends('layouts.app')
@section('title', $pageName . ' — CMS')
@section('heading')
<div class="flex items-center gap-3">
    <a href="{{ route('cms.index') }}" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <span>{{ $pageName }}</span>
    <span class="text-xs text-zinc-400 font-mono bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 rounded">/{{ $page }}</span>
</div>
@endsection

@section('content')
<div x-data="{ tab: 'sections' }" class="space-y-5">

{{-- Tabs --}}
<div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 px-5 py-2.5 flex items-center gap-1">
    <button @click="tab = 'sections'"
            :class="tab === 'sections' ? 'bg-accent/10 text-accent-content' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
            class="px-4 py-2 text-sm font-medium rounded-lg transition">
        Sections
    </button>
    <button @click="tab = 'seo'"
            :class="tab === 'seo' ? 'bg-accent/10 text-accent-content' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
            class="px-4 py-2 text-sm font-medium rounded-lg transition">
        SEO
    </button>
    <button @click="tab = 'history'"
            :class="tab === 'history' ? 'bg-accent/10 text-accent-content' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
            class="px-4 py-2 text-sm font-medium rounded-lg transition">
        History
        @if(count($revisions) > 0)
        <span class="ml-1 text-[10px] bg-zinc-200 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400 rounded-full px-1.5 py-0.5">{{ count($revisions) }}</span>
        @endif
    </button>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-sm px-4 py-3 rounded-xl">{{ session('success') }}</div>
@endif

{{-- ══ SECTIONS TAB ══════════════════════════════════════════════════════════ --}}
<div x-show="tab === 'sections'" x-transition>

    {{-- Add section button --}}
    <div class="flex justify-end mb-4" x-data="{ open: false }">
        <div class="relative">
            <button @click="open = !open"
                    class="flex items-center gap-2 bg-accent hover:opacity-90 text-accent-foreground text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm">
                + Add Section
                <svg class="w-3.5 h-3.5 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition
                 class="absolute right-0 mt-2 w-64 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-xl z-20 overflow-hidden">
                <div class="p-2">
                    @foreach($sectionTypes as $typeKey => $typeInfo)
                    <form method="POST" action="{{ route('cms.add-section', $page) }}">
                        @csrf
                        <input type="hidden" name="type" value="{{ $typeKey }}">
                        <input type="hidden" name="label" value="{{ $typeInfo['label'] }}">
                        <button type="submit"
                                class="w-full text-left px-3 py-2.5 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-accent/10 hover:opacity-80 rounded-xl transition flex items-center gap-2.5">
                            <span class="text-base">{{ ['hero'=>'🦸','steps'=>'🔢','features'=>'✨','stats'=>'📊','faq'=>'❓','pricing'=>'💰','testimonials'=>'⭐','text_block'=>'📝','service_list'=>'🔧','language_list'=>'💻'][$typeKey] ?? '📄' }}</span>
                            <div>
                                <div class="font-medium">{{ $typeInfo['label'] }}</div>
                                <div class="text-xs text-zinc-400">{{ $typeInfo['description'] ?? '' }}</div>
                            </div>
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Section list (SortableJS) --}}
    @if(count($sections) > 0)
    <div id="section-list" class="space-y-3">
        @foreach($sections as $section)
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden transition hover:shadow-sm"
             data-section-id="{{ $section['id'] }}"
             x-data="{ editing: false }">

            {{-- Section card header --}}
            <div class="flex items-center gap-3 px-4 py-3.5">
                {{-- Drag handle --}}
                <div class="drag-handle cursor-grab text-zinc-300 dark:text-zinc-600 hover:text-zinc-500 transition flex-shrink-0">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <circle cx="7" cy="5" r="1.5"/><circle cx="13" cy="5" r="1.5"/>
                        <circle cx="7" cy="10" r="1.5"/><circle cx="13" cy="10" r="1.5"/>
                        <circle cx="7" cy="15" r="1.5"/><circle cx="13" cy="15" r="1.5"/>
                    </svg>
                </div>

                {{-- Type icon + name --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ $section['label'] ?? $section['type'] }}</span>
                        <span class="text-xs text-zinc-400 bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded font-mono">{{ $section['type'] }}</span>
                    </div>
                    <p class="text-xs text-zinc-400 mt-0.5">
                        @if(!empty($section['data']))
                            @php $keys = array_keys((array)$section['data']); @endphp
                            {{ implode(' · ', array_map('ucfirst', array_slice($keys, 0, 3))) }}
                        @endif
                    </p>
                </div>

                {{-- Status toggle --}}
                <form method="POST" action="{{ route('cms.toggle-section', $section['id']) }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full transition
                        {{ $section['is_active']
                            ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-100 dark:bg-emerald-500/15 dark:hover:bg-emerald-500/20'
                            : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-500 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $section['is_active'] ? 'bg-emerald-500' : 'bg-zinc-400' }}"></span>
                        {{ $section['is_active'] ? 'Live' : 'Hidden' }}
                    </button>
                </form>

                {{-- Actions --}}
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button @click="editing = !editing"
                            class="p-1.5 text-zinc-400 hover:text-accent-content hover:bg-accent/10 rounded-lg transition text-xs font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <form method="POST" action="{{ route('cms.delete-section', $section['id']) }}"
                          onsubmit="return confirm('Delete this section?')">
                        @csrf
                        <input type="hidden" name="page" value="{{ $page }}">
                        <button type="submit" class="p-1.5 text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:bg-red-500/10 dark:hover:bg-red-500/15 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Inline editor --}}
            <div x-show="editing"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="border-t border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50/60 px-5 py-5">
                <form method="POST" action="{{ route('cms.update-section', $section['id']) }}">
                    @csrf
                    <input type="hidden" name="page" value="{{ $page }}">
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-zinc-500 mb-1">Label</label>
                        <input type="text" name="label" value="{{ $section['label'] ?? '' }}"
                               class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                    </div>
                    @include('cms.partials.editors.' . $section['type'], ['data' => $section['data'] ?? []])
                    <div class="flex gap-2 mt-5">
                        <button type="button" @click="editing = false"
                                class="px-4 py-2 text-sm text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-800 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 bg-accent hover:opacity-90 text-accent-foreground text-sm font-semibold py-2 rounded-xl transition">
                            Save Section
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 border-dashed py-20 text-center">
        <div class="text-5xl mb-3">📄</div>
        <p class="text-sm text-zinc-400 mb-2">No sections yet</p>
        <p class="text-xs text-zinc-300 dark:text-zinc-600">Click "+ Add Section" to add your first section</p>
    </div>
    @endif
</div>

{{-- ══ SEO TAB ═══════════════════════════════════════════════════════════════ --}}
<div x-show="tab === 'seo'" x-transition>
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
        <form method="POST" action="{{ route('cms.meta', $page) }}">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1.5">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ $meta['meta_title'] ?? '' }}"
                           placeholder="Page title for Google (50-60 chars)"
                           class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1.5">Meta Description</label>
                    <textarea name="meta_description" rows="3" placeholder="Page description for Google (120-155 chars)"
                              class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition resize-none">{{ $meta['meta_description'] ?? '' }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 mb-1.5">OG Title</label>
                        <input type="text" name="og_title" value="{{ $meta['og_title'] ?? '' }}"
                               placeholder="Same as meta title if empty"
                               class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 mb-1.5">OG Image URL</label>
                        <input type="text" name="og_image" value="{{ $meta['og_image'] ?? '' }}"
                               placeholder="https://..."
                               class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1.5">OG Description</label>
                    <textarea name="og_description" rows="2" placeholder="Social media description"
                              class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition resize-none">{{ $meta['og_description'] ?? '' }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 mb-1.5">Canonical URL</label>
                        <input type="text" name="canonical_url" value="{{ $meta['canonical_url'] ?? '' }}"
                               placeholder="Leave blank for current URL"
                               class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-zinc-500 mb-1.5">Keywords</label>
                        <input type="text" name="keywords" value="{{ $meta['keywords'] ?? '' }}"
                               placeholder="comma, separated, keywords"
                               class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1.5">Robots</label>
                    <select name="robots"
                            class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                        @foreach(['index, follow', 'noindex, follow', 'index, nofollow', 'noindex, nofollow'] as $r)
                        <option value="{{ $r }}" {{ ($meta['robots'] ?? 'index, follow') === $r ? 'selected' : '' }}>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-5">
                <button type="submit"
                        class="bg-accent hover:opacity-90 text-accent-foreground text-sm font-semibold px-6 py-2.5 rounded-xl transition">
                    Save SEO
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ HISTORY TAB ═══════════════════════════════════════════════════════════ --}}
<div x-show="tab === 'history'" x-transition>
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        @if(count($revisions) > 0)
        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @foreach($revisions as $rev)
            <div class="px-5 py-4 flex items-start gap-4" x-data="{ open: false }">
                {{-- Avatar --}}
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-accent to-purple-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5">
                    {{ strtoupper(substr($rev['user_name'] ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-baseline gap-2 mb-0.5">
                        <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ $rev['user_name'] ?? 'Unknown' }}</span>
                        <span class="text-xs text-zinc-400">{{ \Carbon\Carbon::parse($rev['created_at'])->diffForHumans() }}</span>
                        <span class="text-[10px] font-mono text-zinc-300 dark:text-zinc-600 bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded capitalize">{{ $rev['action'] }}</span>
                    </div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $rev['summary'] ?? '—' }}</p>
                    @if(!empty($rev['old_data']) || !empty($rev['new_data']))
                    <button @click="open = !open" class="text-xs text-accent-content hover:opacity-80 mt-1">
                        <span x-text="open ? 'hide diff ▲' : 'show diff ▼'"></span>
                    </button>
                    <div x-show="open" class="mt-2 bg-zinc-50 dark:bg-zinc-800/50 rounded-xl p-3 text-xs font-mono text-zinc-500 overflow-auto max-h-40">
                        {{ json_encode($rev['new_data'] ?? $rev['old_data'], JSON_PRETTY_PRINT) }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="py-16 text-center">
            <div class="text-4xl mb-3">📜</div>
            <p class="text-sm text-zinc-400">No changes recorded yet</p>
        </div>
        @endif
    </div>
</div>

</div>

{{-- SortableJS for drag-and-drop --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const list = document.getElementById('section-list');
if (list) {
    Sortable.create(list, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'opacity-40',
        onEnd(evt) {
            const ids = [...list.querySelectorAll('[data-section-id]')]
                .map(el => el.dataset.sectionId);
            fetch('{{ route('cms.reorder', $page) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids })
            });
        }
    });
}
</script>
@endsection
