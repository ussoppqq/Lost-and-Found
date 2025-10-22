<div>
<!-- Backdrop Blur -->
<div class="fixed inset-0 bg-transparent backdrop-blur-sm transition-opacity z-40"></div>

<!-- Modal Container -->
<div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">
    
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl my-8 transform transition-all max-h-[90vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Create New Match</h3>
                    <p class="mt-1 text-sm text-gray-600">Connect lost item report with found item report</p>
                </div>
                <button 
                    type="button"
                    wire:click="$parent.closeCreateModal"
                    class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-white rounded-lg">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Body -->
        <form wire:submit.prevent="createMatch">
            <div class="px-6 py-6 space-y-6">
                
                <!-- Lost Report Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Lost Item Report
                        <span class="text-red-500">*</span>
                    </label>
                    
                    @if($lostReports->isEmpty())
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-sm text-yellow-800">No LOST reports available for matching.</p>
                        </div>
                    @else
                        <select 
                            wire:model.live="lostReportId"
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('lostReportId') border-red-500 @enderror">
                            <option value="">-- Select Lost Report --</option>
                            @foreach($lostReports as $report)
                                <option value="{{ $report->report_id }}">
                                    {{ $report->formatted_report_number }} - {{ $report->item_name }} - {{ $report->report_datetime->format('d M Y') }} - {{ $report->report_location }}
                                </option>
                            @endforeach
                        </select>
                        @error('lostReportId')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        <!-- Preview Lost Report -->
                        @if($lostReportId)
                            @php
                                $selectedLost = $lostReports->firstWhere('report_id', $lostReportId);
                            @endphp
                            @if($selectedLost)
                                <div class="mt-3 p-4 bg-gradient-to-br from-red-50 to-red-100 border-2 border-red-300 rounded-xl shadow-sm">
                                    <div class="flex items-start space-x-4">
                                        @if($selectedLost->photo_url)
                                            <img src="{{ Storage::url($selectedLost->photo_url) }}" 
                                                 alt="Lost item" 
                                                 class="w-24 h-24 rounded-lg object-cover shadow-md border-2 border-red-200 flex-shrink-0">
                                        @else
                                            <div class="w-24 h-24 bg-red-200 rounded-lg flex items-center justify-center shadow-md border-2 border-red-300 flex-shrink-0">
                                                <svg class="w-12 h-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center mb-2">
                                                <span class="px-2 py-1 bg-gray-800 text-white text-xs font-bold rounded mr-2">{{ $selectedLost->formatted_report_number }}</span>
                                                <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded mr-2">LOST</span>
                                                <h4 class="font-bold text-gray-900 truncate">{{ $selectedLost->item_name }}</h4>
                                            </div>
                                            <p class="text-sm text-gray-700 mb-2 line-clamp-2">{{ $selectedLost->report_description }}</p>
                                            <div class="flex flex-wrap gap-2 text-xs text-gray-600">
                                                <span class="inline-flex items-center px-2 py-1 bg-white rounded-md shadow-sm">
                                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $selectedLost->report_datetime->format('d M Y H:i') }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 bg-white rounded-md shadow-sm">
                                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    {{ $selectedLost->report_location }}
                                                </span>
                                                @if($selectedLost->category)
                                                    <span class="inline-flex items-center px-2 py-1 bg-white rounded-md shadow-sm">
                                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                        </svg>
                                                        {{ $selectedLost->category->category_name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>

                <!-- Found Report Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Found Item Report
                        <span class="text-red-500">*</span>
                    </label>
                    
                    @if($foundReports->isEmpty())
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-sm text-yellow-800">No FOUND reports available for matching.</p>
                        </div>
                    @else
                        <select 
                            wire:model.live="foundReportId"
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('foundReportId') border-red-500 @enderror">
                            <option value="">-- Select Found Report --</option>
                            @foreach($foundReports as $report)
                                <option value="{{ $report->report_id }}">
                                    {{ $report->formatted_report_number }} - {{ $report->item_name }} - {{ $report->report_datetime->format('d M Y') }} - {{ $report->report_location }}
                                </option>
                            @endforeach
                        </select>
                        @error('foundReportId')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        <!-- Preview Found Report -->
                        @if($foundReportId)
                            @php
                                $selectedFound = $foundReports->firstWhere('report_id', $foundReportId);
                            @endphp
                            @if($selectedFound)
                                <div class="mt-3 p-4 bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-300 rounded-xl shadow-sm">
                                    <div class="flex items-start space-x-4">
                                        @if($selectedFound->photo_url)
                                            <img src="{{ Storage::url($selectedFound->photo_url) }}" 
                                                 alt="Found item" 
                                                 class="w-24 h-24 rounded-lg object-cover shadow-md border-2 border-green-200 flex-shrink-0">
                                        @else
                                            <div class="w-24 h-24 bg-green-200 rounded-lg flex items-center justify-center shadow-md border-2 border-green-300 flex-shrink-0">
                                                <svg class="w-12 h-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center mb-2">
                                                <span class="px-2 py-1 bg-gray-800 text-white text-xs font-bold rounded mr-2">{{ $selectedFound->formatted_report_number }}</span>
                                                <span class="px-2 py-1 bg-green-600 text-white text-xs font-bold rounded mr-2">FOUND</span>
                                                <h4 class="font-bold text-gray-900 truncate">{{ $selectedFound->item_name }}</h4>
                                            </div>
                                            <p class="text-sm text-gray-700 mb-2 line-clamp-2">{{ $selectedFound->report_description }}</p>
                                            <div class="flex flex-wrap gap-2 text-xs text-gray-600">
                                                <span class="inline-flex items-center px-2 py-1 bg-white rounded-md shadow-sm">
                                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $selectedFound->report_datetime->format('d M Y H:i') }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 bg-white rounded-md shadow-sm">
                                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    {{ $selectedFound->report_location }}
                                                </span>
                                                @if($selectedFound->category)
                                                    <span class="inline-flex items-center px-2 py-1 bg-white rounded-md shadow-sm">
                                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                        </svg>
                                                        {{ $selectedFound->category->category_name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>

                <!-- Match Notes -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Match Notes <span class="text-gray-500 font-normal">(Optional)</span>
                    </label>
                    <textarea 
                        wire:model="matchNotes"
                        rows="4"
                        placeholder="Add notes about why these items match, similarities found, or any important information..."
                        class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none @error('matchNotes') border-red-500 @enderror"></textarea>
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Provide any additional context that helps validate this match
                    </p>
                    @error('matchNotes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3 sticky bottom-0">
                <button 
                    type="button"
                    wire:click="$parent.closeCreateModal"
                    class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Cancel
                </button>
                <button 
                    type="submit"
                    @disabled(!$lostReportId || !$foundReportId)
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Create Match
                </button>
            </div>
        </form>
    </div>
</div>
</div>