<?php

namespace App\Http\Controllers;

use App\Models\Organization;

class PublicOrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::where('status', 'active')
            ->withCount('events')
            ->orderBy('name')
            ->paginate(20);
        return view('panitia.katalog', compact('organizations'));
    }

    public function show($slug)
    {
        $org = Organization::where('slug', $slug)
            ->where('status', 'active')
            ->with(['events' => function ($q) {
                // Tampilkan semua event (lewat & akan datang) untuk rekam jejak penyelenggara
                $q->orderBy('date', 'desc');
            }])
            ->firstOrFail();
        return view('panitia.profile', compact('org'));
    }
}
