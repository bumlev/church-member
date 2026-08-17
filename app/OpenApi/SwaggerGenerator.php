<?php

namespace App\OpenApi;

use Illuminate\Support\Arr;
use L5Swagger\Generator as L5Generator;
use OpenApi\Generator as OpenApiGenerator;

/**
 * Extends L5Swagger\Generator to suppress PHP trigger_error() warnings from swagger-php,
 * which Laravel would otherwise convert to ErrorExceptions.
 */
class SwaggerGenerator extends L5Generator
{
    protected function scanFilesForDocumentation(): static
    {
        // swagger-php calls trigger_error(E_USER_WARNING) for things like
        // "Required @OA\PathItem() not found". Laravel converts these to ErrorExceptions.
        // We temporarily install a custom error handler that routes these to the Laravel
        // log instead of re-throwing, then restore the original handler.
        $previous = set_error_handler(
            function (int $code, string $message, string $file): bool {
                if (str_contains($file, 'swagger-php') || str_contains($file, 'zircote')) {
                    \Illuminate\Support\Facades\Log::warning('[Swagger] ' . $message);
                    return true; // suppress — do not pass to PHP's error handler
                }
                return false; // let Laravel handle anything else
            },
            E_USER_WARNING | E_USER_NOTICE
        );

        try {
            parent::scanFilesForDocumentation();
        } finally {
            set_error_handler($previous);
        }

        return $this;
    }
}
