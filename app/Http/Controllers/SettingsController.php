<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends Controller
{
    //GET
    public function showSettings()
    {
        return view('theme-settings.settings');
    }
}
