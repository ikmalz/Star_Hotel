<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hotels
        </h2>
    </x-slot>
    <div id="flashMessage" class="hidden mb-4 p-4 rounded-lg shadow-sm"></div>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow-lg sm:rounded-2xl border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-500">Daftar Hotel</h1>

                    <button id="openModalBtn"
                        class="inline-flex items-center px-5 py-2.5 bg-gray-400 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-all shadow-md">
                        + Tambah Hotel
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                    <table class="min-w-full text-sm text-gray-700">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                            <tr>
                                <th class="text-left py-3 px-4">ID</th>
                                <th class="text-left py-3 px-4">Nama Hotel</th>
                                <th class="text-left py-3 px-4">Lokasi</th>
                                <th class="text-left py-3 px-4">Deskripsi</th>
                                <th class="text-left py-3 px-4">Gambar</th>
                                <th class="text-left py-3 px-4">CS Phone</th>
                                <th class="text-left py-3 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hotels as $hotel)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-3 px-4">{{ $hotel->id }}</td>
                                <td class="py-3 px-4 font-medium">{{ $hotel->name_hotel }}</td>
                                <td class="py-3 px-4">{{ $hotel->location }}</td>
                                <td class="py-3 px-4">{{ Str::limit($hotel->description, 60) }}</td>
                                <td class="py-3 px-4">
                                    @if($hotel->image)
                                    <img src="{{ asset('storage/' . $hotel->image) }}"
                                        alt="Gambar"
                                        class="w-14 h-14 object-cover rounded-lg shadow-sm">
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">{{ $hotel->customer_service_phone }}</td>
                                <td class="py-3 px-4 space-x-2">
                                    <button
                                        class="btn-edit px-3 py-1 text-sm text-white font-medium bg-gray-400 hover:bg-gray-300 hover:text-black/20 rounded-lg transition"
                                        data-id="{{ $hotel->id }}"
                                        data-name="{{ $hotel->name_hotel }}"
                                        data-location="{{ $hotel->location }}"
                                        data-description="{{ $hotel->description }}"
                                        data-image="{{ $hotel->image }}"
                                        data-cs="{{ $hotel->customer_service_phone }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('hotels.destroy', $hotel->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 text-sm text-white font-medium bg-gray-400 hover:bg-gray-300 hover:text-black/20 rounded-lg transition"
                                            onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                                    </form>

                                    <a 
                                    href="{{ route('room-types.index', $hotel->id) }}"
                                        class="px-3 py-1 text-sm text-white font-medium bg-gray-400 hover:bg-gray-500 rounded-lg transition">
                                        Room Types
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-400">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- pagination --}}
                @if(method_exists($hotels, 'links'))
                <div class="mt-6">
                    {{ $hotels->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>



    <div id="hotelModal" class="fixed inset-0 hidden items-center justify-center bg-gray-800 bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Tambah Hotel</h3>

            <form id="hotelForm" action="{{ route('hotels.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Hotel</label>
                        <input type="text" name="name_hotel"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" name="location"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gambar (URL / path)</label>
                        <input type="file" name="image" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">

                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer Service Phone</label>
                        <input type="text" name="customer_service_phone"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" id="closeModalBtn"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="editHotelModal" class="fixed inset-0 hidden items-center justify-center bg-gray-800 bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Edit Hotel</h3>

            <form id="editHotelForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Hotel</label>
                        <input type="text" name="name_hotel" id="edit_name_hotel"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" name="location" id="edit_location"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" id="edit_description" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gambar (URL / path)</label>
                        <input type="file" name="image" id="edit_image" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer Service Phone</label>
                        <input type="text" name="customer_service_phone" id="edit_cs"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" id="closeEditModalBtn"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function showFlashMessage(message, type = 'success') {
                const flashDiv = document.getElementById('flashMessage');
                flashDiv.className = `mb-4 p-4 rounded-lg shadow-sm text-sm ${
        type === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300'
    }`;
                flashDiv.innerText = message;
                flashDiv.classList.remove('hidden');

                setTimeout(() => {
                    flashDiv.classList.add('hidden');
                }, 3000);
            }


            // create
            const openModalBtn = document.getElementById('openModalBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const hotelModal = document.getElementById('hotelModal');
            const hotelForm = document.getElementById('hotelForm');

            openModalBtn.addEventListener('click', function() {
                hotelModal.classList.remove('hidden');
                hotelModal.classList.add('flex');
            });

            closeModalBtn.addEventListener('click', function() {
                closeCreateModal();
            });

            window.addEventListener('click', function(e) {
                if (e.target === hotelModal) {
                    closeCreateModal();
                }
            });

            function closeCreateModal() {
                hotelModal.classList.remove('flex');
                hotelModal.classList.add('hidden');
                hotelForm.reset();
            }

            hotelForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(hotelForm);

                fetch("{{ route('hotels.store') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": csrf,
                            "Accept": "application/json"
                        },
                        body: formData
                    })
                    .then(async res => {
                        const data = await res.json();

                        if (data.success) {
                            closeCreateModal();
                            showFlashMessage(data.message || 'Hotel berhasil ditambahkan', 'success');
                            setTimeout(() => window.location.reload(), 1200);
                        } else {
                            showFlashMessage(data.message || 'Gagal menambahkan hotel', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showFlashMessage('Terjadi kesalahan saat mengirim data', 'error');
                    });
            });



            // edit
            const editModal = document.getElementById('editHotelModal');
            const editForm = document.getElementById('editHotelForm');
            const closeEditModalBtn = document.getElementById('closeEditModalBtn');

            let currentEditId = null;

            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', () => {
                    currentEditId = btn.dataset.id;

                    document.getElementById('edit_id').value = currentEditId;
                    document.getElementById('edit_name_hotel').value = btn.dataset.name || '';
                    document.getElementById('edit_location').value = btn.dataset.location || '';
                    document.getElementById('edit_description').value = btn.dataset.description || '';
                    document.getElementById('edit_image').value = '';
                    document.getElementById('edit_cs').value = btn.dataset.cs || '';

                    editModal.classList.remove('hidden');
                    editModal.classList.add('flex');
                });
            });

            closeEditModalBtn.addEventListener('click', () => {
                closeEditModal();
            });

            window.addEventListener('click', function(e) {
                if (e.target === editModal) {
                    closeEditModal();
                }
            });

            function closeEditModal() {
                editModal.classList.remove('flex');
                editModal.classList.add('hidden');
                editForm.reset();
                currentEditId = null;
            }

            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (!currentEditId) return;

                const formData = new FormData(editForm);
                formData.append('_method', 'PUT');

                fetch(`/hotels/${currentEditId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(async res => {
                        const data = await res.json();

                        if (data.success) {
                            closeEditModal();
                            showFlashMessage(data.message || 'Hotel berhasil diperbarui', 'success');
                            setTimeout(() => window.location.reload(), 1200);
                        } else {
                            showFlashMessage(data.message || 'Gagal memperbarui hotel', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showFlashMessage('Terjadi kesalahan saat mengirim data', 'error');
                    });
            });
        });
    </script>
</x-app-layout>