<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function index($slug)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $members = $org->users()->get();
        return view('panitia.staff', compact('org', 'members'));
    }

    public function invite(Request $request, $slug)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required|in:owner,admin,staff',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            // Untuk simplifikasi: langsung create user baru dengan role panitia
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'), // default
                'role' => 'panitia',
            ]);
        }

        if ($org->hasUser($user->id)) {
            return back()->withErrors(['email' => 'User ini sudah menjadi anggota']);
        }

        $org->users()->attach($user->id, [
            'role' => $data['role'],
            'invited_at' => now(),
            'joined_at' => now(),
        ]);

        return back()->with('success', "Anggota {$user->name} berhasil ditambahkan sebagai {$data['role']}!");
    }

    public function destroy($slug, $userId)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();

        // Cegah hapus owner terakhir
        $ownerCount = $org->users()->wherePivot('role', 'owner')->count();
        $targetRole = $org->users()->where('users.id', $userId)->first()?->pivot->role;

        if ($targetRole === 'owner' && $ownerCount <= 1) {
            return back()->withErrors(['error' => 'Tidak bisa hapus owner terakhir']);
        }

        $org->users()->detach($userId);

        return back()->with('success', 'Anggota berhasil dihapus');
    }
}
