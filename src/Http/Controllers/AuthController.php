<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['is_active'] = 1;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/' . config('speed-admin.admin_url'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or account is inactive.',
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
        ? redirect()->route('admin.login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if( ! Hash::check($request->old_password, \Auth::user()->password) ) {
            return back()->withErrors(['old_password' => __('Old password not correct')]);
        }

        \Auth::user()->forceFill([
            'password' => Hash::make($request->new_password),
        ])->save();

        return redirect()->route('admin.login')->with('status', __(Password::PASSWORD_RESET));
    }

    public function updateProfile (Request $request)
    {
        $request->validate([
            'name' => 'required',
            'picture' => 'image|max:2048',
        ]);

        $user = \Auth::user();

        $user->name = $request->name;
        $file_name = \Auth::user()->id . '.' . $request->picture->extension();

        if($request->has('remove_picture') && $request->remove_picture == 'on') {
            Storage::delete($user->picture);
            $user->picture = null;

        } else if ($request->has('picture')) {
            // delete old picture
            Storage::delete($user->picture);

            $file_path = $request->picture
                ->storeAs('admin/users', $file_name);

            $user->picture = $file_path;
        }

        $user->save();

        return back();
    }
}
