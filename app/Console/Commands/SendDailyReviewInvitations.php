<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\ReviewInvitationService;
use Illuminate\Console\Command;

class SendDailyReviewInvitations extends Command
{
    protected $signature = 'reviews:send-daily-invitations';

    protected $description = 'Kirim email review untuk semua transaksi sukses yang event-nya sudah lewat 24 jam dan belum pernah dikirim email review';

    public function handle(): int
    {
        $now = now();
        $threshold = $now->copy()->subDay(); // event.date <= H-1 (24 jam yang lalu)

        $this->info("[{$now->toDateTimeString()}] Memulai proses kirim email review harian...");

        // Cari transaksi yang:
        // 1. status = success atau settlement
        // 2. event.date <= threshold (event sudah lewat minimal 24 jam)
        // 3. review_email_sent_at IS NULL
        $transactions = Transaction::with('event')
            ->whereIn('status', ['success', 'settlement'])
            ->whereNull('review_email_sent_at')
            ->whereHas('event', function ($q) use ($threshold) {
                $q->where('date', '<=', $threshold);
            })
            ->get();

        $count = 0;
        $failed = 0;

        foreach ($transactions as $trx) {
            $sent = ReviewInvitationService::sendIfDue($trx);
            if ($sent) {
                $count++;
                $this->line("  ✓ Sent: {$trx->order_id} → {$trx->customer_email}");
            } else {
                $failed++;
                $this->warn("  ✗ Skip: {$trx->order_id}");
            }
        }

        $this->info("\nSelesai. Berhasil: {$count}, Gagal/Skip: {$failed}");

        return self::SUCCESS;
    }
}
