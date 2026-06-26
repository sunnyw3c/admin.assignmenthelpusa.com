@extends('layouts.app')
@section('title', 'Add Writer')
@section('heading', 'Add Writer')

@section('content')

@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-2xl border border-gray-200 p-6 max-w-2xl">
    <form action="{{ route('writers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Basic Info --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('name') border-red-400 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('email') border-red-400 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Password <span class="text-red-500">*</span></label>
            <input type="password" name="password" required
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('password') border-red-400 @enderror">
            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Profile --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Title / Degree</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. PhD in Mathematics"
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Bio</label>
            <textarea name="bio" rows="3" placeholder="Short description about the writer..."
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none">{{ old('bio') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Expertise / Subjects</label>
            <input type="text" name="expertise" value="{{ old('expertise') }}" placeholder="Math, Physics, Programming (comma-separated)"
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <p class="text-xs text-gray-400 mt-1">Separate subjects with commas</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Rating (0–5)</label>
                <input type="number" name="rating" value="{{ old('rating', 5) }}" min="0" max="5" step="0.1"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Experience (years)</label>
                <input type="number" name="experience_years" value="{{ old('experience_years', 0) }}" min="0"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Completed Projects</label>
                <input type="number" name="completed_projects" value="{{ old('completed_projects', 0) }}" min="0"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Profile Photo</label>
            <input type="file" name="photo" accept="image/*"
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition">
                Add Writer
            </button>
            <a href="{{ route('writers.index') }}"
                class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
