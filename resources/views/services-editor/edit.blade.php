@extends('layouts.app')
@section('title', ($service['name'] ?? 'Service') . ' — Edit')
@section('heading', $service['name'] ?? 'Edit Service')

@section('content')

@php
    $s = $service;
    $d = $service['details'] ?? [];
@endphp

<div x-data="{ tab: 'service' }">

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('services-editor.index') }}" class="inline-flex items-center gap-1.5 text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition">
        ← All Services
    </a>
    <a href="{{ config('app.main_api_url') }}/assignment/{{ $s['slug'] ?? '' }}" target="_blank"
       class="text-xs text-zinc-400 hover:text-accent-content transition">
        /assignment/{{ $s['slug'] ?? '' }} ↗
    </a>
</div>

@if(session('success'))
<div class="mb-4 text-sm text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 rounded-xl px-4 py-3">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-4 text-sm text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-xl px-4 py-3">
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Tabs --}}
<div class="flex gap-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl p-1 mb-6 w-fit">
    <button type="button" @click="tab = 'service'"
            :class="tab === 'service' ? 'bg-white dark:bg-zinc-900 shadow-sm text-zinc-900 dark:text-zinc-100' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
            class="px-4 py-2 rounded-lg text-sm font-medium transition">
        Service
    </button>
    <button type="button" @click="tab = 'details'"
            :class="tab === 'details' ? 'bg-white dark:bg-zinc-900 shadow-sm text-zinc-900 dark:text-zinc-100' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
            class="px-4 py-2 rounded-lg text-sm font-medium transition">
        Page Details
    </button>
    <button type="button" @click="tab = 'seo'"
            :class="tab === 'seo' ? 'bg-white dark:bg-zinc-900 shadow-sm text-zinc-900 dark:text-zinc-100' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'"
            class="px-4 py-2 rounded-lg text-sm font-medium transition">
        SEO
    </button>
</div>

<form method="POST" action="{{ route('services-editor.update', $id) }}">
    @csrf

{{-- ===== SERVICE TAB ===== --}}
<div x-show="tab === 'service'" x-transition.opacity>
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Service Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" required value="{{ $s['name'] ?? '' }}"
                       class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Icon (emoji)</label>
                <input type="text" name="icon" value="{{ $s['icon'] ?? '' }}" placeholder="📝"
                       class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
            </div>
        </div>

        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1.5">Short Description</label>
            <textarea name="short_description" rows="2"
                      class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">{{ $s['short_description'] ?? '' }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1.5">Long Description</label>
            <textarea name="long_description" rows="4"
                      class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">{{ $s['long_description'] ?? '' }}</textarea>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Base Price / Page ($)</label>
                <input type="number" name="base_price_per_page" value="{{ $s['base_price_per_page'] ?? '' }}" step="0.01"
                       class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Min Turnaround (hrs)</label>
                <input type="number" name="turnaround_min_hours" value="{{ $s['turnaround_min_hours'] ?? '' }}"
                       class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Max Turnaround (hrs)</label>
                <input type="number" name="turnaround_max_hours" value="{{ $s['turnaround_max_hours'] ?? '' }}"
                       class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Rating</label>
                <input type="number" name="rating" value="{{ $s['rating'] ?? '' }}" step="0.1" min="0" max="5"
                       class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Orders Completed</label>
                <input type="number" name="orders_completed" value="{{ $s['orders_completed'] ?? '' }}"
                       class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Display Order</label>
                <input type="number" name="display_order" value="{{ $s['display_order'] ?? 0 }}"
                       class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
            </div>
        </div>

        {{-- Features --}}
        @php $features = $s['features'] ?? []; @endphp
        <div x-data="{ items: {{ json_encode(is_array($features) ? $features : []) }} }">
            <div class="flex items-center justify-between mb-2">
                <label class="text-xs font-medium text-zinc-500">Features</label>
                <button type="button" @click="items.push('')"
                        class="text-xs font-medium text-accent-content hover:opacity-80 bg-accent/10 hover:bg-accent/20 px-2.5 py-1 rounded-lg transition">
                    + Add
                </button>
            </div>
            <div class="space-y-2">
                <template x-for="(item, i) in items" :key="i">
                    <div class="flex items-center gap-2">
                        <input type="text" x-model="items[i]" placeholder="Feature..."
                               class="flex-1 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                        <button type="button" @click="items.splice(i, 1)"
                                class="text-zinc-300 dark:text-zinc-600 hover:text-red-400 transition text-lg leading-none">✕</button>
                    </div>
                </template>
            </div>
            <input type="hidden" name="features" :value="JSON.stringify(items)">
        </div>

        {{-- Active toggle --}}
        <div class="flex items-center gap-3 pt-2 border-t border-zinc-100 dark:border-zinc-800">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                       {{ ($s['is_active'] ?? true) ? 'checked' : '' }}>
                <div class="w-9 h-5 bg-zinc-200 dark:bg-zinc-700 peer-focus:ring-2 peer-focus:ring-accent/40 rounded-full peer peer-checked:bg-accent after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white dark:after:bg-zinc-900 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
            </label>
            <span class="text-sm text-zinc-600 dark:text-zinc-400">Active (visible on site)</span>
        </div>
    </div>

    <div class="flex justify-end mt-4">
        <button type="submit"
                class="bg-accent hover:opacity-90 text-accent-foreground text-sm font-medium px-6 py-2.5 rounded-xl transition">
            Save Changes
        </button>
    </div>
</div>

{{-- ===== DETAILS TAB ===== --}}
<div x-show="tab === 'details'" x-transition.opacity x-cloak>
    <div class="space-y-6">

        {{-- Hero --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 space-y-4">
            <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Hero Section</h3>
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Hero Title</label>
                <input type="text" name="hero_title" value="{{ $d['hero_title'] ?? '' }}"
                       class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Hero Subtitle</label>
                <input type="text" name="hero_subtitle" value="{{ $d['hero_subtitle'] ?? '' }}"
                       class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-500 mb-1.5">Hero Description</label>
                <textarea name="hero_description" rows="3"
                          class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">{{ $d['hero_description'] ?? '' }}</textarea>
            </div>
        </div>

        {{-- What We Offer --}}
        @php $whatWeOffer = $d['what_we_offer'] ?? []; @endphp
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6" x-data="{ items: {{ json_encode(is_array($whatWeOffer) ? $whatWeOffer : []) }} }">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">What We Offer</h3>
                <button type="button" @click="items.push({ icon: '', title: '', description: '' })"
                        class="text-xs font-medium text-accent-content hover:opacity-80 bg-accent/10 hover:bg-accent/20 px-2.5 py-1 rounded-lg transition">+ Add</button>
            </div>
            <div class="space-y-3">
                <template x-for="(item, i) in items" :key="i">
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-xl p-3 space-y-2">
                        <div class="flex items-center gap-3">
                            <input type="text" x-model="item.icon" placeholder="Icon" class="w-14 text-center border border-zinc-200 dark:border-zinc-800 rounded-lg px-2 py-2 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                            <input type="text" x-model="item.title" placeholder="Title" class="flex-1 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                            <button type="button" @click="items.splice(i, 1)" class="text-zinc-300 dark:text-zinc-600 hover:text-red-400 transition text-lg leading-none">✕</button>
                        </div>
                        <textarea x-model="item.description" placeholder="Description" rows="2"
                                  class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition"></textarea>
                    </div>
                </template>
            </div>
            <input type="hidden" name="details_what_we_offer" :value="JSON.stringify(items)">
        </div>

        {{-- FAQs --}}
        @php $faqs = $d['faqs'] ?? []; @endphp
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6" x-data="{ items: {{ json_encode(is_array($faqs) ? $faqs : []) }} }">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">FAQs</h3>
                <button type="button" @click="items.push({ question: '', answer: '' })"
                        class="text-xs font-medium text-accent-content hover:opacity-80 bg-accent/10 hover:bg-accent/20 px-2.5 py-1 rounded-lg transition">+ Add</button>
            </div>
            <div class="space-y-3">
                <template x-for="(item, i) in items" :key="i">
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-xl p-3 space-y-2">
                        <div class="flex items-center gap-3">
                            <input type="text" x-model="item.question" placeholder="Question" class="flex-1 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                            <button type="button" @click="items.splice(i, 1)" class="text-zinc-300 dark:text-zinc-600 hover:text-red-400 transition text-lg leading-none">✕</button>
                        </div>
                        <textarea x-model="item.answer" placeholder="Answer" rows="3"
                                  class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition"></textarea>
                    </div>
                </template>
            </div>
            <input type="hidden" name="details_faqs" :value="JSON.stringify(items)">
        </div>

        {{-- Testimonials --}}
        @php $testimonials = $d['testimonials'] ?? []; @endphp
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6" x-data="{ items: {{ json_encode(is_array($testimonials) ? $testimonials : []) }} }">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Testimonials</h3>
                <button type="button" @click="items.push({ name: '', role: 'Student', university: '', rating: 5, review: '', project: '' })"
                        class="text-xs font-medium text-accent-content hover:opacity-80 bg-accent/10 hover:bg-accent/20 px-2.5 py-1 rounded-lg transition">+ Add</button>
            </div>
            <div class="space-y-3">
                <template x-for="(item, i) in items" :key="i">
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-xl p-3 space-y-2">
                        <div class="flex items-start gap-3">
                            <div class="flex-1 grid grid-cols-3 gap-2">
                                <input type="text" x-model="item.name" placeholder="Name" class="border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                                <input type="text" x-model="item.university" placeholder="University" class="border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                                <input type="number" x-model.number="item.rating" min="1" max="5" placeholder="Rating" class="border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                            </div>
                            <button type="button" @click="items.splice(i, 1)" class="text-zinc-300 dark:text-zinc-600 hover:text-red-400 transition text-lg leading-none mt-1">✕</button>
                        </div>
                        <textarea x-model="item.review" placeholder="Review..." rows="3"
                                  class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition"></textarea>
                    </div>
                </template>
            </div>
            <input type="hidden" name="details_testimonials" :value="JSON.stringify(items)">
        </div>

        {{-- Process Steps --}}
        @php $processSteps = $d['process_steps'] ?? []; @endphp
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6" x-data="{ items: {{ json_encode(is_array($processSteps) ? $processSteps : []) }} }">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Process Steps</h3>
                <button type="button" @click="items.push({ step: String(items.length + 1).padStart(2,'0'), title: '', description: '' })"
                        class="text-xs font-medium text-accent-content hover:opacity-80 bg-accent/10 hover:bg-accent/20 px-2.5 py-1 rounded-lg transition">+ Add</button>
            </div>
            <div class="space-y-3">
                <template x-for="(item, i) in items" :key="i">
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-xl p-3 space-y-2">
                        <div class="flex items-center gap-3">
                            <input type="text" x-model="item.step" placeholder="01" class="w-14 text-center border border-zinc-200 dark:border-zinc-800 rounded-lg px-2 py-2 text-sm bg-white dark:bg-zinc-900 font-mono focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                            <input type="text" x-model="item.title" placeholder="Step Title" class="flex-1 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                            <button type="button" @click="items.splice(i, 1)" class="text-zinc-300 dark:text-zinc-600 hover:text-red-400 transition text-lg leading-none">✕</button>
                        </div>
                        <textarea x-model="item.description" placeholder="Description" rows="2"
                                  class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition"></textarea>
                    </div>
                </template>
            </div>
            <input type="hidden" name="details_process_steps" :value="JSON.stringify(items)">
        </div>

        {{-- Deliverables & Guarantees (simple arrays) --}}
        @php $deliverables = $d['deliverables'] ?? []; $guarantees = $d['guarantees'] ?? []; @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6" x-data="{ items: {{ json_encode(is_array($deliverables) ? $deliverables : []) }} }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Deliverables</h3>
                    <button type="button" @click="items.push('')" class="text-xs font-medium text-accent-content hover:opacity-80 bg-accent/10 hover:bg-accent/20 px-2.5 py-1 rounded-lg transition">+ Add</button>
                </div>
                <div class="space-y-2">
                    <template x-for="(item, i) in items" :key="i">
                        <div class="flex items-center gap-2">
                            <input type="text" x-model="items[i]" placeholder="Deliverable..." class="flex-1 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                            <button type="button" @click="items.splice(i, 1)" class="text-zinc-300 dark:text-zinc-600 hover:text-red-400 transition text-lg leading-none">✕</button>
                        </div>
                    </template>
                </div>
                <input type="hidden" name="details_deliverables" :value="JSON.stringify(items)">
            </div>
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6" x-data="{ items: {{ json_encode(is_array($guarantees) ? $guarantees : []) }} }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Guarantees</h3>
                    <button type="button" @click="items.push('')" class="text-xs font-medium text-accent-content hover:opacity-80 bg-accent/10 hover:bg-accent/20 px-2.5 py-1 rounded-lg transition">+ Add</button>
                </div>
                <div class="space-y-2">
                    <template x-for="(item, i) in items" :key="i">
                        <div class="flex items-center gap-2">
                            <input type="text" x-model="items[i]" placeholder="Guarantee..." class="flex-1 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                            <button type="button" @click="items.splice(i, 1)" class="text-zinc-300 dark:text-zinc-600 hover:text-red-400 transition text-lg leading-none">✕</button>
                        </div>
                    </template>
                </div>
                <input type="hidden" name="details_guarantees" :value="JSON.stringify(items)">
            </div>
        </div>

        {{-- Sample Topics (simple string array) --}}
        @php $sampleTopics = $d['sample_topics'] ?? []; @endphp
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6" x-data="{ items: {{ json_encode(is_array($sampleTopics) ? $sampleTopics : []) }} }">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Sample Topics</h3>
                <button type="button" @click="items.push('')" class="text-xs font-medium text-accent-content hover:opacity-80 bg-accent/10 hover:bg-accent/20 px-2.5 py-1 rounded-lg transition">+ Add</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <template x-for="(item, i) in items" :key="i">
                    <div class="flex items-center gap-2">
                        <input type="text" x-model="items[i]" placeholder="Topic..." class="flex-1 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                        <button type="button" @click="items.splice(i, 1)" class="text-zinc-300 dark:text-zinc-600 hover:text-red-400 transition text-lg leading-none">✕</button>
                    </div>
                </template>
            </div>
            <input type="hidden" name="details_sample_topics" :value="JSON.stringify(items)">
        </div>

    </div>

    <div class="flex justify-end mt-4">
        <button type="submit"
                class="bg-accent hover:opacity-90 text-accent-foreground text-sm font-medium px-6 py-2.5 rounded-xl transition">
            Save Changes
        </button>
    </div>
</div>

{{-- ===== SEO TAB ===== --}}
<div x-show="tab === 'seo'" x-transition.opacity x-cloak>
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6 space-y-5">
        <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">SEO Meta</h3>
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1.5">Meta Title</label>
            <input type="text" name="meta_title" value="{{ $s['meta_title'] ?? '' }}" maxlength="60"
                   class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
            <p class="text-xs text-zinc-400 mt-1">Recommended: under 60 characters</p>
        </div>
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1.5">Meta Description</label>
            <textarea name="meta_description" rows="3" maxlength="160"
                      class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">{{ $s['meta_description'] ?? '' }}</textarea>
            <p class="text-xs text-zinc-400 mt-1">Recommended: under 160 characters</p>
        </div>
    </div>

    <div class="flex justify-end mt-4">
        <button type="submit"
                class="bg-accent hover:opacity-90 text-accent-foreground text-sm font-medium px-6 py-2.5 rounded-xl transition">
            Save Changes
        </button>
    </div>
</div>

</form>
</div>

@endsection
