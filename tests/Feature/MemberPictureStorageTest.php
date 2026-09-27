<?php

namespace Tests\Feature;

use App\Jobs\DeleteMemberPictureFromRemote;
use App\Jobs\SyncMemberPictureToRemote;
use App\Models\MaritalStatus;
use App\Models\Member;
use App\Models\Sex;
use App\Models\SpiritualGift;
use App\Models\Talent;
use App\Models\User;
use App\Services\MemberPictureService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MemberPictureStorageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('spaces');
        config(['pictures.remote_enabled' => true]);
    }

    public function test_upload_saves_locally_and_queues_remote_sync(): void
    {
        Queue::fake();

        $response = $this->createMemberWithPicture();

        $response->assertCreated();
        $member = Member::findOrFail($response->json('data.id'));
        Storage::disk('public')->assertExists($member->picture);
        $this->assertFalse($member->picture_on_remote);
        Queue::assertPushed(SyncMemberPictureToRemote::class, fn ($job) => $job->path === $member->picture);
    }

    public function test_upload_does_not_queue_remote_sync_when_remote_is_disabled(): void
    {
        Queue::fake();
        config(['pictures.remote_enabled' => false]);

        $this->createMemberWithPicture()->assertCreated();

        Queue::assertNothingPushed();
    }

    public function test_replacing_picture_deletes_old_one_everywhere(): void
    {
        Queue::fake();

        $member = Member::findOrFail($this->createMemberWithPicture()->json('data.id'));
        $oldPath = $member->picture;

        $this->actingAs(User::factory()->create(), 'sanctum')
            ->put("/api/v1/members/{$member->id}", ['picture' => self::fakePicture('new.png')], ['Accept' => 'application/json'])
            ->assertOk();

        $newPath = $member->fresh()->picture;
        $this->assertNotSame($oldPath, $newPath);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($newPath);
        Queue::assertPushed(DeleteMemberPictureFromRemote::class, fn ($job) => $job->path === $oldPath);
        Queue::assertPushed(SyncMemberPictureToRemote::class, fn ($job) => $job->path === $newPath);
    }

    public function test_sync_job_uploads_and_flags_member(): void
    {
        Storage::disk('public')->put('members/pictures/a.jpg', 'img');
        $member = $this->makeMember(['picture' => 'members/pictures/a.jpg']);

        (new SyncMemberPictureToRemote($member->id, 'members/pictures/a.jpg'))->handle();

        Storage::disk('spaces')->assertExists('members/pictures/a.jpg');
        $this->assertTrue($member->fresh()->picture_on_remote);
    }

    public function test_sync_job_skips_when_local_file_already_replaced(): void
    {
        $member = $this->makeMember(['picture' => 'members/pictures/gone.jpg']);

        (new SyncMemberPictureToRemote($member->id, 'members/pictures/gone.jpg'))->handle();

        Storage::disk('spaces')->assertMissing('members/pictures/gone.jpg');
        $this->assertFalse($member->fresh()->picture_on_remote);
    }

    public function test_stale_sync_job_does_not_flag_newer_picture(): void
    {
        Storage::disk('public')->put('members/pictures/old.jpg', 'img');
        $member = $this->makeMember(['picture' => 'members/pictures/new.jpg']);

        (new SyncMemberPictureToRemote($member->id, 'members/pictures/old.jpg'))->handle();

        $this->assertFalse($member->fresh()->picture_on_remote);
    }

    public function test_delete_job_removes_remote_copy(): void
    {
        Storage::disk('spaces')->put('members/pictures/a.jpg', 'img');

        (new DeleteMemberPictureFromRemote('members/pictures/a.jpg'))->handle();

        Storage::disk('spaces')->assertMissing('members/pictures/a.jpg');
    }

    public function test_url_uses_remote_only_when_synced_and_serving_from_remote(): void
    {
        $path = 'members/pictures/a.jpg';

        config(['pictures.serve_from' => 'local']);
        $this->assertSame(Storage::disk('public')->url($path), MemberPictureService::url($path, true));

        config(['pictures.serve_from' => 'remote']);
        $this->assertSame(Storage::disk('public')->url($path), MemberPictureService::url($path, false));
        $this->assertSame(Storage::disk('spaces')->url($path), MemberPictureService::url($path, true));

        $this->assertNull(MemberPictureService::url(null, true));
    }

    public function test_sync_command_queues_unsynced_pictures(): void
    {
        Queue::fake();
        $this->makeMember(['picture' => 'members/pictures/a.jpg']);
        $this->makeMember(['picture' => 'members/pictures/b.jpg', 'picture_on_remote' => true]);
        $this->makeMember();

        $this->artisan('pictures:sync-remote')
            ->expectsOutput('Queued 1 picture(s).')
            ->assertSuccessful();

        Queue::assertPushed(SyncMemberPictureToRemote::class, 1);
        Queue::assertPushed(SyncMemberPictureToRemote::class, fn ($job) => $job->path === 'members/pictures/a.jpg');
    }

    public function test_sync_command_fails_when_remote_is_disabled(): void
    {
        config(['pictures.remote_enabled' => false]);

        $this->artisan('pictures:sync-remote')->assertFailed();
    }

    private function createMemberWithPicture()
    {
        return $this->actingAs(User::factory()->create(), 'sanctum')
            ->post('/api/v1/members', [
                'first_name'        => 'John',
                'last_name'         => 'Doe',
                'sex_id'            => Sex::create(['name' => 'Male'])->id,
                'marital_status_id' => MaritalStatus::create(['name' => 'Single'])->id,
                'date_birthday'     => '1990-05-12',
                'talent'            => [Talent::create(['name' => 'Singing'])->id],
                'spiritual_gift'    => [SpiritualGift::create(['name' => 'Teaching'])->id],
                'picture'           => self::fakePicture('me.png'),
            ], ['Accept' => 'application/json']);
    }

    /** A real 1x1 PNG, so the `image` rule passes without needing the GD extension. */
    private static function fakePicture(string $name): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            $name,
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='),
        );
    }

    private static int $memberCount = 0;

    private function makeMember(array $attributes = []): Member
    {
        $sex = Sex::firstOrCreate(['name' => 'Male']);
        $maritalStatus = MaritalStatus::firstOrCreate(['name' => 'Single']);

        return Member::create([
            'first_name'        => 'John',
            'last_name'         => 'Doe' . ++self::$memberCount,
            'sex_id'            => $sex->id,
            'marital_status_id' => $maritalStatus->id,
            'date_birthday'     => '1990-05-12',
            ...$attributes,
        ]);
    }
}
