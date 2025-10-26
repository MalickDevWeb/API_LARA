<?php

namespace App\Traits;

use App\Enums\HttpStatusEnum;
use Illuminate\Http\JsonResponse;

trait ExceptionHandlerTrait
{
    public function handleException(\Exception $e): JsonResponse
    {
        return response()->json(['error' => $e->getMessage()], HttpStatusEnum::BAD_REQUEST->value);
    }
}