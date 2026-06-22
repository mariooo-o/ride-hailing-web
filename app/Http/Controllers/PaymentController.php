<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Order;
use Throwable;

class PaymentController extends Controller
{
    /**
     * Proses pembayaran baru untuk sebuah order.
     */
    public function store(Request $request): JsonResponse
    {
        // ─── 1. VALIDASI INPUT ────────────────────────────────────────────
        $validated = $request->validate([
            'order_id'       => 'required|integer|exists:orders,id',
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:bank_transfer,credit_card,e_wallet,qris',
        ]);

        // ─── 2. AMBIL ORDER & OTORISASI ───────────────────────────────────
        /** @var Order $order */
        $order = Order::findOrFail($validated['order_id']);

        // Pastikan order milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke order ini.',
            ], 403);
        }

        // ─── 3. CEK STATUS ORDER ──────────────────────────────────────────
        if ($order->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Order ini sudah lunas.',
            ], 422);
        }

        if ($order->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Order ini sudah dibatalkan.',
            ], 422);
        }

        // ─── 4. VALIDASI NOMINAL ──────────────────────────────────────────
        if ((float) $validated['amount'] !== (float) $order->total_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Nominal pembayaran tidak sesuai dengan total order.',
                'data'    => [
                    'expected' => $order->total_amount,
                    'received' => $validated['amount'],
                ],
            ], 422);
        }

        // ─── 5. PROSES DALAM DATABASE TRANSACTION ────────────────────────
        try {
            $payment = DB::transaction(function () use ($validated, $order): Payment {
                // Buat record payment dengan status pending
                $payment = Payment::create([
                    'order_id'       => $order->id,
                    'user_id'        => Auth::id(),
                    'amount'         => $validated['amount'],
                    'payment_method' => $validated['payment_method'],
                    'status'         => 'pending',
                ]);

                // Update status order
                $order->update(['status' => 'pending_payment']);

                return $payment;
            });

            // ─── 6. TRIGGER PAYMENT GATEWAY (opsional) ───────────────────
            // Contoh integrasi Midtrans / Xendit:
            // $gatewayResult = app(PaymentGatewayService::class)->initiate($payment);
            // $payment->update(['gateway_token' => $gatewayResult['token']]);

        } catch (Throwable $e) {
            Log::error('Payment store failed', [
                'user_id'  => Auth::id(),
                'order_id' => $validated['order_id'],
                'error'    => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.',
            ], 500);
        }

        // ─── 7. RESPONSE SUKSES ───────────────────────────────────────────
        return response()->json([
            'success' => true,
            'message' => 'Pembayaran sedang diproses.',
            'data'    => $payment->load('order'),
        ], 201);
    }

    /**
     * Tampilkan detail satu payment.
     */
    public function show(int $id): JsonResponse
    {
        /** @var Payment $payment */
        $payment = Payment::with('order')->findOrFail($id);

        // Pastikan hanya pemilik order yang bisa melihat
        if ($payment->order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke data pembayaran ini.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => $payment,
        ]);
    }

    /**
     * Callback / webhook dari payment gateway (Midtrans, Xendit, dll.)
     * Dipanggil oleh server gateway, BUKAN oleh user langsung.
     */
    public function webhook(Request $request): JsonResponse
    {
        // TODO: Validasi signature dari payment gateway
        // $this->verifyGatewaySignature($request);

        $gatewayStatus = $request->input('transaction_status'); // contoh Midtrans
        $orderId       = $request->input('order_id');

        $payment = Payment::where('order_id', $orderId)->latest()->firstOrFail();

        $statusMap = [
            'settlement' => ['payment' => 'success', 'order' => 'paid'],
            'pending'    => ['payment' => 'pending', 'order' => 'pending_payment'],
            'deny'       => ['payment' => 'failed',  'order' => 'payment_failed'],
            'cancel'     => ['payment' => 'cancelled','order' => 'cancelled'],
            'expire'     => ['payment' => 'expired',  'order' => 'cancelled'],
        ];

        if (isset($statusMap[$gatewayStatus])) {
            DB::transaction(function () use ($payment, $statusMap, $gatewayStatus) {
                $payment->update(['status' => $statusMap[$gatewayStatus]['payment']]);
                $payment->order->update(['status' => $statusMap[$gatewayStatus]['order']]);
            });
        }

        return response()->json(['success' => true]);
    }
}