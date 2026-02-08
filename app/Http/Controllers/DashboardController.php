<?php

namespace App\Http\Controllers;

use App\Models\Boost;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = 0; // auth()->user();

        $boosts = Boost::where('user_id', $user)->get();

        return view('dashboard', [
            'serverTimestamp' => now()->timestamp * 1000, // ms
            'boosts' => $boosts
        ]);
    }
}
