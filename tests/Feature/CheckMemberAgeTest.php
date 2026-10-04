<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckMemberAgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_member_aged_18_is_under_19(): void
    {
        $dateBirthday = now()->subYears(19)->addDay()->toDateString();

        $this->actingAs(User::factory()->create(), 'sanctum')
            ->postJson('/api/v1/members/check-age', ['date_birthday' => $dateBirthday])
            ->assertOk()
            ->assertExactJson([
                'data' => ['date_birthday' => $dateBirthday, 'age' => 18, 'is_under_19' => true],
            ]);
    }

    public function test_a_member_turning_19_today_is_not_under_19(): void
    {
        $dateBirthday = now()->subYears(19)->toDateString();

        $this->actingAs(User::factory()->create(), 'sanctum')
            ->postJson('/api/v1/members/check-age', ['date_birthday' => $dateBirthday])
            ->assertOk()
            ->assertJsonPath('data.age', 19)
            ->assertJsonPath('data.is_under_19', false);
    }

    public function test_it_rejects_a_missing_or_invalid_date_birthday(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/members/check-age', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['date_birthday' => 'Date of birth is required.']);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/members/check-age', ['date_birthday' => now()->addDay()->toDateString()])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['date_birthday' => 'Date of birth cannot be in the future.']);
    }

    public function test_it_requires_authentication(): void
    {
        $this->postJson('/api/v1/members/check-age', ['date_birthday' => '2010-01-01'])
            ->assertUnauthorized();
    }
}
