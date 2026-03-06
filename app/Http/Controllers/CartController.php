<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shoe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request, Shoe $shoe)
    {
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('shoe_id', $shoe->id)
            ->first();

        if ($existingCart) {
            $existingCart->quantity += 1;
            $existingCart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'shoe_id' => $shoe->id,
                'quantity' => 1,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function index(Request $request)
    {
        $carts = Cart::where('user_id', Auth::id())->with('shoe')->get();
        $total = $carts->sum(function($cart) {
            return $cart->shoe->price * $cart->quantity;
        });

        return view('cart', compact('carts', 'total'));
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id === Auth::id()) {
            $cart->delete();
            return redirect()->back()->with('success', 'Item removed from cart!');
        }

        return redirect()->back()->with('error', 'Unauthorized');
    }

    public function updateQuantity(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $action = $request->input('action');

        if ($action === 'increase') {
            $cart->quantity += 1;
        } elseif ($action === 'decrease') {
            if ($cart->quantity > 1) {
                $cart->quantity -= 1;
            } else {
                $cart->delete();
                return redirect()->back()->with('success', 'Item removed from cart!');
            }
        }

        $cart->save();

        return redirect()->back();
    }
}
