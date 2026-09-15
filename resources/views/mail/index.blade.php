@extends('layouts.app')
@section('title', 'Smart Mail & Campaign Scheduler')
@section('heading', 'Smart Mail')

@section('content')
<div x-data="mailComposer(
    @js($recipients),
    @js($savedContacts),
    @js(old('email', '')),
    @js($campaign),
    @js($mailType),
    @js($drafts),
    @js($queueItems),
    @js($queueStats)
)" class="mx-auto max-w-6xl space-y-6">

    <!-- Top Outreach Header & Quality -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="eyebrow">Marketing Automation & Outreach</p>
            <h2 class="mt-1 text-xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Bulk Campaign Scheduler & Contact Manager</h2>
            <p class="mt-1 text-sm text-zinc-500">Automated 2-minute drip delivery after 6:00 PM daily with smart 7-day anti-fatigue cooldown.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="showAddModal = true" class="btn btn-secondary text-xs">
                <x-icon name="plus" class="size-3.5" /> Add Email ID
            </button>
            <button type="button" @click="showImportModal = true" class="btn btn-secondary text-xs">
                <x-icon name="document-arrow-up" class="size-3.5" /> Import 100s / CSV
            </button>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="card p-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-500">Saved Contacts</span>
                <span class="rounded-md bg-blue-50 px-2 py-0.5 text-xs font-bold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400" x-text="stats.total_contacts"></span>
            </div>
            <p class="mt-2 text-2xl font-bold text-zinc-900 dark:text-zinc-50" x-text="stats.total_contacts"></p>
            <p class="mt-0.5 text-[11px] text-zinc-400">Manageable audience</p>
        </div>

        <div class="card p-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-500">Today's Queue (6 PM)</span>
                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-bold text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400" x-text="stats.pending"></span>
            </div>
            <p class="mt-2 text-2xl font-bold text-zinc-900 dark:text-zinc-50" x-text="stats.pending"></p>
            <p class="mt-0.5 text-[11px] text-emerald-500">1 email every 2 mins</p>
        </div>

        <div class="card p-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-500">Deferred Next Week</span>
                <span class="rounded-md bg-amber-50 px-2 py-0.5 text-xs font-bold text-amber-600 dark:bg-amber-900/30 dark:text-amber-400" x-text="stats.deferred"></span>
            </div>
            <p class="mt-2 text-2xl font-bold text-zinc-900 dark:text-zinc-50" x-text="stats.deferred"></p>
            <p class="mt-0.5 text-[11px] text-amber-500">7-Day Cooldown Protection</p>
        </div>

        <div class="card p-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-zinc-500">Delivered Total</span>
                <span class="rounded-md bg-purple-50 px-2 py-0.5 text-xs font-bold text-purple-600 dark:bg-purple-900/30 dark:text-purple-400" x-text="stats.sent"></span>
            </div>
            <p class="mt-2 text-2xl font-bold text-zinc-900 dark:text-zinc-50" x-text="stats.sent"></p>
            <p class="mt-0.5 text-[11px] text-zinc-400">Delivered to Inboxes</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-zinc-200 dark:border-zinc-800">
        <button type="button" @click="activeTab = 'bulk'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition"
                :class="activeTab === 'bulk' ? 'border-accent text-accent' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'">
            <span class="flex items-center gap-2">
                <x-icon name="sparkles" class="size-4" /> Bulk Automated Scheduler
            </span>
        </button>
        <button type="button" @click="activeTab = 'single'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition"
                :class="activeTab === 'single' ? 'border-accent text-accent' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'">
            <span class="flex items-center gap-2">
                <x-icon name="paper-airplane" class="size-4" /> Single Send
            </span>
        </button>
        <button type="button" @click="activeTab = 'contacts'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition"
                :class="activeTab === 'contacts' ? 'border-accent text-accent' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'">
            <span class="flex items-center gap-2">
                <x-icon name="users" class="size-4" /> Saved Contacts (<span x-text="contacts.length"></span>)
            </span>
        </button>
        <button type="button" @click="activeTab = 'queue'" class="border-b-2 px-4 py-2.5 text-sm font-semibold transition"
                :class="activeTab === 'queue' ? 'border-accent text-accent' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'">
            <span class="flex items-center gap-2">
                <x-icon name="clock" class="size-4" /> Delivery Queue (<span x-text="queue.length"></span>)
            </span>
        </button>
    </div>

    <!-- TAB 1: BULK AUTOMATED SCHEDULER -->
    <div x-show="activeTab === 'bulk'" class="grid gap-5 lg:grid-cols-[minmax(0,1.25fr)_minmax(19rem,.75fr)]">
        <div class="space-y-5">
            <div class="card p-5 sm:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Step 1: Choose Audience & Source</p>
                        <p class="mt-0.5 text-xs text-zinc-500">Select how you want to provide your 100s of recipients.</p>
                    </div>
                    <span class="rounded-full bg-accent/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-accent-content">Bulk Mode</span>
                </div>

                <form action="{{ route('mail.bulk-schedule') }}" method="POST" class="mt-5" @submit="submitting = true">
                    @csrf

                    <!-- Source Selector -->
                    <div class="mb-5 grid gap-3 sm:grid-cols-3">
                        <label class="cursor-pointer rounded-xl border p-3.5 transition" :class="bulkSource === 'manual' ? 'border-accent bg-accent/5 ring-2 ring-accent/10' : 'border-zinc-200 dark:border-zinc-700'">
                            <input type="radio" name="recipient_source" value="manual" x-model="bulkSource" class="sr-only">
                            <span class="block text-xs font-semibold text-zinc-800 dark:text-zinc-200">📋 Paste Emails / CSV</span>
                            <span class="mt-1 block text-[11px] text-zinc-500">Paste 100+ raw emails</span>
                        </label>
                        <label class="cursor-pointer rounded-xl border p-3.5 transition" :class="bulkSource === 'contacts' ? 'border-accent bg-accent/5 ring-2 ring-accent/10' : 'border-zinc-200 dark:border-zinc-700'">
                            <input type="radio" name="recipient_source" value="contacts" x-model="bulkSource" class="sr-only">
                            <span class="block text-xs font-semibold text-zinc-800 dark:text-zinc-200">👥 Saved Contacts (<span x-text="contacts.length"></span>)</span>
                            <span class="mt-1 block text-[11px] text-zinc-500">From admin contact list</span>
                        </label>
                        <label class="cursor-pointer rounded-xl border p-3.5 transition" :class="bulkSource === 'all_students' ? 'border-accent bg-accent/5 ring-2 ring-accent/10' : 'border-zinc-200 dark:border-zinc-700'">
                            <input type="radio" name="recipient_source" value="all_students" x-model="bulkSource" class="sr-only">
                            <span class="block text-xs font-semibold text-zinc-800 dark:text-zinc-200">🎓 All Student Accounts</span>
                            <span class="mt-1 block text-[11px] text-zinc-500">Registered platform users</span>
                        </label>
                    </div>

                    <!-- Manual Paste Area -->
                    <div x-show="bulkSource === 'manual'" class="mb-5">
                        <div class="mb-1.5 flex justify-between gap-3">
                            <label for="bulk-emails-input" class="label">Paste Recipient Emails (100s supported)</label>
                            <span class="text-xs font-semibold text-accent" x-text="parsedManualCount + ' emails detected'"></span>
                        </div>
                        <textarea id="bulk-emails-input" name="bulk_emails" x-model="manualEmailText" rows="4" class="field font-mono text-xs"
                                  placeholder="john@example.com&#10;sarah@example.com&#10;alex@example.com, emma@example.com"></textarea>
                        <p class="mt-1 text-[11px] text-zinc-400">Separate emails by new line or commas. Invalid and duplicate emails are automatically cleaned.</p>
                    </div>

                    <!-- Saved Contacts Selection Area -->
                    <div x-show="bulkSource === 'contacts'" class="mb-5 max-h-48 overflow-y-auto rounded-xl border border-zinc-200 p-3 dark:border-zinc-700">
                        <div class="mb-2 flex items-center justify-between border-b pb-2 text-xs">
                            <span class="font-semibold text-zinc-700 dark:text-zinc-300">Select Contacts to Include:</span>
                            <button type="button" @click="toggleSelectAllContacts" class="text-accent hover:underline" x-text="selectedContactIds.length === contacts.length ? 'Deselect All' : 'Select All (' + contacts.length + ')'"></button>
                        </div>
                        <div class="space-y-1.5">
                            <template x-for="c in contacts" :key="c.id">
                                <label class="flex cursor-pointer items-center justify-between rounded-lg p-2 text-xs hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                    <span class="flex items-center gap-2">
                                        <input type="checkbox" name="selected_contacts[]" :value="c.id" x-model="selectedContactIds" class="rounded text-accent focus:ring-accent/30">
                                        <span class="font-medium text-zinc-800 dark:text-zinc-200" x-text="c.email"></span>
                                        <span x-show="c.name" class="text-zinc-400" x-text="'(' + c.name + ')'"></span>
                                    </span>
                                    <span class="text-[10px]" :class="c.is_cooling_down ? 'text-amber-500 font-semibold' : 'text-emerald-500 font-medium'" x-text="c.cooldown_status"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Scheduling Rules Info Box -->
                    <div class="mb-5 rounded-xl border border-blue-200 bg-blue-50/70 p-4 dark:border-blue-900/40 dark:bg-blue-950/20">
                        <div class="flex items-start gap-3">
                            <x-icon name="clock" class="size-5 shrink-0 text-blue-600 dark:text-blue-400" />
                            <div class="space-y-1 text-xs text-blue-950 dark:text-blue-200">
                                <p class="font-bold">Automated Schedule & Anti-Fatigue Policy:</p>
                                <p>• <strong>Drip Throttling:</strong> 1 email sent every <strong>2 minutes</strong> starting after <strong>6:00 PM daily</strong>.</p>
                                <p>• <strong>7-Day Cooldown:</strong> If any email already received an outreach in the past 7 days, it will be <strong>automatically deferred to next week's 6:00 PM batch</strong>.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Message Type -->
                    <fieldset class="mb-5">
                        <legend class="label">Message Type</legend>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <label class="cursor-pointer rounded-xl border p-3.5 transition" :class="mailType === 'promotional' ? 'border-accent bg-accent/5 ring-2 ring-accent/10' : 'border-zinc-200 dark:border-zinc-700'">
                                <span class="flex items-start gap-3">
                                    <input type="radio" name="mail_type" value="promotional" x-model="mailType" @change="setMailType('promotional')" class="mt-1 text-accent focus:ring-accent/30">
                                    <span><span class="block text-sm font-semibold text-zinc-800 dark:text-zinc-200">Promotional</span><span class="mt-0.5 block text-xs leading-5 text-zinc-500">Offers & promo discounts with unsubscribe link.</span></span>
                                </span>
                            </label>
                            <label class="cursor-pointer rounded-xl border p-3.5 transition" :class="mailType === 'direct' ? 'border-blue-500 bg-blue-500/5 ring-2 ring-blue-500/10' : 'border-zinc-200 dark:border-zinc-700'">
                                <span class="flex items-start gap-3">
                                    <input type="radio" name="mail_type" value="direct" x-model="mailType" @change="setMailType('direct')" class="mt-1 text-blue-600 focus:ring-blue-500/30">
                                    <span><span class="block text-sm font-semibold text-zinc-800 dark:text-zinc-200">Primary-intent / Direct</span><span class="mt-0.5 block text-xs leading-5 text-zinc-500">Personalized 1-on-1 style (Highest Primary Inbox rate).</span></span>
                                </span>
                            </label>
                        </div>
                    </fieldset>

                    <!-- Step 2: Campaign Content Fields -->
                    <div class="border-t border-zinc-100 pt-5 dark:border-zinc-800">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Step 2: Customize Campaign</p>
                            <span class="rounded-full bg-accent/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-accent-content">Design</span>
                        </div>

                        <div class="mt-3 flex gap-2 overflow-x-auto pb-1">
                            <template x-for="template in visibleTemplates" :key="template.id">
                                <button type="button" @click="applyTemplate(template)"
                                        class="shrink-0 rounded-lg border px-3 py-1.5 text-xs font-medium transition"
                                        :class="activeTemplate === template.id ? 'border-accent bg-accent/10 text-accent-content font-bold' : 'border-zinc-200 text-zinc-500 hover:border-zinc-300 dark:border-zinc-700'"
                                        x-text="template.name"></button>
                            </template>
                        </div>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="bulk-subject" class="label">Subject Line</label>
                                <input id="bulk-subject" name="campaign[subject]" x-model="campaign.subject" required maxlength="150" class="field">
                            </div>

                            <div>
                                <label for="bulk-preheader" class="label">Preview Text</label>
                                <input id="bulk-preheader" name="campaign[preheader]" x-model="campaign.preheader" maxlength="180" class="field" placeholder="Shown beside the subject in Gmail/Outlook">
                            </div>

                            <div>
                                <label for="bulk-headline" class="label">Email Headline</label>
                                <input id="bulk-headline" name="campaign[headline]" x-model="campaign.headline" required maxlength="140" class="field">
                            </div>

                            <div>
                                <label for="bulk-message" class="label">Message Body</label>
                                <textarea id="bulk-message" name="campaign[message]" x-model="campaign.message" required maxlength="2000" rows="4" class="field resize-y"></textarea>
                            </div>

                            <div x-show="mailType === 'promotional'" class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="bulk-offer" class="label">Offer Label</label>
                                    <input id="bulk-offer" name="campaign[offer_label]" x-model="campaign.offer_label" :required="mailType === 'promotional'" maxlength="60" class="field">
                                </div>
                                <div>
                                    <label for="bulk-code" class="label">Promo Code</label>
                                    <input id="bulk-code" name="campaign[promo_code]" x-model="campaign.promo_code" :disabled="mailType === 'direct'" maxlength="32" class="field font-mono uppercase">
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_5.5rem]">
                                <div>
                                    <label for="bulk-cta" class="label">Button Text</label>
                                    <input id="bulk-cta" name="campaign[cta_text]" x-model="campaign.cta_text" required maxlength="60" class="field">
                                </div>
                                <div>
                                    <label for="bulk-color" class="label">Accent Color</label>
                                    <input id="bulk-color" type="color" name="campaign[accent_color]" x-model="campaign.accent_color" class="field h-[42px] cursor-pointer p-1">
                                </div>
                            </div>

                            <div>
                                <label for="bulk-url" class="label">Button Destination URL</label>
                                <input id="bulk-url" type="url" name="campaign[cta_url]" x-model="campaign.cta_url" required maxlength="2048" class="field">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Action -->
                    <div class="mt-6 border-t border-zinc-100 pt-5 dark:border-zinc-800">
                        <button type="submit" :disabled="!contentReady || submitting" class="btn btn-primary w-full py-3 text-sm font-bold shadow-md">
                            <x-icon name="rocket-launch" class="size-4" />
                            <span x-text="submitting ? 'Scheduling Campaign…' : 'Schedule Bulk Automated Campaign (2-min Drip after 6 PM)'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Preview -->
        <aside class="lg:sticky lg:top-20 lg:self-start">
            <div class="card overflow-hidden">
                <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Live Email Preview</p>
                            <p class="text-xs text-zinc-500">Recipient view</p>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">Verified</span>
                    </div>
                </div>

                <div class="border-b border-zinc-100 px-5 py-3 dark:border-zinc-800">
                    <p class="truncate text-xs font-semibold text-zinc-700 dark:text-zinc-300" x-text="campaign.subject || 'Your subject line'"></p>
                    <p class="mt-0.5 truncate text-[10px] text-zinc-400" x-text="campaign.preheader || 'Inbox preview text'"></p>
                </div>

                <div class="bg-zinc-100 p-4 dark:bg-zinc-950/60">
                    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="h-1.5" :style="`background-color: ${campaign.accent_color}`"></div>
                        <div class="p-5">
                            <img src="{{ asset('images/logo-320w.webp') }}" alt="Assignment Help USA" width="320" height="60" class="h-7 w-auto" decoding="async">
                            <p class="mt-6 text-xs font-medium text-zinc-400">A MESSAGE FOR YOU</p>
                            <h3 class="mt-1 text-xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100" x-text="campaign.headline || 'Your headline'"></h3>
                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-zinc-500" x-text="campaign.message || 'Your campaign message will appear here.'"></p>
                            <div x-show="mailType === 'promotional'" class="mt-5 rounded-lg border border-dashed bg-zinc-50 px-4 py-3 text-center dark:bg-zinc-800/50" :style="`border-color: ${campaign.accent_color}`">
                                <p class="text-base font-bold" :style="`color: ${campaign.accent_color}`" x-text="campaign.offer_label || 'Special offer'"></p>
                                <p x-show="campaign.promo_code" class="mt-1 text-xs text-zinc-500">Use code <strong class="font-mono text-zinc-800 dark:text-zinc-200" x-text="campaign.promo_code"></strong></p>
                            </div>
                            <div class="mt-5 rounded-lg py-2.5 text-center text-xs font-semibold text-white" :style="`background-color: ${campaign.accent_color}`" x-text="campaign.cta_text || 'Learn more'"></div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 px-5 py-4 text-xs">
                    <div class="flex justify-between gap-4"><span class="text-zinc-400">From</span><span class="font-medium text-zinc-700 dark:text-zinc-300">Emma &lt;emma@assignmenthelpusa.com&gt;</span></div>
                    <div class="flex justify-between gap-4"><span class="text-zinc-400">Throttling</span><span class="font-medium text-zinc-700 dark:text-zinc-300">1 email / 2 mins</span></div>
                    <div class="flex justify-between gap-4"><span class="text-zinc-400">Schedule</span><span class="font-medium text-zinc-700 dark:text-zinc-300">Daily after 6:00 PM</span></div>
                </div>
            </div>
        </aside>
    </div>

    <!-- TAB 2: SINGLE SEND -->
    <div x-show="activeTab === 'single'" class="grid gap-5 lg:grid-cols-[minmax(0,1.25fr)_minmax(19rem,.75fr)]">
        <div class="space-y-5">
            <div class="card p-5 sm:p-6">
                <form action="{{ route('mail.send') }}" method="POST" @submit="submitting = true">
                    @csrf
                    <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Send Single Instant Email</p>
                    <p class="mt-0.5 text-xs text-zinc-500">Deliver immediately to a single test or customer address.</p>

                    <div class="mt-4">
                        <label for="single-email" class="label">Recipient Email</label>
                        <input id="single-email" type="email" name="email" x-model.trim="singleEmail" required class="field">
                    </div>

                    <div class="mt-4">
                        <label class="label">Message Type</label>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <label class="cursor-pointer rounded-xl border p-3" :class="mailType === 'promotional' ? 'border-accent bg-accent/5' : 'border-zinc-200 dark:border-zinc-700'">
                                <input type="radio" name="mail_type" value="promotional" x-model="mailType" class="sr-only">
                                <span class="text-xs font-semibold">Promotional</span>
                            </label>
                            <label class="cursor-pointer rounded-xl border p-3" :class="mailType === 'direct' ? 'border-blue-500 bg-blue-500/5' : 'border-zinc-200 dark:border-zinc-700'">
                                <input type="radio" name="mail_type" value="direct" x-model="mailType" class="sr-only">
                                <span class="text-xs font-semibold">Primary-intent / Direct</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-5 space-y-4 border-t pt-4">
                        <div>
                            <label class="label">Subject Line</label>
                            <input name="campaign[subject]" x-model="campaign.subject" required class="field">
                        </div>
                        <div>
                            <label class="label">Headline</label>
                            <input name="campaign[headline]" x-model="campaign.headline" required class="field">
                        </div>
                        <div>
                            <label class="label">Message</label>
                            <textarea name="campaign[message]" x-model="campaign.message" required rows="4" class="field"></textarea>
                        </div>
                        <input type="hidden" name="campaign[preheader]" :value="campaign.preheader">
                        <input type="hidden" name="campaign[offer_label]" :value="campaign.offer_label">
                        <input type="hidden" name="campaign[promo_code]" :value="campaign.promo_code">
                        <input type="hidden" name="campaign[cta_text]" :value="campaign.cta_text">
                        <input type="hidden" name="campaign[cta_url]" :value="campaign.cta_url">
                        <input type="hidden" name="campaign[accent_color]" :value="campaign.accent_color">
                    </div>

                    <div class="mt-5">
                        <button type="submit" :disabled="!singleEmail || !contentReady || submitting" class="btn btn-primary w-full py-2.5">
                            <x-icon name="paper-airplane" class="size-4" /> Send Instant Email Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 3: SAVED CONTACTS AUDIENCE DIRECTORY -->
    <div x-show="activeTab === 'contacts'" class="card p-5 sm:p-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b pb-4">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-50">Admin Contacts Directory</h3>
                <p class="text-xs text-zinc-500">Manage your saved leads and student emails with 7-day cooldown status.</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="showAddModal = true" class="btn btn-primary text-xs">
                    <x-icon name="plus" class="size-3.5" /> Add Email ID
                </button>
                <button type="button" @click="showImportModal = true" class="btn btn-secondary text-xs">
                    <x-icon name="document-arrow-up" class="size-3.5" /> Import 100s / CSV
                </button>
            </div>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b text-zinc-400">
                        <th class="py-2.5 font-semibold">Email</th>
                        <th class="py-2.5 font-semibold">Name</th>
                        <th class="py-2.5 font-semibold">Tags</th>
                        <th class="py-2.5 font-semibold">Last Sent</th>
                        <th class="py-2.5 font-semibold">Cooldown Status</th>
                        <th class="py-2.5 text-right font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    <template x-for="c in contacts" :key="c.id">
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                            <td class="py-3 font-medium text-zinc-900 dark:text-zinc-100" x-text="c.email"></td>
                            <td class="py-3 text-zinc-500" x-text="c.name || '—'"></td>
                            <td class="py-3">
                                <span x-show="c.tags" class="rounded bg-zinc-100 px-2 py-0.5 text-[10px] text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300" x-text="c.tags"></span>
                                <span x-show="!c.tags" class="text-zinc-400">—</span>
                            </td>
                            <td class="py-3 text-zinc-500" x-text="c.last_sent_human || 'Never'"></td>
                            <td class="py-3">
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold"
                                      :class="c.is_cooling_down ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'">
                                    <span class="size-1.5 rounded-full" :class="c.is_cooling_down ? 'bg-amber-500' : 'bg-emerald-500'"></span>
                                    <span x-text="c.cooldown_status"></span>
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <form :action="'/mail/contacts/' + c.id" method="POST" @submit="return confirm('Remove this email from contacts?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="contacts.length === 0">
                        <td colspan="6" class="py-8 text-center text-zinc-400">No saved contacts yet. Click "Add Email ID" or "Import 100s" to get started.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 4: LIVE DELIVERY QUEUE & SCHEDULE MONITOR -->
    <div x-show="activeTab === 'queue'" class="card p-5 sm:p-6">
        <div class="flex items-center justify-between border-b pb-4">
            <div>
                <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-50">Automated Campaign Queue</h3>
                <p class="text-xs text-zinc-500">Live delivery queue with 2-minute throttling and automatic next-week deferral.</p>
            </div>
            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                1 Email / 2 Mins @ 6 PM
            </span>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b text-zinc-400">
                        <th class="py-2.5 font-semibold">Recipient</th>
                        <th class="py-2.5 font-semibold">Subject</th>
                        <th class="py-2.5 font-semibold">Scheduled Dispatch</th>
                        <th class="py-2.5 font-semibold">Status</th>
                        <th class="py-2.5 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    <template x-for="item in queue" :key="item.id">
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                            <td class="py-3 font-medium text-zinc-900 dark:text-zinc-100" x-text="item.recipient_email"></td>
                            <td class="py-3 text-zinc-600 dark:text-zinc-300 truncate max-w-xs" x-text="item.campaign_content?.subject || 'Campaign'"></td>
                            <td class="py-3 font-mono text-zinc-500" x-text="formatDate(item.scheduled_at)"></td>
                            <td class="py-3">
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase"
                                      :class="{
                                          'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400': item.status === 'pending',
                                          'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': item.status === 'processing',
                                          'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400': item.status === 'sent',
                                          'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400': item.status === 'deferred',
                                          'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': item.status === 'failed',
                                          'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400': item.status === 'cancelled'
                                      }" x-text="item.status === 'deferred' ? 'Deferred Next Week' : item.status"></span>
                            </td>
                            <td class="py-3 text-right space-x-2">
                                <template x-if="item.status === 'pending' || item.status === 'deferred'">
                                    <form :action="'/mail/queue/' + item.id + '/cancel'" method="POST" class="inline" @submit="return confirm('Cancel this scheduled email?')">
                                        @csrf
                                        <button type="submit" class="text-red-500 hover:underline">Cancel</button>
                                    </form>
                                </template>
                                <template x-if="item.status === 'failed'">
                                    <form :action="'/mail/queue/' + item.id + '/retry'" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-500 hover:underline">Retry</button>
                                    </form>
                                </template>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="queue.length === 0">
                        <td colspan="5" class="py-8 text-center text-zinc-400">No emails currently in queue.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL: ADD SINGLE EMAIL ID -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="card w-full max-w-md p-6" @click.outside="showAddModal = false">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-50">Add Email ID to Contacts</h3>
                <button type="button" @click="showAddModal = false" class="text-zinc-400 hover:text-zinc-600">&times;</button>
            </div>
            <form action="{{ route('mail.contacts.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="label">Email Address *</label>
                    <input type="email" name="email" required placeholder="student@example.com" class="field">
                </div>
                <div>
                    <label class="label">Full Name (Optional)</label>
                    <input type="text" name="name" placeholder="John Doe" class="field">
                </div>
                <div>
                    <label class="label">Tag / Group (Optional)</label>
                    <input type="text" name="tags" placeholder="Leads, Fall2026, VIP" class="field">
                </div>
                <div>
                    <label class="label">Notes (Optional)</label>
                    <textarea name="notes" rows="2" placeholder="Customer requirements or history..." class="field"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showAddModal = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Email ID</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: BULK IMPORT CONTACTS / CSV -->
    <div x-show="showImportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="card w-full max-w-lg p-6" @click.outside="showImportModal = false">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-50">Bulk Import Email IDs (100s / CSV)</h3>
                <button type="button" @click="showImportModal = false" class="text-zinc-400 hover:text-zinc-600">&times;</button>
            </div>
            <form action="{{ route('mail.contacts.import') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="label">Paste Emails (Comma or Newline separated)</label>
                    <textarea name="contacts_data" rows="5" placeholder="student1@gmail.com&#10;student2@yahoo.com&#10;student3@outlook.com" class="field font-mono text-xs"></textarea>
                </div>
                <div>
                    <label class="label">Or Upload CSV / TXT File</label>
                    <input type="file" name="contacts_file" accept=".csv,.txt" class="field py-1.5 text-xs">
                </div>
                <div>
                    <label class="label">Assign Tag to Imported Contacts</label>
                    <input type="text" name="tags" placeholder="e.g. September-Batch" class="field">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showImportModal = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import Contacts</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function mailComposer(recipients, contacts, initialEmail, campaign, initialMailType, drafts, queue, stats) {
        return {
            recipients,
            contacts,
            queue,
            stats,
            activeTab: 'bulk',
            bulkSource: 'manual',
            manualEmailText: '',
            selectedContactIds: [],
            showAddModal: false,
            showImportModal: false,
            email: initialEmail,
            singleEmail: initialEmail,
            campaign,
            mailType: initialMailType,
            drafts,
            submitting: false,
            activeTemplate: initialMailType === 'promotional' ? 'promotion' : 'service-checkin',
            templates: [
                {
                    id: 'promotion', name: '20% Promotion', type: 'promotional',
                    subject: 'Special Offer — Get 20% Off Assignment Help',
                    preheader: 'Expert academic support is now 20% more affordable.',
                    headline: 'Save 20% on your next order',
                    message: 'Get expert help with assignments, essays, research papers, and more — delivered on time, every time.',
                    offer_label: '20% OFF', promo_code: 'WELCOME20', cta_text: 'Claim your discount', accent_color: '#e63946'
                },
                {
                    id: 'deadline', name: 'Deadline Rescue', type: 'promotional',
                    subject: 'Deadline approaching? Expert help is ready',
                    preheader: 'Get matched with an expert and take the pressure off your deadline.',
                    headline: 'Your deadline does not have to be stressful',
                    message: 'Send us your assignment requirements and deadline. Our support team will quickly match you with a qualified expert in your subject.',
                    offer_label: 'Fast expert matching', promo_code: '', cta_text: 'Get urgent help', accent_color: '#2563eb'
                },
                {
                    id: 'service-checkin', name: 'Service Check-in', type: 'direct',
                    subject: 'How can we help with your assignment?',
                    preheader: 'Our support team is available if you need assistance.',
                    headline: 'We are here to help',
                    message: 'This is a quick service check-in. If you have questions about your assignment requirements, deadline, or account, reply to this email and our support team will assist you.',
                    offer_label: '', promo_code: '', cta_text: 'Contact support', accent_color: '#2563eb'
                }
            ],

            get parsedManualCount() {
                if (!this.manualEmailText.trim()) return 0;
                const matches = this.manualEmailText.match(/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/g);
                return matches ? (new Set(matches.map(e => e.toLowerCase()))).size : 0;
            },

            get visibleTemplates() {
                return this.templates.filter(template => template.type === this.mailType);
            },

            get contentReady() {
                return this.campaign.subject.trim()
                    && this.campaign.headline.trim()
                    && this.campaign.message.trim()
                    && (this.mailType !== 'promotional' || this.campaign.offer_label.trim())
                    && this.campaign.cta_text.trim()
                    && /^https?:\/\//i.test(this.campaign.cta_url);
            },

            setMailType(type) {
                this.mailType = type;
                const template = this.templates.find(item => item.type === type);
                if (template) this.applyTemplate(template);
            },

            applyTemplate(template) {
                const destination = this.campaign.cta_url;
                const { id, name, type, ...content } = template;
                this.campaign = { ...this.campaign, ...content, cta_url: destination };
                this.mailType = type;
                this.activeTemplate = template.id;
            },

            toggleSelectAllContacts() {
                if (this.selectedContactIds.length === this.contacts.length) {
                    this.selectedContactIds = [];
                } else {
                    this.selectedContactIds = this.contacts.map(c => c.id);
                }
            },

            formatDate(d) {
                if (!d) return '—';
                const date = new Date(d);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
            }
        };
    }
</script>
@endsection
