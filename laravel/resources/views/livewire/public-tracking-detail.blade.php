{{-- resources/views/livewire/public-tracking-detail.blade.php --}}
<div class="min-h-screen bg-white py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <button
            onclick="history.back()"
            class="inline-flex items-center gap-2 text-gray-700 hover:text-gray-900 mb-6 font-semibold transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back
        </button>

        <div class="space-y-6">
            <!-- Item Photos -->
            @if($this->report && $this->report->item && $this->report->item->photos->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Item Photos</h2>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($this->report->item->photos as $photo)
                    <img
                        src="{{ asset('storage/' . $photo->photo_url) }}"
                        alt="{{ $photo->alt_text ?? 'Item photo' }}"
                        class="w-full h-64 object-cover rounded-xl cursor-pointer hover:opacity-90 transition-opacity border border-gray-200"
                        wire:click="openImageModal('{{ asset('storage/' . $photo->photo_url) }}')"
                    >
                    @endforeach
                </div>
            </div>
            @elseif($this->report && $this->report->photo_url)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Report Photo</h2>
                <img
                    src="{{ asset('storage/' . $this->report->photo_url) }}"
                    alt="Report photo"
                    class="w-full h-96 object-cover rounded-xl cursor-pointer hover:opacity-90 transition-opacity border border-gray-200"
                    wire:click="openImageModal('{{ asset('storage/' . $this->report->photo_url) }}')"
                >
            </div>
            @endif

            <!-- Report Details -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">Report Details</h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 gap-5">
                        <!-- Report Status -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-semibold text-gray-500">Report Status</div>
                            <div class="col-span-2">
                                @php
                                    $statusConfig = [
                                        'OPEN' => ['color' => 'yellow', 'text' => 'Open'],
                                        'STORED' => ['color' => 'blue', 'text' => 'Stored'],
                                        'MATCHED' => ['color' => 'purple', 'text' => 'Matched'],
                                        'CLOSED' => ['color' => 'gray', 'text' => 'Closed']
                                    ];
                                    $status = $statusConfig[$this->report->report_status] ?? ['color' => 'gray', 'text' => $this->report->report_status];
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-700">
                                    {{ $status['text'] }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <!-- Item Name -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-semibold text-gray-500">Item Name</div>
                            <div class="col-span-2 text-sm font-semibold text-gray-900">
                                {{ $this->report->item->item_name ?? $this->report->item_name ?? '-' }}
                            </div>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <!-- Date of Incident -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-semibold text-gray-500">Date of Incident</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($this->report->report_datetime)->format('d F Y, H:i') }}
                            </div>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <!-- Location -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-semibold text-gray-500">Location</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                {{ $this->report->report_location }}
                            </div>
                        </div>

                        @if($this->report->report_description)
                        <div class="border-t border-gray-100"></div>

                        <!-- Description -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-semibold text-gray-500">Description</div>
                            <div class="col-span-2 text-sm text-gray-700 leading-relaxed">
                                {{ $this->report->report_description }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-900 mb-1">Privacy Notice</h3>
                        <p class="text-sm text-blue-800">
                            For security and privacy reasons, personal contact information and other sensitive details are not displayed publicly. If you believe this item belongs to you, please use the tracking system to submit a claim or contact the lost and found office directly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    @if($showImageModal)
    <div
        class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
        wire:click="closeImageModal"
    >
        <div class="max-w-6xl max-h-[90vh] relative">
            <button
                wire:click="closeImageModal"
                class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors"
            >
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img
                src="{{ $currentImage }}"
                alt="Full size image"
                class="max-w-full max-h-[90vh] object-contain rounded-lg"
            >
        </div>
    </div>
    @endif
</div>
