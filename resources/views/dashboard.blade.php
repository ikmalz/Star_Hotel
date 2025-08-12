<x-app-layout class="bg-gray-50 min-h-screen overflow-x-hidden">
    <div>

        <!-- Header -->
        <header class="bg-gradient-to-r from-sky-50 via-white to-sky-50 shadow-sm rounded-xl">
            <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
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
