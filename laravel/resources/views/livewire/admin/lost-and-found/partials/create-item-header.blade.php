<!-- Modal Header -->
<div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50 sticky top-0 z-10">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-semibold text-gray-900">
                @if($mode === 'from-report')
                    @if($report_type === 'FOUND')
                        Confirm Report - Found Item
                    @else
                        Confirm Report - Lost Item
                    @endif
                @else
                    Register {{ ucfirst(strtolower($report_type)) }} Item
                @endif
            </h3>
            <p class="text-sm text-gray-600 mt-1">
                @if($report_type === 'FOUND')
                    Register found item with physical storage details
                @else
                    Record lost item report (no physical item)
                @endif
            </p>
            <!-- Realtime Clock WIB -->
            <p class="text-xs text-indigo-600 mt-1 font-medium" id="currentTime">
                Current Time (WIB): Loading...
            </p>
        </div>
        <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>