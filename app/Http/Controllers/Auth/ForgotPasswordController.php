<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    function showLinkRequestForm() {
        return view('reset-password');
    }

    public function sendResetLinkEmail(Request $request)
{
    $request->validate([
            'email' => 'required|email|exists:akun,email',
        ]);

        $token = Str::random(60);
        $email = $request->email;

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        // Misalnya kamu ingin pakai Mail (bisa disesuaikan nanti)
        $resetLink = url("/reset-password/{$token}");

        // Contoh pengiriman via Laravel Mail (jika tidak pakai Mail, cukup dd link dulu)
        Mail::raw("Klik link berikut untuk reset password Anda: $resetLink", function ($message) use ($email) {
            $message->to($email)->subject('Reset Password');
        });

        return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
}
}
