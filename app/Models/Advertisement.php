<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;

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
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorite_advertisements', 'advertisement_id', 'user_id')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function rentals()
    {
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

    /**
     * Scope a query to filter and sort advertisements based on request parameters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    #[Scope]
    protected function filterAndSort($query, $request)
    {
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->latest();
            }
        }

        if ($request->filled('filter')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->filter);
            });
        }

        return $query;
    }

    public function canPlaceBid(): ?RedirectResponse
    {
        if ($this->ad_type !== 'sale') {
            return back()->with('error', 'Op dit item kan niet geboden worden (alleen voor verkoop items).');
        }

        if ($this->status !== 'active') {
            return back()->with('error', 'Bieden op dit item is niet meer mogelijk (niet actief).');
        }

        if ($this->user_id === Auth::id()) {
            return back()->with('error', 'Je kunt niet op je eigen advertentie bieden.');
        }

        if ($this->auction_ends_at && $this->auction_ends_at->isPast()) {
            return back()->with('error', 'De veiling voor dit item is afgelopen.');
        }

        return null;
    }

    public function bids()
    {
        return $this->hasMany(Bid::class)->orderBy('amount', 'desc');
    }

    /**
     * Get the highest bid for this advertisement.
     */
    public function highestBid()
    {
        return $this->bids()->orderBy('amount', 'desc')->first();
    }


    public function getMinimumNextBid(): float
    {
        $highestBid = $this->highestBid();
        $currentPrice = $highestBid ? $highestBid->amount : $this->price;
        return (float)$currentPrice + 0.01;
    }

    /**
     * The advertisements that are related to this advertisement.
     * (i.e., items selected TO BE SHOWN WITH this one)
     */
    public function relatedAdvertisements()
    {
        return $this->belongsToMany(
            __CLASS__, // Related model
            'advertisement_related_advertisement', // Pivot table name
            'advertisement_id', // Foreign key on pivot table for THIS model
            'related_advertisement_id' // Foreign key on pivot table for the RELATED model
        )->withTimestamps(); // If you added timestamps to the pivot
    }

    /**
     * The advertisements that THIS advertisement is related TO.
     * (i.e., if this ad was selected as a related item by OTHERS)
     */
    public function relatedTo()
    {
        return $this->belongsToMany(
            __CLASS__,
            'advertisement_related_advertisement',
            'related_advertisement_id', // Flipped keys
            'advertisement_id'
        )->withTimestamps();
    }
}
