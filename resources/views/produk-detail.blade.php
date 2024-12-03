@extends("layouts.app")

@section("wrapper")
    <div class="page-wrapper">
        <div class="page-content">
            <div class="container">
                <div class="main-body">
                    <div class="row justify-content-center mb-5">
                        <div class="col-12 text-center">
                            <h3 class="display-4 font-weight-bold text-primary animated fadeInUp">Detail Produk</h3>
                            <p class="lead text-muted animated fadeInUp" style="animation-delay: 0.3s;">{{$product['name']}}</p>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-4 mb-4">
                            <div class="card shadow-lg border-light rounded-lg h-100">
                                <div class="card-body text-center p-4">
                                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="img-fluid rounded mb-3 animated fadeIn" style="max-width: 100%; height: auto;">
                                    <p class="text-muted animated fadeIn" style="animation-delay: 0.7s;"><strong>Nama Produk:</strong></p>
                                    <h4 class="font-weight-bold text-dark animated fadeIn" style="animation-delay: 0.5s;">{{ $product['name'] }}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Card for Product Details -->
                        <div class="col-lg-8 mb-4">
                            <div class="card shadow-lg border-light rounded-lg h-100">
                                <div class="card-body p-5">
                                    <h4 class="font-weight-bold text-primary mb-4 animated fadeIn" style="animation-delay: 0.8s;">Detail Produk</h4>
                                    <p><strong class="text-dark">Harga:</strong> Rp {{ number_format($product['price'], 0, ',', '.') }}</p>

                                    <h5 class="mt-4 font-weight-bold text-dark animated fadeIn" style="animation-delay: 1s;">Keuntungan:</h5>
                                    <ul class="list-unstyled">
                                        @foreach($product['benefits'] as $benefit)
                                            <li class="mb-3 animated fadeIn" style="animation-delay: 1.2s;">
                                                <i class="fas fa-check-circle text-success"></i> {{ $benefit }}
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{route('produk')}}" class="btn btn-sm btn-secondary btn-lg btn-block animated fadeInUp" style="animation-delay: 1.5s;">
                                Kembali <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom CSS for Animated Elements */
        .animated {
            animation-duration: 1s;
            animation-timing-function: ease-in-out;
        }

        /* Fade In Effect */
        .fadeIn {
            animation-name: fadeIn;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Fade In Up Effect */
        .fadeInUp {
            animation-name: fadeInUp;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhance the Button */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-size: 16px;
            font-weight: 600;
            padding: 15px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Card Shadows and Borders */
        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card-body {
            padding: 20px;
        }

        .shadow-lg {
            box-shadow: 0px 8px 40px rgba(0, 0, 0, 0.1);
        }

        .rounded-lg {
            border-radius: 15px;
        }

        /* Ensure all cards are the same height */
        .card.h-100 {
            height: 100%;
        }

        /* Page Title Styling */
        .display-4 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #007bff;
        }

        .lead {
            font-size: 1.25rem;
            color: #6c757d;
        }

        /* Product Image Styling */
        .card img {
            max-width: 100%;
            height: auto;
            transition: transform 0.3s ease;
        }

        .card img:hover {
            transform: scale(1.05);
        }

        /* Heading Styles */
        .font-weight-bold {
            font-weight: 700 !important;
        }

        .text-primary {
            color: #007bff !important;
        }

        .text-dark {
            color: #343a40 !important;
        }

        /* Customize the Benefit List */
        .list-unstyled {
            margin-left: 0;
            padding-left: 0;
        }

        .list-unstyled li {
            margin-bottom: 15px;
        }

        .list-unstyled li i {
            color: #28a745;
            margin-right: 10px;
        }

        /* Button Styling */
        .btn-secondary {
            background-color: #f8f9fa;
            border: none;
            color: #007bff;
            font-weight: 600;
            padding: 12px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #007bff;
            color: #fff;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .col-lg-4,
            .col-lg-8 {
                margin-bottom: 30px;
            }

            .display-4 {
                font-size: 2rem;
            }

            .card-body {
                padding: 15px;
            }

            .btn-lg {
                font-size: 14px;
                padding: 12px;
            }
        }

        @media (max-width: 576px) {
            .col-12 {
                margin-bottom: 20px;
            }

            .display-4 {
                font-size: 1.75rem;
            }

            .btn-lg {
                font-size: 16px;
                padding: 10px;
            }
        }

        /* Custom Hover Effects */
        .card:hover {
            box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .card-body:hover {
            background-color: #f7f7f7;
        }

        .btn-secondary:hover {
            transform: scale(1.05);
        }
    </style>
@endpush

@push('scripts')
    <!-- Optional: Add ScrollReveal JS or other animation libraries if needed -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/scrollreveal/4.0.7/scrollreveal.min.js"></script>
    <script>
        // ScrollReveal animation for elements when they enter the viewport
        ScrollReveal().reveal('.animated', {
            distance: '50px',
            duration: 1000,
            delay: 300,
            easing: 'ease-in-out',
            origin: 'bottom',
            opacity: 0
        });
    </script>
@endpush
