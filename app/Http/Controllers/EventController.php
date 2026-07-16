<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();
        return view('pages.start', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.event_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'place' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_entries' => 'nullable|integer|min:0',
            'max_entries' => 'nullable|integer|min:1',
            'image' => 'required|image|mimes:jpg,jpeg,webp|max:50',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = ltrim($path, '/');
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
        return view('pages.event_edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'place' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_entries' => 'nullable|integer|min:0',
            'max_entries' => 'nullable|integer|min:1',
            // image делаем nullable, чтобы можно было сохранить изменения без новой картинки
            'image' => 'nullable|image|mimes:jpg,jpeg,webp|max:50',
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
        return view('pages.event_delete', compact('event'));
    }


    public function destroy($id)
    {
        $event = Event::findOrFail($id);

       
        $event->delete();

        return redirect('/')->with('success', 'Мероприятие удалено');
    }
}