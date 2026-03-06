<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems.shoe', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['orderItems.shoe', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function completedOrders()
    {
        $orders = Order::with(['orderItems.shoe', 'user'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(10);
        return view('admin.completed_orders', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $order->status]);
        }

        return redirect()->back()->with('success', 'Order status updated to ' . $order->status);
    }
}
