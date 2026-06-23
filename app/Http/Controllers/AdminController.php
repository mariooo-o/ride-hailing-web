<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalDrivers = Driver::count();
        $activeDrivers = Driver::where('status', 'active')->count();
        $pendingDrivers = Driver::where('status', 'inactive')->count();

        $totalVehicles = Vehicle::count();
        $pendingVehicles = Vehicle::where('verification_status', 'pending')->count();
        $verifiedVehicles = Vehicle::where('verification_status', 'verified')->count();

        $totalCustomers = User::where('role', 'customer')->count();

        return view('admin.dashboard', compact(
            'totalDrivers',
            'activeDrivers',
            'pendingDrivers',
            'totalVehicles',
            'pendingVehicles',
            'verifiedVehicles',
            'totalCustomers'
        ));
    }
}