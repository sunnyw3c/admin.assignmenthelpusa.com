@extends('layouts.app')
@section('title', 'New Service')
@section('heading', 'New Assignment Service')

@section('content')

<div class="max-w-2xl">
    <a href="{{ route('services-editor.index') }}" class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-gray-600 mb-5 transition">
        ← Back to Services
    </a>

    @if($errors->any())
    <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('services-editor.store') }}" x-data="{ name: '', slug: '' }">
        @csrf
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Service Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" required value="{{ old('name') }}"
                       x-model="name"
                       @input="slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')"
                       placeholder="e.g. Essay Writing Help"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">URL Slug <span class="text-red-400">*</span></label>
                <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:bg-white transition">
                    <span class="px-3 text-xs text-gray-400 border-r border-gray-200 py-2.5">/</span>
                    <input type="text" name="slug" required x-model="slug" placeholder="essay-writing-help"
                           class="flex-1 px-3 py-2.5 text-sm bg-transparent focus:outline-none font-mono">
                </div>
                <p class="text-xs text-gray-400 mt-1">This will be the URL: /assignment/{slug}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Icon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="📝"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Base Price / Page ($)</label>
                    <input type="number" name="base_price_per_page" value="{{ old('base_price_per_page') }}" step="0.01" placeholder="15.00"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Short Description</label>
                <textarea name="short_description" rows="2" placeholder="Brief description shown in service cards"
                          class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">{{ old('short_description') }}</textarea>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <a href="{{ route('services-editor.index') }}"
               class="flex-1 text-center border border-gray-200 text-gray-600 text-sm font-medium py-2.5 rounded-xl hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2.5 rounded-xl transition">
                Create Service
            </button>
        </div>
    </form>
</div>

@endsection
