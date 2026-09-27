<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Services\MemberPictureService;
use Illuminate\Console\Command;

class SyncMemberPictures extends Command
{
    protected $signature = 'pictures:sync-remote';

    protected $description = 'Queue upload of member pictures not yet mirrored to DigitalOcean Spaces';

    public function handle(): int
    {
        if (!config('pictures.remote_enabled')) {
            $this->error('PICTURES_REMOTE_ENABLED is false.');

            return self::FAILURE;
        }

        $count = 0;

        Member::whereNotNull('picture')
            ->where('picture_on_remote', false)
            ->lazyById()
            ->each(function (Member $member) use (&$count) {
                MemberPictureService::mirror($member);
                $count++;
            });

        $this->info("Queued {$count} picture(s).");

        return self::SUCCESS;
    }
}
