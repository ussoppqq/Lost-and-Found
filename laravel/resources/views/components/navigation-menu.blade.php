<nav x-data="{ scrolled: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
     :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-md' : 'bg-transparent'"
     class="fixed w-full top-0 left-0 z-50 transition-colors duration-300">
    <div class="container mx-auto flex items-center justify-between px-6 py-4">
        <a href="/" class="text-lg font-bold text-white" :class="scrolled ? 'text-gray-800' : 'text-white'">
            KEBUN RAYA BOGOR
        </a>
        <ul class="flex gap-8 font-medium">
            <li><a href="/maps" class="hover:text-green-500" :class="scrolled ? 'text-gray-800' : 'text-white'">Map</a></li>
            <li><a href="/lost-form" class="hover:text-green-500" :class="scrolled ? 'text-gray-800' : 'text-white'">Lost & Found</a></li>
            <li><a href="/login" class="hover:text-green-500" :class="scrolled ? 'text-gray-800' : 'text-white'">Login</a></li>
        </ul>
    </div>
</nav>
<style>
    nav {
        backdrop-filter: blur(10px);
    }
</style>
