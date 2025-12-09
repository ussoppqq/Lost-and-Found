<!-- Footer -->
<div class="px-6 py-4 bg-gray-50 border-t border-gray-200 sticky bottom-0">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
        <p class="text-sm text-gray-600">
            <span class="font-medium">Note:</span> 
            @if($report_type === 'FOUND')
                @if($reporterMode === 'moderator')
                    Report will be registered under your moderator account
                @else
                    Physical item details and storage location are required
                @endif
            @else
                This is a report-only entry (no physical item)
            @endif
        </p>
        <div class="flex gap-3">
            <button type="button" wire:click="closeModal"
                class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="submit" wire:loading.attr="disabled"
                class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 disabled:bg-gray-400 transition-colors shadow-sm flex items-center">
                <span wire:loading.remove wire:target="save" class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    @if($report_type === 'FOUND')
                        Register Found Item
                    @else
                        Confirm Lost Report
                    @endif
                </span>
                <span wire:loading wire:target="save" class="flex items-center">
                    <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
            </button>
        </div>
    </div>
</div>