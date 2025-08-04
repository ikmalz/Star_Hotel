<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentAPIController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['booking.user'])->latest()->get();
        return PaymentResource::collection($payments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|in:dana,gopay,ovo,shopeepay,linkaja,qris,bca,bni,bri,mandiri,credit_card,debit_card,cash',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Anda tidak bisa membayar booking milik orang lain.'], 403);
        }

        if ($booking->status_booking === 'paid') {
            return response()->json(['message' => 'Booking ini sudah dibayar.'], 422);
        }

        $paymentProofPath = $request->hasFile('payment_proof')
            ? $request->file('payment_proof')->store('payments', 'public')
            : null;

        $autoPaidMethods = ['dana', 'gopay', 'ovo', 'shopeepay', 'linkaja', 'qris', 'credit_card', 'debit_card'];
        $status = in_array($request->payment_method, $autoPaidMethods) ? 'paid' : 'pending';

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => $request->payment_method,
            'payment_proof' => $paymentProofPath,
            'payment_status' => $status,
            'payment_date' => $status === 'paid' ? now() : null,
        ]);

        if ($status === 'paid') {
            $booking->update(['status_booking' => 'paid']);
        }

        return (new PaymentResource($payment->load('booking.user')))
            ->additional(['message' => 'Payment berhasil dibuat'])
            ->response()
            ->setStatusCode(201);
    }


    public function show($id)
    {
        $payment = Payment::with('booking.user')->findOrFail($id);
        return new PaymentResource($payment);
    }

    public function verify(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'payment_status' => 'required|in:paid,failed,refunded'
        ]);

        $payment->update([
            'payment_status' => $request->payment_status,
            'payment_date' => now()
        ]);

        if ($request->payment_status === 'paid') {
            $payment->booking->update(['status_booking' => 'paid']);
        } elseif ($request->payment_status === 'failed') {
            $payment->booking->update(['status_booking' => 'pending']);
        } elseif ($request->payment_status === 'refunded') {
            $payment->booking->update(['status_booking' => 'canceled']);
        }

        return response()->json([
            'message' => 'Payment berhasil diverifikasi',
            'data' => $payment->load('booking.user')
        ]);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment berhasil dihapus']);
    }
}
