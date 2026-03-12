<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $popularShoes = Shoe::where('best_seller', true)->latest()->get();
        $shoes = Shoe::latest()->paginate(8);
        
        return view('welcome', compact('popularShoes', 'shoes'));
    }

    public function shop(Request $request)
    {
        $query = Shoe::query();

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $shoes = $query->latest()->paginate(12);
        return view('shop', compact('shoes'));
    }

    public function shoeDetail(Shoe $shoe)
    {
        return view('shoe-detail', compact('shoe'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        return redirect()->route('contact')->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    public function about()
    {
        return view('about');
    }
}
