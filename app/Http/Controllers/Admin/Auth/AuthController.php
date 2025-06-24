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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        try {
            dd($request->all());
            $credentials = $request->only('email', 'password');

            if ($this->admin->attempt($credentials)) {
                return redirect()->route('admin.dashboard')->with('success', __('messages.auth.login_success'));
            }

            return back()->with('error', __('messages.auth.invalid_credentials'))->withInput();
        } catch (\Exception $e) {
            return back()->with('error', __('messages.auth.login_failed'))->withInput();
        }
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        try {
            $this->admin->logout();
            return redirect()->route('admin.login')->with('success', __('messages.auth.logout_success'));
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error', __('messages.auth.logout_failed'));
        }
    }


    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            DB::beginTransaction();

            $admin = Admin::where('email', $request->email)->first();
            if (!$admin) {
                return back()->with('error', __('messages.auth.email_not_found'))->withInput();
            }

            $admin->update([
                'forgot_password_token' => Str::random(40),
                'forgot_password_token_expiry' => Carbon::now()->addMinutes(10),
            ]);

            Mail::to($request->email)->send(new ResetPasswordMail($admin->forgot_password_token));

            DB::commit();
            return back()->with('success', __('messages.auth.reset_password_link_sent'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('messages.auth.forgot_password_failed'))->withInput();
        }
    }

    /**
     * Display reset password view.
     */
    public function resetPasswordView($token)
    {
        return view('admin.auth.reset-password', compact('token'));
    }

    /**
     * Handle password reset.
     */
    public function resetPassword(ResetPassword $request)
    {
        try {
            DB::beginTransaction();

            $admin = Admin::where('forgot_password_token', $request->token)->first();

            if (!$admin || Carbon::parse($admin->forgot_password_token_expiry)->diffInMinutes(Carbon::now()) >= 10) {
                return back()->with('error', __('messages.auth.invalid_token'))->withInput();
            }

            $admin->update([
                'password' => Hash::make($request->password),
                'forgot_password_token' => null,
                'forgot_password_token_expiry' => null,
            ]);

            DB::commit();
            return redirect()->route('admin.login')->with('success', __('auth.password_reset_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('messages.auth.password_reset_failed'))->withInput();
        }
    }
}
