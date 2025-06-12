<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\LandingPageComponent; // Import LandingPageComponent
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class SettingsController extends Controller
{
    //GET
    public function showSettings()
    {
        $user = Auth::user();
        $settings = Settings::where('user_id', $user->id)->first();

        if (!$settings) {
            $settings = new Settings([
                'nav_color' => '#1f2937', // Default nav color
                'button_color' => '#4f46e5', // Default button color
            ]);
        }

        $landingPageComponents = $user->landingPageComponents;

        $dashboardImageComponentPath = $landingPageComponents->where('component_type', 'dashboard_image')->first()?->data['path'] ?? null;

        return view('theme-settings.settings', [
            'settings' => $settings,
            'landingPageComponents' => $landingPageComponents,
            'dashboardImage' => $dashboardImageComponentPath,
        ]);
    }

    // POST: Update de settings in de database
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $messages = [
            'button_color.required' => 'Het is verplicht een knop kleur te kiezen',
            'nav_color.required' => 'Het is verplicht een navigatie kleur te kiezen.',
            'logo.mimes' => 'Het bestand moet van het type JPG, PNG of SVG zijn.',
            'logo.image' => 'Het bestand moet een image zijn.',
            'logo.max' => 'Het bestand mag niet groter zijn dan 5MB',
            'company_slug.required' => 'Het is verplicht een URL-slug in te vullen.',
            'company_slug.string' => 'De URL moet een tekst zijn.',
            'company_slug.alpha_dash' => 'De URL mag alleen letters, cijfers, \'/\' en \'_\' bevatten.',
            'company_slug.min' => 'De URL moet minimaal 3 tekens bevatten.',
            'company_slug.max' => 'De URL mag maximaal 50 tekens bevatten.',
            'company_slug.unique' => 'Deze URL is al in gebruik.',
            'dashboard_image.image' => 'Het dashboard afbeelding bestand moet een afbeelding zijn.',
            'dashboard_image.mimes' => 'Het dashboard afbeelding bestand moet van het type JPG, PNG, GIF of SVG zijn.',
            'dashboard_image.max' => 'De dashboard afbeelding mag niet groter zijn dan 5MB.',
        ];

        $request->validate([
            'logo' => 'nullable|image|mimes:svg,jpeg,png,jpg|max:5120', // max 5MB
            'nav_color' => 'required|string',
            'button_color' => 'required|string',
            'dashboard_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // Validation for dashboard_image
            'company_slug' => [
                'required', 'string', 'alpha_dash', 'min:3', 'max:50',
                Rule::unique('users', 'slug')->ignore($user->id),
                function ($attribute, $value, $fail) {
                    $reservedSlugs = ['admin', 'login', 'register', 'profile', 'api'];
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

        $settings = Settings::firstOrNew(['user_id' => $user->id]);

        if ($request->hasFile('logo')) {
            // Delete old logo if it exists and is not the default
            if ($settings->logo_path && $settings->logo_path !== 'https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500' && Storage::disk('public')->exists(str_replace('/storage/', '', $settings->logo_path))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $settings->logo_path));
            }
            $path = $request->file('logo')->store('logos', 'public');
            $settings->logo_path = Storage::url($path);
        } elseif (!$settings->exists || !$settings->logo_path) { // Set default only if no logo ever set or record is new
            $settings->logo_path = 'https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500';
        }

        $settings->nav_color = $request->input('nav_color');
        $settings->button_color = $request->input('button_color');
        // user_id is already set by firstOrNew or should be $settings->user_id = $user->id; if new
        if (!$settings->exists) {
            $settings->user_id = $user->id;
        }
        $settings->save();

        $user->slug = Str::slug($request->input('company_slug'));
        $user->save();

        // Handle Landing Page Components
        // 1. Dashboard Image
        if ($request->hasFile('dashboard_image')) {
            $dashboardComponent = $user->landingPageComponents()
                                        ->firstOrNew(['component_type' => 'dashboard_image']);

            // Delete old dashboard image if it exists
            if (isset($dashboardComponent->data['path']) && Storage::disk('public')->exists($dashboardComponent->data['path'])) {
                Storage::disk('public')->delete($dashboardComponent->data['path']);
            }

            $imagePath = $request->file('dashboard_image')->store('dashboard_images', 'public');
            $dashboardComponent->data = ['path' => $imagePath];
            $dashboardComponent->save();
        }

        // 2. Update is_active for all components
        // Fetch fresh list of components to ensure it includes any newly created ones
        $allLandingPageComponents = $user->landingPageComponents()->get();
        foreach ($allLandingPageComponents as $component) {
            $checkboxName = $component->component_type . '_enabled';
            $component->is_active = $request->has($checkboxName);
            $component->save();
        }

        return back()->with('success', 'Instellingen succesvol bijgewerkt!');
    }
}
