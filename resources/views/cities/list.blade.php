<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cities
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div id="flash-message" class="hidden mb-4 p-4 rounded-xl shadow-sm"></div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-700">Daftar Kota</h1>
            <button onclick="openCreateModal()"
                class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-xl shadow-md transition-all duration-200">
                + Tambah Kota
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl shadow-lg border border-gray-100">
            <table class="w-full border-collapse min-w-[600px]">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-gray-600 text-sm">
                        <th class="px-4 py-3 font-medium">#</th>
                        <th class="px-4 py-3 font-medium">Nama Kota</th>
                        <th class="px-4 py-3 font-medium">Provinsi</th>
                        <th class="px-4 py-3 text-center font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @forelse($cities as $city)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-4 py-3">{{ $city->id }}</td>
                        <td class="px-4 py-3">{{ $city->name }}</td>
                        <td class="px-4 py-3">{{ $city->province->name ?? '-' }}</td>
                        <td class="px-4 py-3 flex justify-center gap-2">
                            <button onclick="openEditModal(`{{ $city->id }}`)"
                                class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded-lg shadow-sm text-xs transition-all">
                                Edit
                            </button>
                            <button onclick="deleteCity('{{ $city->id }}')"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-lg shadow-sm text-xs transition-all">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-400 text-sm">Belum ada data kota.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $cities->links() }}
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="createModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <h2 class="text-xl font-bold mb-4">Tambah Kota</h2>
            <form id="createForm">
                @csrf
                <div class="mb-4">
                    <label for="create_province_id" class="block text-sm font-medium text-gray-700">Provinsi</label>
                    <select id="create_province_id" name="province_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                        @foreach($provinces as $province)
                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="create_name" class="block text-sm font-medium text-gray-700">Nama Kota</label>
                    <input type="text" name="name" id="create_name" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
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
            <h2 class="text-xl font-bold mb-4">Edit Kota</h2>
            <form id="editForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_city_id">
                <div class="mb-4">
                    <label for="edit_province_id" class="block text-sm font-medium text-gray-700">Provinsi</label>
                    <select id="edit_province_id" name="province_id" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                        @foreach($provinces as $province)
                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Kota</label>
                    <input type="text" name="name" id="edit_name" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS --}}
    <script>
        const flashMessage = (type, message) => {
            const flash = document.getElementById('flash-message');
            flash.className = 'mb-4 p-4 rounded-xl shadow-sm';
            flash.textContent = message;
            if (type === 'success') {
                flash.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                flash.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }
            flash.classList.remove('hidden');
            setTimeout(() => flash.classList.add('hidden'), 3000);
        }

        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
            document.getElementById('createModal').classList.add('flex');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.getElementById('createModal').classList.remove('flex');
        }

        function openEditModal(id) {
            fetch(`/cities/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('edit_city_id').value = id;
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_province_id').value = data.province_id;
                    document.getElementById('editModal').classList.remove('hidden');
                    document.getElementById('editModal').classList.add('flex');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        document.getElementById('createForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const res = await fetch("{{ route('cities.store') }}", {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();
            if (data.success) {
                flashMessage('success', data.message);
                closeCreateModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                flashMessage('error', data.message || 'Gagal menambahkan kota');
            }
        });

        document.getElementById('editForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('edit_city_id').value;
            const formData = new FormData(e.target);
            const res = await fetch(`/cities/${id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                }
            });
            const data = await res.json();
            if (data.success) {
                flashMessage('success', data.message);
                closeEditModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                flashMessage('error', data.message || 'Gagal memperbarui kota');
            }
        });

        function deleteCity(id) {
            if (!confirm('Yakin ingin menghapus kota ini?')) return;
            fetch(`/cities/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'DELETE'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    flashMessage('success', data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    flashMessage('error', data.message || 'Gagal menghapus kota');
                }
            })
            .catch(() => flashMessage('error', 'Terjadi kesalahan sistem'));
        }
    </script>
</x-app-layout>
