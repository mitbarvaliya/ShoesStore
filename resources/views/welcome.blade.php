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
    }

    body {
        font-family: 'Space Grotesk', sans-serif;
        background-color: var(--bg-primary);
        color: var(--fg-primary);
    }

    h1, h2, h3, h4, h5 {
        font-family: 'Bebas Neue', sans-serif;
        letter-spacing: 0.05em;
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
        transition: opacity 0.8s ease, visibility 0.8s ease;
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
        color: var(--fg-primary);
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
        color: var(--fg-primary);
        padding: 1rem 2.5rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-primary-custom:hover {
        background: var(--accent-hover);
        color: var(--fg-primary);
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

    .slider-dot.active {
        background: var(--accent);
    }

    /* --- Product Card Styles --- */
    .product-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: all 0.4s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-10px);
        border-color: var(--accent);
        box-shadow: 0 20px 50px rgba(232, 93, 4, 0.1);
    }

    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }

    .product-image {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.3));
    }

    /* The requested hover effect */
    .product-card:hover .product-image {
        transform: scale(1.15) rotate(-5deg);
    }

    .product-badges {
        position: absolute;
        top: 1rem;
        left: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .badge-new {
        background: var(--accent);
        color: var(--fg-primary);
        padding: 0.35rem 1rem;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.05em;
    }

    .badge-sale {
        background: #dc2626;
        color: var(--fg-primary);
        padding: 0.35rem 1rem;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.05em;
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
    }

    .product-card:hover .product-actions {
        opacity: 1;
        transform: translateX(0);
    }

    .action-btn {
        width: 40px;
        height: 40px;
        background: var(--bg-primary);
        border: none;
        color: var(--fg-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: var(--accent);
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

    .product-card:hover .product-title {
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
        font-family: 'Bebas Neue', sans-serif;
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
    }

    .btn-add-cart:hover {
        background: var(--accent);
        border-color: var(--accent);
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
        font-size: 3rem;
        margin-bottom: 0;
    }

    .feature-box {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .feature-box:hover {
        border-color: var(--accent);
        transform: translateY(-5px);
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        background: rgba(232, 93, 4, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .feature-icon i {
        color: var(--accent);
        font-size: 1.5rem;
    }

    /* Reveal Animation */
    .reveal {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s ease;
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            height: 70vh;
            min-height: 500px;
        }
        .slide-title {
            font-size: 2.5rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Slider -->
<section class="hero-section">
    <div class="slider-container">
        
        <!-- Slide 1 (Active by default) -->
        <div class="slide active">
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1920');"></div>
            <div class="slide-overlay"></div>
            <div class="slide-content">
                <div class="container">
                    <span class="slide-tag">NEW COLLECTION</span>
                    <h1 class="slide-title">STEP INTO <span>STYLE</span></h1>
                    <p class="slide-desc">Discover the perfect pair for every occasion. Premium quality shoes at unbeatable prices.</p>
                    <a href="#all-shoes" class="btn btn-primary-custom">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Now
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
                    <span class="slide-tag">LATEST TRENDS</span>
                    <h1 class="slide-title">URBAN <span>COLLECTION</span></h1>
                    <p class="slide-desc">Explore our latest collection of trendy and comfortable footwear.</p>
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
                    <span class="slide-tag">LIMITED OFFER</span>
                    <h1 class="slide-title">SUMMER <span>SALE</span></h1>
                    <p class="slide-desc">Get up to 50% off on selected items. Limited time offer!</p>
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
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4 reveal">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-shipping-fast"></i></div>
                    <h5>Free Shipping</h5>
                    <p class="text-muted mb-0">On orders over $100</p>
                </div>
            </div>
            <div class="col-md-4 reveal">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-undo"></i></div>
                    <h5>Easy Returns</h5>
                    <p class="text-muted mb-0">30-day return policy</p>
                </div>
            </div>
            <div class="col-md-4 reveal">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-headset"></i></div>
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
        <div class="section-header reveal">
            <span class="section-subtitle">TOP RATED</span>
            <h2 class="section-title">Popular Shoes</h2>
        </div>
        <div class="row g-4">
            @foreach($popularShoes as $shoe)
                <div class="col-md-6 col-lg-3 reveal">
                    <article class="product-card">
                        <div class="product-image-wrapper">
                            @if($shoe->image)
                                <img src="{{ asset('shoes/' . $shoe->image) }}" alt="{{ $shoe->name }}" class="product-image">
                            @else
                                <img src="https://via.placeholder.com/400x300?text=No+Image" alt="{{ $shoe->name }}" class="product-image">
                            @endif
                            
                            <div class="product-badges">
                                <span class="badge-new"><i class="fas fa-fire me-1"></i>HOT</span>
                            </div>

                            <div class="product-actions">
                                <button class="action-btn"><i class="far fa-heart"></i></button>
                                <button class="action-btn"><i class="far fa-eye"></i></button>
                            </div>
                        </div>
                        
                        <div class="product-info">
                            <span class="product-category">{{ $shoe->category }}</span>
                            <h3 class="product-title">{{ $shoe->name }}</h3>
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
<section id="all-shoes" class="py-5" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-subtitle">OUR PRODUCTS</span>
            <h2 class="section-title">All Shoes</h2>
        </div>
        
        @if($shoes->count() > 0)
            <div class="row g-4">
                @foreach($shoes as $shoe)
                    <div class="col-md-6 col-lg-3 reveal">
                        <article class="product-card">
                            <div class="product-image-wrapper">
                                @if($shoe->image)
                                    <img src="{{ asset('shoes/' . $shoe->image) }}" alt="{{ $shoe->name }}" class="product-image">
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
                                    <button class="action-btn"><i class="far fa-eye"></i></button>
                                </div>
                            </div>
                            
                            <div class="product-info">
                                <span class="product-category">{{ $shoe->category }}</span>
                                <h3 class="product-title">{{ $shoe->name }}</h3>
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
                <i class="fas fa-shoe-prints fa-3x text-muted mb-3"></i>
                <p class="text-muted fs-5">No shoes available yet.</p>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
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

    // Auto-play
    function startAutoPlay() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    function stopAutoPlay() {
        clearInterval(slideInterval);
    }

    // Event Listeners
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
</script>
@endsection
