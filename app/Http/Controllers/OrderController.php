<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display the authenticated user's order history (items they bought).
     */
    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
        ]);
    }
}
