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
        $category = $request->category;
        
        if ($category) {
            $shoes = Shoe::where('category', $category)->latest()->paginate(12);
            $categoryShoes = null;
        } else {
            $shoes = Shoe::latest()->paginate(12);
            $categories = Shoe::whereNotNull('category')
                ->select('category')
                ->distinct()
                ->pluck('category');
            
            $categoryShoes = [];
            foreach ($categories as $cat) {
                $categoryShoes[$cat] = Shoe::where('category', $cat)->latest()->take(4)->get();
            }
        }
        
        return view('shop', compact('shoes', 'categoryShoes', 'category'));
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
