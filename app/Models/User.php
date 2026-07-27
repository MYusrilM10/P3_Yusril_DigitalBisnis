<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'avatar',
        'average_rating',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship: User has many reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get organizer events (through relationship)
     */
    public function events()
    {
        // Assuming user_id or organizer_id field in events table
        // For now, this is a placeholder - adjust based on your actual structure
        // If you add organizer_id to events table, use:
        // return $this->hasMany(Event::class, 'organizer_id');
        return $this->hasMany(Event::class);
    }

    /**
     * Get average rating for this organizer
     */
    public function getAverageRating()
    {
        return $this->average_rating ?? 0;
    }

    /**
     * Check if organizer is trusted (rating >= 4.5)
     */
    public function isTrustedOrganizer()
    {
        return $this->average_rating >= 4.5;
    }

    // ============================================
    // MULTI-TENANT RELATIONS (BARU)
    // ============================================

    /**
     * Organizations yang user ini jadi anggotanya
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
            ->withPivot('role', 'invited_at', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Organization yang user miliki (sebagai owner)
     */
    public function ownedOrganizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
            ->wherePivot('role', 'owner')
            ->withPivot('role', 'invited_at', 'joined_at');
    }

    /**
     * Check apakah user adalah superadmin
     */
    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check apakah user adalah panitia
     */
    public function isPanitia(): bool
    {
        return $this->role === 'panitia';
    }

    /**
     * Check apakah user adalah owner dari org tertentu
     */
    public function isOwnerOf(int $organizationId): bool
    {
        return $this->organizations()
            ->where('organizations.id', $organizationId)
            ->wherePivot('role', 'owner')
            ->exists();
    }

    /**
     * Check apakah user adalah anggota org tertentu
     */
    public function isMemberOf(int $organizationId): bool
    {
        return $this->organizations()
            ->where('organizations.id', $organizationId)
            ->exists();
    }

    /**
     * Get role user dalam org tertentu
     */
    public function roleIn(int $organizationId)
    {
        $pivot = $this->organizations()
            ->where('organizations.id', $organizationId)
            ->first()?->pivot;
        return $pivot ? $pivot->role : null;
    }
}

