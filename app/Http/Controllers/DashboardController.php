<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Room;

class DashboardController extends Controller
{
   public function index()
{
    $totalUsers = User::where('role', 'user')->count();

    $totalBookings = Booking::count();
    $availableRooms = Room::where('status', 'available')->count();

  
    $recentBookings = Booking::latest()->take(5)->get();

    return view('dashboard', compact('totalUsers', 'totalBookings', 'availableRooms', 'recentBookings'));
}

}
