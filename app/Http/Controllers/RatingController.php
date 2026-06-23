<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
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
            ->where('rater_id', auth()->id())
            ->where('type', $request->type)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Sudah pernah memberi rating'], 409);
        }

        $rating = Rating::create([
            'order_id' => $request->order_id,
            'rater_id' => auth()->id(),
            'rated_id' => $request->rated_id,
            'stars'    => $request->stars,
            'type'     => $request->type,
            'comment'  => $request->comment,
        ]);

        return response()->json(['message' => 'Rating berhasil disimpan', 'data' => $rating], 201);
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