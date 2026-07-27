<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index($slug)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $events = $org->events()->with('category')->latest()->paginate(15);
        return view('panitia.events.index', compact('org', 'events'));
    }

    public function create($slug)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $categories = Category::all();
        return view('panitia.events.create', compact('org', 'categories'));
    }

    public function store(Request $request, $slug)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload poster
        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $data['organization_id'] = $org->id;
        unset($data['poster']);

        Event::create($data);

        return redirect()->route('panitia.events.index', $org->slug)
            ->with('success', 'Event berhasil dibuat!');
    }

    public function edit($slug, $event)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $event = $org->events()->findOrFail($event);
        $categories = Category::all();
        return view('panitia.events.edit', compact('org', 'event', 'categories'));
    }

    public function update(Request $request, $slug, $event)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $event = $org->events()->findOrFail($event);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        unset($data['poster']);
        $event->update($data);

        return redirect()->route('panitia.events.index', $org->slug)
            ->with('success', 'Event berhasil diupdate!');
    }

    public function destroy($slug, $event)
    {
        $org = Organization::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $event = $org->events()->findOrFail($event);

        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return redirect()->route('panitia.events.index', $org->slug)
            ->with('success', 'Event berhasil dihapus!');
    }
}
