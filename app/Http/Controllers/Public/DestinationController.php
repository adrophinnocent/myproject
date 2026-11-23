<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Destination;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::where('is_active', true)->get();

        return view('public.destinations.index', compact('destinations'));
    }
}
