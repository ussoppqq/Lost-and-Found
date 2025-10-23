
<x-layouts.app>
    {{-- ========== HERO SECTION ========== --}}
    <section class="relative w-full h-screen overflow-hidden">
        <div class="absolute inset-0">
            <video 
                src="{{ asset('storage/images/video.mp4') }}" 
                autoplay 
                muted 
                loop 
                playsinline
                class="absolute top-0 left-0 w-full h-full object-cover"
            >
            </video>
            <div class="absolute inset-0 bg-black/30"></div>
        </div>


        {{-- Search Overlay --}}
        <div class="absolute bottom-8 md:bottom-12 lg:bottom-16 left-1/2 -translate-x-1/2 w-full max-w-xs sm:max-w-md md:max-w-lg lg:max-w-2xl px-4 z-20">
            <form action="{{ url('/search') }}" method="GET"
                class="relative flex items-center bg-white/95 backdrop-blur-sm rounded-full shadow-2xl overflow-hidden h-12 sm:h-14 md:h-16 lg:h-[65px] border border-white/20">

                {{-- Search Icon --}}
                <div class="absolute left-4 lg:left-6 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-6 lg:w-6" fill="none" 
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                    </svg>
                </div>

                {{-- Search Input --}}
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Search....."
                    class="font-openSans flex-1 pl-12 lg:pl-16 pr-24 lg:pr-32 h-full bg-transparent text-gray-700 placeholder:text-gray-400 
                           placeholder:font-openSans text-sm lg:text-base outline-none rounded-full transition-all duration-300
                           focus:placeholder:text-gray-300" 
                />

                {{-- Search Button --}}
                <button 
    type="submit"
    class="absolute right-2 lg:right-3 bg-gray-800 text-white px-4 lg:px-6 py-2 lg:py-3 rounded-full 
           text-sm lg:text-base font-medium hover:bg-gray-900 transition-all duration-300 
           shadow-lg hover:shadow-xl active:scale-95"
>
    <span class="hidden sm:inline">Search</span>
    <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
    </svg>
</button>

            </form>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white animate-bounce">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </section>

    {{-- ========== MAIN CONTENT SECTION ========== --}}
    <section id="lostandfound" class="py-16 lg:py-24 bg-white">

        <div class="container mx-auto px-4 md:px-8">
            
            {{-- Section Title --}}
            <div class="text-center mb-12 lg:mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 tracking-wide mb-4">
                    KEBUN RAYA BOGOR
                </h2>
                <div class="w-24 h-1 bg-green-600 mx-auto rounded-full"></div>
            </div>

            {{-- Cards Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 max-w-4xl mx-auto">
                
                {{-- LOST Card --}}
                <div class="group">
                    <a href="{{ url('/lost-form') }}"
                        class="block bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        
                        <div class="relative h-48 md:h-56 lg:h-64 overflow-hidden">
                            <img 
                                src="{{ asset('storage/images/kebunrayaikon.jpg') }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
                                alt="Lost Items"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <h3 class="text-white text-xl lg:text-2xl font-bold tracking-widest drop-shadow-lg">
                                    LOST
                                </h3>
                                <p class="text-white/90 text-sm mt-1">Report lost items</p>
                            </div>
                        </div>
                        
                        <div class="p-6 lg:p-8 text-center">
                            <div class="w-16 h-1 bg-red-500 mx-auto rounded-full mb-4"></div>
                            <p class="text-gray-600 leading-relaxed">
                                Help us reunite lost items with their owners. Report any items you've lost in the botanical garden.
                            </p>
                            <div class="mt-6">
                                <span class="inline-flex items-center text-red-600 font-semibold group-hover:text-red-700 transition-colors">
                                    Report Lost Item
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- FOUND Card --}}
                <div class="group">
                    <a href="{{ url('/found-form') }}"
                        class="block bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        
                        <div class="relative h-48 md:h-56 lg:h-64 overflow-hidden">
                            <img 
                                src="{{ asset('storage/images/kebunrayaikon.jpg') }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
                                alt="Found Items"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <h3 class="text-white text-xl lg:text-2xl font-bold tracking-widest drop-shadow-lg">
                                    FOUND
                                </h3>
                                <p class="text-white/90 text-sm mt-1">Report found items</p>
                            </div>
                        </div>
                        
                        <div class="p-6 lg:p-8 text-center">
                            <div class="w-16 h-1 bg-green-500 mx-auto rounded-full mb-4"></div>
                            <p class="text-gray-600 leading-relaxed">
                                Found something that doesn't belong to you? Help us return it to the rightful owner.
                            </p>
                            <div class="mt-6">
                                <span class="inline-flex items-center text-green-600 font-semibold group-hover:text-green-700 transition-colors">
                                    Report Found Item
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Additional Info Section --}}
            <div class="mt-16 lg:mt-20 text-center">
                <div class="bg-gray-50 rounded-3xl p-8 lg:p-12 max-w-3xl mx-auto">
                    <div class="flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mb-4">How It Works</h3>
                    <p class="text-gray-600 leading-relaxed text-sm lg:text-base">
                        Our lost and found system helps visitors quickly report and recover lost items. 
                        Simply report what you've lost or found, and we'll help connect you with the right person. 
                        Together, we can make Kebun Raya Bogor a more caring community.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ url('/map') }}" class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            View Map
                        </a>
                        <a href="#" class="inline-flex items-center justify-center px-6 py-3 border border-green-600 text-green-600 rounded-full hover:bg-green-50 transition-colors font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>