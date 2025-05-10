<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index() {
        $advertisements = Advertisement::all();

        return view('index', [
            'advertisements' => $advertisements
        ]);
    }
}
