<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrganizationRegisterController extends Controller
{
    /**
     * Tampilkan form pendaftaran
     */
    public function showForm()
    {
        return view('organization.register');
    }

    /**
     * Submit pendaftaran
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hima,bem,kepanitiaan,external,ukm',
            'description' => 'required|string|max:1000',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'pic_name' => 'required|string|max:255',
            'pic_email' => 'required|email|max:255',
            'pic_password' => 'required|string|min:6|confirmed',
        ]);

        DB::transaction(function () use ($data) {
            // 1. Buat atau cari PIC user
            $pic = User::firstOrCreate(
                ['email' => $data['pic_email']],
                [
                    'name' => $data['pic_name'],
                    'password' => Hash::make($data['pic_password']),
                    'role' => 'panitia',
                ]
            );

            // 2. Buat organization
            $org = Organization::create([
                'name' => $data['name'],
                'type' => $data['type'],
                'description' => $data['description'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'] ?? null,
                'status' => 'pending',
                'commission_percentage' => 10.00,
            ]);

            // 3. Attach PIC sebagai owner
            $org->users()->attach($pic->id, [
                'role' => 'owner',
                'invited_at' => now(),
                'joined_at' => now(),
            ]);
        });

        return redirect()->route('organization.pending');
    }

    /**
     * Halaman "sedang diverifikasi"
     */
    public function pending()
    {
        return view('organization.pending');
    }
}
