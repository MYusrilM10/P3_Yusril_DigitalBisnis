<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        $organizations = Organization::orderBy('name')->get();
        return view('admin.commission', compact('organizations'));
    }

    public function update(Request $request, $id)
    {
        $org = Organization::findOrFail($id);
        $data = $request->validate([
            'commission_percentage' => 'required|numeric|min:0|max:100',
        ]);
        $org->update(['commission_percentage' => $data['commission_percentage']]);
        return back()->with('success', "Komisi {$org->name} diupdate ke {$data['commission_percentage']}%");
    }
}
