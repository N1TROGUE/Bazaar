<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
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

    /**
     * Store a newly created rental in storage.
     */
    public function store(Request $request, Advertisement $advertisement)
    {
        $request->validate([
            'rented_from' => ['required', 'date', 'after_or_equal:today'],
            'rented_until' => ['required', 'date', 'after:rented_from']
        ]);

        $rentedFrom = Carbon::parse($request->input('rented_from'));
        $rentedUntil = Carbon::parse($request->input('rented_until'));

        if (!$advertisement->isAvailableForRent($rentedFrom, $rentedUntil)) {
            return back()->withInput()->with('error_rent', 'Sorry, dit product is beschikbaar voor de gekozen periode.');
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

    public function handInRental(Request $request, Rental $rental)
    {
        $request->validate([
            'handed_in_at' => ['required', 'date', 'after_or_equal:' . $rental->rented_until]
        ], [
            'handed_in_at.required' => 'De inleverdatum is verplicht.',
            'handed_in_at.date' => 'De inleverdatum moet een geldige datum zijn.',
            'handed_in_at.after_or_equal' => 'De inleverdatum moet op of na het einde van de huurperiode liggen.'
        ]);

        $handedInAt = Carbon::parse($request->input('handed_in_at'));

        $rental->update([
            'status' => 'completed',
            'handed_in_at' => $handedInAt
        ]);

        return redirect()->route('advertisements.index')->with('success', 'Huur succesvol ingeleverd!');
    }
}
