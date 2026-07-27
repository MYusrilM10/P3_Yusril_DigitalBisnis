<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SmartLoginController extends Controller
{
    /**
     * Tampilkan form login universal
     */
    public function showLoginForm()
    {
        // Kalau sudah login, redirect otomatis sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.universal-login');
    }

    /**
     * Proses login: cek email → tentukan role → redirect
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $request->session()->regenerate();
            return $this->redirectByRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Logout universal
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget('current_organization_id');
        return redirect()->route('home');
    }

    /**
     * Redirect user ke dashboard sesuai role & email
     */
    protected function redirectByRole(User $user)
    {
        // 1. Superadmin
        if ($user->isSuperadmin()) {
            return redirect()->route('admin.dashboard');
        }

        // 2. Panitia → cek org pertama yang dia jadi owner/staff
        if ($user->isPanitia()) {
            $firstOrg = $user->organizations()->first();
            if ($firstOrg) {
                session(['current_organization_id' => $firstOrg->id]);
                return redirect()->route('panitia.dashboard', $firstOrg->slug);
            }
            // Panitia tapi belum ada org (edge case)
            return redirect()->route('home')
                ->with('error', 'Anda belum terhubung ke organisasi manapun.');
        }

        // 3. User biasa → ke home
        return redirect()->route('home')
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }
}
