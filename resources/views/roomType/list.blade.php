<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Room Types - {{ $hotel->name_hotel }} - {{ $hotel->city }}
        </h2>
    </x-slot>

    <div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 1500)" class="p-6 bg-white shadow rounded-lg relative">

        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-bold">Daftar Room Types</h3>
            <a href="{{ route('hotels.index') }}" class="text-black hover:underline">← Kembali</a>
        </div>

        @if(session('success'))
        <div class="p-3 bg-green-200 text-green-800 rounded mb-3">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('room-types.store') }}" method="POST" enctype="multipart/form-data" class="mb-6 grid grid-cols-6 gap-4">
            @csrf
            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
            <input type="text" name="name_type" placeholder="Nama Tipe" class="border p-2 rounded" required>
            <input type="text" name="facility" placeholder="Fasilitas" class="border p-2 rounded">
            <input type="number" name="capacity" placeholder="Kapasitas" class="border p-2 rounded" required>
            <input type="number" name="nightly_rate" placeholder="Harga/Malam" class="border p-2 rounded" required>
            <input type="file" name="photos[]" multiple class="border p-2 rounded">
            <button class="bg-gray-500 text-white rounded px-4 py-2">Tambah</button>
        </form>

        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm relative">

            <table x-show="loading" class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Nama Tipe</th>
                        <th class="px-4 py-3 text-left">Kapasitas</th>
                        <th class="px-4 py-3 text-left">Fasilitas</th>
                        <th class="px-4 py-3 text-left">Harga/Malam</th>
                        <th class="px-4 py-3 text-left">Foto</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i=0;$i<5;$i++)
                        <tr class="animate-pulse border-b">
                        <td class="px-4 py-3">
                            <div class="h-4 bg-gray-200 rounded w-8"></div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="h-4 bg-gray-200 rounded w-24"></div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="h-4 bg-gray-200 rounded w-12"></div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="h-4 bg-gray-200 rounded w-12"></div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="h-4 bg-gray-200 rounded w-16"></div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="h-10 w-10 bg-gray-200 rounded"></div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="h-4 bg-gray-200 rounded w-16 mx-auto"></div>
                        </td>
                        </tr>
                        @endfor
                </tbody>
            </table>

            <table x-show="!loading" x-cloak class="w-full text-sm border-separate border-spacing-0 rounded-xl overflow-hidden shadow">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Nama Tipe</th>
                        <th class="px-4 py-3 text-left">Fasilitas</th>
                        <th class="px-4 py-3 text-left">Kapasitas</th>
                        <th class="px-4 py-3 text-left">Harga/Malam</th>
                        <th class="px-4 py-3 text-left">Foto</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roomTypes as $type)
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-4 py-3">{{ $type->id }}</td>
                        <td class="px-4 py-3">{{ $type->name_type }}</td>
                        <td class="px-4 py-3">
                            @if($type->facilities->count())
                            <ul class="list-disc pl-4">
                                @foreach($type->facilities as $facility)
                                <li>{{ $facility->facility_name }}</li>
                                @endforeach
                            </ul>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $type->capacity }}</td>
                        <td class="px-4 py-3">{{ number_format($type->nightly_rate) }}</td>
                        <td class="px-4 py-3">
                            @if(!empty($type->photos) && is_array($type->photos))
                            <div class="flex gap-2">
                                @foreach($type->photos as $photo)
                                <img src="{{ asset('storage/' . $photo) }}" class="w-12 h-12 object-cover rounded border"
                                    alt="Room Photo">
                                @endforeach
                            </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('room-types.destroy', $type->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin ingin hapus?')"
                                    class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded-lg transition">
                                    Hapus
                                </button>
                            </form>

                            <button class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded-lg transition"
                                onclick="openEditModal({{ $type->id }}, '{{ $type->name_type }}', '{{ $type->facility }}', {{ $type->capacity }}, {{ $type->nightly_rate }})">
                                Edit
                            </button>

                            <a href="{{ route('rooms.index', ['roomTypeId' => $type->id]) }}"
                                class="px-3 py-1 text-sm text-black/50 font-medium bg-gray-500/20 hover:bg-gray-500 hover:text-white rounded-lg transition">
                                Room
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-400">Belum ada room type</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-xl relative">
            <h2 class="text-lg font-bold mb-4">Edit Room Type</h2>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                <div class="grid grid-cols-1 gap-4">
                    <input type="text" id="editNameType" name="name_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <input type="number" id="editCapacity" name="capacity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <input type="number" id="editNightlyRate" name="nightly_rate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <input type="file" name="photos[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="mr-2 px-4 py-2 bg-gray-400 rounded text-white">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 rounded text-white">Update</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function openEditModal(id, name_type, facility, capacity, nightly_rate) {
            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.getElementById('editNameType').value = name_type;
            document.getElementById('editCapacity').value = capacity;
            document.getElementById('editNightlyRate').value = nightly_rate;

            const form = document.getElementById('editForm');
            form.action = `/room-types/${id}`;
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

</x-app-layout>