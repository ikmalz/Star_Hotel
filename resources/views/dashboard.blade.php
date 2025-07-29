<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-10">

            <!-- Card Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="text-gray-400 text-sm">Total User</div>
                    <div class="text-2xl font-bold text-gray-800 mt-2">100</div>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="text-gray-400 text-sm">Total Booking</div>
                    <div class="text-2xl font-bold text-gray-800 mt-2">100</div>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="text-gray-400 text-sm">Kamar Tersedia</div>
                    <div class="text-2xl font-bold text-gray-800 mt-2">100</div>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="text-gray-400 text-sm">Pendapatan Bulan Ini</div>
                    <div class="text-2xl font-bold text-gray-500 mt-2">Rp 100.000</div>
                </div>
            </div>

            <!-- Booking Terbaru -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="font-bold text-xl pb-4">Booking Terbaru</h2>
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-2">Kode</th>
                            <th class="p-2">User</th>
                            <th class="p-2">Hotel</th>
                            <th class="p-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="p-2">101</td>
                            <td class="p-2">Zack</td>
                            <td class="p-2">StarHotels</td>
                            <td class="p-2">
                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">
                                    tersedia
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="p-2 text-center text-gray-500">Tidak ada data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>