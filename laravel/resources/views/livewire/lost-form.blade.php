<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-8 mt-10">
    <h1 class="text-2xl font-bold text-center mb-8">Report Lost Item</h1>

    <form action="#" method="POST" class="space-y-6">
        @csrf

        {{-- Item Name --}}
        <div>
            <label for="item_name" class="block text-sm font-medium text-gray-700">Item Name</label>
            <input type="text" name="item_name" id="item_name"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                required>
        </div>

        {{-- Category --}}
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category" id="category"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                onchange="document.getElementById('custom-category-box').classList.toggle('hidden', this.value !== 'other')"
                required>
                <option value="">-- Choose Category --</option>
                <option value="Electronics">Electronics</option>
                <option value="Wallet">Wallet</option>
                <option value="Bag">Bag</option>
                <option value="Keys">Keys</option>
                <option value="Clothing">Clothing</option>
                <option value="other">Other</option>
            </select>
        </div>

        {{-- Custom Category (shows only if "Other" is chosen) --}}
        <div id="custom-category-box" class="hidden">
            <label for="custom_category" class="block text-sm font-medium text-gray-700">Custom Category</label>
            <input type="text" name="custom_category" id="custom_category"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
        </div>

        {{-- Color --}}
        <div>
            <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
            <input type="text" name="color" id="color"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                placeholder="e.g. Red, Blue, Black">
        </div>

        {{-- Description / Characteristics --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description /
                Characteristics</label>
            <textarea name="description" id="description" rows="4"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                placeholder="Explain special marks, brand, etc." required></textarea>
        </div>

        {{-- Location --}}
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700">Where It Was Lost</label>
            <input type="text" name="location" id="location"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                placeholder="Example: Near main gate, Garden area" required>
        </div>

        {{-- Date Lost --}}
        <div>
            <label for="date_lost" class="block text-sm font-medium text-gray-700">Date Lost</label>
            <input type="date" name="date_lost" id="date_lost"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                required>
        </div>

        {{-- Contact Info --}}
        <div>
            <label for="contact" class="block text-sm font-medium text-gray-700">Contact Phone/Email</label>
            <input type="text" name="contact" id="contact"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                required>
        </div>

        {{-- Submit --}}
        <div class="text-center">
            <button type="submit" class="px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700">
                Submit Report
            </button>
        </div>
    </form>
</div>