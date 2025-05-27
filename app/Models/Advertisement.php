<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'allow_bids' => 'boolean',
        'expiration_date' => 'datetime',
    ];

    /**
     * The user that owns the advertisement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Category of the advertisement.
     */
    public function category()
    {
        return $this->belongsTo(AdvertisementCategory::class, 'advertisement_category_id');
    }

    /**
     * The users that have favorited this advert.
     */
    public function favoritedByUsers() {
        return $this->belongsToMany(User::class, 'favorite_advertisements', 'advertisement_id', 'user_id')->withTimestamps();
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function rentals() {
        return $this->hasMany(Rental::class);
    }

    /**
     * Check if the advertisement is available for rent during the specified period.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return bool
     */
    public function isAvailableForRent(Carbon $startDate, Carbon $endDate): bool
    {
        return !$this->rentals()->where(function ($query) use ($startDate, $endDate) {
            // Check if the rental period overlaps with existing rentals
            $query->whereBetween('rented_from', [$startDate, $endDate])
                  ->orWhereBetween('rented_until', [$startDate, $endDate])
                  ->orWhere(function ($query) use ($startDate, $endDate) {
                      $query->where('rented_from', '<=', $startDate)
                            ->where('rented_until', '>=', $endDate);
                  });
        })->exists();
    }
}
