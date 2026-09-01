@extends('layouts.app')
@section('title', 'Assignment Services')
@section('heading', 'Assignment Services')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-zinc-500">Manage assignment service pages — content, pricing, and details.</p>
    <a href="{{ route('services-editor.create') }}"
       class="flex items-center gap-1.5 text-sm font-semibold text-accent-foreground bg-accent hover:opacity-90 px-4 py-2 rounded-xl transition">
        + New Service
    </a>
</div>

@if(session('success'))
<div class="mb-4 text-sm text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 rounded-xl px-4 py-3">
    {{ session('success') }}
</div>
@endif

<div class="card overflow-hidden">
    <div class="table-scroll">
        <table class="min-w-[52rem]">
            <thead>
                <tr class="border-b border-zinc-100 dark:border-zinc-800">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wide">Service</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wide">URL Slug</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wide">Price / Page</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wide">Rating</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-400 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($services as $service)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/60 group transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            <span class="text-xl">{{ $service['icon'] ?? '📄' }}</span>
                            <div>
                                <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $service['name'] }}</div>
                                @if(!empty($service['short_description']))
                                <div class="text-xs text-zinc-400 truncate max-w-xs">{{ Str::limit($service['short_description'], 60) }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-xs text-zinc-400 font-mono">/{{ $service['slug'] }}</td>
                    <td class="px-5 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                        @if(!empty($service['base_price_per_page']))
                            ${{ $service['base_price_per_page'] }}
                        @else
                            <span class="text-zinc-300 dark:text-zinc-600">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                        @if(!empty($service['rating']))
                            <span class="text-amber-500">★</span> {{ $service['rating'] }}
                        @else
                            <span class="text-zinc-300 dark:text-zinc-600">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($service['is_active'] ?? true)
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-0.5 rounded-full ring-1 ring-emerald-200 dark:ring-emerald-500/30">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Active
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-500 bg-zinc-100 dark:bg-zinc-800 px-2.5 py-0.5 rounded-full ring-1 ring-zinc-200 dark:ring-zinc-800">
                            <span class="w-1.5 h-1.5 bg-zinc-400 rounded-full"></span>Inactive
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('services-editor.edit', $service['id']) }}"
                           class="text-xs font-medium text-accent-content row-action hover:underline">Edit →</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-16 text-center text-sm text-zinc-400">No services found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
