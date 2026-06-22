<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Driver;
use App\Models\Vehicle;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        return view('customer.dashboard', compact('user'));
    }

    public function daftarDriver()
    {
        if(Auth::user()->driver){
            return redirect('/customer/dashboard')->with('info', 'Kamu sudah terdaftar sebagai driver.');
        }
        return view('customer.daftar-driver');
    }

    public function submitDaftarDriver(Request $request)
    {
        $request->validate([
            'license_number' => 'required|string|unique:drivers',
            'phone_number'   => 'required|string|max:15',
            'brand'          => 'required|string|max:255',
            'model'          => 'required|string|max:255',
            'color'          => 'required|string|max:255',
            'plate_number'   => 'required|string|unique:vehicles',
            'year'           => 'required|digits:4',
            'type'           => 'required|in:motorcycle,car,car_xl',
        ]);

        // Buat data driver
        $driver = Driver::create([
            'user_id'        => Auth::user()->id,
            'license_number' => $request->license_number,
            'phone_number'   => $request->phone_number,
            'status'         => 'inactive',
        ]);

        // Buat data kendaraan
        Vehicle::create([
            'driver_id'           => $driver->id,
            'brand'               => $request->brand,
            'model'               => $request->model,
            'color'               => $request->color,
            'plate_number'        => $request->plate_number,
            'year'                => $request->year,
            'type'                => $request->type,
            'verification_status' => 'pending',
        ]);

        // Update role user jadi driver
        Auth::user()->update(['role' => 'driver']);

        return redirect('/driver/dashboard')->with('success', 'Pendaftaran berhasil! Tunggu persetujuan admin.');
    }
}