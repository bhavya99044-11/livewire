<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = $this->admin->user();

        return view('admin.pages.profile.index', compact('user'));
    }

    public function changePasswordView()
    {
        return view('admin.pages.profile.change-password');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = $this->admin->user();
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success', 'Password changed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
