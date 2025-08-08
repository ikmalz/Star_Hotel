<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $roomType->name_type }} - Floors
        </h2>
    </x-slot>

    <div class="p-6 bg-white shadow rounded-lg">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-bold">Daftar Lantai</h3>
            <div class="flex gap-3">
                <a href="{{ route('rooms.index', ['roomTypeId' => $roomType->id]) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm">
                    Lihat Rooms
                </a>
                <a href="{{ route('room-types.index', $roomType->hotel->id) }}"
                    class="text-black hover:underline px-3 py-2 text-sm">
                    ← Kembali ke Room Types
                </a>
            </div>
        </div>


        @if(session('success'))
        <div class="p-3 bg-green-200 text-green-800 rounded mb-3">
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('floors.store', $roomType->id) }}" class="mb-4 flex space-x-2">
            @csrf
            <input type="number" name="floor_number" placeholder="Nomor Lantai" class="border-gray-300 rounded-md shadow-sm" required>
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded">Tambah</button>
        </form>

        <!-- Tabel Lantai -->
        <table class="w-full text-sm border-separate border-spacing-0 rounded-xl overflow">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Nomor Lantai</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($floors as $floor)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $floor->id }}</td>
                    <td class="px-4 py-2">{{ $floor->floor_number }}</td>
                    <td class="px-4 py-2 text-center">
                        <form action="{{ route('floors.destroy', $floor->id) }}" method="POST" onsubmit="return confirm('Yakin hapus lantai ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-gray-500 text-white px-3 py-1 rounded">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">Belum ada lantai untuk Room Type ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>