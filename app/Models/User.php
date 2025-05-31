<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'role_id',
        'slug'
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
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isAdmin(): bool
    {
        return $this->role_id === 4; //
    }

    public function isAdminOrBusiness(): bool
    {
        return in_array($this->role_id, [3, 4]);
    }

    public function isAdvertiser(): bool
    {
        return in_array($this->role_id, [2, 3]);
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    /**
     * The adverts that the user has favorited.
     */
    public function favoriteAdverts()
    {
        return $this->belongsToMany(Advertisement::class, 'favorite_advertisements', 'user_id', 'advertisement_id')->withTimestamps();
    }

    public function hasFavorited(Advertisement $advertisement): bool
    {
        return $this->favoriteAdverts()->where('advertisement_id', $advertisement->id)->exists();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function hasReviewed(Advertisement $advertisement): bool
    {
        return $this->reviews()->where('advertisement_id', $advertisement->id)->exists();
    }

    /**
     * The advertisements that the user has rented.
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Get the orders placed by the user (as a buyer).
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    /**
     * Get the orders received by the user (as a seller).
     */
    public function sales()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    /**
     * The advertisements that the user has favorited.
     */
    public function favoriteAdvertisements()
    {
        return $this->belongsToMany(Advertisement::class, 'favorite_advertisements', 'user_id', 'advertisement_id')->withTimestamps();
    }

    public function settings()
    {
        return $this->hasOne(Settings::class);
    }

    /**
     * Get the reviews received by the user as a seller.
     */
    public function reviewsAsSeller()
    {
        return $this->hasMany(UserReview::class, 'seller_id');
    }

    /**
     * Get the reviews written by the user.
     */
    public function writtenSellerReviews()
    {
        return $this->hasMany(UserReview::class, 'reviewer_id');
    }

    public function canReviewSeller(Order $order): bool
    {
        // User must be the buyer and not the seller
        if ($this->id !== $order->buyer_id || $this->id === $order->seller_id) {
            return false;
        }

        // Prevent reviewing the same seller more than once
        if ($this->writtenSellerReviews()->where('seller_id', $order->seller_id)->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Check if the user has written a review for the given user (seller).
     *
     * @param User $seller
     * @return bool
     */
    public function hasReviewedUser(User $seller): bool
    {
        return $this->writtenSellerReviews()->where('seller_id', $seller->id)->exists();
    }

    /**
     * Get the rating given by this user to a specific seller, or null if not reviewed.
     *
     * @param User $seller
     * @return int|null
     */
    public function getSellerReviewRating(User $seller): ?int
    {
        $review = $this->writtenSellerReviews()->where('seller_id', $seller->id)->first();
        return $review->rating ?? null;
    }
}
