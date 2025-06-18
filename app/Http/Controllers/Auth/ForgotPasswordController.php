<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    function showLinkRequestForm() {
        return view('reset-password');
    }

    public function sendResetLinkEmail(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $user = Akun::where('email', $request->input('email'))->first();

    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    $token = Password::createToken($user);

    Mail::to($user->email)->send(new PasswordResetMail($token));

    return response()->json(['message' => 'Password reset email sent.']);
}
}
