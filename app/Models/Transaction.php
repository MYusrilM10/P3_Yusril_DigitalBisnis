<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'organization_id', 'event_id', 'order_id',
        'customer_name', 'customer_email', 'customer_phone',
        'total_price', 'platform_fee', 'net_income', 'payout_id',
        'status', 'snap_token',
        'review_email_sent_at', 'review_token',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'net_income' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relationship: Transaction has one review
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // ============================================
    // MULTI-TENANT (BARU)
    // ============================================

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function payout()
    {
        return $this->belongsTo(Payout::class);
    }
}
