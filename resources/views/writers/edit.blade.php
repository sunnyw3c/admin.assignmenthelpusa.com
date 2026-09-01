@extends('layouts.app')
@section('title', 'Edit Writer')
@section('heading', 'Edit Writer')

@section('content')

@if(session('error'))
<div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl mb-4 text-sm">{{ session('error') }}</div>
@endif

@php
    $expertiseStr = is_array($writer['expertise'] ?? null) ? implode(', ', $writer['expertise']) : ($writer['expertise'] ?? '');
@endphp

<div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 max-w-2xl">
    <form action="{{ route('writers.update', $writer['id']) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Basic Info --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $writer['name'] ?? '') }}" required
                    class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40 @error('name') border-red-400 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $writer['email'] ?? '') }}" required
                    class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40 @error('email') border-red-400 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1">New Password <span class="text-zinc-400 font-normal">(leave blank to keep current)</span></label>
            <input type="password" name="password"
                class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40 @error('password') border-red-400 @enderror">
            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Profile --}}
        <div>
            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Title / Degree</label>
            <input type="text" name="title" value="{{ old('title', $writer['title'] ?? '') }}" placeholder="e.g. PhD in Mathematics"
                class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40">
        </div>

        <div>
            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Bio</label>
            <textarea name="bio" rows="3" placeholder="Short description about the writer..."
                class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40 resize-none">{{ old('bio', $writer['bio'] ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Expertise / Subjects</label>
            <input type="text" name="expertise" value="{{ old('expertise', $expertiseStr) }}" placeholder="Math, Physics, Programming (comma-separated)"
                class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40">
            <p class="text-xs text-zinc-400 mt-1">Separate subjects with commas</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Rating (0–5)</label>
                <input type="number" name="rating" value="{{ old('rating', $writer['rating'] ?? 5) }}" min="0" max="5" step="0.1"
                    class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Experience (years)</label>
                <input type="number" name="experience_years" value="{{ old('experience_years', $writer['experience_years'] ?? 0) }}" min="0"
                    class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1">Completed Projects</label>
                <input type="number" name="completed_projects" value="{{ old('completed_projects', $writer['completed_projects'] ?? 0) }}" min="0"
                    class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40">
            </div>
        </div>

        {{-- Current photo --}}
        @if(!empty($writer['photo']))
        <div>
            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-2">Current Photo</label>
            <img src="{{ $writer['photo'] }}" alt="{{ $writer['name'] }}"
                class="w-16 h-16 rounded-full object-cover border border-zinc-200 dark:border-zinc-800">
        </div>
        @endif

        <div>
            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1">{{ !empty($writer['photo']) ? 'Replace Photo' : 'Profile Photo' }}</label>
            <input type="file" name="photo" accept="image/*"
                class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40">
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="px-6 py-2 bg-accent hover:opacity-90 text-accent-foreground text-sm font-semibold rounded-xl transition">
                Save Changes
            </button>
            <a href="{{ route('writers.index') }}"
                class="px-6 py-2 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-sm font-semibold rounded-xl transition">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
