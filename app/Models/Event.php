<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'category_id',
        'organization_id',
        'title',
        'description',
        'date',
        'location',
        'price',
        'stock',
        'poster_path',
        'average_rating',
        'total_reviews',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Event has many reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relationship: Event has many transactions (BARU)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get successful transactions only
     */
    public function successfulTransactions()
    {
        return $this->hasMany(Transaction::class)->where('status', 'success');
    }

    /**
     * Get average rating for this event
     */
    public function getAverageRating()
    {
        return $this->average_rating ?? 0;
    }

    /**
     * Get total reviews for this event
     */
    public function getTotalReviews()
    {
        return $this->total_reviews ?? 0;
    }

    /**
     * Get verified purchase reviews only
     */
    public function verifiedReviews()
    {
        return $this->reviews()->where('is_verified_purchase', true);
    }

    /**
     * Get reviews sorted by newest
     */
    public function getReviewsNewest()
    {
        return $this->reviews()
            ->where('is_verified_purchase', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // ============================================
    // MULTI-TENANT (BARU)
    // ============================================

    /**
     * Event ini milik organization yang mana
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
