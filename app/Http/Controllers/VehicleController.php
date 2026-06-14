<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // Tampilkan semua kendaraan
    public function index()
    {
        $vehicles = Vehicle::with('driver.user')->get();
        return view('vehicles.index', compact('vehicles'));
    }

    // Tampilkan form tambah kendaraan
    public function create()
    {
        $drivers = Driver::with('user')->where('status', 'active')->get();
        return view('vehicles.create', compact('drivers'));
    }

    // Simpan kendaraan baru
    public function store(Request $request)
    {
        $request->validate([
            'driver_id'    => 'required|exists:drivers,id|unique:vehicles,driver_id',
            'brand'        => 'required|string|max:255',
            'model'        => 'required|string|max:255',
            'color'        => 'required|string|max:255',
            'plate_number' => 'required|string|unique:vehicles',
            'year'         => 'required|digits:4',
            'type'         => 'required|in:motorcycle,car,car_xl',
        ]);

        Vehicle::create([
            'driver_id'           => $request->driver_id,
            'brand'               => $request->brand,
            'model'               => $request->model,
            'color'               => $request->color,
            'plate_number'        => $request->plate_number,
            'year'                => $request->year,
            'type'                => $request->type,
            'verification_status' => 'pending',
        ]);

        return redirect('/vehicles')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    // Tampilkan detail kendaraan
    public function show(Vehicle $vehicle)
    {
        $vehicle->load('driver.user');
        return view('vehicles.show', compact('vehicle'));
    }

    // Tampilkan form edit kendaraan
    public function edit(Vehicle $vehicle)
    {
        $drivers = Driver::with('user')->where('status', 'active')->get();
        return view('vehicles.edit', compact('vehicle', 'drivers'));
    }

    // Update kendaraan
    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'brand'        => 'required|string|max:255',
            'model'        => 'required|string|max:255',
            'color'        => 'required|string|max:255',
            'plate_number' => 'required|string|unique:vehicles,plate_number,' . $vehicle->id,
            'year'         => 'required|digits:4',
            'type'         => 'required|in:motorcycle,car,car_xl',
        ]);

        $vehicle->update($request->only([
            'brand', 'model', 'color', 'plate_number', 'year', 'type'
        ]));

        return redirect('/vehicles')->with('success', 'Kendaraan berhasil diupdate.');
    }

    // Hapus kendaraan
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect('/vehicles')->with('success', 'Kendaraan berhasil dihapus.');
    }

    // Verifikasi kendaraan
    public function verify(Vehicle $vehicle)
    {
        $vehicle->update(['verification_status' => 'verified']);
        return redirect('/vehicles')->with('success', 'Kendaraan berhasil diverifikasi.');
    }

    // Tolak kendaraan
    public function reject(Vehicle $vehicle)
    {
        $vehicle->update(['verification_status' => 'rejected']);
        return redirect('/vehicles')->with('success', 'Kendaraan berhasil ditolak.');
    }
}