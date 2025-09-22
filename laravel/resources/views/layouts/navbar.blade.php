<nav class="px-8 py-4 bg-white flex items-center justify-between border-b">
    <a href="{{ url('/') }}" class="flex items-center gap-2">
        <img src="{{ asset('images/kebun-logo.png') }}" alt="Logo" class="h-8">
    </a>

    <ul class="flex items-center gap-8 text-sm tracking-widest">
        <li><a href="{{ url('/maps') }}" class="hover:text-green-700">MAPS</a></li>

        <li class="relative group">
            <button class="flex items-center gap-1 hover:text-green-700">
                FOUND/FIND
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <ul class="absolute hidden group-hover:block bg-white shadow rounded mt-2 w-44">
                <li><a href="{{ url('/found') }}" class="block px-4 py-2 hover:bg-gray-100">Found Items</a></li>
                <li><a href="{{ url('/report') }}" class="block px-4 py-2 hover:bg-gray-100">Report Lost Item</a></li>
            </ul>
        </li>

        <li><a href="{{ url('/login') }}" class="hover:text-green-700">LOGIN</a></li>
    </ul>
</nav>
