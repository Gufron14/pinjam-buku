<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    function logout()
    {
        Auth::logout();

        return redirect('login')->with('success', 'Logout Success.');
    }
}
