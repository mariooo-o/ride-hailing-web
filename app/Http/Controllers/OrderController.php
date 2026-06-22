<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OpenStreetMapService;

class OrderController extends Controller
{
    // ─── INDEX ───────────────────────────────────────────────────────────────
    public function index()
    {
        $orders = Order::latest()->get();
        return view('orders.index', compact('orders'));
    }

    // ─── CREATE ───────────────────────────────────────────────────────────────
    public function create()
    {
        return view('orders.create');
    }

    // ─── STORE ────────────────────────────────────────────────────────────────
    public function store(Request $request, OpenStreetMapService $map)
    {
        // 1. Validasi input
        $request->validate([
            'pickup'       => 'required|string|min:3|max:255',
            'destination'  => 'required|string|min:3|max:255',
            'vehicle_type' => 'required|in:Motor,Mobil',
        ]);

        // 2. Cek pickup dan destination tidak sama
        if (strtolower(trim($request->pickup)) === strtolower(trim($request->destination))) {
            return back()
                ->withInput()
                ->withErrors(['destination' => 'Lokasi tujuan tidak boleh sama dengan pickup.']);
        }

        // 3. Geocoding pickup
        $pickup = $map->searchLocation($request->pickup);
        if (empty($pickup)) {
            return back()
                ->withInput()
                ->withErrors(['pickup' => 'Lokasi pickup tidak ditemukan, coba nama yang lebih spesifik.']);
        }

        // 4. Geocoding destination
        $destination = $map->searchLocation($request->destination);
        if (empty($destination)) {
            return back()
                ->withInput()
                ->withErrors(['destination' => 'Lokasi tujuan tidak ditemukan, coba nama yang lebih spesifik.']);
        }

        $pickupLat      = $pickup['lat'];
        $pickupLng      = $pickup['lon'];
        $destinationLat = $destination['lat'];
        $destinationLng = $destination['lon'];

        // 5. Hitung jarak
        $distance = $map->getRoadDistance(
            $pickupLat,
            $pickupLng,
            $destinationLat,
            $destinationLng
        );

        // 6. Hitung harga
        if ($request->vehicle_type === 'Motor') {
            $basePrice  = 5000;
            $pricePerKm = 2500;
            $minPrice   = 10000;
        } else {
            $basePrice  = 10000;
            $pricePerKm = 5000;
            $minPrice   = 20000;
        }

        $price = (int) round($basePrice + ($distance * $pricePerKm));
        $price = max($price, $minPrice);

        // 7. Simpan order
        Order::create([
            'pickup'          => $request->pickup,
            'destination'     => $request->destination,
            'pickup_lat'      => $pickupLat,
            'pickup_lng'      => $pickupLng,
            'destination_lat' => $destinationLat,
            'destination_lng' => $destinationLng,
            'distance'        => $distance,
            'vehicle_type'    => $request->vehicle_type,
            'price'           => $price,
            'status'          => 'pending',
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil dibuat!');
    }

    // ─── EDIT ─────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.edit', compact('order'));
    }

    // ─── UPDATE ───────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
            'price'  => 'required|integer|min:0',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'price'  => $request->price,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil diupdate!');
    }

    // ─── COMPLETE ─────────────────────────────────────────────────────────────
    public function complete($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'completed']);

        return redirect()->route('orders.index')
            ->with('success', 'Order ditandai selesai.');
    }

    // ─── DESTROY ──────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        Order::findOrFail($id)->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil dihapus.');
    }

    // ─── HISTORY ──────────────────────────────────────────────────────────────
    public function history()
    {
        $orders = Order::whereIn('status', ['completed', 'cancelled'])
            ->latest()
            ->get();

        return view('orders.history', compact('orders'));
    }
}