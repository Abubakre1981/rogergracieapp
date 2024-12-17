<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoBundle;

class VideoBundleController extends Controller
{
    /**
     * Haalt alle video bundels op.
     */
    public function index()
    {
        // Haal alle video bundels uit de database
        $bundles = VideoBundle::all();

        // Stuur een JSON-respons terug
        return response()->json($bundles);
    }
}
