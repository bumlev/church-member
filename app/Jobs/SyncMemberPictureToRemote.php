<?php

namespace App\Jobs;

use App\Models\Member;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class SyncMemberPictureToRemote implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public array $backoff = [10, 60, 300, 900];

    public function __construct(public int $memberId, public string $path)
    {
    }

    public function handle(): void
    {
        $local = Storage::disk(config('pictures.local_disk'));

        // The picture was replaced/deleted before this job ran — nothing to mirror.
        if (!$local->exists($this->path)) {
            return;
        }

        $stream = $local->readStream($this->path);

        try {
            Storage::disk(config('pictures.remote_disk'))->writeStream($this->path, $stream, [
                'visibility'   => 'public',
                'ContentType'  => $local->mimeType($this->path) ?: 'application/octet-stream',
                // Filenames are random, so the object is safe to cache forever.
                'CacheControl' => 'public, max-age=31536000, immutable',
            ]);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        // Guard on `picture = path` so a stale job never flags a newer picture as synced.
        Member::whereKey($this->memberId)
            ->where('picture', $this->path)
            ->update(['picture_on_remote' => true]);
    }
}
