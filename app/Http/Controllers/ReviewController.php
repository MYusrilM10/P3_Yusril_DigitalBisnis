<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display reviews for a specific event
     */
    public function index(Event $event)
    {
        $reviews = $event->reviews()
            ->where('is_verified_purchase', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $averageRating = $event->getAverageRating();
        $totalReviews = $event->getTotalReviews();

        return view('reviews.index', compact('event', 'reviews', 'averageRating', 'totalReviews'));
    }

    /**
     * Show form to create a new review
     */
    public function create(Event $event)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Silakan login untuk memberikan review.');
        }

        // Check if user already reviewed this event
        $existingReview = Review::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview)->with('info', 'Anda sudah membuat review untuk acara ini. Silakan edit review Anda.');
        }

        // Check if user purchased this event - try by user_id first, then by email (for old transactions)
        // Accept both 'success' and 'settlement' statuses from Midtrans webhook
        $transaction = Transaction::where('event_id', $event->id)
            ->whereIn('status', ['success', 'settlement'])
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('customer_email', Auth::user()->email);
            })
            ->first();

        if (!$transaction) {
            return redirect()->route('events.show', $event)->with('error', 'Anda harus membeli tiket acara ini untuk memberikan review.');
        }

        // Check if event has finished
        $eventDate = \Carbon\Carbon::parse($event->date);
        $now = \Carbon\Carbon::now();

        // Event belum dimulai
        if ($now->isBefore($eventDate)) {
            $timeUntil = $this->formatTimeDifference($now, $eventDate);
            return redirect()->route('events.show', $event)
                ->with('warning', "Acara masih berlangsung dalam $timeUntil. Review dapat diberikan setelah acara selesai.");
        }

        // Event sudah dimulai, tapi belum H+12 jam
        $hoursSinceEvent = $now->diffInHours($eventDate);
        if ($hoursSinceEvent < 12) {
            $nextReviewDate = $eventDate->copy()->addHours(12);
            $timeRemaining = $this->formatTimeDifference($now, $nextReviewDate);
            return redirect()->route('events.show', $event)
                ->with('warning', "Acara baru saja dimulai. Anda bisa memberikan review dalam $timeRemaining lagi (H+12).");
        }

        return view('reviews.create', compact('event', 'transaction'));
    }

    /**
     * Store a new review
     */
    public function store(Request $request, Event $event)
    {
        // Validate input
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review_text' => 'nullable|string|min:10',
        ]);

        // Check authentication
        if (!Auth::check()) {
            return back()->with('error', 'Anda harus login untuk memberikan review.');
        }

        // Check if already reviewed
        $existingReview = Review::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review untuk acara ini.');
        }

        // Get transaction
        $transaction = Transaction::where('event_id', $event->id)
            ->where('customer_email', Auth::user()->email)
            ->where('status', 'settlement')
            ->first();

        if (!$transaction) {
            return back()->with('error', 'Pembelian tidak ditemukan.');
        }

        // Create review
        $review = Review::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'transaction_id' => $transaction->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'review_text' => $validated['review_text'] ?? null,
            'is_verified_purchase' => true,
        ]);

        // Update event ratings
        $this->updateEventRating($event);

        // Update user (organizer) ratings
        // Note: You may need to add organizer_id to events table
        // For now, this assumes you want to track organizer ratings

        return redirect()->route('events.show', $event)->with('success', 'Review berhasil ditambahkan!');
    }

    /**
     * Show review edit form
     */
    public function edit(Review $review)
    {
        // Authorization check
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak diizinkan untuk mengedit review ini.');
        }

        $event = $review->event;

        return view('reviews.edit', compact('review', 'event'));
    }

    /**
     * Update review
     */
    public function update(Request $request, Review $review)
    {
        // Authorization check
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak diizinkan untuk mengubah review ini.');
        }

        // Validate input
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review_text' => 'nullable|string|min:10',
        ]);

        // Update review
        $review->update([
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'review_text' => $validated['review_text'] ?? null,
        ]);

        // Update event ratings
        $this->updateEventRating($review->event);

        return redirect()->route('events.show', $review->event)->with('success', 'Review berhasil diperbarui!');
    }

    /**
     * Delete review
     */
    public function destroy(Review $review)
    {
        // Authorization check
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak diizinkan untuk menghapus review ini.');
        }

        $event = $review->event;
        $review->delete();

        // Update event ratings
        $this->updateEventRating($event);

        return redirect()->route('events.show', $event)->with('success', 'Review berhasil dihapus!');
    }

    /**
     * Get user's own reviews
     */
    public function myReviews()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $reviews = Review::where('user_id', Auth::id())
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reviews.my-reviews', compact('reviews'));
    }

    /**
     * Update event average rating and total reviews count
     */
    private function updateEventRating(Event $event)
    {
        $reviews = $event->reviews()->where('is_verified_purchase', true)->get();

        if ($reviews->count() > 0) {
            $averageRating = $reviews->avg('rating');
            $totalReviews = $reviews->count();
        } else {
            $averageRating = 0;
            $totalReviews = 0;
        }

        $event->update([
            'average_rating' => round($averageRating, 2),
            'total_reviews' => $totalReviews,
        ]);

        // Update organizer rating if organizer_id exists
        // This would need adjustment based on your Event model structure
    }

    /**
     * Get review status and message for a user & event
     */
    public function getReviewStatus(Event $event)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'not_logged_in',
                'message' => 'Silakan login untuk memberikan review',
                'can_review' => false,
            ]);
        }

        // Check if already reviewed
        $existingReview = Review::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'status' => 'already_reviewed',
                'message' => 'Anda sudah memberikan review untuk acara ini',
                'can_review' => false,
                'review_id' => $existingReview->id,
            ]);
        }

        // Check if user purchased - try by user_id first, then by email (for old transactions)
        // Accept both 'success' and 'settlement' statuses from Midtrans webhook
        $transaction = Transaction::where('event_id', $event->id)
            ->whereIn('status', ['success', 'settlement'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('customer_email', $user->email);
            })
            ->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'not_purchased',
                'message' => 'Anda harus membeli tiket acara ini untuk memberikan review',
                'can_review' => false,
            ]);
        }

        // Check event status
        $eventDate = \Carbon\Carbon::parse($event->date);
        $now = \Carbon\Carbon::now();

        if ($now->isBefore($eventDate)) {
            $timeUntil = $this->formatTimeDifference($now, $eventDate);
            return response()->json([
                'status' => 'event_not_finished',
                'message' => "Acara masih berlangsung dalam $timeUntil. Review dapat diberikan setelah acara selesai.",
                'can_review' => false,
                'event_date' => $eventDate->toDateTimeString(),
                'time_until' => $timeUntil,
            ]);
        }

        $hoursSince = $now->diffInHours($eventDate);
        if ($hoursSince < 12) {
            $nextReviewDate = $eventDate->copy()->addHours(12);
            $timeRemaining = $this->formatTimeDifference($now, $nextReviewDate);
            return response()->json([
                'status' => 'event_just_finished',
                'message' => "Acara baru saja dimulai. Anda bisa memberikan review dalam $timeRemaining lagi (H+12).",
                'can_review' => false,
                'next_review_date' => $nextReviewDate->toDateTimeString(),
                'time_remaining' => $timeRemaining,
            ]);
        }

        return response()->json([
            'status' => 'can_review',
            'message' => 'Anda bisa memberikan review sekarang',
            'can_review' => true,
        ]);
    }

    public function getRatingStats(Event $event)
    {
        $reviews = $event->reviews()->where('is_verified_purchase', true)->get();

        $stats = [
            'total' => $reviews->count(),
            'average' => $reviews->avg('rating') ?? 0,
            'distribution' => [
                5 => $reviews->where('rating', 5)->count(),
                4 => $reviews->where('rating', 4)->count(),
                3 => $reviews->where('rating', 3)->count(),
                2 => $reviews->where('rating', 2)->count(),
                1 => $reviews->where('rating', 1)->count(),
            ],
        ];

        return response()->json($stats);
    }

    /**
     * Format time difference into readable format (e.g., "6 days 17 hours 30 minutes")
     */
    private function formatTimeDifference($from, $to)
    {
        $totalMinutes = $from->diffInMinutes($to);
        
        $days = intdiv($totalMinutes, 24 * 60);
        $remainingMinutes = $totalMinutes % (24 * 60);
        $hours = intdiv($remainingMinutes, 60);
        $minutes = $remainingMinutes % 60;
        
        $parts = [];
        if ($days > 0) {
            $parts[] = "$days hari";
        }
        if ($hours > 0) {
            $parts[] = "$hours jam";
        }
        if ($minutes > 0 || empty($parts)) {
            $parts[] = "$minutes menit";
        }
        
        return implode(' ', $parts);
    }

    // =============================================
    // PUBLIC REVIEW (Tanpa Login, via Email Token)
    // =============================================

    /**
     * Tampilkan form review publik berdasarkan order_id + token
     */
    public function showPublicForm(Request $request, string $orderId)
    {
        $token = $request->query('token');

        $transaction = Transaction::with('event')
            ->where('order_id', $orderId)
            ->firstOrFail();

        // Validasi token HMAC
        $expected = $transaction->review_token;
        if (! $expected || ! hash_equals($expected, (string) $token)) {
            abort(403, 'Link review tidak valid atau sudah kedaluwarsa.');
        }

        // Validasi status transaksi
        if (! in_array(strtolower((string) $transaction->status), ['success', 'settlement'])) {
            return redirect()->route('events.show', $transaction->event)
                ->with('error', 'Transaksi Anda belum berhasil.');
        }

        // Validasi tanggal event (sudah lewat)
        $eventDate = \Carbon\Carbon::parse($transaction->event->date);
        if (now()->lt($eventDate)) {
            return redirect()->route('events.show', $transaction->event)
                ->with('warning', 'Ulasan baru dapat diberikan setelah acara selesai.');
        }

        // Sudah pernah review? (cek termasuk soft-deleted agar tidak duplikat)
        $existing = Review::withTrashed()->where('transaction_id', $transaction->id)->first();
        if ($existing) {
            return redirect()->route('events.show', $transaction->event)
                ->with('info', 'Anda sudah memberikan ulasan untuk acara ini.');
        }

        return view('reviews.public', [
            'transaction' => $transaction,
            'event'       => $transaction->event,
            'token'       => $token,
        ]);
    }

    /**
     * Submit review publik (tanpa login)
     */
    public function submitPublic(Request $request, string $orderId)
    {
        $token = $request->input('token');

        $transaction = Transaction::with('event')
            ->where('order_id', $orderId)
            ->firstOrFail();

        // Validasi token
        $expected = $transaction->review_token;
        if (! $expected || ! hash_equals($expected, (string) $token)) {
            abort(403, 'Link review tidak valid.');
        }

        // Validasi status & tanggal (sama seperti di form)
        if (! in_array(strtolower((string) $transaction->status), ['success', 'settlement'])) {
            return back()->with('error', 'Transaksi Anda belum berhasil.');
        }
        if (now()->lt(\Carbon\Carbon::parse($transaction->event->date))) {
            return back()->with('error', 'Acara belum selesai, ulasan belum dapat diberikan.');
        }
        if (Review::withTrashed()->where('transaction_id', $transaction->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk acara ini.');
        }

        $data = $request->validate([
            'rating'      => 'required|integer|min:1|max:5',
            'title'       => 'nullable|string|max:255',
            'review_text' => 'nullable|string|min:10',
        ]);

        try {
            $review = Review::create([
                'user_id'              => $transaction->user_id, // bisa null untuk guest
                'customer_name'        => $transaction->customer_name,
                'customer_email'       => $transaction->customer_email,
                'event_id'             => $transaction->event_id,
                'transaction_id'       => $transaction->id,
                'rating'               => $data['rating'],
                'title'                => $data['title'] ?? null,
                'review_text'          => $data['review_text'] ?? null,
                'is_verified_purchase' => true,
            ]);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk acara ini.');
        } catch (\Exception $e) {
            \Log::error('Gagal menyimpan review publik: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }

        // Recalculate event rating
        $this->updateEventRating($transaction->event);

        return redirect()->route('events.show', $transaction->event)
            ->with('success', 'Terima kasih atas ulasan Anda! Ulasan Anda akan tampil di halaman event dan profil penyelenggara.');
    }
}
