<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Bid;
use Auth;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function store(Request $request, Advertisement $advertisement)
    {
        // Check if the advertisement allows bids, if null it means bidding is allowed
        $response = $advertisement->canPlaceBid();

        if ($response !== null) {
            return back()->with('error', $response)->withInput();
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:' . $advertisement->getMinimumNextBid()],
        ], [
            'amount.required' => 'Een bodbedrag is verplicht.',
            'amount.numeric' => 'Het bodbedrag moet een getal zijn.',
            'amount.min' => 'Je bod moet hoger zijn dan het huidige hoogste bod of de startprijs. Minimaal: €' . number_format($advertisement->getMinimumNextBid(), 2, ',', '.'),
        ]);

        $highestBid = $advertisement->highestBid();

        if ($highestBid && $validated['amount'] <= $highestBid->amount) {
             return back()->with('error', 'Je bod moet hoger zijn dan het huidige hoogste bod van €' . number_format($highestBid->amount, 2, ',', '.') . '.')->withInput();
        }

        if (!$highestBid && $validated['amount'] < $advertisement->price) {
             return back()->with('error', 'Je bod moet gelijk zijn aan of hoger zijn dan de startprijs van €' . number_format($advertisement->price, 2, ',', '.') . '.')->withInput();
        }


        Bid::create([
            'advertisement_id' => $advertisement->id,
            'bidder_id' => Auth::id(),
            'amount' => $validated['amount'],
        ]);

        return back()->with('success', 'Bod geplaatst!');
    }
}
