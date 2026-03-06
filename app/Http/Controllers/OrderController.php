<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function myOrders()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.shoe')
            ->latest()
            ->paginate(10);

        return view('orders.my_orders', compact('orders'));
    }

public function cancelOrder($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('my-orders')->with('success', 'Your order has been cancelled');
    }
}
