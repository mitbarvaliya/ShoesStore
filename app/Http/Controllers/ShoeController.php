<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ShoeController extends Controller
{
    public function index()
    {
        $shoes = Shoe::latest()->paginate(10);
        return view('admin.shoes.index', compact('shoes'));
    }

    public function create()
    {
        return view('admin.shoes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'deleted_price' => 'nullable|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'best_seller' => 'nullable|boolean',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('shoes'), $imageName);
        }

        Shoe::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'deleted_price' => $request->deleted_price,
            'image' => $imageName,
            'best_seller' => $request->has('best_seller') ? true : false,
        ]);

        return redirect()->route('admin.shoes.index')->with('success', 'Shoe created successfully!');
    }

    public function show(Shoe $shoe)
    {
        return view('admin.shoes.show', compact('shoe'));
    }

    public function edit(Shoe $shoe)
    {
        return view('admin.shoes.edit', compact('shoe'));
    }

    public function update(Request $request, Shoe $shoe)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'deleted_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'best_seller' => 'nullable|boolean',
        ]);

        $shoe->name = $request->name;
        $shoe->category = $request->category;
        $shoe->price = $request->price;
        $shoe->deleted_price = $request->deleted_price;
        $shoe->best_seller = $request->has('best_seller') ? true : false;

        if ($request->hasFile('image')) {
            if ($shoe->image && File::exists(public_path('shoes/' . $shoe->image))) {
                File::delete(public_path('shoes/' . $shoe->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('shoes'), $imageName);
            $shoe->image = $imageName;
        }

        $shoe->save();

        return redirect()->route('admin.shoes.index')->with('success', 'Shoe updated successfully!');
    }

    public function destroy(Shoe $shoe)
    {
        if ($shoe->image && File::exists(public_path('shoes/' . $shoe->image))) {
            File::delete(public_path('shoes/' . $shoe->image));
        }

        $shoe->delete();

        return redirect()->route('admin.shoes.index')->with('success', 'Shoe deleted successfully!');
    }
}
