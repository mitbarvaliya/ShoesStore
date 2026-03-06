@extends('layouts.admin')

@section('title', 'Order Details')
@section('header', 'Order Details')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Order #{{ $order->id }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    Back to Orders
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Customer Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold mb-3">Customer Details</h3>
                <p><span class="font-medium">Name:</span> {{ $order->customer_name }}</p>
                <p><span class="font-medium">Phone:</span> {{ $order->phone }}</p>
                <p><span class="font-medium">Address:</span> {{ $order->address }}</p>
                <p><span class="font-medium">Email:</span> {{ $order->user->email }}</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-3">Order Status</h3>
                <div class="flex items-center gap-4 mb-4">
                    <span class="@if($order->status == 'pending') bg-yellow-200 text-yellow-800 @elseif($order->status == 'processing') bg-blue-200 text-blue-800 @elseif($order->status == 'completed') bg-green-200 text-green-800 @else bg-red-200 text-red-800 @endif px-3 py-1 rounded-full text-sm font-semibold">
                        {{ ucfirst($order->status) }}
                    </span>
                    @if($order->status == 'pending')
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Complete Order
                            </button>
                        </form>
                    @endif
                </div>
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex gap-2">
                    @csrf
                    <select name="status" class="border rounded px-3 py-2">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Update
                    </button>
                </form>
                <p class="text-gray-500 text-sm mt-2">Order Date: {{ $order->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>

        <!-- Ordered Products -->
        <h3 class="text-lg font-semibold mb-3">Ordered Products</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->shoe->image)
                                    <img src="{{ asset('shoes/' . $item->shoe->image) }}" alt="{{ $item->shoe->name }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No Image</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium">{{ $item->shoe->name }}</p>
                                <p class="text-gray-500 text-sm">{{ $item->shoe->category }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right font-bold">Total:</td>
                        <td class="px-6 py-4 font-bold text-lg text-blue-600">${{ number_format($order->total_price, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
