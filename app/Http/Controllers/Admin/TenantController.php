<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $organizations = Organization::latest()->paginate(20);
        return view('admin.tenants.index', compact('organizations'));
    }

    public function pending()
    {
        $organizations = Organization::where('status', 'pending')->latest()->paginate(20);
        return view('admin.tenants.pending', compact('organizations'));
    }

    public function show($id)
    {
        $org = Organization::with('users', 'events')->findOrFail($id);
        return view('admin.tenants.show', compact('org'));
    }

    public function approve($id)
    {
        $org = Organization::findOrFail($id);
        $org->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);
        return back()->with('success', "Organisasi '{$org->name}' berhasil disetujui!");
    }

    public function reject($id)
    {
        $org = Organization::findOrFail($id);
        $org->update(['status' => 'rejected']);
        return back()->with('success', 'Organisasi ditolak.');
    }

    public function suspend($id)
    {
        $org = Organization::findOrFail($id);
        $org->update(['status' => 'suspended']);
        return back()->with('success', "Organisasi '{$org->name}' di-suspend.");
    }

    public function activate($id)
    {
        $org = Organization::findOrFail($id);
        $org->update(['status' => 'active']);
        return back()->with('success', "Organisasi '{$org->name}' diaktifkan kembali.");
    }
}
