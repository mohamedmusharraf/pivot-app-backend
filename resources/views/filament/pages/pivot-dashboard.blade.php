<x-filament::page>
    <div class="relative overflow-hidden rounded-2xl bg-slate-950 text-slate-100 ring-1 ring-slate-800/70">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-24 -right-16 h-72 w-72 rounded-full bg-emerald-500/20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-10 h-72 w-72 rounded-full bg-cyan-500/20 blur-3xl"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,116,144,0.25),transparent_40%),radial-gradient(circle_at_bottom_right,rgba(16,185,129,0.2),transparent_45%)]"></div>
        </div>

        <div class="relative p-6 md:p-8">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-emerald-300/80">
                        Pivot Control
                    </p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-100">
                        Welcome back, {{ auth()->user()->name }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-300">
                        Your focus cockpit for the week ahead.
                    </p>
                </div>
                <div class="rounded-xl bg-slate-900/60 px-4 py-3 ring-1 ring-slate-800/60">
                    <p class="text-xs text-slate-400">Current Tier</p>
                    <p class="text-lg font-semibold text-emerald-300">Growth</p>
                    <p class="text-xs text-emerald-200/80">Active Subscription</p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-xl bg-slate-900/60 p-4 ring-1 ring-slate-800/60">
                    <p class="text-xs text-slate-400">Weekly Goal</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-100">21&ndash;30 hrs</p>
                    <p class="mt-1 text-xs text-cyan-300">Deep Reset</p>
                </div>
                <div class="rounded-xl bg-slate-900/60 p-4 ring-1 ring-slate-800/60">
                    <p class="text-xs text-slate-400">Blocked Time</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-100">18 hrs</p>
                    <p class="mt-1 text-xs text-orange-300">3 hrs remaining</p>
                </div>
                <div class="rounded-xl bg-slate-900/60 p-4 ring-1 ring-slate-800/60">
                    <p class="text-xs text-slate-400">Streak</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-100">9 days</p>
                    <p class="mt-1 text-xs text-emerald-300">No resets</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-2xl bg-white/80 p-6 shadow-sm ring-1 ring-slate-200/70 dark:bg-slate-900/70">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Safety & Contacts</h3>
                <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200">
                    Active
                </span>
            </div>

            <div class="mt-4 rounded-xl bg-emerald-50/80 p-3 text-sm text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200">
                Pivot Lockout Active &mdash; Emergency whitelist enabled
            </div>

            <div class="mt-4 space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-slate-700 dark:text-slate-200">Sarah (Partner)</span>
                    <x-filament::button size="sm" color="success">Call</x-filament::button>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-700 dark:text-slate-200">Dr. Miller</span>
                    <x-filament::button size="sm" color="success">Call</x-filament::button>
                </div>
            </div>

            <div class="mt-5">
                <x-filament::button color="danger" class="w-full">CALL 000</x-filament::button>
            </div>
        </div>

        <div class="lg:col-span-2 rounded-2xl bg-white/80 p-6 shadow-sm ring-1 ring-slate-200/70 dark:bg-slate-900/70">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Weekly Progress</h3>
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Focus Engine</p>
            </div>

            <div class="mt-4 rounded-full bg-slate-200/70 p-1 dark:bg-slate-800">
                <div class="h-4 w-2/3 rounded-full bg-gradient-to-r from-emerald-400 via-cyan-400 to-blue-500"></div>
            </div>
            <div class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                18 of 30 hours completed
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <x-filament::button color="primary">Launch My Pivot</x-filament::button>
                <x-filament::button color="gray">View Analytics</x-filament::button>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="rounded-xl bg-slate-100/80 p-4 text-sm text-slate-700 dark:bg-slate-800/70 dark:text-slate-200">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Next Reset</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">Thursday, 7:00 AM</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Auto-guard enabled</p>
                </div>
                <div class="rounded-xl bg-slate-100/80 p-4 text-sm text-slate-700 dark:bg-slate-800/70 dark:text-slate-200">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Focus Window</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">9:00 AM &ndash; 12:00 PM</p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Deep work protected</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Subscription Plans</h3>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Choose your pace</p>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-2xl bg-white/80 p-6 ring-1 ring-slate-200/70 dark:bg-slate-900/70">
                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Starter</h4>
                <p class="text-sm text-slate-500 dark:text-slate-400">$0 + IAP</p>
                <ul class="mt-4 space-y-2 text-sm text-slate-700 dark:text-slate-200">
                    <li>&check; 7hr App Blocking</li>
                    <li>&check; Level 1 Challenges</li>
                    <li>&check; Basic Analytics</li>
                </ul>
                <x-filament::button class="mt-4 w-full">Current Plan</x-filament::button>
            </div>

            <div class="rounded-2xl bg-gradient-to-br from-emerald-500/10 via-cyan-500/10 to-blue-500/10 p-6 ring-1 ring-emerald-200/70 dark:ring-emerald-400/40">
                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Growth</h4>
                <p class="text-sm text-slate-500 dark:text-slate-300">$12.99 / mo</p>
                <ul class="mt-4 space-y-2 text-sm text-slate-700 dark:text-slate-200">
                    <li>&check; 12hr Blocking</li>
                    <li>&check; Level 1 &amp; 2 Access</li>
                    <li>&check; Live Tracking</li>
                </ul>
                <x-filament::button color="primary" class="mt-4 w-full">Upgrade</x-filament::button>
            </div>

            <div class="rounded-2xl bg-white/80 p-6 ring-1 ring-slate-200/70 dark:bg-slate-900/70">
                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Mastery</h4>
                <p class="text-sm text-slate-500 dark:text-slate-400">$17.99 / mo</p>
                <ul class="mt-4 space-y-2 text-sm text-slate-700 dark:text-slate-200">
                    <li>&check; 24hr Hardcore Mode</li>
                    <li>&check; Family Sharing</li>
                    <li>&check; Pattern Log Pro</li>
                </ul>
                <x-filament::button color="success" class="mt-4 w-full">Go Premium</x-filament::button>
            </div>
        </div>
    </div>
</x-filament::page>
