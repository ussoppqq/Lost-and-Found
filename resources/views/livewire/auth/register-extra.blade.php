<div>
    <div class="flex h-screen">
        <!-- Left: Gambar -->
        <div class="w-1/2 h-screen hidden md:block">
            <img src="{{ asset('storage/images/kebunraya.jpg') }}" class="w-full h-full object-cover">
        </div>

        <!-- Right: Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center bg-gray-50">
            <div class="w-3/4 max-w-md">
                <div class="text-center mb-6">
                    <a href="/">
                        <img src="{{ asset('storage/images/footer-logo.png') }}"
                            class="mx-auto h-12 mb-4 hover:opacity-80 transition">
                    </a>
                    <h2 class="text-xl tracking-wide">COMPLETE YOUR ACCOUNT</h2>
                </div>

                <div class="border-gray-300 rounded-lg p-6 bg-100">
                    {{-- Alert untuk success message --}}
                    @if (session()->has('success'))
                        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="register">
                        {{-- Phone Number --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1 text-gray-700">Phone Number</label>
                            <input type="text" wire:model="phone_number"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Contoh: 6281234567890">
                            @error('phone_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- OTP --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1 text-gray-700">OTP Verification</label>
                            <div class="flex space-x-2">
                                <input type="text" wire:model="otp"
                                    class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                    placeholder="Enter 6 digit OTP" maxlength="6">

                                <button type="button" wire:click="sendOtp" wire:loading.attr="disabled"
                                    class="bg-black hover:bg-gray-800 disabled:bg-gray-400 text-white px-4 py-2 rounded transition">
                                    <span wire:loading.remove wire:target="sendOtp">
                                        Send OTP
                                    </span>
                                    <span wire:loading wire:target="sendOtp">
                                        Sending...
                                    </span>
                                </button>
                            </div>

                            @error('otp') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                            {{-- Status OTP --}}
                            @if (session()->has('success'))
                                <div class="mt-2 p-2 bg-green-100 border border-green-300 rounded text-sm text-green-700">
                                    {{ session('success') }}
                                    <br>Code is valid for 5 minutes.
                                </div>
                            @endif
                        </div>

                        <!-- Full Name -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1 text-gray-700">Full Name</label>
                            <input type="text" wire:model="full_name"
                                class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Your full name">
                            @error('full_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>


                        {{-- Email --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1 text-gray-700">Email (Optional)</label>
                            <div class="flex space-x-2">
                                <input type="email" wire:model="email"
                                    class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                    placeholder="Enter your email">

                                @if($email && !$emailVerificationSent)
                                    <button type="button" wire:click="sendEmailVerification" wire:loading.attr="disabled"
                                        class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white px-4 py-2 rounded transition whitespace-nowrap">
                                        <span wire:loading.remove wire:target="sendEmailVerification">
                                            Verify Email
                                        </span>
                                        <span wire:loading wire:target="sendEmailVerification">
                                            Sending...
                                        </span>
                                    </button>
                                @endif
                            </div>
                            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Email Verification Code --}}
                        @if($emailVerificationSent && $step == 2)
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1 text-gray-700">Email Verification Code</label>
                                <div class="flex space-x-2">
                                    <input type="text" wire:model="email_verification_code"
                                        class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                                        placeholder="Enter 6 digit code" maxlength="6">

                                    <button type="button" wire:click="verifyEmail" wire:loading.attr="disabled"
                                        class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white px-4 py-2 rounded transition">
                                        <span wire:loading.remove wire:target="verifyEmail">
                                            Verify
                                        </span>
                                        <span wire:loading wire:target="verifyEmail">
                                            Verifying...
                                        </span>
                                    </button>
                                </div>
                                @error('email_verification_code') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                                <div class="mt-2 p-2 bg-blue-100 border border-blue-300 rounded text-sm text-blue-700">
                                    Verification code has been sent to <strong>{{ $email }}</strong>
                                    <br>Code is valid for 10 minutes.
                                </div>
                            </div>
                        @endif

                        @if($step == 3)
                            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                                ✓ Email successfully verified!
                            </div>
                        @endif

                        {{-- Password --}}
                        <div class="mb-4" x-data="{
                            password: @entangle('password'),
                            strength: 'weak',
                            strengthColor: 'red',
                            strengthWidth: '0',
                            isUnique: true,
                            uniqueMessage: '',
                            checkingUnique: false,

                            calculateStrength() {
                                if (!this.password) {
                                    this.strength = 'weak';
                                    this.strengthColor = 'red';
                                    this.strengthWidth = '0';
                                    return;
                                }

                                let score = 0;
                                if (this.password.length >= 6) score += 1;
                                if (this.password.length >= 8) score += 1;
                                if (/[a-z]/.test(this.password)) score += 1;
                                if (/[A-Z]/.test(this.password)) score += 1;
                                if (/[0-9]/.test(this.password)) score += 1;
                                if (/[^A-Za-z0-9]/.test(this.password)) score += 1;

                                if (score <= 2) {
                                    this.strength = 'Weak';
                                    this.strengthColor = 'red';
                                    this.strengthWidth = '33%';
                                } else if (score <= 4) {
                                    this.strength = 'Medium';
                                    this.strengthColor = 'yellow';
                                    this.strengthWidth = '66%';
                                } else {
                                    this.strength = 'Strong';
                                    this.strengthColor = 'green';
                                    this.strengthWidth = '100%';
                                }

                                // Check uniqueness with debounce
                                if (this.password.length >= 6) {
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
                            <div class="mb-4" x-data="{ show: false }">
    <label class="block text-sm font-medium mb-1 text-gray-700">
        Password
    </label>

    <div class="relative">
        <input
            :type="show ? 'text' : 'password'"
            wire:model="password"
            placeholder="Enter your password"
            class="w-full border border-gray-300 rounded px-3 py-2 pr-10
                   focus:outline-none focus:ring-2 focus:ring-green-500">

        <button type="button"
            @click="show = !show"
            class="absolute inset-y-0 right-3 flex items-center">
            <img
                x-show="show"
                src="{{ asset('icons/view.png') }}"
                alt="Show password"
                class="w-5 h-5 opacity-70 hover:opacity-100 transition">
            <img
                x-show="!show"
                src="{{ asset('icons/hide.png') }}"
                alt="Hide password"
                class="w-5 h-5 opacity-70 hover:opacity-100 transition">
        </button>
    </div>

    @error('password')
        <span class="text-red-600 text-sm">{{ $message }}</span>
    @enderror
</div>



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
                            <div class="mt-2" x-show="password && password.length >= 6">
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

                            @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4" x-data="{ show: false }">
    <div class="mb-4" x-data="{ show: false }">
    <label class="block text-sm font-medium mb-1 text-gray-700">
        Confirm Password
    </label>

    <div class="relative">
        <input
            :type="show ? 'text' : 'password'"
            wire:model="password_confirmation"
            placeholder="Confirm your password"
            class="w-full border border-gray-300 rounded px-3 py-2 pr-10
                   focus:outline-none focus:ring-2 focus:ring-green-500">

        <button type="button"
            @click="show = !show"
            class="absolute inset-y-0 right-3 flex items-center">
            <img
                x-show="show"
                src="{{ asset('icons/view.png') }}"
                alt="Show password"
                class="w-5 h-5 opacity-70 hover:opacity-100 transition">
            <img
                x-show="!show"
                src="{{ asset('icons/hide.png') }}"
                alt="Hide password"
                class="w-5 h-5 opacity-70 hover:opacity-100 transition">
        </button>
    </div>

    @error('password_confirmation')
        <span class="text-red-600 text-sm">{{ $message }}</span>
    @enderror
</div>



                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full bg-black hover:bg-gray-800 disabled:bg-gray-400 text-white py-2 rounded transition">
                            <span wire:loading.remove wire:target="register">Register</span>
                            <span wire:loading wire:target="register">Registering...</span>
                        </button>
                    </form>

                    <div class="flex justify-between mt-3 text-sm">
                        <a href="/login" class="text-blue-600 hover:underline">Already have an account? Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>