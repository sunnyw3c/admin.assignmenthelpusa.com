{{-- Hero Banner Editor --}}
<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5">Title</label>
            <input type="text" name="data[title]" value="{{ $data['title'] ?? '' }}"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5">Subtitle</label>
            <input type="text" name="data[subtitle]" value="{{ $data['subtitle'] ?? '' }}"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
        </div>
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Description</label>
        <textarea name="data[description]" rows="3"
                  class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">{{ $data['description'] ?? '' }}</textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5">CTA Button Text</label>
            <input type="text" name="data[cta_text]" value="{{ $data['cta_text'] ?? '' }}"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5">CTA Button URL</label>
            <input type="text" name="data[cta_url]" value="{{ $data['cta_url'] ?? '' }}"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
        </div>
    </div>
</div>
