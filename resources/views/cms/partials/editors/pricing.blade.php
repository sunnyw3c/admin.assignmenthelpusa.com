{{-- Pricing Plans Editor --}}
@php $plans = $data['plans'] ?? [['name' => '', 'price' => '', 'period' => '/page', 'description' => '', 'features' => [], 'color' => 'indigo', 'recommended' => false]]; @endphp
<div x-data="{ plans: {{ json_encode($plans) }} }">
    <div class="flex items-center justify-between mb-3">
        <label class="text-xs font-medium text-gray-500">Pricing Plans</label>
        <button type="button" @click="plans.push({ name: '', price: '', period: '/page', description: '', features: [], color: 'indigo', recommended: false })"
                class="text-xs font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg transition">
            + Add Plan
        </button>
    </div>
    <div class="space-y-4">
        <template x-for="(plan, pi) in plans" :key="pi">
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center gap-3 flex-1">
                        <input type="text" x-model="plan.name" placeholder="Plan Name"
                               class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition w-36">
                        <div class="flex items-center gap-1">
                            <span class="text-xs text-gray-400">$</span>
                            <input type="text" x-model="plan.price" placeholder="15"
                                   class="w-20 border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                        <input type="text" x-model="plan.period" placeholder="/page"
                               class="w-20 border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <label class="flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer">
                            <input type="checkbox" x-model="plan.recommended" class="rounded">
                            Recommended
                        </label>
                    </div>
                    <button type="button" @click="plans.splice(pi, 1)"
                            class="text-gray-300 hover:text-red-400 transition text-sm ml-3">Remove</button>
                </div>
                <div class="p-3 space-y-3">
                    <input type="text" x-model="plan.description" placeholder="Plan description"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <div>
                        <div class="text-xs text-gray-400 mb-2">Features (one per line)</div>
                        <textarea :value="(plan.features || []).join('\n')"
                                  @input="plan.features = $event.target.value.split('\n').filter(f => f.trim())"
                                  placeholder="24/7 Support&#10;Plagiarism-free&#10;Free revisions" rows="4"
                                  class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"></textarea>
                    </div>
                </div>
            </div>
        </template>
    </div>
    <input type="hidden" name="data[plans]" :value="JSON.stringify(plans)">
</div>
