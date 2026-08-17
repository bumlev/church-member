<?php

namespace App\OpenApi;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Illuminate\Support\Facades\Log;

/**
 * Custom logger for swagger-php that routes messages to Laravel's log
 * instead of triggering PHP errors (which Laravel converts to exceptions).
 */
class SwaggerLogger extends AbstractLogger
{
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        match ($level) {
            LogLevel::ERROR, LogLevel::CRITICAL, LogLevel::ALERT, LogLevel::EMERGENCY
                => Log::error('[Swagger] ' . $message, $context),
            LogLevel::WARNING
                => Log::warning('[Swagger] ' . $message, $context),
            default
                => Log::debug('[Swagger] ' . $message, $context),
        };
    }
}

