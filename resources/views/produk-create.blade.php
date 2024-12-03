@extends('layouts.app')

@section('style')
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection

@section('wrapper')
<!-- Start Page Wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <h6 class="mb-0 text-uppercase">Tambah Produk Baru</h6>
        <hr/>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Form untuk menambahkan produk -->
        <form id="productForm">
            @csrf

            <div class="card">
                <div class="card-body">
                    <!-- Input Nama Produk -->
                    <input type="text" name="name" id="name" placeholder="Nama Produk" required><br>

                    <!-- Input Harga Produk -->
                    <input type="number" name="price" id="price" placeholder="Harga Produk" required><br>

                    <!-- Input Manfaat -->
                    <input type="text" name="benefits[]" id="benefit1" placeholder="Manfaat 1"><br>
                    <input type="text" name="benefits[]" id="benefit2" placeholder="Manfaat 2"><br>
                    <input type="text" name="benefits[]" id="benefit3" placeholder="Manfaat 3"><br>

                    <!-- Input Gambar -->
                    <input type="file" id="image" name="image" accept="image/*" required><br>

                    <button type="submit">Kirim</button>
                </div>
            </div>

            <!-- Menampilkan Base64 -->
            <textarea id="base64image" readonly style="display: none;"></textarea>

        </form>
    </div>
</div>
<!-- End Page Wrapper -->
@endsection

@section('script')
<link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<script>
    // Mengonversi gambar menjadi base64
    document.getElementById('image').addEventListener('change', function(event) {
        var reader = new FileReader();
        
        reader.onloadend = function() {
            // Menyimpan base64 string ke textarea tersembunyi
            document.getElementById('base64image').value = reader.result;
        };

        // Membaca file gambar sebagai base64
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    });

    // Menangani pengiriman data ke API eksternal
    document.getElementById('productForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah form untuk submit default

        // Ambil nilai dari form
        var name = document.getElementById('name').value;
        var price = document.getElementById('price').value;
        var benefits = [
            document.getElementById('benefit1').value,
            document.getElementById('benefit2').value,
            document.getElementById('benefit3').value
        ];
        var formData = new FormData();
        formData.append('name', name);
        formData.append('price', price);
        formData.append('benefits[]', benefits[0]);
        formData.append('benefits[]', benefits[1]);
        formData.append('benefits[]', benefits[2]);
        formData.append('image', document.getElementById('image').files[0]);

        fetch('https://loops-rotator.vercel.app/packages', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Produk berhasil ditambahkan');
            } else {
                alert('Gagal menambahkan produk');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim data');
        });

        // Kirim data ke API menggunakan Fetch API
        fetch('https://cors-anywhere.herokuapp.com/https://loops-rotator.vercel.app/packages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Produk berhasil ditambahkan');
                // Redirect atau clear form jika diperlukan
            } else {
                alert('Gagal menambahkan produk');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim data');
        });
    });
</script>
@endsection
