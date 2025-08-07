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
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Room Type</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Province</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">City</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Floor</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Room</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Payment</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2 text-sm text-gray-800">{{ $booking->user->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800">{{ $booking->roomType->name_type ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800">{{ $booking->city->province->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800">{{ $booking->city->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800">{{ $booking->floor->floor_number ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800">{{ $booking->room->room_number ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm">
                            <x-booking-status :status="$booking->status_booking" />
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-800">
                            @forelse ($booking->payments as $payment)
                            <div class="mb-1">
                                <span class="block">Rp {{ number_format($payment->amount,0,',','.') }}</span>
                                <span class="block text-xs text-gray-500">{{ ucfirst($payment->payment_status) }}</span>

                                @if ($payment->payment_status === 'refunded')
                                <span class="block text-red-600 text-xs">
                                    Refunded: Rp {{ number_format($payment->refund_amount ?? 0,0,',','.') }}
                                </span>
                                @endif

                                <a href="{{ route('payments.show', $payment->id) }}"
                                    class="mt-1 inline-block px-3 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600 transition">
                                    Detail
                                </a>
                            </div>
                            @empty
                            <span class="text-gray-400 text-xs italic">Belum ada pembayaran</span>
                            @endforelse
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

                            @if ($booking->payments->contains('payment_status', 'refunded'))
                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">Refunded</span>
                            @endif
                        </td>
                    </tr>

                    <div id="modal-{{ $booking->id }}"
                        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm">
                        <div class="bg-gray-50 rounded-xl shadow-xl w-full max-w-lg p-6 relative">

                            <div class="flex justify-between items-center border-b border-gray-200 pb-3 mb-4">
                                <h3 class="text-lg font-semibold text-gray-700">
                                    Update Booking <span class="text-gray-500 text-sm">#{{ $booking->code_booking }}</span>
                                </h3>
                                <button type="button" onclick="closeModal('{{ $booking->id }}')"
                                    class="text-gray-400 hover:text-gray-600 transition text-xl leading-none">&times;</button>
                            </div>

                            <div class="text-sm text-gray-600 space-y-1 mb-4">
                                @php
                                $firstPayment = $booking->payments->first();
                                @endphp
                                @if($firstPayment)
                                <p><strong>Amount:</strong> Rp {{ number_format($firstPayment->amount,0,',','.') }}</p>
                                <p><strong>Status Payment:</strong> {{ ucfirst($firstPayment->payment_status) }}</p>
                                @if ($firstPayment->payment_status === 'refunded')
                                <p><strong>Refund:</strong> Rp {{ number_format($firstPayment->refund_amount ?? 0,0,',','.') }}</p>
                                @endif
                                @endif
                                <p><strong>User:</strong> {{ $booking->user->name ?? '-' }}</p>
                                <p><strong>Room Type:</strong> {{ $booking->roomType->name_type ?? '-' }}</p>
                                <p><strong>Check-in:</strong> {{ $booking->checkin_at->format('d M Y H:i') }}</p>
                                <p><strong>Check-out:</strong> {{ $booking->checkout_at->format('d M Y H:i') }}</p>
                                <p><strong>Total:</strong> Rp {{ number_format($booking->price_total,0,',','.') }}</p>
                            </div>

                            @if ($booking->payments->isEmpty() && $booking->status_booking === 'pending')
                            <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg text-sm mb-4">
                                User belum melakukan pembayaran, Anda tidak bisa mengupdate booking ini.
                            </div>

                            <div class="flex justify-end">
                                <button type="button" onclick="closeModal('{{ $booking->id }}')"
                                    class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                                    Tutup
                                </button>
                            </div>
                            @else
                            <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="space-y-5">
                                @csrf
                                @method('PATCH')

                                @if ($booking->status_booking === 'paid')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Room</label>
                                    <select name="room_id" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-gray-300 focus:outline-none">
                                        <option value="">-- Pilih Room --</option>
                                        @foreach ($booking->roomType->rooms()->where('status','available')->get() as $room)
                                        <option value="{{ $room->id }}">
                                            Room {{ $room->room_number }} (Lantai {{ $room->floor->name ?? '-' }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <div class="flex justify-end gap-3 pt-3 border-t border-gray-200">
                                    <button type="button" onclick="closeModal('{{ $booking->id }}')"
                                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                                        Batal
                                    </button>

                                    @if ($booking->status_booking === 'pending')
                                    <button name="status_booking" value="paid"
                                        class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition">
                                        Approve Payment
                                    </button>
                                    <button name="status_booking" value="canceled"
                                        class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                                        Cancel
                                    </button>

                                    @elseif ($booking->status_booking === 'paid')
                                    <button name="status_booking" value="checked_in"
                                        class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition">
                                        Check In
                                    </button>
                                    <button name="status_booking" value="canceled"
                                        class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                                        Cancel
                                    </button>

                                    @elseif ($booking->status_booking === 'checkout_pending')
                                    <button name="status_booking" value="checked_out"
                                        class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition">
                                        Approve Checkout
                                    </button>
                                    <button name="status_booking" value="canceled"
                                        class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                                        Reject Checkout
                                    </button>
                                    @endif
                                </div>
                            </form>
                            @endif
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