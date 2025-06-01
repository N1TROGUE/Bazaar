<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function bidder()
    {
        return $this->belongsTo(User::class, 'bidder_id');
    }
}
