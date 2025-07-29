<nav x-data="{ open: false }" class="bg-white w-64 h-screen shadow-lg fixed flex flex-col">
    <!-- Logo -->
    <div class="h-16 flex items-center justify-center border-b">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="mt-4 flex-1 px-3">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>
    
        <!-- Master Data -->    
        <div class="mt-3">
            <button 
                @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out focus:outline-none">
                <span>Master Data</span>
                <svg class="w-4 h-4 transform transition-transform duration-200" 
                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" x-transition class="ml-5 mt-2 space-y-1">
                <a href="{{ route('hotels.index') }}"
                    class="block px-5 py-2 rounded-md text-gray-600 hover:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('hotels.index') ? 'bg-gray-200 font-semibold' : '' }}">
                    Hotels
                </a>
                <a href="{{ route('users.index') }}"
                    class="block px-5 py-2 rounded-md text-gray-600 hover:bg-gray-100 transition duration-150 ease-in-out {{ request()->routeIs('users.index') ? 'bg-gray-200 font-semibold' : '' }}">
                    Users
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom User Menu -->
    <div class="border-t p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</nav>
