<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Advertisement $advertisement)
    {
        $request->validate([
           'rating' => ['integer', 'between:1,5'],
           'comment' => ['string', 'max:255']
        ]);

        if (Auth::user()->hasReviewed($advertisement))
        {
            return back()->with('error', 'You have already reviewed this advert.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'advertisement_id' => $advertisement->id,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment')
        ]);

        return back();
    }
}
