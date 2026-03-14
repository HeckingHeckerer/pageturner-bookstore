<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_factor_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/two-factor/verification');

        $response->assertStatus(200);
    }

    public function test_two_factor_settings_screen_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/two-factor/settings');

        $response->assertStatus(200);
    }

    public function test_two_factor_can_be_enabled(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->put('/two-factor/settings', [
            'two_factor_enabled' => true,
        ]);

        $response->assertRedirect('/profile');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'two_factor_enabled' => true,
        ]);
    }

    public function test_two_factor_can_be_disabled(): void
    {
        $user = User::factory()->create([
            'two_factor_enabled' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->put('/two-factor/settings', [
            'two_factor_enabled' => false,
        ]);

        $response->assertRedirect('/profile');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'two_factor_enabled' => false,
        ]);
    }
}