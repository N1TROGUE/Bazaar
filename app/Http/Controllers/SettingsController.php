<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


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
        $user = Auth::user();

        $messages = [
            'button_color.required' => 'Het is verplicht een knop kleur te kiezen',
            'nav_color.required' => 'Het is verplicht een navigatie kleur te kiezen.',
            'logo.mimes' => 'Het bestand moet van het type JPG of PNG zijn.',
            'logo.image' => 'Het bestand moet een image zijn.',
            'logo.max' => 'Het bestand mag niet groter zijn dan 5MB',
            'company_slug.required' => 'Het is verplicht een URL-slug in te vullen.',
            'company_slug.string' => 'De URL moet een tekst zijn.',
            'company_slug.alpha_dash' => 'De URL mag alleen letters, cijfers, \'/\' en \'_\' bevatten.',
            'company_slug.min' => 'De URL moet minimaal 3 tekens bevatten.',
            'company_slug.max' => 'De URL mag maximaal 50 tekens bevatten.',
            'company_slug.unique' => 'Deze URL is al in gebruik.',
        ];

        $request->validate([
            'logo' => 'nullable|image|mimes:svg,jpeg,png,jpg|max:5120', // max 5MB
            'nav_color' => 'required|string',
            'button_color' => 'required|string',
            'company_slug' => [
                'required', 'string', 'alpha_dash', 'min:3', 'max:50', // alpha_dash laat alleen letters, cijfers, streepjes en underscores toe
                Rule::unique('users', 'slug')->ignore($user->id), // Ensure slug is unique, ignoring current user
                function ($attribute, $value, $fail) {
                    $reservedSlugs = ['admin', 'login', 'register', 'profile', 'api']; // Gereserveerde slugs (mogen niet gebruikt worden)
                    if (in_array(strtolower($value), $reservedSlugs)) {
                        $fail("De gekozen URL-slug '{$value}' is gereserveerd.");
                    }
                    if (Str::startsWith($value, '_') || Str::endsWith($value, '_') ||
                        Str::startsWith($value, '-') || Str::endsWith($value, '-')) {
                        $fail("De URL-slug mag niet beginnen of eindigen met een underscore of streepje.");
                    }
                },
            ],
        ], $messages);

        // Haal de eerste instellingen op, of maak nieuwe aan
        $settings = Settings::firstOrNew(['user_id' => $user->id]);

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
        $settings->user_id = $user->id;

        $settings->save();

        // Update de slug van de gebruiker
        $slug = Str::slug($request->input('company_slug'));
        $user->slug = $slug;
        $user->save();

        $component = $user->landingPageComponents;

        if ($request->filled('welcome_message')) {
            $component->welcome_message = $request->input('welcome_message');
        }

        if ($request->hasFile('welcome_image')) {
            $imagePath = $request->file('welcome_image')->store('welcome_images', 'public');
            $component->welcome_image = Storage::url($imagePath);
        }

        $component->save();

        return back()->with('success', 'Instellingen succesvol bijgewerkt!');
    }
}
