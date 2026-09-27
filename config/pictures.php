<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Member Pictures
    |--------------------------------------------------------------------------
    |
    | Pictures are always written to the local disk (source of truth) and,
    | when remote storage is enabled, mirrored to DigitalOcean Spaces by a
    | queued job. `serve_from` picks which copy's URL the API returns once
    | the mirror has landed: "local" or "remote".
    |
    */

    'local_disk'     => 'public',
    'remote_disk'    => 'spaces',
    'remote_enabled' => (bool) env('PICTURES_REMOTE_ENABLED', false),
    'serve_from'     => env('PICTURES_SERVE_FROM', 'local'),

];
