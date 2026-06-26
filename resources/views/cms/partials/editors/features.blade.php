{{-- Features List Editor --}}
@php $items = $data['items'] ?? [['icon' => '', 'title' => '', 'description' => '']]; @endphp
<div x-data="{ items: {{ json_encode($items) }} }">
    <div class="flex items-center justify-between mb-3">
        <label class="text-xs font-medium text-gray-500">Features</label>
        <button type="button" @click="items.push({ icon: '', title: '', description: '' })"
                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg transition">
            + Add Feature
        </button>
    </div>
    <div class="space-y-3">
        <template x-for="(item, i) in items" :key="i">
            <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                <div class="flex items-start gap-3">
                    <div class="flex-1 grid grid-cols-4 gap-3">
                        <input type="text" x-model="item.icon" placeholder="Icon (emoji)"
                               class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <input type="text" x-model="item.title" placeholder="Title" class="col-span-3 border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <button type="button" @click="items.splice(i, 1)"
                            class="text-gray-300 hover:text-red-400 transition text-lg leading-none mt-1 flex-shrink-0">✕</button>
                </div>
                <textarea x-model="item.description" placeholder="Description" rows="2"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"></textarea>
            </div>
        </template>
    </div>
    <input type="hidden" name="data[items]" :value="JSON.stringify(items)">
</div>
