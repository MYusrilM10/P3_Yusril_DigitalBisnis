<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationAccess
{
    /**
     * Handle request - cek apakah user adalah anggota org yang diminta
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');
        $org = Organization::where('slug', $slug)->where('status', 'active')->first();

        if (!$org) {
            abort(404, 'Organisasi tidak ditemukan atau tidak aktif.');
        }

        if (!auth()->check()) {
            return redirect()->route('panitia.login', $slug);
        }

        if (!$org->hasUser(auth()->id())) {
            abort(403, 'Anda bukan anggota organisasi ini.');
        }

        // Set current_organization_id di session
        session(['current_organization_id' => $org->id]);

        return $next($request);
    }
}
