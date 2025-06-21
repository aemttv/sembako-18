<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        // Retrieve the token data from the database
        $tokenData = DB::table('password_reset_tokens')->where('token', $token)->first();

        // Check if the token is valid
        if (!$tokenData) {
            return redirect()->route('password.request')->withErrors(['token' => 'Invalid or expired token.']);
        }

        // Pass the token and email to the view
        return view('emails.update_pw_reset', ['token' => $token, 'email' => $tokenData->email]);
    }


    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:akun,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $tokenData = DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if (!$tokenData || $tokenData->email !== $request->email) {
            return back()->withErrors(['email' => 'Invalid token or email.']);
        }

        $user = Akun::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/')->with('success', 'Password berhasil diubah.');
    }
}
