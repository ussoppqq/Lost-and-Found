<footer class="bg-gray-100 text-gray-700 mt-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 grid grid-cols-1 md:grid-cols-4 gap-8">
        {{-- Logo & blurb --}}
        <div class="md:col-span-2">
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ asset('images/kebun-logo.png') }}" alt="Kebun Raya Bogor" class="h-10">
            </div>
            <p class="text-sm leading-relaxed max-w-prose">
                Building amazing digital experiences that connect people and transform businesses
                through innovative technology solutions.
            </p>

            <div class="flex items-center gap-3 mt-4">
                {{-- Social icons (inline SVG so no external lib needed) --}}
                <a href="#" class="p-2 rounded-full bg-white border hover:bg-gray-50" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 fill-current text-gray-600">
                        <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06C2 17.08 5.66 21.2 10.44 22v-7h-3v-3h3V9.5c0-3 1.8-4.66 4.54-4.66 1.32 0 2.7.24 2.7.24v3h-1.52c-1.5 0-1.96.94-1.96 1.9V12h3.34l-.53 3h-2.81v7C18.34 21.2 22 17.08 22 12.06z"/>
                    </svg>
                </a>
                <a href="#" class="p-2 rounded-full bg-white border hover:bg-gray-50" aria-label="Twitter / X">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 fill-current text-gray-600">
                        <path d="M3 3h3.89l5.13 7.3L17.87 3H21l-7.35 9.9L21 21h-3.89l-5.33-7.59L6.13 21H3l7.53-10.1L3 3z"/>
                    </svg>
                </a>
                <a href="#" class="p-2 rounded-full bg-white border hover:bg-gray-50" aria-label="LinkedIn">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 fill-current text-gray-600">
                        <path d="M6.94 21H3.56V8.98h3.38V21zM5.25 7.5A1.75 1.75 0 1 1 5.26 4a1.75 1.75 0 0 1 0 3.5zM21 21h-3.36v-5.83c0-1.39-.03-3.17-1.93-3.17-1.94 0-2.24 1.52-2.24 3.07V21H10.1V8.98h3.22v1.64h.05c.45-.85 1.55-1.76 3.19-1.76 3.41 0 4.04 2.25 4.04 5.18V21z"/>
                    </svg>
                </a>
                <a href="#" class="p-2 rounded-full bg-white border hover:bg-gray-50" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 fill-current text-gray-600">
                        <path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5A5.5 5.5 0 1 1 6.5 13 5.5 5.5 0 0 1 12 7.5zm0 2A3.5 3.5 0 1 0 15.5 13 3.5 3.5 0 0 0 12 9.5zm5.25-2.75a1 1 0 1 1-1 1 1 1 0 0 1 1-1z"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Quick links --}}
        <div>
            <h4 class="font-semibold mb-3">Links</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#" class="hover:underline">About Us</a></li>
                <li><a href="#" class="hover:underline">Services</a></li>
                <li><a href="#" class="hover:underline">Portfolio</a></li>
                <li><a href="#" class="hover:underline">Blog</a></li>
                <li><a href="#" class="hover:underline">Contact</a></li>
            </ul>
        </div>

        {{-- Contact --}}
        <div>
            <h4 class="font-semibold mb-3">Contact</h4>
            <ul class="space-y-2 text-sm">
                <li class="flex items-start gap-2">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 mt-0.5 fill-current text-gray-600">
                        <path d="M20 4H4v16h16V4zM6 6h12v.51L12 12 6 6.51V6zm0 3.49l5 4.99 5-4.99V18H6V9.49z"/>
                    </svg>
                    <span>hello@yourbrand.com</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 mt-0.5 fill-current text-gray-600">
                        <path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.07 21 3 13.93 3 5a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.24.2 2.45.57 3.57a1 1 0 0 1-.25 1.02l-2.2 2.2z"/>
                    </svg>
                    <span>+1 (555) 123-4567</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 mt-0.5 fill-current text-gray-600">
                        <path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5z"/>
                    </svg>
                    <span>123 Business St, City, State 12345</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <hr class="border-gray-200">
        <div class="py-4 text-xs text-gray-600 flex flex-col md:flex-row items-center justify-between gap-3">
            <p>&copy; {{ date('Y') }} YourBrand. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:underline">Cookie Policy</a>
                <a href="#" class="hover:underline">Privacy Policy</a>
                <a href="#" class="hover:underline">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
