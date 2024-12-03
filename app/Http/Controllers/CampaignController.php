<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        // URL API untuk data campaign
        $campaignUrl = 'https://panel.lamonte.cloud/be/campaigns';
        // URL API untuk data CS
        $csUrl = 'https://panel.lamonte.cloud/be/cs-numbers';
    
        try {
            // Mengambil data campaign
            $campaignResponse = Http::get($campaignUrl);
            $campaigns = $campaignResponse->successful() ? $campaignResponse->json() : [];
    
            // Mengambil data CS
            $csResponse = Http::get($csUrl);
            $customerservices = $csResponse->successful() ? $csResponse->json() : [];
    
            // Ambil data tambahan untuk setiap campaign dari API log/campaign/{campaignName}
            foreach ($campaigns as &$campaign) {
                $campaignName = $campaign['campaignName']; // Pastikan key ini ada dalam response
                $logResponse = Http::get("https://panel.lamonte.cloud/be/log/campaign/{$campaignName}");
    
                // Periksa apakah data log ditemukan dan masukkan ke dalam campaign
                if ($logResponse->successful()) {
                    $logData = $logResponse->json();
    
                    // Periksa apakah elemen 'totalForm' dan 'totalTraffic' ada dalam logData
                    $campaign['logData'] = [
                        'totalForm' => isset($logData['totalForm']) ? $logData['totalForm'] : 'Data tidak tersedia',
                        'totalTraffic' => isset($logData['totalTraffic']) ? $logData['totalTraffic'] : 'Data tidak tersedia'
                    ];
                } else {
                    $campaign['logData'] = [
                        'totalForm' => 'Data tidak tersedia',
                        'totalTraffic' => 'Data tidak tersedia'
                    ];
                }
            }
    
            // Mengirim data ke view
            return view('campaign', [
                'campaigns' => $campaigns,
                'customerservices' => $customerservices,
            ]);
        } catch (\Exception $e) {
            // Menangani exception jika terjadi kesalahan
            return view('campaign', [
                'campaigns' => [],
                'customerservices' => [],
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }
    
    public function detail($campaignName)
    {
        // Fetch campaign data
        $campaignResponse = Http::get("https://panel.lamonte.cloud/be/campaign/{$campaignName}");
    
        // Fetch CS numbers data
        $csResponse = Http::get("https://panel.lamonte.cloud/be/cs-numbers");
    
        // Check if both API requests were successful
        if (!$campaignResponse->successful()) {
            return back()->withErrors(['error' => 'Failed to fetch campaign data']);
        }
        if (!$csResponse->successful()) {
            return back()->withErrors(['error' => 'Failed to fetch CS numbers data']);
        }
    
        // Get the campaign data and CS numbers data
        $campaignData = $campaignResponse->json();
        $campaign = $campaignData['campaign']; // campaign details
        $csData = $csResponse->json(); // cs numbers and names
    
        // Initialize an empty array for CS names
        $csNames = [];
    
        // Map the CS numbers to their names
        foreach ($campaign['csNumbers'] as $csNumber) {
            // Find the matching CS entry based on the 'nomor' field
            $matchingCs = collect($csData)->firstWhere('nomor', $csNumber);
    
            // If a match is found, use the name; otherwise, use "Name Not Found"
            $csNames[$csNumber] = $matchingCs ? $matchingCs['name'] : 'Name Not Found';
        }
    
        // Return the view with campaign and CS names
        return view('campaign-detail', compact('campaign', 'csNames'));
    }

    public function storeCampaign(Request $request)
    {
        $validatedData = $request->validate([
            'campaignTitle' => 'required|string',
            'campaignName' => 'required|string',
            'csNumbers' => 'required|array',
            'csNumbers.*' => 'required|string', 
            'metaPixel' => 'required|string',
            'campaignType' => 'required|string',
            'pixelTrack' => 'required|string',
            'formField' => 'nullable|array',
            'formField.*' => 'string', 
            'templateMessage' => 'nullable|string',
        ]);

        $data = [
            'campaignTitle' => $validatedData['campaignTitle'],
            'campaignName' => $validatedData['campaignName'],
            'csNumbers' => $validatedData['csNumbers'],
            'metaPixel' => $validatedData['metaPixel'],
            'campaignType' => $validatedData['campaignType'],
            'pixelTrack' => $validatedData['pixelTrack'],
            'formField' => $validatedData['formField'] ?? [], 
            'templateMessage' => empty($validatedData['templateMessage']) ? null : $validatedData['templateMessage'],
        ];

        try {
            $response = Http::post('https://panel.lamonte.cloud/be/campaign', $data);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Campaign berhasil ditambahkan!');
            } else {
                return redirect()->back()->with('error', 'Gagal menambahkan campaign: ' . $response->body());
            }
        } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function create()
    {
        $url = 'https://panel.lamonte.cloud/be/cs-numbers';
        $response = Http::get($url);

        if ($response->successful()) {
            $csNumbers = $response->json();
        } else {
            $csNumbers = [];
        }

        return view('campaign-create', compact('csNumbers'));
    }

    public function edit($campaignName)
    {
        $campaignResponse = Http::get("https://panel.lamonte.cloud/be/campaign/{$campaignName}");
        if (!$campaignResponse->successful()) {
            return back()->withErrors(['error' => 'Failed to fetch campaign data']);
        }
        $data = $campaignResponse->json();

        $csNumbersResponse = Http::get("https://panel.lamonte.cloud/be/cs-numbers");
        if (!$csNumbersResponse->successful()) {
            return back()->withErrors(['error' => 'Failed to fetch CS numbers']);
        }
        $csNumbers = $csNumbersResponse->json();

        $campaign = $data['campaign'];

        return view('campaign-edit', compact('campaign', 'csNumbers'));
    }

    public function update(Request $request, $id)
    {
        $url = 'https://panel.lamonte.cloud/be/campaign';  

        $data = [
            'id' => $id,
            'campaignTitle' => $request->input('campaignTitle'),
            'campaignName' => $request->input('campaignName'),
            'csNumbers' => $request->input('csNumbers'),  
            'campaignType' => $request->input('campaignType'),
            'metaPixel' => $request->input('metaPixel'),
            'pixelTrack' => $request->input('pixelTrack'),
            'formField' => $request->input('formField'),
            'templateMessage' => $request->input('templateMessage'),
        ];

    
        try {
            $response = Http::put($url, $data);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Data Campaign  berhasil diubah.');
            } else {
                return redirect()->back()->with('error', 'Gagal mengubah data. ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request,$id)
    {
        $url = 'https://panel.lamonte.cloud/be/campaign';  

        $request->validate([
            'id'=> 'request'
        ]);

        $data = [
            'id' => $id
        ];
        try {
            $response = Http::delete($url, $data);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Data Campaign  berhasil dihapus.');
            } else {
                return redirect()->back()->with('error', 'Gagal menghapus data. ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function trafficDetail(Request $request, $campaignName)
    {
        // Fetch traffic and campaign data from API
        $trafficResponse = Http::get("https://panel.lamonte.cloud/be/log/traffic/{$campaignName}");
        $campaignResponse = Http::get("https://panel.lamonte.cloud/be/campaign/{$campaignName}");

        // Decode the responses into arrays
        $dataTraffic = $trafficResponse->json();
        $dataCampaign = $campaignResponse->json();

        // Pass the data to the view
        return view('campaign-traffic', compact('dataTraffic', 'dataCampaign'));
    }
    
    public function formDetail(Request $request, $campaignName)
    {
        // Fetch campaign and form data from the API
        $formResponse = Http::get("https://panel.lamonte.cloud/be/log/form/{$campaignName}");
        $campaignResponse = Http::get("https://panel.lamonte.cloud/be/campaign/{$campaignName}");
    
        // Decode the JSON response into arrays
        $dataForm = $formResponse->json();
        $dataCampaign = $campaignResponse->json();
    
        // Pass the data to the view
        return view('campaign-form-detail', compact('dataForm', 'dataCampaign'));
    }
    

}
