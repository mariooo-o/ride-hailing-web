<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function create($orderId)
    {
        $order = Order::with('driver.user')->findOrFail($orderId);

        // Cek order sudah paid
        if($order->status !== 'paid'){
            return redirect()->back()->with('error', 'Order belum selesai.');
        }

        // Cek sudah pernah rating atau belum
        $alreadyRated = Rating::where('order_id', $orderId)
            ->where('rater_id', Auth::id())
            ->where('type', 'driver')
            ->exists();

        if($alreadyRated){
            return redirect()->back()->with('error', 'Kamu sudah memberi rating untuk order ini.');
        }

        return view('ratings.create', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rated_id' => 'required|exists:users,id',
            'stars'    => 'required|integer|min:1|max:5',
            'type'     => 'required|in:driver,passenger',
            'comment'  => 'nullable|string|max:500',
        ]);

        $existing = Rating::where('order_id', $request->order_id)
            ->where('rater_id', Auth::id())
            ->where('type', $request->type)
            ->first();

        if($existing){
            return redirect()->back()->with('error', 'Kamu sudah memberi rating untuk order ini.');
        }

        Rating::create([
            'order_id' => $request->order_id,
            'rater_id' => Auth::id(),
            'rated_id' => $request->rated_id,
            'stars'    => $request->stars,
            'type'     => $request->type,
            'comment'  => $request->comment,
        ]);

        return redirect()->route('orders.index')->with('success', 'Rating berhasil diberikan!');
    }

    public function show($userId)
    {
        $avg = Rating::where('rated_id', $userId)
            ->selectRaw('type, AVG(stars) as average, COUNT(*) as total')
            ->groupBy('type')
            ->get();

        return response()->json($avg);
    }
}