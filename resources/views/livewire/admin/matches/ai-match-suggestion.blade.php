<div>
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('info'))
        <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('info') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Header with Actions -->
    <div class="mb-6 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl border border-purple-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    AI Match Suggestions
                </h2>
                <p class="text-sm text-gray-600 mt-1">Powered by Google Gemini AI</p>
            </div>
            <button 
                wire:click="runBatchAnalysis"
                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold flex items-center transition">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Batch Analysis
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Lost Reports List -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg">
                <div class="p-4 border-b border-gray-200 bg-red-50">
                    <h3 class="font-bold text-gray-900 flex items-center">
                        <span class="bg-red-600 text-white px-2 py-1 rounded text-xs mr-2">LOST</span>
                        Unmatched Reports
                    </h3>
                    <p class="text-xs text-gray-600 mt-1">Select a report to analyze</p>
                </div>
                
                <div class="divide-y divide-gray-200 max-h-[600px] overflow-y-auto">
                    @forelse($lostReports as $report)
                        <button 
                            wire:click="selectLostReport('{{ $report->report_id }}')"
                            class="w-full text-left p-4 hover:bg-gray-50 transition {{ $selectedLostReportId === $report->report_id ? 'bg-blue-50 border-l-4 border-blue-600' : '' }}">
                            <div class="flex items-start space-x-3">
                                @if($report->photo_url)
                                    <img src="{{ Storage::url($report->photo_url) }}" 
                                        alt="{{ $report->item_name }}"
                                        class="w-16 h-16 rounded object-cover flex-shrink-0">
                                @else
                                    <div class="w-16 h-16 bg-red-100 rounded flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $report->item_name }}</p>
                                    <p class="text-xs text-gray-500 line-clamp-2">{{ $report->report_description }}</p>
                                    <div class="flex items-center mt-1 text-xs text-gray-400">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $report->report_datetime->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-sm">No lost reports to analyze</p>
                        </div>
                    @endforelse
                </div>

                @if($lostReports->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $lostReports->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: AI Analysis Results -->
        <div class="lg:col-span-2">
            @if($selectedReport)
                <!-- Selected Report Card -->
                <div class="bg-white rounded-lg shadow-lg mb-6">
                    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-orange-50 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900">Analyzing Report</h3>
                            <p class="text-xs text-gray-600">{{ $selectedReport->formatted_report_number }}</p>
                        </div>
                        <button 
                            wire:click="analyzeMatches"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold flex items-center transition disabled:opacity-50">
                            <svg wire:loading.remove wire:target="analyzeMatches" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <svg wire:loading wire:target="analyzeMatches" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="analyzeMatches">Analyze with AI</span>
                            <span wire:loading wire:target="analyzeMatches">Analyzing...</span>
                        </button>
                    </div>

                    <div class="p-4">
                        <div class="flex items-start space-x-4">
                            @if($selectedReport->photo_url)
                                <img src="{{ Storage::url($selectedReport->photo_url) }}" 
                                    alt="{{ $selectedReport->item_name }}"
                                    class="w-32 h-32 rounded-lg object-cover border-2 border-red-200">
                            @else
                                <div class="w-32 h-32 bg-red-100 rounded-lg flex items-center justify-center border-2 border-red-200">
                                    <svg class="w-16 h-16 text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-bold text-xl text-gray-900 mb-2">{{ $selectedReport->item_name }}</h4>
                                <p class="text-sm text-gray-700 mb-3">{{ $selectedReport->report_description }}</p>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ $selectedReport->category->category_name ?? 'No Category' }}
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        {{ $selectedReport->report_location }}
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $selectedReport->report_datetime->format('d M Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Suggestions -->
                @if(!empty($aiSuggestions))
                    <div class="space-y-4">
                        <h3 class="font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            AI Suggested Matches ({{ count($aiSuggestions) }})
                        </h3>

                        @foreach($aiSuggestions as $index => $suggestion)
                            <div class="bg-white rounded-lg shadow-lg border-2 {{ $suggestion['confidence'] >= 80 ? 'border-green-300' : ($suggestion['confidence'] >= 60 ? 'border-yellow-300' : 'border-gray-300') }}">
                                <!-- Match Header -->
                                <div class="p-4 border-b {{ $suggestion['confidence'] >= 80 ? 'bg-gradient-to-r from-green-50 to-emerald-50' : ($suggestion['confidence'] >= 60 ? 'bg-gradient-to-r from-yellow-50 to-amber-50' : 'bg-gray-50') }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-2xl font-bold text-gray-400">#{{ $index + 1 }}</span>
                                            <div>
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $suggestion['confidence'] >= 80 ? 'bg-green-600 text-white' : ($suggestion['confidence'] >= 60 ? 'bg-yellow-600 text-white' : 'bg-gray-600 text-white') }}">
                                                        {{ $suggestion['confidence'] }}% Match
                                                    </span>
                                                    <span class="px-2 py-1 bg-white rounded text-xs font-semibold text-gray-700">
                                                        {{ $suggestion['recommendation'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-600">{{ $suggestion['found_report']->formatted_report_number }}</p>
                                            </div>
                                        </div>
                                        <button 
                                            wire:click="createMatchFromSuggestion('{{ $suggestion['found_report']->report_id }}', {{ json_encode($suggestion) }})"
                                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold flex items-center transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Create Match
                                        </button>
                                    </div>
                                </div>

                                <!-- Found Report Details -->
                                <div class="p-4">
                                    <div class="flex items-start space-x-4 mb-4">
                                        @if($suggestion['found_report']->photo_url)
                                            <img src="{{ Storage::url($suggestion['found_report']->photo_url) }}" 
                                                alt="{{ $suggestion['found_report']->item_name }}"
                                                class="w-24 h-24 rounded-lg object-cover border-2 border-green-200">
                                        @else
                                            <div class="w-24 h-24 bg-green-100 rounded-lg flex items-center justify-center border-2 border-green-200">
                                                <svg class="w-12 h-12 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h5 class="font-bold text-gray-900 mb-1">{{ $suggestion['found_report']->item_name }}</h5>
                                            <p class="text-sm text-gray-600 mb-2">{{ $suggestion['found_report']->report_description }}</p>
                                            <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                                                <div>📍 {{ $suggestion['found_report']->report_location }}</div>
                                                <div>📅 {{ $suggestion['found_report']->report_datetime->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- AI Reasoning -->
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                        <p class="text-xs font-semibold text-blue-900 mb-1">🤖 AI Analysis:</p>
                                        <p class="text-xs text-blue-800">{{ $suggestion['reasoning'] }}</p>
                                    </div>

                                    <!-- Similarities & Differences -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @if(!empty($suggestion['similarities']))
                                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                                <p class="text-xs font-semibold text-green-900 mb-2">✅ Similarities:</p>
                                                <ul class="text-xs text-green-800 space-y-1">
                                                    @foreach($suggestion['similarities'] as $similarity)
                                                        <li class="flex items-start">
                                                            <span class="mr-1">•</span>
                                                            <span>{{ $similarity }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @if(!empty($suggestion['differences']))
                                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                                <p class="text-xs font-semibold text-amber-900 mb-2">⚠️ Differences:</p>
                                                <ul class="text-xs text-amber-800 space-y-1">
                                                    @foreach($suggestion['differences'] as $difference)
                                                        <li class="flex items-start">
                                                            <span class="mr-1">•</span>
                                                            <span>{{ $difference }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif($isAnalyzing)
                    <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                        <svg class="animate-spin w-12 h-12 mx-auto mb-4 text-indigo-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-gray-600 font-semibold">AI is analyzing potential matches...</p>
                        <p class="text-sm text-gray-500 mt-2">This may take a few moments</p>
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <p class="text-gray-600 font-semibold mb-2">Ready to analyze</p>
                        <p class="text-sm text-gray-500">Click "Analyze with AI" to find potential matches</p>
                    </div>
                @endif
            @else
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <p class="text-gray-600 font-semibold mb-2">No report selected</p>
                    <p class="text-sm text-gray-500">Select a lost report from the list to begin AI analysis</p>
                </div>
            @endif
        </div>
    </div>
</div>