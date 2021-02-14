<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function validateLogin(Request  $req)
    {
        $remember = ($req->remember) ? true: false;
        if(Auth::attempt(['username' => $req->username, 'password' => $req->password], $remember )){
            return redirect()->intended('/');
        }
        return redirect()->back()->with('error',true);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->intended('/login');
    }
}
