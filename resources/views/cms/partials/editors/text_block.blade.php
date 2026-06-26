{{-- Text Block Editor --}}
<div class="space-y-4">
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Heading</label>
        <input type="text" name="data[heading]" value="{{ $data['heading'] ?? '' }}"
               class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1.5">Body</label>
        <textarea name="data[body]" rows="6"
                  class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">{{ $data['body'] ?? '' }}</textarea>
    </div>
</div>
