<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Storage;


class SettingsController extends Controller
{
    //GET
    public function showSettings()
    {
        $settings = Settings::first();

        return view('theme-settings.settings', compact('settings'));
    }

    // POST: Update de settings in de database
    public function updateSettings(Request $request)
    {
        $messages = [
            'button_color.required' => 'Het is verplicht een knop kleur te kiezen',
            'nav_color.required' => 'Het is verplicht een navigatie kleur te kiezen.',
            'logo.mimes' => 'Het bestand moet van het type JPG of PNG zijn.',
            'logo.image' => 'Het bestand moet een image zijn.',
            'logo.max' => 'Het bestand mag niet groter zijn dan 5MB'
        ];

        $request->validate([
            'logo' => 'nullable|image|mimes:svg,jpeg,png,jpg|max:5120', // max 5MB
            'nav_color' => 'required|string',
            'button_color' => 'required|string',
        ], $messages);

        // Haal de eerste instellingen op, of maak nieuwe aan
        $settings = Settings::first() ?? new Settings();

        // Logo uploaden indien aanwezig
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public'); // opslag in storage/app/public/logos
            $settings->logo_path = Storage::url($path); // maakt /storage/logos/...
        } elseif (!$settings->logo_path) {
            // fallback als er nog geen logo was
            $settings->logo_path = 'https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500';
        }

        // Overige settings opslaan
        $settings->nav_color = $request->input('nav_color');
        $settings->button_color = $request->input('button_color');

        $settings->save();

        return redirect()->route('settings.show')->with('success', 'Instellingen succesvol bijgewerkt!');
    }

    

}
