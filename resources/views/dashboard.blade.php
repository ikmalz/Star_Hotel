<x-app-layout class="bg-gray-50 min-h-screen overflow-x-hidden">
    <div>

        <!-- Header -->
        <header class="relative overflow-hidden bg-gradient-to-r from-sky-50 via-white to-sky-50 shadow-sm"
            x-data="{ fly: false }"
            x-init="setInterval(() => { fly = false; $nextTick(() => fly = true) }, 7000)">

            <!-- Container -->
            <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center relative z-10">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
                <button class="p-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition-colors shadow-sm">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V5a2 2 0 10-4 0v.3A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                    </svg>
                </button>
            </div>

            <!-- Pesawat Futuristik -->
            <div x-show="fly" x-transition.opacity.duration.3000ms
                class="absolute top-8 -left-32 z-0 animate-flyAcross">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"
                    class="w-16 h-16 drop-shadow-[0_0_8px_rgba(59,130,246,0.7)] text-sky-500">
                    <path fill="currentColor"
                        d="M480 192l128-64-128-64-128 64 128 64zm-160-64l-128 64 128 64 128-64-128-64zm-160 64L32 128v64l128 64v-64zm128 64l-128 64v64l128-64v-64zm160 0v64l128 64v-64l-128-64zm-160 64v64l128 64v-64l-128-64z" />
                    <!-- Afterburner -->
                    <circle cx="40" cy="256" r="8" fill="url(#flame)" />
                    <defs>
                        <radialGradient id="flame" cx="50%" cy="50%" r="50%">
                            <stop offset="0%" stop-color="#fbbf24" />
                            <stop offset="100%" stop-color="#ef4444" stop-opacity="0" />
                        </radialGradient>
                    </defs>
                </svg>
            </div>
        </header>   

        <!-- Stats Cards -->
        <section class="py-6">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white rounded-lg p-5 border shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white bg-indigo-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4a4 4 0 110 8M15 21H3v-1a6 6 0 0112 0v1h6v-1a6 6 0 00-9-5"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-500 uppercase">Total Users</span>
                    </div>
                    <div class="text-2xl font-semibold text-gray-900">{{ $totalUsers }}</div>
                </div>

                <div class="bg-white rounded-lg p-5 border shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white bg-green-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5h6a2 2 0 012 2v10a2 2 0 01-2 2H9V5z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-500 uppercase">Total Bookings</span>
                    </div>
                    <div class="text-2xl font-semibold text-gray-900">{{ $totalBookings }}</div>
                </div>

                <div class="bg-white rounded-lg p-5 border shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white bg-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7h16M4 11h16M4 15h16"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-500 uppercase">Available Rooms</span>
                    </div>
                    <div class="text-2xl font-semibold text-gray-900">{{ $availableRooms }}</div>
                </div>
            </div>
        </section>

        <!-- Bookings Table -->
        <section class="pb-10">
            <div class="max-w-7xl mx-auto bg-white rounded-lg p-6 border shadow-sm">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Bookings</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 font-medium">Code</th>
                                <th class="px-4 py-3 font-medium">User</th>
                                <th class="px-4 py-3 font-medium">Hotel</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($recentBookings as $booking)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900">#{{ $booking->code }}</td>
                                    <td class="px-4 py-3">{{ $booking->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $booking->hotel->name ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2.5 py-1 text-xs rounded-full
                                            @if($booking->status === 'Active') bg-green-100 text-green-700
                                            @elseif($booking->status === 'Pending') bg-yellow-100 text-yellow-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($recentBookings->isEmpty())
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                        No recent bookings found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
