<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertisementApiController extends Controller
{
    public function index(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        if ($apiKey !== env('API_KEY')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = Auth::user()->id;

        $advertisements = Advertisement::where('status', 'active')
            ->where('user_id', $userId)  // Pas hier je user_id aan als nodig
            ->paginate(10);

        return response()->json($advertisements);
    }
}
