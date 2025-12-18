<div class="max-w-6xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Profile Dashboard</h1>
        <p class="text-sm text-gray-600 mt-1">Manage your profile information and view your reports</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button
                    wire:click="switchTab('profile')"
                    class="flex items-center gap-2 px-6 py-4 text-sm font-semibold border-b-2 transition-colors {{ $currentTab === 'profile' ? 'border-gray-800 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile Information
                </button>
                <button
                    wire:click="switchTab('reports')"
                    class="flex items-center gap-2 px-6 py-4 text-sm font-semibold border-b-2 transition-colors {{ $currentTab === 'reports' ? 'border-gray-800 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    My Reports
                </button>
            </nav>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session()->has('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Profile Tab --}}
    @if($currentTab === 'profile')
    <div class="space-y-6">
        {{-- Profile Photo Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Profile Photo</h2>
            @php
                $user = Auth::user();
                $initials = collect(explode(' ', $user->full_name ?? 'User'))
                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                    ->join('');
            @endphp
            <div class="flex items-center gap-6">
                <div class="relative group">
                    <div class="w-24 h-24 rounded-full border-2 border-gray-200 shadow-sm overflow-hidden bg-gray-200 flex items-center justify-center">
                        @if($newAvatar)
                            <img src="{{ $newAvatar->temporaryUrl() }}" alt="Avatar" class="w-full h-full object-cover">
                        @elseif($avatar)
                            <img src="{{ Storage::url($avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <span class="text-gray-800 font-bold text-2xl">{{ $initials }}</span>
                        @endif
                    </div>

                    {{-- Loading Indicator --}}
                    <div wire:loading wire:target="newAvatar" class="absolute inset-0 flex items-center justify-center bg-black/60 rounded-full">
                        <svg class="animate-spin h-8 w-8 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label for="avatar-upload" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-xl hover:bg-gray-900 cursor-pointer font-semibold text-sm transition-colors">
                        <input type="file" id="avatar-upload" wire:model="newAvatar" accept="image/*" class="hidden">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Upload New Photo
                    </label>
                    @if($newAvatar)
                        <button wire:click="saveAvatar" class="inline-flex items-center px-4 py-2 ml-2 bg-green-600 text-white rounded-xl hover:bg-green-700 font-semibold text-sm transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Photo
                        </button>
                        <button wire:click="$set('newAvatar', null)" class="inline-flex items-center px-4 py-2 ml-2 bg-gray-300 text-gray-700 rounded-xl hover:bg-gray-400 font-semibold text-sm transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </button>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF (MAX. 2MB)</p>
                    @error('newAvatar')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Profile Information Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Profile Information</h2>
                @if(!$editMode)
                <button wire:click="$set('editMode', true)" class="text-sm font-semibold text-gray-800 hover:text-gray-900 transition-colors">
                    Edit
                </button>
                @endif
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                    <input type="text" wire:model="full_name" {{ $editMode ? '' : 'disabled' }}
                           class="w-full px-4 py-3 text-sm rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 disabled:bg-gray-50 disabled:text-gray-500">
                    @error('full_name') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nickname</label>
                    <input type="text" wire:model="nickname" {{ $editMode ? '' : 'disabled' }}
                           class="w-full px-4 py-3 text-sm rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 disabled:bg-gray-50 disabled:text-gray-500">
                    @error('nickname') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                    <input type="email" wire:model="email" {{ $editMode ? '' : 'disabled' }}
                           class="w-full px-4 py-3 text-sm rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 disabled:bg-gray-50 disabled:text-gray-500">
                    @error('email') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                    <input type="text" wire:model="phone_number" {{ $editMode ? '' : 'disabled' }}
                           class="w-full px-4 py-3 text-sm rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800 disabled:bg-gray-50 disabled:text-gray-500">
                    @error('phone_number') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Company (Admin/Moderator only - Read-only) --}}
                @php
                    $user = Auth::user();
                @endphp
                @if($user->company_id && ($user->isAdmin() || $user->isModerator()))
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Company / Kebun Raya</label>
                    <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-xl border-2 border-gray-200">
                        <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="text-sm font-medium text-gray-900 flex-1">{{ $user->company->company_name ?? 'N/A' }}</span>
                        <span class="text-xs font-semibold text-gray-600 bg-white border border-gray-200 px-3 py-1 rounded-lg">Read-only</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Company assignment is managed by system administrators</p>
                </div>
                @endif
            </div>

            @if($editMode)
            <div class="flex gap-3 mt-6">
                <button wire:click="update" class="px-6 py-3 bg-gray-800 text-white rounded-xl hover:bg-gray-900 font-semibold text-sm transition-colors">
                    Save Changes
                </button>
                <button wire:click="cancelEdit" class="px-6 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 font-semibold text-sm transition-colors">
                    Cancel
                </button>
            </div>
            @endif
        </div>

        {{-- Change Password Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Change Password</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password *</label>
                    <input type="password" wire:model="current_password"
                           class="w-full px-4 py-3 text-sm rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800">
                    @error('current_password') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div x-data="{
                    password: @entangle('new_password'),
                    strength: 'weak',
                    strengthWidth: '0',
                    isUnique: true,
                    uniqueMessage: '',
                    checkingUnique: false,

                    calculateStrength() {
                        if (!this.password) {
                            this.strength = 'weak';
                            this.strengthWidth = '0';
                            return;
                        }

                        let score = 0;
                        if (this.password.length >= 8) score += 1;
                        if (this.password.length >= 10) score += 1;
                        if (/[a-z]/.test(this.password)) score += 1;
                        if (/[A-Z]/.test(this.password)) score += 1;
                        if (/[0-9]/.test(this.password)) score += 1;
                        if (/[^A-Za-z0-9]/.test(this.password)) score += 1;

                        if (score <= 2) {
                            this.strength = 'Weak';
                            this.strengthWidth = '33%';
                        } else if (score <= 4) {
                            this.strength = 'Medium';
                            this.strengthWidth = '66%';
                        } else {
                            this.strength = 'Strong';
                            this.strengthWidth = '100%';
                        }

                        // Check uniqueness
                        if (this.password.length >= 8) {
                            this.checkPasswordUnique();
                        }
                    },

                    async checkPasswordUnique() {
                        this.checkingUnique = true;
                        try {
                            const result = await @this.checkPasswordUnique();
                            this.isUnique = result.unique;
                            this.uniqueMessage = result.message;
                        } catch(e) {
                            console.error('Error checking password:', e);
                        }
                        this.checkingUnique = false;
                    }
                }" x-init="$watch('password', () => calculateStrength())">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">New Password *</label>
                    <input type="password" wire:model="new_password"
                           class="w-full px-4 py-3 text-sm rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800"
                           @input="calculateStrength()">

                    {{-- Password Strength Indicator --}}
                    <div class="mt-2" x-show="password">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-medium text-gray-600">Password Strength:</span>
                            <span class="text-xs font-semibold" :class="{
                                'text-red-600': strength === 'Weak',
                                'text-yellow-600': strength === 'Medium',
                                'text-green-600': strength === 'Strong'
                            }" x-text="strength"></span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full transition-all duration-300" :class="{
                                'bg-red-500': strength === 'Weak',
                                'bg-yellow-500': strength === 'Medium',
                                'bg-green-500': strength === 'Strong'
                            }" :style="'width: ' + strengthWidth"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Use uppercase, lowercase, numbers, and symbols for a strong password</p>
                    </div>

                    {{-- Unique Password Check --}}
                    <div class="mt-2" x-show="password && password.length >= 8">
                        <div class="flex items-center gap-2" x-show="checkingUnique">
                            <svg class="w-4 h-4 animate-spin text-gray-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a12 12 0 0112-12V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span class="text-xs text-gray-500">Checking password uniqueness...</span>
                        </div>
                        <div x-show="!checkingUnique && isUnique" class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs text-green-600 font-medium">Password is unique</span>
                        </div>
                        <div x-show="!checkingUnique && !isUnique" class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs text-red-600 font-medium" x-text="uniqueMessage"></span>
                        </div>
                    </div>

                    @error('new_password') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password *</label>
                    <input type="password" wire:model="new_password_confirmation"
                           class="w-full px-4 py-3 text-sm rounded-xl border-2 border-gray-300 focus:border-gray-800 focus:ring-2 focus:ring-gray-800">
                </div>

                <button wire:click="changePassword" class="px-6 py-3 bg-gray-800 text-white rounded-xl hover:bg-gray-900 font-semibold text-sm transition-colors">
                    Change Password
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- My Reports Tab --}}
    @if($currentTab === 'reports')
    <div>
        @if($reports->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reports as $report)
            <a href="{{ route('tracking.detail', ['reportId' => $report->report_id]) }}"
               class="block bg-white rounded-2xl shadow-sm border-2 border-gray-200 hover:border-gray-800 hover:shadow-lg transition-all overflow-hidden group">
                {{-- Image --}}
                <div class="w-full h-48 bg-gray-100 overflow-hidden">
                    @if($report->item && $report->item->photos && $report->item->photos->isNotEmpty())
                        <img src="{{ asset('storage/' . $report->item->photos->first()->photo_url) }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             alt="Item photo">
                    @elseif($report->photo_url)
                        <img src="{{ asset('storage/' . $report->photo_url) }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             alt="Report photo">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="p-5">
                    {{-- Badges --}}
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $report->report_type === 'LOST' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $report->report_type === 'LOST' ? 'Lost' : 'Found' }}
                        </span>
                        @php
                            $statusConfig = [
                                'OPEN' => ['color' => 'yellow', 'text' => 'Open'],
                                'STORED' => ['color' => 'blue', 'text' => 'Stored'],
                                'MATCHED' => ['color' => 'purple', 'text' => 'Matched'],
                                'CLOSED' => ['color' => 'gray', 'text' => 'Closed']
                            ];
                            $status = $statusConfig[$report->report_status] ?? ['color' => 'gray', 'text' => $report->report_status];
                        @endphp
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-700">
                            {{ $status['text'] }}
                        </span>
                        @if($report->item && $report->item->category)
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700">
                            {{ $report->item->category->category_name }}
                        </span>
                        @elseif($report->category)
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700">
                            {{ $report->category->category_name }}
                        </span>
                        @endif
                    </div>

                    {{-- Item Name --}}
                    <div class="text-base font-bold text-gray-900 truncate mb-2 group-hover:text-gray-800 transition-colors">
                        {{ $report->item->item_name ?? $report->item_name ?? 'Unnamed Item' }}
                    </div>

                    {{-- Location & Date --}}
                    <div class="space-y-1">
                        <div class="flex items-center gap-1 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="truncate">{{ $report->report_location }}</span>
                        </div>
                        <div class="flex items-center gap-1 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($report->report_datetime)->format('d M Y') }}</span>
                        </div>
                    </div>

                    {{-- View Details --}}
                    <div class="mt-4 flex items-center text-gray-700 text-xs font-semibold group-hover:gap-2 transition-all">
                        <span>View Details</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">No Reports Yet</h3>
            <p class="text-sm text-gray-600 mb-4">You haven't submitted any lost or found reports.</p>
            <div class="flex gap-3 justify-center">
                <a href="{{ route('lost-form') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-xl hover:bg-gray-900 font-semibold text-sm transition-colors">
                    Report Lost Item
                </a>
                <a href="{{ route('found-form') }}"
                   class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:bg-gray-50 font-semibold text-sm transition-colors">
                    Report Found Item
                </a>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
