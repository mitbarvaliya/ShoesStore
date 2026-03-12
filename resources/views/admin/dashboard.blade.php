@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header', 'Dashboard')

@section('content')
    @php
        $totalShoesSold = \App\Models\OrderItem::sum('quantity');
        $totalSales = \App\Models\OrderItem::selectRaw('SUM(price * quantity) as total')->first()->total ?? 0;
        $salesByCategory = \App\Models\Shoe::select('category')
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->leftJoin('order_items', 'shoes.id', '=', 'order_items.shoe_id')
            ->groupBy('category')
            ->get();
    @endphp
    
    <div class="px-4 py-6 sm:px-0">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg font-medium text-gray-900">Total Users</h3>
                    <p class="mt-2 text-3xl font-bold text-blue-600">{{ \App\Models\User::count() }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg font-medium text-gray-900">Total Shoes</h3>
                    <p class="mt-2 text-3xl font-bold text-green-600">{{ \App\Models\Shoe::count() }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg font-medium text-gray-900">Total Cart Items</h3>
                    <p class="mt-2 text-3xl font-bold text-purple-600">{{ \App\Models\Cart::count() }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg font-medium text-gray-900">Total Shoes Sold</h3>
                    <p class="mt-2 text-3xl font-bold text-orange-600">{{ $totalShoesSold }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <h3 class="text-lg font-medium text-gray-900">Total Sales</h3>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">${{ number_format($totalSales, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-bold mb-4">Sales by Category</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($salesByCategory as $category)
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <h3 class="font-semibold text-gray-800">{{ $category->category ?? 'Uncategorized' }}</h3>
                        <p class="text-2xl font-bold text-blue-600 mt-2">{{ $category->total_sold ?? 0 }} pairs</p>
                    </div>
                @empty
                    <p class="text-gray-500">No sales data available.</p>
                @endforelse
            </div>
        </div>

        <div class="mt-8 bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
            <div class="flex gap-4">
                <a href="{{ route('admin.shoes.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Manage Shoes
                </a>
            </div>
        </div>
    </div>
@endsection
