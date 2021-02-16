<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if(auth()->user()->isAdmin()){
            return view('admin.index');
        }
        return view('user.index');
    }
}
