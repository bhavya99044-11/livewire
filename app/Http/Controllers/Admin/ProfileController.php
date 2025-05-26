<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use Illuminate\Support\Facades\DB;
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
            DB::beginTransaction(); // Start transaction
            $user = $this->admin->user();
            $user->update(['password' => Hash::make($request->password)]);
            DB::commit();
            return back()->with('success', __('messages.profile.password_change_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('messages.profile.password_change_error'))->withInput();
        }
    }
}
