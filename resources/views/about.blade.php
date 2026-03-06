@extends('layouts.main')

@section('title', 'About Us - ShoeStore')

@section('styles')
<style>
    .about-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        padding: 60px 0;
        margin-bottom: 40px;
    }
    .about-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    .team-card {
        border-radius: 15px;
        border: none;
        overflow: hidden;
        transition: all 0.3s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    .team-card:hover {
        transform: translateY(-10px);
    }
    .team-card .card-img-top {
        height: 250px;
        object-fit: cover;
    }
    .team-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .value-card {
        border-radius: 15px;
        border: none;
        padding: 30px;
        text-align: center;
        background: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s;
    }
    .value-card:hover {
        transform: translateY(-5px);
    }
    .value-card i {
        font-size: 2.5rem;
        color: #e94560;
        margin-bottom: 15px;
    }
    .stats-section {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    }
    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        color: #e94560;
    }
</style>
@endsection

@section('content')
<!-- About Header -->
<div class="about-header">
    <div class="container">
        <div class="text-center text-white">
            <h1 class="fw-bold mb-3"><i class="fas fa-info-circle me-3"></i>About Us</h1>
            <p class="mb-0">Learn more about our story and mission</p>
        </div>
    </div>
</div>

<div class="container pb-5">
    <!-- Our Story -->
    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="card about-card h-100">
                <div class="card-body p-4">
                    <h3 class="mb-4" style="color: #e94560;"><i class="fas fa-book me-2"></i>Our Story</h3>
                    <p class="text-muted">
                        Welcome to ShoeStore, your premier destination for quality footwear. Founded with a passion for style and comfort, we have been serving our customers with the finest selection of shoes for years.
                    </p>
                    <p class="text-muted">
                        We believe that the right pair of shoes can transform not just your look, but your entire day. That's why we carefully curate our collection to offer you the best in quality, style, and comfort.
                    </p>
                    <p class="text-muted mb-0">
                        Our commitment to customer satisfaction and our love for footwear has made us a trusted name in the industry. We continuously strive to bring you the latest trends and timeless classics.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card about-card h-100">
                <div class="card-body p-4">
                    <h3 class="mb-4" style="color: #e94560;"><i class="fas fa-bullseye me-2"></i>Our Mission</h3>
                    <p class="text-muted">
                        At ShoeStore, our mission is simple: to provide our customers with the highest quality footwear at affordable prices while delivering an exceptional shopping experience.
                    </p>
                    <p class="text-muted">
                        We are dedicated to:
                    </p>
                    <ul class="text-muted">
                        <li class="mb-2">Offering a diverse selection of styles for every occasion</li>
                        <li class="mb-2">Ensuring customer satisfaction with every purchase</li>
                        <li class="mb-2">Providing excellent customer service</li>
                        <li class="mb-2">Staying up-to-date with the latest fashion trends</li>
                        <li class="mb-0">Building long-lasting relationships with our customers</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="text-center mb-5">
        <h2 class="section-title">Why Choose Us</h2>
    </div>
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="value-card">
                <i class="fas fa-shipping-fast"></i>
                <h5>Fast Shipping</h5>
                <p class="text-muted mb-0">Quick delivery on all orders</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="value-card">
                <i class="fas fa-medal"></i>
                <h5>Quality Products</h5>
                <p class="text-muted mb-0">Premium quality footwear</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="value-card">
                <i class="fas fa-headset"></i>
                <h5>24/7 Support</h5>
                <p class="text-muted mb-0">Always here to help you</p>
            </div>
        </div>
    </div>

    <!-- Owner/Developer Info -->
    <div class="text-center mb-5">
        <h2 class="section-title">Meet the Developer</h2>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card team-card">
                <div class="card-body p-4 text-center">
                    <div class="team-icon mb-3">
                        <i class="fas fa-user fa-2x text-white"></i>
                    </div>
                    <h4 class="mb-1">Mit Barvaliya</h4>
                    <p class="text-muted mb-3">Founder & Developer</p>
                    <div class="text-start">
                        <p class="mb-2"><i class="fas fa-envelope me-2" style="color: #e94560;"></i><strong>Email:</strong> meetbarvaliya5@gmail.com</p>
                        <p class="mb-2"><i class="fas fa-map-marker-alt me-2" style="color: #e94560;"></i><strong>Address:</strong> Kothariya Chokdi, Rajkot-360022</p>
                        <p class="mb-0"><i class="fas fa-code me-2" style="color: #e94560;"></i><strong>Role:</strong> Full Stack Developer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-section rounded-3 py-5 mt-5">
        <div class="container">
            <div class="row text-center text-white">
                <div class="col-6 col-md-3 mb-3 mb-md-0">
                    <div class="stat-number">500+</div>
                    <p class="mb-0">Products</p>
                </div>
                <div class="col-6 col-md-3 mb-3 mb-md-0">
                    <div class="stat-number">1000+</div>
                    <p class="mb-0">Happy Customers</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-number">50+</div>
                    <p class="mb-0">Brands</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-number">5+</div>
                    <p class="mb-0">Years Experience</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
