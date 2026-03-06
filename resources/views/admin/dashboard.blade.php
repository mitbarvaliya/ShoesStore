@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header', 'Dashboard')

@section('content')
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
