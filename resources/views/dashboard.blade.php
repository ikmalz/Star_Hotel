<x-app-layout class="bg-gray-50 min-h-screen overflow-x-hidden">
    <div x-data="dashboard()" class="relative">

        <!-- Header -->
        <header class="px-6 py-5 bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
                <button class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V5a2 2 0 10-4 0v.3A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Stats Cards -->
        <section class="px-6 py-6">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <template x-for="(card, index) in cards" :key="index">
                    <div class="bg-white rounded-lg p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow"
                        x-data="{ count: 0 }" x-init="animateCount($el, card.value)">
                        <div class="flex justify-between items-center mb-4">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white" :class="card.iconBg" x-html="card.icon"></div>
                            <span class="text-xs font-medium text-gray-500 uppercase" x-text="card.label"></span>
                        </div>
                        <div class="text-2xl font-semibold text-gray-900" x-text="card.format ? formatNumber(count) : count"></div>
                    </div>
                </template>
            </div>
        </section>

        <!-- Bookings Table -->
        <section class="px-6 pb-10">
            <div class="max-w-7xl mx-auto bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Bookings</h2>
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">Add New</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 font-medium">Code</th>
                                <th class="px-4 py-3 font-medium">User</th>
                                <th class="px-4 py-3 font-medium">Hotel</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(booking, index) in bookings" :key="index">
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900" x-text="'#' + booking.code"></td>
                                    <td class="px-4 py-3" x-text="booking.user"></td>
                                    <td class="px-4 py-3" x-text="booking.hotel"></td>
                                    <td class="px-4 py-3">
                                        <span class="px-2.5 py-1 text-xs rounded-full"
                                            :class="{
                                                'bg-green-100 text-green-700': booking.status === 'Active',
                                                'bg-yellow-100 text-yellow-700': booking.status === 'Pending',
                                                'bg-gray-100 text-gray-700': booking.status === 'Completed'
                                            }"
                                            x-text="booking.status">
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <button class="text-indigo-600 hover:underline">View</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <script>
        function dashboard() {
            return {
                cards: [
                    { label: 'Total Users', value: 1250, icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4a4 4 0 110 8M15 21H3v-1a6 6 0 0112 0v1h6v-1a6 6 0 00-9-5"/></svg>', iconBg: 'bg-indigo-500' },
                    { label: 'Total Bookings', value: 892, icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6a2 2 0 012 2v10a2 2 0 01-2 2H9V5z"/></svg>', iconBg: 'bg-green-500' },
                    { label: 'Available Rooms', value: 47, icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 11h16M4 15h16"/></svg>', iconBg: 'bg-blue-500' },
                    { label: 'Monthly Revenue', value: 125000, format: true, icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.6 0-3 .9-3 2s1.4 2 3 2 3 .9 3 2-1.4 2-3 2m0-8v8m0 0v2"/></svg>', iconBg: 'bg-purple-500' }
                ],
                bookings: [
                    { code: '001', user: 'John Doe', hotel: 'Grand Palace', status: 'Active' },
                    { code: '002', user: 'Sarah Wilson', hotel: 'Ocean View', status: 'Pending' },
                    { code: '003', user: 'Mike Johnson', hotel: 'Mountain Resort', status: 'Active' },
                    { code: '004', user: 'Emma Davis', hotel: 'City Center', status: 'Completed' },
                    { code: '005', user: 'Alex Brown', hotel: 'Seaside Villa', status: 'Active' }
                ],
                animateCount(el, target) {
                    let count = 0, step = target / 50;
                    const interval = setInterval(() => {
                        count = Math.min(count + step, target);
                        el.__x.$data.count = Math.floor(count);
                        if (count >= target) clearInterval(interval);
                    }, 20);
                },
                formatNumber(num) {
                    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', minimumFractionDigits: 0 }).format(num);
                }
            }
        }
    </script>
</x-app-layout>
