@extends('layouts.main')

@section('title', 'Contact Us - ShoeStore')

@section('styles')
<style>
    .contact-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        padding: 60px 0;
        margin-bottom: 40px;
    }
    .contact-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    .contact-info-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    }
    .info-icon {
        width: 50px;
        height: 50px;
        background: rgba(233, 69, 96, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #e94560;
        font-size: 1.2rem;
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
    .map-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
</style>
@endsection

@section('content')
<!-- Contact Header -->
<div class="contact-header">
    <div class="container">
        <div class="text-center text-white">
            <h1 class="fw-bold mb-3"><i class="fas fa-envelope me-3"></i>Contact Us</h1>
            <p class="mb-0">We'd love to hear from you. Get in touch with us!</p>
        </div>
    </div>
</div>

<div class="container pb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card contact-card">
                <div class="card-body p-4">
                    <h4 class="mb-4"><i class="fas fa-paper-plane me-2" style="color: #e94560;"></i>Send us a Message</h4>
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Your Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter subject" required>
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Message</label>
                                <textarea name="message" id="message" rows="5" class="form-control" placeholder="Write your message here..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 py-3">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="col-lg-5">
            <div class="card contact-info-card text-white h-100">
                <div class="card-body p-4">
                    <h4 class="mb-4"><i class="fas fa-id-card me-2" style="color: #e94560;"></i>Contact Information</h4>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="info-icon me-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Name</h6>
                            <p class="text-white-50 mb-0">Mit Barvaliya</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="info-icon me-3">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Email</h6>
                            <p class="text-white-50 mb-0">meetbarvaliya5@gmail.com</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="info-icon me-3">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Address</h6>
                            <p class="text-white-50 mb-0">Kothariya Chokdi<br>Rajkot-360022</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="info-icon me-3">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Phone</h6>
                            <p class="text-white-50 mb-0">+91 98765 43210</p>
                        </div>
                    </div>

                    <hr style="border-color: rgba(255,255,255,0.2);">

                    <h5 class="mb-3">Follow Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-5"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white fs-5"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white fs-5"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
