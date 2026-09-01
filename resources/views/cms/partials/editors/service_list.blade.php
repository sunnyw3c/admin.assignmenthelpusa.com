{{-- Service List Editor --}}
@php $items = $data['items'] ?? [['name' => '', 'slug' => '', 'icon' => '', 'description' => '', 'features' => [], 'price_from' => '', 'turnaround' => '', 'rating' => '']]; @endphp
<div x-data="{ items: {{ json_encode($items) }} }">
    <div class="flex items-center justify-between mb-3">
        <label class="text-xs font-medium text-zinc-500">Services</label>
        <button type="button" @click="items.push({ name: '', slug: '', icon: '', description: '', features: [], price_from: '', turnaround: '', rating: '' })"
                class="text-xs font-medium text-accent-content hover:opacity-80 bg-accent/10 hover:bg-accent/20 px-2.5 py-1 rounded-lg transition">
            + Add Service
        </button>
    </div>
    <div class="space-y-4">
        <template x-for="(item, i) in items" :key="i">
            <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden">
                <div class="flex items-center gap-3 px-4 py-3 bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-800">
                    <input type="text" x-model="item.icon" placeholder="Icon"
                           class="w-14 text-center border border-zinc-200 dark:border-zinc-800 rounded-lg px-2 py-1.5 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                    <input type="text" x-model="item.name" placeholder="Service Name"
                           class="flex-1 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-1.5 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                    <input type="text" x-model="item.slug" placeholder="URL slug"
                           class="w-40 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-1.5 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 font-mono transition">
                    <button type="button" @click="items.splice(i, 1)"
                            class="text-zinc-300 dark:text-zinc-600 hover:text-red-400 transition text-sm">Remove</button>
                </div>
                <div class="p-3 space-y-2">
                    <textarea x-model="item.description" placeholder="Description" rows="2"
                              class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition"></textarea>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs text-zinc-400 mb-1">Price From</label>
                            <input type="text" x-model="item.price_from" placeholder="$10"
                                   class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                        </div>
                        <div>
                            <label class="block text-xs text-zinc-400 mb-1">Turnaround</label>
                            <input type="text" x-model="item.turnaround" placeholder="3-6 hours"
                                   class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                        </div>
                        <div>
                            <label class="block text-xs text-zinc-400 mb-1">Rating</label>
                            <input type="text" x-model="item.rating" placeholder="4.9"
                                   class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-400 mb-1">Features (one per line)</label>
                        <textarea :value="(item.features || []).join('\n')"
                                  @input="item.features = $event.target.value.split('\n').filter(f => f.trim())"
                                  placeholder="Feature 1&#10;Feature 2" rows="3"
                                  class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition"></textarea>
                    </div>
                </div>
            </div>
        </template>
    </div>
    <input type="hidden" name="data[items]" :value="JSON.stringify(items)">
</div>
