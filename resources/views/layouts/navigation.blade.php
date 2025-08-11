<nav x-data="{ 
        openAuth: {{ request()->routeIs('users.*') ? 'true' : 'false' }}, 
        openMaster: {{ (request()->routeIs('hotels.*') || request()->routeIs('room_facilities.*') || request()->routeIs('provinces.*') || request()->routeIs('cities.*') || request()->routeIs('floors.*')) ? 'true' : 'false' }},
        openTransaction: {{ request()->routeIs('bookings.*') ? 'true' : 'false' }},
        openFeedback: {{ request()->routeIs('ratings.*') ? 'true' : 'false' }}
    }" class="bg-white w-64 h-screen shadow-lg fixed flex flex-col">

    <div class="h-32 flex items-center justify-center border-b">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </a>
    </div>

    <div class="mt-4 flex-1 px-3">
        {{-- Dashboard --}}
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>

        {{-- Auth --}}
        <div class="mt-3">
            <button @click="openAuth = !openAuth"
                class="w-full flex items-center justify-between px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out focus:outline-none">

                <span class="flex items-center space-x-2">
                    <i class='bx bx-lock text-lg text-gray-500'></i>
                    <span>Auth</span>
                </span>

                <i class='bx bx-chevron-down text-lg transform transition-transform duration-200'
                    :class="{ 'rotate-180': openAuth }"></i>
            </button>

            <div x-show="openAuth" x-transition class="ml-5 mt-2 space-y-1">
                <a href="{{ route('users.index') }}"
                    class="flex items-center space-x-2 px-5 py-2 rounded-md transition duration-150 ease-in-out
                {{ request()->routeIs('users.*') ? 'bg-gray-200 font-semibold text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">

                    <i class='bx bx-group text-lg text-gray-500'></i>
                    <span>Users</span>
                </a>
            </div>
        </div>

        {{-- Master Data --}}
        <div class="mt-3">
            <button @click="openMaster = !openMaster"
                class="w-full flex items-center justify-between px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out focus:outline-none">

                <span class="flex items-center space-x-2">
                    <i class='bx bx-data text-lg text-gray-500'></i>
                    <span>Master Data</span>
                </span>

                <i class='bx bx-chevron-down text-lg transform transition-transform duration-200'
                    :class="{ 'rotate-180': openMaster }"></i>
            </button>

            <div x-show="openMaster" x-transition class="ml-5 mt-2 space-y-1">

                <a href="{{ route('hotels.index') }}"
                    class="flex items-center space-x-2 px-5 py-2 rounded-md transition duration-150 ease-in-out
                {{ request()->routeIs('hotels.*') ? 'bg-gray-200 font-semibold text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class='bx bxs-hotel text-lg text-gray-500'></i>
                    <span>Hotels</span>
                </a>

                <a href="{{ route('room_facilities.index') }}"
                    class="flex items-center space-x-2 px-5 py-2 rounded-md transition duration-150 ease-in-out
                {{ request()->routeIs('room_facilities.*') ? 'bg-gray-200 font-semibold text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class='bx bx-wrench text-lg text-gray-500'></i>
                    <span>Room Facilities</span>
                </a>

                <a href="{{ route('provinces.index') }}"
                    class="flex items-center space-x-2 px-5 py-2 rounded-md transition duration-150 ease-in-out
                {{ request()->routeIs('provinces*') ? 'bg-gray-200 font-semibold text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class='bx bx-map-alt text-lg text-gray-500'></i>
                    <span>Provinces</span>
                </a>

                <a href="{{ route('cities.index') }}"
                    class="flex items-center space-x-2 px-5 py-2 rounded-md transition duration-150 ease-in-out
                {{ request()->routeIs('cities.*') ? 'bg-gray-200 font-semibold text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class='bx bx-buildings text-lg text-gray-500'></i>
                    <span>Cities</span>
                </a>

                <a href="{{ route('floors.all') }}"
                    class="flex items-center space-x-2 px-5 py-2 rounded-md transition duration-150 ease-in-out
                {{ request()->routeIs('floors.*') ? 'bg-gray-200 font-semibold text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class='bx bx-grid-alt text-lg text-gray-500'></i>
                    <span>Floors</span>
                </a>

            </div>
        </div>

        {{-- Transaction Data --}}
        <div class="mt-3">
            <button @click="openTransaction = !openTransaction"
                class="w-full flex items-center justify-between px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out focus:outline-none">

                <span class="flex items-center space-x-2">
                    <i class='bx bx-transfer text-lg text-gray-500'></i>
                    <span>Transaction Data</span>
                </span>

                <i class='bx bx-chevron-down text-lg transform transition-transform duration-200'
                    :class="{ 'rotate-180': openTransaction }"></i>
            </button>

            <div x-show="openTransaction" x-transition class="ml-5 mt-2 space-y-1">
                <a href="{{ route('bookings.index') }}"
                    class="flex items-center space-x-2 px-5 py-2 rounded-md transition duration-150 ease-in-out
                {{ request()->routeIs('bookings.*') ? 'bg-gray-200 font-semibold text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class='bx bx-calendar-check text-lg text-gray-500'></i>
                    <span>Bookings</span>
                </a>
            </div>
        </div>

        {{-- Feedback --}}
        <div class="mt-3">
            <button @click="openFeedback = !openFeedback"
                class="w-full flex items-center justify-between px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out focus:outline-none">

                <span class="flex items-center space-x-2">
                    <i class='bx bx-message-dots text-lg text-gray-500'></i>
                    <span>Feedback</span>
                </span>

                <i class='bx bx-chevron-down text-lg transform transition-transform duration-200'
                    :class="{ 'rotate-180': openFeedback }"></i>
            </button>

            <div x-show="openFeedback" x-transition class="ml-5 mt-2 space-y-1">

                <a href="{{ route('ratings.index') }}"
                    class="flex items-center space-x-2 px-5 py-2 rounded-md transition duration-150 ease-in-out
                {{ request()->routeIs('ratings.*') ? 'bg-gray-200 font-semibold text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class='bx bxs-star text-lg text-gray-500'></i>
                    <span>Ratings</span>
                </a>

                <a href="{{ route('admin.comments.index') }}"
                    class="flex items-center space-x-2 px-5 py-2 rounded-md transition duration-150 ease-in-out
                {{ request()->routeIs('comments.*') ? 'bg-gray-200 font-semibold text-gray-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class='bx bx-comment-detail text-lg text-gray-500'></i>
                    <span>Comments</span>
                </a>

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