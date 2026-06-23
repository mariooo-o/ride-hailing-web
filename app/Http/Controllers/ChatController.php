<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // ── Daftar chat per order (untuk admin) ───────────────────────────────
    public function index()
    {
        $orders = Order::withCount([
                'messages as unread_count' => function ($q) {
                    $q->where('sender', 'customer')->where('is_read', false);
                }
            ])
            ->has('messages')
            ->latest()
            ->get();

        return view('chat.index', compact('orders'));
    }

    // ── Halaman chat untuk 1 order ────────────────────────────────────────
    public function show($orderId, Request $request)
    {
        $order    = Order::findOrFail($orderId);
        $messages = $order->messages()->get();

        // Tentukan sender dari query param: ?as=admin atau ?as=customer
        $as = $request->query('as', 'customer');

        // Tandai pesan masuk sebagai sudah dibaca
        if ($as === 'admin') {
            $order->messages()
                ->where('sender', 'customer')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            $order->messages()
                ->where('sender', 'admin')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('chat.show', compact('order', 'messages', 'as'));
    }

    // ── Kirim pesan (AJAX) ────────────────────────────────────────────────
    public function send(Request $request, $orderId)
    {
        $request->validate([
            'message'     => 'required|string|max:1000',
            'sender'      => 'required|in:customer,admin',
            'sender_name' => 'required|string|max:100',
        ]);

        $order = Order::findOrFail($orderId);

        $msg = Message::create([
            'order_id'    => $order->id,
            'sender'      => $request->sender,
            'sender_name' => $request->sender_name,
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

        // Tandai pesan sebagai dibaca
        $as = $request->query('as', 'customer');
        $senderToRead = $as === 'admin' ? 'customer' : 'admin';

        Message::where('order_id', $orderId)
            ->where('sender', $senderToRead)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }
}