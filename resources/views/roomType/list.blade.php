<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Room Types - {{ $hotel->name_hotel }} - {{ $hotel->city }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white shadow rounded-lg">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-bold">Daftar Room Types</h3>
            <a href="{{ route('hotels.index') }}" class="text-black hover:underline">← Kembali</a>
        </div>

        @if(session('success'))
        <div class="p-3 bg-green-200 text-green-800 rounded mb-3">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('room-types.store') }}" method="POST" enctype="multipart/form-data" class="mb-6">
            @csrf
            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
            <div class="grid grid-cols-6 gap-4">
                <input type="text" name="name_type" placeholder="Nama Tipe" class="border p-2 rounded" required>
                <input type="text" name="facility" placeholder="Fasilitas" class="border p-2 rounded">
                <input type="number" name="capacity" placeholder="Kapasitas" class="border p-2 rounded" required>
                <input type="number" name="nightly_rate" placeholder="Harga/Malam" class="border p-2 rounded" required>
                <input type="file" name="photos[]" multiple class="border p-2 rounded">
                <button class="bg-gray-500 text-white rounded px-4 py-2">Tambah</button>
            </div>
        </form>

        <table class="w-full text-sm border-separate border-spacing-0 rounded-xl overflow-hidden shadow">
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
                    <td class="px-4 py-3">{{ $type->facility }}</td>
                    <td class="px-4 py-3">{{ $type->capacity }}</td>
                    <td class="px-4 py-3">{{ number_format($type->nightly_rate) }}</td>
                    <td class="px-4 py-3">
                        @if(!empty($type->photos) && is_array($type->photos))
                        <div class="flex gap-2">
                            @foreach($type->photos as $photo)
                            <img src="{{ asset('storage/'.$photo) }}"
                                class="w-12 h-12 object-cover rounded border"
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
</x-app-layout>