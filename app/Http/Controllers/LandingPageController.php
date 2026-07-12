<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Carbon\Carbon;

class LandingPageController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $postings = JobPosting::where('is_active', true)
            ->where(function ($query) use ($today) {
                $query->whereNull('active_until')
                    ->orWhere('active_until', '>=', $today);
            })
            ->latest()
            ->paginate(3);

        return view('landingpage', [
            'postings' => $postings,
        ]);
    }
}
