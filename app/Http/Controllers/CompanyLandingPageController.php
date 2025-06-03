<?php

namespace App\Http\Controllers;

use App\Models\AdvertisementCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyLandingPageController extends Controller
{
    /**
     * Show the landing page for a company with its advertisements.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function show(Request $request, string $slug)
    {
        // Manually fetch the user by the slug
        $companyProfileUser = User::where('slug', $slug)->firstOrFail();

        $advertisementCategories = AdvertisementCategory::all();
        $favoriteAdvertisements = collect();
        $components  = $companyProfileUser->landingPageComponents()
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Advertisements belonging to the company
        $query = $companyProfileUser->advertisements()
                                   ->where('status', 'active')
                                   ->filterAndSort($request);

        $advertisements = $query->paginate(10);

        if (Auth::check()) {
            $loggedInUser = Auth::user();
            $favoriteAdvertisements = $loggedInUser->favoriteAdvertisements()->get();
        }

        return view('dashboard', [
            'company' => $companyProfileUser,
            'advertisements' => $advertisements,
            'advertisementCategories' => $advertisementCategories,
            'favoriteAdvertisements' => $favoriteAdvertisements,
            'components' => $components,
        ]);
    }
}
