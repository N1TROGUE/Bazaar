<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdvertisementController extends Controller
{
    //GET
    public function index(Request $request)
    {
        $query = Advertisement::query()->where('status', 'active')->filterAndSort($request);
        $advertisements = $query->paginate(10);
        $advertisementCategories = AdvertisementCategory::all();
        $favoriteAdvertisements = collect();

        if (Auth::check()) {
            $user = Auth::user();
            $favoriteAdvertisements = $user->favoriteAdvertisements()->where('status', 'active')->get();
        }

        if ($request->filled('filter')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->filter);
            });
        }

        return view('index', [
            'advertisements' => $advertisements,
            'favoriteAdvertisements' => $favoriteAdvertisements,
            'advertisementCategories' => $advertisementCategories
        ]);
    }

    public function show(Advertisement $advertisement)
    {
        $url = route('advertisements.show', $advertisement);
        $qrCodeImage = QrCode::format('svg')->size(80)->generate($url);

        return view('advertisements.show', [
            'advertisement' => $advertisement,
            'qrCodeImage' => $qrCodeImage
        ]);
    }

    //GET
    public function showAdvertisements(Request $request)
    {
        $query = Advertisement::where('user_id', Auth::id());

        // Filter op categorie
        if ($request->filled('category_id')) {
            $query->where('advertisement_category_id', $request->category_id);
        }

        // Sortering prijs
        if ($request->filled('sort_price')) {
            $query->orderBy('price', $request->sort_price); // asc of desc
        }

        $advertisements = $query->orderBy('expiration_date')->paginate(4);

        $categories = \App\Models\AdvertisementCategory::all();

        return view('advertisements.my-advertisements', compact('advertisements', 'categories'));
    }


    //GET
    public function create()
    {
        $allAdvertisements = Auth::user()->advertisements()->where('status', 'active')->orderBy('title')->get();

        $categories = AdvertisementCategory::all();
        return view('advertisements.create-advertisement', [
            'categories' => $categories,
            'allAdvertisements' => $allAdvertisements,
        ]);
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
            'expiration_date.after' => 'Vervaldatum moet in de toekomst liggen.',
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
            'related_advertisements' => 'nullable|array',
            'related_advertisements.*' => 'nullable|integer|exists:advertisements,id',
        ], $messages);

        $imagePath = $request->file('image_path')->store('advert_images', 'public');

        $advertisement = Advertisement::create([
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

        if ($request->has('related_advertisements')) {
            $advertisement->relatedAdvertisements()->sync($request->input('related_advertisements'));
        } else {
            $advertisement->relatedAdvertisements()->detach();
        }

        return redirect()->route('advertisements.create')->with('success', 'U heeft successvol een advertentie geplaatst.');
    }
}
