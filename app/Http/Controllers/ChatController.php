<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // ── Tentukan role user login terhadap order ini ──────────────────────
    private function resolveRole(Order $order)
    {
        $userId = Auth::id();

        if ($order->user_id === $userId) {
            return 'customer';
        }

        if ($order->driver && $order->driver->user_id === $userId) {
            return 'driver';
        }

        return null; // user ini bukan customer/driver di order ini
    }

    // ── Daftar chat milik user yang login ─────────────────────────────────
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        if ($role === 'customer') {
            $orders = Order::with('driver.user', 'messages')
                ->where('user_id', $user->id)
                ->has('messages')
                ->withCount(['messages as unread_count' => function ($q) {
                    $q->where('sender', 'driver')->where('is_read', false);
                }])
                ->latest()
                ->get();
        } elseif ($role === 'driver') {
            $driverId = $user->driver?->id;

            $orders = Order::with('user', 'messages')
                ->where('driver_id', $driverId)
                ->has('messages')
                ->withCount(['messages as unread_count' => function ($q) {
                    $q->where('sender', 'customer')->where('is_read', false);
                }])
                ->latest()
                ->get();
        } else {
            abort(403);
        }

        return view('chat.index', compact('orders', 'role'));
    }
    // ── Halaman chat untuk 1 order ─────────────────────────────────────────
    public function show($orderId)
    {
        $order = Order::with('user', 'driver.user')->findOrFail($orderId);

        $as = $this->resolveRole($order);
        if (!$as) {
            abort(403, 'Kamu tidak punya akses ke chat ini.');
        }

        $messages = $order->messages()->orderBy('created_at')->get();

        // Tandai pesan dari lawan bicara sebagai sudah dibaca
        $otherSender = $as === 'customer' ? 'driver' : 'customer';
        $order->messages()
            ->where('sender', $otherSender)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat.show', compact('order', 'messages', 'as'));
    }

    // ── Kirim pesan (AJAX) ───────────────────────────────────────────────
    public function send(Request $request, $orderId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $order = Order::findOrFail($orderId);
        $as = $this->resolveRole($order);
        if (!$as) {
            abort(403, 'Kamu tidak punya akses ke chat ini.');
        }

        $msg = Message::create([
            'order_id'    => $order->id,
            'sender'      => $as,
            'sender_name' => Auth::user()->name,
            'message'     => $request->message,
            'is_read'     => false,
        ]);

        return response()->json([
            'id'          => $msg->id,
            'sender'      => $msg->sender,
            'sender_name' => $msg->sender_name,
            'message'     => $msg->message,
            'time'        => $msg->created_at->format('H:i'),
        ]);
    }

    // ── Polling: ambil pesan baru (AJAX) ─────────────────────────────────
    public function poll(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $as = $this->resolveRole($order);
        if (!$as) {
            abort(403);
        }

        $lastId = $request->query('last_id', 0);

        $messages = Message::where('order_id', $orderId)
            ->where('id', '>', $lastId)
            ->orderBy('created_at')
            ->get()
            ->map(fn($m) => [
                'id'          => $m->id,
                'sender'      => $m->sender,
                'sender_name' => $m->sender_name,
                'message'     => $m->message,
                'time'        => $m->created_at->format('H:i'),
            ]);

        $otherSender = $as === 'customer' ? 'driver' : 'customer';
        Message::where('order_id', $orderId)
            ->where('sender', $otherSender)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }
}