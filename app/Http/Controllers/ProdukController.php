<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;



class ProdukController extends Controller
{
    public function index()
    {
        $url = 'https://loops-rotator.vercel.app/packages';

        if (!$url) {
            return redirect()->back()->with('error', 'URL API tidak ditemukan.');
        }
        try {
            $response = Http::get($url);

            if ($response->successful()) {
                return view('produk', ['produk' => $response->json()]);
            } else {
                return view('produk', ['produk' => [], 'error' => 'Gagal memuat data produk.']);
            }
        } catch (\Exception $e) {
            // Menangani exception jika terjadi kesalahan
            return view('produk', ['produk' => [], 'error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('produk-create');
    }

    public function detailProduk($id)
    {
        // URL API untuk mendapatkan detail produk
        $url = "https://loops-rotator.vercel.app/package/{$id}";

        // Mengambil data produk dari API
        $response = Http::get($url);

        // Cek apakah request berhasil
        if ($response->successful()) {
            // Ambil data dari response
            $product = $response->json();

            // Kirim data produk ke view
            return view('produk-detail', compact('product'));
        }

        // Jika gagal mendapatkan data
        return response()->json(['message' => 'Produk tidak ditemukan'], 404);
    }
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'benefits' => 'required|array',
            'benefits.*' => 'string|max:255',
            'image' => 'required|string', // Gambar harus berupa string base64
        ]);

        // Mengambil data gambar base64 dari request
        $imageData = $validated['image']; // data gambar dalam base64

        // Data produk yang akan dikirim ke API eksternal
        $data = [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'benefits' => json_encode($validated['benefits']),
            'image' => $imageData, // Kirim base64 image
        ];

        // Mengirim data ke API eksternal
        $response = Http::post('https://loops-rotator.vercel.app/packages', $data);

        // Menangani respons dari API
        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke API eksternal']);
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal mengirim data ke API eksternal']);
        }
    }
}