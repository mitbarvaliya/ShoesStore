@extends('layouts.admin')

@section('title', 'Manage Shoes')
@section('header', 'Manage Shoes')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">All Shoes</h2>
            <a href="{{ route('admin.shoes.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Add New Shoe
            </a>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Best Seller</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($shoes as $shoe)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $shoe->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($shoe->image)
                                    <img src="{{ asset('shoes/' . $shoe->image) }}" alt="{{ $shoe->name }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <span class="text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $shoe->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $shoe->category }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${{ number_format($shoe->price, 2) }}
                                @if ($shoe->deleted_price)
                                    <span class="text-gray-400 line-through text-sm">${{ number_format($shoe->deleted_price, 2) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($shoe->best_seller)
                                    <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs">Yes</span>
                                @else
                                    <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded text-xs">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.shoes.edit', $shoe->id) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                <form action="{{ route('admin.shoes.destroy', $shoe->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No shoes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $shoes->links() }}
        </div>
    </div>
@endsection
