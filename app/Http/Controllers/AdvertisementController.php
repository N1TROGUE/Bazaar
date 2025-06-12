<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdvertisementController extends Controller
{
    //GET
    public function index(Request $request)
    {
        $query = Advertisement::query()->where('status', 'active')->filterAndSort($request);
        $advertisements = $query->paginate(12);
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

    //GET
    public function showCSV()
    {
        return view('advertisements.upload-csv');
    }

    //POST
    public function store(Request $request)
    {
        //Maximaal 4 adv
        $userAdCount = Advertisement::where('user_id', Auth::id())
            ->where('status', 'active')
            ->count();

        if($userAdCount >= 4)
        {
            return redirect()->back()->withErrors(['max_ads' => 'U heeft het maximum van 4 actieve advertenties al bereikt.'])->withInput();
        }

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


    public function uploadCSV(Request $request)
    {
        // Validatie van het bestand
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // max 10MB
        ]);

        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');

        $header = fgetcsv($file); // Eerste rij met kolomnamen
        if (!$header || count($header) < 7) {
            return back()->withErrors(['csv' => 'CSV-bestand moet minimaal 7 kolommen bevatten.']);
        }

        $requiredColumns = ['title', 'description', 'advertisement_category_id', 'price', 'ad_type', 'expiration_date', 'image_path'];
        foreach ($requiredColumns as $column) {
            if (!in_array($column, $header)) {
                return back()->withErrors(['csv' => "Verplichte kolom ontbreekt: $column"]);
            }
        }

        $userId = Auth::id();
        $activeAds = Advertisement::where('user_id', $userId)->where('status', 'active')->count();
        $maxAds = 4;
        $imported = 0;
        $rowIndex = 1;
        $errors = [];

        while (($row = fgetcsv($file)) !== false) {
            $rowIndex++;
            if ($activeAds + $imported >= $maxAds) {
                break;
            }

            $data = array_combine($header, $row);

            // Individuele rijvalidatie
            $validator = Validator::make($data, [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'advertisement_category_id' => 'required|exists:advertisement_categories,id',
                'price' => 'required|numeric',
                'ad_type' => 'required|in:sale,rental',
                'rental_min_duration_hours' => 'nullable|integer',
                'rental_max_duration_hours' => 'nullable|integer',
                'expiration_date' => 'required|date|after:today',
                // image_path wordt gebruikt als dummy pad
            ]);

            if ($validator->fails()) {
                $errors[] = "Rij $rowIndex: " . implode(', ', $validator->errors()->all());
                continue;
            }

            // Dummy-afbeelding (later vervangen door echte upload of URL-logica)
            $defaultImage = 'advert_images/default.jpg'; // Zorg dat deze bestaat in storage/app/public

            Advertisement::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'advertisement_category_id' => $data['advertisement_category_id'],
                'user_id' => $userId,
                'price' => $data['price'],
                'image_path' => $defaultImage,
                'ad_type' => $data['ad_type'],
                'rental_min_duration_hours' => $data['ad_type'] === 'rental' ? ($data['rental_min_duration_hours'] ?? null) : null,
                'rental_max_duration_hours' => $data['ad_type'] === 'rental' ? ($data['rental_max_duration_hours'] ?? null) : null,
                'status' => 'active',
                'expiration_date' => $data['expiration_date'],
            ]);

            $imported++;
        }

        fclose($file);

        if ($imported === 0) {
            return back()->withErrors(['csv' => 'Geen advertenties geïmporteerd. ' . ($errors ? implode(' ', $errors) : '')]);
        }

        $message = "$imported advertentie(s) succesvol geïmporteerd.";
        if ($errors) {
            $message .= ' Sommige rijen zijn overgeslagen: ' . implode(' ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }
}
