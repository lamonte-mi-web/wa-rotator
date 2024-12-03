@extends("layouts.app")

@section("style")
<link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<style>
    /* General Styles */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    .page-wrapper {
        padding: 30px;
    }

    h6 {
        font-size: 24px;
        font-weight: bold;
        color: #007bff;
    }

    hr {
        border-top: 2px solid #007bff;
    }

    .card {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        background-color: #fff;
        padding: 30px;
        margin-bottom: 30px;
    }

    .card-header {
        font-size: 20px;
        font-weight: bold;
    }

    .card-body {
        padding: 0;
    }

    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 30px;
        padding: 20px;
    }

    .product-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: center;
        transition: transform 0.3s ease-in-out;
    }

    .product-card:hover {
        transform: scale(1.05);
    }

    .product-card img {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .product-card h5 {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .price {
        font-size: 16px;
        font-weight: 600;
        color: #28a745;
        margin-bottom: 15px;
    }

    .btn {
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .btn-info {
        background-color: #007bff;
        color: #fff;
    }

    .btn-info:hover {
        background-color: #0056b3;
    }

    .btn-add {
        background-color: #28a745;
        color: #fff;
        margin-bottom: 30px;
        display: inline-block;
    }

    .btn-add:hover {
        background-color: #218838;
    }

    /* Mobile Responsiveness */
    @media screen and (max-width: 768px) {
        .card-container {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media screen and (max-width: 480px) {
        .card-container {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section("wrapper")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="card">
        <div class="card-header">
            Daftar Produk
        </div>
        <div class="card-body">
            <!-- Add Product Button -->
            <div class="text-right mb-3">
                <a href="{{route('produk.create')}}" class="btn btn-success btn-sm">
                <i class="bx bx-plus"></i> Tambah Data Produk
                </a>
            </div>

            <div class="card-container">
                @foreach($produk as $package)
                <div class="product-card">
                    @if(isset($package['image']) && $package['image'])
                        <img src="{{ $package['image'] }}" alt="{{ $package['name'] }}">
                    @else
                        <img src="https://via.placeholder.com/300" alt="No Image Available">
                    @endif

                    <h5>{{ $package['name'] }}</h5>

                    <div class="price">Rp {{ number_format($package['price'], 0, ',', '.') }}</div>

                    <button class="btn btn-info btn-sm btn-detail" 
                            onclick="window.location.href='{{ route('produk.detail', ['id' => $package['id']]) }}'">
                        Detail
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        // Action for detail button
        $(document).on('click', '.btn-detail', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const price = $(this).data('price');
            const benefits = JSON.parse($(this).data('benefits'));

            // Display details
            alert(`ID: ${id}\n
                Name: ${name}\n
                Price: Rp ${price.toLocaleString('id-ID')}\n
                Benefits: \n - ${benefits.join('\n - ')}`);
        });
    });
</script>
@endsection
