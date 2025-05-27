<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'logo_path',
        'nav_color',
        'button_color'
    ];
}
