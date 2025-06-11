<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;

class LandingPageComponent extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => AsCollection::class,
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
