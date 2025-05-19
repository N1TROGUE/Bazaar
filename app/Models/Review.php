<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = [];

    /**
     * The user that wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The advertisement that the review is about.
     */
    public function advert() {
        return $this->belongsTo(Advertisement::class);
    }
}
