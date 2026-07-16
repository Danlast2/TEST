<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FavoriteController extends Controller
{
    public function store($id){
        $event = Event::findOrFail($id);
        $userId = Auth::id();
        Favorite::create([
            'user_id' => $userId,
            'event_id' => $id,
        ]);

        return back()->with('success', 'Добавлено в избранное');
    }

    public function destroy($eventId){
        $event = Event::findOrFail($eventId);
        $userId = Auth::id();
        Favorite::where('user_id', $userId)->where('event_id', $eventId)->delete();

        return back()->with('success', 'Удалено из избранное');
    }
}
