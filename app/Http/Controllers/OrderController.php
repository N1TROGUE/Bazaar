<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\AdvertisementCategory;
use App\Models\Order;
use DB;
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

        // TODO: Move into function
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

    public function create(Advertisement $advertisement)
    {
        if ($advertisement->user_id === Auth::id()) {
            return redirect()->route('advertisements.show', $advertisement)->with('error', 'Je kunt je eigen advertentie niet kopen.');
        }

        return view('orders.create', [
            'advertisement' => $advertisement,
        ]);
    }

    public function store(Request $request, Advertisement $advertisement)
    {
        try {
            DB::transaction(function () use ($advertisement) {
                // Create the order
                Order::create([
                    'buyer_id' => Auth::id(),
                    'seller_id' => $advertisement->user_id,
                    'advertisement_id' => $advertisement->id,
                    'final_price' => $advertisement->price,
                ]);

                $advertisement->status = 'sold';
                $advertisement->save();
            });
        } catch (\Exception $e) {
            \Log::error('Order creation failed: ' . $e->getMessage());
            return redirect()->route('advertisements.show', $advertisement)->with('error', 'Er is een fout opgetreden bij het verwerken van je aankoop. Probeer het later opnieuw.');
        }

        return redirect()->route('advertisements.index')->with('success', 'Aankoop succesvol! "' . $advertisement->title . '" is toegevoegd aan je bestellingen.');
    }
}
