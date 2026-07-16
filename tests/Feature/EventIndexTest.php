<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_index_can_filter_and_sort_events_from_database(): void
    {
        $club = User::create([
            'username' => 'Клуб',
            'email' => 'club@example.com',
            'password' => 'password',
            'role' => 'club',
        ]);

        $event = Event::create([
            'title' => 'Книжная встреча',
            'date' => now()->addDay()->toDateTimeString(),
            'place' => 'Казань',
            'description' => 'Обсуждение книг',
            'min_entries' => 0,
            'max_entries' => 10,
            'image' => 'events/test.jpg',
            'club_id' => $club->id,
            'author_id' => $club->id,
        ]);

        $otherEvent = Event::create([
            'title' => 'Лекция по литературе',
            'date' => now()->addDays(2)->toDateTimeString(),
            'place' => 'Москва',
            'description' => 'Лекция',
            'min_entries' => 0,
            'max_entries' => 10,
            'image' => 'events/test2.jpg',
            'club_id' => $club->id,
            'author_id' => $club->id,
        ]);

        $this->actingAs($club);

        $response = $this->get('/event-index?q=книжная&date_filter=upcoming&sort=popular');

        $response->assertOk();
        $response->assertSee('Книжная встреча');
        $response->assertDontSee('Лекция по литературе');
    }
}
