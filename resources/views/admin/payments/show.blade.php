<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pembayaran
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6 space-y-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Pembayaran</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Nama Pengguna</p>
                    <p class="text-base font-medium text-gray-800">{{ $payment->booking->user->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Kode Booking</p>
                    <p class="text-base font-medium text-gray-800">{{ $payment->booking->code_booking }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Metode Pembayaran</p>
                    <p class="text-base font-medium text-gray-800 uppercase">{{ $payment->payment_method }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Status Pembayaran</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold 
                        {{ $payment->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                           ($payment->payment_status === 'failed' ? 'bg-red-100 text-red-800' : 
                           ($payment->payment_status === 'refunded' ? 'bg-gray-100 text-gray-700' :
                            'bg-yellow-100 text-yellow-800')) }}">
                        {{ ucfirst($payment->payment_status) }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Tanggal Pembayaran</p>
                    <p class="text-base font-medium text-gray-800">
                        {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->translatedFormat('d F Y, H:i') : '-' }}
                    </p>
                </div>

                @if ($payment->payment_status === 'refunded')
                <div>
                    <p class="text-sm text-gray-500">Jumlah Refund</p>
                    <p class="text-base font-medium text-gray-800">Rp{{ number_format($payment->refund_amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Refund</p>
                    <p class="text-base font-medium text-gray-800">
                        {{ $payment->refunded_at ? \Carbon\Carbon::parse($payment->refunded_at)->translatedFormat('d F Y, H:i') : '-' }}
                    </p>
                </div>
                @endif

                @if ($payment->payment_proof)
                <div class="sm:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Bukti Pembayaran</p>
                    <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank"
                        class="inline-block bg-blue-50 text-blue-600 px-4 py-2 rounded hover:bg-blue-100 transition">
                        Lihat Bukti Pembayaran
                    </a>
                </div>
                @endif
            </div>

            @if ($payment->payment_status === 'pending')
            <div class="border-t pt-4">
                <form action="{{ route('payments.verify', $payment->id) }}" method="POST" class="flex flex-col sm:flex-row items-start sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">
                    @csrf
                    @method('PATCH')

                    <select name="payment_status" class="border px-3 py-2 rounded text-sm w-full sm:w-auto">
                        <option value="paid">✔️ Setujui (Paid)</option>
                        <option value="failed">❌ Gagal (Failed)</option>
                        <option value="refunded">💸 Refund</option>
                    </select>

                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition text-sm">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>