<?php

namespace App\Services;

use App\Jobs\DeleteMemberPictureFromRemote;
use App\Jobs\SyncMemberPictureToRemote;
use App\Models\Member;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class MemberPictureService
{
    private const string DIRECTORY = 'members/pictures';

    /**
     * Saves the new picture locally, then removes the previous one (local now,
     * remote via queue). Storing first means a failed upload never leaves the
     * member without a picture.
     */
    public static function store(UploadedFile $file, ?string $previousPath = null): string
    {
        $path = Storage::disk(config('pictures.local_disk'))->putFile(self::DIRECTORY, $file);

        if ($path === false) {
            throw new RuntimeException('Unable to store member picture.');
        }

        if ($previousPath) {
            self::delete($previousPath);
        }

        return $path;
    }

    /** Queues the Spaces upload. Called once the member row exists (needs its ID). */
    public static function mirror(Member $member): void
    {
        if (self::remoteEnabled() && $member->picture && !$member->picture_on_remote) {
            SyncMemberPictureToRemote::dispatch($member->id, $member->picture)->afterCommit();
        }
    }

    public static function delete(string $path): void
    {
        Storage::disk(config('pictures.local_disk'))->delete($path);

        if (self::remoteEnabled()) {
            DeleteMemberPictureFromRemote::dispatch($path)->afterCommit();
        }
    }

    /** Remote URL once the mirror has landed, local URL otherwise (and as fallback). */
    public static function url(?string $path, bool $onRemote = false): ?string
    {
        if (!$path) {
            return null;
        }

        $disk = $onRemote && config('pictures.serve_from') === 'remote'
            ? config('pictures.remote_disk')
            : config('pictures.local_disk');

        return Storage::disk($disk)->url($path);
    }

    private static function remoteEnabled(): bool
    {
        return (bool) config('pictures.remote_enabled');
    }
}
