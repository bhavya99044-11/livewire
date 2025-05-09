<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ForgotPasswordRequest;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\ResetPassword;
use App\Mail\ResetPasswordMail;
use App\Models\Admin\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $this->admin->attempt($credentials);
        if ($this->admin->check()) {
            return redirect()->route('admin.dashboard')->with('success', 'Login successful');
        }

        return redirect()->back()->with('error', 'Invalid credentials')->withInput();
    }

    public function logout(Request $request)
    {
        $this->admin->logout();

        return redirect()->route('admin.login')->with('success', 'Logout successful');
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $admin = Admin::where('email', $request->email)->first();
        if (! $admin) {
            return redirect()->back()->with('error', 'Email not found')->withInput();
        }
        $random = Str::random(40);
        $admin->forgot_password_token = $random;
        $admin->forgot_password_token_expiry = Carbon::now()->addMinutes(10);
        $admin->save();
        Mail::to($request->email)->send(new ResetPasswordMail($random));

        return redirect()->back()->with('success', 'Reset password link sent to your email');
    }

    public function resetPasswordView($token)
    {
        return view('admin.auth.reset-password', compact('token'));
    }

    public function resetPassword(ResetPassword $request)
    {

        $admin = Admin::where('forgot_password_token', $request->token)->first();
        if ($admin && Carbon::parse($admin->forgot_password_token_expiry)->diffInMinutes(Carbon::now()) < 10) {
            $admin->password = Hash::make($request->password);
            $admin->forgot_password_token = null;
            $admin->forgot_password_token_expiry = null;
            $admin->save();

            return redirect()->route('admin.login')->with('success', 'Password reset successful');
        }

        return redirect()->back()->with('error', 'Invalid token')->withInput();

    }
}
