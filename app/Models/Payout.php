<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = [
        'organization_id', 'amount', 'period_start', 'period_end',
        'status', 'notes',
        'requested_by', 'requested_at',
        'processed_by', 'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    // === RELATIONS ===
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // === SCOPES ===
    public function scopePending($query)
    {
        return $query->where('status', 'requested');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    // === HELPERS ===
    public function isPending(): bool
    {
        return $this->status === 'requested';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'requested' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'paid' => 'Sudah Ditransfer',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'requested' => 'yellow',
            'approved' => 'blue',
            'paid' => 'green',
            'rejected' => 'red',
            default => 'slate',
        };
    }
}
