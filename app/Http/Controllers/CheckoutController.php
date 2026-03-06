<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('shoe')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Your cart is empty');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->shoe->price * $item->quantity;
        });

        return view('checkout', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->with('shoe')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Your cart is empty');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->shoe->price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_price' => $total,
            'status' => 'pending',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'shoe_id' => $item->shoe_id,
                'quantity' => $item->quantity,
                'price' => $item->shoe->price,
            ]);
        }

        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('home')->with('success', 'Order placed successfully!');
    }
}
