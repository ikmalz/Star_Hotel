<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $rooms->first()->roomType->name_type ?? '-' }} - Rooms
        </h2>
    </x-slot>


    <div class="p-6 bg-white shadow rounded-lg">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-bold">Daftar Rooms</h3>
            <div class="space-x-2">
                <button onclick="openCreateModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    + Tambah Room
                </button>
                <a href="{{ route('room-types.index', $hotel->id) }}" class="text-black hover:underline">
                    ← Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="p-3 bg-green-200 text-green-800 rounded mb-3">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="text-sm list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        <table class="w-full text-sm border-separate border-spacing-0 rounded-xl overflow">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Nomor Kamar</th>
                    <th class="px-4 py-3 text-left">Lantai</th>
                    <th class="px-4 py-3 text-left">Foto</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Catatan</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $room->id }}</td>
                    <td class="px-4 py-2">{{ $room->room_number }}</td>
                    <td class="px-4 py-2">{{ $room->floor ?? '-' }}</td>
                    <td class="px-4 py-2 border-b border-gray-100">
                        @if($room->photos && count($room->photos) > 0)
                        <div class="flex items-center space-x-2 cursor-pointer"
                            onclick='openPhotoModal(@json($room->photos->pluck("photo")))'>
                            <img src="{{ asset('storage/' . $room->photos[0]->photo) }}"
                                alt="Foto Kamar"
                                class="w-16 h-16 object-cover rounded border border-gray-200">
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
                    ($room->status == 'booked' ? 'bg-yellow-200 text-yellow-800' :
                        ($room->status == 'occupied' ? 'bg-red-200 text-red-800' :
                            'bg-gray-200 text-gray-800')) }}">
                            {{ ucfirst($room->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $room->notes }}</td>
                    <td class="px-4 py-2 text-center">
                        <div class="flex justify-center space-x-2">
                            <button
                                onclick='openEditModal({{ $room->id }}, @json($room->room_number), @json($room->floor), @json($room->status), @json($room->notes), @json($room->photos->map(function($p){ return ["id" => $p->id, "photo" => $p->photo]; })->values()) )'
                                class="bg-gray-500 text-white px-3 py-1 rounded">
                                Edit
                            </button>

                            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-gray-500 text-white px-3 py-1 rounded">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                        Belum ada room untuk tipe kamar ini.
                        <button onclick="openCreateModal()" class="text-gray-600 hover:underline ml-1">
                            Tambah room pertama
                        </button>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="photoModal"
        class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden z-50 flex items-center justify-center transition-opacity duration-300">
        <div class="max-w-5xl w-full mx-4 bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-6 transform scale-95 transition-transform duration-300"
            id="photoModalContent">
            <div class="flex justify-end mb-4">
                <button onclick="closePhotoModal()"
                    class="text-white bg-white/20 hover:bg-white/40 rounded-full w-10 h-10 flex items-center justify-center transition">
                    ✕
                </button>
            </div>
            <div id="photoGallery" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"></div>
        </div>
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
                        <label class="block text-sm font-medium text-gray-700">Nomor Kamar</label>
                        <input type="text" name="room_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Lantai</label>
                        <input type="number" name="floor" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="available">Tersedia</option>
                            <option value="booked">Dipesan</option>
                            <option value="occupied">Dihuni</option>
                            <option value="maintenance">Perawatan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto Kamar</label>
                        <input type="file" name="photos[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="flex justify-end space-x-2 pt-4">
                        <button type="button" onclick="closeModal('createRoomModal')"
                            class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="roomModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg w-1/2 p-6 max-h-[90vh] overflow-y-auto">
            <h2 id="modalTitle" class="text-xl font-bold mb-4">Edit Room</h2>
            <form id="roomForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nomor Kamar</label>
                    <input type="text" name="room_number" id="room_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Lantai</label>
                    <input type="text" name="floor" id="floor" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="available">Tersedia</option>
                        <option value="booked">Dipesan</option>
                        <option value="occupied">Dihuni</option>
                        <option value="maintenance">Perawatan</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="notes" id="notes" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Foto Lama</label>
                    <div id="oldPhotos" class="flex flex-wrap gap-2"></div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Ganti Foto (Opsional)</label>
                    <input type="file" name="photos[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('roomModal')"
                        class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('createRoomForm').reset();
            document.getElementById('createRoomModal').classList.remove('hidden');
        }

        function openPhotoModal(photos) {
            const modal = document.getElementById('photoModal');
            const modalContent = document.getElementById('photoModalContent');
            const gallery = document.getElementById('photoGallery');
            gallery.innerHTML = '';

            photos.forEach(photo => {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group overflow-hidden rounded-xl shadow-lg cursor-pointer transition';

                const img = document.createElement('img');
                img.src = `/storage/${photo}`;
                img.className = 'w-full h-40 object-cover transform group-hover:scale-110 transition duration-500';

                const overlay = document.createElement('div');
                overlay.className = 'absolute inset-0 bg-black/0 group-hover:bg-black/30 transition';

                wrapper.appendChild(img);
                wrapper.appendChild(overlay);
                gallery.appendChild(wrapper);
            });

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closePhotoModal() {
            const modal = document.getElementById('photoModal');
            const modalContent = document.getElementById('photoModalContent');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('opacity-0');
            }, 200);
        }



        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
        }

        function openEditModal(id, room_number, floor, status, notes, photos) {
            document.getElementById('room_number').value = room_number;
            document.getElementById('floor').value = floor ?? '';
            document.getElementById('status').value = status;
            document.getElementById('notes').value = notes ?? '';

            const form = document.getElementById('roomForm');
            form.action = `/rooms/${id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            const oldPhotosContainer = document.getElementById('oldPhotos');
            oldPhotosContainer.innerHTML = '';

            photos.forEach(photo => {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative w-16 h-16';

                const img = document.createElement('img');
                img.src = `/storage/${photo.photo}`;
                img.className = 'w-16 h-16 object-cover rounded';

                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = '×';
                deleteBtn.type = 'button';
                deleteBtn.className = 'absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-full';
                deleteBtn.onclick = () => {
                    if (confirm('Hapus foto ini?')) {
                        fetch(`/room-photos/delete/${photo.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }).then(() => wrapper.remove());
                    }
                };

                wrapper.appendChild(img);
                wrapper.appendChild(deleteBtn);
                oldPhotosContainer.appendChild(wrapper);
            });

            document.getElementById('roomModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>

</x-app-layout>