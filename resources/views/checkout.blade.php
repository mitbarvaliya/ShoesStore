@extends('layouts.main')

@section('title', 'Checkout - ShoeStore')

@section('styles')
<style>
    .checkout-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        padding: 60px 0;
        margin-bottom: 40px;
    }
    .checkout-form {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .form-title {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        color: white;
        padding: 20px;
        border-radius: 15px 15px 0 0;
    }
    .form-control {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #dee2e6;
    }
    .form-control:focus {
        border-color: #e94560;
        box-shadow: 0 0 0 0.2rem rgba(233, 69, 96, 0.15);
    }
    .form-label {
        font-weight: 500;
        color: #1a1a2e;
    }
    .order-summary {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .summary-title {
        background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
        color: white;
        padding: 20px;
        border-radius: 15px 15px 0 0;
    }
    .order-item {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    .order-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .order-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }
    .total-price {
        color: #e94560;
        font-weight: 700;
        font-size: 1.5rem;
    }
    .price {
        color: #1a1a2e;
        font-weight: 600;
    }
    .input-icon {
        position: relative;
    }
    .input-icon i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    .input-icon .form-control {
        padding-left: 40px;
    }
    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }
    .step {
        display: flex;
        align-items: center;
        color: #6c757d;
    }
    .step.active {
        color: #e94560;
    }
    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        font-weight: 600;
    }
    .step.active .step-number {
        background: #e94560;
        color: white;
    }
    .step-line {
        width: 50px;
        height: 2px;
        background: #dee2e6;
        margin: 0 15px;
    }
</style>
@endsection

@section('content')
<!-- Checkout Header -->
<div class="checkout-header">
    <div class="container">
        <div class="text-center text-white">
            <h1 class="fw-bold mb-3"><i class="fas fa-credit-card me-3"></i>Checkout</h1>
            <p class="mb-0">Complete your order</p>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <!-- Checkout Form -->
        <div class="col-lg-7">
            <div class="card checkout-form">
                <div class="form-title">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Customer Details</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" class="form-control" placeholder="Enter your full name" required>
                            </div>
                            @error('customer_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <div class="input-icon">
                                <i class="fas fa-phone"></i>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control" placeholder="Enter your phone number" required>
                            </div>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">Address</label>
                            <div class="input-icon">
                                <i class="fas fa-home" style="top: 15px;"></i>
                                <textarea name="address" id="address" rows="3" class="form-control" placeholder="Enter your delivery address" required>{{ old('address') }}</textarea>
                            </div>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-check-circle me-2"></i>Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-5">
            <div class="card order-summary">
                <div class="summary-title">
                    <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Order Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="order-items">
                        @foreach($cartItems as $item)
                            <div class="order-item d-flex align-items-center">
                                @if($item->shoe->image)
                                    <img src="{{ asset('shoes/' . $item->shoe->image) }}" alt="{{ $item->shoe->name }}">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 8px;">
                                        <i class="fas fa-shoe-prints text-muted"></i>
                                    </div>
                                @endif
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-0">{{ $item->shoe->name }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                </div>
                                <span class="price">${{ number_format($item->shoe->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping</span>
                        <span class="text-success">Free</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total</span>
                        <span class="total-price">${{ number_format($total, 2) }}</span>
                    </div>

                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
