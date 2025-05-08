<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function viewLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate credentials (example using idAkun)
        $user = \App\Models\Akun::where('idAkun', $request->idAkun)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Store manual session
            session(['user_logged_in' => true]);
            session(['user_data' => $user]);

            return redirect('/dashboard');
        }

        return redirect()->back()->withErrors(['Invalid credentials']);
    }


    function logout(Request $request)
    {
        // Clear the session values
        $request->session()->forget('user_logged_in');
        $request->session()->forget('user_data');

        // Or flush the entire session (clears everything)
        $request->session()->flush();

        // Optionally regenerate session ID
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'Successfully logged out.');
    }
}
