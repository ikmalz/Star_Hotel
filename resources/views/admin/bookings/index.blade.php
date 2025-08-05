<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Booking
        </h2>
    </x-slot>

    <div class="p-6">
        @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">User</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Room</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Room Type</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Payment</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $booking->user->name ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $booking->room->room_number ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800">{{ $booking->roomType->name_type ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm">
                                <span class="
                                    px-2 py-1 rounded text-xs font-semibold
                                    @switch($booking->status_booking)
                                        @case('pending') bg-yellow-100 text-yellow-700 @break
                                        @case('paid') bg-green-100 text-green-700 @break
                                        @case('checked_in') bg-blue-100 text-blue-700 @break
                                        @case('checked_out') bg-gray-200 text-gray-700 @break
                                        @case('canceled') bg-red-100 text-red-700 @break
                                        @default bg-gray-100 text-gray-700
                                    @endswitch
                                ">
                                    {{ ucfirst(str_replace('_',' ',$booking->status_booking)) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800">
                                @if ($booking->payment)
                                    <a href="{{ route('payments.show', $booking->payment->id) }}"
                                        class="ml-2 px-3 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 transition">
                                        Payment
                                    </a>
                                @endif

                            </td>
                            <td class="px-4 py-3 text-center">
                                @if (!in_array($booking->status_booking, ['checked_out','canceled']))
                                    <button onclick="openModal('{{ $booking->id }}')"
                                        class="px-3 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 transition">
                                        Update
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400 italic">Final state</span>
                                @endif
                            </td>
                        </tr>

                        <div id="modal-{{ $booking->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                                <h3 class="text-lg font-semibold mb-4">Update Booking - {{ $booking->code_booking }}</h3>

                                <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PATCH')

                                    @if ($booking->status_booking === 'paid')
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Pilih Room</label>
                                            <select name="room_id" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-200">
                                                <option value="">-- Pilih Room --</option>
                                                @foreach ($booking->roomType->rooms()->where('status','available')->get() as $room)
                                                    <option value="{{ $room->id }}" {{ $booking->room_id == $room->id ? 'selected' : '' }}>
                                                        Room {{ $room->room_number }} (Lantai {{ $room->floor }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div>
                                        <label class="block text-sm font-medium mb-1">Status Booking</label>
                                        <select name="status_booking" class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-200">
                                            @if ($booking->status_booking === 'pending')
                                                <option value="paid">Paid</option>
                                                <option value="canceled">Canceled</option>
                                            @elseif ($booking->status_booking === 'paid')
                                                <option value="checked_in">Checked In</option>
                                                <option value="canceled">Canceled</option>
                                            @elseif ($booking->status_booking === 'checked_in')
                                                <option value="checked_out">Checked Out</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="flex justify-end gap-2">
                                        <button type="button" onclick="closeModal('{{ $booking->id }}')"
                                            class="px-3 py-1 text-gray-600 border rounded hover:bg-gray-100">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500 text-sm">
                                Tidak ada data booking.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById('modal-' + id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById('modal-' + id).classList.add('hidden');
        }
    </script>
</x-app-layout>
