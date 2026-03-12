@extends('layouts.admin')

@section('title', 'View Shoe')
@section('header', 'View Shoe')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">{{ $shoe->name }}</h2>
            <div class="flex gap-3">
                <a href="{{ route('admin.shoes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back
                </a>
                <a href="{{ route('admin.shoes.edit', $shoe->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                @if($shoe->image)
                    <img src="{{ asset('shoes/' . $shoe->image) }}" alt="{{ $shoe->name }}" class="w-full rounded-lg shadow-lg" style="max-height: 500px; object-fit: cover;">
                @else
                    <div class="bg-gray-100 rounded-lg d-flex align-items-center justify-content-center" style="height: 400px;">
                        <span class="text-gray-400">No Image</span>
                    </div>
                @endif
            </div>
            
            <div>
                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Name:</label>
                    <p class="text-lg">{{ $shoe->name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Category:</label>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $shoe->category }}</span>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Price:</label>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($shoe->price, 2) }}</p>
                    @if($shoe->deleted_price)
                        <span class="text-gray-400 line-through text-lg">${{ number_format($shoe->deleted_price, 2) }}</span>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Best Seller:</label>
                    @if($shoe->best_seller)
                        <span class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-fire"></i> Yes
                        </span>
                    @else
                        <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-sm">No</span>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Created At:</label>
                    <p>{{ $shoe->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2">Updated At:</label>
                    <p>{{ $shoe->updated_at->format('M d, Y h:i A') }}</p>
                </div>

                <div class="border-t pt-4 mt-6">
                    <form action="{{ route('admin.shoes.destroy', $shoe->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this shoe?')">
                            Delete Shoe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
