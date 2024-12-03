<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


class CsController extends Controller
{
    public function index()
    {
        $url = 'https://panel.lamonte.cloud/be/cs-numbers';

        if (!$url) {
            return redirect()->back()->with('error', 'URL API tidak ditemukan.');
        }

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                return view('customer-service', ['customerservices' => $response->json()]);
            } else {
                return view('customer-service', ['customerservices' => [], 'error' => 'Gagal memuat data CS.']);
            }
        } catch (\Exception $e) {
            // Menangani exception jika terjadi kesalahan
            return view('customer-service', ['customerservices' => [], 'error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function postCSNumber(Request $request)
    {
        try {
            // Ambil data dari form
            $name = $request->input('name');
            $phoneNumber = $request->input('phoneNumber');

            // Kirim data menggunakan POST request
            $response = Http::post('https://panel.lamonte.cloud/be/cs-number', [
                'name' => $name,
                'phoneNumber' => $phoneNumber,
            ]);

            // Mengecek apakah request berhasil
            if ($response->successful()) {
                // Jika berhasil, bisa kembalikan response atau redirect
                return back()->with('success', 'Nomor CS berhasil ditambahkan');
            } else {
                // Jika gagal, tampilkan error
                return back()->with('error', 'Gagal menambahkan nomor CS');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $url = 'https://panel.lamonte.cloud/be/cs-number';
    
        $request->validate([
            'name' => 'required|string|max:255',
            'nomor' => 'required|string|max:15',
        ]);
    
        $payload = [
            'id' => $id,
            'name' => $request->input('name'),
            'nomor' => $request->input('nomor'),
        ];
    
        try {
            $response = Http::put($url, $payload);
    
            if ($response->successful()) {
                return redirect()->back()->with('success', 'Data Customer Service berhasil diubah.');
            } else {
                return redirect()->back()->with('error', 'Gagal mengubah data. ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request,$id)
    {
        $url = 'https://panel.lamonte.cloud/be/cs-number';  

        $request->validate([
            'id'=> 'request'
        ]);

        $data = [
            'id' => $id
        ];
        try {
            // Kirim permintaan DELETE ke API
            $response = Http::delete($url, $data);

            // Cek apakah respons API berhasil
            if ($response->successful()) {
                return redirect()->back()->with('success', 'Data Customer Service berhasil dihapus.');
            } else {
                // Jika API gagal
                return redirect()->back()->with('error', 'Gagal menghapus data. ' . $response->body());
            }
        } catch (\Exception $e) {
            // Menangani error umum
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
}
