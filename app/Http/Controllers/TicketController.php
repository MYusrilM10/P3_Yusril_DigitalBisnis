<?php 

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Get user's transactions with events
        $transactions = Transaction::where('customer_email', Auth::user()->email)
            ->where('status', 'settlement')
            ->with('event', 'review')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate if event is finished
        $transactions = $transactions->map(function($transaction) {
            $eventDate = Carbon::parse($transaction->event->date);
            $now = Carbon::now();
            
            $transaction->event_finished = $now->isAfter($eventDate);
            $transaction->can_review = $transaction->event_finished && abs(Carbon::now()->diffInHours($eventDate)) >= 12;
            $transaction->has_review = $transaction->review != null;
            
            return $transaction;
        });

        return view('ticket', compact('transactions'));
    }
}
