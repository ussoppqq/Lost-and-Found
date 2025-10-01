<div>
   <section class="container mx-auto px-4 md:px-6 lg:px-12 py-12">

    {{-- Decorative Background Elements --}}
    <div class="relative w-full max-w-7xl mx-auto">
        <div class="absolute -top-4 -left-4 w-72 h-72 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-8 -right-4 w-72 h-72 bg-gradient-to-br from-pink-400 to-orange-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse delay-700"></div>
        
        {{-- Avatar + Nama + Email (Top Section) --}}
        <div class="relative bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-6 sm:p-10 border border-gray-100 mb-6">
            {{-- Decorative Top Border --}}
            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-24 h-1.5 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-full"></div>

            {{-- Back Button (Top Left) --}}
            <button onclick="window.history.back()" 
                    class="absolute top-6 left-6 group flex items-center space-x-2 px-3 py-2 bg-gray-100/80 hover:bg-blue-50 rounded-xl shadow-md hover:shadow-lg border border-gray-200 hover:border-blue-300 transition-all duration-300 hover:scale-105 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600 group-hover:text-blue-600 transition-colors duration-300 group-hover:-translate-x-1 transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="text-xs font-medium text-gray-700 group-hover:text-blue-600 transition-colors duration-300">Back</span>
            </button>

            {{-- Avatar + Info --}}
            <div class="flex flex-col items-center relative">
                {{-- Avatar with Gradient Ring & Edit Button --}}
                @php
                    $user = Auth::user();
                    $initials = collect(explode(' ', $user->full_name ?? 'User'))
                        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                        ->join('');
                @endphp

                <div class="relative inline-block group">
                    {{-- Gradient Border --}}
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-full blur opacity-75 group-hover:opacity-100 transition duration-500 animate-pulse"></div>
                    
                    {{-- Avatar Circle --}}
                    <div class="relative flex items-center justify-center w-28 h-28 sm:w-32 sm:h-32 rounded-full border-4 border-white shadow-2xl overflow-hidden bg-gray-200 text-gray-800 font-bold text-2xl sm:text-3xl transform group-hover:scale-105 transition duration-300 cursor-pointer"
                         onclick="document.getElementById('avatarModal').classList.remove('hidden')">
                        @if($avatar || $newAvatar)
                            @if($newAvatar)
                                <img src="{{ $newAvatar->temporaryUrl() }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <img src="{{ Storage::url($avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @endif
                            {{-- Hover Overlay --}}
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-300 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                </svg>
                            </div>
                        @else
                            {{ $initials }}
                        @endif
                        
                        {{-- Loading Indicator saat Upload - INSIDE avatar untuk centering sempurna --}}
                        <div wire:loading wire:target="newAvatar" class="absolute inset-0 flex items-center justify-center bg-black/60 rounded-full z-30">
                            <svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>

                    {{-- Edit Button (Bottom Right) --}}
                    <label for="avatar-upload" class="absolute -bottom-1 -right-1 w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:shadow-xl transform hover:scale-110 transition duration-300 border-3 border-white group/edit z-20">
                        <input type="file" id="avatar-upload" wire:model="newAvatar" accept="image/*" class="hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white group-hover/edit:rotate-12 transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </label>

                    {{-- Status Badge --}}
                    <div class="absolute -bottom-1 -left-1 w-5 h-5 bg-green-500 border-4 border-white rounded-full shadow-lg"></div>
                </div>

                {{-- Preview Info --}}
                @if($newAvatar)
                    <div class="mt-3 text-center">
                        <p class="text-sm text-green-600 font-medium flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>New photo ready to save</span>
                        </p>
                    </div>
                @endif

                @error('newAvatar')
                    <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
                
                <h2 class="mt-4 text-xl sm:text-2xl font-bold bg-gradient-to-r from-gray-800 via-gray-900 to-gray-800 bg-clip-text text-transparent">
                    {{ $full_name }}
                </h2>
                <p class="mt-1 text-gray-500 text-sm flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>{{ Auth::user()->email }}</span>
                </p>
            </div>
        </div>

        {{-- Two Cards Side by Side --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Left Card: Profile Information --}}
            <div class="relative bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-6 sm:p-8 border border-gray-100">
                <h3 class="text-gray-800 font-bold text-lg mb-6 flex items-center space-x-2">
                    <span class="w-8 h-8 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 text-white rounded-lg text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <span>Profile Information</span>
                </h3>

                <div class="space-y-5">
                    {{-- Full Name --}}
                    <div>
                        <label class="text-gray-600 text-sm font-medium mb-2 block">Full Name</label>
                        <input type="text" wire:model="full_name" @disabled(!$editMode)
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-blue-500 outline-none transition duration-300 disabled:bg-gray-100 disabled:cursor-not-allowed">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="text-gray-600 text-sm font-medium mb-2 block">Email</label>
                        <input type="email" wire:model="email" @disabled(!$editMode)
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-indigo-500 outline-none transition duration-300 disabled:bg-gray-100 disabled:cursor-not-allowed">
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="text-gray-600 text-sm font-medium mb-2 block">Phone Number</label>
                        <input type="text" wire:model="phone_number" @disabled(!$editMode)
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-green-500 outline-none transition duration-300 disabled:bg-gray-100 disabled:cursor-not-allowed">
                    </div>

                    {{-- Company --}}
                    @if(in_array(Auth::user()->role->role_code ?? '', ['ADMIN','MODERATOR']))
                        <div>
                            <label class="text-gray-600 text-sm font-medium mb-2 block">Company</label>
                            <input type="text" wire:model="company_name" @disabled(!$editMode)
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-orange-500 outline-none transition duration-300 disabled:bg-gray-100 disabled:cursor-not-allowed">
                        </div>
                    @endif
                </div>

                {{-- Tombol Action --}}
                <div class="mt-6 flex space-x-3">
                    @if(!$editMode)
                        <button wire:click="$set('editMode', true)" 
                            class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg text-sm font-medium hover:from-blue-700 hover:to-blue-800 transition duration-300 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:scale-105">
                            Edit Profile
                        </button>
                    @else
                        <button wire:click="update" 
                            class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg text-sm font-medium hover:from-green-700 hover:to-green-800 transition duration-300 shadow-lg shadow-green-500/30 hover:shadow-xl hover:scale-105 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Save Changes</span>
                        </button>
                        <button wire:click="cancelEdit" 
                            class="px-5 py-2.5 bg-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-400 transition duration-300">
                            Cancel
                        </button>
                    @endif
                </div>
            </div>

            {{-- Right Card: Change Password --}}
            <div class="relative bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-6 sm:p-8 border border-gray-100">
                <h3 class="text-gray-800 font-bold text-lg mb-6 flex items-center space-x-2">
                    <span class="w-8 h-8 flex items-center justify-center bg-gradient-to-br from-red-500 to-pink-600 text-white rounded-lg text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <span>Change Password</span>
                </h3>

                <div class="space-y-5">
                    {{-- Current Password --}}
                    <div class="group">
                        <label class="flex items-center space-x-2 text-gray-600 text-sm font-medium mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <span>Current Password</span>
                        </label>
                        <input type="password" wire:model="current_password" 
                            placeholder="Enter current password"
                            class="w-full px-4 py-3.5 rounded-xl bg-gray-50/50 text-gray-800 outline-none border-2 border-gray-200 
                                   focus:border-red-500 focus:bg-white focus:shadow-lg focus:shadow-red-500/10 transition-all duration-300 group-hover:border-gray-300 placeholder:text-gray-400">
                        @error('current_password')
                            <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="group">
                        <label class="flex items-center space-x-2 text-gray-600 text-sm font-medium mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>New Password</span>
                        </label>
                        <input type="password" wire:model="new_password" 
                            placeholder="Enter new password"
                            class="w-full px-4 py-3.5 rounded-xl bg-gray-50/50 text-gray-800 outline-none border-2 border-gray-200 
                                   focus:border-green-500 focus:bg-white focus:shadow-lg focus:shadow-green-500/10 transition-all duration-300 group-hover:border-gray-300 placeholder:text-gray-400">
                        @error('new_password')
                            <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Confirm New Password --}}
                    <div class="group">
                        <label class="flex items-center space-x-2 text-gray-600 text-sm font-medium mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Confirm New Password</span>
                        </label>
                        <input type="password" wire:model="new_password_confirmation" 
                            placeholder="Confirm new password"
                            class="w-full px-4 py-3.5 rounded-xl bg-gray-50/50 text-gray-800 outline-none border-2 border-gray-200 
                                   focus:border-blue-500 focus:bg-white focus:shadow-lg focus:shadow-blue-500/10 transition-all duration-300 group-hover:border-gray-300 placeholder:text-gray-400">
                        @error('new_password_confirmation')
                            <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Password Requirements --}}
                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <p class="text-sm font-semibold text-gray-700 mb-2 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Requirements:</span>
                        </p>
                        <ul class="text-xs text-gray-600 space-y-1 ml-6">
                            <li class="flex items-center space-x-2">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                <span>Min. 8 characters</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                <span>Upper & lowercase letters</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                <span>At least one number</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Change Password Button --}}
                    <button wire:click="changePassword"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-xl text-sm font-semibold hover:from-red-700 hover:to-pink-700 transition-all duration-300 shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 hover:scale-105 flex items-center justify-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Update Password</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Modal untuk Lihat Foto Penuh --}}
<div id="avatarModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="relative max-w-4xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
        {{-- Header Modal --}}
        <div class="absolute top-0 left-0 right-0 bg-gradient-to-b from-black/60 to-transparent p-4 z-10 flex justify-between items-center">
            <h3 class="text-white font-semibold text-lg">Profile Photo</h3>
            <button onclick="document.getElementById('avatarModal').classList.add('hidden')" 
                    class="w-10 h-10 flex items-center justify-center bg-white/20 hover:bg-white/30 rounded-full transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Image Container --}}
        <div class="flex items-center justify-center bg-gray-900 min-h-[400px] max-h-[80vh]">
            @if($avatar || $newAvatar)
                @if($newAvatar)
                    <img src="{{ $newAvatar->temporaryUrl() }}" alt="Avatar Full" class="max-w-full max-h-[80vh] object-contain">
                @else
                    <img src="{{ Storage::url($avatar) }}" alt="Avatar Full" class="max-w-full max-h-[80vh] object-contain">
                @endif
            @else
                <div class="text-white text-xl font-bold p-12">{{ $initials }}</div>
            @endif
        </div>

        {{-- Footer dengan tombol aksi --}}
        <div class="bg-gray-50 p-4 flex justify-end space-x-3">
            <label for="avatar-upload-modal" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg text-sm font-medium hover:from-blue-700 hover:to-purple-700 transition duration-300 shadow-lg cursor-pointer flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Change Photo</span>
            </label>
            <input type="file" id="avatar-upload-modal" wire:model="newAvatar" accept="image/*" class="hidden">
            
            <button onclick="document.getElementById('avatarModal').classList.add('hidden')" 
                    class="px-5 py-2.5 bg-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-400 transition duration-300">
                Close
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0%, 100% {
            opacity: 0.2;
        }
        50% {
            opacity: 0.3;
        }
    }
    
    .animate-pulse {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .delay-700 {
        animation-delay: 700ms;
    }
</style>
</div>