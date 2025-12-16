@props(['items', 'color' => 'blue', 'showDivider' => false])

@php
$colorClasses = [
    'blue' => 'text-blue-600',
    'purple' => 'text-purple-600',
    'green' => 'text-green-600',
];
$colorClass = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<div class="space-y-2 text-sm">
    @foreach($items as $item)
        <div class="flex gap-2">
            <span class="font-semibold {{ $colorClass }} min-w-[28px]">{{ $item['number'] }}.</span>
            <span class="text-gray-700">{{ $item['name'] }}</span>
        </div>
    @endforeach
    
    @if($showDivider)
        <div class="mt-4 pt-4 border-t border-gray-100"></div>
    @endif
</div>