<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementCategory extends Model
{
    protected $guarded = [];

    public function advertisements() {
        return $this->hasMany(Advertisement::class);
    }
}
