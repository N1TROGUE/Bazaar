<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\LandingPageComponent;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
    //GET
    public function showRegister ()
    {
        $roles = Role::where('name', '!=', 'admin')->get();

        return view('auth.register', compact('roles'));
    }

    //GET
    public function showLogin ()
    {
        return view('auth.login');
    }

    //POST
    public function register(Request $request)
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
            'password.confirmed' => 'Wachtwoorden komen niet overeen.',
            'role_id.exists' => 'Selecteer een geldige rol.',
            'role_id.required' => 'Het is verplicht een rol te kiezen'
        ];

        // TODO: The register method uses Validator::make(). Consider using a Form Request class (e.g., RegisterUserRequest) for cleaner validation logic.
        // validatie handmatig
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ], $messages);

        if ($validator->fails()) {
            // Laad opnieuw de rollen
            $roles = Role::where('name', '!=', 'admin')->get();

            // terug naar formulier mét fouten en rollen
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with(['roles' => $roles]);
        }

        // Gebruiker aanmaken
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
        ]);

        $this->createDefaultLandingPageComponents($user);

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

    /**
     * Create default landing page components for a user.
     */
    private function createDefaultLandingPageComponents(User $user): void
    {
        if (in_array($user->role_id, [3, 4])) {
            $components = ['welcome_message', 'advertisements', 'favorites', 'dashboard_image'];
            foreach ($components as $componentType) {
                $settings = [];
                if ($componentType === 'dashboard_image') {
                    $settings = ['path' => 'dashboard_images/dashboard_image.png'];
                }

                LandingPageComponent::create([
                    'user_id' => $user->id,
                    'component_type' => $componentType,
                    'is_active' => true,
                    'data' => $settings,
                ]);
            }
        }
    }

}
