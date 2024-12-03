<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function login()
    {
        return view('login');
    }

    public function prosesLogin(Request $request)
    {
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ],
        [
            'email.required'=>'email wajib diisi',
            'password.required'=>'password wajib diisi'
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($infologin)){
            if(Auth::user()->role == 1){
                return view('index');
            }elseif(Auth::user()->role == 2){
                return view('/');
            }
        }else{
            return redirect('')->withErrors('username dan password tidak sesuai')->withInput();
        }

    }

    function logout (){
        Auth::logout();
        return redirect('');
    }

    public function register()
    {
        return view('register');
    }
    


    public function campaignPayment()
    {
        return view('campaign-payment');
    }
    public function customerService()
    {
        return view('customer-service');
    }
    public function produk()
    {
        return view('produk');
    }
}
