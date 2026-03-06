@extends('layouts.main')

@section('title', 'ShoeStore - Your One-Stop Shoe Shop')

@section('styles')
<style>
    .hero-slider {
        position: relative;
        height: 500px;
        overflow: hidden;
    }
    .hero-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
    }
    .hero-slide.active {
        opacity: 1;
    }
    .hero-slide::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(26,26,46,0.85) 0%, rgba(22,33,62,0.7) 100%);
    }
    .hero-content {
        position: relative;
        z-index: 2;
        height: 100%;
        display: flex;
        align-items: center;
    }
    .hero-slide h1 {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        animation: fadeInUp 0.8s ease-out;
    }
    .hero-slide p {
        font-size: 1.25rem;
        margin-bottom: 30px;
        animation: fadeInUp 0.8s ease-out 0.2s backwards;
    }
    .hero-slide .btn {
        animation: fadeInUp 0.8s ease-out 0.4s backwards;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .slider-indicators {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }
    .slider-indicators button {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        border: none;
        margin: 0 5px;
        transition: all 0.3s;
    }
    .slider-indicators button.active {
        background: #e94560;
        transform: scale(1.2);
    }
    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s;
    }
    .slider-nav:hover {
        background: #e94560;
    }
    .slider-nav.prev {
        left: 20px;
    }
    .slider-nav.next {
        right: 20px;
    }
    .shoe-card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
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
    .category-section {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    }
    .category-card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s;
    }
    .category-card:hover {
        transform: scale(1.05);
    }
    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(233,69,96,0.8) 0%, rgba(255,107,107,0.8) 100%);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .category-card:hover::before {
        opacity: 1;
    }
    .features-section {
        background: #f8f9fa;
    }
    .feature-box {
        text-align: center;
        padding: 30px;
        border-radius: 15px;
        background: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: all 0.3s;
    }
    .feature-box:hover {
        transform: translateY(-5px);
    }
    .feature-box i {
        font-size: 2.5rem;
        color: #e94560;
        margin-bottom: 15px;
    }
    .section-title {
        position: relative;
        display: inline-block;
        margin-bottom: 50px;
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
</style>
@endsection

@section('content')
<!-- Hero Slider -->
<div class="hero-slider">
    <div class="hero-slide active" style="background: url('https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1920') center/cover;">
        <div class="container">
            <div class="hero-content">
                <div class="row">
                    <div class="col-lg-7">
                        <h1 class="text-white">Step Into Style</h1>
                        <p class="text-white-50">Discover the perfect pair for every occasion. Premium quality shoes at unbeatable prices.</p>
                        <a href="#all-shoes" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-shopping-bag me-2"></i>Shop Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="hero-slide" style="background: url('https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=1920') center/cover;">
        <div class="container">
            <div class="hero-content">
                <div class="row">
                    <div class="col-lg-7">
                        <h1 class="text-white">New Arrivals</h1>
                        <p class="text-white-50">Explore our latest collection of trendy and comfortable footwear.</p>
                        <a href="#all-shoes" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-arrow-right me-2"></i>Explore Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="hero-slide" style="background: url('https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=1920') center/cover;">
        <div class="container">
            <div class="hero-content">
                <div class="row">
                    <div class="col-lg-7">
                        <h1 class="text-white">Summer Sale</h1>
                        <p class="text-white-50">Get up to 50% off on selected items. Limited time offer!</p>
                        <a href="#all-shoes" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-tag me-2"></i>Shop Sale
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <button class="slider-nav prev" onclick="changeSlide(-1)">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button class="slider-nav next" onclick="changeSlide(1)">
        <i class="fas fa-chevron-right"></i>
    </button>
    
    <div class="slider-indicators">
        <button class="active" onclick="goToSlide(0)"></button>
        <button onclick="goToSlide(1)"></button>
        <button onclick="goToSlide(2)"></button>
    </div>
</div>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-shipping-fast"></i>
                    <h5>Free Shipping</h5>
                    <p class="text-muted mb-0">On orders over $100</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-undo"></i>
                    <h5>Easy Returns</h5>
                    <p class="text-muted mb-0">30-day return policy</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-headset"></i>
                    <h5>24/7 Support</h5>
                    <p class="text-muted mb-0">Dedicated customer care</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Shoes Section -->
@if($popularShoes->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Popular Shoes</h2>
            <p class="text-muted">Our most loved styles by customers</p>
        </div>
        <div class="row g-4">
            @foreach($popularShoes as $shoe)
                <div class="col-md-6 col-lg-3">
                    <div class="card shoe-card h-100">
                        <div class="position-relative">
                            @if($shoe->image)
                                <img src="{{ asset('shoes/' . $shoe->image) }}" alt="{{ $shoe->name }}" class="card-img-top">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                                    <i class="fas fa-shoe-prints text-muted fa-3x"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 badge badge-seller text-white m-2 px-2 py-1">
                                <i class="fas fa-fire me-1"></i>Best Seller
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mb-1">{{ $shoe->name }}</h5>
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
</section>
@endif

<!-- All Shoes Section -->
<section id="all-shoes" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">All Shoes</h2>
            <p class="text-muted">Browse our complete collection</p>
        </div>
        
        @if($shoes->count() > 0)
            <div class="row g-4">
                @foreach($shoes as $shoe)
                    <div class="col-md-6 col-lg-3">
                        <div class="card shoe-card h-100">
                            <div class="position-relative">
                                @if($shoe->image)
                                    <img src="{{ asset('shoes/' . $shoe->image) }}" alt="{{ $shoe->name }}" class="card-img-top">
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
                            <div class="card-body">
                                <h5 class="card-title mb-1">{{ $shoe->name }}</h5>
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
        @else
            <div class="text-center py-5">
                <i class="fas fa-shoe-prints text-muted fa-4x mb-3"></i>
                <p class="text-muted fs-5">No shoes available yet.</p>
                <p class="text-muted">Check back soon!</p>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.slider-indicators button');
    
    function showSlide(n) {
        slides.forEach(slide => slide.classList.remove('active'));
        indicators.forEach(ind => ind.classList.remove('active'));
        
        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
        indicators[currentSlide].classList.add('active');
    }
    
    function changeSlide(direction) {
        showSlide(currentSlide + direction);
    }
    
    function goToSlide(n) {
        showSlide(n);
    }
    
    // Auto advance slides
    setInterval(() => {
        changeSlide(1);
    }, 5000);
</script>
@endsection
