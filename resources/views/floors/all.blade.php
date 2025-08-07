<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-700 leading-tight">All Floors</h2>
    </x-slot>

    <div class="p-6 bg-gray-50 min-h-screen">
        @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
        @endif

        <div class="mb-4 p-4 bg-blue-50 text-gray-700 border border-blue-200 rounded shadow-sm">
            To create a new floor, please go through a specific <strong>Room Type</strong> page. Contact the admin if unsure.
        </div>

        <div class="overflow-x-auto bg-white rounded shadow-sm border">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-200 text-left text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 border-b">Floor Number</th>
                        <th class="px-4 py-3 border-b">Room Type</th>
                        <th class="px-4 py-3 border-b">Hotel</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($floors as $floor)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 border-b">{{ $floor->floor_number }}</td>
                        <td class="px-4 py-2 border-b">{{ $floor->roomType->name_type ?? '-' }}</td>
                        <td class="px-4 py-2 border-b">{{ $floor->roomType->hotel->name_hotel ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center px-4 py-6 text-gray-500">
                            No floor data found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>