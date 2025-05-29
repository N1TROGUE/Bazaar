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
        $companyProfileUser = User::where('slug', $slug)->first();

        // Handle case where user is not found
        if (!$companyProfileUser) {
            abort(404, 'Company profile not found.');
        }

        $advertisementCategories = AdvertisementCategory::all();
        $favoriteAdvertisements = collect();

        // Now that $companyProfileUser is fetched, the rest of the logic can proceed
        $query = $companyProfileUser->advertisements()
                                   ->where('status', 'active')
                                   ->filterAndSort($request);

        $advertisements = $query->paginate(10);


        if (Auth::check()) {
            $loggedInUser = Auth::user(); // Use a different variable for the logged-in user
            $favoriteAdvertisements = $loggedInUser->favoriteAdvertisements()->get();
        }

        return view('index', [
            'company' => $companyProfileUser,
            'advertisements' => $advertisements,
            'advertisementCategories' => $advertisementCategories,
            'favoriteAdvertisements' => $favoriteAdvertisements,
        ]);
    }
}
