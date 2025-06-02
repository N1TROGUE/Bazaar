<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class UserReviewController extends Controller
{
    public function createFromOrder(Order $order)
    {
        $reviewer = auth()->user();
        $seller = $order->seller;

        if (!$reviewer->canReviewSeller($order))
        {
            return back()->with('error', 'Je komt op dit moment niet in aanmerking om deze verkoper te beoordelen, of je hebt al een beoordeling ingediend.');
        }

        return view('user-reviews.create', [ // A generic review form
            'reviewer' => $reviewer,
            'reviewee' => $seller,
            'order' => $order,
        ]);
    }

    public function storeFromOrder(Request $request, Order $order)
    {
        $reviewer = auth()->user();
        $seller = $order->seller;

        if (!$reviewer->canReviewSeller($order))
        {
            return back()->with('error', 'Je komt op dit moment niet in aanmerking om deze verkoper te beoordelen, of je hebt al een beoordeling ingediend.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:255']
        ], [
            'rating.required' => 'Rating is verplicht.',
            'rating.integer' => 'Rating moet een getal zijn.',
            'rating.between' => 'Rating moet tussen 1 en 5 zijn.',
            'comment.string' => 'Opmerking moet tekst zijn.',
            'comment.max' => 'Opmerking mag maximaal 255 tekens bevatten.'
        ]);

        $seller->reviewsAsSeller()->create([
            'reviewer_id' => $reviewer->id,
            'seller_id' => $seller->id,
            'order_id' => $order->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('orders.index', $order)->with('success', 'Beoordeling succesvol geplaatst!');
    }

}
