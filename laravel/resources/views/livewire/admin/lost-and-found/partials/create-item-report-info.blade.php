<!-- Report Information (Read-only) -->
<div class="mb-8 {{ $report_type === 'FOUND' ? 'bg-green-50 border-green-200' : 'bg-orange-50 border-orange-200' }} border rounded-lg p-5">
    <div class="flex items-center mb-4">
        <svg class="w-5 h-5 {{ $report_type === 'FOUND' ? 'text-green-600' : 'text-orange-600' }} mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h4 class="text-lg font-semibold {{ $report_type === 'FOUND' ? 'text-green-900' : 'text-orange-900' }}">
            @if($report_type === 'LOST')
                Lost Item Report (Original Report)
            @else
                Found Item Report
            @endif
        </h4>
        <span class="ml-auto px-3 py-1 text-xs font-medium rounded-full {{ $report_type === 'FOUND' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
            {{ $report_type }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="col-span-2 bg-white rounded-lg p-4">
            <h5 class="text-sm font-semibold text-gray-700 mb-3">Reporter Details</h5>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-gray-500">Name</label>
                    <p class="text-sm font-medium">{{ $reporter_name }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500">Phone</label>
                    <p class="text-sm">{{ $reporter_phone }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500">Email</label>
                    <p class="text-sm truncate">{{ $reporter_email ?: 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4">
            <label class="text-xs text-gray-500">Item Name</label>
            <p class="text-sm font-medium">{{ $item_name }}</p>
        </div>

        <div class="bg-white rounded-lg p-4">
            <label class="text-xs text-gray-500">Category</label>
            <p class="text-sm font-medium">
                {{ $categories->firstWhere('category_id', $category_id)?->category_name ?? '-' }}
            </p>
        </div>

        <div class="bg-white rounded-lg p-4">
            <label class="text-xs text-gray-500">Location</label>
            <p class="text-sm">{{ $report_location }}</p>
        </div>

        <div class="bg-white rounded-lg p-4">
            <label class="text-xs text-gray-500">Date & Time</label>
            <p class="text-sm">{{ \Carbon\Carbon::parse($report_datetime)->format('d M Y, H:i') }}</p>
        </div>

        <div class="col-span-2 bg-white rounded-lg p-4">
            <label class="text-xs text-gray-500">Description</label>
            <p class="text-sm">{{ $report_description }}</p>
        </div>

        @if(!empty($reportPhotos))
        <div class="col-span-2 bg-white rounded-lg p-4">
            <label class="text-xs text-gray-500 mb-3 block">User Uploaded Photos</label>
            <div class="grid grid-cols-4 gap-3">
                @foreach($reportPhotos as $photo)
                    @php
                        $photoUrl = str_starts_with($photo, 'http') ? $photo : url('storage/' . $photo);
                    @endphp
                    <img src="{{ $photoUrl }}" 
                         alt="Report photo"
                         class="w-full h-24 object-cover rounded-lg border-2 border-gray-200"
                         onerror="this.src='{{ asset('images/placeholder.png') }}'">
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>