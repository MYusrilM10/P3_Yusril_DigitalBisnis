<?php

namespace App\Services;

use App\Mail\ReviewInvitationMail;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ReviewInvitationService
{
    /**
     * Kirim email review jika:
     *  - status transaksi = success / settlement
     *  - tanggal event sudah lewat (>= hari ini)
     *  - email review belum pernah dikirim
     */
    public static function sendIfDue(Transaction $transaction): bool
    {
        // Refresh model biar dapat data terbaru
        $transaction->refresh();

        // 1. Status harus success
        if (! in_array(strtolower((string) $transaction->status), ['success', 'settlement'])) {
            return false;
        }

        // 2. Email belum pernah dikirim
        if (! empty($transaction->review_email_sent_at)) {
            return false;
        }

        // 3. Event harus sudah lewat
        $event = $transaction->event;
        if (! $event || now()->lt($event->date)) {
            return false;
        }

        // 4. Generate token kalau belum ada
        if (empty($transaction->review_token)) {
            $transaction->review_token = hash_hmac('sha256', $transaction->order_id . '|' . $transaction->customer_email, config('app.key'));
        }

        // 5. Kirim email
        try {
            Mail::to($transaction->customer_email)->send(new ReviewInvitationMail($transaction));
            $transaction->review_email_sent_at = now();
            $transaction->save();
            return true;
        } catch (\Exception $e) {
            Log::error('Gagal kirim email review untuk order ' . $transaction->order_id . ': ' . $e->getMessage());
            return false;
        }
    }
}
