@extends('layouts.app')
@section('title', 'Order ' . ($order['order_number'] ?? '#' . ($order['id'] ?? '')))
@section('heading')
    <div class="flex items-center gap-3">
        <a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <span>{{ $order['order_number'] ?? '#' . ($order['id'] ?? '') }}</span>
        <x-status-badge :status="$order['status'] ?? 'pending'" type="order"/>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- ═══ LEFT COLUMN ═══ --}}
    <div class="xl:col-span-2 space-y-4">

        {{-- Order details --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Order Details</h2>
            <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
                @php
                $fields = [
                    'Title'          => $order['title'] ?? '—',
                    'Subject'        => $order['subject'] ?? '—',
                    'Academic Level' => $order['academic_level'] ?? '—',
                    'Assignment Type'=> $order['assignment_type'] ?? '—',
                    'Pages'          => $order['pages'] ?? '—',
                    'Word Count'     => $order['word_count'] ?? '—',
                    'Deadline'       => ($order['deadline'] ?? null) ? \Carbon\Carbon::parse($order['deadline'])->format('M j, Y H:i') : '—',
                    'Writer Deadline'=> ($order['tutor_deadline'] ?? null) ? \Carbon\Carbon::parse($order['tutor_deadline'])->format('M j, Y H:i') : '—',
                ];
                @endphp
                @foreach($fields as $label => $val)
                <div>
                    <dt class="text-xs text-gray-400 mb-0.5">{{ $label }}</dt>
                    <dd class="text-sm text-gray-800 font-medium">{{ $val }}</dd>
                </div>
                @endforeach
            </dl>

            @if($order['description'] ?? null)
            <div class="mt-5 pt-4 border-t border-gray-100">
                <dt class="text-xs text-gray-400 mb-1.5">Description</dt>
                <dd class="text-sm text-gray-700 leading-relaxed">{{ $order['description'] }}</dd>
            </div>
            @endif
        </div>

        {{-- Financials --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Financials</h2>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-400 mb-1">Budget</p>
                    <p class="text-xl font-bold text-gray-900">${{ number_format($order['budget'] ?? 0, 2) }}</p>
                </div>
                <div class="text-center p-4 bg-emerald-50 rounded-xl">
                    <p class="text-xs text-emerald-500 mb-1">Paid</p>
                    <p class="text-xl font-bold text-emerald-700">${{ number_format($order['amount_paid'] ?? 0, 2) }}</p>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-xl">
                    <p class="text-xs text-red-400 mb-1">Due</p>
                    <p class="text-xl font-bold text-red-600">${{ number_format($order['amount_due'] ?? 0, 2) }}</p>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <x-status-badge :status="$order['payment_status'] ?? 'unpaid'" type="payment"/>
            </div>
        </div>

        {{-- Files --}}
        @if(!empty($order['files']))
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">Attached Files</h2>
            <ul class="space-y-2">
                @foreach($order['files'] as $file)
                <li class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100 text-sm">
                    <div class="w-8 h-8 bg-white rounded-lg border border-gray-200 flex items-center justify-center text-base">📎</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-700 truncate">{{ $file['original_name'] }}</p>
                        <p class="text-xs text-gray-400">{{ $file['file_size_formatted'] ?? '' }}</p>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Messages link --}}
        @if(!empty($order['messages']))
        <a href="{{ route('messages.show', $order['id'] ?? 0) }}"
           class="flex items-center justify-between bg-white rounded-2xl border border-gray-200 p-5 hover:border-indigo-300 hover:shadow-sm transition group">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center text-base">💬</div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Message Thread</p>
                    <p class="text-xs text-gray-400">{{ count($order['messages']) }} message(s)</p>
                </div>
            </div>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-indigo-400 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endif
    </div>

    {{-- ═══ RIGHT COLUMN ═══ --}}
    <div class="space-y-4">

        {{-- Student --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Student</h2>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold">
                    {{ strtoupper(substr($order['user']['name'] ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $order['user']['name'] ?? '—' }}</p>
                    <p class="text-xs text-gray-400">{{ $order['user']['email'] ?? '' }}</p>
                </div>
            </div>
            @if(!empty($order['user']['id']))
            <a href="{{ route('users.show', $order['user']['id']) }}"
               class="mt-3 flex items-center gap-1.5 text-xs text-indigo-600 hover:text-indigo-800 font-medium transition">
                View profile
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endif
        </div>

        {{-- Update Status --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Update Status</h2>
            <form method="POST" action="{{ route('orders.status', $order['id'] ?? 0) }}">
                @csrf
                <select name="status"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-3 transition">
                    @foreach(['pending','in_progress','submitted','revision','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ ($order['status'] ?? '') === $s ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $s)) }}
                    </option>
                    @endforeach
                </select>
                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2.5 rounded-xl transition">
                    Update Status
                </button>
            </form>
        </div>

        {{-- Assign Writer --}}
        @if(in_array(session('admin_user.role'), ['admin', 'manager']))
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Assign Writer</h2>
            @if(isset($order['writer']))
            <div class="flex items-center gap-2 mb-3 p-2.5 bg-blue-50 rounded-xl">
                <div class="w-6 h-6 rounded-full bg-blue-400 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($order['writer']['name'] ?? 'W', 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs font-medium text-blue-800">{{ $order['writer']['name'] }}</p>
                    <p class="text-[10px] text-blue-500">Currently assigned</p>
                </div>
            </div>
            @endif
            <form method="POST" action="{{ route('orders.assign', $order['id'] ?? 0) }}">
                @csrf
                <select name="tutor_id"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-2.5 transition">
                    <option value="">Select writer...</option>
                    @foreach($writers as $writer)
                    <option value="{{ $writer['id'] }}" {{ ($order['tutor_id'] ?? null) == $writer['id'] ? 'selected' : '' }}>
                        {{ $writer['name'] }} · {{ $writer['active_assignments'] ?? 0 }} active
                    </option>
                    @endforeach
                </select>
                <div class="mb-3">
                    <label class="block text-xs text-gray-400 mb-1.5">Writer deadline</label>
                    <input type="datetime-local" name="tutor_deadline"
                           value="{{ ($order['tutor_deadline'] ?? null) ? \Carbon\Carbon::parse($order['tutor_deadline'])->format('Y-m-d\TH:i') : '' }}"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2.5 rounded-xl transition">
                    Assign Writer
                </button>
            </form>
        </div>
        @endif

        {{-- Update Payment --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Payment</h2>
            <form method="POST" action="{{ route('orders.payment', $order['id'] ?? 0) }}">
                @csrf
                <select name="payment_status"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-2.5 transition">
                    @foreach(['unpaid','partial','paid'] as $p)
                    <option value="{{ $p }}" {{ ($order['payment_status'] ?? '') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
                <div class="mb-3">
                    <label class="block text-xs text-gray-400 mb-1.5">Amount paid ($)</label>
                    <input type="number" name="amount_paid" step="0.01" min="0"
                           value="{{ $order['amount_paid'] ?? 0 }}"
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2.5 rounded-xl transition">
                    Update Payment
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
