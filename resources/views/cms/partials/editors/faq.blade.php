{{-- FAQ Editor --}}
@php $categories = $data['categories'] ?? [['name' => 'General', 'icon' => '❓', 'questions' => [['question' => '', 'answer' => '']]]]; @endphp
<div x-data="{ categories: {{ json_encode($categories) }} }">
    <div class="flex items-center justify-between mb-3">
        <label class="text-xs font-medium text-gray-500">FAQ Categories</label>
        <button type="button" @click="categories.push({ name: 'New Category', icon: '📌', questions: [{ question: '', answer: '' }] })"
                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg transition">
            + Add Category
        </button>
    </div>
    <div class="space-y-4">
        <template x-for="(cat, ci) in categories" :key="ci">
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <input type="text" x-model="cat.icon" placeholder="Icon"
                           class="w-14 text-center border border-gray-200 rounded-lg px-2 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <input type="text" x-model="cat.name" placeholder="Category Name"
                           class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <button type="button" @click="categories.splice(ci, 1)"
                            class="text-gray-300 hover:text-red-400 transition text-sm">Remove</button>
                </div>
                <div class="p-3 space-y-3">
                    <template x-for="(q, qi) in cat.questions" :key="qi">
                        <div class="bg-white border border-gray-100 rounded-xl p-3 space-y-2">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="q.question" placeholder="Question"
                                       class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                <button type="button" @click="cat.questions.splice(qi, 1)"
                                        class="text-gray-300 hover:text-red-400 transition text-lg leading-none">✕</button>
                            </div>
                            <textarea x-model="q.answer" placeholder="Answer" rows="3"
                                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"></textarea>
                        </div>
                    </template>
                    <button type="button" @click="cat.questions.push({ question: '', answer: '' })"
                            class="w-full text-xs font-medium text-gray-400 hover:text-indigo-600 border border-dashed border-gray-200 hover:border-indigo-300 rounded-xl py-2 transition">
                        + Add Question
                    </button>
                </div>
            </div>
        </template>
    </div>
    <input type="hidden" name="data[categories]" :value="JSON.stringify(categories)">
</div>
