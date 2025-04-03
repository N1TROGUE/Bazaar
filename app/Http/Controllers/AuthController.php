<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //GET
    public function showRegister ()
    {
        return view('auth.register');
    }

    //GET
    public function showLogin ()
    {
        return view('auth.login');
    }

    //POST
    public function register (Request $request)
    {
        $messages = [
            'name.required' => 'Naam is verplicht.',
            'name.string' => 'Naam moet een geldige tekst zijn.',
            'name.max' => 'Naam mag niet langer zijn dan 255 tekens.',
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'E-mailadres moet een geldig e-mailadres zijn.',
            'email.unique' => 'Dit e-mailadres is al in gebruik.',
            'password.required' => 'Wachtwoord is verplicht.',
            'password.string' => 'Wachtwoord moet een geldige tekst zijn.',
            'password.min' => 'Wachtwoord moet minimaal 8 tekens bevatten.',
            'password.confirmed' => 'Wachtwoorden komen niet overeen.'
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'in:particulier,zakelijk',
        ], $messages);

        $user = User::create($validated);

        Auth::login($user);

        return redirect()->route('index');
    }

    //POST
    public function login (Request $request)
    {
        $messages = [
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'E-mailadres moet een geldig e-mailadres zijn.',
            'password.required' => 'Wachtwoord is verplicht.',
            'password.string' => 'Wachtwoord moet een geldige tekst zijn.',
        ];

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ], $messages);

        if(Auth::attempt($validated))
        {
            $request->session()->regenerate();

            return redirect()->route('index');
        } 

        throw ValidationException::withMessages([
            'credentials' => 'Je inloggegevens zijn incorrect.'
        ]);
    }

    //POST
    public function logout (Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('show.login'); 
    }

}
