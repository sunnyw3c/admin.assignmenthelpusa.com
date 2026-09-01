@extends('layouts.app')
@section('title', 'Website Content')
@section('heading', 'Website Content')

@section('content')

{{-- Built-in Pages --}}
<div class="mb-6">
    <h2 class="text-xs font-semibold text-zinc-400 uppercase tracking-wide mb-3">Built-in Pages</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($builtInPages as $slug => $info)
        <a href="{{ route('cms.edit', $slug) }}"
           class="group card p-5 hover:shadow-md transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center text-xl">
                    {{ $info['icon'] }}
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 group-hover:text-accent-content transition">{{ $info['name'] }}</h3>
                    <p class="text-xs text-zinc-400">{{ $info['description'] }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-zinc-400">{{ config('app.main_api_url') }}/{{ $slug }}</span>
                <span class="text-xs font-semibold text-accent-content opacity-0 group-hover:opacity-100 transition">Edit →</span>
            </div>
        </a>
        @endforeach
    </div>
</div>

{{-- Dynamic Pages --}}
<div>
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Custom Pages</h2>
        <button onclick="document.getElementById('create-page-modal').classList.remove('hidden')"
                class="flex items-center gap-1.5 text-xs font-semibold text-accent-content hover:opacity-80 bg-accent/10 hover:bg-accent/20 px-3 py-1.5 rounded-lg transition">
            + New Page
        </button>
    </div>

    @if(count($dynamicPages) > 0)
    <div class="card overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-zinc-100 dark:border-zinc-800">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wide">Page</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wide">URL</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @foreach($dynamicPages as $page)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50/60 group transition-colors">
                    <td class="px-5 py-3 text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $page['name'] }}</td>
                    <td class="px-5 py-3 text-xs text-zinc-400 font-mono">/{{ $page['slug'] }}</td>
                    <td class="px-5 py-3">
                        @if($page['is_active'])
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-0.5 rounded-full ring-1 ring-emerald-200 dark:ring-emerald-500/30">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Live
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-500 bg-zinc-100 dark:bg-zinc-800 px-2.5 py-0.5 rounded-full ring-1 ring-zinc-200 dark:ring-zinc-800">
                            <span class="w-1.5 h-1.5 bg-zinc-400 rounded-full"></span>Hidden
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 flex items-center gap-2 justify-end">
                        <a href="{{ route('cms.edit', $page['slug']) }}"
                           class="text-xs font-medium text-accent-content hover:opacity-80 opacity-0 group-hover:opacity-100 transition">Edit →</a>
                        <form method="POST" action="{{ route('cms.delete-page', $page['slug']) }}"
                              onsubmit="return confirm('Delete this page and all its sections?')">
                            @csrf
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600 dark:text-red-400 opacity-0 group-hover:opacity-100 transition">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="card py-16 text-center">
        <div class="text-4xl mb-3">📄</div>
        <p class="text-sm text-zinc-400">No custom pages yet. Create one to get started.</p>
    </div>
    @endif
</div>

{{-- Create Page Modal --}}
<div id="create-page-modal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-xl w-full max-w-md p-6">
        <h2 class="text-base font-bold text-zinc-900 dark:text-zinc-100 mb-5">Create New Page</h2>
        <form method="POST" action="{{ route('cms.create-page') }}" x-data="{ name: '', slug: '' }">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1.5">Page Name</label>
                    <input type="text" name="name" required placeholder="e.g. Nursing Assignment Help"
                           x-model="name"
                           @input="slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')"
                           class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1.5">URL Slug</label>
                    <div class="flex items-center border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden bg-zinc-50 dark:bg-zinc-800/50 focus-within:ring-2 focus-within:ring-accent/40 focus-within:bg-white dark:focus-within:bg-zinc-900 transition">
                        <span class="px-3 text-xs text-zinc-400 border-r border-zinc-200 dark:border-zinc-800 py-2.5">/</span>
                        <input type="text" name="slug" required x-model="slug" placeholder="nursing-assignment-help"
                               class="flex-1 px-3 py-2.5 text-sm bg-transparent focus:outline-none">
                    </div>
                </div>
            </div>
            <div class="flex gap-2 mt-5">
                <button type="button"
                        onclick="document.getElementById('create-page-modal').classList.add('hidden')"
                        class="flex-1 border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400 text-sm font-medium py-2.5 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-accent hover:opacity-90 text-accent-foreground text-sm font-medium py-2.5 rounded-xl transition">
                    Create Page
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
