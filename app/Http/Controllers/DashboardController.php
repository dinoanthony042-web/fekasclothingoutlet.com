<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $orders = $user->orders()->with('items.product')->latest()->get();
        $wishlist = $user->wishlists()->with('product')->get();

        return view('dashboard', compact('orders', 'wishlist'));
    }
}
