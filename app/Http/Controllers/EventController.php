<?php

namespace App\Http\Controllers;

use App\Enums\EventTag;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $sort = $request->input('sort', 'date');
        $selectedTags = array_values(array_filter(array_map('trim', (array) $request->input('tags', []))));

        $eventsQuery = Event::query()
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('title', 'like', '%' . $query . '%')
                        ->orWhere('place', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%')
                        ->orWhereJsonContains('tags', $query);
                });
            })
            ->when(! empty($selectedTags), function ($q) use ($selectedTags) {
                foreach ($selectedTags as $tag) {
                    $q->whereJsonContains('tags', $tag);
                }
            });

        if ($dateFrom) {
            $eventsQuery->where('date', '>=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $eventsQuery->where('date', '<=', $dateTo . ' 23:59:59');
        }

        if ($sort === 'popular') {
            $eventsQuery->withCount('registrations')->orderByDesc('registrations_count');
        } else {
            $eventsQuery->orderBy('date');
        }

        $events = $eventsQuery->get();

        return view('pages.events.event_index', compact('events', 'query', 'dateFrom', 'dateTo', 'sort', 'selectedTags'))
            ->with('availableTags', EventTag::options())
            ->with('tags', $selectedTags);
    }


        public function start(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $sort = $request->input('sort', 'date');
        $selectedTags = array_values(array_filter(array_map('trim', (array) $request->input('tags', []))));

        $eventsQuery = Event::query()
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('title', 'like', '%' . $query . '%')
                        ->orWhere('place', 'like', '%' . $query . '%')
                        ->orWhere('description', 'like', '%' . $query . '%')
                        ->orWhereJsonContains('tags', $query);
                });
            })
            ->when(! empty($selectedTags), function ($q) use ($selectedTags) {
                foreach ($selectedTags as $tag) {
                    $q->whereJsonContains('tags', $tag);
                }
            });

        if ($dateFrom) {
            $eventsQuery->where('date', '>=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $eventsQuery->where('date', '<=', $dateTo . ' 23:59:59');
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

        return view('pages.start', compact('events', 'query', 'dateFrom', 'dateTo', 'sort', 'selectedTags', 'mapMarkers'))
            ->with('availableTags', EventTag::options())
            ->with('tags', $selectedTags);
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

        return view('pages.events.event_create')->with('availableTags', EventTag::options());
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

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'place'       => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'tags'        => 'nullable|array',
            'tags.*'      => 'nullable|string|in:' . implode(',', array_map(static fn (EventTag $tag) => $tag->value, EventTag::cases())),
            'min_entries' => 'nullable|integer|min:0',
            'max_entries' => 'nullable|integer|min:1',
            'image'       => 'nullable|image|mimes:jpg,jpeg,webp|max:50',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Проверьте форму и заполните обязательные поля.');
        }

        $validated = $validator->validated();
        $validated['description'] = $validated['description'] ?? '';
        $validated['tags'] = array_values(array_filter($validated['tags'] ?? []));

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
        return view('pages.events.event_show', compact('event'));
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

        return view('pages.events.event_edit', compact('event'))->with('availableTags', EventTag::options());
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

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'place'       => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'tags'        => 'nullable|array',
            'tags.*'      => 'nullable|string|in:' . implode(',', array_map(static fn (EventTag $tag) => $tag->value, EventTag::cases())),
            'min_entries' => 'nullable|integer|min:0',
            'max_entries' => 'nullable|integer|min:1',
            'image'       => 'nullable|image|mimes:jpg,jpeg,webp|max:50',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Проверьте форму и заполните обязательные поля.');
        }

        $validated = $validator->validated();
        $validated['description'] = $validated['description'] ?? '';
        $validated['tags'] = array_values(array_filter($validated['tags'] ?? []));

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

        return view('pages.events.event_delete', compact('event'));
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