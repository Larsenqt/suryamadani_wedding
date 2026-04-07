<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Item;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalItems = Item::count();
        $totalTestimonials = Testimonial::count();
        $recentOrders = Order::with('user')->latest()->paginate(10);

        return view('admin.dashboard', compact(
            'totalOrders', 
            'pendingOrders', 
            'totalItems', 
            'totalTestimonials', 
            'recentOrders'
        ));
    }
}