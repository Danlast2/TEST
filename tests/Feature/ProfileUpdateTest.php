<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_can_store_avatar_and_description(): void
    {
        Storage::fake('public');

        $user = User::create([
            'username' => 'Тестовый пользователь',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'user',
        ]);

        $this->actingAs($user);

        $avatar = UploadedFile::fake()->image('avatar.jpg', 200, 200);
        $description = str_repeat('А', 1000);

        $response = $this->post('/profile/update', [
            'username' => 'Новое имя',
            'email' => 'new@example.com',
            'description' => $description,
            'avatar' => $avatar,
        ]);

        $response->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Новое имя', $user->username);
        $this->assertSame('new@example.com', $user->email);
        $this->assertSame($description, $user->description);
        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }
}
