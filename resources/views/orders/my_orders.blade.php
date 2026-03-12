@extends('layouts.main')

@section('title', 'My Orders - ShoeStore')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold mb-8">My Orders</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-bold">Order #{{ $order->id }}</span>
                                <span class="ml-4 text-gray-500 text-sm">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div>
                                @if($order->status == 'pending')
                                    <span class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-sm">Pending</span>
                                @elseif($order->status == 'completed')
                                    <span class="bg-green-200 text-green-800 px-3 py-1 rounded-full text-sm">Completed</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="bg-red-200 text-red-800 px-3 py-1 rounded-full text-sm">Cancelled</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-start mb-4">
                                @if($item->shoe->image)
                                    <img src="{{ asset('shoes/' . $item->shoe->image) }}" alt="{{ $item->shoe->name }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No Image</span>
                                    </div>
                                @endif
                                <div class="ml-4 flex-1">
                                    <p class="font-semibold text-gray-800">{{ $item->shoe->name }}</p>
                                    <p class="text-gray-500 text-sm">Qty: {{ $item->quantity }}</p>
                                    <p class="text-gray-600 text-sm font-medium">${{ number_format($item->price, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="mt-4 pt-4 border-t flex justify-between items-center">
                            <div class="text-gray-700 text-sm">
                                <p><strong>Shipping Address:</strong> {{ $order->address }}</p>
                                @if($order->status == 'pending')
                                    <form action="{{ route('order.cancel', $order->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm font-medium" onclick="return confirm('Are you sure you want to cancel this order?')">
                                            Cancel Order
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-gray-500 text-sm">Total</p>
                                <p class="text-2xl font-bold text-blue-600">${{ number_format($order->total_price, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 text-xl">You haven't placed any orders yet.</p>
            <a href="{{ route('shop') }}" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
