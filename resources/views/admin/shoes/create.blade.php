@extends('layouts.admin')

@section('title', 'Add New Shoe')
@section('header', 'Add New Shoe')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Add New Shoe</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.shoes.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div>
                    <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                    <input type="text" name="category" id="category" value="{{ old('category') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="e.g., Running, Casual, Formal" required>
                </div>

                <div>
                    <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div>
                    <label for="deleted_price" class="block text-gray-700 text-sm font-bold mb-2">Discounted Price (optional)</label>
                    <input type="number" name="deleted_price" id="deleted_price" value="{{ old('deleted_price') }}" step="0.01" min="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Leave empty for no discount">
                </div>

                <div>
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Image</label>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp,image/avif" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="best_seller" id="best_seller" value="1" {{ old('best_seller') ? 'checked' : '' }} class="mr-2">
                    <label for="best_seller" class="text-gray-700 text-sm font-bold">Mark as Best Seller</label>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Create Shoe
                </button>
                <a href="{{ route('admin.shoes.index') }}" class="ml-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
