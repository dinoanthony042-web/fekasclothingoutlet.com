<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $orders = $user->orders()->with('items.product')->latest()->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'You do not have permission to view this order.');
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }
}
