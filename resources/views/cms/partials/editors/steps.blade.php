{{-- Steps / How It Works Editor --}}
@php $items = $data['items'] ?? [['step' => '01', 'title' => '', 'description' => '', 'icon' => '', 'estimated_time' => '']]; @endphp
<div x-data="{ items: {{ json_encode($items) }} }">
    <div class="flex items-center justify-between mb-3">
        <label class="text-xs font-medium text-gray-500">Steps</label>
        <button type="button" @click="items.push({ step: String(items.length + 1).padStart(2,'0'), title: '', description: '', icon: '', estimated_time: '' })"
                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg transition">
            + Add Step
        </button>
    </div>
    <div class="space-y-3">
        <template x-for="(item, i) in items" :key="i">
            <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                <div class="flex items-center gap-3">
                    <div class="flex-1 grid grid-cols-6 gap-3">
                        <input type="text" x-model="item.step" placeholder="01"
                               class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-center font-mono">
                        <input type="text" x-model="item.icon" placeholder="Icon"
                               class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-center">
                        <input type="text" x-model="item.title" placeholder="Step Title" class="col-span-3 border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <input type="text" x-model="item.estimated_time" placeholder="~5 min"
                               class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-center">
                    </div>
                    <button type="button" @click="items.splice(i, 1)"
                            class="text-gray-300 hover:text-red-400 transition text-lg leading-none flex-shrink-0">✕</button>
                </div>
                <textarea x-model="item.description" placeholder="Step description" rows="2"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"></textarea>
            </div>
        </template>
    </div>
    <input type="hidden" name="data[items]" :value="JSON.stringify(items)">
</div>
