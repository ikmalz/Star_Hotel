<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
            Data Rating Hotel
        </h2>
    </x-slot>

    <div class="p-6">
        @if($ratings->isEmpty())
            <div class="text-gray-500 text-center py-10">
                <p class="text-lg">Belum ada rating dari user.</p>
            </div>
        @else
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Hotel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Room Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($ratings as $index => $rating)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $rating->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $rating->hotel->name_hotel }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $rating->roomType->name_type ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-yellow-500 font-bold">{{ $rating->value }} ⭐</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $rating->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
