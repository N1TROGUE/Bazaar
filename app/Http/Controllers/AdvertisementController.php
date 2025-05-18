<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    //GET
    public function index() {
        $advertisements = Advertisement::paginate(10);

        return view('index', [
            'advertisements' => $advertisements
        ]);
    }

    //GET
    public function create()
    {
        $categories = AdvertisementCategory::all();
        return view('advertisements.create-advertisement', compact('categories'));
    }

    //POST
    public function store(Request $request)
    {
    $messages = [
        'title.required' => 'Titel is verplicht.',
        'title.string' => 'Titel moet een geldige tekst zijn.',
        'title.max' => 'Titel mag niet langer zijn dan 255 tekens.',
        'description.required' => 'Beschrijving is verplicht.',
        'description.string' => 'Beschrijving moet een geldige tekst zijn.',
        'advertisement_category_id.required' => 'Selecteer een categorie.',
        'advertisement_category_id.exists' => 'Geselecteerde categorie bestaat niet.',
        'price.required' => 'Prijs is verplicht.',
        'price.numeric' => 'Prijs moet een numerieke waarde zijn.',
        'image_path.required' => 'Een afbeelding is verplicht.',
        'image_path.image' => 'Het bestand moet een afbeelding zijn.',
        'image_path.max' => 'De afbeelding mag niet groter zijn dan 5MB.',
        'ad_type.required' => 'Kies een type advertentie.',
        'ad_type.in' => 'Advertentietype moet "sale" of "rental" zijn.',
        'rental_min_duration_hours.required_if' => 'Minimale huurperiode is verplicht bij een huuradvertentie.',
        'rental_max_duration_hours.required_if' => 'Maximale huurperiode is verplicht bij een huuradvertentie.',
        'expiration_date.required' => 'Vervaldatum is verplicht.',
        'expiration_date.date' => 'Vervaldatum moet een geldige datum zijn.',
        'expiration_date.after' => 'Vervaldatum moet in de toekomst liggen.'
    ];

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'advertisement_category_id' => 'required|exists:advertisement_categories,id',
        'price' => 'required|numeric',
        'image_path' => 'required|image|max:5120', // max 5MB
        'ad_type' => 'required|in:sale,rental',
        'rental_min_duration_hours' => 'required_if:ad_type,rental|nullable|integer',
        'rental_max_duration_hours' => 'required_if:ad_type,rental|nullable|integer',
        'expiration_date' => 'required|date|after:today',
    ], $messages);

    $imagePath = $request->file('image_path')->store('advert_images', 'public');

    Advertisement::create([
        'title' => $request->title,
        'description' => $request->description,
        'advertisement_category_id' => $request->advertisement_category_id,
        'user_id' => Auth::id(), // Of een andere logica voor toewijzing
        'price' => $request->price,
        'image_path' => $imagePath,
        'ad_type' => $request->ad_type,
        'rental_min_duration_hours' => $request->ad_type === 'rental' ? $request->rental_min_duration_hours : null,
        'rental_max_duration_hours' => $request->ad_type === 'rental' ? $request->rental_max_duration_hours : null,
        'status' => 'active',
        'expiration_date' => $request->expiration_date,
    ]);

    return redirect()->route('index');
}

}
