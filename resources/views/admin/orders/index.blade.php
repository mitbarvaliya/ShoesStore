@extends('layouts.admin')

@section('title', 'Pending Orders')
@section('header', 'Pending Orders')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Pending Orders</h2>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">#{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ Str::limit($order->address, 30) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">${{ number_format($order->total_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->status == 'pending')
                                    <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs">Pending</span>
                                @elseif($order->status == 'completed')
                                    <span class="bg-green-200 text-green-800 px-2 py-1 rounded text-xs">Completed</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="bg-red-200 text-red-800 px-2 py-1 rounded text-xs">Cancelled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    @if($order->status == 'pending')
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="text-green-600 hover:text-green-800 font-medium">
                                                Complete Order
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">View Order</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
