@extends('layouts.main')

@section('title', 'Shop - ShoeStore')

@section('styles')
<style>
    .shop-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        padding: 60px 0;
        margin-bottom: 40px;
    }
    .shoe-card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .shoe-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    .shoe-card .card-img-top {
        height: 220px;
        object-fit: cover;
    }
    .shoe-card .badge-seller {
        background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
    }
    .shoe-card .price {
        color: #e94560;
        font-weight: 700;
        font-size: 1.25rem;
    }
    .shoe-card .old-price {
        color: #999;
        text-decoration: line-through;
        font-size: 0.9rem;
    }
    .pagination {
        margin-top: 40px;
    }
    .pagination .page-link {
        color: #1a1a2e;
        border: none;
        border-radius: 8px;
        margin: 0 3px;
        padding: 8px 15px;
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
        color: white;
    }
    .pagination .page-link:hover {
        background: #f8f9fa;
        color: #e94560;
    }
    .section-title {
        position: relative;
        display: inline-block;
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
        border-radius: 2px;
    }
    .category-section {
        margin-bottom: 60px;
    }
    .category-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 30px;
        padding-bottom: 10px;
        border-bottom: 3px solid #e94560;
        display: inline-block;
    }
    .view-all-btn {
        background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .view-all-btn:hover {
        transform: scale(1.05);
        color: white;
    }
</style>
@endsection

@section('content')
<!-- Shop Header -->
<div class="shop-header">
    <div class="container">
        <div class="text-center text-white">
            <h1 class="fw-bold mb-3">Shop</h1>
            <p class="mb-0">Browse our complete collection of quality shoes</p>
        </div>
    </div>
</div>

<div class="container pb-5">
<!-- Search and Filter -->
<div class="mb-4 filter-form">
    <form action="{{ route('shop') }}" method="GET" class="d-flex flex-wrap gap-3 align-items-center">
        
        <!-- Search Input -->
        <div class="flex-grow-1" style="max-width: 400px;">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search shoes..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-search">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- Category Buttons with Gap -->
        <div class="d-flex flex-wrap gap-2" role="group">
            <!-- All -->
            <a href="{{ route('shop') }}" 
               class="btn filter-btn {{ !$category ? 'active' : '' }}">
                All
            </a>

            <!-- Men -->
            <a href="{{ route('shop', ['category' => 'Men']) }}" 
               class="btn filter-btn {{ $category == 'Men' ? 'active' : '' }}">
                Men
            </a>

            <!-- Women -->
            <a href="{{ route('shop', ['category' => 'Women']) }}" 
               class="btn filter-btn {{ $category == 'Women' ? 'active' : '' }}">
                Women
            </a>

            <!-- Children -->
            <a href="{{ route('shop', ['category' => 'Children']) }}" 
               class="btn filter-btn {{ $category == 'Children' ? 'active' : '' }}">
                Children
            </a>
        </div>
    </form>
</div>

@if($category)
    {{-- Single Category View --}}
    @if($shoes->count() > 0)
        <div class="row g-4">
            @foreach($shoes as $shoe)
                <div class="col-md-6 col-lg-3">
                    <div class="card shoe-card h-100">
                        <a href="{{ route('shoe.detail', $shoe->id) }}">
                            <div class="position-relative">
                                @if($shoe->image)
                                    <img src="{{ $shoe->image_url }}" alt="{{ $shoe->name }}" class="card-img-top">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                                        <i class="fas fa-shoe-prints text-muted fa-3x"></i>
                                    </div>
                                @endif
                                @if($shoe->best_seller)
                                    <span class="position-absolute top-0 end-0 badge badge-seller text-white m-2 px-2 py-1">
                                        <i class="fas fa-fire me-1"></i>Best Seller
                                    </span>
                                @endif
                            </div>
                        </a>
                        <div class="card-body">
                            <a href="{{ route('shoe.detail', $shoe->id) }}" class="text-decoration-none">
                                <h5 class="card-title mb-1 text-dark">{{ $shoe->name }}</h5>
                            </a>
                            <p class="text-muted small mb-2"><i class="fas fa-tag me-1"></i>{{ $shoe->category }}</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    @if($shoe->deleted_price)
                                        <span class="old-price">${{ number_format($shoe->deleted_price, 2) }}</span>
                                    @endif
                                    <span class="price">${{ number_format($shoe->price, 2) }}</span>
                                </div>
                            </div>
                            @auth
                                <form action="{{ route('cart.add', $shoe->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                    </button>
                                </form>
                            @else
                                <button type="button" onclick="openAuthModal()" class="btn btn-primary w-100">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $shoes->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shoe-prints text-muted fa-4x mb-3"></i>
            <p class="text-muted fs-5">No shoes available in this category.</p>
            <p class="text-muted">Check back soon!</p>
        </div>
    @endif

@else
    {{-- Category Wise View --}}
    @if($categoryShoes && count($categoryShoes) > 0)
        @foreach($categoryShoes as $cat => $shoesInCategory)
            @if($shoesInCategory->count() > 0)
                <div class="category-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="category-title">{{ $cat }}</h2>
                        <a href="{{ route('shop', ['category' => $cat]) }}" class="view-all-btn">View All</a>
                    </div>
                    
                    <div class="row g-4">
                        @foreach($shoesInCategory as $shoe)
                            <div class="col-md-6 col-lg-3">
                                <div class="card shoe-card h-100">
                                    <a href="{{ route('shoe.detail', $shoe->id) }}">
                                        <div class="position-relative">
                                            @if($shoe->image)
                                                <img src="{{ $shoe->image_url }}" alt="{{ $shoe->name }}" class="card-img-top">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                                                    <i class="fas fa-shoe-prints text-muted fa-3x"></i>
                                                </div>
                                            @endif
                                            @if($shoe->best_seller)
                                                <span class="position-absolute top-0 end-0 badge badge-seller text-white m-2 px-2 py-1">
                                                    <i class="fas fa-fire me-1"></i>Best Seller
                                                </span>
                                            @endif
                                        </div>
                                    </a>
                                    <div class="card-body">
                                        <a href="{{ route('shoe.detail', $shoe->id) }}" class="text-decoration-none">
                                            <h5 class="card-title mb-1 text-dark">{{ $shoe->name }}</h5>
                                        </a>
                                        <p class="text-muted small mb-2"><i class="fas fa-tag me-1"></i>{{ $shoe->category }}</p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                @if($shoe->deleted_price)
                                                    <span class="old-price">${{ number_format($shoe->deleted_price, 2) }}</span>
                                                @endif
                                                <span class="price">${{ number_format($shoe->price, 2) }}</span>
                                            </div>
                                        </div>
                                        @auth
                                            <form action="{{ route('cart.add', $shoe->id) }}" method="POST" class="mt-3">
                                                @csrf
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" onclick="openAuthModal()" class="btn btn-primary w-100">
                                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                            </button>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <div class="text-center py-5">
            <i class="fas fa-shoe-prints text-muted fa-4x mb-3"></i>
            <p class="text-muted fs-5">No shoes available yet.</p>
            <p class="text-muted">Check back soon!</p>
        </div>
    @endif
@endif
</div>
@endsection
