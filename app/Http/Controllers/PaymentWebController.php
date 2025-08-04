<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentWebController extends Controller
{
    public function show($id)
    {
        $payment = Payment::with('booking.user')->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,failed,refunded',
        ]);

        $payment = Payment::with('booking')->findOrFail($id);
        $payment->update([
            'payment_status' => $request->payment_status,
            'payment_date' => now(),
        ]);

        if ($request->payment_status === 'paid') {
            $payment->booking->update(['status_booking' => 'paid']);
        } elseif ($request->payment_status === 'failed') {
            $payment->booking->update(['status_booking' => 'pending']);
        } elseif ($request->payment_status === 'refunded') {
            $payment->booking->update(['status_booking' => 'canceled']);
        }

        return redirect()->route('bookings.index')->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
