<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pembayaran
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="bg-white rounded shadow p-6 space-y-4">
            <div>
                <strong>User:</strong> {{ $payment->booking->user->name }}
            </div>
            <div>
                <strong>Kode Booking:</strong> {{ $payment->booking->code_booking }}
            </div>
            <div>
                <strong>Metode Pembayaran:</strong> {{ strtoupper($payment->payment_method) }}
            </div>
            <div>
                <strong>Status Pembayaran:</strong>
                <span
                    class="px-2 py-1 rounded text-sm {{ $payment->payment_status === 'paid' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                    {{ ucfirst($payment->payment_status) }}
                </span>
            </div>
            <div>
                <strong>Tanggal Pembayaran:</strong> {{ $payment->payment_date ?? '-' }}
            </div>
            @if ($payment->payment_proof)
                <div>
                    <strong>Bukti Pembayaran:</strong><br>
                    <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank"
                        class="text-blue-600 underline">Lihat Bukti</a>
                </div>
            @endif

            @if ($payment->payment_status === 'pending')
                <form action="{{ route('payments.verify', $payment->id) }}" method="POST" class="mt-4 space-x-2">
                    @csrf
                    @method('PATCH')
                    <select name="payment_status" class="border px-3 py-2 rounded text-sm">
                        <option value="paid">Setujui (Paid)</option>
                        <option value="failed">Gagal (Failed)</option>
                        <option value="refunded">Refund</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                        Simpan
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>