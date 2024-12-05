<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CsController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProdukController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->group(function(){
    Route::get('/', [HomeController::class, 'login'])->name('login');
    Route::post('/', [HomeController::class, 'prosesLogin']); 
    Route::get('/register', [HomeController::class, 'register'])->name('register');
});


Route::middleware(['auth'])->group(function(){
    Route::get('/',[HomeController::class,'index']);
    Route::get('/logout',[HomeController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/campaign-payment', [HomeController::class, 'campaignPayment'])->name('campaign-payment');
    
    
    
    //Campaign
    Route::get('/campaign', [CampaignController::class, 'index'])->name('campaign');
    Route::get('/campaigns-detail/{campaignName}', [CampaignController::class, 'detail'])->name('campaign.detail');
    Route::get('/campaign-create', [CampaignController::class, 'create'])->name('campaign.create');
    Route::post('/campaign', [CampaignController::class, 'storeCampaign'])->name('campaign.store');
    Route::get('/campaigns-edit/{campaignName}', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaign/update/{id}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaign/{id}', [CampaignController::class, 'destroy'])->name('campaign.destroy');
    Route::get('/campaign-form/{namaCampaign}', [CampaignController::class, 'formDetail'])->name('form.detail');
    Route::get('/campaign-traffic/{namaCampaign}', [CampaignController::class, 'trafficDetail'])->name('traffic.detail');
    
    
    //Customer Service
    Route::get('/customer-service', [CsController::class, 'index'])->name('customer-service');
    Route::post('/cs-number', [CsController::class, 'postCSNumber'])->name('cs-store');
    Route::put('/cs-number/{cs}', [CsController::class, 'update'])->name('cs.update');
    Route::delete('/csNumbers/{id}', [CsController::class, 'destroy'])->name('cs.destroy');
    
    
    //Produk
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk-detail/{id}', [ProdukController::class, 'detailProduk'])->name('produk.detail');

    //Form
});

Route::get('form/{namaCampaign}',[FormController::class,'index'])->name('form');
Route::post('formSubmit/{namaCampaign}',[FormController::class, 'store'])->name('form.store');
