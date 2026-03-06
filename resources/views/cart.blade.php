@extends('layouts.main')

@section('title', 'Shopping Cart - ShoeStore')

@section('styles')
<style>
    .cart-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        padding: 60px 0;
        margin-bottom: 40px;
    }
    .cart-item {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .cart-item:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .cart-item img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
    }
    .quantity-btn {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        background: white;
        color: #1a1a2e;
        transition: all 0.3s;
    }
    .quantity-btn:hover {
        background: #e94560;
        border-color: #e94560;
        color: white;
    }
    .remove-btn {
        color: #dc3545;
        transition: all 0.3s;
    }
    .remove-btn:hover {
        color: #a71d2a;
        transform: scale(1.1);
    }
    .cart-summary {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .summary-title {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        color: white;
        padding: 20px;
        border-radius: 15px 15px 0 0;
    }
    .price {
        color: #e94560;
        font-weight: 700;
        font-size: 1.1rem;
    }
    .total-price {
        color: #e94560;
        font-weight: 700;
        font-size: 1.5rem;
    }
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-cart i {
        font-size: 5rem;
        color: #dee2e6;
    }
    .alert-custom {
        border-radius: 10px;
        border: none;
    }
</style>
@endsection

@section('content')
<!-- Cart Header -->
<div class="cart-header">
    <div class="container">
        <div class="text-center text-white">
            <h1 class="fw-bold mb-3"><i class="fas fa-shopping-cart me-3"></i>Shopping Cart</h1>
            <p class="mb-0">Review your items before checkout</p>
        </div>
    </div>
</div>

<div class="container pb-5">
    @if(session('success'))
        <div class="alert alert-success alert-custom mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($carts->count() > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                @foreach($carts as $cart)
                    <div class="card cart-item mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                @if($cart->shoe->image)
                                    <img src="{{ asset('shoes/' . $cart->shoe->image) }}" alt="{{ $cart->shoe->name }}">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; border-radius: 10px;">
                                        <i class="fas fa-shoe-prints text-muted fa-2x"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="mb-1">{{ $cart->shoe->name }}</h5>
                                            <p class="text-muted small mb-2"><i class="fas fa-tag me-1"></i>{{ $cart->shoe->category }}</p>
                                            <span class="price">${{ number_format($cart->shoe->price, 2) }}</span>
                                        </div>
                                        <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn remove-btn p-2">
                                                <i class="fas fa-trash fa-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="d-flex align-items-center">
                                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="action" value="decrease">
                                                <button type="submit" class="quantity-btn" {{ $cart->quantity <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </form>
                                            <span class="mx-3 fw-bold">{{ $cart->quantity }}</span>
                                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="action" value="increase">
                                                <button type="submit" class="quantity-btn">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <span class="ms-4 fw-bold">= ${{ number_format($cart->shoe->price * $cart->quantity, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="col-lg-4">
                <div class="card cart-summary">
                    <div class="summary-title">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span class="text-success">Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="total-price">${{ number_format($total, 2) }}</span>
                        </div>
                        <a href="{{ route('checkout') }}" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                        </a>
                        <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100 mt-3">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="empty-cart">
            <i class="fas fa-shopping-cart mb-4"></i>
            <h3 class="text-muted mb-3">Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary px-5 py-3">
                <i class="fas fa-store me-2"></i>Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
