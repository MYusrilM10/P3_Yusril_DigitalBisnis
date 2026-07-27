<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Transaction $transaction)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Beri Ulasan untuk ' . $this->transaction->event->title . ' — AmikomEventHub',
        );
    }

    public function content(): Content
    {
        $reviewUrl = route('review.public', [
            'order_id' => $this->transaction->order_id,
            'token'    => $this->transaction->review_token,
        ]);

        return new Content(
            view: 'emails.review-invitation',
            with: [
                'transaction' => $this->transaction,
                'event'       => $this->transaction->event,
                'reviewUrl'   => $reviewUrl,
            ],
        );
    }
}
