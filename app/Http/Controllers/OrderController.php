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
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }
}
