<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $guarded = [];

    protected $casts = [
        'rented_from' => 'datetime',
        'rented_until' => 'datetime',
        'returned_at' => 'datetime',
    ];

    /**
     * The user that owns the rental.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The advertisement that is being rented.
     */
    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
