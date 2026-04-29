{{-- Main Scrollable Container to prevent overflow --}}
<div class="max-h-[65vh] overflow-y-auto pr-3 custom-scrollbar space-y-8">

    {{-- SECTION: FUN FACTS (BLUE) --}}
    <section>
        <div class="flex items-center gap-2 mb-3">
            <span class="w-1.5 h-4 bg-blue-500 rounded-full"></span>
            <h3 class="text-[11px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest">
                Fun Facts
            </h3>
        </div>
        <div class="bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 rounded-2xl p-5 shadow-sm">
            <p class="text-sm md:text-base font-semibold text-gray-800 dark:text-gray-100 leading-relaxed">
                {{ $record->fun_facts }}
            </p>
        </div>
    </section>

    {{-- SECTION: SUMMARY (PURPLE) --}}
    <section>
        <div class="flex items-center gap-2 mb-3">
            <span class="w-1.5 h-4 bg-purple-500 rounded-full"></span>
            <h3 class="text-[11px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest">
                Summary
            </h3>
        </div>
        <div class="bg-white dark:bg-[#1a1c1e] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="prose prose-sm max-w-none dark:prose-invert prose-p:text-gray-600 dark:prose-p:text-gray-300">
                {!! $record->summary !!}
            </div>
        </div>
    </section>

    {{-- SECTION: FULL ARTICLE (EMERALD/GREEN) --}}
    @if($record->files)
    <section class="pb-2">
        <div class="flex items-center gap-2 mb-3">
            <span class="w-1.5 h-4 bg-emerald-500 rounded-full"></span>
            <h3 class="text-[11px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">
                Full Article & Resources
            </h3>
        </div>

        <div class="space-y-3">
            @foreach((array) $record->files as $file)
            <div class="group flex items-center justify-between bg-gray-50 dark:bg-[#202225] border border-gray-200 dark:border-gray-700 p-4 rounded-xl transition-all hover:bg-white dark:hover:bg-[#282a2d] hover:shadow-md hover:border-emerald-400">
                <div class="flex items-center space-x-3 min-w-0">
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">
                        {{ basename($file) }}
                    </span>
                </div>
                
                <a href="{{ asset('storage/' . $file) }}" target="_blank"
                    class="ml-4 flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-emerald-500 rounded-lg hover:bg-emerald-600 transition-all shadow-sm">
                    <span>Open</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #475569;
    }
</style>