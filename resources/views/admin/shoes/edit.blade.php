@extends('layouts.admin')

@section('title', 'Edit Shoe')
@section('header', 'Edit Shoe')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Edit Shoe</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.shoes.update', $shoe->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $shoe->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div>
                    <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                    <select name="category" id="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Select Category</option>
                        <option value="Men" {{ old('category', $shoe->category) == 'Men' ? 'selected' : '' }}>Men</option>
                        <option value="Women" {{ old('category', $shoe->category) == 'Women' ? 'selected' : '' }}>Women</option>
                        <option value="Children" {{ old('category', $shoe->category) == 'Children' ? 'selected' : '' }}>Children</option>
                    </select>
                </div>

                <div>
                    <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $shoe->price) }}" step="0.01" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div>
                    <label for="deleted_price" class="block text-gray-700 text-sm font-bold mb-2">Discounted Price (optional)</label>
                    <input type="number" name="deleted_price" id="deleted_price" value="{{ old('deleted_price', $shoe->deleted_price) }}" step="0.01" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div>
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Image (leave empty to keep current)</label>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp,image/avif" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @if ($shoe->image)
                        <div class="mt-2">
                            <img src="{{ $shoe->image_url }}" alt="{{ $shoe->name }}" class="w-24 h-24 object-cover rounded">
                            <p class="text-sm text-gray-500 mt-1">Current image</p>
                        </div>
                    @endif
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="best_seller" id="best_seller" value="1" {{ old('best_seller', $shoe->best_seller) ? 'checked' : '' }} class="mr-2">
                    <label for="best_seller" class="text-gray-700 text-sm font-bold">Mark as Best Seller</label>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Shoe
                </button>
                <a href="{{ route('admin.shoes.index') }}" class="ml-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
