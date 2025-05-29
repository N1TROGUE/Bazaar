<?php

namespace App\Http\Controllers;

use App\Models\AdvertisementCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display the authenticated user's order history (items they bought).
     */
    public function index(Request $request)
    {
        $query = Auth::user()->orders()->latest();
        $advertisementCategories = AdvertisementCategory::all();

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('final_price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('final_price', 'desc');
                    break;
                case 'date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->latest(); // Default sorting
            }
        }

        if ($request->filled('filter')) {
            $query->whereHas('advertisement.category', function ($q) use ($request) {
                $q->where('name', $request->filter);
            });
        }

        $orders = $query->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
            'advertisementCategories' => $advertisementCategories,
        ]);
    }
}
