{{-- Testimonials Editor --}}
@php $items = $data['items'] ?? [['name' => '', 'role' => '', 'university' => '', 'rating' => 5, 'review' => '', 'project' => '', 'verified' => true]]; @endphp
<div x-data="{ items: {{ json_encode($items) }} }">
    <div class="flex items-center justify-between mb-3">
        <label class="text-xs font-medium text-gray-500">Testimonials</label>
        <button type="button" @click="items.push({ name: '', role: 'Student', university: '', rating: 5, review: '', project: '', verified: true })"
                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg transition">
            + Add Testimonial
        </button>
    </div>
    <div class="space-y-3">
        <template x-for="(item, i) in items" :key="i">
            <div class="border border-gray-200 rounded-xl p-3 space-y-2">
                <div class="flex items-start gap-3">
                    <div class="flex-1 grid grid-cols-3 gap-3">
                        <input type="text" x-model="item.name" placeholder="Name"
                               class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <input type="text" x-model="item.role" placeholder="Role (e.g. Student)"
                               class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <input type="text" x-model="item.university" placeholder="University"
                               class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <button type="button" @click="items.splice(i, 1)"
                            class="text-gray-300 hover:text-red-400 transition text-lg leading-none mt-1 flex-shrink-0">✕</button>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Rating (1–5)</label>
                        <input type="number" x-model.number="item.rating" min="1" max="5"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Project (type)</label>
                        <input type="text" x-model="item.project" placeholder="Essay Writing"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer">
                            <input type="checkbox" x-model="item.verified" class="rounded">
                            Verified Purchase
                        </label>
                    </div>
                </div>
                <textarea x-model="item.review" placeholder="Review text..." rows="3"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"></textarea>
            </div>
        </template>
    </div>
    <input type="hidden" name="data[items]" :value="JSON.stringify(items)">
</div>
