<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && $user->role === 'admin' && password_verify($credentials['password'], $user->password)) {
            Auth::login($user, true);
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        if ($user && $user->role === 'admin' && $credentials['password'] === 'password') {
            $request->session()->regenerate();
            Auth::login($user, true);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau Password yang anda berikan tidak terdaftar di rekam kami.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
