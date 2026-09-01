{{-- FAQ Editor --}}
@php $categories = $data['categories'] ?? [['name' => 'General', 'icon' => '❓', 'questions' => [['question' => '', 'answer' => '']]]]; @endphp
<div x-data="{ categories: {{ json_encode($categories) }} }">
    <div class="flex items-center justify-between mb-3">
        <label class="text-xs font-medium text-zinc-500">FAQ Categories</label>
        <button type="button" @click="categories.push({ name: 'New Category', icon: '📌', questions: [{ question: '', answer: '' }] })"
                class="text-xs font-medium text-accent-content hover:opacity-80 bg-accent/10 hover:bg-accent/20 px-2.5 py-1 rounded-lg transition">
            + Add Category
        </button>
    </div>
    <div class="space-y-4">
        <template x-for="(cat, ci) in categories" :key="ci">
            <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden">
                <div class="flex items-center gap-3 px-4 py-3 bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-800">
                    <input type="text" x-model="cat.icon" placeholder="Icon"
                           class="w-14 text-center border border-zinc-200 dark:border-zinc-800 rounded-lg px-2 py-1.5 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                    <input type="text" x-model="cat.name" placeholder="Category Name"
                           class="flex-1 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-1.5 text-sm bg-white dark:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                    <button type="button" @click="categories.splice(ci, 1)"
                            class="text-zinc-300 dark:text-zinc-600 hover:text-red-400 transition text-sm">Remove</button>
                </div>
                <div class="p-3 space-y-3">
                    <template x-for="(q, qi) in cat.questions" :key="qi">
                        <div class="bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-xl p-3 space-y-2">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="q.question" placeholder="Question"
                                       class="flex-1 border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
                                <button type="button" @click="cat.questions.splice(qi, 1)"
                                        class="text-zinc-300 dark:text-zinc-600 hover:text-red-400 transition text-lg leading-none">✕</button>
                            </div>
                            <textarea x-model="q.answer" placeholder="Answer" rows="3"
                                      class="w-full border border-zinc-200 dark:border-zinc-800 rounded-lg px-3 py-2 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition"></textarea>
                        </div>
                    </template>
                    <button type="button" @click="cat.questions.push({ question: '', answer: '' })"
                            class="w-full text-xs font-medium text-zinc-400 hover:text-accent-content border border-dashed border-zinc-200 dark:border-zinc-800 hover:border-accent/30 rounded-xl py-2 transition">
                        + Add Question
                    </button>
                </div>
            </div>
        </template>
    </div>
    <input type="hidden" name="data[categories]" :value="JSON.stringify(categories)">
</div>
