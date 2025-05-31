<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class UserReviewController extends Controller
{
    public function createFromOrder(Order $order)
    {
        $user = auth()->user();

        if (!$user->canReviewSeller($order))
        {
            return back()->with('error', 'Je komt op dit moment niet in aanmerking om deze verkoper te beoordelen, of je hebt al een beoordeling ingediend.');
        }

        return view('.create', [ // A generic review form
            'reviewer' => $reviewer,
            'reviewee' => $seller,
            'order' => $order, // Pass order for context and to link the review
            // 'advertisement' => $order->advertisement, // Also for context if needed in the form
        ]);
    }


}
