<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $level = Auth::user()->userAccess();
        return $level;

        return view('admin.index');
    }
}
