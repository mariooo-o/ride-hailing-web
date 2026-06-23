<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OpenStreetMapService;

class OrderController extends Controller
{
    // ─── INDEX ───────────────────────────────────────────────────────────────
    public function index()
    {
        $orders = Order::with('user', 'driver.user')->latest()->get();
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
        $request->validate([
            'pickup'       => 'required|string|min:3|max:255',
            'destination'  => 'required|string|min:3|max:255',
            'vehicle_type' => 'required|in:Motor,Mobil',
        ]);

        if (strtolower(trim($request->pickup)) === strtolower(trim($request->destination))) {
            return back()->withInput()->withErrors(['destination' => 'Lokasi tujuan tidak boleh sama dengan pickup.']);
        }

        $pickup = $map->searchLocation($request->pickup);
        if (empty($pickup)) {
            return back()->withInput()->withErrors(['pickup' => 'Lokasi pickup tidak ditemukan.']);
        }

        $destination = $map->searchLocation($request->destination);
        if (empty($destination)) {
            return back()->withInput()->withErrors(['destination' => 'Lokasi tujuan tidak ditemukan.']);
        }

        $pickupLat      = $pickup['lat'];
        $pickupLng      = $pickup['lon'];
        $destinationLat = $destination['lat'];
        $destinationLng = $destination['lon'];

        $distance = $map->getRoadDistance($pickupLat, $pickupLng, $destinationLat, $destinationLng);

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

        Order::create([
            'user_id'         => Auth::id(), // otomatis dari user yang login
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

        return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat!');
    }

    // ─── TAKE ORDER (driver ambil order) ─────────────────────────────────────
    public function takeOrder($id)
    {
        $driver = Auth::user()->driver;

        // Cek driver aktif dan online
        if(!$driver || $driver->status !== 'active' || !$driver->available){
            return back()->with('error', 'Kamu harus aktif dan online untuk mengambil order.');
        }

        $order = Order::findOrFail($id);

        // Cek order masih pending
        if($order->status !== 'pending'){
            return back()->with('error', 'Order ini sudah diambil atau selesai.');
        }

        $order->update([
            'driver_id' => $driver->id,
            'status'    => 'ongoing',
        ]);

        return redirect()->route('driver.orders')->with('success', 'Order berhasil diambil!');
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
            'status' => 'required|in:pending,ongoing,completed,cancelled',
            'price'  => 'required|integer|min:0',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'price'  => $request->price,
        ]);

        return redirect()->route('orders.index')->with('success', 'Order berhasil diupdate!');
    }

    // ─── COMPLETE ─────────────────────────────────────────────────────────────
    public function complete($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'completed']);

        // Cek yang complete driver atau customer
        if(Auth::user()->role == 'driver'){
            return redirect()->route('driver.orders')->with('success', 'Order ditandai selesai.');
        }

        return redirect()->route('orders.index')->with('success', 'Order ditandai selesai.');
    }

    // ─── DESTROY ──────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus.');
    }

    // ─── HISTORY ──────────────────────────────────────────────────────────────
    public function history()
    {
        $orders = Order::whereIn('status', ['completed', 'cancelled'])->latest()->get();
        return view('orders.history', compact('orders'));
    }

    // ─── DRIVER ORDER LIST ────────────────────────────────────────────────────
    public function driverOrders()
    {
        $driver = Auth::user()->driver;
        $pendingOrders = Order::where('status', 'pending')->with('user')->latest()->get();
        $myOrders = Order::where('driver_id', $driver->id)->with('user')->latest()->get();
        return view('orders.driver', compact('pendingOrders', 'myOrders'));
    }
}