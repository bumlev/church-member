<?php

namespace Tests\Feature;

use App\Models\Church;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListChurchesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_churches_ordered_by_name(): void
    {
        Church::create(['name' => 'Zion Temple', 'location' => 'Kigali']);
        Church::create(['name' => 'ADPR']);

        $this->actingAs(User::factory()->create(), 'sanctum')
            ->getJson('/api/v1/churches')
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    ['id' => 2, 'name' => 'ADPR',        'location' => null],
                    ['id' => 1, 'name' => 'Zion Temple', 'location' => 'Kigali'],
                ],
            ]);
    }

    public function test_it_requires_authentication(): void
    {
        $this->getJson('/api/v1/churches')->assertUnauthorized();
    }
}
