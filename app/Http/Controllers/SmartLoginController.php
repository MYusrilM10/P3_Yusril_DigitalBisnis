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
        // 1. Admin / Superadmin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // 2. Panitia → cek org yang dia jadi owner/staff
        if ($user->isPanitia()) {
            // Prioritaskan org yang udah aktif (disetujui admin)
            $activeOrg = $user->organizations()->where('status', 'active')->first();
            if ($activeOrg) {
                session(['current_organization_id' => $activeOrg->id]);
                return redirect()->route('panitia.dashboard', $activeOrg->slug);
            }

            // Org masih pending approval → jangan redirect ke dashboard (bakal 404
            // kena OrganizationAccess middleware), arahin ke halaman status pending
            $pendingOrg = $user->organizations()->first();
            if ($pendingOrg) {
                return redirect()->route('organization.pending')
                    ->with('info', 'Organisasi "' . $pendingOrg->name . '" Anda masih menunggu verifikasi admin.');
            }

            // Panitia tapi belum ada org (edge case)
            return redirect()->route('home')
                ->with('error', 'Anda belum terhubung ke organisasi manapun.');
        }

        // 3. User biasa → balik ke halaman yang dituju sebelum diminta login (mis. checkout), atau home
        return redirect()->intended(route('home'))
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }
}
