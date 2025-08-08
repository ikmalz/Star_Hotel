<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserPaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with('booking.roomType')
            ->whereHas('booking', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->latest()
            ->get();

        return PaymentResource::collection($payments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|in:dana,gopay,ovo,shopeepay,linkaja,qris,bca,bni,bri,mandiri,credit_card,debit_card,cash',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Anda tidak bisa membayar booking milik orang lain.'], 403);
        }

        if (!in_array($booking->status_booking, ['pending'])) {
            return response()->json([
                'message' => 'Booking ini tidak bisa dibayar karena sudah dibayar, dibatalkan, atau diselesaikan.'
            ], 422);
        }

        $paymentProofPath = $request->hasFile('payment_proof')
            ? $request->file('payment_proof')->store('payments', 'public')
            : null;

        $autoPaidMethods = ['dana', 'gopay', 'ovo', 'shopeepay', 'linkaja', 'qris', 'credit_card', 'debit_card'];
        $status = in_array($request->payment_method, $autoPaidMethods) ? 'paid' : 'pending';

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $request->amount ?? $booking->price_total,
            'payment_method' => $request->payment_method,
            'payment_proof' => $paymentProofPath,
            'payment_status' => $status,
            'payment_date' => $status === 'paid' ? now() : null,
            'refund_amount' => 0,
            'refunded_at' => null,
        ]);

        if ($status === 'paid') {
            $booking->update(['status_booking' => 'paid']);
        }

        return (new PaymentResource($payment->load('booking.roomType')))
            ->additional(['message' => 'Payment berhasil dibuat.']);
    }

    public function show(Request $request, $id)
    {
        $payment = Payment::with('booking.roomType')
            ->whereHas('booking', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->findOrFail($id);

        return new PaymentResource($payment);
    }


    public function requestRefund(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $payment = Payment::with('booking')->findOrFail($id);

            if ($payment->booking->user_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            if ($payment->payment_status !== 'paid') {
                return response()->json(['message' => 'Payment not eligible for refund'], 422);
            }

            $payment->update([
                'payment_status' => 'refund_requested',
                'request_refund_at' => now(),
                'request_refund_reason' => $request->input('reason'),
            ]);

            $payment->booking->update([
                'refund_requested' => true
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Refund request submitted. Waiting for admin approval.',
                'data' => new PaymentResource($payment->fresh(['booking']))
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to process refund request',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
