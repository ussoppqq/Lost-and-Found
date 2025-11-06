<div
    x-data="{
        openLost: false,
        openFound: false,
        highlightLost: 0,
        highlightFound: 0,
        selectLost(id) { $wire.selectLost(id); this.openLost = false; },
        selectFound(id) { $wire.selectFound(id); this.openFound = false; },
        move(type, dir) {
            let max = type === 'lost' ? $wire.lostOptions.length : $wire.foundOptions.length;
            if(max === 0) return;
            if(type==='lost'){ this.highlightLost = (this.highlightLost + dir + max) % max; }
            else{ this.highlightFound = (this.highlightFound + dir + max) % max; }
        },
        choose(type){
            if(type==='lost'){
                const i = $wire.lostOptions[this.highlightLost];
                if(i) this.selectLost(i.report_id);
            }else{
                const i = $wire.foundOptions[this.highlightFound];
                if(i) this.selectFound(i.report_id);
            }
        }
    }"
    class="relative"
>
    <div class="fixed inset-0 bg-black/20 backdrop-blur-sm z-40"></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl my-8 overflow-y-auto max-h-[90vh]">

            <div class="sticky top-0 z-10 bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Create New Match</h2>
                    <p class="text-sm text-gray-600">Connect lost item report with found item report</p>
                </div>
                <button wire:click="$parent.closeCreateModal" class="p-2 rounded-lg hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="createMatch" class="px-6 py-6 space-y-8">

                {{-- LOST FIELD --}}
                <div class="relative">
                    <label class="block font-semibold text-gray-900 mb-2">Lost Item Report <span class="text-red-500">*</span></label>

                    <div class="relative" x-data>
                        <div class="flex items-center relative">
                            <input
                                type="text"
                                wire:model.debounce.400ms="lostQuery"
                                x-on:focus="openLost = true"
                                x-on:click.away="openLost = false"
                                x-on:keydown.arrow-down.prevent="move('lost', 1)"
                                x-on:keydown.arrow-up.prevent="move('lost', -1)"
                                x-on:keydown.enter.prevent="choose('lost')"
                                placeholder="Search lost report..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 pr-9"
                            >
                            <div wire:loading wire:target="lostQuery" class="absolute right-3 text-gray-400">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"></path>
                                </svg>
                            </div>
                        </div>

                        {{-- DROPDOWN --}}
                        <div
                            x-show="openLost"
                            x-transition
                            class="absolute z-20 mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-xl max-h-64 overflow-auto"
                        >
                            @if($this->lostOptions->isEmpty())
                                <div class="px-4 py-3 text-sm text-gray-500">No reports found</div>
                            @else
                                <ul class="divide-y divide-gray-100">
                                    @foreach($this->lostOptions as $i => $opt)
                                        <li>
                                            <button
                                                type="button"
                                                wire:click="selectLost('{{ $opt->report_id }}')"
                                                :class="highlightLost === {{ $i }} ? 'bg-indigo-50' : ''"
                                                class="block w-full text-left px-4 py-2 hover:bg-gray-50"
                                            >
                                                <div class="font-medium">{{ $opt->item_name }}</div>
                                                <div class="text-xs text-gray-600">
                                                    {{ $opt->report_datetime->format('d M Y H:i') }} • {{ $opt->report_location }} • #{{ $opt->report_number }}
                                                </div>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    @error('lostReportId')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror

                    {{-- Preview --}}
                    @if($lostReportId)
                        @php $s = \App\Models\Report::with('category')->find($lostReportId); @endphp
                        @if($s)
                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    @if($s->photo_url)
                                        <img src="{{ Storage::url($s->photo_url) }}" class="w-16 h-16 rounded-lg object-cover border">
                                    @endif
                                    <div>
                                        <div class="font-semibold">{{ $s->item_name }}</div>
                                        <div class="text-xs text-gray-600">{{ $s->report_datetime->format('d M Y H:i') }} • {{ $s->report_location }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                {{-- FOUND FIELD --}}
                <div class="relative">
                    <label class="block font-semibold text-gray-900 mb-2">Found Item Report <span class="text-red-500">*</span></label>

                    <div class="relative" x-data>
                        <div class="flex items-center relative">
                            <input
                                type="text"
                                wire:model.debounce.400ms="foundQuery"
                                x-on:focus="openFound = true"
                                x-on:click.away="openFound = false"
                                x-on:keydown.arrow-down.prevent="move('found', 1)"
                                x-on:keydown.arrow-up.prevent="move('found', -1)"
                                x-on:keydown.enter.prevent="choose('found')"
                                placeholder="Search found report..."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 pr-9"
                            >
                            <div wire:loading wire:target="foundQuery" class="absolute right-3 text-gray-400">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"></path>
                                </svg>
                            </div>
                        </div>

                        {{-- DROPDOWN --}}
                        <div
                            x-show="openFound"
                            x-transition
                            class="absolute z-20 mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-xl max-h-64 overflow-auto"
                        >
                            @if($this->foundOptions->isEmpty())
                                <div class="px-4 py-3 text-sm text-gray-500">No reports found</div>
                            @else
                                <ul class="divide-y divide-gray-100">
                                    @foreach($this->foundOptions as $i => $opt)
                                        <li>
                                            <button
                                                type="button"
                                                wire:click="selectFound('{{ $opt->report_id }}')"
                                                :class="highlightFound === {{ $i }} ? 'bg-green-50' : ''"
                                                class="block w-full text-left px-4 py-2 hover:bg-gray-50"
                                            >
                                                <div class="font-medium">{{ $opt->item_name }}</div>
                                                <div class="text-xs text-gray-600">
                                                    {{ $opt->report_datetime->format('d M Y H:i') }} • {{ $opt->report_location }} • #{{ $opt->report_number }}
                                                </div>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    @error('foundReportId')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror

                    {{-- Preview --}}
                    @if($foundReportId)
                        @php $s = \App\Models\Report::with('category')->find($foundReportId); @endphp
                        @if($s)
                            <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    @if($s->photo_url)
                                        <img src="{{ Storage::url($s->photo_url) }}" class="w-16 h-16 rounded-lg object-cover border">
                                    @endif
                                    <div>
                                        <div class="font-semibold">{{ $s->item_name }}</div>
                                        <div class="text-xs text-gray-600">{{ $s->report_datetime->format('d M Y H:i') }} • {{ $s->report_location }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                {{-- NOTES --}}
                <div>
                    <label class="block font-semibold text-gray-900 mb-2">Match Notes</label>
                    <textarea wire:model="matchNotes" rows="4" placeholder="Explain why these reports match..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>

                {{-- FOOTER --}}
                <div class="flex justify-end gap-3 border-t border-gray-200 pt-4">
                    <button wire:click="$parent.closeCreateModal" type="button" class="px-5 py-2.5 text-sm font-semibold bg-white border rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 disabled:opacity-50"
                        @disabled(!$lostReportId || !$foundReportId)">
                        Create Match
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
