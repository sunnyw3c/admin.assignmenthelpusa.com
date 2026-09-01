@extends('layouts.app')
@section('title', 'New Service')
@section('heading', 'New Assignment Service')

@section('content')

<div class="max-w-2xl">
    <a href="{{ route('services-editor.index') }}" class="inline-flex items-center gap-1.5 text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 mb-5 transition">
        ← Back to Services
    </a>

    @if($errors->any())
    <div class="mb-4 text-sm text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-xl px-4 py-3">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('services-editor.store') }}" x-data="{ name: '', slug: '' }">
        @csrf
        <div class="card p-6 space-y-5">
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Service Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" required value="{{ old('name') }}"
                       x-model="name"
                       @input="slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')"
                       placeholder="e.g. Essay Writing Help"
                       class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">URL Slug <span class="text-red-400">*</span></label>
                <div class="flex items-center border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden bg-zinc-50 dark:bg-zinc-800/50 focus-within:ring-2 focus-within:ring-accent/40 focus-within:bg-white dark:focus-within:bg-zinc-900 transition">
                    <span class="px-3 text-xs text-zinc-400 border-r border-zinc-200 dark:border-zinc-800 py-2.5">/</span>
                    <input type="text" name="slug" required x-model="slug" placeholder="essay-writing-help"
                           class="flex-1 px-3 py-2.5 text-sm bg-transparent focus:outline-none font-mono">
                </div>
                <p class="text-xs text-zinc-400 mt-1">This will be the URL: /assignment/{slug}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1.5">Icon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="📝"
                           class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-zinc-500 mb-1.5">Base Price / Page ($)</label>
                    <input type="number" name="base_price_per_page" value="{{ old('base_price_per_page') }}" step="0.01" placeholder="15.00"
                           class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Short Description</label>
                <textarea name="short_description" rows="2" placeholder="Brief description shown in service cards"
                          class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">{{ old('short_description') }}</textarea>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <a href="{{ route('services-editor.index') }}"
               class="flex-1 text-center border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400 text-sm font-medium py-2.5 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                Cancel
            </a>
            <button type="submit"
                    class="flex-1 bg-accent hover:opacity-90 text-accent-foreground text-sm font-medium py-2.5 rounded-xl transition">
                Create Service
            </button>
        </div>
    </form>
</div>

@endsection
