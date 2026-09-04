@extends('layouts.app')
@section('title', 'Smart Mail')
@section('heading', 'Smart Mail')

@section('content')
<div x-data="mailComposer(@js($recipients), @js(old('email', '')), @js($campaign))" class="mx-auto max-w-6xl">
    <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="eyebrow">Customer outreach</p>
            <h2 class="mt-1 text-xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">Send the right offer to the right person</h2>
            <p class="mt-1 text-sm text-zinc-500">Suggestions are ranked by order history and recency. You always choose who receives the email.</p>
        </div>
        <div class="flex items-center gap-2 text-xs text-zinc-500">
            <span class="size-2 rounded-full" :class="qualityScore >= 85 ? 'bg-emerald-500' : 'bg-amber-500'"></span>
            <span x-text="qualityScore + '% campaign quality'"></span>
        </div>
    </div>

    <div class="grid gap-5 lg:grid-cols-[minmax(0,1.25fr)_minmax(19rem,.75fr)]">
        <div class="space-y-5">
            <div class="card p-5 sm:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Choose a recipient</p>
                        <p class="mt-0.5 text-xs text-zinc-500">Search known customers or enter any valid address.</p>
                    </div>
                    <span class="rounded-full bg-accent/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-accent-content">Step 1</span>
                </div>

                <form action="{{ route('mail.send') }}" method="POST" class="mt-5" @submit="submitting = true">
                    @csrf
                    <label for="mail-email" class="label">Recipient email</label>
                    <div class="relative">
                        <x-icon name="envelope" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-zinc-400" />
                        <input id="mail-email" type="email" name="email" x-model.trim="email" required autofocus autocomplete="off"
                               placeholder="student@example.com" class="field pl-10 pr-10"
                               :class="email.length && !validEmail ? '!border-red-400 !ring-red-400/10' : ''">
                        <span x-show="validEmail" x-cloak class="absolute right-3 top-1/2 -translate-y-1/2 text-emerald-500">
                            <x-icon name="check-circle" class="size-4" />
                        </span>
                    </div>

                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    <button x-show="suggestedEmail" x-cloak type="button" @click="applyCorrection"
                            class="mt-2 flex items-center gap-1.5 text-left text-xs text-amber-600 hover:underline dark:text-amber-400">
                        <x-icon name="lightbulb" class="size-3.5" />
                        Did you mean <span class="font-semibold" x-text="suggestedEmail"></span>?
                    </button>

                    <div x-show="selectedRecipient" x-cloak class="mt-3 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50/70 p-3 dark:border-emerald-900/60 dark:bg-emerald-950/20">
                        <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white" x-text="initials(selectedRecipient?.name)"></div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-zinc-800 dark:text-zinc-200" x-text="selectedRecipient?.name"></p>
                            <p class="text-xs text-zinc-500"><span x-text="selectedRecipient?.segment"></span> · <span x-text="selectedRecipient?.orders"></span> orders</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase tracking-wide text-zinc-400">Fit score</p>
                            <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400" x-text="selectedRecipient?.score + '%'"></p>
                        </div>
                    </div>

                    <div class="mt-7 border-t border-zinc-100 pt-6 dark:border-zinc-800">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Customize campaign</p>
                                <p class="mt-0.5 text-xs text-zinc-500">Start from a proven template, then make it yours.</p>
                            </div>
                            <span class="rounded-full bg-accent/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-accent-content">Step 2</span>
                        </div>

                        <div class="mt-4 flex gap-2 overflow-x-auto pb-1">
                            <template x-for="template in templates" :key="template.id">
                                <button type="button" @click="applyTemplate(template)"
                                        class="shrink-0 rounded-lg border px-3 py-2 text-xs font-medium transition"
                                        :class="activeTemplate === template.id ? 'border-accent bg-accent/10 text-accent-content' : 'border-zinc-200 text-zinc-500 hover:border-zinc-300 dark:border-zinc-700 dark:hover:border-zinc-600'"
                                        x-text="template.name"></button>
                            </template>
                        </div>

                        <div class="mt-5 space-y-4">
                            <div>
                                <div class="mb-1.5 flex justify-between gap-3">
                                    <label for="campaign-subject" class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Subject line</label>
                                    <span class="text-[10px] text-zinc-400" x-text="campaign.subject.length + '/150'"></span>
                                </div>
                                <input id="campaign-subject" name="campaign[subject]" x-model="campaign.subject" required maxlength="150" class="field">
                            </div>

                            <div>
                                <div class="mb-1.5 flex justify-between gap-3">
                                    <label for="campaign-preheader" class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Preview text</label>
                                    <span class="text-[10px] text-zinc-400" x-text="campaign.preheader.length + '/180'"></span>
                                </div>
                                <input id="campaign-preheader" name="campaign[preheader]" x-model="campaign.preheader" maxlength="180" class="field" placeholder="Shown beside the subject in most inboxes">
                            </div>

                            <div>
                                <label for="campaign-headline" class="label">Email headline</label>
                                <input id="campaign-headline" name="campaign[headline]" x-model="campaign.headline" required maxlength="140" class="field">
                            </div>

                            <div>
                                <div class="mb-1.5 flex justify-between gap-3">
                                    <label for="campaign-message" class="text-xs font-medium text-zinc-600 dark:text-zinc-400">Message</label>
                                    <span class="text-[10px] text-zinc-400" x-text="campaign.message.length + '/2000'"></span>
                                </div>
                                <textarea id="campaign-message" name="campaign[message]" x-model="campaign.message" required maxlength="2000" rows="5" class="field resize-y"></textarea>
                                <p class="mt-1 text-[10px] text-zinc-400">Plain text only. Line breaks are preserved safely.</p>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="campaign-offer" class="label">Offer label</label>
                                    <input id="campaign-offer" name="campaign[offer_label]" x-model="campaign.offer_label" required maxlength="60" class="field">
                                </div>
                                <div>
                                    <label for="campaign-code" class="label">Promo code</label>
                                    <input id="campaign-code" name="campaign[promo_code]" x-model="campaign.promo_code" maxlength="32" pattern="[A-Za-z0-9_-]+" class="field font-mono uppercase" placeholder="Optional">
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_5.5rem]">
                                <div>
                                    <label for="campaign-cta" class="label">Button text</label>
                                    <input id="campaign-cta" name="campaign[cta_text]" x-model="campaign.cta_text" required maxlength="60" class="field">
                                </div>
                                <div>
                                    <label for="campaign-color" class="label">Accent</label>
                                    <input id="campaign-color" type="color" name="campaign[accent_color]" x-model="campaign.accent_color" class="field h-[42px] cursor-pointer p-1">
                                </div>
                            </div>

                            <div>
                                <label for="campaign-url" class="label">Button destination</label>
                                <input id="campaign-url" type="url" name="campaign[cta_url]" x-model="campaign.cta_url" required maxlength="2048" class="field" placeholder="https://assignmenthelpusa.com/order">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 border-t border-zinc-100 pt-5 sm:flex-row sm:items-center sm:justify-between dark:border-zinc-800">
                        <p class="flex items-center gap-1.5 text-xs text-zinc-400">
                            <x-icon name="shield" class="size-3.5" />
                            One recipient per send prevents accidental blasts.
                        </p>
                        <button type="submit" :disabled="!readyToSend || submitting" class="btn btn-primary min-w-36">
                            <x-icon name="envelope" class="size-4" />
                            <span x-text="submitting ? 'Sending…' : 'Send promotion'"></span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card overflow-hidden">
                <div class="border-b border-zinc-100 p-5 dark:border-zinc-800">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Smart suggestions</p>
                            <p class="mt-0.5 text-xs text-zinc-500">Ranked using transparent customer activity signals.</p>
                        </div>
                        <div class="relative">
                            <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 size-3.5 -translate-y-1/2 text-zinc-400" />
                            <input x-model.debounce.150ms="search" type="search" placeholder="Search customers…" class="field w-full py-2 pl-9 sm:w-56">
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    <template x-for="person in visibleRecipients" :key="person.email">
                        <button type="button" @click="choose(person)" class="group flex w-full items-center gap-3 px-5 py-3.5 text-left transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-accent to-orange-400 text-xs font-bold text-white" x-text="initials(person.name)"></div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="truncate text-sm font-medium text-zinc-800 dark:text-zinc-200" x-text="person.name"></p>
                                    <span class="hidden rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] text-zinc-500 sm:inline dark:bg-zinc-800" x-text="person.segment"></span>
                                </div>
                                <p class="truncate text-xs text-zinc-400" x-text="person.email"></p>
                            </div>
                            <div class="hidden text-right sm:block">
                                <p class="text-xs font-semibold text-zinc-700 dark:text-zinc-300" x-text="person.orders + (person.orders === 1 ? ' order' : ' orders')"></p>
                                <p class="text-[10px] text-zinc-400" x-text="person.joined ? 'Joined ' + person.joined : 'Customer'"></p>
                            </div>
                            <div class="w-12 text-right">
                                <span class="text-xs font-bold" :class="person.score >= 70 ? 'text-emerald-500' : 'text-amber-500'" x-text="person.score + '%'"></span>
                            </div>
                            <x-icon name="chevron-right" class="size-4 text-zinc-300 transition group-hover:translate-x-0.5 group-hover:text-accent" />
                        </button>
                    </template>

                    <div x-show="visibleRecipients.length === 0" class="px-5 py-10 text-center">
                        <p class="text-sm text-zinc-500" x-text="recipients.length ? 'No customers match that search.' : 'No customer suggestions are available yet.'"></p>
                        <p class="mt-1 text-xs text-zinc-400">You can still enter an email address above.</p>
                    </div>
                </div>
            </div>
        </div>

        <aside class="lg:sticky lg:top-20 lg:self-start">
            <div class="card overflow-hidden">
                <div class="border-b border-zinc-100 px-5 py-4 dark:border-zinc-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Campaign preview</p>
                            <p class="text-xs text-zinc-500">What the recipient will receive</p>
                        </div>
                        <span class="rounded-full bg-orange-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-orange-700 dark:bg-orange-500/10 dark:text-orange-400">Live</span>
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
                            <div class="mt-5 rounded-lg border border-dashed bg-zinc-50 px-4 py-3 text-center dark:bg-zinc-800/50" :style="`border-color: ${campaign.accent_color}`">
                                <p class="text-base font-bold" :style="`color: ${campaign.accent_color}`" x-text="campaign.offer_label || 'Special offer'"></p>
                                <p x-show="campaign.promo_code" class="mt-1 text-xs text-zinc-500">Use code <strong class="font-mono text-zinc-800 dark:text-zinc-200" x-text="campaign.promo_code"></strong></p>
                            </div>
                            <div class="mt-5 rounded-lg py-2.5 text-center text-xs font-semibold text-white" :style="`background-color: ${campaign.accent_color}`" x-text="campaign.cta_text || 'Learn more'"></div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 px-5 py-4 text-xs">
                    <div class="flex justify-between gap-4"><span class="text-zinc-400">To</span><span class="truncate text-right font-medium text-zinc-700 dark:text-zinc-300" x-text="email || 'Choose a recipient'"></span></div>
                    <div class="flex justify-between gap-4"><span class="text-zinc-400">Offer</span><span class="font-medium text-zinc-700 dark:text-zinc-300" x-text="campaign.offer_label || 'None'"></span></div>
                    <div class="flex justify-between gap-4"><span class="text-zinc-400">Delivery</span><span class="font-medium text-zinc-700 dark:text-zinc-300">Immediately</span></div>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
    function mailComposer(recipients, initialEmail, campaign) {
        return {
            recipients,
            email: initialEmail,
            campaign,
            search: '',
            submitting: false,
            activeTemplate: 'promotion',
            templates: [
                {
                    id: 'promotion', name: '20% promotion',
                    subject: 'Special Offer — Get 20% Off Assignment Help',
                    preheader: 'Expert academic support is now 20% more affordable.',
                    headline: 'Save 20% on your next order',
                    message: 'Get expert help with assignments, essays, research papers, and more — delivered on time, every time.',
                    offer_label: '20% OFF', promo_code: 'WELCOME20', cta_text: 'Claim your discount', accent_color: '#e63946'
                },
                {
                    id: 'deadline', name: 'Deadline rescue',
                    subject: 'Deadline approaching? Expert help is ready',
                    preheader: 'Get matched with an expert and take the pressure off your deadline.',
                    headline: 'Your deadline does not have to be stressful',
                    message: 'Send us your assignment requirements and deadline. Our support team will quickly match you with a qualified expert in your subject.',
                    offer_label: 'Fast expert matching', promo_code: '', cta_text: 'Get urgent help', accent_color: '#2563eb'
                },
                {
                    id: 'winback', name: 'Win-back',
                    subject: 'We saved something special for you',
                    preheader: 'Come back and save on your next academic project.',
                    headline: 'Ready for your next success?',
                    message: 'It has been a while since your last order. Our expert team is ready to support your next assignment with original work and on-time delivery.',
                    offer_label: 'Returning customer offer', promo_code: 'COMEBACK15', cta_text: 'Start a new order', accent_color: '#7c3aed'
                },
                {
                    id: 'welcome', name: 'New customer',
                    subject: 'Welcome — expert assignment support starts here',
                    preheader: 'Meet the academic support team available whenever you need it.',
                    headline: 'Welcome to easier assignment help',
                    message: 'From essays and research to programming and mathematics, our subject experts are available around the clock to help you move forward confidently.',
                    offer_label: 'First-order saving', promo_code: 'WELCOME20', cta_text: 'Place your first order', accent_color: '#059669'
                }
            ],

            get validEmail() {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email);
            },

            get readyToSend() {
                return this.validEmail
                    && this.campaign.subject.trim()
                    && this.campaign.headline.trim()
                    && this.campaign.message.trim()
                    && this.campaign.offer_label.trim()
                    && this.campaign.cta_text.trim()
                    && /^https?:\/\//i.test(this.campaign.cta_url);
            },

            get qualityScore() {
                let score = 40;
                if (this.campaign.subject.length >= 25 && this.campaign.subject.length <= 65) score += 15;
                if (this.campaign.preheader.length >= 35) score += 10;
                if (this.campaign.headline.length >= 15) score += 10;
                if (this.campaign.message.length >= 80 && this.campaign.message.length <= 600) score += 10;
                if (this.campaign.cta_text.length >= 4) score += 10;
                if (this.campaign.promo_code) score += 5;
                return Math.min(score, 100);
            },

            get selectedRecipient() {
                return this.recipients.find(person => person.email.toLowerCase() === this.email.toLowerCase()) || null;
            },

            get visibleRecipients() {
                const term = this.search.trim().toLowerCase();
                const matches = term
                    ? this.recipients.filter(person => `${person.name} ${person.email} ${person.segment}`.toLowerCase().includes(term))
                    : this.recipients;
                return matches.slice(0, 8);
            },

            get suggestedEmail() {
                if (!this.email.includes('@')) return '';
                const [local, domain] = this.email.toLowerCase().split('@');
                const corrections = {
                    'gmial.com': 'gmail.com', 'gmai.com': 'gmail.com', 'gmail.co': 'gmail.com',
                    'hotnail.com': 'hotmail.com', 'hotmai.com': 'hotmail.com',
                    'outlok.com': 'outlook.com', 'yaho.com': 'yahoo.com', 'yahoo.co': 'yahoo.com'
                };
                return corrections[domain] ? `${local}@${corrections[domain]}` : '';
            },

            choose(person) {
                this.email = person.email;
                document.getElementById('mail-email')?.focus({ preventScroll: true });
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            applyCorrection() { this.email = this.suggestedEmail; },

            applyTemplate(template) {
                const destination = this.campaign.cta_url;
                this.campaign = { ...this.campaign, ...template, cta_url: destination };
                this.activeTemplate = template.id;
            },

            initials(name) {
                return (name || 'S').split(/\s+/).slice(0, 2).map(part => part[0]).join('').toUpperCase();
            }
        };
    }
</script>
@endsection
