<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Provinces
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        {{-- Flash message dari redirect (success) --}}
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        {{-- Flash message dinamis via JavaScript --}}
        <div id="flash-message" class="hidden mb-4 p-4 rounded-xl shadow-sm"></div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-700">Daftar Provinsi</h1>
            <button onclick="openCreateModal()"
                class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl shadow-md transition-all duration-200">
                + Tambah Provinsi
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl shadow-lg border border-gray-100">
            <table class="w-full border-collapse min-w-[600px]">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-gray-600 text-sm">
                        <th class="px-4 py-3 font-medium">#</th>
                        <th class="px-4 py-3 font-medium">Nama Provinsi</th>
                        <th class="px-4 py-3 font-medium">Jumlah Kota</th>
                        <th class="px-4 py-3 text-center font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @forelse($provinces as $province)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-4 py-3">{{ $province->id }}</td>
                        <td class="px-4 py-3">{{ $province->name }}</td>
                        <td class="px-4 py-3">{{ $province->cities_count }}</td>
                        <td class="px-4 py-3 flex justify-center gap-2">
                            <button onclick="openEditModal(`{{ $province->id }}`)"
                                class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded-lg shadow-sm text-xs transition-all">
                                Edit
                            </button>
                            <form action="{{ route('provinces.destroy', $province->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus provinsi ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-lg shadow-sm text-xs transition-all">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-400 text-sm">Belum ada data provinsi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $provinces->links() }}
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <h2 class="text-xl font-bold mb-4">Tambah Provinsi</h2>
            <form id="createForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="create_name" class="block text-sm font-medium text-gray-700">Nama Provinsi</label>
                    <input type="text" name="name" id="create_name"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <h2 class="text-xl font-bold mb-4">Edit Provinsi</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Provinsi</label>
                    <input type="text" name="name" id="edit_name"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT --}}
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
        const editNameInput = document.getElementById('edit_name');

     function openEditModal(id) {
    fetch(`/provinces/${id}/edit`, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        editForm.action = `/provinces/${id}`;
        editForm.setAttribute('data-id', id);
        editNameInput.value = data.name;
        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
    })
    .catch(error => {
        showFlashMessage('error', 'Gagal mengambil data provinsi');
    });
}

        function closeEditModal() {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        }

        function showFlashMessage(type, message) {
            const flashDiv = document.getElementById('flash-message');
            flashDiv.classList.remove('hidden');
            flashDiv.className = 'mb-4 p-4 rounded-xl shadow-sm';
            flashDiv.textContent = message;

            if (type === 'success') {
                flashDiv.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                flashDiv.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }

            setTimeout(() => {
                flashDiv.classList.add('hidden');
            }, 3000);
        }

        // CREATE
        const createForm = document.getElementById('createForm');
        if (createForm) {
            createForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(createForm);

                try {
                  const res = await fetch("{{ route('provinces.store') }}", {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
    }
});
const data = await res.json();

                    if (data.success) {
                        showFlashMessage('success', data.message);
                        closeCreateModal();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showFlashMessage('error', data.message || 'Gagal menambahkan');
                    }
                } catch (error) {
                    showFlashMessage('error', 'Terjadi kesalahan sistem');
                }
            });
        }

        // UPDATE
        if (editForm) {
            editForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const id = editForm.getAttribute('data-id');
                const formData = new FormData(editForm);

                try {
              const res = await fetch(`/provinces/${id}`, {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-HTTP-Method-Override': 'PUT',
        'Accept': 'application/json'
    }
});
const data = await res.json();


                    if (data.success) {
                        showFlashMessage('success', data.message);
                        closeEditModal();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showFlashMessage('error', data.message || 'Gagal memperbarui');
                    }
                } catch (error) {
                    showFlashMessage('error', 'Terjadi kesalahan sistem');
                }
            });
        }
    </script>
</x-app-layout>
