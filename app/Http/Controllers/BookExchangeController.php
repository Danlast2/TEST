<?php

namespace App\Http\Controllers;

use App\Models\BookExchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookExchangeController extends Controller
{
    public function index()
    {
        $exchanges = BookExchange::with(['user', 'bookedByUser'])->latest()->get();

        return view('pages.books.exchange_index', compact('exchanges'));
    }

    public function create()
    {
        return view('pages.books.exchange_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'place' => 'required|string|max:255',
            'date' => 'nullable|date',
            'contacts' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'active';

        BookExchange::create($validated);

        return redirect()->route('exchange.index')->with('success', 'Объявление создано');
    }

    public function show(BookExchange $exchange)
    {
        return view('pages.books.exchange_show', compact('exchange'));
    }

    public function edit(BookExchange $exchange)
    {
        abort_unless($exchange->canBeManagedBy(Auth::user()), 403);

        return view('pages.books.exchange_edit', compact('exchange'));
    }

    public function update(Request $request, BookExchange $exchange)
    {
        abort_unless($exchange->canBeManagedBy(Auth::user()), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'place' => 'required|string|max:255',
            'date' => 'required|date',
            'contacts' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $exchange->update($validated);

        return redirect()->route('exchange.show', $exchange)->with('success', 'Объявление обновлено');
    }

    public function delete(BookExchange $exchange)
    {
        abort_unless($exchange->canBeManagedBy(Auth::user()), 403);

        return view('pages.books.exchange_delete', compact('exchange'));
    }

    public function destroy(BookExchange $exchange)
    {
        if (! Auth::user() || (! $exchange->canBeManagedBy(Auth::user()) && ! in_array(Auth::user()->role, ['admin', 'moderator'], true))) {
            abort(403);
        }

        $exchange->delete();

        return redirect()->route('exchange.index')->with('success', 'Объявление удалено');
    }

    public function book(BookExchange $exchange)
    {
        if (! Auth::check()) {
            abort(403);
        }

        if ($exchange->status === 'booked') {
            return back()->with('error', 'Книга уже забронирована');
        }

        $exchange->update([
            'status' => 'booked',
            'booked_by_user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Книга забронирована');
    }
}
