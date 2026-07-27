<?php

namespace App\Http\Middleware;

use App\Models\Transaction;
use App\Services\ReviewInvitationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TriggerPendingReviewEmails
{
    /**
     * Safety net: setiap kali ada request ke homepage / public page,
     * cek & kirim email review untuk transaksi yang qualify tapi belum pernah dikirim.
     * Bekerja sebagai fallback kalau Midtrans webhook gagal.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya trigger di route public tertentu (homepage, event detail, profil publik)
        // supaya tidak spam di setiap request
        $publicRoutes = ['/', null]; // null = homepage
        $path = $request->path();

        // Cek hanya di path tertentu agar tidak membebani server
        if (in_array($path, ['/', 'home', ''], true) || str_starts_with($path, 'events/') || str_starts_with($path, 'panitia/')) {
            $this->triggerPending();
        }

        return $next($request);
    }

    /**
     * Cari semua transaksi success/settlement yang:
     * - event.date <= now() (event sudah lewat)
     * - review_email_sent_at IS NULL
     * Lalu kirim email review untuk masing-masing (max 5 per request untuk hemat resource)
     */
    protected function triggerPending(): void
    {
        try {
            $transactions = Transaction::with('event')
                ->whereIn('status', ['success', 'settlement'])
                ->whereNull('review_email_sent_at')
                ->whereHas('event', function ($q) {
                    $q->where('date', '<=', now());
                })
                ->limit(5)
                ->get();

            foreach ($transactions as $trx) {
                ReviewInvitationService::sendIfDue($trx);
            }
        } catch (\Exception $e) {
            // Silent fail - jangan ganggu user experience
            \Log::warning('Lazy review email trigger failed: ' . $e->getMessage());
        }
    }
}
