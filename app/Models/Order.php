<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'final_price' => 'decimal:2',
    ];

    /**
     * The user (buyer) who placed the order.
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, "buyer_id");
    }

    /**
     * The user (seller) who sold the advertisement.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * The advertisement that was ordered.
     */
    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
