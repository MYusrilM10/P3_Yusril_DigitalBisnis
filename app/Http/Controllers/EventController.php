<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
class EventController extends Controller
{
    public function show(\App\Models\Event $event)
    {
        $categories = \App\Models\Category::all();
        $event->load('organization');

        $hasPurchased = auth()->check() && $event->transactions()
            ->where('user_id', auth()->id())
            ->whereIn('status', ['success', 'settlement'])
            ->exists();

        return view('event-detail', compact('categories', 'event', 'hasPurchased'));
    }

        public function checkout()
        {
            return view('checkout');
        }
}