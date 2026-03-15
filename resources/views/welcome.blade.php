@extends('layouts.main')

@section('title', 'STRIDE - Premium Footwear')

@section('styles')
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --bg-primary: #0d0d0d;
        --bg-secondary: #1a1a1a;
        --bg-card: #141414;
        --fg-primary: #ffffff;
        --fg-secondary: #a0a0a0;
        --accent: #e85d04;
        --accent-hover: #f48c06;
        --border-color: #2a2a2a;
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        --body-font: 'Space Grotesk', sans-serif;
        --heading-font: 'Bebas Neue', sans-serif;
    }

    /* Light Mode Variables */
    body.light-mode {
        --bg-primary: #f8f9fa;
        --bg-secondary: #ffffff;
        --bg-card: #ffffff;
        --fg-primary: #212529;
        --fg-secondary: #6c757d;
        --border-color: #dee2e6;
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    body {
        font-family: var(--body-font);
        background-color: var(--bg-primary);
        color: var(--fg-primary);
        transition: background-color 0.4s ease, color 0.4s ease;
        overflow-x: hidden;
    }

    h1, h2, h3, h4, h5 {
        font-family: var(--heading-font);
        letter-spacing: 0.05em;
    }

    /*--- Utility Classes for Theme ---*/
    .bg-theme-primary { background-color: var(--bg-primary); }
    .bg-theme-secondary { background-color: var(--bg-secondary); }
    .text-theme-primary { color: var(--fg-primary) !important; }
    .text-theme-secondary { color: var(--fg-secondary); }
    .border-theme { border-color: var(--border-color) !important; }

    /* --- Theme Toggle Button --- */
    .theme-toggle-btn {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--fg-primary);
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .theme-toggle-btn:hover {
        background: var(--accent);
        border-color: var(--accent);
        transform: rotate(180deg);
    }

    /* --- Hero Slider Styles --- */
    .hero-section {
        position: relative;
        height: 90vh;
        min-height: 600px;
        overflow: hidden;
    }

    .slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        visibility: hidden;
        transition: opacity 1s ease, visibility 1s ease;
    }

    .slide.active {
        opacity: 1;
        visibility: visible;
    }

    .slide-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        transform: scale(1.1);
        transition: transform 6s ease;
    }

    .slide.active .slide-bg {
        transform: scale(1);
    }

    .slide-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(13, 13, 13, 0.95) 0%, rgba(13, 13, 13, 0.7) 50%, rgba(13, 13, 13, 0.4) 100%);
    }
    
    /* Lighter overlay for light mode */
    body.light-mode .slide-overlay {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 50%, rgba(255, 255, 255, 0.4) 100%);
    }

    .slide-content {
        position: relative;
        z-index: 10;
        height: 100%;
        display: flex;
        align-items: center;
    }

    .slide-tag {
        display: inline-block;
        background: var(--accent);
        color: #fff;
        padding: 0.5rem 1.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        margin-bottom: 1.5rem;
    }

    .slide-title {
        font-size: clamp(3rem, 8vw, 6rem);
        line-height: 0.95;
        margin-bottom: 1.5rem;
        color: var(--fg-primary); /* Adapts to theme */
    }

    .slide-title span {
        color: var(--accent);
    }

    .slide-desc {
        font-size: 1.1rem;
        color: var(--fg-secondary);
        max-width: 500px;
        margin-bottom: 2rem;
    }

    .btn-primary-custom {
        background: var(--accent);
        border: none;
        color: #fff;
        padding: 1rem 2.5rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary-custom:hover {
        background: var(--accent-hover);
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(232, 93, 4, 0.3);
    }

    /* Slider Navigation */
    .slider-nav {
        position: absolute;
        bottom: 50px;
        right: 5%;
        display: flex;
        gap: 1rem;
        z-index: 20;
    }

    .slider-arrow {
        width: 50px;
        height: 50px;
        border: 2px solid var(--border-color);
        background: transparent;
        color: var(--fg-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .slider-arrow:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    .slider-dots {
        position: absolute;
        bottom: 50px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 1rem;
        z-index: 20;
    }

    .slider-dot {
        width: 40px;
        height: 4px;
        background: rgba(255, 255, 255, 0.3);
        cursor: pointer;
        transition: background 0.3s;
    }
    
    body.light-mode .slider-dot {
        background: rgba(0, 0, 0, 0.2);
    }

    .slider-dot.active {
        background: var(--accent);
    }

    /* --- Product Card Styles --- */
    .product-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: var(--card-shadow);
    }

    .product-card:hover {
        transform: translateY(-10px);
        border-color: var(--accent);
        box-shadow: 0 20px 40px rgba(232, 93, 4, 0.15);
    }

    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
        height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    
    body.light-mode .product-image-wrapper {
        background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
    }

    .product-image {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.3));
    }

    /* Hover Effect */
    .product-card:hover .product-image {
        transform: scale(1.1) rotate(-5deg);
    }

    .product-badges {
        position: absolute;
        top: 1rem;
        left: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        z-index: 2;
    }

    .badge-new {
        background: var(--accent);
        color: #fff;
        padding: 0.35rem 1rem;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .badge-sale {
        background: #dc2626;
        color: #fff;
        padding: 0.35rem 1rem;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .product-actions {
        position: absolute;
        top: 1rem;
        right: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.4s ease;
        z-index: 2;
    }

    .product-card:hover .product-actions {
        opacity: 1;
        transform: translateX(0);
    }

    .action-btn {
        width: 40px;
        height: 40px;
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        color: var(--fg-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    .product-info {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-category {
        color: var(--accent);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .product-title {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        transition: color 0.3s ease;
    }

    .product-card:hover .product-title a {
        color: var(--accent);
    }

    .product-price {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: auto;
        padding-top: 1rem;
    }

    .current-price {
        font-family: var(--heading-font);
        font-size: 1.5rem;
        color: var(--accent);
    }

    .original-price {
        font-size: 0.9rem;
        color: var(--fg-secondary);
        text-decoration: line-through;
    }

    .btn-add-cart {
        width: 100%;
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--fg-primary);
        padding: 0.75rem;
        margin-top: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
    }

    .btn-add-cart:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    /* --- Sections --- */
    .section-header {
        margin-bottom: 4rem;
        text-align: center;
    }

    .section-subtitle {
        color: var(--accent);
        font-weight: 600;
        letter-spacing: 0.2em;
        margin-bottom: 0.5rem;
        display: block;
    }

    .section-title {
        font-size: 3.5rem;
        margin-bottom: 0;
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
        height: 3px;
        background: var(--accent);
    }

    .feature-box {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        padding: 2.5rem 2rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .feature-box:hover {
        border-color: var(--accent);
        transform: translateY(-5px);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        background: rgba(232, 93, 4, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: all 0.3s;
    }

    .feature-box:hover .feature-icon {
        background: var(--accent);
    }
    
    .feature-box:hover .feature-icon i {
        color: #fff;
    }

    .feature-icon i {
        color: var(--accent);
        font-size: 1.75rem;
        transition: color 0.3s;
    }

    /* Newsletter */
    .newsletter-section {
        background: var(--accent);
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .newsletter-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('https://images.unsplash.com/photo-1460353581641-37baddab0fa2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') center/cover no-repeat;
        opacity: 0.1;
    }

    .newsletter-form input {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        padding: 1rem 1.5rem;
        height: auto;
    }
    
    .newsletter-form input::placeholder { color: rgba(255,255,255,0.7); }
    .newsletter-form input:focus { box-shadow: 0 0 0 3px rgba(255,255,255,0.2); background: rgba(255,255,255,0.3); }

    /* Footer */
    .footer {
        background: var(--bg-secondary);
        padding-top: 5rem;
    }
    
    .footer-links a {
        color: var(--fg-secondary);
        text-decoration: none;
        transition: color 0.3s;
        display: block;
        margin-bottom: 0.75rem;
    }
    
    .footer-links a:hover { color: var(--accent); }
    
    .social-icons a {
        width: 40px;
        height: 40px;
        border: 1px solid var(--border-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--fg-primary);
        margin-right: 0.5rem;
        transition: all 0.3s;
    }
    
    .social-icons a:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    /* Scroll to Top */
    .scroll-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 999;
        box-shadow: 0 5px 15px rgba(232, 93, 4, 0.4);
    }
    
    .scroll-top.active { opacity: 1; visibility: visible; }
    .scroll-top:hover { transform: translateY(-5px); }

    /* Reveal Animation */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
    }
    .reveal.active { opacity: 1; transform: translateY(0); }
    .delay-1 { transition-delay: 0.1s; }
    .delay-2 { transition-delay: 0.2s; }
    .delay-3 { transition-delay: 0.3s; }
    .delay-4 { transition-delay: 0.4s; }

    /* Responsive */
    @media (max-width: 992px) {
        .section-title { font-size: 2.5rem; }
    }

    @media (max-width: 768px) {
        .hero-section { height: 70vh; min-height: 500px; }
        .slide-title { font-size: 2.5rem; }
        .slider-dots { bottom: 20px; }
        .slider-nav { bottom: 20px; right: 20px; }
        .slider-arrow { width: 40px; height: 40px; }
        .theme-toggle-btn { top: 15px; right: 15px; width: 40px; height: 40px; }
        .footer { text-align: center; }
    }
</style>
@endsection

@section('content')

<!-- Theme Toggle Button -->
<button id="themeToggle" class="theme-toggle-btn" title="Toggle Theme">
    <i class="fas fa-moon"></i>
</button>

<!-- Hero Slider -->
<section class="hero-section">
    <div class="slider-container">
        
        <!-- Slide 1 (Active by default) -->
        <div class="slide active">
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1920');"></div>
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <div class="container">
                    <span class="slide-tag">NEW COLLECTION 2024</span>
                    <h1 class="slide-title">STEP INTO <span>FUTURE</span></h1>
                    <p class="slide-desc">Discover the perfect pair for every occasion. Premium quality shoes designed for the modern lifestyle.</p>
                    <a href="#all-shoes" class="btn btn-primary-custom">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Collection
                    </a>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="slide">
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=1920');"></div>
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <div class="container">
                    <span class="slide-tag">LIMITED EDITION</span>
                    <h1 class="slide-title">URBAN <span>LEGEND</span></h1>
                    <p class="slide-desc">Explore our latest collection of trendy and comfortable footwear crafted with precision.</p>
                    <a href="#all-shoes" class="btn btn-primary-custom">
                        <i class="fas fa-arrow-right me-2"></i>Explore Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="slide">
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=1920');"></div>
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <div class="container">
                    <span class="slide-tag">MEGA SALE</span>
                    <h1 class="slide-title">SUMMER <span>SALE</span></h1>
                    <p class="slide-desc">Get up to 50% off on selected items. Limited time offer while stocks last!</p>
                    <a href="#all-shoes" class="btn btn-primary-custom">
                        <i class="fas fa-tag me-2"></i>Shop Sale
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Slider Navigation -->
    <div class="slider-dots">
        <div class="slider-dot active" data-slide="0"></div>
        <div class="slider-dot" data-slide="1"></div>
        <div class="slider-dot" data-slide="2"></div>
    </div>

    <div class="slider-nav">
        <button class="slider-arrow" id="prevSlide"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-arrow" id="nextSlide"><i class="fas fa-chevron-right"></i></button>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-theme-secondary">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4 reveal delay-1">
                <div class="feature-box h-100">
                    <div class="feature-icon"><i class="fas fa-shipping-fast"></i></div>
                    <h5 class="text-theme-primary">Free Shipping</h5>
                    <p class="text-theme-secondary mb-0">Free shipping on all orders over $100. Fast and reliable delivery.</p>
                </div>
            </div>
            <div class="col-md-4 reveal delay-2">
                <div class="feature-box h-100">
                    <div class="feature-icon"><i class="fas fa-sync-alt"></i></div>
                    <h5 class="text-theme-primary">Easy Returns</h5>
                    <p class="text-theme-secondary mb-0">30-day return policy. No questions asked, just smooth returns.</p>
                </div>
            </div>
            <div class="col-md-4 reveal delay-3">
                <div class="feature-box h-100">
                    <div class="feature-icon"><i class="fas fa-headset"></i></div>
                    <h5 class="text-theme-primary">24/7 Support</h5>
                    <p class="text-theme-secondary mb-0">Dedicated customer care team ready to assist you anytime.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="py-5 bg-theme-primary">
    <div class="container">
        <form action="{{ route('shop') }}" method="GET" class="search-filter d-flex flex-wrap gap-4 align-items-center justify-content-center">
            
            <!-- Search Input Group -->
            <div style="min-width: 280px;">
                <div class="input-group">
                    <input type="text" name="search" class="form-control border-end-0" placeholder="Search shoes..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Category Buttons with Gap -->
            <div class="d-flex flex-wrap gap-2 justify-content-center" role="group">
                <!-- All Button -->
                <a href="{{ route('shop') }}" 
                   class="btn filter-btn {{ !request('category') ? 'active' : '' }}">
                    All
                </a>

                <!-- Men Button -->
                <a href="{{ route('shop', ['category' => 'Men']) }}" 
                   class="btn filter-btn {{ request('category') == 'Men' ? 'active' : '' }}">
                    Men
                </a>

                <!-- Women Button -->
                <a href="{{ route('shop', ['category' => 'Women']) }}" 
                   class="btn filter-btn {{ request('category') == 'Women' ? 'active' : '' }}">
                    Women
                </a>

                <!-- Children Button -->
                <a href="{{ route('shop', ['category' => 'Children']) }}" 
                   class="btn filter-btn {{ request('category') == 'Children' ? 'active' : '' }}">
                    Children
                </a>
            </div>

        </form>
    </div>
</section>
<!-- Popular Shoes Section -->
@if($popularShoes->count() > 0)
<section class="py-6 bg-theme-primary">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-subtitle">TOP RATED</span>
            <h2 class="section-title">Popular Shoes</h2>
        </div>
        <div class="row g-4">
            @foreach($popularShoes as $index => $shoe)
                <div class="col-6 col-lg-3 reveal delay-{{ $index % 4 + 1 }}">
                    <article class="product-card">
                        <a href="{{ route('shoe.detail', $shoe->id) }}">
                            <div class="product-image-wrapper">
                                @if($shoe->image)
                                    <img src="{{ $shoe->image_url }}" alt="{{ $shoe->name }}" class="product-image">
                                @else
                                    <img src="https://via.placeholder.com/400x300?text=No+Image" alt="{{ $shoe->name }}" class="product-image">
                                @endif
                                
                                <div class="product-badges">
                                    <span class="badge-new"><i class="fas fa-fire me-1"></i>HOT</span>
                                </div>

                                <div class="product-actions">
                                    <button class="action-btn"><i class="far fa-heart"></i></button>
                                    <a href="{{ route('shoe.detail', $shoe->id) }}" class="action-btn"><i class="far fa-eye"></i></a>
                                </div>
                            </div>
                        </a>
                        
                        <div class="product-info">
                            <span class="product-category">{{ $shoe->category }}</span>
                            <h3 class="product-title">
                                <a href="{{ route('shoe.detail', $shoe->id) }}" class="text-decoration-none text-theme-primary">{{ $shoe->name }}</a>
                            </h3>
                            <div class="product-price">
                                <span class="current-price">${{ number_format($shoe->price, 2) }}</span>
                                @if($shoe->deleted_price)
                                    <span class="original-price">${{ number_format($shoe->deleted_price, 2) }}</span>
                                @endif
                            </div>
                            
                            @auth
                                <form action="{{ route('cart.add', $shoe->id) }}" method="POST">
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
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- All Shoes Section -->
<section id="all-shoes" class="py-6 bg-theme-secondary">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-subtitle">OUR PRODUCTS</span>
            <h2 class="section-title">All Shoes</h2>
        </div>
        
        @if($shoes->count() > 0)
            <div class="row g-4">
                @foreach($shoes as $index => $shoe)
                    <div class="col-6 col-lg-3 reveal delay-{{ $index % 4 + 1 }}">
                        <article class="product-card">
                            <a href="{{ route('shoe.detail', $shoe->id) }}">
                                <div class="product-image-wrapper">
                                    @if($shoe->image)
                                        <img src="{{ $shoe->image_url }}" alt="{{ $shoe->name }}" class="product-image">
                                    @else
                                        <img src="https://via.placeholder.com/400x300?text=No+Image" alt="{{ $shoe->name }}" class="product-image">
                                    @endif
                                    
                                    <div class="product-badges">
                                        @if($shoe->best_seller)
                                            <span class="badge-new"><i class="fas fa-fire me-1"></i>BEST</span>
                                        @endif
                                        @if($shoe->deleted_price)
                                            <span class="badge-sale">SALE</span>
                                        @endif
                                    </div>

                                    <div class="product-actions">
                                        <button class="action-btn"><i class="far fa-heart"></i></button>
                                        <a href="{{ route('shoe.detail', $shoe->id) }}" class="action-btn"><i class="far fa-eye"></i></a>
                                    </div>
                                </div>
                            </a>
                            
                            <div class="product-info">
                                <span class="product-category">{{ $shoe->category }}</span>
                                <h3 class="product-title">
                                    <a href="{{ route('shoe.detail', $shoe->id) }}" class="text-decoration-none text-theme-primary">{{ $shoe->name }}</a>
                                </h3>
                                <div class="product-price">
                                    <span class="current-price">${{ number_format($shoe->price, 2) }}</span>
                                    @if($shoe->deleted_price)
                                        <span class="original-price">${{ number_format($shoe->deleted_price, 2) }}</span>
                                    @endif
                                </div>
                                
                                @auth
                                    <form action="{{ route('cart.add', $shoe->id) }}" method="POST">
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
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-shoe-prints fa-3x text-theme-secondary mb-3"></i>
                <p class="text-theme-secondary fs-5">No shoes available yet.</p>
            </div>
        @endif
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container position-relative">
        <div class="row justify-content-center text-center text-white">
            <div class="col-lg-6">
                <h2 class="mb-3 text-white" style="font-size: 2.5rem;">JOIN OUR CLUB</h2>
                <p class="mb-4 opacity-75">Subscribe to get special offers, free giveaways, and once-in-a-lifetime deals.</p>
                <form class="newsletter-form d-flex gap-2 flex-column flex-sm-row">
                    <input type="email" class="form-control flex-grow-1" placeholder="Enter your email address">
                    <button type="submit" class="btn btn-light px-4 py-3 fw-bold text-uppercase">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row g-4 pb-5">
            <div class="col-lg-4 col-md-6 reveal">
                <h3 class="text-theme-primary mb-3" style="font-size: 2rem;">STRIDE</h3>
                <p class="text-theme-secondary">Premium footwear for the modern individual. Style, comfort, and quality crafted for excellence.</p>
                <div class="social-icons mt-4">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 reveal delay-1">
                <h5 class="text-theme-primary mb-3">Quick Links</h5>
                <div class="footer-links">
                    <a href="#">Home</a>
                    <a href="#all-shoes">Shop</a>
                    <a href="#">About Us</a>
                    <a href="#">Contact</a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 reveal delay-2">
                <h5 class="text-theme-primary mb-3">Support</h5>
                <div class="footer-links">
                    <a href="#">FAQ</a>
                    <a href="#">Shipping Info</a>
                    <a href="#">Returns</a>
                    <a href="#">Size Guide</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 reveal delay-3">
                <h5 class="text-theme-primary mb-3">Contact Info</h5>
                <ul class="list-unstyled text-theme-secondary">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-3 text-theme-secondary"></i>123 Fashion Street, NYC</li>
                    <li class="mb-2"><i class="fas fa-phone me-3 text-theme-secondary"></i>+1 234 567 890</li>
                    <li class="mb-2"><i class="fas fa-envelope me-3 text-theme-secondary"></i>hello@stride.com</li>
                </ul>
            </div>
        </div>
        <div class="border-top border-theme py-4 text-center">
            <p class="text-theme-secondary mb-0">&copy; 2024 STRIDE. All Rights Reserved. Designed with <i class="fas fa-heart text-danger"></i></p>
        </div>
    </div>
</footer>

<!-- Scroll to Top Button -->
<button class="scroll-top" id="scrollTop">
    <i class="fas fa-chevron-up"></i>
</button>

@endsection

@section('scripts')
<script>
    // --- Theme Toggle Logic ---
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;
    const icon = themeToggle.querySelector('i');

    // Check for saved theme preference
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'light') {
        body.classList.add('light-mode');
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    }

    themeToggle.addEventListener('click', () => {
        body.classList.toggle('light-mode');
        
        if (body.classList.contains('light-mode')) {
            localStorage.setItem('theme', 'light');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            localStorage.setItem('theme', 'dark');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    });

    // --- Slider Logic ---
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    const totalSlides = slides.length;
    let slideInterval;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        currentSlide = (index + totalSlides) % totalSlides;
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    function startAutoPlay() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    function stopAutoPlay() {
        clearInterval(slideInterval);
    }

    document.getElementById('nextSlide').addEventListener('click', () => {
        stopAutoPlay();
        nextSlide();
        startAutoPlay();
    });

    document.getElementById('prevSlide').addEventListener('click', () => {
        stopAutoPlay();
        prevSlide();
        startAutoPlay();
    });

    dots.forEach(dot => {
        dot.addEventListener('click', (e) => {
            stopAutoPlay();
            showSlide(parseInt(e.target.dataset.slide));
            startAutoPlay();
        });
    });

    startAutoPlay();


    // --- Scroll Reveal Animation ---
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal').forEach(el => {
        observer.observe(el);
    });

    // --- Scroll to Top Logic ---
    const scrollTopBtn = document.getElementById('scrollTop');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollTopBtn.classList.add('active');
        } else {
            scrollTopBtn.classList.remove('active');
        }
    });

    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>
@endsection