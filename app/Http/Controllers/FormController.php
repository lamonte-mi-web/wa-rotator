<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index($campaignName)
    {
        try {
            $rotatorResponse = Http::get("https://panel.lamonte.cloud/be/cs-number-rotator/{$campaignName}");
            $rotatorData = $rotatorResponse->json();
    
            \Log::info('Rotator Data: ', $rotatorData);
    
            $assignedCSNumber = $rotatorData['assignedCSNumber'] ?? 'Tidak tersedia';
            $assignedCSName = $rotatorData['assignedCSName'] ?? 'Tidak tersedia';
    
            $formField = $rotatorData['formField'] ?? [];
    
            $campaignResponse = Http::get("https://panel.lamonte.cloud/be/campaign/{$campaignName}");
            $campaignData = $campaignResponse->json();
    
            \Log::info('Campaign Data: ', $campaignData);
    
            $campaignDetail = $campaignData['campaign'] ?? [];
            $campaignType = $campaignDetail['campaignType'] ?? 'cta_form'; 
            $metaPixel = $campaignDetail['metaPixel']; 
            $pixelTrack = $campaignDetail['pixelTrack']; 
    
            return view('campaign-form', [
                'campaignName' => $campaignName,
                'assignedCSNumber' => $assignedCSNumber,
                'assignedCSName' => $assignedCSName,
                'formField' => $formField,
                'campaignDetail' => $campaignDetail,
                'campaignType' => $campaignType,
                'metaPixel' => $metaPixel, 
                'pixelTrack' => $pixelTrack,
            ]);
        } catch (\Exception $e) {
            \Log::error('API Error: ' . $e->getMessage());
    
            return view('campaign-form', [
                'campaignName' => $campaignName,
                'assignedCSNumber' => 'Tidak tersedia',
                'assignedCSName' => 'Tidak tersedia',
                'formField' => [],
                'campaignDetail' => [],
                'error' => 'Gagal mengambil data: ' . $e->getMessage(),
                'campaignType' => 'cta_form', 
                'metaPixel' => '', 
            ]);
        }
    }
    

    public function store(Request $request, $campaignName)
    {
        try {
            $formData = [
                'assignedCSNumber' => $request->input('assignedCSNumber'),
                'formField' => [
                    'name' => $request->input('name'),
                    'nomortelepon' => $request->input('nomortelepon'),
                    'alamat' => $request->input('alamat')
                ]
            ];

            $assignedCSNumber = $request->input('assignedCSNumber');

            $response = Http::post("https://panel.lamonte.cloud/be/{$campaignName}", $formData);

            if ($response->successful()) {
                return redirect("https://wa.me/{$assignedCSNumber}");
            } else {
                return redirect()->back()->withErrors(['error' => 'Gagal mengirim data.']);
            }
        } catch (\Exception $e) {
            \Log::error('Form Submission Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan.']);
        }
    }

    
}
