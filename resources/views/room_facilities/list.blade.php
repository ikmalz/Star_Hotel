<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Room Facilities
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6">
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-700">Daftar Fasilitas Kamar</h1>
            <button onclick="openCreateModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow">
                + Tambah Fasilitas
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100 border-b">
                    <tr class="text-left text-gray-700">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Tipe Kamar</th>
                        <th class="px-4 py-3">Fasilitas</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facilities as $facility)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3">{{ $facility->id }}</td>
                        <td class="px-4 py-3">{{ $facility->roomType->name_type }}</td>
                        <td class="px-4 py-3">{{ $facility->facility_name }}</td>
                        <td class="px-4 py-3 flex justify-center gap-2">
                            <button
                                onclick="openEditModal({{ $facility->id }}, '{{ $facility->roomType->id }}', '{{ $facility->facility_name }}')"
                                class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded shadow text-sm">
                                Edit
                            </button>
                            <form action="{{ route('room_facilities.destroy', $facility->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded shadow text-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">Belum ada data fasilitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $facilities->links() }}
        </div>
    </div>

    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <h2 class="text-xl font-bold mb-4">Tambah Fasilitas</h2>

            @php
            $roomTypes = \App\Models\RoomType::all();
            @endphp

            @if($roomTypes->count() === 0)
            <div class="p-4 bg-yellow-100 border border-yellow-300 text-yellow-800 rounded-lg mb-4">
                ⚠ Belum ada Tipe Kamar yang terdaftar.
                <br>Silakan buat Tipe Kamar terlebih dahulu sebelum menambahkan fasilitas.
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Tutup</button>
            </div>
            @else
            <form method="POST" action="{{ route('room_facilities.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="create_room_type_id" class="block text-sm font-medium text-gray-700">Tipe Kamar</label>
                    <select name="room_type_id" id="create_room_type_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                        @foreach($roomTypes as $roomType)
                        <option value="{{ $roomType->id }}">{{ $roomType->name_type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="create_facility_name" class="block text-sm font-medium text-gray-700">Nama Fasilitas</label>
                    <input type="text" name="facility_name" id="create_facility_name" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Simpan</button>
                </div>
            </form>
            @endif
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <h2 class="text-xl font-bold mb-4">Edit Fasilitas</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="edit_room_type_id" class="block text-sm font-medium text-gray-700">Tipe Kamar</label>
                    <select name="room_type_id" id="edit_room_type_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                        @foreach(\App\Models\RoomType::all() as $roomType)
                        <option value="{{ $roomType->id }}">{{ $roomType->name_type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="edit_facility_name" class="block text-sm font-medium text-gray-700">Nama Fasilitas</label>
                    <input type="text" name="facility_name" id="edit_facility_name" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const createModal = document.getElementById('createModal');

        function openCreateModal() {
            createModal.classList.remove('hidden');
            createModal.classList.add('flex');
        }

        function closeCreateModal() {
            createModal.classList.add('hidden');
            createModal.classList.remove('flex');
        }

        const editModal = document.getElementById('editModal');
        const editForm = document.getElementById('editForm');
        const editFacilityName = document.getElementById('edit_facility_name');
        const editRoomType = document.getElementById('edit_room_type_id');

        function openEditModal(id, roomTypeId, facilityName) {
            editForm.action = `/room_facilities/${id}`;
            editFacilityName.value = facilityName;
            editRoomType.value = roomTypeId;
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        }
    </script>

</x-app-layout>