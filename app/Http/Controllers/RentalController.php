<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementCategory;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    /**
     * Show the form for creating a new rental for a specific advertisement.
     */
    public function create(Advertisement $advertisement)
    {
        return view('rentals.create', ['advertisement' => $advertisement]);
    }

    public function showRentals(Request $request)
    {
        $query = Rental::with('advertisement')
            ->whereHas('advertisement', function ($q) {
                $q->where('user_id', Auth::id());
            });

        // Filter op categorie van de gekoppelde advertentie
        if ($request->filled('category_id')) {
            $query->whereHas('advertisement', function ($q) use ($request) {
                $q->where('advertisement_category_id', $request->category_id);
            });
        }

        // Sorteren op prijs van de gekoppelde advertentie
        if ($request->filled('sort_price')) {
            $query->join('advertisements', 'rentals.advertisement_id', '=', 'advertisements.id')
                ->orderBy('advertisements.price', $request->sort_price)
                ->select('rentals.*'); // om dubbele kolommen bij join te vermijden
        } else {
            // Standaard sortering op startdatum
            $query->orderBy('rented_from');
        }

        $rentals = $query->paginate(4)->withQueryString(); // behoud filters bij paginering
        $categories = AdvertisementCategory::all();

        return view('rentals.my-rentals', compact('rentals', 'categories'));
    }

    public function showRented(Request $request)
    {
        $query = Rental::with('advertisement')
            ->where('user_id', Auth::id());


        // Filter op categorie van de gekoppelde advertentie
        if ($request->filled('category_id')) {
            $query->whereHas('advertisement', function ($q) use ($request) {
                $q->where('advertisement_category_id', $request->category_id);
            });
        }

        // Sorteren op prijs van de gekoppelde advertentie
        if ($request->filled('sort_price')) {
            $query->join('advertisements', 'rentals.advertisement_id', '=', 'advertisements.id')
                ->orderBy('advertisements.price', $request->sort_price)
                ->select('rentals.*'); // om dubbele kolommen bij join te vermijden
        } else {
            // Standaard sortering op startdatum
            $query->orderBy('rented_from');
        }

        $rentals = $query->paginate(4)->withQueryString(); // behoud filters bij paginering
        $categories = AdvertisementCategory::all();

        return view('rentals.rented', compact('rentals', 'categories'));
    }

    /**
     * Store a newly created rental in storage.
     */
    public function store(Request $request, Advertisement $advertisement)
    {
        $request->validate([
            'rented_from' => [
                'required',
                'date',
                'after_or_equal:today',
                'before:' . $advertisement->expiration_date
            ],
            'rented_until' => [
                'required',
                'date',
                'after:rented_from',
                'before:' . $advertisement->expiration_date
            ],
        ], [
            'rented_from.required' => 'De begindatum is verplicht.',
            'rented_from.date' => 'De begindatum moet een geldige datum zijn.',
            'rented_from.after_or_equal' => 'De begindatum moet vandaag of later zijn.',
            'rented_from.before' => 'De begindatum moet voor de verloopdatum van de advertentie liggen.',
            'rented_until.required' => 'De einddatum is verplicht.',
            'rented_until.date' => 'De einddatum moet een geldige datum zijn.',
            'rented_until.after' => 'De einddatum moet na de begindatum liggen.',
            'rented_until.before' => 'De einddatum moet voor de verloopdatum van de advertentie liggen.',
        ]);

        $rentedFrom = Carbon::parse($request->input('rented_from'));
        $rentedUntil = Carbon::parse($request->input('rented_until'));

        if (!$advertisement->isAvailableForRent($rentedFrom, $rentedUntil)) {
            return back()->withInput()->with('error_rent', 'Sorry, dit product is niet beschikbaar voor de gekozen periode.');
        }

        Rental::create([
            'user_id' => Auth::id(),
            'advertisement_id' => $advertisement->id,
            'rented_from' => $rentedFrom,
            'rented_until' => $rentedUntil,
            'status' => 'active'
        ]);

        return redirect()->route('advertisements.index')->with('success', 'Huur succesvol bevestigd!');
    }

    /**
     * Show the page to confirm returning a rental and upload an image.
     */
    public function confirmReturn(Rental $rental)
    {
        return view('rentals.confirm-return', compact('rental'));
    }

    public function returnRental(Request $request, Rental $rental)
    {
        $request->validate([
            'return_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validate the image
        ], [
            'return_image.image' => 'Het bestand moet een afbeelding zijn.',
            'return_image.mimes' => 'De afbeelding moet een JPG, JPEG, of PNG bestand zijn.',
            'return_image.max' => 'De afbeelding mag niet groter zijn dan 2MB.',
        ]);

        $handedInAt = Carbon::now();

        $currentWear = $rental->wear_percentage ?? 0;

        $daysRentedDuration = $rental->rented_from->diffInDays($handedInAt, false);

        $wearToAdd = max(0, $daysRentedDuration);

        $wearPercentage = min(100, $currentWear + $wearToAdd);

        $status = $wearPercentage === 100 ? 'worn_out' : 'returned';

        $returnImagePath = $rental->image_path;

        if ($request->hasFile('return_image')) {
            $returnImagePath = $request->file('return_image')->store('return_images', 'public');
        }

        $rental->update([
            'returned_at' => $handedInAt,
            'wear_percentage' => $wearPercentage,
            'status' => $status,
            'image_path' => $returnImagePath,
        ]);

        return redirect()->route('advertisements.index')->with('success', 'Huur succesvol ingeleverd!');
    }
}
