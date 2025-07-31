<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $roomType->type_name }} - Rooms
        </h2>
    </x-slot>

    <div class="p-6 bg-white shadow rounded-lg">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-bold">Daftar Rooms</h3>
            <div class="space-x-2">
                <button onclick="openCreateModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    + Tambah Room
                </button>
                <a href="{{ route('hotels.index') }}" class="text-black hover:underline">← Kembali</a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-3 bg-green-200 text-green-800 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full text-sm border-separate border-spacing-0 rounded-xl overflow">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Nomor Kamar</th>
                    <th class="px-4 py-3 text-left">Lantai</th>
                    <th class="px-4 py-3 text-left">Tipe Kamar</th>
                    <th class="px-4 py-3 text-left">Foto</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $room->id }}</td>
                                <td class="px-4 py-2">{{ $room->room_number }}</td>
                                <td class="px-4 py-2">{{ $room->floor ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $room->roomType->name_type ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if($room->photos && count($room->photos) > 0)
                                        <div class="flex items-center space-x-2">
                                            <img src="{{ asset('storage/' . $room->photos[0]->photo) }}" alt="Foto Kamar"
                                                class="w-16 h-16 object-cover rounded">
                                            @if(count($room->photos) > 1)
                                                <span class="text-xs text-gray-500">+{{ count($room->photos) - 1 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 text-xs rounded 
                                                                                                                                                                                                                     {{ $room->status == 'available' ? 'bg-green-200 text-green-800' :
                    ($room->status == 'booked' ? 'bg-yellow-200 text-yellow-800' :
                        ($room->status == 'occupied' ? 'bg-red-200 text-red-800' :
                            'bg-gray-200 text-gray-800')) }}">
                                        {{ ucfirst($room->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button
                                            onclick="openEditModal({{ $room->id }}, @json($room->room_number), @json($room->floor), @json($room->status), @json($room->notes))"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded">
                                            Edit
                                        </button>

                                        <form action="{{ route('rooms.destroy', $room->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            Belum ada room untuk tipe kamar ini.
                            <button onclick="openCreateModal()" class="text-blue-500 hover:underline ml-1">
                                Tambah room pertama
                            </button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="createRoomModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <form id="createRoomForm" method="POST"
                    action="{{ route('rooms.store', ['roomType' => $roomType->id]) }}" enctype="multipart/form-data"
                    class="px-6 py-4 space-y-4">
                    @csrf
                    <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

                    <h2 class="text-xl font-bold text-gray-700">Tambah Room</h2>

                    <div>
                        <label class="block text-sm font-medium">Nomor Kamar</label>
                        <input type="text" name="room_number" class="w-full border px-3 py-2 rounded" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Lantai</label>
                        <input type="number" name="floor" class="w-full border px-3 py-2 rounded">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select name="status" class="w-full border px-3 py-2 rounded" required>
                            <option value="available">Tersedia</option>
                            <option value="booked">Dipesan</option>
                            <option value="maintenance">Perawatan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Catatan</label>
                        <textarea name="notes" rows="3" class="w-full border px-3 py-2 rounded"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Foto Kamar</label>
                        <input type="file" name="photos[]" multiple class="w-full border px-3 py-2 rounded">
                    </div>

                    <div class="flex justify-end space-x-2 pt-4">
                        <button type="button" onclick="closeModal('createRoomModal')"
                            class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Room -->
    <!-- Modal -->
    <div id="roomModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg w-1/2 p-6">
            <h2 id="modalTitle" class="text-xl font-bold mb-4">Edit Room</h2>
            <form id="roomForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div> <!-- Untuk method PATCH -->

                <div class="mb-4">
                    <label for="room_number" class="block font-medium">Room Number</label>
                    <input type="text" name="room_number" id="room_number" class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label for="floor" class="block font-medium">Floor</label>
                    <input type="text" name="floor" id="floor" class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label for="status" class="block font-medium">Status</label>
                    <select name="status" id="status" class="w-full border rounded p-2">
                        <option value="available">Available</option>
                        <option value="booked">Booked</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block font-medium">Notes</label>
                    <textarea name="notes" id="notes" class="w-full border rounded p-2"></textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('roomModal')"
                        class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>




    <script>
        function openCreateModal() {
            document.getElementById('createRoomForm').reset();
            document.getElementById('createRoomModal').classList.remove('hidden');
        }

        function openEditModal(id, room_number, floor, status, notes) {
            document.getElementById('modalTitle').textContent = 'Edit Room';

            document.getElementById('room_number').value = room_number;
            document.getElementById('floor').value = floor;
            document.getElementById('status').value = status;
            document.getElementById('notes').value = notes;

            const form = document.getElementById('roomForm');
            form.action = `/rooms/${id}`;

            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('roomModal').classList.remove('hidden');
        }


        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>

</x-app-layout>