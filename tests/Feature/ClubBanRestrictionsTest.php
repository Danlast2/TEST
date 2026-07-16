<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

class ClubBanRestrictionsTest extends TestCase
{
    public function test_user_banned_from_a_club_cannot_participate_in_that_clubs_event(): void
    {
        $user = new User();
        $user->id = 11;
        $user->role = 'user';
        $user->club_banned = true;
        $user->club_ban_club_id = 10;

        $event = new Event();
        $event->club_id = 10;

        $this->assertFalse($user->canParticipateInClubEvent($event));
    }

    public function test_user_banned_by_other_club_can_still_participate(): void
    {
        $user = new User();
        $user->id = 11;
        $user->role = 'user';
        $user->club_banned = true;
        $user->club_ban_club_id = 20;

        $event = new Event();
        $event->club_id = 10;

        $this->assertTrue($user->canParticipateInClubEvent($event));
    }
}
