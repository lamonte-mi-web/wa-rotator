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
                    <input type="text" name="name" id="name" placeholder="Nama Produk" required>

                    <!-- Input Harga Produk -->
                    <input type="number" name="price" id="price" placeholder="Harga Produk" required>

                    <!-- Input Manfaat (dalam format array) -->
                    <input type="text" name="benefits" id="benefits" placeholder="Masukkan Manfaat Produk, pisahkan dengan koma" required>

                    <!-- Input Gambar -->
                    <input type="file" id="image" name="image" accept="image/*" required>

                    <!-- Submit Button -->
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
    document.getElementById('productForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah form untuk submit default

    // Ambil nilai dari form
    var name = document.getElementById('name').value;
    var price = document.getElementById('price').value;
    
    // Mengambil nilai dari input benefits dan mengonversinya ke array
    var benefits = document.getElementById('benefits').value.split(',').map(function(item) {
        return item.trim(); // Menghapus spasi yang tidak perlu di setiap elemen
    });

    var imageFile = document.getElementById('image').files[0];

    var formData = new FormData();
    formData.append('name', name);
    formData.append('price', price);
    formData.append('benefits[]', JSON.stringify(benefits)); // Menyimpan benefits sebagai array JSON
    formData.append('image', imageFile);

    // Kirim data menggunakan Fetch API
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
});
            
</script>
@endsection
