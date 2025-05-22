<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class FavoriteAdvertisementController extends Controller
{
    public function toggle(Advertisement $advertisement) {
        $user = auth()->user();

        if ($user->favoriteAdverts()->where('advertisement_id', $advertisement->id)->exists()) {
            $user->favoriteAdverts()->detach($advertisement->id);
            return redirect()->route('advertisements.show', $advertisement);
        }

        $user->favoriteAdverts()->attach($advertisement->id);
        return redirect()->route('advertisements.show', $advertisement);
    }
}
