{{-- Text Block Editor --}}
<div class="space-y-4">
    <div>
        <label class="block text-xs font-medium text-zinc-500 mb-1.5">Heading</label>
        <input type="text" name="data[heading]" value="{{ $data['heading'] ?? '' }}"
               class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">
    </div>
    <div>
        <label class="block text-xs font-medium text-zinc-500 mb-1.5">Body</label>
        <textarea name="data[body]" rows="6"
                  class="w-full border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-sm bg-zinc-50 dark:bg-zinc-800/50 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent/40 transition">{{ $data['body'] ?? '' }}</textarea>
    </div>
</div>
