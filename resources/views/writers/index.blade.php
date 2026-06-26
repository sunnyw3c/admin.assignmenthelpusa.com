@extends('layouts.app')
@section('title', 'Writers')
@section('heading', 'Writers')

@section('content')

@if(session('success'))
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-4 text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm">{{ session('error') }}</div>
@endif

{{-- Summary bar --}}
<div class="bg-white rounded-2xl border border-gray-200 px-5 py-3.5 mb-4 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <p class="text-sm text-gray-500"><span class="font-semibold text-gray-900">{{ count($writers) }}</span> writers</p>
        <div class="h-4 w-px bg-gray-200"></div>
        <p class="text-sm text-gray-500">
            <span class="font-semibold text-gray-900">{{ collect($writers)->sum('active_assignments') }}</span> active assignments
        </p>
        <div class="h-4 w-px bg-gray-200"></div>
        <p class="text-sm text-gray-500">
            <span class="font-semibold text-gray-900">{{ collect($writers)->sum('completed') }}</span> completed total
        </p>
    </div>
    <a href="{{ route('writers.create') }}"
       class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition">
        + Add Writer
    </a>
</div>

{{-- Writers grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse($writers as $writer)
    <div class="bg-white rounded-2xl border border-gray-200 p-5 hover:shadow-sm transition-shadow">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-4">
            @if(!empty($writer['photo']))
                <img src="{{ $writer['photo'] }}" alt="{{ $writer['name'] }}"
                    class="w-11 h-11 rounded-2xl object-cover flex-shrink-0">
            @else
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-lg font-bold flex-shrink-0">
                    {{ strtoupper(substr($writer['name'] ?? 'W', 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $writer['name'] ?? '—' }}</h3>
                <p class="text-xs text-gray-400 truncate">{{ $writer['title'] ?? $writer['email'] ?? '' }}</p>
            </div>
            <div class="flex items-center gap-1 flex-shrink-0">
                @if(!empty($writer['rating']))
                    <span class="text-yellow-400 text-xs">★</span>
                    <span class="text-xs text-gray-600 font-medium">{{ number_format($writer['rating'], 1) }}</span>
                @endif
            </div>
        </div>

        {{-- Bio --}}
        @if(!empty($writer['bio']))
        <p class="text-xs text-gray-500 mb-3 line-clamp-2 leading-relaxed">{{ $writer['bio'] }}</p>
        @endif

        {{-- Expertise tags --}}
        @if(!empty($writer['expertise']))
        <div class="flex flex-wrap gap-1 mb-4">
            @foreach(array_slice((array)$writer['expertise'], 0, 3) as $tag)
            <span class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full font-medium">{{ $tag }}</span>
            @endforeach
        </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-2.5 mb-4">
            <div class="bg-blue-50 rounded-xl p-3 text-center">
                <p class="text-xl font-bold text-blue-700">{{ $writer['active_assignments'] ?? 0 }}</p>
                <p class="text-xs text-blue-400 font-medium mt-0.5">Active</p>
            </div>
            <div class="bg-emerald-50 rounded-xl p-3 text-center">
                <p class="text-xl font-bold text-emerald-700">{{ $writer['completed'] ?? $writer['completed_projects'] ?? 0 }}</p>
                <p class="text-xs text-emerald-400 font-medium mt-0.5">Done</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-2">
            <a href="{{ route('writers.edit', $writer['id'] ?? 0) }}"
               class="flex-1 text-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 py-2 rounded-xl transition">
                Edit Profile
            </a>
            <a href="{{ route('users.show', $writer['id'] ?? 0) }}"
               class="flex-1 text-center text-xs font-semibold text-gray-500 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 py-2 rounded-xl transition">
                View →
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-20 text-center bg-white rounded-2xl border border-gray-200">
        <div class="text-5xl mb-3">✍️</div>
        <p class="text-sm text-gray-400 mb-4">No writers found</p>
        <a href="{{ route('writers.create') }}"
           class="inline-block px-5 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-xl hover:bg-indigo-700 transition">
            Add First Writer
        </a>
    </div>
    @endforelse
</div>

@endsection
