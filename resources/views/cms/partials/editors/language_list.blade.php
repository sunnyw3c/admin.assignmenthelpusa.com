{{-- Language List Editor --}}
@php $items = $data['items'] ?? [['name' => '', 'slug' => '', 'icon' => '', 'description' => '', 'difficulty' => 'Intermediate', 'use_cases' => [], 'popularity' => 80]]; @endphp
<div x-data="{ items: {{ json_encode($items) }} }">
    <div class="flex items-center justify-between mb-3">
        <label class="text-xs font-medium text-gray-500">Languages</label>
        <button type="button" @click="items.push({ name: '', slug: '', icon: '', description: '', difficulty: 'Intermediate', use_cases: [], popularity: 80 })"
                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg transition">
            + Add Language
        </button>
    </div>
    <div class="space-y-4">
        <template x-for="(item, i) in items" :key="i">
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <input type="text" x-model="item.icon" placeholder="Icon"
                           class="w-14 text-center border border-gray-200 rounded-lg px-2 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <input type="text" x-model="item.name" placeholder="Language Name"
                           class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <input type="text" x-model="item.slug" placeholder="URL slug"
                           class="w-40 border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono transition">
                    <button type="button" @click="items.splice(i, 1)"
                            class="text-gray-300 hover:text-red-400 transition text-sm">Remove</button>
                </div>
                <div class="p-3 space-y-2">
                    <textarea x-model="item.description" placeholder="Description" rows="2"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"></textarea>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Difficulty</label>
                            <select x-model="item.difficulty"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                <option>Beginner</option>
                                <option>Intermediate</option>
                                <option>Advanced</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Popularity %</label>
                            <input type="number" x-model.number="item.popularity" min="0" max="100"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Use Cases (one per line)</label>
                        <textarea :value="(item.use_cases || []).join('\n')"
                                  @input="item.use_cases = $event.target.value.split('\n').filter(f => f.trim())"
                                  placeholder="Web Development&#10;Data Science" rows="3"
                                  class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"></textarea>
                    </div>
                </div>
            </div>
        </template>
    </div>
    <input type="hidden" name="data[items]" :value="JSON.stringify(items)">
</div>
