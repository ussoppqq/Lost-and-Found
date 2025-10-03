<div class="min-h-screen flex items-center justify-center bg-white py-12 px-4"
     x-data="{ code: @entangle('generatedCode') }">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-300 p-8">

        {{-- Header --}}
        <div class="flex flex-col items-center mb-6">
            <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center mb-3 border border-gray-300">
                {{-- phone bubble icon (neutral) --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-gray-700" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M16.72 13.07c-.26-.13-1.53-.75-1.77-.83s-.41-.13-.59.13-.68.83-.83 1c-.15.17-.3.2-.56.07-.26-.13-1.12-.41-2.13-1.31-.79-.71-1.32-1.59-1.47-1.85-.15-.26-.02-.4.11-.53.11-.11.26-.3.39-.45.13-.15.17-.26.26-.43.09-.17.04-.32-.02-.45-.06-.13-.59-1.42-.81-1.95-.21-.51-.43-.44-.59-.45h-.5c-.17 0-.45.06-.68.32-.23.26-.9.88-.9 2.15s.92 2.49 1.05 2.66c.13.17 1.81 2.77 4.4 3.88.62.27 1.1.43 1.47.55.62.2 1.19.17 1.64.1.5-.07 1.53-.62 1.75-1.21.22-.59.22-1.09.15-1.21-.06-.11-.24-.18-.5-.31z"/>
                    <path d="M12 2a10 10 0 0 0-8.94 14.47L2 22l5.7-1.99A10 10 0 1 0 12 2zm0 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900">OTP Verification</h2>
            <p class="mt-1 text-sm text-gray-600">Enter your WhatsApp number and captcha to get the OTP.</p>
        </div>

        {{-- Flash success --}}
        @if (session('success'))
            <div class="mb-4 rounded border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-900">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-6">

            {{-- Phone --}}
            <div>
                <label class="sr-only" for="wa-number">WhatsApp Number</label>
                <div class="flex gap-2">
                    <select wire:model="country_code" class="w-32 rounded-lg border border-gray-300 px-3 py-2 focus:border-gray-500 focus:ring-gray-400/30">
                        <option value="+62">🇮🇩 +62</option>
                        <option value="+60">🇲🇾 +60</option>
                        <option value="+65">🇸🇬 +65</option>
                        <option value="+81">🇯🇵 +81</option>
                        <option value="+1">🇺🇸 +1</option>
                        <option value="+44">🇬🇧 +44</option>
                        <option value="+91">🇮🇳 +91</option>
                    </select>
                    <input id="wa-number" type="tel" wire:model="phone" required
                           placeholder="Enter your WhatsApp number"
                           class="flex-1 rounded-lg border border-gray-300 px-3 py-2 focus:border-gray-500 focus:ring-gray-400/30 placeholder:text-gray-500">
                </div>
                @error('phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Captcha --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-semibold text-gray-900">Enter the characters shown below</label>
                    <button type="button" wire:click="refreshCaptcha"
                            class="rounded border border-gray-300 px-2 py-1 text-xs text-gray-800 hover:bg-gray-50">
                        Refresh
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-2">
                    <input type="text" wire:model="captcha" required
                           placeholder="Type code"
                           class="rounded-lg border border-gray-300 px-3 py-2 focus:border-gray-500 focus:ring-gray-400/30 placeholder:text-gray-500">
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-center font-mono text-2xl tracking-widest bg-gray-100 text-gray-900 select-none">
                        {{ $generatedCode }}
                    </div>
                </div>
                @error('captcha') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full rounded-full bg-black text-white py-3 font-semibold hover:bg-gray-900 active:scale-[.99] transition">
                Get OTP
            </button>
        </form>
    </div>
</div>
