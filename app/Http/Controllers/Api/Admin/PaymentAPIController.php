<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentAPIController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['booking.user', 'booking.roomType'])->latest()->get();
        return PaymentResource::collection($payments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'nullable|numeric|min:1',
            'payment_method' => 'required|in:dana,gopay,ovo,shopeepay,linkaja,qris,bca,bni,bri,mandiri,credit_card,debit_card,cash',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if (in_array($booking->status_booking, ['paid', 'checked_in', 'checked_out', 'canceled'])) {
            return response()->json(['message' => 'Booking ini tidak bisa dibayar.'], 422);
        }

        $paymentProofPath = $request->hasFile('payment_proof')
            ? $request->file('payment_proof')->store('payments', 'public')
            : null;

        $autoPaidMethods = ['dana', 'gopay', 'ovo', 'shopeepay', 'linkaja', 'qris', 'credit_card', 'debit_card'];
        $status = in_array($request->payment_method, $autoPaidMethods) ? 'paid' : 'pending';

        DB::transaction(function () use ($request, $booking, $paymentProofPath, $status) {
            $payment = Payment::create([
                'booking_id'     => $booking->id,
                'amount'         => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_proof'  => $paymentProofPath,
                'payment_status' => $status,
                'payment_date'   => $status === 'paid' ? now() : null,
            ]);

            if ($status === 'paid') {
                $booking->update(['status_booking' => 'paid']);
            }

            return $payment;
        });

        $payment = Payment::with(['booking.user', 'booking.roomType'])->latest()->first();

        return (new PaymentResource($payment))
            ->additional(['message' => 'Payment berhasil dibuat'])
            ->response()
            ->setStatusCode(201);
    }

    public function show($id)
    {
        $payment = Payment::with(['booking.user', 'booking.roomType'])->findOrFail($id);
        return new PaymentResource($payment);
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
                'payment_date'   => now(),
            ];

            if ($status === 'refunded') {
                $updateData['refund_amount'] = $payment->amount;
                $updateData['refunded_at']   = now();
            }

            $payment->update($updateData);

            match ($status) {
                'paid' => $payment->booking->update(['status_booking' => 'paid']),
                'failed' => $payment->booking->update(['status_booking' => 'pending']),
                'refunded' => $payment->booking->update(['status_booking' => 'canceled']),
            };
        });

        return response()->json([
            'message' => 'Payment berhasil diverifikasi',
            'data' => $payment->load(['booking.user', 'booking.roomType']),
        ]);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment berhasil dihapus']);
    }
}
