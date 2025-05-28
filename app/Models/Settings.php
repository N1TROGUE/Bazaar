<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'user_id',
        'logo_path',
        'nav_color',
        'button_color'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
