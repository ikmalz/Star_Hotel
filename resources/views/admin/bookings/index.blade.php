<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Booking
        </h2>
    </x-slot>

    <div class="p-6 bg-white shadow sm:rounded-lg">
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <table class="min-w-full table-auto border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2 border">User</th>
                    <th class="px-4 py-2 border">Room</th>
                    <th class="px-4 py-2 border">Room Type</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td class="px-4 py-2 border">{{ $booking->user->name ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $booking->room->room_number ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $booking->roomType->name_type ?? '-' }}</td>
                        <td class="px-4 py-2 border capitalize">{{ $booking->status_booking }}</td>
                        <td class="px-4 py-2 border">
                            <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status_booking" class="border rounded p-1">
                                    <option value="pending" {{ $booking->status_booking == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $booking->status_booking == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                                <button type="submit" class="ml-2 px-2 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                            Tidak ada data booking.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
