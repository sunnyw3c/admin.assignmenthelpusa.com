{{-- Stats Row Editor --}}
@php $items = $data['items'] ?? [['number' => '', 'label' => '']]; @endphp
<div x-data="{ items: {{ json_encode($items) }} }">
    <div class="flex items-center justify-between mb-3">
        <label class="text-xs font-medium text-gray-500">Stats</label>
        <button type="button" @click="items.push({ number: '', label: '' })"
                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg transition">
            + Add Stat
        </button>
    </div>
    <div class="space-y-2">
        <template x-for="(item, i) in items" :key="i">
            <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-3 py-2.5">
                <div class="flex-1 grid grid-cols-2 gap-3">
                    <input type="text" :name="'_items_number_' + i" x-model="item.number" placeholder="e.g. 10,000+"
                           class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <input type="text" :name="'_items_label_' + i" x-model="item.label" placeholder="e.g. Students Helped"
                           class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <button type="button" @click="items.splice(i, 1)"
                        class="text-gray-300 hover:text-red-400 transition text-lg leading-none flex-shrink-0">✕</button>
            </div>
        </template>
    </div>
    <input type="hidden" name="data[items]" :value="JSON.stringify(items)">
</div>
