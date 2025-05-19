<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $guarded = [];

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
        return $this->belongsTo(AdvertisementCategory::class);
    }

    /**
     * The users that have favorited this advert.
     */
    public function favoritedByUsers() {
        return $this->belongsToMany(User::class, 'favorite_advertisements', 'advertisement_id', 'user_id')->withTimestamps();
    }
}
