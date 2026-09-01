{{-- Hero Banner Editor --}}
<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1.5">Title</label>
            <input type="text" name="data[title]" value="{{ $data['title'] ?? '' }}"
                   class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
        </div>
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1.5">Subtitle</label>
            <input type="text" name="data[subtitle]" value="{{ $data['subtitle'] ?? '' }}"
                   class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
        </div>
    </div>
    <div>
        <label class="block text-xs font-medium text-zinc-500 mb-1.5">Description</label>
        <textarea name="data[description]" rows="3"
                  class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">{{ $data['description'] ?? '' }}</textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1.5">CTA Button Text</label>
            <input type="text" name="data[cta_text]" value="{{ $data['cta_text'] ?? '' }}"
                   class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
        </div>
        <div>
            <label class="block text-xs font-medium text-zinc-500 mb-1.5">CTA Button URL</label>
            <input type="text" name="data[cta_url]" value="{{ $data['cta_url'] ?? '' }}"
                   class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
        </div>
    </div>
</div>
