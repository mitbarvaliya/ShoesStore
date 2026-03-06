<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShoeStore')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 15px 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar-brand img {
            height: 40px;
            width: auto;
        }
        .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            padding: 8px 15px !important;
            transition: all 0.3s;
            border-radius: 5px;
        }
        .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,0.1);
        }
        .btn-login {
            background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            color: #fff;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 69, 96, 0.4);
            color: #fff;
        }
        .btn-register {
            background: linear-gradient(135deg, #0f3460 0%, #1a1a2e 100%);
            border: 2px solid #e94560;
            padding: 7px 20px;
            border-radius: 25px;
            color: #e94560;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-register:hover {
            background: #e94560;
            color: #fff;
        }
        .cart-icon {
            position: relative;
            color: #fff;
            font-size: 1.2rem;
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e94560;
            color: #fff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }
        footer h5 {
            color: #e94560;
            font-weight: 600;
        }
        footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.3s;
        }
        footer a:hover {
            color: #e94560;
        }
        .btn-subscribe {
            background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
            border: none;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #d63850 0%, #e94560 100%);
            transform: translateY(-2px);
        }
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 10px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #e94560;
            border-radius: 2px;
        }
        .auth-modal .modal-content {
            border-radius: 15px;
            border: none;
        }
        .auth-modal .modal-header {
            border-bottom: 1px solid #eee;
            padding: 20px 25px;
        }
.auth-modal .modal-body {
            padding: 25px;
        }
        .section-title {
            position: relative;
            display: inline-block;
            font-weight: 700;
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
    @yield('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="ShoeStore Logo" height="35">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border-color: rgba(255,255,255,0.5);">
                <span class="navbar-toggler-icon" style="background-image: url('data:image/svg+xml,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 30 30%27%3e%3cpath stroke=%27rgba%28255,255,255,0.85%29%27 stroke-linecap=%27round%27 stroke-miterlimit=%2710%27 stroke-width=%272%27 d=%27M4 7h22M4 15h22M4 23h22%27/%3e%3c/svg%3e');"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop') }}"><i class="fas fa-store me-1"></i> Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}"><i class="fas fa-info-circle me-1"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}"><i class="fas fa-envelope me-1"></i> Contact</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <a href="{{ route('my-orders') }}" class="nav-link"><i class="fas fa-box me-1"></i> My Orders</a>
                        <a href="{{ route('cart.index') }}" class="cart-icon me-3">
                            <i class="fas fa-shopping-cart"></i>
                            @php $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count(); @endphp
                            @if($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-login dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-register">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-white pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="mb-3">
                        <i class="fas fa-shoe-prints me-2" style="color: #e94560;"></i>ShoeStore
                    </h5>
                    <p class="text-white-50">Your one-stop shop for quality shoes. We offer the best brands at competitive prices. Step into comfort and style with our exclusive collection.</p>
                    <div class="mt-3">
                        <a href="#" class="me-3 text-white-50 fs-5"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="me-3 text-white-50 fs-5"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="me-3 text-white-50 fs-5"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white-50 fs-5"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}">Home</a></li>
                        <li class="mb-2"><a href="{{ route('shop') }}">Shop</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}">About Us</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Customer Service</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Contact Us</a></li>
                        <li class="mb-2"><a href="#">FAQ</a></li>
                        <li class="mb-2"><a href="#">Shipping Policy</a></li>
                        <li class="mb-2"><a href="#">Returns & Exchanges</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Newsletter</h5>
                    <p class="text-white-50 mb-3">Subscribe to get special offers and updates</p>
                    <form class="d-flex">
                        <input type="email" class="form-control rounded-start" placeholder="Your email">
                        <button type="submit" class="btn btn-subscribe text-white rounded-end px-4">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <p class="mb-0 text-white-50">&copy; {{ date('Y') }} ShoeStore. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Auth Modal -->
    <div class="modal fade auth-modal" id="authModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Login or Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">Please login or register to add items to cart.</p>
                    <div class="d-grid gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-register">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openAuthModal() {
            var myModal = new bootstrap.Modal(document.getElementById('authModal'));
            myModal.show();
        }
    </script>
    @yield('scripts')
</body>
</html>
