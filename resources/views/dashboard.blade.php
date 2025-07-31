<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0v6h6"></path>
            </svg>
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto mb-10">
            <!-- Card Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @php
                $cards = [
                ['title' => 'Total User', 'value' => '100', 'color' => 'from-indigo-500 to-purple-500'],
                ['title' => 'Total Booking', 'value' => '100', 'color' => 'from-green-500 to-emerald-500'],
                ['title' => 'Kamar Tersedia', 'value' => '100', 'color' => 'from-blue-500 to-cyan-500'],
                ['title' => 'Pendapatan Bulan Ini', 'value' => 'Rp 100.000', 'color' => 'from-pink-500 to-rose-500'],
                ];
                @endphp

                @foreach($cards as $card)
                <div
                    class="bg-white rounded-xl shadow-md hover:shadow-lg p-6 transform hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm">{{ $card['title'] }}</div>
                            <div class="text-2xl font-bold text-gray-800 mt-2">{{ $card['value'] }}</div>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br {{ $card['color'] }} flex items-center justify-center text-white shadow">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.1 0-2 .9-2 2v8a2 2 0 004 0v-8c0-1.1-.9-2-2-2z" />
                            </svg>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Booking Terbaru -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="font-bold text-xl text-gray-800 pb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7h18M3 12h18M3 17h18" />
                    </svg>
                    Booking Terbaru
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase">
                                <th class="p-3 text-left">Kode</th>
                                <th class="p-3 text-left">User</th>
                                <th class="p-3 text-left">Hotel</th>
                                <th class="p-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 text-gray-800 font-medium">101</td>
                                <td class="p-3 text-gray-700">Zack</td>
                                <td class="p-3 text-gray-700">StarHotels</td>
                                <td class="p-3">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                        Tersedia
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-400 italic">
                                    Tidak ada data
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>