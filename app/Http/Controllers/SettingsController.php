<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    //GET
    public function showSettings()
    {
        return view('theme-settings.settings');
    }
}
