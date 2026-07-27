<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Organization;
use App\Models\Review;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Event::with('category')
            ->where('date', '>=', now())
            ->orderBy('date', 'asc');

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $events = $query->get();
        $partners = Partner::all();
        $organizations = Organization::where('status', 'active')
            ->withCount('events')
            ->orderBy('name')
            ->limit(8)
            ->get();

        // Ulasan terbaru (verified purchase only)
        $latestReviews = Review::with(['event', 'user'])
            ->where('is_verified_purchase', true)
            ->whereNotNull('review_text')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('welcome', compact('events', 'categories', 'partners', 'organizations', 'latestReviews'));
    }
}