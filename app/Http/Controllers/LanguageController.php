<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        // Check of de taal beschikbaar is, anders default
        $availableLocales = ['en', 'nl'];
        if (!in_array($locale, $availableLocales)) {
            $locale = config('app.locale'); // default taal uit config/app.php
        }

        // Sla taal op in sessie
        session(['locale' => $locale]);

        // Optioneel: redirect terug naar vorige pagina
        return redirect()->back();
    }
}
