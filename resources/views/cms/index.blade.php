@extends('layouts.app')
@section('title', 'Website Content')
@section('heading', 'Website Content')

@section('content')

{{-- Built-in Pages --}}
<div class="mb-6">
    <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Built-in Pages</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($builtInPages as $slug => $info)
        <a href="{{ route('cms.edit', $slug) }}"
           class="group bg-white rounded-2xl border border-gray-200 p-5 hover:shadow-md transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-xl">
                    {{ $info['icon'] }}
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition">{{ $info['name'] }}</h3>
                    <p class="text-xs text-gray-400">{{ $info['description'] }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-400">{{ config('app.main_api_url') }}/{{ $slug }}</span>
                <span class="text-xs font-semibold text-indigo-500 opacity-0 group-hover:opacity-100 transition">Edit →</span>
            </div>
        </a>
        @endforeach
    </div>
</div>

{{-- Dynamic Pages --}}
<div>
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Custom Pages</h2>
        <button onclick="document.getElementById('create-page-modal').classList.remove('hidden')"
                class="flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
            + New Page
        </button>
    </div>

    @if(count($dynamicPages) > 0)
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Page</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">URL</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($dynamicPages as $page)
                <tr class="hover:bg-gray-50/60 group transition-colors">
                    <td class="px-5 py-3 text-sm font-medium text-gray-800">{{ $page['name'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-400 font-mono">/{{ $page['slug'] }}</td>
                    <td class="px-5 py-3">
                        @if($page['is_active'])
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full ring-1 ring-emerald-200">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Live
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full ring-1 ring-gray-200">
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>Hidden
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 flex items-center gap-2 justify-end">
                        <a href="{{ route('cms.edit', $page['slug']) }}"
                           class="text-xs font-medium text-indigo-600 hover:text-indigo-800 opacity-0 group-hover:opacity-100 transition">Edit →</a>
                        <form method="POST" action="{{ route('cms.delete-page', $page['slug']) }}"
                              onsubmit="return confirm('Delete this page and all its sections?')">
                            @csrf
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-gray-200 py-16 text-center">
        <div class="text-4xl mb-3">📄</div>
        <p class="text-sm text-gray-400">No custom pages yet. Create one to get started.</p>
    </div>
    @endif
</div>

{{-- Create Page Modal --}}
<div id="create-page-modal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h2 class="text-base font-bold text-gray-900 mb-5">Create New Page</h2>
        <form method="POST" action="{{ route('cms.create-page') }}" x-data="{ name: '', slug: '' }">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Page Name</label>
                    <input type="text" name="name" required placeholder="e.g. Nursing Assignment Help"
                           x-model="name"
                           @input="slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">URL Slug</label>
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:bg-white transition">
                        <span class="px-3 text-xs text-gray-400 border-r border-gray-200 py-2.5">/</span>
                        <input type="text" name="slug" required x-model="slug" placeholder="nursing-assignment-help"
                               class="flex-1 px-3 py-2.5 text-sm bg-transparent focus:outline-none">
                    </div>
                </div>
            </div>
            <div class="flex gap-2 mt-5">
                <button type="button"
                        onclick="document.getElementById('create-page-modal').classList.add('hidden')"
                        class="flex-1 border border-gray-200 text-gray-600 text-sm font-medium py-2.5 rounded-xl hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2.5 rounded-xl transition">
                    Create Page
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
