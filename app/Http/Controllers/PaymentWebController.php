<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentWebController extends Controller
{
    public function show($id)
    {
        $payment = Payment::with(['booking.user', 'booking.roomType'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,failed,refunded',
        ]);

        $payment = Payment::with('booking')->findOrFail($id);

        DB::transaction(function () use ($request, $payment) {
            $status = $request->payment_status;

            $updateData = [
                'payment_status' => $status,
                'payment_date' => now(),
            ];

            if ($status === 'refunded') {
                $updateData['refund_amount'] = $payment->amount;
                $updateData['refunded_at'] = now();
            }

            $payment->update($updateData);

            match ($status) {
                'paid' => $payment->booking->update(['status_booking' => 'paid']),
                'failed' => $payment->booking->update(['status_booking' => 'pending']),
                'refunded' => $payment->booking->update(['status_booking' => 'canceled']),
                default => null,
            };
        });

        return redirect()->route('bookings.index')
            ->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
