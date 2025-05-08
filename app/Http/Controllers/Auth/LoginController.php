<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function viewLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->only('idAkun', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed
            
            return redirect()->intended('/dashboard');
        }

        // Authentication failed
        return back()->withErrors(['idAkun' => 'Invalid credentials.']);
    }
}
