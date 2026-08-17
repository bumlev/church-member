<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_change_their_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('old-password')]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/password/change', [
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertOk()->assertJson([
            'message' => 'Your password has been updated successfully.',
        ]);

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_it_rejects_an_incorrect_current_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('old-password')]);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/password/change', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('current_password');

        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
    }

    public function test_it_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/password/change', [
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertUnauthorized();
    }

    public function test_it_clears_must_change_password_flag(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('temp-password'),
            'must_change_password' => true,
        ]);

        $this->actingAs($user, 'sanctum')->postJson('/api/v1/password/change', [
            'current_password' => 'temp-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertOk();

        $this->assertFalse($user->fresh()->must_change_password);
    }
}
