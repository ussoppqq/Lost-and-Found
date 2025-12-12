<!-- Type Indicator for Standalone -->
<div class="mb-6 p-4 rounded-lg {{ $report_type === 'FOUND' ? 'bg-green-50 border border-green-200' : 'bg-orange-50 border border-orange-200' }}">
    <div class="flex items-center">
        <svg class="w-5 h-5 {{ $report_type === 'FOUND' ? 'text-green-600' : 'text-orange-600' }} mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="font-medium {{ $report_type === 'FOUND' ? 'text-green-900' : 'text-orange-900' }}">
            {{ $report_type === 'FOUND' ? '✓ Physical item available - Complete storage details required' : '⚠ No physical item - Report only mode' }}
        </span>
    </div>
</div>