<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'type', 'description', 'logo_path',
        'email', 'phone', 'address', 'status', 'commission_percentage',
        'approved_at', 'approved_by',
        'bank_account_name', 'bank_account_number', 'bank_name',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'commission_percentage' => 'decimal:2',
    ];

    /**
     * Boot method - auto generate slug dari name
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($org) {
            if (empty($org->slug)) {
                $org->slug = static::generateUniqueSlug($org->name);
            }
        });
    }

    public static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    // === RELATIONS ===
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot('role', 'invited_at', 'joined_at')
            ->withTimestamps();
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->wherePivot('role', 'owner')
            ->withPivot('role', 'invited_at', 'joined_at');
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // === SCOPES ===
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // === HELPERS ===
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasUser($userId): bool
    {
        return $this->users()->where('users.id', $userId)->exists();
    }

    public function getUserRole($userId)
    {
        $pivot = $this->users()->where('users.id', $userId)->first()?->pivot;
        return $pivot ? $pivot->role : null;
    }

    public function totalRevenue()
    {
        return $this->transactions()
            ->where('status', 'success')
            ->sum('net_income') ?? 0;
    }

    public function totalTicketsSold()
    {
        return $this->transactions()
            ->where('status', 'success')
            ->count() ?? 0;
    }

    public function pendingPayoutAmount()
    {
        return $this->transactions()
            ->where('status', 'success')
            ->whereNull('payout_id')
            ->sum('net_income') ?? 0;
    }
}
