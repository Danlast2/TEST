<?php

namespace Tests\Feature;

use App\Models\BookExchange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookExchangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_an_exchange_without_date(): void
    {
        $user = User::create([
            'username' => 'owner',
            'email' => 'owner@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('exchange.store'), [
            'title' => 'Война и мир',
            'description' => 'Готов обменять',
            'place' => 'Казань',
            'contacts' => 'Телефон: 123',
            'latitude' => 55.7887,
            'longitude' => 49.1221,
        ]);

        $response->assertRedirect(route('exchange.index'));
        $this->assertDatabaseHas('book_exchanges', [
            'user_id' => $user->id,
            'title' => 'Война и мир',
            'date' => null,
        ]);
    }

    public function test_user_can_book_an_exchange_and_status_updates(): void
    {
        $owner = User::create([
            'username' => 'owner',
            'email' => 'owner@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);

        $booker = User::create([
            'username' => 'booker',
            'email' => 'booker@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);

        $exchange = BookExchange::create([
            'user_id' => $owner->id,
            'title' => 'Война и мир',
            'description' => 'Готов обменять',
            'place' => 'Казань',
            'date' => now()->addDay()->toDateTimeString(),
            'contacts' => 'Телефон: 123',
            'latitude' => 55.7887,
            'longitude' => 49.1221,
        ]);

        $this->actingAs($booker);

        $response = $this->post(route('exchange.book', $exchange->id));

        $response->assertRedirect();
        $exchange->refresh();
        $this->assertSame('booked', $exchange->status);
        $this->assertSame($booker->id, $exchange->booked_by_user_id);
    }
}
