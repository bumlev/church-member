<?php

namespace Tests\Feature;

use App\Models\MaritalStatus;
use App\Models\Member;
use App\Models\Sex;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class ExportMembersTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_exports_only_the_filtered_members_to_excel(): void
    {
        $sex = Sex::create(['name' => 'Male']);
        $maritalStatus = MaritalStatus::create(['name' => 'Single']);

        foreach (['Doe' => '1199080012345678', 'Smith' => '1199080087654321'] as $lastName => $nationalId) {
            Member::create([
                'first_name'        => 'John',
                'last_name'         => $lastName,
                'sex_id'            => $sex->id,
                'marital_status_id' => $maritalStatus->id,
                'national_id'       => $nationalId,
                'date_birthday'     => '1990-05-12',
            ]);
        }

        $response = $this->actingAs(User::factory()->create(), 'sanctum')
            ->get('/api/v1/members/export?last_name=Doe');

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringContainsString('.xlsx', $response->headers->get('Content-Disposition'));

        $path = tempnam(sys_get_temp_dir(), 'members') . '.xlsx';
        file_put_contents($path, $response->streamedContent());
        $rows = IOFactory::load($path)->getActiveSheet()->toArray();
        unlink($path);

        $this->assertCount(2, $rows, 'Expected a header row plus one matching member.');
        $this->assertSame(['ID', 'First Name', 'Last Name', 'Sex'], array_slice($rows[0], 0, 4));
        $this->assertSame(['John', 'Doe', 'Male'], array_slice($rows[1], 1, 3));
        $this->assertContains('1199080012345678', $rows[1]);
    }

    public function test_it_rejects_invalid_filters(): void
    {
        $this->actingAs(User::factory()->create(), 'sanctum')
            ->getJson('/api/v1/members/export?sex_id=999')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('sex_id.0');
    }

    public function test_it_requires_authentication(): void
    {
        $this->getJson('/api/v1/members/export')->assertUnauthorized();
    }

    public function test_it_returns_401_json_when_unauthenticated_client_accepts_xlsx(): void
    {
        $this->withHeaders(['Accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
            ->get('/api/v1/members/export')
            ->assertUnauthorized()
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
