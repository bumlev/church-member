<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Safety net: re-queue pictures whose mirror job ran out of retries.
Schedule::command('pictures:sync-remote')
    ->daily()
    ->when(fn () => config('pictures.remote_enabled'));
