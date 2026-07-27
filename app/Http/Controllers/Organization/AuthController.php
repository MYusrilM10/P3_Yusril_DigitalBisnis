<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Tampilkan form login panitia (by slug)
     */
    public function showLoginForm($slug)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();
        return view('panitia.login', compact('org'));
    }

    /**
     * Login panitia
     */
    public function login(Request $request, $slug)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Pastikan user adalah anggota org ini
            if (!$org->hasUser($user->id)) {
                Auth::logout();
                return back()->withErrors(['email' => 'Anda bukan anggota ' . $org->name]);
            }

            // Set current_organization_id di session
            session(['current_organization_id' => $org->id]);
            $request->session()->regenerate();

            return redirect()->route('panitia.dashboard', $org->slug);
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    /**
     * Logout panitia
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget('current_organization_id');
        return redirect()->route('home');
    }
}
