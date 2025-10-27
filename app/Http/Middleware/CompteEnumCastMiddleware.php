<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\CompteTypeEnum;
use App\Enums\DeviseEnum;
use App\Enums\StatutEnum;

class CompteEnumCastMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->method(), ['POST', 'PUT']) && $request->is('api/v1/comptes*')) {
            $data = $request->all();
            try {
                if (isset($data['type'])) {
                    $type = strtolower(trim($data['type']));
                    $request->merge(['type' => CompteTypeEnum::from($type)]);
                }
                if (isset($data['devise'])) {
                    $devise = strtoupper(trim($data['devise']));
                    $request->merge(['devise' => DeviseEnum::from($devise)]);
                }
                if (isset($data['statut'])) {
                    $statut = strtolower(trim($data['statut']));
                    $request->merge(['statut' => StatutEnum::from($statut)]);
                }
            } catch (\ValueError $e) {
                return response()->json([
                    'error' => 'Invalid enum value: ' . $e->getMessage()
                ], 400);
            }
        }
        return $next($request);
    }
}