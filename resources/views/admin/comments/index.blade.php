<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Komentar') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-left">User</th>
                                <th class="py-2 px-4 border-b text-left">Komentar</th>
                                <th class="py-2 px-4 border-b text-left">Ditujukan ke</th>
                                <th class="py-2 px-4 border-b text-left">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
    @foreach($comments as $comment)
        <tr class="hover:bg-gray-50">
            <td class="py-2 px-4 border-b">{{ $comment->user->name }}</td>
            <td class="py-2 px-4 border-b">{{ $comment->content }}</td>
            <td class="py-2 px-4 border-b">
                Hotel: {{ $comment->hotel->name_hotel ?? '-' }}

            </td>
            <td class="py-2 px-4 border-b">{{ $comment->created_at->format('d M Y H:i') }}</td>
        </tr>
    @endforeach
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
