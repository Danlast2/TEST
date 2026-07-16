<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store($id)
    {
        if (! Auth::check()) {
            abort(403);
        }

        $event = Event::findOrFail($id);
        $userId = Auth::id();
        $user = Auth::user();

        if ($event->registered_count >= $event->max_entries) {
            return back()->with('error', 'Места на мероприятие закончились.');
        }

        if (! $user->canParticipateInClubEvent($event)) {
            return back()->with('error', 'Вы забанены в этом клубе и не можете записаться на его мероприятие.');
        }

        if (EventRegistration::where('user_id', $userId)->where('event_id', $id)->exists()) {
            return back()->with('error', 'Вы уже записаны на это мероприятие.');
        }

        EventRegistration::create([
            'user_id' => $userId,
            'event_id' => $id,
        ]);

        return back()->with('success', 'Вы успешно записались на мероприятие.');
    }

    public function destroy($eventId)
    {
        $event = Event::findOrFail($eventId);
        $userId = Auth::id();

        EventRegistration::where('user_id', $userId)->where('event_id', $eventId)->delete();

        return back()->with('success', 'Вы отменили запись на мероприятие.');
    }
}
