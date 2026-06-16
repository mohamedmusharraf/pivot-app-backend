{{-- Mobile First Container --}}
<div class="flex flex-col h-[92vh]">

    {{-- Scroll Area --}}
    <div class="flex-1 overflow-hidden">
        <div class="h-full max-h-[88vh] overflow-y-auto px-2 sm:px-3 custom-scrollbar space-y-5">

            {{-- FUN FACTS --}}
            <section>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-1 h-3 bg-blue-500 rounded-full"></span>
                    <h3 class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider">
                        Fun Facts
                    </h3>
                </div>

                <div class="bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 rounded-xl p-3 shadow-sm">
                    <p class="text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-100 leading-relaxed break-words">
                        {{ $record->fun_facts }}
                    </p>
                </div>
            </section>

            {{-- SUMMARY --}}
            <section>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-1 h-3 bg-purple-500 rounded-full"></span>
                    <h3 class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-wider">
                        Summary
                    </h3>
                </div>

                <div class="bg-white dark:bg-[#1a1c1e] border border-gray-100 dark:border-gray-800 rounded-xl p-3 shadow-sm">
                    <div class="prose prose-xs sm:prose-sm max-w-none break-words dark:prose-invert">
                        {!! $record->summary !!}
                    </div>
                </div>
            </section>

            {{-- VIDEO DETAILS --}}
            @if($record->video_link || $record->video_type)
            <section>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-1 h-3 bg-rose-500 rounded-full"></span>
                    <h3 class="text-[10px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-wider">
                        Video
                    </h3>
                </div>

                <div class="bg-rose-50/40 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900/30 rounded-xl p-3 shadow-sm space-y-2">
                    @if($record->video_type)
                    <p class="text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-100">
                        Type: {{ str_replace('_', ' ', ucfirst($record->video_type)) }}
                    </p>
                    @endif

                    @if($record->video_link)
                    <a href="{{ $record->video_link }}" target="_blank"
                       class="inline-block px-3 py-2 text-[11px] font-bold text-white bg-rose-500 rounded-md hover:bg-rose-600 transition">
                        Open Video
                    </a>
                    @endif
                </div>
            </section>
            @endif

            {{-- FILES --}}
            @if($record->files)
            <section>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-1 h-3 bg-emerald-500 rounded-full"></span>
                    <h3 class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                        Resources
                    </h3>
                </div>

                <div class="space-y-2">
                    @foreach((array) $record->files as $file)
                    <div class="flex flex-col gap-2 bg-gray-50 dark:bg-[#202225] border border-gray-200 dark:border-gray-700 p-2 rounded-lg">

                        {{-- File Info --}}
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="p-1.5 bg-emerald-100 dark:bg-emerald-900/30 rounded text-emerald-600 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>

                            <span class="text-xs text-gray-700 dark:text-gray-300 break-all">
                                {{ basename($file) }}
                            </span>
                        </div>

                        {{-- Button --}}
                        <a href="{{ asset('storage/' . $file) }}" target="_blank"
                           class="w-full text-center px-3 py-2 text-[11px] font-bold text-white bg-emerald-500 rounded-md hover:bg-emerald-600 transition">
                            Open File
                        </a>

                    </div>
                    @endforeach
                </div>
            </section>
            @endif

        </div>
    </div>

    {{-- Sticky Bottom Button --}}
    <!-- <div class="p-2 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-[#1a1c1e]">
        <button class="w-full py-2 text-xs font-semibold bg-gray-100 dark:bg-gray-800 rounded-md">
            Close
        </button>
    </div> -->

</div>

<style>
.custom-scrollbar {
    -webkit-overflow-scrolling: touch;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #475569;
}
</style>
