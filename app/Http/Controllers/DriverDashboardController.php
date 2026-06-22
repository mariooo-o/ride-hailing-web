<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $driver = $user->driver;
        return view('driver.dashboard', compact('user', 'driver'));
    }

    public function toggleAvailable()
    {
        $driver = Auth::user()->driver;
        $driver->update(['available' => !$driver->available]);
        return redirect('/driver/dashboard')->with('success', 'Status berhasil diubah.');
    }
}