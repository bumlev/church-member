<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class DeleteMemberPictureFromRemote implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public array $backoff = [10, 60, 300, 900];

    public function __construct(public string $path)
    {
    }

    public function handle(): void
    {
        // S3 DELETE is idempotent — deleting a missing object is not an error.
        Storage::disk(config('pictures.remote_disk'))->delete($this->path);
    }
}
