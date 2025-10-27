<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Throwable;
use App\Enums\HttpStatusEnum;

trait HandlesApiException
{
    protected function handleApiException(Throwable $e, int $defaultStatus = null): JsonResponse
    {
        $status = $defaultStatus ?? (method_exists($e, 'getStatusCode') ? $e->getStatusCode() : HttpStatusEnum::BAD_REQUEST->value);
        return response()->json([
            'error' => $e->getMessage(),
            'exception' => class_basename($e),
        ], $status);
    }
}
