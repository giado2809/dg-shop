<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // form đăng ký
    public function showRegisterForm()
    {
        return view('auths.register');
    }

    // submit đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'username' => $request->username,
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'role'     => 'user', // mặc định
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('index')->with('success', 'Đăng ký thành công!');
    }

    // form đăng nhập
    public function showLoginForm()
    {
        return view('auths.login');
    }

    // submit đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->is_blocked) {
                Auth::logout(); // đá ra ngay
                return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa.');
            }

            return redirect()->route('index')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors([
            'login' => 'Sai tài khoản hoặc mật khẩu',
        ]);
    }

    // đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->back();
    }

    // form đổi mk
    public function formChangePassWord()
    {
        return view('auths.change-pass');
    }

    // submit đổi mk
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('login')->with('success', 'Đổi mật khẩu thành công!');
    }

    // form nhập email quên mk
    public function showForgotForm()
    {
        return view('auths.forgot');
    }

    // gửi link tới email
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()
                ->with('success', 'Đã gửi link đặt lại mật khẩu về email!')
                ->withInput()
            : back()
                ->withErrors(['email' => 'Không thể gửi email.']);
    }

    // form nhập mật khẩu mới
    public function showResetForm($token)
    {
        return view('auths.reset', ['token' => $token]);
    }

    // submit reset mk
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                auth()->login($user); // Đăng nhập luôn sau khi đổi
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('index')->with('success', 'Đổi mật khẩu thành công!')
            : back()->withErrors(['email' => __($status)]);
    }

}
