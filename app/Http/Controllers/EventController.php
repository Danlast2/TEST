<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $dateFilter = $request->input('date_filter', 'all');
        $sort = $request->input('sort', 'date');

        $eventsQuery = Event::query()
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('title', 'like', '%' . $query . '%')
                        ->orWhere('place', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%');
                });
            });

        if ($dateFilter === 'upcoming') {
            $eventsQuery->where('date', '>=', now());
        } elseif ($dateFilter === 'past') {
            $eventsQuery->where('date', '<', now());
        }

        if ($sort === 'popular') {
            $eventsQuery->withCount('registrations')->orderByDesc('registrations_count');
        } else {
            $eventsQuery->orderBy('date');
        }

        $events = $eventsQuery->get();

        return view('pages.event_index', compact('events', 'query', 'dateFilter', 'sort'));
    }


        public function start(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $dateFilter = $request->input('date_filter', 'all');
        $sort = $request->input('sort', 'date');

        $eventsQuery = Event::query()
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('title', 'like', '%' . $query . '%')
                        ->orWhere('place', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%');
                });
            });

        if ($dateFilter === 'upcoming') {
            $eventsQuery->where('date', '>=', now());
        } elseif ($dateFilter === 'past') {
            $eventsQuery->where('date', '<', now());
        }

        if ($sort === 'popular') {
            $eventsQuery->withCount('registrations')->orderByDesc('registrations_count');
        } else {
            $eventsQuery->orderBy('date');
        }

        $events = $eventsQuery->get();

        // === ДАННЫЕ ДЛЯ КАРТЫ ===
        $mapMarkers = $events->filter(function ($event) {
            return $event->latitude && $event->longitude;
        })->map(function ($event) {
            return [
                'id'        => $event->id,
                'title'     => $event->title,
                'latitude'  => $event->latitude,
                'longitude' => $event->longitude,
                'place'     => $event->place,
                'date'      => $event->date,
                'url'       => route('event.show', $event->id),
            ];
        })->values();

        return view('pages.start', compact('events', 'query', 'dateFilter', 'sort', 'mapMarkers'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (! $user || ! $user->canCreateEvents()) {
            abort(403);
        }

        return view('pages.event_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user || ! $user->canCreateEvents()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'place'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_entries' => 'nullable|integer|min:0',
            'max_entries' => 'nullable|integer|min:1',
            'image'       => 'nullable|image|mimes:jpg,jpeg,webp|max:50',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = ltrim($path, '/');
        }

        $user = auth()->user();
        if ($user && in_array($user->role, ['club', 'club_admin', 'club_moderator'], true)) {
            $validated['club_id'] = $user->club_id ?? $user->id;
            $validated['author_id'] = $user->id;
        }

        Event::create($validated);

        return redirect('/')->with('success', 'Мероприятие создано!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('pages.event_show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        if (! Auth::user() || ! Auth::user()->canManageEvent($event)) {
            abort(403);
        }

        return view('pages.event_edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        if (! Auth::user() || ! Auth::user()->canManageEvent($event)) {
            abort(403);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'place'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_entries' => 'nullable|integer|min:0',
            'max_entries' => 'nullable|integer|min:1',
            'image'       => 'nullable|image|mimes:jpg,jpeg,webp|max:50',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image_path);
            }

            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = ltrim($path, '/');
        } else {
            $validated['image'] = $event->image;
        }

        $user = auth()->user();
        if ($user && in_array($user->role, ['club', 'club_admin', 'club_moderator'], true)) {
            $validated['club_id'] = $user->club_id ?? $user->id;
            $validated['author_id'] = $user->id;
        }

        $event->update($validated);

        return redirect()->route('event.show', $event->id)->with('success', 'Мероприятие обновлено!');
    }

    public function image($path)
    {
        $filePath = storage_path('app/public/' . $path);

        if (! file_exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath);
    }

    public function delete($id){
        $event = Event::findOrFail($id);

        if (! Auth::user() || ! Auth::user()->canManageEvent($event)) {
            abort(403);
        }

        return view('pages.event_delete', compact('event'));
    }


    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if (! Auth::user() || ! Auth::user()->canManageEvent($event)) {
            abort(403);
        }

        $event->delete();

        return redirect('/')->with('success', 'Мероприятие удалено');
    }
}