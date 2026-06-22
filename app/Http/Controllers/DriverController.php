<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::with('user')->get();
        return view('drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('drivers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users',
            'phone_number'   => 'required|string|max:15',
            'password'       => 'required|min:8',
            'license_number' => 'required|string|unique:drivers',
        ]);

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'password'     => $request->password,
            'role'         => 'driver',
        ]);

        Driver::create([
            'user_id'        => $user->id,
            'license_number' => $request->license_number,
            'phone_number'   => $request->phone_number,
            'status'         => 'inactive',
        ]);

        return redirect('/drivers')->with('success', 'Driver berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        $driver->load('user', 'vehicle');
        return view('drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        $driver->load('user');
        return view('drivers.edit', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver)
    {
         $request->validate([
            'name'           => 'required|string|max:255',
            'phone_number'   => 'required|string|max:15',
            'license_number' => 'required|string|unique:drivers,license_number,' . $driver->id,
            'status'         => 'required|in:active,inactive',
        ]);

        $driver->user->update([
            'name'         => $request->name,
            'phone_number' => $request->phone_number,
        ]);

        $driver->update([
            'license_number' => $request->license_number,
            'phone_number'   => $request->phone_number,
            'status'         => $request->status,
        ]);

        return redirect('/drivers')->with('success', 'Driver berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        $driver->user->delete(); 
        return redirect('/drivers')->with('success', 'Driver berhasil dihapus.');
    }

    // Approve driver
    public function approve(Driver $driver)
    {
        $driver->update(['status' => 'active']);
    
        // Sekalian verify kendaraannya
        if($driver->vehicle){
            $driver->vehicle->update(['verification_status' => 'verified']);
        }
    
    return redirect('/drivers')->with('success', 'Driver dan kendaraan berhasil diaktifkan.');
}

    // Suspend driver
    public function suspend(Driver $driver)
    {
        $driver->update(['status' => 'inactive']);
        return redirect('/drivers')->with('success', 'Driver berhasil disuspend.');
    }
}
