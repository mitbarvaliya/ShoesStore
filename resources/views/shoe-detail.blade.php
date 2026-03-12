@extends('layouts.main')

@section('title', '{{ $shoe->name }} - ShoeStore')

@section('styles')
<style>
    .detail-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        padding: 40px 0;
        margin-bottom: 40px;
    }
    .product-image {
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        object-fit: cover;
    }
    .category-badge {
        background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
    }
    .price-tag {
        color: #e94560;
        font-weight: 700;
        font-size: 2rem;
    }
    .old-price {
        color: #999;
        text-decoration: line-through;
        font-size: 1.25rem;
    }
    .seller-badge {
        background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%);
        color: white;
    }
    .btn-add-cart {
        background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
        color: white;
        padding: 15px 40px;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-add-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(233, 69, 96, 0.4);
        color: white;
    }
</style>
@endsection

@section('content')
<!-- Detail Header -->
<div class="detail-header">
    <div class="container">
        <div class="text-center text-white">
            <h1 class="fw-bold mb-2">{{ $shoe->name }}</h1>
            <p class="mb-0"><a href="{{ route('shop') }}" class="text-white-50 text-decoration-none">Shop</a> / {{ $shoe->name }}</p>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-5">
        <div class="col-lg-6">
            @if($shoe->image)
                <img src="{{ asset('shoes/' . $shoe->image) }}" alt="{{ $shoe->name }}" class="product-image w-100" style="max-height: 500px; object-fit: cover;">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 500px;">
                    <i class="fas fa-shoe-prints text-muted fa-5x"></i>
                </div>
            @endif
        </div>
        <div class="col-lg-6">
            <div class="ps-lg-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="category-badge">{{ $shoe->category }}</span>
                    @if($shoe->best_seller)
                        <span class="badge seller-badge px-3 py-2 rounded-pill">
                            <i class="fas fa-fire me-1"></i>Best Seller
                        </span>
                    @endif
                </div>
                
                <h2 class="fw-bold mb-3">{{ $shoe->name }}</h2>
                
                <div class="mb-4">
                    @if($shoe->deleted_price)
                        <span class="old-price me-2">${{ number_format($shoe->deleted_price, 2) }}</span>
                    @endif
                    <span class="price-tag">${{ number_format($shoe->price, 2) }}</span>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold">Description</h5>
                    <p class="text-muted">Premium quality {{ $shoe->name }} from our {{ $shoe->category }} collection. Designed for comfort and style, this shoe is perfect for any occasion.</p>
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold">Details</h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Category: {{ $shoe->category }}</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Premium Quality</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Comfortable Fit</li>
                        @if($shoe->best_seller)
                            <li class="mb-2"><i class="fas fa-fire text-warning me-2"></i>Best Seller</li>
                        @endif
                    </ul>
                </div>

                <div class="border-top pt-4 mt-4">
                    @auth
                        <form action="{{ route('cart.add', $shoe->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-add-cart">
                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                            </button>
                        </form>
                    @else
                        <button type="button" onclick="openAuthModal()" class="btn btn-add-cart">
                            <i class="fas fa-cart-plus me-2"></i>Add to Cart
                        </button>
                    @endauth
                    
                    <a href="{{ route('shop') }}" class="btn btn-outline-secondary ms-3 px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back to Shop
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
