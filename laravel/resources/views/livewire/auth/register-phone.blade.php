<div class="flex h-screen">
    <!-- Left: Gambar -->
    <div class="w-1/2 h-screen hidden md:block">
        <img src="/kebunraya.jpg" class="w-full h-full object-cover">
    </div>

    <!-- Right: Form -->
    <div class="w-full md:w-1/2 flex items-center justify-center bg-gray-50">
        <div class="w-3/4 max-w-md">
            <div class="text-center mb-6">
                <a href="/">
                    <img src="/footer-logo.png" class="mx-auto h-12 mb-4 hover:opacity-80 transition">
                </a>
                <h2 class="text-xl tracking-wide">REGISTER VIA WHATSAPP</h2>
            </div>

            <div class="border-gray-300 rounded-lg p-6 bg-100">
                <form wire:submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1 text-gray-700">Nomor WhatsApp</label>
                        <input type="text" wire:model="phone"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Masukkan nomor WhatsApp">
                        @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-black text-white py-2 rounded">Kirim OTP</button>
                </form>
            </div>
        </div>
    </div>
</div>
