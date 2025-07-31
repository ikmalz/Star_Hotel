<nav
    x-data="{ 
        openAuth: {{ request()->routeIs('users.*') ? 'true' : 'false' }}, 
        openMaster: {{ request()->routeIs('hotels.*') ? 'true' : 'false' }} 
    }"
    class="bg-white w-64 h-screen shadow-lg fixed flex flex-col">

    <div class="h-16 flex items-center justify-center border-b">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    <div class="mt-4 flex-1 px-3">
        {{-- Dashboard --}}
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>

        <div class="mt-3">
            <button
                @click="openAuth = !openAuth"
                class="w-full flex items-center justify-between px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out focus:outline-none">
                <span>Auth</span>
                <svg class="w-4 h-4 transform transition-transform duration-200"
                    :class="{ 'rotate-180': openAuth }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openAuth" x-transition class="ml-5 mt-2 space-y-1">
                <a href="{{ route('users.index') }}"
                    class="block px-5 py-2 rounded-md transition duration-150 ease-in-out
                        {{ request()->routeIs('users.*') ? 'bg-gray-200 font-semibold text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    Users
                </a>
            </div>
        </div>

        <div class="mt-3">
            <button
                @click="openMaster = !openMaster"
                class="w-full flex items-center justify-between px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out focus:outline-none">
                <span>Master Data</span>
                <svg class="w-4 h-4 transform transition-transform duration-200"
                    :class="{ 'rotate-180': openMaster }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openMaster" x-transition class="ml-5 mt-2 space-y-1">
                <a href="{{ route('hotels.index') }}"
                    class="block px-5 py-2 rounded-md transition duration-150 ease-in-out
                        {{ request()->routeIs('hotels.*') ? 'bg-gray-200 font-semibold text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    Hotels
                </a>
            </div>
        </div>
    </div>

    <div class="border-t p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</nav>